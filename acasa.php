<?php
// GET DE CONFIG FILE (starts session with name erulment, constants, error handling, $live))
require('./incl/config.inc.php');

// SET USER SESSION ID FOR THE CART
if (isset($_COOKIE['id_lista_cumparaturi'])) {
    $uid = $_COOKIE['id_lista_cumparaturi'];
} else {
    $uid = md5(uniqid('biped', true));
}
setcookie('id_lista_cumparaturi', $uid, time()+(60*60*24*30));

// GET MYSQLI CONNECTION
require(MYSQL);

// MANAGE POST AND GET REQUESTS FROM THE FORMS (send emails, clean cart, add, remove items) 
require('./incl/manageForms.inc.php');

// INCLUDE HEADER (1 STORED PROCEDURE)
include('./theme/head.html');
?>

<div class="row-fluid row"></div><!-- for the first child css -->
<div class="row-fluid row"><!--row-fluid-->
<?php 
include('./theme/searchForm.html');
?>
    <div class="span12"><!--span12-->
        <div class="hero-unit home"><!--hero-unit--> 
<?php
//MESSAGE FOR POST AND GEST REQUESTS
if (isset($message_action)) {
    echo $message_action;
}
// STOC TABLE + PAGINATOR (1 stored procedure)
include('./theme/acasa.html');


echo '<h3 class="muted center-align tags">Altii au cautat: ';
if ($dbc) {
    $tags = "";
    mysqli_next_result($dbc);
    $rez_cautari = mysqli_query($dbc, "SELECT cautare FROM cautari ORDER BY hits DESC LIMIT 15");
    while(list($tag) = mysqli_fetch_array($rez_cautari, MYSQLI_NUM)) {
        $tags .= " <a class=\"muted\" href=\"./cauta.html?q=$tag\">$tag</a> ";   
        }
    echo trim($tags);
    }
?>          
            </h3>                                         
        </div><!--/hero-unit-->
    </div><!--/span12-->
</div><!--/row-fluid-->

<!-- To make the search query items appear only if they are not empty -->      
<script type="text/javascript" src="./theme/js/searchQuery.js"></script>

<?php
// INCLUDE FOOTER
include('./theme/foot.html');
?>