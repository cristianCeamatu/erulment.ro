<?php

// SET USER SESSION FOR THE CART
if (isset($_COOKIE['id_lista_cumparaturi'])) {
    $uid = $_COOKIE['id_lista_cumparaturi'];
} else {
    $uid = md5(uniqid('biped', true));
}
setcookie('id_lista_cumparaturi', $uid, time()+(60*60*24*30));

if (!isset($_GET['ip']) || !is_numeric($_GET['ip'])) {
    header('Location: ./index.php');
    die();
} else {
    $id_produs = $_GET['ip'];
}

// GET DE CONFIG FILE
require('./incl/config.inc.php');

// GET MYSQL CONNECTION
require(MYSQL);

$q_produs = "SELECT produs.nume AS rulment, marca.nume AS marca, marca.imageProdus AS imagine, produs.id_produs, produs.interior, produs.exterior, produs.latime, produs.masa
                  FROM produs 
                  LEFT JOIN marca ON produs.id_marca = marca.id_marca
                  WHERE produs.id_produs = $id_produs
                  LIMIT 1";
$r_produs = mysqli_query($dbc, $q_produs);
if (mysqli_num_rows($r_produs) == 1) {
$produs = mysqli_fetch_array($r_produs, MYSQLI_ASSOC);
} else {
    header('Location: ./index.php');
    die();   
}

// MANAGE POST AND GET REQUESTS FROM THE FORMS (send emails, clean cart, add, remove items) 
require('./incl/manageForms.inc.php');

// INCLUDE HEADER AND SET $page_title, THE META DESC AND KEYWORDS
$page_title = "Rulment seria " . $produs['rulment'] . " | " . $produs['marca'] . " | Rulmenti Romania | Catalog Comenzi Online si Distributie";  
$meta_desc = 'Rulment ' . $produs['rulment'] . ' produs de ' . $produs['marca'] . ' dimensiuni ' . $produs['interior'] . 'x' . $produs['exterior'] . 'x' . $produs['latime'] . 'mm';
$meta_keys = $produs['rulment'] . ',' . $produs['marca'] . ',mm,rulment,bile rulment,role rulment,ace,bile,role,rulmenti';
include('./theme/head.html');

?>
<div class="container-fluid mainContent">
    <div class="row-fluid row"></div><!-- for the first child css -->
    <div class="row-fluid row">
<?php include('./theme/searchForm.html'); ?>
    <div class="span12">
        <div class="hero-unit fluidheight">
<?php 
//MESSAGE FOR POST AND GEST REQUESTS
if (isset($message_action)) {
    echo $message_action;
}

//GET THE produs Display table
include('./theme/produs.html');

//CART CONTENTS OR DISPLAY EMPTY CART
mysqli_next_result($dbc);
$r_cart = mysqli_query($dbc, "CALL get_cart_contents('$uid')");
$rezultate_cart = mysqli_num_rows($r_cart);
if ($rezultate_cart > 0) { // Products to show!
	include('./theme/mainCart.html');
} else { // Empty cart!
	include('./theme/mainEmptyCart.html');
}
?>                                             
        </div>
    </div>
<!--/row-->

        </div>

        <!--/row-->
    </div>
    <!--/.fluid-container-->
<script type="text/javascript" src="./theme/js/searchQuery.js"></script>
<?php
include('./theme/foot.html');
?>