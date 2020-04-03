<?php
session_start();

if(isset($_COOKIE['loginname'])){
    $loginname = $_COOKIE['loginname'];
}
if(isset($_SESSION['authorized'])){
	unset($_SESSION['authorized']);
}
if(isset($_SESSION['panelfilename'])){
	unset($_SESSION['panelfilename']);
}
if(isset($_SESSION['language'])){
	unset($_SESSION['language']);
}
if (isset($_GET['url'])){
	$url = $_GET['url'];
}
else{
	if (isset($_POST['url'])){
		$url = $_POST['url'];
	}
}
//bypass
/*
	header("Location: $url");
	$_SESSION['authorized'] = "ok";
          $_SESSION['loginname'] = "glavoie2@msn.com";
	exit();
*/

if (isset($_POST['usrname']) && isset($_POST['pass'])){
    $loginname = strtoupper($_POST['usrname']);
    $password = $_POST['pass'];
	$badmsg = "";
    
    include_once ('includes/passwordclass.php');
    $passwordfilename = "datafiles/glispassword.cfg";
    $checkpw = new passwordClass($passwordfilename);
    if ($checkpw->validate($loginname,$password)){
		$_SESSION['authorized'] = "ok";
        $_SESSION['loginname'] = $loginname;

        if ($loginname=='CPAAN') $_SESSION['language'] = 'En';
       
        if ($bdaccess == "menu"){
        	$_SESSION['menu'] = "yes";
        }
        else{
        	$_SESSION['menu'] = "no";
        }
        $inOneMonth = 60 * 60 * 24 * 30 + time(); //1 month
        setcookie('loginname', $loginname, $inOneMonth);
        //echo "<p style=\"color:red\">Redirected!</p><br/>";
        $url = ($_POST['url'] ? $_POST['url'] : "index.php");
        //echo "<A href=\"$url\" >$url</A>";
        //if ($loginname=="CPAZ") $url = "/skatetest/index.php";
        header("Location: $url");
        exit();
    }
    else{
        if ($bdloginname != $loginname){
            $badmsg = "<p style=\"color:red\">Mauvais nom ou mot de passe</p><br/>";
        }
        else{
            $badmsg = "<p style=\"color:red\">Mauvais mot de passe</p><br/>";
        }
        if ($_SESSION['authorized']) session_destroy();

    }
}
else{
    if (isset($_GET['url'])){
        //come from direct page access without authorisation
        //$badmsg = "<p style=\"color:red\">Accès refusé</p><br/>";
    }

}
?>
<HTML>
<HEAD>
<LINK href="css/login.css" rel="stylesheet" type="text/css" charset="utf-8" />
<TITLE>Ouverture Session</TITLE>
</HEAD>
<BODY onload=document.forms[0].elements.pass.focus()>
<div id="ctr" align="center">
	<div class="login">
		<div class="login-form">
			<img src="images/applogo.png" alt="Login" />
            <form action="login.php" method="post" name="loginForm" id="loginForm" enctype="multipart/form-data">
			<div class="form-block">
	        	<div class="inputlabel">Nom d'utilisateur</div>
		    	<div><input name="usrname" type="text" class="inputbox" size="15" value="<?php echo $loginname; ?>" /></div>
	        	<div class="inputlabel">Mot de passe</div>
		    	<div><input name="pass" type="password" class="inputbox" size="15" />
		    	<input name="url" type="hidden" value="<?php echo $url; ?>"/></div>
	        	<div align="left"><input type="submit" name="submit" class="button" value="Connexion" /></div>
        	</div>
			</form>
    	</div>
		<div class="login-text">
			<div class="ctr"><img src="images/security.png" width="64" height="64" alt="security" /></div>
        	<p>Tests de patinage artistique</p>
			<?php echo $badmsg; ?>
    	</div>
		<div class="clr"></div>
	</div>
</div>
</BODY>
</HTML>