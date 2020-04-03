<?php
include_once 'includes/access.php';
include_once 'includes/testsummaryclass.php';

if (isset($_GET['sb'])) $sortby = $_GET['sb'];
else $sortby = 's';

if (isset($_GET['os'])) $officialsplit = $_GET['os'];
else $officialsplit = 0;

//Summary Data
$summaryfilename = "datafiles/".strtolower($_SESSION['loginname'])."summary.txt";
$header = new testSummaryClass($summaryfilename);
$header->parsefile();

if (isset($_POST['orgname'])){

    $header->orgname=$_POST['orgname'];
    $header->orgno=$_POST['orgno'];
    $header->testday=$_POST['testday'];
    $header->checkno=$_POST['checkno'];
    $header->calc=$_POST['calc'];
    $header->orgaddresse1=$_POST['orgaddresse1'];
    $header->orgaddresse2=$_POST['orgaddresse2'];
    $header->orgcity=$_POST['orgcity'];
    $header->orgprovince=$_POST['orgprovince'];
    $header->orgzipcode=$_POST['orgzipcode'];
    $header->directorname=$_POST['directorname'];
    $header->directortel=$_POST['directortel'];
    $header->directoremail=$_POST['directoremail'];
    $header->directorno=$_POST['directorno'];

    $sb = $_POST['sb'];

    if (isset($_POST['os'])) $os = $_POST['os'];
    else $os = 0;
    //$header->isofficialsplit = $os;
    
    $header->savedata();
    header("Location: generatesubmissionfees.php?sb=$sb&os=$os" , false, 303);
    exit();
}

$sdspost = $header->summarydaystart;
if( $sdspost == -1 ) $sdspost = "";

?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<link href="css/content.css" rel="stylesheet" type="text/css" charset="utf-8" />
<title><?php echo $sitetitle; ?></title>
<script src="jscript/validation.js"></script>
</head>

<body onload=document.forms[0].elements["orgname"].focus()>

<div class="ccontainer"> <!-- Begin main container-->

<?php

if ($header->ispaginate==0){
    $ndsp_checked = "";
    $startpage_style = "display:none;";
}
else {
    $ndsp_checked = "checked";
    $startpage_style = "display:block;";
}

//if ($header->withfees==0) $sf_checked = "";
//else $sf_checked = "checked";

?>

<div class="contentonly"> <!-- Begin right content-->

<h3>Saisir les informations d'entête des sommaires</h3>

<form name="main" action="summary_form.php" method="post">
<INPUT name="sb"  type="hidden" value="<?php echo $sortby; ?>"/>
<INPUT name="os"  type="hidden" value="<?php echo $officialsplit; ?>"/>
<table border="0" cellspacing="2" cellpadding="2">
<tr>
    <td></td><td><strong>Club hôte</strong></td>
    <td width="30">&nbsp;</td>
    <td width="70"></td><td><strong>Directeur de test</strong></td>
</tr>
<tr>
    <td>No de l’organisme : </td><td><INPUT name="orgno"  type="text" size="40" value="<?php echo $header->orgno; ?>"/></td>
    <td>&nbsp;</td>
    <td width="70">Nom</td><td><INPUT name="directorname"  type="text" size="40" value="<?php echo $header->directorname; ?>"/></td>
</tr>
<tr>
    <td>Nom de l’organisme : </td><td><INPUT name="orgname"  type="text" size="40" value="<?php echo $header->orgname; ?>"/></td>
    <td>&nbsp;</td>
    <td width="90">Tél. :</td><td><INPUT name="directortel"  type="text" size="40" value="<?php echo $header->directortel; ?>"/></td>
</tr>
<tr>
    <td colspan="3"></td>
    <td>Courriel :</td><td><INPUT name="directoremail"  type="text" size="40" value="<?php echo $header->directoremail; ?>"/></td>
</tr>
<tr>
    <td>Jour du test (date):</td><td><INPUT name="testday"  type="text" size="40" value="<?php echo $header->testday; ?>"/></td>
    <td>&nbsp;</td>
    <td width="90">#Pat. Canada :</td><td><INPUT name="directorno"  type="text" size="40" value="<?php echo $header->directorno; ?>"/></td>
</tr>
<tr>
    <td>No du chèque :</td><td><INPUT name="checkno"  type="text" size="40" value="<?php echo $header->checkno; ?>"/></td><INPUT name="orgaddresse1" type="hidden" size="40" value="<?php echo $header->orgaddresse1; ?>"/></td>
    <td colspan="3"></td>
</tr>
<tr>
    <td>Totaliser formulaires</td><td><input type="checkbox" name="calc" <?php if ($header->calc=="on") echo "checked"; ?> /></td><td><td><INPUT name="orgaddresse2" type="hidden" size="40" value="<?php echo $header->orgaddresse2; ?>"/></td>
    <td><INPUT name="submit" type="submit" value="&nbsp;Soumettre...&nbsp;"/></td>

</tr>
<tr>
    <td>&nbsp;</td><td><INPUT name="orgcity"  type="hidden" size="40" value="<?php echo $header->orgcity; ?>"/></td>
    <td colspan="3"></td>

</tr>
<tr>
    <td>&nbsp;</td><td><INPUT name="orgprovince" type="hidden" size="40" value="<?php echo $header->orgprovince; ?>"/></td>
    <td colspan="3"></td>

</tr>
<tr>
    <td>&nbsp;</td><td><INPUT name="orgzipcode" type="hidden" size="40" value="<?php echo $header->orgzipcode; ?>"/></td>
    <td>&nbsp;</td>
    <td></td>
</tr>
</table>

</form>

</div> <!-- End right content-->

<?php
//include 'includes/footer.php';
?>

</div> <!-- End main container-->
</body>
</html>
