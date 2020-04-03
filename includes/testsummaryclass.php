<?php

class testSummaryClass {

/* Call Syntax
$testSummary = new testSummaryClass("datafiles\filename.txt");
echo $testSummary->orgname."<br/>";
*/
private $datafilename;

public $orgno;
public $orgname;
public $orgaddresse1;
public $orgaddresse2;
public $orgcity;
public $orgprovince;
public $orgzipcode;
public $directorname;
public $directortel;
public $directoremail;
public $directorno;
public $testday;
public $checkno;
public $calc;

public $isofficialsplit=0;
public $summarydaystart=-1;
public $unitcost = 12; //12.00$

function testSummaryClass($datafilename){

    $this->datafilename = $datafilename;
}

function parsefile(){

    $fh = fopen($this->datafilename, 'r') or die("can't open file for reading ".$this->datafilename);

    $line=fgets($fh);
    $row = explode(";", $line);
    $this->orgno = $row[0];
    $this->orgname = $row[1];
    $this->testday = $row[2];
    $this->checkno = $row[3];
    $this->calc = $row[4];

    $line=fgets($fh);
    $row = explode(";", $line);
    $this->orgaddresse1 = $row[0];
    $this->orgaddresse2 = $row[1];
    $this->orgcity = $row[2];
    $this->orgprovince = $row[3];
    $this->orgzipcode = $row[4];

    $line=fgets($fh);
    $row = explode(";", $line);
    $this->directorname = $row[0];
    $this->directortel = $row[1];
    $this->directoremail = $row[2];
    $this->directorno = $row[3];

    $line=fgets($fh);
    $row = explode(";", $line);
    $this->summarydaystart = (int)$row[0];

    $line=fgets($fh);
    $row = explode(";", $line);
    $this->isofficialsplit = (int)$row[0];

    fclose ($fh);
}

function savedata(){

    $fh = fopen($this->datafilename, 'w') or die("can't open file ".$this->datafilename);

    fwrite($fh, "$this->orgno;$this->orgname;$this->testday;$this->checkno;$this->calc;\n");
    fwrite($fh, "$this->orgaddresse1;$this->orgaddresse2;$this->orgcity;$this->orgprovince;$this->orgzipcode;\n");
    fwrite($fh, "$this->directorname;$this->directortel;$this->directoremail;$this->directorno;\n");
    fwrite($fh, "$this->summarydaystart;\n");
    fwrite($fh, "$this->isofficialsplit;\n");

    fclose ($fh);
}

}

?>