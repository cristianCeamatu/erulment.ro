<?php
include "../../incl/config.inc.php"; //Aici $site = "http://domeniul.tld/"
include "../../incl/mysql.inc.php"; // Conectarea la baza de date, de obicei un fisier mysql.php
 
header("Content-Type: text/xml;charset=utf-8");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
/* ADD LIMIT 0,49997 to the query if more then 50000 rows */
$interogare = @mysqli_query($dbc, "SELECT produs.id_produs AS id_produs, produs.nume AS nume, marca.nume AS marca FROM produs LEFT JOIN marca ON produs.id_marca = marca.id_marca");
while($rand = @mysqli_fetch_array($interogare,MYSQLI_ASSOC)){
$serie = $rand['id_produs']."-rulment-".str_replace(' ', '-', (str_replace('/', '-', $rand['nume'])))."-".$rand['marca']; // nume din baza de date
// [time] = article date
$date = date("Y-m-d", time());
echo "<url>
     <loc>https://".BASE_URL.$serie.".html</loc>
     <changefreq>weekly</changefreq>     
     </url>";
}
echo "</urlset>";
?>