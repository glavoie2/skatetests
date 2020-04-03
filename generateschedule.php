<?php
include_once 'includes/access.php';
include_once 'includes/divers.php';
include_once 'includes/skatingtestclass.php';
include_once 'includes/testtypeclass.php';

$panelfilename = $_SESSION['panelfilename'];
$exportfilename = "Horaire Tests ".date('Y-m-d').".xls";
$starttimehour= array();
$starttimemin= array();

if (isset($_SESSION['language'])) $language = $_SESSION['language'];
else $language = 'Fr';

if (isset($_GET['fmt'])) $format = $_GET['fmt'];
else $format = "wp";

if ($format=="wp"){
    header('Content-type: text/html; charset=iso-8859-1');
    $htmlstr = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
    $htmlstr .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" >\n";
    $htmlstr .= "<head>\n";
    $htmlstr .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\" />\n";
    $htmlstr .= "<link href=\"css/content.css\" rel=\"stylesheet\" type=\"text/css\" charset=\"utf-8\" />\n";
    $htmlstr .= "</head>\n";
    $htmlstr .= "<body style=\"margin-left: 70px;\">\n";
}
else{
    header("Content-Disposition: attachment; filename=\"$exportfilename\"");
    header("Content-Type: application/vnd.ms-excel;charset=ISO-8859-1");
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
}

if (isset($_GET['stl'])) $starttimelist = $_GET['stl'];
else $starttimelist = "8h00"; //default

if (isset($_GET['sb'])) $sortby = $_GET['sb'];
else $sortby = 's';

$starttimearray = explode(".", $starttimelist);
$i = 0;
foreach ($starttimearray as $value){
	$starttime = explode(":", $value);
    $starttimehour[$i] = (int)$starttime[0];
    $starttimemin[$i] = (int)$starttime[1];
    //$htmlstr .= $value."<br>";
    $i++;
}
$skateobj = new testTypeClass;
$skateobj->loadTestOrder("datafiles/skatetestorder.cfg");
$skateobj->loadTestsListNoStar($panelfilename);

//Sort if user has asked for it
if ($sortby=='c') $skateobj->sortTestsList();

$disciplineno = 0;
$categryno = 0;
$catcodeprec = "";
$disciplineprec = "";
$excelstr = "";
$timeindex = 0;
$isnextgroup = false;
$testdateprec = strtotime("now");
$newgroup = false;
foreach ($skateobj->skatingarray as $key => $skatetest){
    $titleexcel = "";
    $testdate = strtotime($skatetest->testdate);

    //Danse, Habiletés, Style-Libre, Interprétation
	if ($disciplineprec!=$skatetest->disciplinestd || $testdateprec != $testdate){
        $categryno = 0; //reset
        if ($testdateprec != $testdate){
            $testdateprec= $testdate;
            if (isset($starttimehour[$timeindex])) $hre = $starttimehour[$timeindex];
            else $hre = 8;
            
            $min = $starttimemin[$timeindex];
            $timeindex++;
        }
        $disciplinestr = mb_strtoupper($skatetest->discipline, 'ISO-8859-1');
        if (strlen($disciplineprec) > 0) {
            if ($format=="wp") {
                $htmlstr .= "</table>\n";
                $htmlstr .= "<br><div style=\"page-break-before: always\"><div class=\"noprint\"><p style=\"margin-left:0px;\"><span style=\"color:blue\">-- -- -- saut de page  -- -- --</span></p></div></div>\n";
            }
            else {
                $excelstr .= "\r\n";
            }
        }
        $excelstr .= "\r\n\tHORAIRE DES TESTS DU CLUB DE PATINAGE\r\n";
        $excelstr .= "\t".strftime("%A %#d %B %Y", $testdate)."\r\n";
        //var_dump($excelstr);

		$disciplineprec = $skatetest->disciplinestd;

        $htmlstr .= "<br/><div class=\"title\">HORAIRE DES TESTS DU CLUB DE PATINAGE<br/>\n";
        $htmlstr .= strftime("%A %#d %B %Y", $testdate)."\n"; 
        $htmlstr .= "</div><br/>\n";
        $htmlstr .= "<table border=\"0\">\n";
        $titleexcel = $disciplinestr."\t\r\n";
		$htmlstr .= "<tr><td colspan=\"3\"><h1>".$disciplinestr."</h1></td></tr>\n";
        $disciplineno++;
	}
    //Niveau ou activité
    $title = "<tr><td colspan=\"2\">&nbsp;</td></tr>\n"; //change group default
	if ($catcodeprec!=$skatetest->patcancode){
        $catcodeprec = $skatetest->patcancode;
        $newgroup = true;
        //$testcount = $skateobj->getTestCount($skatetest->patcancode);
        $testcount = 0;
        $isnextgroup = true; //force next group
	}
	if ($isnextgroup==true){
        $categryno++;

 		$minfmt = number_format($min, 0, '.', '0');
        $minfmt = str_pad($minfmt, 2, "0", STR_PAD_LEFT); 
		$heure = $hre."h".$minfmt;
		$min += $skatetest->warmup;

        $levelstr = $skatetest->level;
        //Si ce n'est pas une habileté on ajoute l'activité
        if (!(strlen($skatetest->patcancode) == 4 && substr($skatetest->patcancode, -2) == "SS")) $levelstr .= " - ".$skatetest->activity;
        
        $titleexcel .= "\t".$disciplineno.".".$categryno." ".$levelstr."\r\n";
        //$debug = " (".$skatetest->maxgroup." ".$skateobj->getTestTime($skatetest->patcancode).")";
        $titlehtml = "<tr><td colspan=\"3\"><h3>".$disciplineno.".".$categryno."&nbsp;&nbsp;".$levelstr."$debug</h3></td></tr>\n";
        //$testdump = $skatetest->var_dump();
        //echo "<tr><td colspan=\"2\">".$testdump."</td></tr>\n";

        $excelstr .= "\r\n";
        $excelstr .= $titleexcel;
        $htmlstr .= $titlehtml;
        $excelstr .= $heure."\tRéchauffement ".$skatetest->warmup."min.\tEntraîneur\r\n";
		$htmlstr .= "<tr><td>&nbsp;&nbsp;&nbsp;$heure&nbsp;</td><td>Réchauffement ".$skatetest->warmup." min.&nbsp;</td><td>&nbsp;Entraîneur</td></tr>\n";
    }

    $dancepartner = "";
    if ($skatetest->isdance && strlen($skatetest->partnername)) $dancepartner = " (".$skatetest->partnername.")";
    
    $excelstr .= "\t".$skatetest->getCandiate().$dancepartner."\t".$skatetest->coachname."\r\n";
    $htmlstr .= "<tr><td>&nbsp;</td><td><sstrong>&#8226;&nbsp;".$skatetest->getCandiate()."</sstrong>&nbsp;".$dancepartner."&nbsp;&nbsp;&nbsp;</td><td>&nbsp;".$skatetest->coachname."</td></tr>\n";

	$min += $skateobj->getTestTime($skatetest->patcancode);
    $min += 1; //official notes
	if ($min >=60){
		$hre = $hre+1;
		$min = $min-60;
	}
	$testcount++;
    $isnextgroup = $skateobj->isLastOfGroup($skatetest->patcancode,$testcount);
	$i++;
}

$htmlstr .= "</table>\n";
$htmlstr .= "</body>\n";
$htmlstr .= "</html>";
if ($format=="ex"){
    echo $excelstr;
}
else{
    echo $htmlstr;
    //var_dump($starttimehour);
    //var_dump($starttimemin);
}
?>
