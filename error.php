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
        
<div class="alert alert-danger">Upsss! Eroare de sistem. Ne pare rau, deja am primit semnalarea si vom rezolva foarte repede.
<br />Va rugam incercati mai tarziu.</div>


        </div><!--/hero-unit-->
    </div><!--/span12-->
</div><!--/row-fluid-->     
<script type="text/javascript" src="./theme/js/searchQuery.js"></script>
<?php
// INCLUDE FOOTER
include('./theme/foot.html');
?>
