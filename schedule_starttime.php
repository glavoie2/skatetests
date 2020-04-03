<?php
include_once 'includes/access.php';
include_once 'includes/skatingtestclass.php';
include_once 'includes/testtypeclass.php';
include_once 'includes/divers.php';

$displaywarning = "style=\"display:none\"";
$displayform = "";

if (isset($_GET['sb'])) $sortby = $_GET['sb'];
else $sortby = 's';

$panelfilename = $_SESSION['panelfilename'];
$skateobj = new testTypeClass;
$skateobj->loadTestOrder("datafiles/skatetestorder.cfg");
$skateobj->loadTestsListNoStar($panelfilename);
$testdatecount = count($skateobj->testdates);
   
if (isset($_POST['sb'])){

    if (isset($_POST['sb'])) $sortby=$_POST['sb'];
    else $sortby = 's';
    

    //Sort if user has aked for it
    if ($sortby=='c') $skateobj->sortTestsList();

    //Valid if schedule is well formed
    $disciplinelevel = array();
    $current = "";
    $warning = false;
    foreach($skateobj->skatingarray as $skatingtest){
        
        $testdatetrimed = str_replace("-", "", $skatingtest->testdate);
        $key = $testdatetrimed.$skatingtest->detectcode;
        if ($key != $current){
            //var_dump($key); echo "<br>";
            $current = $key;
            if (array_key_exists ( $key, $disciplinelevel )){
                $warning = true;
                //echo "<br>ordre non respecté ici<br>";
            }
            else{
                $disciplinelevel[$key] = $key;
            }
        }
    }
    //var_dump($disciplinelevel);

    $fmt = $_POST['format'];
    $i = 0;
    while ($i<=$testdatecount){
        $sth= $_POST['j'.$i];
        $i++;
        $starttimelist .= "$sth.";
    }
    $starttimelist = rtrim($starttimelist, ".");
    $querystr = "sb=".$sortby."&fmt=".$fmt."&stl=".$starttimelist;
    $_SESSION['sumquerystring'] = $querystr;
    if ($warning==false){
        header("Location: generateschedule.php?".$querystr , false, 303);
        exit();
    }
    else {
        $displaywarning = "";
        $displayform = "style=\"display:none\"";
    }
}

//var_dump($_SESSION['sumquerystring']);
if (isset($_SESSION['sumquerystring'])) //Conserve les débuts d'heures saisies
{
    $querystr = $_SESSION['sumquerystring'];
    $varlist = explode('=', $querystr);
    $timelist = explode('.', $varlist[3]);
}
else {
    $timelist = array();
    for ($i=0; $i<$testdatecount; $i++){
        array_push($timelist, "08:00");
    }
}

?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<link href="css/content.css" rel="stylesheet" type="text/css" charset="utf-8" />
<title>GLIS</title>
<script src="jscript/validation.js"></script>
<!--loading stylesheet for time picker -->
<link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" media="all" type="text/css" href="jscript/jstime/jquery-ui-timepicker-addon.css" />
<!--loading jQuery and time picker lib -->
<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript" src="jscript/jstime/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="jscript/jstime/jquery-ui-slideraccess.js"></script>
<script type="text/javascript" src="jscript/jstime/script.js"></script>

</head>

<body onload=document.forms[0].elements["submit"].focus()>

<div class="container"> <!-- Begin main container-->

<div class="contentonly"> <!-- Begin right content-->

<div <?php echo $displayform; ?> > <!-- Begin Form -->
<h3>Choisir l'heure de début des tests</h3>
<form name="main" action="schedule_starttime.php" method="post">
<INPUT name="sb"  type="hidden" value="<?php echo $sortby; ?>"/>
<table border="0" cellspacing="2" cellpadding="2">
<tr>
    <td>Format : </td>
    <td>
        <input type="radio" name="format" value="wp" checked />Page Web&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="format" value="ex" />Excel
    </td>
</tr>
<?php
$i=0; 
foreach ($skateobj->testdates as &$sdate) {
    echo "<tr><td>Journée #".($i+1)."</td><td><input name=\"j".$i."\" id=\"j".$i."\" size=\"10\" type=\"text\" value=\"".$timelist[$i]."\"/>&nbsp;(".$sdate.")</td></tr>\n";
    $i++;
}
?>
<tr>
    <td colspan="5">&nbsp;</td>
</tr>
<tr>
    <td></td><td><INPUT name="submit" type="submit" value="&nbsp;Soumettre...&nbsp;"/></td>
</tr>
</table>

</form>
<br/>
</div> <!-- End Form -->

<div <?php echo $displaywarning; ?> > <!-- Begin Warning -->
<br>
<h2>AVERTISSEMENT !</h2>
<h3>L'ordre de vos tests ne sont pas bien regroupés!</h3>
<h3>Vérifiez bien l'horaire produite.</h3>
<p style="margin-left:50px;"><a href="<?php echo "generateschedule.php?".$querystr; ?>">Continuer...</a></p>
<br>
</div> <!-- End Warning -->

</div> <!-- End right content-->

</div> <!-- End main container-->
</body>
</html>
