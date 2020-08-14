<?php
// GET DE CONFIG FILE
require('../../incl/config.inc.php');

// SET USER SESSION FOR THE CART
if (isset($_COOKIE['id_lista_cumparaturi'])) {
    $uid = $_COOKIE['id_lista_cumparaturi'];
} else {
    $uid = md5(uniqid('biped', true));
}
setcookie('id_lista_cumparaturi', $uid, time()+(60*60*24*30));

// GET MYSQL CONNECTION
require('../.' . MYSQL);

// ADD CART MANAGEMENT FUNCTIONS (CHECK POST AND GET ARRAYS))
require('../../incl/manageForms.inc.php');

// INCLUDE HEADER
include('./views/head.html');
?>

<div class="row-fluid row"></div><!-- for the first child css -->
<div class="row-fluid row">
<?php include('../../theme/searchForm.html'); ?>
    <div class="span12">
        <div class="hero-unit fluidheight">
<?php
//MESSAGE FOR POST AND GEST REQUESTS
if (isset($message_action)) {
    echo $message_action;
}
// STOC TABLE + PAGINATOR (1 stored procedure)
include('./views/homestoc.html');

//CART CONTENTS OR DISPLAY EMPTY CART
mysqli_next_result($dbc);
$r_cart = mysqli_query($dbc, "CALL get_cart_contents('$uid')");
$rezultate_cart = mysqli_num_rows($r_cart);
if ($rezultate_cart > 0) { // Products to show!
	include('./views/mainCart.html');
} else { // Empty cart!
	include('./views/mainEmptyCart.html');
}
?>                                                      
        </div>
    </div>
<!--/row-->

        </div>

        <!--/row-->
    </div>
    <!--/.fluid-container-->
<script type="text/javascript" src="../../theme/js/searchQuery.js"></script>
<?php
// INCLUDE FOOTER
include('./views/foot.html');
?>