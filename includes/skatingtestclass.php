<?php

class skatingTest
{
    public $line="";
    public $firstname="";
    public $lastname="";
    public $discipline="";
    public $disciplinestd="";
    public $level="";
    public $activity="";
    public $candiatno="";
    public $coachname="";
    public $partnername="";
    public $officialname="";
    public $officialno="";
    public $officialnamestd="";
    public $testdate="";
    public $candiatclubname="";
    public $candiatclubno="";
    public $hostclub="";
    public $isdance=false;
    public $hasactivity=false;
    public $detectcode="";
    public $patcancode="";
    public $orderno=0;
    public $warmup=0;
    public $maxgroup=0;
    public $duration=0;

public function loadTest($line, $delimiter="\t")
{
	$this->line = $line;
    $fields = explode($delimiter, $line);
	$this->lastname = trim($fields[0]);
	$this->firstname = trim($fields[1]);

	$this->discipline = trim($fields[2]);
    $tmp1 = mb_strtolower($this->discipline, 'ISO-8859-1');
    $this->disciplinestd = stripAccents($tmp1);

	$this->level = trim($fields[3]);
	$this->activity = trim($fields[4]);
	$this->candiatno = trim($fields[5]);
	$this->coachname = trim($fields[6]);
	$this->partnername = trim($fields[7]);
	$this->officialname = trim($fields[8]);
	$this->officialno = trim($fields[9]);
	$this->testdate = trim($fields[10]);
	$this->candiatclubname = trim($fields[11]);
	$this->candiatclubno = trim($fields[12]);
	$this->hostclub = trim($fields[13]);
}

public function setCalculatedValues($detectcode,$patcancode,$orderno,$warmup,$maxgroup,$duration)
{
	$this->detectcode = $detectcode;
	$this->patcancode = $patcancode;
	$this->orderno = (int)$orderno;
	$this->warmup = $warmup;
	$this->maxgroup = $maxgroup;
	$this->duration = $duration;

    $tmp1 = mb_strtolower($this->officialname, 'ISO-8859-1');
    $tmp2 = stripAccents($tmp1);
	$this->officialnamestd= $tmp2;
    
    if ($this->detectcode[0] == 'D') $this->isdance = true;
}

public function getCategory()
{
    $str = "$this->discipline - $this->level";
    //Si ce n'est pas une habileté on ajoute l'activité
    if (!(strlen($this->patcancode) == 4 && substr($this->patcancode, -2) == "SS")) $str .= " - ".$this->activity;
    
    return $str;
}

public function getCandiate()
{
    $str= "$this->firstname $this->lastname";
    return $str;
}

public function var_dump()
{
    $str = "<p>$this->firstname $this->lastname : $this->candiatno ($this->candiatclubname)<br>";
    $str .= "$this->discipline, $this->level, $this->activity : $this->detectcode, $this->patcancode<br>";
    $str .= "($this->coachname) $this->partnername, $this->officialname-$this->officialno key=$this->officialname<br>";
    $str .= "$this->testdate : $this->hostclub ($this->candiatclubno)<br>";
    $str .= "orderno=$this->orderno, warmup=$this->warmup, maxgroup=$this->maxgroup duration=$this->duration</p>";
    
    return $str;
}

}
?>