<?php ob_start();
//conectare la baza de date
require_once('../incl/mysql.inc.php'); ?>
<!DOCTYPE html><html lang="en"><head><title>Load Products</title><body>
<style>body{font-size: 1.4em;font-family: Roboto, Arial, sans-serif;color:blue;padding:1em;}
table tr td{padding:2em;}input{font-size:1.4em;background-color:#8AD4ED;padding:1em;}</style>
<?php
echo "<table style=\"border: 3px solid #008000; width: 500px; margin-left: auto; margin-right: auto;\" cellpading=\"0\" cellspacing=\"0\"><tr><td align=\"center\" colspan=\"12\">Importa produse din fisierul *.CSV</td>
</tr><tr><td align=\"center\">";
///////////////////////////////////// CONTINUT  ////////////////////////////

if (isset($_POST['Submit'])) {
    echo "Requested<br />";
if ($_FILES['csv']['size'] > 0) { 
    //preia fisier csv 
    $file = $_FILES['csv']['tmp_name'];
    echo $file . "<br />";
    $handle = fopen($file,"r");      
    //coloane din baza de date 
    $x = 1;
    do { 
        if (isset($data[0])) {
    $q_adaugat = "INSERT INTO produse (nume, id_marca, categorie, interior, exterior, latime, masa) 
    VALUES ( '".addslashes($data[0])."', '1', '".addslashes($data[4])."', '".addslashes($data[1])."', '".addslashes($data[2])."', '".addslashes($data[3])."', '".addslashes($data[5])."')";
    $adaugat = mysqli_query($dbc, $q_adaugat);
    echo $q_adaugat;
    if (mysqli_affected_rows($adaugat) == 1) {
        $x++;
    }
        } 
    } while ($data = fgetcsv($handle,1000,",","'")); 
    //
    //redirectare 
    }echo "<br />$x Adaugate"; 
}

if (!empty($_GET['success'])) { echo "<b>Fisier importat cu succes.</b><br><br>"; } //generic success notice
echo "<form action=\"http://erulment.ro/admin/incarcareProduse.php\" method=\"post\" enctype=\"multipart/form-data\" name=\"form1\" id=\"form1\">Alege fisierul:<br /><input name=\"csv\" type=\"file\" id=\"csv\" /><input type=\"submit\" name=\"Submit\" value=\"Incarca\"/></form></td></tr></table>";
?>
</body></head></html>
<?php ob_flush(); ?>