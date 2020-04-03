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
<title>GLIS</title>
</head>
<body style="margin-left:15px">
<?php

if (isset($_SESSION['language'])) $language = $_SESSION['language'];
else $language = 'Fr';
include_once 'includes/sharedlanguage.php';

if (isset($_GET['sb'])) $sortby=$_GET['sb'];
else $sortby = 's';

if (isset($_GET['sds'])) $summarydaystart = $_GET['sds'];
else $summarydaystart = 0;

$officialsplit = 0;
if (isset($_GET['os'])) $officialsplit = $_GET['os'];

$panelfilename = $_SESSION['panelfilename'];

if ($withsummarytest==2) $aucuntest = false;
else $aucuntest = true;


$skateobj = new testTypeClass;
$skateobj->loadTestOrder("datafiles/skatetestorder.cfg");
$skateobj->loadTestsList($panelfilename);

//Sort if user has aked for it
if ($sortby=='c') $skateobj->sortTestsList();

//split or not summary by official
$nbrofficial = $skateobj->loadOfficials($officialsplit);
//var_dump($skateobj->officials);

if ($summarydaystart>0) $summaryday = $summarydaystart;
else $summaryday = "<div class=\"box2\">&nbsp;</div>";

//Summary Data
$summaryfilename = "datafiles/".strtolower($_SESSION['loginname'])."summary.txt";
$header = new testSummaryClass($summaryfilename);
$header->parsefile();

if ($language == 'En'){
    $imgsummarytitle = 'images\summarytitleen.png';
    $imgsummarytotal = 'images/testsummarytotalen.png';
    $imgpatcanlogo = "images/skatecan.jpg";

    $labelinstruction = 'Please ensure that the information below is complete and correct prior to submission; missing information will result in delayed processing.';

    $labelhomeorgname = 'Home Organization';
    $labeltestcode = 'Assessment Code';
    $labelofficialno = 'Assessor #';
    $labelofficial = 'Assessor Name';
    $labeldateformat = 'YYYY-MM-DD';

    $labelhomeorgno = 'Home Org #';
    $labelsucces = "Pass";
    $labelfailed = "Retry";
    $labelfee = "Fee $";
}
else{
    $imgsummarytitle = 'images\summarytitlefr.png';
    $imgsummarytotal = 'images/testsummarytotalfr.png';
    $imgpatcanlogo = "images/patcan.jpg";

    $labelinstruction = 'Assurez-vous que les informations inscrit ci-dessous sont bien remplies avant soumission. Les informations manquantes se traduiront par un traitement différé.';

    $labelhomeorgname = 'Org d’appartenance';
    $labeltestcode = 'Code d’évaluation';
    $labelofficialno = '# Évaluateur';
    $labelofficial = 'Nom Évaluateur';
    $labeldateformat = 'AAAA-MM-JJ';

    $labelhomeorgno = '# Org d’appartenance';
    $labelsucces = "Réussite";
    $labelfailed = "Reprise";
    $labelfee = "Droits $";

}

//For each offical we print their summary
$page=1;
$nbritemforcost=0;
$itemperpage = 1;
$summarypage = 1;
$officialindex = 1;
//var_dump($skateobj->officials);
foreach ($skateobj->officials as  $offkey => $official){
    $offtestlist = $skateobj->testbyofficial[$offkey];
    //echo "<br><br>$offkey<br>$official<br>";
    //var_dump($offtestlist);
    foreach ($offtestlist as  $key => $skatingobj){
        $candidate= $skatingobj->getCandiate();
        $nationalno= $skatingobj->candiatno;
        $instructor= $skatingobj->coachname;
        $officialname= $skatingobj->officialname;
        $officialno= $skatingobj->officialno;
        $grouping = $offkey;
        $testdate= $skatingobj->testdate;
        $candidateclub= $skatingobj->candiatclubname;
        $candidateclubno= $skatingobj->candiatclubno;
        $patcancode= $skatingobj->patcancode;
        if (empty($patcancode)) $patcancode= "";

        $officialname= $official;
        $officialno= $skatingobj->officialno;
        if (strlen($officialno)>0) $officialname_no = $officialname." - ".$officialno;
        else  $officialname_no = $officialname;
        //echo "\$key=$key  \$itemperpage=$itemperpage  \$page=$page   \$nbritemforcost=$nbritemforcost <br>";

        //for first pass only
        if (!isset($groupby)){
            $groupby = $grouping;

            include 'Summary-Header.html';
        }
        if ($groupby != $grouping){
            $groupby = $grouping;
            for ($j=$itemperpage;$j<=10;$j++){
                include 'Summary-Content-Empty.html';
            }
            $itemperpage=11; //force page change
        }
        if ($itemperpage>10){
            $cost = $nbritemforcost*$header->unitcost;
            include 'Summary-Footer.html';
            $page++;
            $summarypage++;
            $nbritemforcost=0;
            $itemperpage = 1;
            ?><div style="page-break-before: always"><div class='noprint'><p style="margin-left:40px;" ><span style="color:blue">-- -- -- saut de page <?php echo $pages; ?> -- -- --</span></p></div></div><?php
            include 'Summary-Header.html';
        }
        include 'Summary-Content.html';
        $nbritemforcost++;
        $itemperpage++;
    }
    $officialindex++;
}

 //finalize last page
for ($j=$itemperpage;$j<=10;$j++){
    include 'Summary-Content-Empty.html';
}
$cost = $nbritemforcost*$header->unitcost;
include 'Summary-Footer.html';

if ($header->withfees==1){
    ?><div style="page-break-before: always"><div class='noprint'><p style="margin-left:40px;" ><span style="color:blue">-- -- -- saut de page <?php echo $pages; ?> -- -- --</span></p></div></div><?php
}

?>
</body>
</html>
