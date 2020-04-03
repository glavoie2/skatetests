<?php
include_once 'includes/access.php';
include_once 'includes/testtypeclass.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
<link href="css/content.css" rel="stylesheet" type="text/css" charset="utf-8" />
<title><?php echo $sitetitle; ?></title>
<script src="jscript/validation.js"></script>
</head>

<body>
<div class="container"> <!-- Begin main container-->

<?php
include_once 'includes/header.php';
include_once 'includes/menutop.php';
include_once 'includes/menuleft.php';
?>

<div class="content"> <!-- Begin right content-->

<?php

$testDetect = new testTypeClass;

$test = "Test de DANSE Préliminaire - ";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = "Test d'habiletés Préliminaire - ";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = "style libre Préliminaire - ÉLÉMENT ";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = "style libre Préliminaire - PROGRAMME ";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";

$test = "Test juNIOR, BRonze de Danse";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = " BRonze juNIOR, Test de habiletee";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = " juNIOR, ELEMENTS Test BRonze de sTYLE Libre";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = " juNIOR, sOLO Test BRonze de sTYLE Libre";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";

$test = "Test JUNIOR, argent de Danse";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = "Test juNIOR, ARGENT de habileté";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = " juNIOR élément, Test argent de STYLE LIBRES";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = " juNIOR, Test argent de STYLE LIBRES solo";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";

$test = "Test Senior, BRonze de Danse";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = " BRonze Senior, Test de habiletÉ";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = " Senior, Test BRonze de sTYLE Libre Éléments";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = " Senior, Test BRonze de sTYLE Libre Programm";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";

$test = "Test Senior, argent de danse";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = "Test Senior, ARGENT d'habiletés";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = " Senior, Test argent elements de STYLE libre";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = " Senior, Test argent program de STYLE libre";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";


$test = "Test OR de Danse";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = "Test de habileté Or";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = " Test or de sTYLE Libre Élément";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = " Test or de sTYLE Libre SOLO";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";

$test = "Test danse diamant ";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = "interprétation Introduction Simple";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = "interprétation Bronze Simple";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = "interprétation Argent Simple";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = "interprétation OR Simple";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = "InterprÉtation INTRODUCTION DOUBLE";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = "InterprÉtation BRONZE DOUBLE";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = "InterprÉtation ARGENT DOUBLE";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = "InterprÉtation or DOUBLE";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";

$test = "Test Star 1 de Danse";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = "Test Star 4b de Danse";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = "Test de habileté Star 3";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = " Test star 2 de sTYLE Libre Élément";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";
$test = " Test STAR 5 de sTYLE Libre SOLO";
echo $test." : <strong>".$testDetect->getDetectCode($test)."</strong><BR/>";

?>
</div> <!-- End right content-->
<?php
include 'includes/footer.php';
?>

</div> <!-- End main container-->
</body>
</html>
