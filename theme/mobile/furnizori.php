<?php
require('./incl/config.inc.php');
require(MYSQL);
include('./theme/bearing/head.html');
if ($dbc) {
    $query = "SELECT * FROM marcatest";
    $result = mysqli_query($dbc, $query);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
?>
<ul>
 <li><a href="http://localhost/eRulmenti/cauta.php?q=<?php echo $row['id_marca']; ?>"><?php echo $row['nume_marca'] ?></a></li>
</ul>
<?php   
    }// END OF WHILE
} else {
    trigger_error("Something went wrong. Please try again later.");
}// END OF IF/ELSE $dbc

include('./theme/bearing/foot.html');
?>
