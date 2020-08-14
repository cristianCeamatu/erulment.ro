<?php
// GET DE CONFIG FILE
require('./incl/config.inc.php');

// SET USER SESSION FOR THE CART
if (isset($_COOKIE['id_lista_cumparaturi'])) {
    $uid = $_COOKIE['id_lista_cumparaturi'];
} else {
    $uid = md5(uniqid('biped', true));
}
setcookie('id_lista_cumparaturi', $uid, time()+(60*60*24*30));

// MYSQL CONNECTION
require(MYSQL);

// MANAGE POST AND GET REQUESTS FROM THE FORMS (send emails, clean cart, add, remove items) 
require('./incl/manageForms.inc.php');

// http://www.catchmyfame.com/2007/07/28/finally-the-simple-pagination-class/comment-page-10/
require_once('./incl/paginator.class.2.php');

// MULTIPLE SEARCH QUERY FOR KEY OR DIMENSIONS, BASED ON $_GET
require('./incl/searchQuery.inc.php');

// INCLUDE THE HEADER
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
    echo '<div class="messageAction">' . $message_action . '</div>';
}

//SEARCH RESULTS TABLE WITH PAGINATION
include('./theme/cauta.html');

//CART CONTENTS OR DISPLAY EMPTY CART  ($rezultate_cart from stored procedure in the head)
if ($rezultate_cart > 0) { // Products to show!
	include('./theme/mainCart.html');
} else { // Empty cart!
	include('./theme/mainEmptyCart.html');
}

//cauta.html HAS  mysqli_next_result($dbc) ONLY IF WE HAVE RESULTS 
if (!$num_rezultate) {
    mysqli_next_result($dbc);
}
echo '<h3 class="muted center-align tags">Altii au mai cautat: ';
if ($dbc) {
    $tags = "";
    $rez_cautari = mysqli_query($dbc, "SELECT cautare FROM cautari ORDER BY hits DESC LIMIT 15");
    while(list($tag) = mysqli_fetch_array($rez_cautari, MYSQLI_NUM)) {
        $tags .= " <a class=\"muted\" href=\"./cauta.html?q=$tag\">$tag</a> ";   
        }
    echo trim($tags);
    }
?>
            
            </h3>                                               
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