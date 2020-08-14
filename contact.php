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

//MANAGE POST REQUEST
$reg_errors = array();
if (isset($_POST['cere_oferta']) && $_POST['cere_oferta'] == 'contact') {
        $departament = $_POST['departament'];
        if (preg_match('/^[A-Z \'.-]{2,40}$/i', $_POST['nume'])) {
            $nume = $_POST['nume'];  
        } else {
            $reg_errors['nume'] = 'Numele persoanei de contact';
        }// END OF first_name IF/ELSE $reg_errors
    
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $email = $_POST['email'];
        } else {
            $reg_errors['email'] = 'Adresa de mail valida';
        }// END OF email IF/ELSE $reg_errors
    
        if (preg_match('/^[0-9 \'.-]{2,15}$/', $_POST['telefon'])) {
            $telefon = $_POST['telefon'];  
        } else {
            $reg_errors['telefon'] = 'Numarul de telefon';
        }// END OF first_name IF/ELSE $reg_errors
        
        if (isset($_POST['extra'])) {
        $extra = filter_var($_POST['extra'], FILTER_SANITIZE_STRING);
        }
        
        if (!empty($_POST['acord'])) {
            $acord = $_POST['acord'];
        } else {
            $reg_errors['acord'] = 'Bifeaza casuta';
        }
        
// IF NO ERRORS AND CART IS NOT ALREADY EMPTY (TO AVOID SPAM)
    if (empty($reg_errors)) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $catre = "vanzari@erulment.ro";
        $subiect = 'Cerere contact catre departamentul ' . $departament;
        $mail_client = '
        <html>
            <head>
                <title>Cerere Oferta serie fara stoc</title>
            </head>
            <body>
                <h3>Va multumim pentru cerere! Va rugam sa verificati detaliile de contact: </h3>';
                $mail_client .= '<br /><strong>Contact</strong>: ' . $_POST['nume'] . ' <strong>Tel</strong>: ' . $_POST['telefon'] . ' <strong>Email</strong>: ' . $_POST['email'] . '<br />';
                if (!empty($extra)) {
                    $mail_client .= "<strong>Cereri Suplimentare: </strong><pre><i>$extra</i></pre>";
                }
                $mail_client .= '</table>
                    <p>Ne angajam sa va contactam in cel mai scurt timp posibil.</p>
                    <p><a href="http://www.erulment.ro/contact.html">Nu ezitati sa ne contactati direct prin canalele disponibile pentru orice detaliu sau modificare.</a></p>
                    <blockquote>Cu stima,<br />Echipa <a href="http://www.erulment.ro">erulment.ro</a></blockquote>';
                $mail_vanzari = $mail_client;
                $mail_vanzari .= '<p>Adresa ip cerere: <a style="text-decoration:none; text-weight:bold;" href="http://magadyla.ro/ip/rezultat?verificare=' . $ip . '" target="_blank">' . $ip . '</a></p>
               </body>
            </html>';
                $mail_client .= '</body></html>';
        
        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers_client = $headers;
        $headers_client .= 'From: erulment.ro <vanzari@erulment.ro>';
        $headers .= 'From: ' . $email . ' < ' . $email . '>' . "\r\n";
        // Mail it
        if (mail($catre, $subiect, $mail_vanzari, $headers)) {
            mail($email, $subiect, $mail_client, $headers_client);
            $_POST = array();
            $location = './index.php?cerere=true&email=' . $email;
            header("Location: $location");  
        }
    }
}

// INCLUDE HEADER AND SET $page_title, THE META DESC AND KEYWORDS
$page_title = "Contact | Rulmenti Romania | Catalog Comenzi Online si Distributie"; 
$meta_desc = 'Pagina de Contact cuprinde toate detaliile necesare pentru a lua legatura cu noi';
$meta_keys = 'telefon,adresa,skype,contact,rulment cu ace,rulment cu bile,rulment cu role,ace,bile,role,rulment,toate,dimensiunile,dimensiuni';
include('./theme/head.html');
?>

<div class="row-fluid row"></div><!-- for the first child css -->
<div class="row-fluid row">

<?php include('./theme/searchForm.html'); ?>
    <div class="span12">
        <div class="hero-unit fluidheight">
<?php 
if (!empty($reg_errors)) {
?>
            <h3 class="center-align" id="messageAction"><span>Completeaza toate detaliile obligatorii pentru a putea face solicitarea</span></h3>
            <script type="text/javascript">$('#messageAction').delay(2000).fadeOut(1000);</script>
<?php
}////END OF IF/ELSE IF $cerere or $reg_errors
?>
            <div class="pull-right formPages">
                <div class="despre">
                <table class="center-align table table-condensed table-responsive" style="border:none;" id="content">
                     <tbody>
                        <tr>
                            <th colspan="4" style="border: 0;">
                                <h1 class="center-align" id="formular">Informatii de contact</h1>       
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2" class="tableBlock" style="vertical-align: top;">
                                <form action="" method="post">
                                <table class="center-align table table-condensed table-responsive" style="background-color: inherit;border:none;" id="tableForm">
                                    <tbody>
                                        <tr>
                                            <th colspan="2" style="text-align:center;border: 0;"><strong>Formular</strong></th>
                                        </tr>
<?php                                               
require_once('./incl/form_functions.inc.php');
?> 
<!-- form inputs -->    
                        <tr>
                            <td class="right-align" style="padding-right: 20px;">Departament<span class="muted credit"></span></td>                   
                            <td class="left-align">                                            
                                <select name="departament" id="persoana">
                                    <option value="VANZARI" class="center-align" selected="selected">Vanzari</option>
                                    <option value="FACTURARE" class="center-align">Facturare</option>
                                    <option value="LIVRARI" class="center-align">Livrari</option>
                                    <option value="INFO" class="center-align">Info</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="right-align" style="padding-right: 20px;">Nume<span class="muted credit">*</span> </td>
                            <td class="left-align"><?php create_form_input('nume', 'text', $reg_errors, 'placeholder="Persoana de contact"'); ?></td>
                        </tr>
                        <tr>
                            <td class="right-align" style="padding-right: 20px;">Email<span class="muted credit">*</span> </td>
                            <td class="left-align"><?php create_form_input('email', 'text', $reg_errors, 'placeholder="Adresa email"'); ?></td>
                        </tr>
                        <tr>
                            <td class="right-align" style="padding-right: 20px;">Telefon<span class="muted credit">*</span> </td>
                            <td class="left-align"><?php create_form_input('telefon', 'text', $reg_errors, 'placeholder="Persoana de contact"'); ?></td>
                        </tr>
                        <tr>
                            <td class="right-align" style="padding-right: 20px;">Extra&nbsp;&nbsp;</td>
                            <td class="left-align"><?php create_form_input('extra', 'textarea', $reg_errors, 'placeholder="Motivul pentru care doriti sa va contactam"'); ?></td>
                        </tr>
                        <tr>
                        <td colspan="2" style="padding-top: 15px; padding-bottom: 0px;">
                        <a target="_blank" href="./termeni.html">Termeni si Conditii</a>&nbsp;&nbsp;
                            <label style="display: inline-block; <?php if (array_key_exists('acord', $reg_errors)) {echo 'color: #f37575; font-weight: bold';} ?>">Sunt de acord* <input type="checkbox" style="width: 20px; height: 20px; padding: 0; margin:0; vertical-align: middle; position: relative; top: -1px; *overflow: hidden;" id="acord" name="acord" value="1"
                            <?php if (isset($_POST['acord'])) echo ' checked'?>/></label></td></tr> 
                        <tr>
                            <td colspan="2">
                            <input type="hidden" name="cere_oferta" value="contact"/>
                            <input type="submit" value="Trimite Cerere" class="btn" id="trimitComanda"/>
                            </td>
                        </tr>  
<!-- end form inputs -->  
                                    </tbody>
                                </table>
                                </form>
                            </td>
                            <td colspan="2" class="tableBlock" style="vertical-align: top;">
                                <table class="table table-condensed table-responsive" style="background-color: inherit; font-weight: normal;border: none;">
                                    <tbody>
                                    <tr>
                                            <th colspan="2" style="text-align:center;border:0;">Detalii firma</th>
                                        </tr>
                                        <tr>
                                            <td class="left-align">Denumire firma:</td>
                                            <td class="left-align">BEARINGS MARKET S.R.L.</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                        </tr>                                     
                                        <tr>
                                            <td class="left-align">Telefon:</td>
                                            <td class="left-align">+40 757 302 074</td>
                                        </tr>                                        
                                        <tr>
                                            <td class="left-align">Fax:</td>
                                            <td class="left-align">+40 374 094 000</td>
                                        </tr>   
                                        <tr>
                                            <td class="left-align">Skype:</td>
                                            <td class="left-align">live:thebearingsmarket</td>
                                        </tr>                                    	
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                            </td>
                        </tr>
        </tbody>    
                </table> 
                </div>
            </div>
<?php include('./theme/informatiiNav.html')?>
        <div class="google-maps" style="display: block;">
        <!-- iframe comes from iframeMap.js -->
        </div>
        </div><!--/hero-unit-->                                             
    </div><!--/span12-->
</div><!--/row-fluid-->

    <!--/.fluid-container-->
<?php
// INCLUDE FOOTER
include('./theme/foot.html');
?>