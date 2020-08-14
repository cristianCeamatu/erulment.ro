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

// GET MYSQL CONNECTION
require(MYSQL);

// MANAGE POST AND GET REQUESTS FROM THE FORMS (send emails, clean cart, add, remove items) 
require('./incl/manageForms.inc.php');

// http://www.catchmyfame.com/2007/07/28/finally-the-simple-pagination-class/comment-page-10/
require('./incl/paginator.class.2.php');

// INCLUDE HEADER AND SET $page_title, THE META DESC AND KEYWORDS
$page_title = "Termeni si Conditii | Rulmenti Romania | Catalog Comenzi Online si Distributie"; 
$meta_desc = 'Pagina termeni si conditii cuprinde toate detaliile necesare pentru a putea avea o buna colaborare cu noi';
$meta_keys = 'termeni,conditii,reguli,telefon,skype,contact,rulment cu ace,rulment cu bile,rulment cu role,ace,bile,role,rulment';
include('./theme/head.html');
?>
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

//GET termeni.html
include('./theme/termeni.html');
include('./theme/informatiiNav.html');
?>
        </div><!--/hero-unit-->                                             
    </div><!--/span12-->
</div><!--/row-fluid-->

    <!--/.fluid-container-->
<script type="text/javascript" src="./theme/js/searchQuery.js"></script>
<?php
// INCLUDE FOOTER
include('./theme/foot.html');
?>