<?php
include_once 'includes/access.php';

$tempname = tempnam("datafiles","C"); 
$rawdata = $_POST['csvdata'];

$fh = fopen($tempname, "w") or die("can't open file for writing".$tempname);
fwrite($fh, $rawdata."\r\n");
fclose($fh);

//Le dernier caractères du loginname pour distingué les données par club
$panelfilename = str_replace("\\datafiles\\C", "/datafiles/".$_SESSION['loginname']."_",$tempname);
$_SESSION['panelfilename'] = $panelfilename;
//echo $_SESSION['panelfilename']."<br>";
rename($tempname,$panelfilename);

//echo $panelfilename;
header("Location: index.php?c=3", true, 303);
exit();
?>
