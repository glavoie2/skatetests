<?php
include_once 'includes/access.php';
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<link href="css/content.css" rel="stylesheet" type="text/css" charset="utf-8" />
<title><?php echo $sitetitle; ?></title>
<script src="jscript/validation.js"></script>
</head>

<body>

<div class="container"> <!-- Begin main container-->

<?php
include_once 'includes/menutop.php';
include_once 'includes/header.php';
include_once 'includes/menuleft.php';
?>

<div class="content"> <!-- Begin right content-->
<br/>
<?php

$browser = $_GET['b'];

if ($browser == "ie"){
     ?><img src="images\IExplorerPageSetup.png"/><?php
}
elseif ($browser == "mf"){
     ?><img src="images\FirefoxPageSetup1.png" />
     <img src="images\FirefoxPageSetup2.png" /><?php
}
elseif ($browser == "sa"){
     ?><img src="images\SafariPageSetup1.jpg" />
     <img src="images\SafariPageSetup2.jpg" /><?php
}
elseif ($browser == "gc"){
     ?><img src="images\GChromePageSetup.png" /><?php
}
?>
<br/><br/><br/><br/><br/><br/><br/>
<br/><br/><br/><br/><br/><br/><br/>
<br/><br/><br/><br/><br/><br/><br/>
<br/><br/><br/><br/>

</div> <!-- End right container-->

<?php
include 'includes/footer.php';
?>

</div> <!-- End main container-->
</body>
</html>
