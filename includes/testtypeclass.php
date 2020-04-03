<?php

class testTypeClass {

/* Call Syntax
$testDetect = new testTypeClass;
$test = "Test de Danse Senior Bronze";
echo $testDetect->getDetectCode($test)."<br/>";

if ($testDetect->validate($line)) echo $testDetect->rowerr()." : ".$testDetect->errmsg()."<br/>\n";

*/

private $rowname = "";
private $error = "";
private $testorder = array();
private $testtime = array();
private $testmaxgroup = array();
private $testcount = array();

public $testcode = "";
public $skatingarray = array();
public $testdates = array();

//Obtient les officiels distincts
public $officials = array();
public $officialsindex = array();
public $testbyofficial = array();

function stripAccents($string){
	return strtr($string,
           'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
           'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

function loadTestOrder($filename){
    $fh = fopen($filename, "r") or die("can't open file for reading ".$filename);
    while(!feof($fh) ){
        $line=fgets($fh);
        if ($line===false) break;
        $values = explode(";",$line);
        $this->testorder[$values[0]] = trim($values[1]);
        $this->testmaxgroup[$values[0]] = trim($values[2]);
        $this->testtime[$values[0]] = (int)(trim($values[3]));
        $this->testwarmup[$values[0]] = (int)(trim($values[4]));
        $this->testcount[$values[0]] = 0;
    }
    //var_dump($this->testorder);
}

function loadOfficials($officialsplit){

	$i = 0;
	foreach ($this->skatingarray as $key => $testobj){
		
		$values = explode("\t", $testobj->line);
		
		$tmp = mb_strtolower($values[8], 'ISO-8859-1'); //officialname
		$key = stripAccents(trim($tmp)."_"); //underscore for blank officialname
		if (!array_key_exists ( $key, $this->officials )){
			$this->officials[$key] = $values[8];
			$this->officialsindex[$i] = $key;
			$this->testbyofficial[$key] = array();
			$i++;
		}
		//array_push($sortedtests, $testobj->line);
	}

	$nbrofficial = count($this->officials);

	//Cas spécifique pour la répartion en alternance des évaluateurs
	//lorsque certains tests laissé à blanc comme évaluateur. => on ne considère pas les blancs comme évaluateur.
	//par contre si aucun évaluateur (tous à blanc) alors on le considère comme 1 évaluateur pour sortir les sommaires à un seul éval. à blanc.
	if ($officialsplit>0 && $nbrofficial>1 && array_key_exists("_", $this->officials)){
		//echo "Cas spécifique<br>";
		unset($this->officials["_"]); //enlève l'évaluateur à blanc
		for ($i=0;$i<count($this->officialsindex);$i++){
			if ($this->officialsindex[$i] == "_"){
				unset($this->officialsindex[$i]); //enlève l'évaluateur à blanc
				//echo "remove $i<br>";
				break;
			}
		}

		//repositione l'index en copiant
		$officialsindex2 = array();
		$i = 0;
		foreach ($this->officialsindex as $offkey => $valkey){
			$officialsindex2[$i] = $valkey;
			$i++;
		}

		//recopie dans $officialisindex
		$this->officialsindex = array();
		$i = 0;
		foreach ($officialsindex2 as $offkey => $valkey){
			$this->officialsindex[$i] = $valkey;
			$i++;
		}
	}

	//On sépare par officiel
	$nbrofficial = count($this->officials);
	$i = 0;
	foreach ($this->skatingarray as $key => $skatingobj){
		
		if ($officialsplit==0){ //répartion selon fichier
			$tmp = mb_strtolower($skatingobj->officialname, 'ISO-8859-1');
			$key = stripAccents(trim($tmp)."_");
		}
		else { //répartion à chacun leur tour
			$index = $i % $nbrofficial; //Modulo
			$key = $this->officialsindex[$index];
			//echo "\$index=$index \$key=$key<br>";
		}
		array_push($this->testbyofficial[$key], $skatingobj);
		//echo "\$key=$key $skatingobj->lastname<br>";
		$i++;
	}

	//var_dump($officials);
	//echo "<br><br>";
	//var_dump($officialsindex);
	//echo "<br><br>";
	return $nbrofficial;
}

function importTests($panelfilename){

    $rawdata = "";
    
    $fh = fopen($panelfilename, "r") or die("can't open file for reading ".$panelfilename);
    while( !feof($fh) ){
        $line=fgets($fh);
        if ($line===false) break;
        $rawdata .= $line;

    }
    fclose ($fh);
    
    $csvdata = explode("\r\n", $rawdata);
    $fh = fopen($panelfilename, "w") or die("can't open file for writing".$panelfilename);

    $errmsg = "";
    $errcount=0;
    $rowcount=1;
    $excelrowcount=2;
    foreach ($csvdata as $line) {

        $row = explode("\t", $line);
        if (sizeof($row) > 1 && strlen($line) > 25){ //Skip empty row or row with some whitespace

            //echo "$line<br/>";
            $excelheader = mb_strtolower(trim($row[0]), 'ISO-8859-1');
            if ($excelheader=="nom de famille") {
            
                //Skip first line header
                $excelrowcount=1;
            }
            elseif (sizeof($row)!=14){ //Field count validation
                
                $errmsg .= "&nbsp;&nbsp;<span style=\"color:blue;\">Ligne #$excelrowcount</span> ";
                for ($i=0; $i<sizeof($row); $i++) {
                    //if ($i == 2) $errmsg .= "&nbsp;(";
                    //if ($i == 3 || $i == 4) $errmsg .= ",";
                    if ($i > 2) break;
                    $errmsg .= "&nbsp;$row[$i]";
                }
                $errmsg .= "&nbsp;... ".
                        "<span style=\"color:red;\">Il manque des colonnes!</span></br>\n";
                $errcount++;
            }
            elseif ($this->validate($line)) {
                $errmsg .= "&nbsp;&nbsp;<span style=\"color:blue;\">Ligne #$excelrowcount</span> (".$row[0]." ".$row[1].") ";
                $errmsg .= "<span style=\"color:red;\">".$this->error."</span></br>\n";
                $errcount++;
            }
            else{ //Ligne de test valide

                //var_dump($row);
                $this->instantiate($line, $rowcount);

                //save only valid in tmp file
                fwrite($fh, $this->cleanup($line)."\r\n");
                
                $rowcount++;
            }
        }
        $excelrowcount++; //must include empty line
        //echo "$rowcount $line<br>";
    }
    fclose ($fh);

    if ($rowcount<=1 || $errcount>0){
        //Delete partial test file
        //unlink($panelfilename);
    }
    //var_dump($this->testcount);

    return $errmsg;
}

function loadTestsList($panelfilename){

    $fh = fopen($panelfilename, "r") or die("can't open file for reading ".$panelfilename);
    $rowcount=1;
    while( !feof($fh) ){
        $line=fgets($fh);
        if ($line===false) break;

        $this->instantiate($line, $rowcount);
        $rowcount++;
    }
    fclose ($fh);

    //Trier tjrs les journées de tests
    sort($this->testdates);
}

function loadTestsListNoStar($panelfilename){

    $fh = fopen($panelfilename, "r") or die("can't open file for reading ".$panelfilename);
    $rowcount=1;
    while( !feof($fh) ){
        $line=fgets($fh);
        if ($line===false) break;
        //var_dump($line);
        if (stripos($line, "\tstar ") === false) //Filtre les Star 1 à 5
        {
            $this->instantiate($line, $rowcount);
            $rowcount++;
        }
    }
    fclose ($fh);

    //Trier tjrs les journées de tests
    sort($this->testdates);

}

function sortTestsList(){
    //Sort array by key
    ksort($this->skatingarray,SORT_NUMERIC); 
}


function instantiate($line, $rowcount){

    $skatetest = new skatingTest;
    $skatetest->loadTest($line);

    $patcancode = $this->getPatCanadaCode($skatetest->discipline." - ".$skatetest->level." - ".$skatetest->activity);
    $detectcode = $this->testcode;
    $orderno = $this->getTestOrder($patcancode);
    $maxgroup = $this->getTestMaxGroup($patcancode);
    $warmup = $this->getTestWarmup($patcancode);
    $duration = $this->getTestTime($patcancode);
    $skatetest->setCalculatedValues($detectcode,$patcancode,$orderno,$warmup,$maxgroup,$duration);
    
    $testdatetrimed = str_replace("-", "", $skatetest->testdate);
    
    $key = (int)$testdatetrimed * 1000000 + (int)$orderno * 1000 + $rowcount;
    $this->skatingarray[(string)$key] = $skatetest;
    //$this->skatingarray[$key]->var_dump();
    //var_dump($skatetest);
    //echo $skatetest->var_dump();
        
    //collect distinct test dates
    if (array_key_exists($skatetest->testdate, $this->testdates)==false) {
        $this->testdates[$skatetest->testdate] = $skatetest->testdate;
    }
    //compte le nbr de tests identique
    $this->testcount[$patcancode] = $this->testcount[$patcancode] + 1;
}

function getTestOrder($key){
    return $this->testorder[$key];
}

function getTestTime($key){
    return $this->testtime[$key];
}

function getTestMaxGroup($key){
    return $this->testmaxgroup[$key];
}

function getTestWarmup($key){
    return $this->testwarmup[$key];
}

function getTestCount($key){
    return $this->testcount[$key];
}

function isLastOfGroup($key, $count){

    $result = false;

    $maxgroup = $this->testmaxgroup[$key];
    //var_dump($key);
   // var_dump($this->testcount[$key]);
   // var_dump($maxgroup);
   // echo "<br>";
   // var_dump($this->testcount);
  //  echo "<br>";
    $restanttotal = $this->testcount[$key] % $maxgroup;
    $remaining = $this->testcount[$key] - $count;
    $cuttwolastgrp = $maxgroup + $restanttotal;

    $dmp = "<tr><td>\$key=$key  \$this->testcount[$key]=".$this->testcount[$key]."  \$count=$count</td></tr>";
    $dmp .= "<tr><td>\$maxgroup=$maxgroup  \$restanttotal=$restanttotal  \$remaining=$remaining  \$cuttwolastgrp=$cuttwolastgrp</td></tr>";
    //echo $dmp;

    if ($remaining >= $cuttwolastgrp || $restanttotal == 0){
        $modulo = $count % $maxgroup;
        if ($modulo == 0) $result = true;
        $dmp .= "<tr><td>\$modulo=$modulo</td></tr>";
    }
    else {
        //La dernière coupure est la moitier des 2 derniers groupes
        if (($cuttwolastgrp % 2) == 1) $lastcut = ($cuttwolastgrp - 1) / 2; //Si impair mettre pair
        else $lastcut = $cuttwolastgrp / 2;

        if ($remaining == $lastcut) $result = true;
        $dmp .= "<tr><td>\$lastcut=$lastcut</td></tr>";
    }
    
    return $result;
}

function getDetectCode($str){

    $str1 = mb_strtolower($str, 'ISO-8859-1');
    $str2 = $this->stripAccents($str1);

    $type = $this->detectType($str2);
    $level = $this->detectLevel($str2);
    $medal = $this->detectMedal($str2);
    $part = $this->detectPart($str2);

    $tstCode = "$type-$level$medal-$part";
    $this->testcode = rtrim($tstCode, "-");
    return $this->testcode;
}

function getPatCanadaCode($txt){

    $patCanCode = "<div class=\"shade\">Code de test</div>";
    $str1 = $this->getDetectCode($txt);
    
    if ($str1 == "H-P") $patCanCode = "PRSS";
    elseif ($str1 == "H-JB") $patCanCode = "JBSS";
    elseif ($str1 == "H-JA") $patCanCode = "JSSS";
    elseif ($str1 == "H-SB") $patCanCode = "SBSS";
    elseif ($str1 == "H-SA") $patCanCode = "SSSS";
    elseif ($str1 == "H-O") $patCanCode = "GDSS";

    elseif ($str1 == "SL-P-E") $patCanCode = "PRE1";
    elseif ($str1 == "SL-P-P") $patCanCode = "PRP2";
    elseif ($str1 == "SL-JB-E") $patCanCode = "JBE1";
    elseif ($str1 == "SL-JB-P") $patCanCode = "JBP2";
    elseif ($str1 == "SL-JA-E") $patCanCode = "JSE1";
    elseif ($str1 == "SL-JA-P") $patCanCode = "JSP2";
    elseif ($str1 == "SL-SB-E") $patCanCode = "SBE1";
    elseif ($str1 == "SL-SB-P") $patCanCode = "SBP2";
    elseif ($str1 == "SL-SA-E") $patCanCode = "SSE1";
    elseif ($str1 == "SL-SA-P") $patCanCode = "SSP2";
    elseif ($str1 == "SL-O-E") $patCanCode = "GDE1";
    elseif ($str1 == "SL-O-P") $patCanCode = "GDP2";

    elseif ($str1 == "I-SI") $patCanCode = "IIS";
    elseif ($str1 == "I-SB") $patCanCode = "BIS";
    elseif ($str1 == "I-SA") $patCanCode = "SIS";
    elseif ($str1 == "I-SO") $patCanCode = "GIS";
    elseif ($str1 == "I-DI") $patCanCode = "IIC";
    elseif ($str1 == "I-DB") $patCanCode = "BIC";
    elseif ($str1 == "I-DA") $patCanCode = "SIC";
    elseif ($str1 == "I-DO") $patCanCode = "GIC";
    
    elseif ($str1 == "SL-ST1") $patCanCode = "S1FS";
    elseif ($str1 == "SL-ST2-E") $patCanCode = "S2FSE";
    elseif ($str1 == "SL-ST2-P") $patCanCode = "S2FSP";
    elseif ($str1 == "SL-ST3-E") $patCanCode = "S3FSE";
    elseif ($str1 == "SL-ST3-P") $patCanCode = "S3FSP";
    elseif ($str1 == "SL-ST4-E") $patCanCode = "S4FSE";
    elseif ($str1 == "SL-ST4-P") $patCanCode = "S4FSP";
    elseif ($str1 == "SL-ST5-E") $patCanCode = "S5FSE";
    elseif ($str1 == "SL-ST5-P") $patCanCode = "S5FSP";
    elseif ($str1 == "H-ST1") $patCanCode = "S1SS";
    elseif ($str1 == "H-ST2") $patCanCode = "S2SS";
    elseif ($str1 == "H-ST3") $patCanCode = "S3SS";
    elseif ($str1 == "H-ST4") $patCanCode = "S4SS";
    elseif ($str1 == "H-ST5") $patCanCode = "S5SS";

    elseif ($str1[0] == 'D') $patCanCode = $this->getPatCanadaDanceCode($txt);

    //echo "$txt, $str1, \$patCanCode=$patCanCode<br/>";
    return $patCanCode;
}

function getPatCanadaDanceCode($txt){

    $patCanDanceCode = "";
    $str1 = mb_strtolower($txt, 'ISO-8859-1');
    $str2 = $this->stripAccents($str1);

    $star1 = stripos($str2, ' 1');
    $star2a = stripos($str2, ' 2a');
    $star2b = stripos($str2, ' 2b');
    $star3a = stripos($str2, ' 3a');
    $star3b = stripos($str2, ' 3b');
    $star4a = stripos($str2, ' 4a');
    $star4b = stripos($str2, ' 4b');
    $star5a = stripos($str2, ' 5a');
    $star5b = stripos($str2, ' 5b');
    
    $pr1 = stripos($str2, 'hollandaise');
    $pr2 = stripos($str2, 'dutch');
    $pr3 = stripos($str2, 'canasta');
    $pr4 = stripos($str2, 'baby blues'); 
    $pr5 = stripos($str2, 'creative prel'); 

    $jb1 = stripos($str2, 'swing');
    $jb2 = stripos($str2, 'fiesta');
    $jb3 = stripos($str2, 'willow');

    $sb1 = stripos($str2, 'ten-fox');
    $sb2 = stripos($str2, 'fourteenstep');
    $sb3 = stripos($str2, 'europeenne');
    $sb4 = stripos($str2, 'creative bronze'); 

    $ja1 = stripos($str2, 'de keats');
    $ja2 = stripos($str2, 'harris');
    $ja3 = stripos($str2, 'americaine');
    $ja4 = stripos($str2, 'rocker fox-trot');

    $sa1 = stripos($str2, 'paso doble');
    $sa2 = stripos($str2, 'starlight');
    $sa3 = stripos($str2, 'blues');
    $sa4 = stripos($str2, 'kilian');
    $sa5 = stripos($str2, 'congelado');
    $sa6 = stripos($str2, 'creative argent'); 

    $or1 = stripos($str2, 'viennoise');
    $or2 = stripos($str2, 'westminster');
    $or3 = stripos($str2, 'quickstep');
    $or4 = stripos($str2, 'argentin');
    $or5 = stripos($str2, 'samba arg');
    $or6 = stripos($str2, 'creative or'); 

    $di1 = stripos($str2, 'ravensburger');
    $di2 = stripos($str2, 'autrich');
    $di3 = stripos($str2, 'romantica');
    $di4 = stripos($str2, 'valse or');
    $di5 = stripos($str2, 'yankee');
    $di6 = stripos($str2, 'rhumba');

    if ($star1 !== false) $patCanDanceCode = 'S1D';
    elseif ($star2a !== false) $patCanDanceCode = 'S2aD';
    elseif ($star2b !== false) $patCanDanceCode = 'S2bD';
    elseif ($star3a !== false) $patCanDanceCode = 'S3aD';
    elseif ($star3b !== false) $patCanDanceCode = 'S3bD';
    elseif ($star4a !== false) $patCanDanceCode = 'S4aD';
    elseif ($star4b !== false) $patCanDanceCode = 'S4bD';
    elseif ($star5a !== false) $patCanDanceCode = 'S5aD';
    elseif ($star5b !== false) $patCanDanceCode = 'S5bD';

    elseif ($pr1 !== false) $patCanDanceCode = 'DUT';
    elseif ($pr2 !== false) $patCanDanceCode = 'DUT'; // 2 noms possible pour la dutch
    elseif ($pr3 !== false) $patCanDanceCode = 'CAN';
    elseif ($pr4 !== false) $patCanDanceCode = 'BAB';
    elseif ($pr5 !== false) $patCanDanceCode = 'PCD';

    elseif ($jb1 !== false) $patCanDanceCode = 'SWI';
    elseif ($jb2 !== false) $patCanDanceCode = 'FIE';
    elseif ($jb3 !== false) $patCanDanceCode = 'WIL';
    
    elseif ($sb1 !== false) $patCanDanceCode = 'TEN';
    elseif ($sb2 !== false) $patCanDanceCode = 'FOU';
    elseif ($sb3 !== false) $patCanDanceCode = 'EUR';
    elseif ($sb4 !== false) $patCanDanceCode = 'BCD';
    
    elseif ($ja1 !== false) $patCanDanceCode = 'KEA';
    elseif ($ja2 !== false) $patCanDanceCode = 'HAR';
    elseif ($ja3 !== false) $patCanDanceCode = 'AME';
    elseif ($ja4 !== false) $patCanDanceCode = 'ROC';
    
    elseif ($sa1 !== false) $patCanDanceCode = 'PAS';
    elseif ($sa2 !== false) $patCanDanceCode = 'STA';
    elseif ($sa3 !== false && $pr3 === false) $patCanDanceCode = 'BLU'; //On doit différencier la "blues" de la "baby blues"
    elseif ($sa4 !== false) $patCanDanceCode = 'KIL';
    elseif ($sa5 !== false) $patCanDanceCode = 'CHA';
    elseif ($sa6 !== false) $patCanDanceCode = 'SCD';
    
    elseif ($or1 !== false) $patCanDanceCode = 'VIE';
    elseif ($or2 !== false) $patCanDanceCode = 'WES';
    elseif ($or3 !== false) $patCanDanceCode = 'QUI';
    elseif ($or4 !== false) $patCanDanceCode = 'ARG';
    elseif ($or5 !== false) $patCanDanceCode = 'SAM';
    elseif ($pr5 !== false) $patCanDanceCode = 'GCD';
    
    elseif ($di1 !== false) $patCanDanceCode = 'RAV';
    elseif ($di2 !== false) $patCanDanceCode = 'AUS';
    elseif ($di3 !== false) $patCanDanceCode = 'TAN';
    elseif ($di4 !== false) $patCanDanceCode = 'GOL';
    elseif ($di5 !== false) $patCanDanceCode = 'YAN';
    elseif ($di6 !== false) $patCanDanceCode = 'RHU';

    //echo "$txt : \$patCanDanceCode=$patCanDanceCode<br/>";
    return $patCanDanceCode;
}

function getPassingTestTitle($str,$testType,$lang){
    $ReturnTitle = "";
    $fields = explode(" - ", $str);

    switch($testType[0]){
    case 'D' :
        $ReturnTitle = mb_strtoupper(trim($fields[2]),'ISO-8859-1');
        break;
    case 'S' :
        if ($testType[5] == 'E' || $testType[6] == 'E'){
            if ($lang=='En') $ReturnTitle = 'PART 1: ELEMENTS';
            else $ReturnTitle = 'PARTIE 1 : ÉLÉMENTS';
        }
        else {
            if ($lang=='En') $ReturnTitle = 'PART 2: PROGRAM';
            else $ReturnTitle = 'PARTIE 2 : PROGRAMME';
        }
        break;
    case 'H' :
            $ReturnTitle = mb_strtoupper(trim($fields[1]),'ISO-8859-1');
        break;
    default :
        $ReturnTitle = '';
        break;
    }
    return $ReturnTitle;
}


function getInterpretationName($str){

    $InterpretationName = "";
    $fields = explode(" - ", $str);

    if (sizeof($fields)>=3) $InterpretationName = mb_strtoupper(trim($fields[1]), 'ISO-8859-1');

    return $InterpretationName;
}

function detectLevel($txt){

    $testLevel = "";
    $p = stripos($txt, 'preliminaire');
    $j = stripos($txt, 'junior');
    $jr = stripos($txt, ' jr');
    $m = stripos($txt, 'senior');
    $mr = stripos($txt, ' sr');
    $s = stripos($txt, 'simple');
    $d = stripos($txt, 'double');
    $c = stripos($txt, 'couple');
    $st = stripos($txt, 'star');

    if ($p !== false) $testLevel = 'P';
    elseif ($j !== false) $testLevel = 'J';
    elseif ($m !== false) $testLevel = 'S';
    elseif ($s !== false) $testLevel = 'S';
    elseif ($d !== false) $testLevel = 'D';
    elseif ($c !== false) $testLevel = 'D';
    elseif ($jr !== false) $testLevel = 'J';
    elseif ($mr !== false) $testLevel = 'S';
    elseif ($st !== false) $testLevel = 'ST';

    return $testLevel;
}

function detectType($txt){

    $testType = "";
    $d = stripos($txt, 'danse');
    $h = stripos($txt, 'habile');
    $l = stripos($txt, 'libre');
    $i = stripos($txt, 'interpre');
    if ($d !== false) $testType = 'D';
    elseif ($h !== false) $testType = 'H';
    elseif ($l !== false) $testType = 'SL';
    elseif ($i !== false) $testType = 'I';

    return $testType;
}

function detectMedal($txt){

    $testMedal = "";
    $i = stripos($txt, ' intro');
    $b = stripos($txt, ' bronze');
    $a = stripos($txt, ' argent');
    $o = stripos($txt, ' or');
    $d = stripos($txt, ' diamant');
    $st1 = stripos($txt, ' 1');
    $st2 = stripos($txt, ' 2');
    $st3 = stripos($txt, ' 3');
    $st4 = stripos($txt, ' 4');
    $st5 = stripos($txt, ' 5');

    if ($i !== false) $testMedal = 'I';
    elseif ($b !== false) $testMedal = 'B';
    elseif ($a !== false) $testMedal = 'A';
    elseif ($o !== false) $testMedal = 'O';
    elseif ($d !== false) $testMedal = 'D';
    elseif ($d !== false) $testMedal = 'D';
    elseif ($st1 !== false) $testMedal = '1';
    elseif ($st2 !== false) $testMedal = '2';
    elseif ($st3 !== false) $testMedal = '3';
    elseif ($st4 !== false) $testMedal = '4';
    elseif ($st5 !== false) $testMedal = '5';

    return $testMedal;
}

function detectPart($txt){

    $testPart = "";
    $e = stripos($txt, 'element');
    $s = stripos($txt, ' solo');
    $p = stripos($txt, ' program');
    $star2a = stripos($txt, '2a');
    $star2b = stripos($txt, '2b');
    $star3a = stripos($txt, '3a');
    $star3b = stripos($txt, '3b');
    $star4a = stripos($txt, '4a');
    $star4b = stripos($txt, '4b');
    $star5a = stripos($txt, '5a');
    $star5b = stripos($txt, '5b');
    
    if ($e !== false) $testPart = 'E';
    elseif ($s !== false) $testPart = 'P';
    elseif ($p !== false) $testPart = 'P';
    elseif ($star2a !== false) $testPart = 'a';
    elseif ($star2b !== false) $testPart = 'b';
    elseif ($star3a !== false) $testPart = 'a';
    elseif ($star3b !== false) $testPart = 'b';
    elseif ($star4a !== false) $testPart = 'a';
    elseif ($star4b !== false) $testPart = 'b';
    elseif ($star5a !== false) $testPart = 'a';
    elseif ($star5b !== false) $testPart = 'b';

    return $testPart;
}

function validate($line){
    $result = false;

    $row = explode("\t", $line);
    $this->rowname = $row[0]." ".$row[1];
    if ($this->checkTestType($row[2])) $result = true;
    elseif ($this->checkTestLevelMedal($row[3])) $result = true;
    else{
        $txt = trim($row[2]);
        $str = $this->stripAccents(mb_strtolower($txt, 'ISO-8859-1'));
        $type = $this->detectType($str);
        if($this->checkTestPart($type, $row[3], $row[4])) $result = true;
    }
    //echo "validate=$this->rowname<br>";
    return $result;
}

function checkTestType($testType) {
    $result = true;

    $str = $this->stripAccents(mb_strtolower(trim($testType), 'ISO-8859-1'));

    $d = stripos($str, 'danse');
    $h = stripos($str, 'habile');
    $l = stripos($str, 'libre');
    $i = stripos($str, 'interpre');

    if ($d !== false) $result = false;
    elseif ($h !== false) $result = false;
    elseif ($l !== false) $result = false;
    elseif ($i !== false) $result = false;
    $this->error = "Discipline du test non reconnaissable [$testType] !";
    if (strlen($testType)==0) $this->error = "Discipline du test vide !";

    //echo "checkTestType=$result ";
    return $result;
}

function checkTestLevelMedal($medal) {
    $result = true;

    $str = $this->stripAccents(mb_strtolower(trim($medal), 'ISO-8859-1'));

    $j = stripos($str, 'junior');
    $jr = stripos($str, ' jr');
    $m = stripos($str, 'senior');
    $mr = stripos($str, ' sr');

    $p = stripos($str, 'preliminaire');

    $b = stripos($str, 'bronze');
    $a = stripos($str, 'argent');
    
    if (strlen($medal)==2) $o = stripos($str, 'or');
    else $o = false;
    
    $d = stripos($str, 'diamant');
    $i = stripos($str, 'intro');

    $st = stripos($str, 'star');

    //echo "\$p=$p \$b=$b \$a=$a \$o=$o \$d=$d \$i=$i \$j=$j \$jr=$jr \$m=$m \$mr=$mr \$s=$s \$c=$c \$db=$db<br/>";
    if ($p !== false ) $result = false;
    elseif (($j !== false || $jr !== false) && $b !== false) $result = false;
    elseif (($j !== false || $jr !== false) && $a !== false) $result = false;
    elseif (($m !== false || $mr !== false) && $b !== false) $result = false;
    elseif (($m !== false || $mr !== false) && $a !== false) $result = false;
    elseif ($d !== false) $result = false;
    elseif ($i !== false) $result = false;
    elseif ($b !== false) $result = false;
    elseif ($a !== false) $result = false;
    elseif ($o !== false) $result = false;
    elseif ($st !== false) $result = false;
    $this->error = "Niveau du test non reconnaissable [$medal] !";
    if (strlen($medal)==0) $this->error = "Niveau du test vide !";
    //echo "checkTestLevelMedal=$result ";

    return $result;
}

function checkTestPart($type, $niv, $part) {
    $result = true;

    $txt = trim($part);
    $str = $this->stripAccents(mb_strtolower($txt, 'ISO-8859-1'));

    $lev = $this->stripAccents(mb_strtolower($niv, 'ISO-8859-1'));
    $isStar = stripos($lev, 'star'); 
    //echo "\$pos=$pos<br>";
    //echo "$type,$niv,$part<br>";

    switch ($type){
    case "D" :
        if (strlen($txt)==0 && $isStar === false) 
            $this->error = "Activité (nom de danse) wide !";
        else {
            $danseCode = $this->getPatCanadaDanceCode($type." ".$niv." ".$part);
            if (strlen($danseCode) > 0) $result = false;
            else $this->error = "Activité (nom de danse) inconnue [$part] !";
        }
        break;
    case "H" :
        $result = false; //Not used
        break;
    case "SL" :
        $e = stripos($str, 'element');
        $s = stripos($str, 'solo');
        $p = stripos($str, 'program');
        $vide = strlen($str);

        if ($e !== false) $result = false;
        elseif ($s !== false) $result = false;
        elseif ($p !== false) $result = false;
        elseif ($vide == 0) $result = false;
        $this->error = "Activité (style libre) inconnue [$part] !";
        //if (strlen($txt)==0) $this->error = "Activité (style libre) vide !";
        break;
    case 'I' :
        $s = stripos($str, 'simple');
        $d = stripos($str, 'double');
        $c = stripos($str, 'couple');

        if ($s !== false) $result = false;
        elseif ($d !== false) $result = false;
        elseif ($c !== false) $result = false;
        $this->error = "Activité (interprétation) inconnue [$part] !";
        if (strlen($txt)==0) $this->error = "Activité (interprétation) vide !";
        break;
    default :
        $this->error = "Activité inconnue [$part] !";
        break;
    }
    //echo "checkTestPart=$result ";

    return $result;
}
function cleanup($line)
{
    $cleanline = "";
    $values = explode("\t", $line);
   
    $i=0;
    foreach($values as $value){
        $value = trim($value);
        //Capitalise tout les noms de personne
        switch($i){
        case 0 :
        case 1 :
        case 6 :
        case 7 :
        case 8 :
            $value = ucfirst( $value );
            break;
        case 2 :
            //$skatetest = (object)$this->skatingarray[0];
            //var_dump($this->skatingarray);
            break;
        }
        //echo "cleanup: ".$i." ".$value."<br>";
        $cleanline .= $value."\t";
        $i++;
    }
    
    $cleanline = trim($cleanline, "\t");
    return $cleanline;
}

}
?>