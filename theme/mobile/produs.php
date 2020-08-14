<?php

// SET USER SESSION FOR THE CART
if (isset($_COOKIE['id_lista_cumparaturi'])) {
    $uid = $_COOKIE['id_lista_cumparaturi'];
} else {
    $uid = md5(uniqid('biped', true));
}
setcookie('id_lista_cumparaturi', $uid, time()+(60*60*24*30));

if (!isset($_GET['ip']) && !is_numeric($_GET['ip'])) {
    header('Location: ./index.php');
    die();
} else {
    $id_produs = $_GET['ip'];
}

// GET DE CONFIG FILE
require('./incl/config.inc.php');

// GET MYSQL CONNECTION
require(MYSQL);

$q_produs = "SELECT produs.nume AS rulment, marca.nume AS marca, produs.id_produs, produs.interior, produs.exterior, produs.latime, produs.masa
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

// ADD CART MANAGEMENT FUNCTIONS (CHECK POST AND GET ARRAYS))
require('./incl/manageCart.inc.php');

// INCLUDE THE HEADER
include('./theme/bearing/head.html');

?>
<div class="container-fluid mainContent">
    <div class="row-fluid row"></div><!-- for the first child css -->
    <div class="row-fluid row">
<?php include('./theme/bearing/searchForm.html'); ?>
    <div class="span12">
        <div class="hero-unit fluidheight">

            <div class="span13 pull-left">
            <table class="table table-condensed table-responsive">
                <tbody>
                <tr style="width: 100%;">
                   <td style="width: 50%; height: 100%;">
                        <a href="http://www.erulment.ro" title="Home">
                            <img style="width: 300px; height: 350px;" alt="Magazin Rulmenti Online" src="./theme/bearing/images/produsLogo.png" />
                        </a>
                   </td>
                   <td class="center-align">
                                    <table class="center-align table-condensed table-responsive" id="tableForm">
                                        <tr id="pagination">
                                            <td class="center-align" colspan="2" style="font-size: 14px; padding-bottom: 10px; color: #585858">Rulment seria <?php echo $produs['rulment']; ?></td>
                                        </tr>
                                        <tr id="pagination">
                                        <td class="right-align" style="padding-right: 20px;">Denumire: </td>
                                        <td class="left-align" style="padding-right: 20px;"><span class="muted credit"><?php echo $produs['rulment']; ?></span></td>
                                        </tr>
                                        <tr id="pagination">
                                            <td class="right-align" id="firma" style="padding-right: 20px;">Marca: </td>
                                            <td class="left-align" style="padding-right: 20px;"><span class="muted credit"><?php echo $produs['marca']; ?></span></td>
                                        </tr>
                                        <tr id="pagination">
                                            <td class="right-align" style="padding-right: 20px;">Interior(d): </td>
                                            <td class="left-align" style="padding-right: 20px;"><span class="muted credit"><?php if($produs['interior'] > 0) echo $produs['interior']; else echo '--'; ?> mm</span></td>
                                        </tr>
                                        <tr id="pagination">
                                            <td class="right-align" style="padding-right: 20px;">Exterior(D): </td>
                                            <td class="left-align" style="padding-right: 20px;"><span class="muted credit"><?php if($produs['exterior'] > 0) echo $produs['exterior']; else echo '--'; ?> mm</span></td>
                                        </tr>
                                        <tr id="pagination">
                                            <td class="right-align" style="padding-right: 20px;">Latime(B): </td>
                                            <td class="left-align" style="padding-right: 20px;"><span class="muted credit"><?php if($produs['latime'] > 0) echo $produs['latime']; else echo '--'; ?> mm</span></td>
                                        </tr>
                                        <tr id="pagination">
                                            <td class="right-align" style="padding-right: 20px;">Greutate: </td>
                                            <td class="left-align" style="padding-right: 20px;"><span class="muted credit"><?php echo $produs['masa']; ?> Kg</span></td>
                                        </tr>
                                        <tr id="pagination">
                                            <td class="right-align" style="padding-right: 20px;">Disponibil: </td>
                                            <td class="left-align" style="padding-right: 20px;"><span style="color: #3BC8F7;">In Stoc</span></td>
                                        </tr>
                                            <form action="" method="post" style="padding: 0px;">
                                        <tr>
                                        <td class="right-align" style="padding-right: 20px;">Cantitate: </td>
                                        <td class="left-align">
                                        <input name="cantitate" type="text" autocomplete="off" value="1" maxlength="3" size="3" id="cantCart"/>
                                        </td>
                                        </tr>
                                        <tr id="pagination">
                                            <td colspan="2" style="width: 15%">
                                            <input type="hidden" name="id_produs" value="<?php echo $produs['id_produs']; ?>"/>
                                            <input type="hidden" name="adauga" value="1"/>
                                            <input type="submit" style="margin-top: 15px; width: 50%; padding: 4px 0px;" value="Adauga" class="btn btn-success" id="buttonAdauga"/>
                                            </td>
                                            </form>
                                        </tr> 
                                        </table>
                   </td>
                </tr>
                <tr>
                    <td colspan="2" class="left-align" style="padding: 10px 12px; font-weight: normal; line-height: 18px; text-indent: 10px;">
                        <p>Comercializam rulmentul cu seria <strong><?php echo $produs['rulment']; ?></strong> fabricat de <strong><?php echo $produs['marca']; ?></strong> respectand cele mai inalte stardande de calitate colaborand in permanenta cu furnizorul pentru a imbunatati serviciul oferit.</p>
                        <p><strong>Termenul de livrare</strong> poate fi estimat in functie de disponibilitatea din stoc sau furnizor, dupa cum urmeaza:
                            <ul id="produs">
                                <li>Pentru Romania, termenul de livrare este de minim 1 zi de la confirmarea comenzii.</li>
                                <li>Pentru Tarile din UE, timpul de livrare este de minim 2 zile de la confirmarea comenzii.</li>
                                <li>Pentru Tarile din afara UE, timpul de livrare este de minim 3 zile de la confirmarea comenzii.</li>
                            </ul>
                        </p>
                        <p><strong>Pretul de livrare</strong> se calculeaza in functie de greutate, marime si destinatie, asa cum prevad companiile de curierat, dupa cum urmeaza::
                            <ul id="produs">
                                <li>Fan Courier - doar pentru Romania</li>
                                <li>DHL - livrare la toate zonele</li>
                                <li>TNT - livrare la toate zonele</li>
                                <li>DPD - livrare la toate zonele</li>
                                <li>Posta Romana - livrare pentru toate zonele, costuri reduse dar timp mai mare de 15 de zile pentru receptia produselor.</li>
                            </ul>
                        </p>
                        <p><strong>Modalitatile de plata</strong>  disponibile sunt:
                            <ul id="produs">
                                <li>Transfer Bancar</li>
                                <li>PayPal</li>
                                <li>Plata cu Card</li>
                            </ul>
                        </p>
                        <p><strong>Timpul maxim de returnare</strong>  a produsului este de 5 zile. Produsele sunt in conformitate cu specificatiile prevazute de catre producator. Nu vom vinde produse contrafacute si care nu indeplinesc toate standardele corespunzatoare.
                        </p>
                        <p><strong>Recomandari</strong>:
                            <ul id="produs">
                                <li>Verificati de mai multe ori daca produsul indeplineste cerintele dumneavoastra.</li>
                                <li>Asigurati-va ca dimensiunile sunt identice cu produsul comandat.</li>
                                <li>Nu ezitati se ne contactati pentru orice nelamurire.</li>
                            </ul>
                        </p>
                        <p class="right-align" style="margin-top: 10px;">Cu stima,</p>
                        <p class="right-align"><a href="http://www.erulment.ro">Echipa erulment.ro</a></p>
                    </td>
                </tr>
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