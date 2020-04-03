<?php
include_once 'includes/access.php';
include_once 'includes/divers.php';
include_once 'includes/skatingtestclass.php';
include_once 'includes/testtypeclass.php';

$panelfilename = $_SESSION['panelfilename'];
$exportfilename = "TestsPatin".date('Y-m-d').".xls";

header("Content-Disposition: attachment; filename=\"$exportfilename\"");
header("Content-Type: application/vnd.ms-excel");

if (isset($_GET['sb'])) $sortby = $_GET['sb'];
else $sortby = 's';

$skateobj = new testTypeClass;
$skateobj->loadTestOrder("datafiles/skatetestorder.cfg");
$skateobj->loadTestsList($panelfilename);
$skateobj->sortTestsList();

echo "Nom de famille\tPrénom\tDiscipline\tNiveau\tActivité\tNo Patinage Can.\tEntraîneur\tPartenaire\tOfficiel\tNo Officiel\tDate test\tClub du candidat\tNo Club Candidat\tClub hôte\r\n";
foreach ($skateobj->skatingarray as $key => $skatetest){
    echo $skatetest->line;
}
?>
