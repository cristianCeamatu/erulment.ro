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

// ADD CART MANAGEMENT FUNCTIONS (CHECK POST AND GET ARRAYS))
require('./incl/manageCart.inc.php');

// http://www.catchmyfame.com/2007/07/28/finally-the-simple-pagination-class/comment-page-10/
require_once('./incl/paginator.class.2.php');

// MULTIPLE SEARCH QUERY FOR KEY OR DIMENSIONS, BASED ON $_GET
require('/incl/searchQuery.inc.php');

// INCLUDE THE HEADER
include('./theme/bearing/head.html');

 /*QUERY ALREADY IN THE HEAD
$numar_randuri = mysqli_query($dbc, "SELECT count(*) as total from produstest");
$data = mysqli_fetch_array($numar_randuri, MYSQLI_ASSOC);
$num_randuri = $data['total'];*/ 


?>

<div class="container-fluid mainContent">
    <div class="row-fluid row"></div><!-- for the first child css -->
    <div class="row-fluid row">
<?php include('./theme/bearing/searchForm.html'); ?>
    <div class="span12">
        <div class="hero-unit fluidheight">
<?php 
//DISPLAY ONLY WHEN CERERE IS TRUE
if (($cerere == 'true') && isset($email)) { 
?>
            <h3 class="center-align">Cererea a fost trimisa! Ti-am trimis un mail cu solicitarea la adresa <?php echo $email; ?></h3>
<?php 
} else if (!empty($reg_errors)) {
?>
            <h3 class="center-align">Cererea nu a fost trimisa pentru ca nu ai completat detaliile obligatorii.</h3>
<?php
}//END OF IF/ELSE IF $cerere or $reg_errors
?>
            <div class="span13 pull-left">
            <table class="table table-condensed table-hover table-responsive">
            <tbody>
            <tr id="pagination"><td style="width= 100%; padding-bottom: 14px" class="center-align muted credit creditfooter"><?php echo $mesaj_rezultate; ?></td></tr>
            </tbody>
            </table>
                        <table class="table table-condensed table-hover table-responsive">
                        <thead>
                            <tr>
                                <th style="width=20%" class="left-align">Rulment</th>
                                <th style="width=5%" class="center-align">Marca</th>
                                <th style="width=5%" class="center-align">Dimensiune(mm)</th>
                                <th style="width=5%" class="center-align">Cant.</th>
                                <th style="width=15%" class="center-align"></th>
                            </tr>
                        </thead>
                        <tbody>
<?php
 if ($num_rezultate) {
    $rezultate = mysqli_query($dbc, $query);
    while ($row = mysqli_fetch_array($rezultate, MYSQLI_ASSOC)) { ?>
    <tr style="width: 100%;">
        <td class="left-align"  style="width: 40%"><a href="./produs.php?ip=<?php echo $row['id_produs'] ?>"><?php echo $row['rulment']; ?></a></td>
        <td class="center-align"  style="width: 15%"><?php echo $row['marca'] ?></td>
        <td class="center-align" id="dimensiune" style="width: 15%"><?php echo $row['dimensiune'] ?></td>
                <form action="" method="post" style="padding: 0px;">
        <td class="center-align"  style="width: 15%"><input name="cantitate" type="text" autocomplete="off" placeholder="1" maxlength="3" size="3" id="cantCart"/>
        </td>
        <td class="center-align"  style="width: 15%">
                    <input type="hidden" name="id_produs" value="<?php echo $row['id_produs']; ?>"/>
                    <input type="hidden" name="adauga" value="1"/>
                    <input type="submit" value="Adauga" class="btn btn-success" id="buttonAdauga" /></td>
                </form>
    </tr>
<?php   
        }// END OF WHILE LOOP
?>
<tr id="pagination"><td colspan="5" class="center-align" id="pagination"><ul class="pagination pagination-sm"><?php if (isset($pages)) echo $pages->display_pages(); ?></ul></td></tr>
<?php
    } else {
        echo '<tr id="pagination"><td class="center-align" colspan="4" style="padding-top: 20px;">Nici un rezultat. <a href=\"./contact\">Contacteaza-ne</a> si cautam noi produsul pentru tine.';
    }// END OF IF/ELSE $num_rezultate STATEMENT
?>
                        </tbody>
                </table>
            </div>
<?php
// Get the cart contents:
$query_cart = "SELECT lista.user_session_id, lista.id_produs, produs.nume AS rulment, marca.nume AS furnizor, CONCAT_WS(\"x\", produs.interior, produs.exterior, produs.latime) AS dimensiune, lista.cantitate AS cant
               FROM lista 
               INNER JOIN produs ON lista.id_produs = produs.id_produs 
               INNER JOIN marca ON produs.id_marca = marca.id_marca WHERE user_session_id = '$uid'
               ORDER BY rulment ASC";
$r_cart = mysqli_query($dbc, $query_cart);
//$r_cart = mysqli_query($dbc, "CALL get_cart_contents('$uid')");
$rezultate_cart = mysqli_num_rows($r_cart);
if ($rezultate_cart > 0) { // Products to show!
	include('/theme/bearing/mainCart.html');
} else { // Empty cart!
	include('/theme/bearing/mainEmptyCart.html');
}
?>                                             
        </div>
    </div>
<!--/row-->

        </div>

        <!--/row-->
    </div>
    <!--/.fluid-container-->
<script type="text/javascript" src="./theme/bearing/js/searchQuery.js"></script>
<?php
include('./theme/bearing/foot.html');
?>