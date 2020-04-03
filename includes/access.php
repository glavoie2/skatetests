<?php
//ini_set('session.cache_limiter', 'private');
header('Content-type: text/html; charset=iso-8859-1');
// calc an offset of 2 sec
$offset = 2;
// calc the string in GMT not localtime and add the offset
$expire = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
Header($expire);

session_start();
if (!(isset($_SESSION['authorized']) && $_SESSION['authorized'] != '')){
    header("Location: login.php?url=".nl2br($_SERVER['PHP_SELF']));
    exit();
}
$sitetitle = "GLIS";
setlocale(LC_TIME,'French');
//setlocale(LC_ALL, 'fr_FR');
date_default_timezone_set('America/New_York');

?>