<?php

class passwordClass {

/* Call Syntax
    $checkpw = new passwordClass($passwordfilename);
    if ($checkpw->validate($loginname,$password)){...
*/

private $passwords = array();
private $filename = "";

function passwordClass($passwordfilename){

    $this->filename = $passwordfilename;
    $fh = fopen($passwordfilename, "r");
    while( !feof($fh) ){
        $line=fgets($fh);
        if ($line===false) break;
        $record = explode ("=",$line);
        $bdloginname = strtoupper(trim($record[0]));
        $bdpassword = trim($record[1]);
        $this->passwords[$bdloginname] = $bdpassword;
    }
    fclose ($fh);
}

function validate($loginname,$password){

    $result = false;
    $key = strtoupper(trim($loginname));
    if (!empty($loginname) && !empty($password) && ($this->passwords[$key] == $password)) {
        $result = true;
    }
   
    return $result;
}

function change($loginname,$password){

    $key = strtoupper(trim($loginname));
    $this->passwords[$key] = $password;

    $fh = fopen($this->filename, "w") or die("can't open file for writing".$this->filename);
    foreach ($this->passwords as $login => $pass){
        fwrite($fh, "$login=$pass\n");
    }
    fclose ($fh);
}

} //End class
?>