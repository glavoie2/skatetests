<?php
include_once 'includes/divers.php';
include_once 'includes/skatingtestclass.php';
include_once 'includes/testtypeclass.php';
include_once 'includes/testsummaryclass.php';

$errcount=0;
$rowcount=0;
$excelrowcount=2;

if ($case==3){

    $panelfilename = $_SESSION['panelfilename'];
    //var_dump($_SESSION['panelfilename']);

    $validation = new testTypeClass;
    $errmsg = $validation->importTests($panelfilename);
   
    $rowcount = count($validation->skatingarray);
    
    if (strlen($errmsg)>0) { //Field count error
        echo $errmsg;
        ?>
        </table>
        <br/><br/><br/><br/>
        <?php
    }
    else{
        echo "<br/>\n";
        if ($rowcount>0){
            echo "<p><strong>Importation des $rowcount lignes compl�t�es avec succ�s!</strong></p>\n";
            $summaryfilename = "datafiles/".strtolower($_SESSION['loginname'])."summary.txt";
            $summary = new testSummaryClass($summaryfilename);
            $summary->parsefile();
            
            if (isset($_SESSION['querystring'])) {
                $querystring = $_SESSION['querystring'];
                //echo $querystring."<br>";
                
                $found = strpos($querystring,"sb=s");
                if ($found===false) $sb_s_checked = "";
                else $sb_s_checked = "checked";

                $found = strpos($querystring,"sb=c");
                if ($found===false) $sb_c_checked = "";
                else $sb_c_checked = "checked";

                $found = strpos($querystring,"os=on");
                if ($found===false) $os_checked = "";
                else $os_checked = "checked";
              
            }
            else{
                $sb_s_checked = "checked";
                $sb_c_checked = "";
				$os_checked = "";
            }
            ?>
            <form name="gentest" enctype="text/plain" action="index.php" method="get">
            <input type="hidden" name="c" value="8" />
            <input type="radio" name="sb" value="s" <?php echo $sb_s_checked; ?> />Aucun tri (selon l'ordre copi�)<br/>
            <input type="radio" name="sb" value="c" <?php echo $sb_c_checked; ?> />Trier par cat�gories de tests (journ�e > diciplines > niveaux > activit�s)<br/><br/>
			<input type="checkbox" name="os" <?php echo $os_checked; ?> />Tests en alternance par �valuateur (r�partition �gale entre les �valuateurs)<br/><br/>
            <input name="submit" type="submit" value="&nbsp;Soumettre...&nbsp;"/>
            </form>

            <br/><br/><br/>
            <?php
        }
        else{
            ?>
            <br/>
            <p><span style='color:red'><strong>Aucune donn�es valides import�es!</strong></span></p>
            <br/>
            <?php
        }
    }

}
?>
