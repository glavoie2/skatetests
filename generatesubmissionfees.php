<?php
include_once 'includes/access.php';
include_once 'includes/skatingtestclass.php';
include_once 'includes/testtypeclass.php';
include_once 'includes/testsummaryclass.php';
include_once 'includes/divers.php';
$ua = getBrowser();

if (isset($_SESSION['language'])) $language = $_SESSION['language'];
else $language = 'Fr';
include_once 'includes/sharedlanguage.php';

if (isset($_GET['sb'])) $sortby = $_GET['sb'];
else $sortby = 's';

if (isset($_GET['os'])) $officialsplit = $_GET['os'];
else $officialsplit = 0;


$panelfilename = $_SESSION['panelfilename'];

//Summary Data
$summaryfilename = "datafiles/".strtolower($_SESSION['loginname'])."summary.txt";
$header = new testSummaryClass($summaryfilename);
$header->parsefile();    

if ($language == 'En'){
    $imgsubmissiontitle = "images/submissiontitleen.png";
    $imgsubmissiontestday = 'images/testdayen.png';
    $imgsubmissiontestofficial = 'images/testofficialen.png';
    $imgsubmissiontestdir = 'images/testdiren.png';
    $imgsubmissiontestfee = 'images/testfeeen.png';
    $imgsubmissiontesttotal = 'images/testsubmissiontotalen.png';
    $imgsubmissiontestcheckno = 'images/testsubmissionchecknoen.png';
    $imgsubmissiontestofficialusage = 'images/testofficeusageen.png';
    $imgpatcanlogo = "images/skatecan.jpg";

    $labelorgno = 'Organization #';
    $labelorgname = 'Organization Name';
    $dateformat = 'DD-MM-YYYY';

    $labelofficiallist = 'Please list all officials and/or coaches who have evaluated the submitted tests';

    $labeldirnotel = 'Telephone #';
    $labeldiremail = 'Email';

    $labelfeeinstruction = 'Please enter fees for each test summary sheet submitted';
    $labelsummaryform = 'Sheet';
    $labelsummaryfee = 'Fees $';
}
else{
    
    $imgsubmissiontitle = "images/submissiontitlefr.png";
    $imgsubmissiontestday = 'images/testdayfr.png';
    $imgsubmissiontestofficial = 'images/testofficialfr.png';
    $imgsubmissiontestdir = 'images/testdirfr.png';
    $imgsubmissiontestfee = 'images/testfeefr.png';
    $imgsubmissiontesttotal = 'images/testsubmissiontotalfr.png';
    $imgsubmissiontestcheckno = 'images/testsubmissionchecknofr.png';
    $imgsubmissiontestofficialusage = 'images/testofficeusagefr.png';
    $imgpatcanlogo = "images/patcan.jpg";

    $labelorgno = '# Organisation';
    $labelorgname = 'Nom de l’organisation';
    $dateformat = 'JJ-MM-AAAA';

    $labelofficiallist = 'Veuillez lister tous les officiels et / ou entraîneur qui ont évalué les tests soumis.';

    $labeldirnotel = '# de téléphone';
    $labeldiremail = 'Courriel';

    $labelfeeinstruction = 'Veuillez entrer les droits de tests pour chaque formulaire de récapitulation de tests soumis.';
    $labelsummaryform = 'Formulaire';
    $labelsummaryfee = 'Droits $';
}

include_once 'Submission-Header.html';

$skateobj = new testTypeClass;
$skateobj->loadTestOrder("datafiles/skatetestorder.cfg");
$skateobj->loadTestsList($panelfilename);

//Sort if user has aked for it
if ($sortby=='c') $skateobj->sortTestsList();

//split or not summary by official
$nbrofficial = $skateobj->loadOfficials($officialsplit);
//var_dump($skateobj->officials);

//For each offical we print their summary
$k=0;
$stackOfficials = array();
$recapforms = array();
$grandtotal = 0;
foreach ($skateobj->officials as  $offkey => $officialname){
    $offtestlist = $skateobj->testbyofficial[$offkey];
    $j=1;

    $officialTestsQty = count($offtestlist);
    $grandtotal += $officialTestsQty*$header->unitcost;
    //echo $officialTestsQty."<br>";
    if ($header->calc=="on"){
        do {
            if ($officialTestsQty <= 10)
                array_push($recapforms, number_format($officialTestsQty*$header->unitcost, 0, '.', '').' $');
            else
                array_push($recapforms, number_format(10*$header->unitcost, 0, '.', '').' $');
            $officialTestsQty -= 10;
        } while ($officialTestsQty > 0);
    }
    
    foreach ($skateobj->skatingarray as  $key => $skatingobj){
        if ($skatingobj->officialname == $officialname ){ // un seul est suffisant
          $sFeeOfficial = array($officialname, $skatingobj->officialno);
          array_push($stackOfficials, $sFeeOfficial);
          break;
        } 
        $j++;
    }
    $k++;
}
//print_r($recapforms);
?>

<tr>
    <td valign="top">
        </br>
        <?php echo $labelofficiallist; ?></br>
        <div><img width="400" height="21" src="<?php echo $imgsubmissiontestofficial; ?>"></div>
        <table border="1" cellspacing="0" cellpadding="2" width="400">
        <tr>
            <td><strong>#</strong></td>
            <td><strong><?php echo $labelnoskatecan; ?></strong></td>
            <td width="200"><strong><?php echo $labelname; ?></strong></td>
        </tr>
        <tr>
            <td>1</td>
            <td><?php echo $stackOfficials[0][1]; ?></td>
            <td><?php echo $stackOfficials[0][0]; ?></td>
        </tr>
        <tr>
            <td>2</td>
            <td><?php echo $stackOfficials[1][1]; ?></td>
            <td><?php echo $stackOfficials[1][0]; ?></td>
        </tr>
        <tr>
            <td>3</td>
            <td><?php echo $stackOfficials[2][1]; ?></td>
            <td><?php echo $stackOfficials[2][0]; ?></td>
        </tr>
        <tr>
            <td>4</td>
            <td><?php echo $stackOfficials[3][1]; ?></td>
            <td><?php echo $stackOfficials[3][0]; ?></td>
        </tr>
        <tr>
            <td>5</td>
            <td><?php echo $stackOfficials[4][1]; ?></td>
            <td><?php echo $stackOfficials[4][0]; ?></td>
        </tr>
        </table>
    </td>
    <td width="30"></td>
    <td valign="top">
        </br>
        </br>
        <div><img width="300" height="21" src="<?php echo $imgsubmissiontestdir; ?>"></div>
        <table border="1" cellspacing="0" cellpadding="3" width="300">
        <tr>
            <td><strong><?php echo $labelnoskatecan; ?></strong></td>
            <td width="170"><?php echo $header->directorno; ?></td>
        </tr>
        <tr>
            <td><strong><?php echo $labelname; ?></strong></td>
            <td><?php echo $header->directorname; ?></td>
        </tr>
        <tr>
            <td><strong><?php echo $labeldirnotel; ?></strong></td>
            <td><?php echo $header->directortel; ?></td>
        </tr>
        <tr>
            <td><strong><?php echo $labeldiremail; ?></strong></td>
            <td><?php echo $header->directoremail; ?></td>
        </tr>
        </table>
    </td>
</tr>
</table>

<br/>
<br/>
<?php echo $labelfeeinstruction; ?></br>
<img width="780" height="21" src="<?php echo $imgsubmissiontestfee; ?>">
<table border="1" cellspacing="0" cellpadding="4" width="780">
<?php
for ($i=0;$i<10;$i++){
    echo '<tr>';
    echo '<td width="80">'.$labelsummaryform.'&nbsp;'.($i+1).'</td>';
    if ($recapforms[$i])
        echo '<td width="180" align="center"><strong>'.$recapforms[$i].'</strong></td>';
    else
        echo '<td width="180" align="center"><div style="color:#CECED9;">'.$labelsummaryfee.'</div></td>';
    echo '<td width="80">'.$labelsummaryform.'&nbsp;'.($i+11).'</td>';
    if ($recapforms[$i+10])
        echo '<td width="180" align="center"><strong>'.$recapforms[$i+10].'</strong></td>';
    else
        echo '<td width="180" align="center"><div style="color:#CECED9;">'.$labelsummaryfee.'</div></td>';
    echo '<td width="80">'.$labelsummaryform.'&nbsp;'.($i+21).'</td>';
    if ($recapforms[$i+20])
        echo '<td width="180" align="center"><strong>'.$recapforms[$i+20].'</strong></td>';
    else
        echo '<td width="180" align="center"><div style="color:#CECED9;">'.$labelsummaryfee.'</div></td>';
    echo '</tr>';
}
echo "</table>";
echo '<table border="1" cellspacing="0" cellpadding="2" width="780">';
if ($header->calc=="on")
    echo '<tr><td width="601"><img width="600" height="21" src="'.$imgsubmissiontesttotal.'"></td><td align="center"><span style="font-size:12px;font-weight:bold;">'.$grandtotal.' $</span></td></tr>';
else
    echo '<tr><td width="601"><img width="600" height="21" src="'.$imgsubmissiontesttotal.'"></td><td></td></tr>';
?>
<tr><td><img width="600" height="21" src="<?php echo $imgsubmissiontestcheckno; ?>"></td><td align="center"><span style="font-size:12px;font-weight:bold;">#&nbsp;<?php echo $header->checkno; ?></span></td></tr>
</table>
<br/>
<br/>

<?php
include_once 'Submission-Footer.html';
?>
