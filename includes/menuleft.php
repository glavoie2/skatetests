<div class="left">
<?php

if (isset($_GET['m'])) {
    $_SESSION['menu']=$_GET['m'];
}
else{
    if (!isset($_SESSION['menu'])) {
        $_SESSION['menu'] = "0";
    }
}
$menu = $_SESSION['menu'];

switch ($menu) {
default:
    echo "<br/>";
    echo "<a class=\"csssubmnu\" href=\"index.php?c=1\">Template Excel</a><br/>";
    echo "<a class=\"csssubmnu\" href=\"index.php?c=2\">Importation des tests</a><br/>";
	if ($_SERVER['HTTP_HOST'] == 'localhost'){
		echo "<a class=\"csssubmnu\" href=\"index.php?c=7\">Test Auto-Import</a><br/>";
		echo "<a class=\"csssubmnu\" href=\"index.php?c=4\">Test [Complet]</a><br/>";
		echo "<a class=\"csssubmnu\" href=\"index.php?c=5\">Test [Chaque]</a><br/>";
		echo "<a class=\"csssubmnu\" href=\"testtype.php\">Test [DetectType]</a><br/>";
    }
	echo "<a class=\"csssubmnu\" href=\"index.php?c=6\">Changer mot de passe</a><br/>";
    echo "<a class=\"csssubmnu\" href=\"login.php\">Déconnexion</a><br/>";
    break;
}

?>
</div>
