<?php
include_once 'includes/access.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<link rel="shortcut icon" href="images/favicon.ico">
<link href="css/content.css" rel="stylesheet" type="text/css" charset="utf-8" />
<title><?php echo $sitetitle; ?></title>
<script src="jscript/validation.js"></script>
</head>

<body onload="document.forms[0].elements.csvdata.focus()">
<div class="container"> <!-- Begin main container-->

<?php
include_once 'includes/menutop.php';
include_once 'includes/header.php';
include_once 'includes/menuleft.php';
?>

<div class="content"> <!-- Begin right content-->

<?php

$displaytemplate = "style=\"display:none\"";
$displayimport = "style=\"display:none\"";
$displayprinting = "style=\"display:none\"";
$displayprinting2 = "style=\"display:none\"";
$displayprinting3 = "style=\"display:none\"";
$displaypassword = "style=\"display:none\"";
$displayready = "style=\"display:none\"";

$case = $_GET['c'];
switch ($case){
case "1":
    $displaytemplate = "";
    break;
case "2":
case "7":
    unset($_SESSION['querystring']);
    unset($_SESSION['sumquerystring']);
    $displayimport = "";
    break;
case "3":
    $displayprinting = "";
    break;
case "4":
    $displayprinting2 = "";
    break;
case "5":
    $displayprinting3 = "";
    break;
case "6":
    $displaypassword = "";
    break;
case "8":
    $displayready = "";
    break;
default :
    echo "<h1>Bienvenue</h1><br/>\n";
    echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>";
}

if ($case=="7"){
$rawdata = 
'Lavoie	Anna-Marie	HABILETÉS	SENIOR ARGENT	Hab Sr A.	100163	Dupuis, Roxanne		Beaulieu, Catherine	123507	2017-09-25	CPA Boucherville	9825	CPA Boucherville
Di Nezza	Sara-Maude	STYLE LIBRE	STAR 5	Éléments	100164	Dupuis, Roxanne		Beaulieu, Catherine	123508	2017-09-25	CPA Boucherville	9824	CPA Boucherville
Lavoie	Josée-Anne	STYLE LIBRE	JUNIOR ARGENT	Programme	100165	Rainville, Julie		Beaulieu, Catherine	123509	2017-09-26	CPA Longueuil	9823	CPA Longueuil
Patenaude	Matilde	DANSE	JUNIOR ARGENT	FOX-TROT DE KEATS	100109	Rainville, Julie	Dyck, Stefan	Beaulieu, Catherine	123462	2017-09-26	CPA Boucherville	9870	CPA Longueuil';
}
?>

<div <?php echo $displaytemplate; ?> > <!-- Begin template -->
    <h3>Télécharger le template Excel pour bâtir les tests de patinage</h3>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="Template-Tests-Patinage.xlsx">Template-Tests-Patinage.xlsx</a><br/>
    <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
</div> <!-- End template -->


<div <?php echo $displayimport; ?> > <!-- Begin form -->

<h3>Copier/Coller la liste des tests de patinage ici :</h3>
<form onsubmit="return formValidator();" action="importprocessing.php" method="post">
<p><TEXTAREA name="csvdata" wrap="off" rows="12" cols="80" ><?php echo $rawdata; ?></TEXTAREA></p>
<p>&nbsp;<strong>Avertissement :</strong> La liste copiée doit respecter le format du template Excel</p>
<p>&nbsp;<INPUT type="submit" value="&nbsp;Soumettre...&nbsp;"/></p>
</form>

</div> <!-- End form -->


<div <?php echo $displayprinting; ?> > <!-- Begin Import -->

<?php
if ($case=="3"){
    include_once 'import.php';
}
?>

</div> <!-- End import -->


<div <?php echo $displayprinting2; ?> > <!-- Begin print -->
<?php
if ($case=="4"){
    $_SESSION['panelfilename'] = "datafiles//testfile-10000.txt";
    echo "<a href=\"generateskatetest.php\" >Impression des tests...</a><br/>\n";
    echo "(".$_SESSION['panelfilename'].")<br/><br/>";
    echo "<a href=\"generatesummarytest.php\" >Impression sommaire...</a><br/><br/>\n";
}11
?>
<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>

</div> <!-- End print -->

<div <?php echo $displayprinting3; ?> > <!-- Begin print -->
    <a href="generateskatetest.php?id=10001" >Impression des tests : D-P</a><br/>
    <a href="generateskatetest.php?id=10002" >Impression des tests : H-P</a><br/>
    <a href="generateskatetest.php?id=10003" >Impression des tests : SL-P-E</a><br/>
    <a href="generateskatetest.php?id=10004" >Impression des tests : SL-P-P</a><br/>
    <a href="generateskatetest.php?id=10005" >Impression des tests : D-JB</a><br/>
    <a href="generateskatetest.php?id=10006" >Impression des tests : H-JB</a><br/>
    <a href="generateskatetest.php?id=10007" >Impression des tests : SL-JB-E</a><br/>
    <a href="generateskatetest.php?id=10008" >Impression des tests : SL-JB-P</a><br/>
    <a href="generateskatetest.php?id=10009" >Impression des tests : D-JA</a><br/>
    <a href="generateskatetest.php?id=10010" >Impression des tests : H-JA</a><br/>
    <a href="generateskatetest.php?id=10011" >Impression des tests : SL-JA-E</a><br/>
    <a href="generateskatetest.php?id=10012" >Impression des tests : SL-JA-P</a><br/>
    <a href="generateskatetest.php?id=10013&s=1" >Impression des tests : D-SB</a><br/>
    <a href="generateskatetest.php?id=10014&s=1" >Impression des tests : H-SB</a><br/>
    <a href="generateskatetest.php?id=10015&s=1" >Impression des tests : SL-SB-E</a><br/>
    <a href="generateskatetest.php?id=10016&s=1" >Impression des tests : SL-SB-P</a><br/>
    <a href="generateskatetest.php?id=10017&s=1" >Impression des tests : D-SA</a><br/>
    <a href="generateskatetest.php?id=10018&s=1" >Impression des tests : H-SA</a><br/>
    <a href="generateskatetest.php?id=10019&s=1" >Impression des tests : SL-SA-E</a><br/>
    <a href="generateskatetest.php?id=10020&s=1" >Impression des tests : SL-SA-P</a><br/>
    <a href="generateskatetest.php?id=10021&s=1" >Impression des tests : D-O</a><br/>
    <a href="generateskatetest.php?id=10022&s=1" >Impression des tests : H-O</a><br/>
    <a href="generateskatetest.php?id=10023&s=1" >Impression des tests : SL-O-E</a><br/>
    <a href="generateskatetest.php?id=10024&s=1" >Impression des tests : SL-O-P</a><br/>
    <a href="generateskatetest.php?id=10025&s=1" >Impression des tests : D-D</a><br/>
    <a href="generateskatetest.php?id=10026&s=1" >Impression des tests : I-S</a><br/>
    <a href="generateskatetest.php?id=10027&s=1" >Impression des tests : I-D</a><br/>
    <a href="generateskatetest.php?id=10029" >Impression des tests : D-ST1</a><br/>
    <a href="generateskatetest.php?id=10030" >Impression des tests : D-ST2-a</a><br/>
    <a href="generateskatetest.php?id=10031" >Impression des tests : D-ST2-b</a><br/>
    <a href="generateskatetest.php?id=10032" >Impression des tests : D-ST3-a</a><br/>
    <a href="generateskatetest.php?id=10033" >Impression des tests : D-ST3-b</a><br/>
    <a href="generateskatetest.php?id=10034" >Impression des tests : D-ST4-a</a><br/>
    <a href="generateskatetest.php?id=10035" >Impression des tests : D-ST4-b</a><br/>
    <a href="generateskatetest.php?id=10036" >Impression des tests : D-ST5-a</a><br/>
    <a href="generateskatetest.php?id=10037" >Impression des tests : D-ST5-b</a><br/>
    <a href="generateskatetest.php?id=10038" >Impression des tests : H-ST1</a><br/>
    <a href="generateskatetest.php?id=10039" >Impression des tests : H-ST2</a><br/>
    <a href="generateskatetest.php?id=10040" >Impression des tests : H-ST3</a><br/>
    <a href="generateskatetest.php?id=10041" >Impression des tests : H-ST4</a><br/>
    <a href="generateskatetest.php?id=10042" >Impression des tests : H-ST5</a><br/>
    <a href="generateskatetest.php?id=10043" >Impression des tests : SL-ST1-E</a><br/>
    <a href="generateskatetest.php?id=10044" >Impression des tests : SL-ST1-P</a><br/>
    <a href="generateskatetest.php?id=10045" >Impression des tests : SL-ST2-E</a><br/>
    <a href="generateskatetest.php?id=10046" >Impression des tests : SL-ST2-P</a><br/>
    <a href="generateskatetest.php?id=10047" >Impression des tests : SL-ST3-E</a><br/>
    <a href="generateskatetest.php?id=10048" >Impression des tests : SL-ST3-P</a><br/>
    <a href="generateskatetest.php?id=10049" >Impression des tests : SL-ST4-E</a><br/>
    <a href="generateskatetest.php?id=10050" >Impression des tests : SL-ST4-P</a><br/>
    <a href="generateskatetest.php?id=10051" >Impression des tests : SL-ST5-E</a><br/>
    <a href="generateskatetest.php?id=10052" >Impression des tests : SL-ST5-P</a><br/>
    <a href="testtype.php" >testtype classe</a><br/>

    </div> <!-- End print -->

<div <?php echo $displaypassword; ?> > <!-- Begin form password -->

<h3>Changement du mot de passe</h3>
<?php
if ($case=="6" && isset($_POST['password'])) {
    $password = $_POST['password'];
    $passwordconf = $_POST['passwordconf'];
    if (strlen($password) < 6){
        $msg = "<p style=\"color:red\">Mot de passe min. 6 caractères!</p>";
    }
    elseif ($password != $passwordconf){
        $msg = "<p style=\"color:red\">Mot de passe non identique!</p>";
    }
    else {
        include_once ('includes/passwordclass.php');
        $passwordfilename = "datafiles/glispassword.cfg";
        $checkpw = new passwordClass($passwordfilename);
        $checkpw->change($_SESSION['loginname'],$password);
        $msg = "<p style=\"color:green\">Mot de passe changé avec succès!</p>";
    }

}
?>
<form onsubmit="return formValidator();" action="index.php?c=6" method="post">
<table cellspacing="3" cellpadding="3">
<tr><td colspan="2"><?php echo $msg; ?></td></tr>
<tr><td>Nouveau mot de passe</td><td><input name="password" id="password" type="password" value="" /></td></tr>
<tr><td>Confirmer mot de passe</td><td><input name="passwordconf" id="passwordconf" type="password" value="" /></td></tr>
<tr><td></td><td><INPUT type="submit" value="Soumettre"/></td></tr>
</table>

</form>

</div> <!-- End form password -->

<div <?php echo $displayready; ?> > <!-- Begin Ready -->

<br/>
<h2>&nbsp;Selectionner ce que vous desirez faire avec les tests importés</h2>
<ul>
<?php

if (isset($_GET['sb'])) $sortby = $_GET['sb'];
else $sortby = 's';

if (isset($_GET['os'])) $officialsplit = 1;
else $officialsplit = 0;

if ($case == "8") $_SESSION['querystring'] = $_SERVER['QUERY_STRING'];

echo "<li>Impression <a href=\"generateskatetest.php?sb=$sortby&os=$officialsplit\" target=\"_blank\">Feuilles de Tests</a>&nbsp;<strong><sup style=\"color:DarkGoldenRod;\"> &dagger;</sup></strong></li>";
echo "<li>Impression <a href=\"generatesummarytest.php?sb=$sortby&os=$officialsplit\"  target=\"_blank\">Récapitulation de Tests</a> (sommaire par juge)&nbsp;<strong><sup style=\"color:DarkGoldenRod;\"> &dagger;</sup></strong></li>";
echo "<li>Impression <a href=\"summary_form.php?sb=$sortby&os=$officialsplit\" target=\"_blank\">Soumission de Tests</a> (total des droits)&nbsp;<strong><sup style=\"color:DarkGoldenRod;\"> &dagger;</sup></strong></li>";
echo "<li>Impression <a href=\"schedule_starttime.php?sb=$sortby\" target=\"_blank\">Horaire des Tests</a></li>";
echo "<li>Exportation <a href=\"export.php\" target=\"_blank\">Tests Triés </a>(sous Excel)</li>";

?>
</ul>
<br/>

<p><strong>&nbsp;&nbsp;&nbsp;<strong><sup style="color:DarkGoldenRod;">&dagger;</sup></strong>&nbsp;Assurez-vous d'avoir ajusté les marges du navigateur web avant d'imprimer!</strong></p>
<ul>
<li>Mise en page <a href="printsetting.php?b=gc">Google Chrome</a></li>
<li>Mise en page <a href="printsetting.php?b=ie">Internet Explorer</a></li>
<li>Mise en page <a href="printsetting.php?b=mf">Mozzila Firefox</a></li>
<li>Mise en page <a href="printsetting.php?b=sa">Safari (iMac)</a></li>
</ul>
<br/>

</div> <!-- End Ready -->

</div> <!-- End right content-->

<?php
include 'includes/footer.php';
?>

</div> <!-- End main container-->
</body>
</html>
