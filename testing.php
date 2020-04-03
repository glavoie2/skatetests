<?php
include_once 'includes/access.php';
include_once 'includes/divers.php';
include_once 'includes/skatingtestclass.php';
include_once 'includes/testtypeclass.php';

$panelfilenamecoded = "datafiles\\skating.tmp";

$skateobj = new testTypeClass;
$skateobj->loadTestOrder("datafiles/skatetestorder.cfg");
$errmsg = $skateobj->loadTestsList($panelfilenamecoded);
if (strlen($errmsg)>0){
    $errmsg = "<p><strong>Ces lignes sont invalides :</strong></p>".$errmsg;
    echo $errmsg;
}
else echo sizeof($skateobj->skatingarray)." tests importés<br>";
var_dump($skateobj->skatingarray);

?>
