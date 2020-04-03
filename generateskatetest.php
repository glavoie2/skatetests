<?php
include_once 'includes/access.php';
include_once 'includes/skatingtestclass.php';
include_once 'includes/testtypeclass.php';
include_once 'includes/testsummaryclass.php';
include_once 'includes/divers.php';
$ua = getBrowser();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
<link href="css/skatetestform.css" rel="stylesheet" type="text/css" charset="utf-8" />
</head>
<body>
<?php

$sortby='s';
if (isset($_GET['sb'])) $sortby=$_GET['sb'];

$officialsplit = 0;
if (isset($_GET['os'])) $officialsplit = $_GET['os'];

$panelfilename = $_SESSION['panelfilename'];
if (isset($_GET['id'])) $panelfilename = "datafiles/testfile-".$_GET['id'].".txt";

if (isset($_SESSION['language'])) $language = $_SESSION['language'];
else $language = 'Fr';

$aucuntest = true;

$testDetect = new testTypeClass;

$skateobj = new testTypeClass;
$skateobj->loadTestOrder("datafiles/skatetestorder.cfg");
$skateobj->loadTestsList($panelfilename);

//Sort if user has aked for it
if ($sortby=='c') $skateobj->sortTestsList();

$nbrofficial = $skateobj->loadOfficials($officialsplit);
$officialsbyvalue = array_values($skateobj->officials);
//var_dump($skateobj->officials);

$rowcount=0;
$page = 0;
foreach ($skateobj->skatingarray as $key => $testobj){
    
    $row = explode("\t", $testobj->line);
    
    $elemcount=0;
    foreach ($row as $value){
        switch ($elemcount){
        case 0 :
            $candidate= $value.", ";
            break;
        case 1 :
            $candidate .= $value;
            break;
        case 2 :
            $skatetest = $value;
            break;
        case 3 :
            $skatetest .= " - ".$value;
            break;
        case 4 :
            $skatetest .= " - ".$value;
            $testcode= $testDetect->getPatCanadaCode($skatetest);
            $ttype= $testDetect->testcode;
            $testname= $testDetect->getPassingTestTitle($skatetest,$ttype,$language);
            $testname2= $testDetect->getInterpretationName($skatetest);
            //echo "\$testname=$testname<br/>";
            //echo "\$ttype=$ttype<br/>";
            //echo "\$testcode=$testcode<br/>";
            break;
        case 5 :
            $nationalno = $value;
            break;
        case 6 :
            $instructor= $value;
            break;
        case 7 :
            $partner= $value;
            break;
        case 8 :
            if ($officialsplit>0){
                $index = $rowcount % $nbrofficial; //Modulo
                $official = $officialsbyvalue[$index];
            }
            else $official= $value;
            break;
        case 9 :
            $officialno= $value;
            break;
        case 10 :
            $testdate= $value;
            break;
        case 11 :
            $candidateclub= $value;
            break;
        case 12 :
            $candidateclubno= $value;
            break;
        case 13 :
            $hostclub= $value;
            break;
        }
        $elemcount++;
    }
    
    if (stripos($ttype, '-ST') == false ) //Pour les Star 1 à 5 on ne change pas de page car pas imprimé.
    {
        if ($page > 0) {
            ?>
            <div style="page-break-before: always"><div class='noprint'><p style="margin-left:40px;" ><span style="color:blue">-- -- -- saut de page <?php echo $pages ?> -- -- --</span></p></div></div>
            <?php
        }
        $page++;
    }

    //Dance name not found for failure
    if ($ttype[0]=="D" && empty($testcode)) $ttype= "UnknownDance"; 
	
	if ($language == 'En'){
		$imageclubcopy = 'images/clubcopyenimg.png';
		$labellocation = 'Club Holding Test';
		$labelcandidate = 'Candidate';
		$labelstep = 'Steps Performed on Test';
		$labelsexchoice = 'Male/Female<br/>(circle one)';
		$labelcandidateclub = 'Home Club of Candidate';
		$labelpartnerclub = 'Home Club of Partner';
		$labelsignature = 'Signature of Evaluator';
		$labeldateformat = 'Year-Month-Day';
		$labelinstructor = 'Coach';
		$labelofficial = 'Evaluator';
		$labelpartner = 'Partner';
		$labelpartneriscandidate = 'Is Partner also a candidate?';
		$labelsuccess = 'Pass';
		$labelretake = 'Retry';
	    $labelyes = 'Yes';
		$labelno = 'No';
		$imagecandidatecopy = 'images/candidatecopyenimg.png';
		$labelresultcode1txt = 'E = Excellent';
		$labelresultcode2txt = 'G = Good';
		$labelresultcode3txt = 'S = Satisfactory';
		$labelresultcode4txt = 'NI = Needs Improvement';
		$labelexercises = 'EXERCICE';
		$labelresultcode1 = 'E';
		$labelresultcode2 = 'G';
		$labelresultcode3 = 'S';
		$labelresultcode4 = 'NI';
		$labelresult = 'RESULT ';
		$labelcomments = 'COMMENTS';
		$labelofficialnotes = 'EVALUATOR IS EXPECTED TO MAKE COMMENTS';
	}
	else{
		$imageclubcopy = 'images/clubcopyfrimg.png';
		$labellocation = 'Club où a lieu le test';
		$labelcandidate = 'Candidat';
		$labelstep = 'Pas exécutés<br>lors du test';
		$labelsexchoice = 'homme / femme<br/>(encercler un choix)';
		$labelcandidateclub = 'Club d’appartenance du candidat';
		$labelpartnerclub = 'Club d’appartenance du partenaire';
		$labelsignature = 'Signature de l’évaluateur';
		$labeldateformat = 'Année-Mois-Jour';
		$labelinstructor = 'Entraîneur';
		$labelofficial = 'Évaluateur';
		$labelpartner = 'Partenaire';
		$labelpartneriscandidate = 'Is Partner also a candidate?';
		$labelsuccess = 'Réussite';
		$labelretake = 'Reprise';
		$labelyes = 'Oui';
		$labelno = 'Non';
		$imagecandidatecopy = 'images/candidatecopyfrimg.png';
		$labelresultcode1txt = 'E = EXCELLENT';
		$labelresultcode2txt = 'B = BIEN';
		$labelresultcode3txt = 'S = SATISFAISANT';
		$labelresultcode4txt = 'BA = BESOIN D’AMÉLIORATION';
		$labelexercises = 'EXERCICE';
		$labelresultcode1 = 'E';
		$labelresultcode2 = 'B';
		$labelresultcode3 = 'S';
		$labelresultcode4 = 'BA';
		$labelresult = 'RÉSULTAT ';
		$labelcomments = 'COMMENTAIRES';
		$labelofficialnotes = 'ON DEMANDE À L’ÉVALUATEUR D’INSCRIRE SES COMMENTAIRES.';
	}
    switch ($ttype){
    case "D-P" :
        //Danse Préliminaire
        include 'D-P-13-470-0064F.html';
        break;
    case "H-P" :
        //Habiletés Préliminaire
        include 'H-P-13-470-0140F.html';
        break;
    case "SL-P-E" :
        //Style Libre Préliminaire
        include 'SL-P-E-13-470-0058F.html';
        break;
    case "SL-P-P" :
        //Style Libre Préliminaire
        include 'SL-P-P-13-470-0058F.html';
        break;
    case "D-JB" :
        //Danse Junior Bronze
        include 'D-JB-13-470-0065F.html';
        break;
    case "H-JB" :
        //Habiletés Junior Bronze
        include 'H-JB-13-470-0141F.html';
        break;
    case "SL-JB-E" :
        //Style Libre Junior Bronze
        include 'SL-JB-E-13-470-0059F.html';
        break;
    case "SL-JB-P" :
        //Style Libre Junior Bronze
        include 'SL-JB-P-13-470-0059F.html';
        break;
    case "D-JA" :
        //Danse Junior Argent
        include 'D-JA-13-470-0067F.html';
        break;
    case "H-JA" :
        //Habiletés Junior Argent
        include 'H-JA-13-470-0143F.html';
        break;
    case "SL-JA-E" :
        //Style Libre Junior Argent
        include 'SL-JA-E-13-470-0061F.html';
        break;
    case "SL-JA-P" :
        //Style Libre Junior Argent
        include 'SL-JA-P-13-470-0061F.html';
        break;
    case "D-SB" :
        //Danse Senior Bronze
        include 'D-SB-13-470-0066F.html';
        break;
    case "H-SB" :
        //Habiletés Senior Bronze
        include 'H-SB-13-470-0142F.html';
        break;
    case "SL-SB-E" :
        //Style Libre Senior Bronze
        include 'SL-SB-E-13-470-0060F.html';
        break;
    case "SL-SB-P" :
        //Style Libre Senior Bronze
        include 'SL-SB-P-13-470-0060F.html';
        break;
    case "D-SA" :
        //Danse Senior Argent
        include 'D-SA-13-470-0068F.html';
        break;
    case "H-SA" :
        //Habiletés Senior Argent
        include 'H-SA-13-470-0144F.html';
        break;
    case "SL-SA-E" :
        //Style Libre Senior Argent
        include 'SL-SA-E-13-470-0062F.html';
        break;
    case "SL-SA-P" :
        //Style Libre Senior Argent
        include 'SL-SA-P-13-470-0062F.html';
        break;
    case "D-O" :
        //Danse Or
        include 'D-O-13-470-0069F.html';
        break;
    case "H-O" :
        //Habiletés Or 
        include 'H-O-13-470-0145F.html';
        break;
    case "SL-O-E" :
        //Style Libre Or 
        include 'SL-O-E-13-470-0063F.html';
        break;
    case "SL-O-P" :
        //Style Libre Or 
        include 'SL-O-P-13-470-0063F.html';
        break;
    case "D-D" :
        //Danse Diamant
        include 'D-D-13-470-0070F.html';
        break;
    case "I-SI" :
    case "I-SB" :
    case "I-SA" :
    case "I-SO" :
        //Interpretation Simple
        include 'I-S-13-470-0086F.html';
        break;
    case "I-DI" :
    case "I-DB" :
    case "I-DA" :
    case "I-DO" :
        //Interpretation Double
        include 'I-D-13-470-0086F.html';
        break;
    case "D-ST1" :
    case "D-ST2-a" :
    case "D-ST2-b" :
    case "D-ST3-a" :
    case "D-ST3-b" :
    case "D-ST4-a" :
    case "D-ST4-b" :
    case "D-ST5-a" :
    case "D-ST5-b" :
    case "H-ST1" :
    case "H-ST2" :
    case "H-ST3" :
    case "H-ST4" :
    case "H-ST5" :
    case "SL-ST1" :
    case "SL-ST2-E" :
    case "SL-ST2-P" :
    case "SL-ST3-E" :
    case "SL-ST3-P" :
    case "SL-ST4-E" :
    case "SL-ST4-P" :
    case "SL-ST5-E" :
    case "SL-ST5-P" :
        //echo "<p><span style=\"color:orange\"><strong>Avertissement :</strong></span> Formulaire non supporté pour le STAR 1 à 5 <br/><strong>\"$skatetest\"</strong> ($candidate)</p></BR>";
        break;
    default:
        if ($ttype== "UnknownDance") echo "<p><span style=\"color:red\"><strong>Erreur :</strong></span> Danse inconue pour <strong>\"$skatetest\"</strong> ($candidate)</p></BR>";
        else echo "<p><span style=\"color:red\"><strong>Erreur :</strong></span> Aucun formulaire pour <strong>\"$skatetest\"</strong> ($candidate) [$ttype]</p></BR>";
    }
    $rowcount++;
    $aucuntest = false;
}

if ($aucuntest) echo "<p>Erreur : Aucun test à imprimer!</p><br/><br/><br/>\n";

?>
</body>
</html>
