<?php
$reg_errors = array();

 //DISPLAY ONLY WHEN CERERE IS TRUE
if (isset($_GET['cerere'], $_GET['email']) && $_GET['cerere'] == 'true' && empty($reg_errors)) {
    $message_action = '<h3 class="center-align" id="messageAction"><span>Cererea a fost trimisa!</span></h3>';
    $_GET = array();
} else if (isset($_GET['subscriere'], $_GET['email']) && $_GET['subscriere'] == 'true' && empty($reg_errors)) {
    $message_action = '<h3 class="center-align" id="messageAction"><span>Cererea a fost trimisa!</span></h3>';
}////END OF IF/ELSE IF $cerere or $reg_errors



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
if (isset($_POST['cere_oferta'])) {
    if (isset($_POST['persoana'])){
      $persoana = strip_tags($_POST['persoana']);
    } else {
      $persoana = 'fizica';
    }
    if ($persoana == 'juridica') {
        if (preg_match('/^[A-Z \'.-]{2,40}$/i', $_POST['firma']))  {
            $firma = strip_tags(trim($_POST['firma']));
        } else {
            $reg_errors['firma'] = 'Numele firmei';
        }
        if (preg_match('/^[A-Z0-9 \'.-]{2,16}$/i', $_POST['cif'])) {
            $cif = strip_tags(trim($_POST['cif']));
        } else {
            $reg_errors['cif'] = 'Codul Fiscal al firmei';
        }// END OF first_name IF/ELSE $reg_errors
    }

        if (preg_match('/^[A-Z \'.-]{2,40}$/i', $_POST['nume'])) {
            $nume = strip_tags(trim($_POST['nume']));
        } else {
            $reg_errors['nume'] = 'Numele persoanei de contact';
        }// END OF first_name IF/ELSE $reg_errors

        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $email = strip_tags(trim($_POST['email']));
        } else {
            $reg_errors['email'] = 'Adresa de mail valida';
        }// END OF email IF/ELSE $reg_errors

        if (preg_match('/^[0-9 \'.-]{2,15}$/', $_POST['telefon'])) {
            $telefon = strip_tags(trim($_POST['telefon']));
        } else {
            $reg_errors['telefon'] = 'Numarul de telefon';
        }// END OF first_name IF/ELSE $reg_errors

        if (isset($_POST['extra'])) {
        $extra = strip_tags(filter_var($_POST['extra'], FILTER_SANITIZE_STRING));
        }

        if (!empty($_POST['acord'])) {
            $acord = $_POST['acord'];
        } else {
            $reg_errors['acord'] = 'Bifeaza casuta';
        }

    if ($_POST['cere_oferta'] == 'formular') {
        if (isset($_POST['seria']) && !empty($_POST['seria']) && (strlen($_POST['seria']) < 40) ) {
            $seria = filter_var(strip_tags(trim($_POST['seria'])), FILTER_SANITIZE_STRING);
        } else {
            $reg_errors['seria'] = 'Numarul seriei';
        }// END OF first_name IF/ELSE $reg_errors
        if (isset($_POST['marca'])) {
        $marca = filter_var(strip_tags(trim($_POST['marca'])), FILTER_SANITIZE_STRING);
        }
    }

    if ($_POST['cere_oferta'] == 'cos') {

    // IF NO ERRORS AND CART IS NOT ALREADY EMPTY (TO AVOID SPAM)
    if (empty($reg_errors)) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $catre = "info@bearingsmarket.ro";
        $subiect = 'Cerere Oferta';
        $mail_client = '
        <html>
            <head>
                <title>Cerere Oferta Rulmenti</title>
            </head>
            <body>
                <style>
                tr:hover{background-color:#f5f5f5}
                </style>
                <h3>Va multumim pentru cerere! Va rugam sa verificati detaliile de contact: </h3>
                <p><strong>Persoana</strong>: ' . $persoana . ' ';
                if (isset ($firma)) {
                    $mail_client .= "<strong>Firma</strong>: $firma <strong>C.I.F.</strong>: $cif";
                }
                $mail_client .= '<br /><strong>Contact</strong>: ' . $_POST['nume'] . ' <strong>Tel</strong>: ' . $_POST['telefon'] . ' <strong>Email</strong>: ' . $_POST['email'] . '<br />';
                if (!empty($extra)) {
                    $mail_client .= "<strong>Cereri Suplimentare:<br /></strong><pre><i>$extra</i></pre>";
                }
                $mail_client .= '</p>
                <p>Ati solicitat lista de pret pentru urmatoarele produse:
            <table style="border-collapse: collapse; overflow-x: scroll;">
                <tr>
                    <th style="text-align: left; border-bottom: 1px solid #ddd; vertical-align: middle; padding: 8px 8px 8px 30px; background-color: #3BC8F7; color: white;">Rulment</th>
                    <th style="text-align: left; border-bottom: 1px solid #ddd; vertical-align: middle; padding: 8px 8px 8px 30px; background-color: #3BC8F7; color: white;">Marca</th>
                    <th style="text-align: left; border-bottom: 1px solid #ddd; vertical-align: middle; padding: 8px 8px 8px 30px; background-color: #3BC8F7; color: white;">Dimensiune(mm)</th>
                    <th style="text-align: left; border-bottom: 1px solid #ddd; vertical-align: middle; padding: 8px 8px 8px 30px; background-color: #3BC8F7; color: white;">Greutate(Kg)</th>
                    <th style="text-align: left; border-bottom: 1px solid #ddd; vertical-align: middle; padding: 8px 8px 8px 30px; background-color: #3BC8F7; color: white;">Cant.</th>
                </tr>';
        // Get the cart contents:
        $query_cart = "SELECT lista.user_session_id, lista.id_produs, produs.nume AS rulment, marca.nume AS furnizor, CONCAT_WS(\"x\", produs.interior, produs.exterior, produs.latime) AS dimensiune, lista.cantitate AS cant, produs.masa
               FROM lista
               INNER JOIN produs ON lista.id_produs = produs.id_produs
               INNER JOIN marca ON produs.id_marca = marca.id_marca WHERE user_session_id = '$uid'";
        $r_cart = mysqli_query($dbc, $query_cart);
        while ($cos = mysqli_fetch_array($r_cart, MYSQLI_ASSOC)) {
            $mail_client .= '<tr>
                       <td style="text-align: left; border-bottom: 1px solid #ddd; vertical-align: middle; padding: 8px 8px 8px 30px;"><a href="http://www.erulment.ro/' . $cos['id_produs'] . '-rulment-' . str_replace(array(' ', '/', '*'),array('-', '-', ''), $cos['rulment']) . '-' . $cos['furnizor'] . '.html" style="text-decoration: none; font-weight: bold">' . $cos['rulment'] .'</a></td>
                       <td style="text-align: left; border-bottom: 1px solid #ddd; vertical-align: middle; padding: 8px 8px 8px 30px;">' . $cos['furnizor'] . '</td>
                       <td style="text-align: left; border-bottom: 1px solid #ddd; vertical-align: middle; padding: 8px 8px 8px 30px;">' . $cos['dimensiune'] .'</td>
                       <td style="text-align: center; border-bottom: 1px solid #ddd; vertical-align: middle; padding: 8px 8px 8px 30px;">' . $cos['masa'] .'</td>
                       <td style="text-align: center; border-bottom: 1px solid #ddd; vertical-align: middle; padding: 8px 8px 8px 30px;">' . $cos['cant'] . '</td>
                       </tr>';
        }
        $mail_client .= '</table>
                    <p>Ne angajam sa va trimitem cea mai buna oferta de pe piata in cel mai scurt timp posibil.</p>
                    <p><a href="http://www.erulment.ro/contact.html">Nu ezitati sa ne contactati pentru orice detaliu sau modificare.</a></p>
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
            $q_goleste = "DELETE FROM lista WHERE user_session_id = '$uid'";
            $goleste_cos = mysqli_query($dbc, $q_goleste);
            $_POST = array();
            $location = './index.php?cerere=true&email=' . $email;
            header("Location: $location");
        }
    }
  } else if ($_POST['cere_oferta'] == 'formular') {
// IF NO ERRORS AND CART IS NOT ALREADY EMPTY (TO AVOID SPAM)
    if (empty($reg_errors) && isset($acord)) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $catre = "info@bearingsmarket.ro";
        $subiect = 'Cerere oferta seria ' . $seria . ' fara stoc';
        $mail_client = '
        <html>
            <head>
                <title>Cerere Oferta serie fara stoc</title>
            </head>
            <body>
                <h3>Va multumim pentru cerere! Va rugam sa verificati detaliile de contact: </h3>
                <p><strong>Persoana</strong>: ' . $persoana . ' ';
                if (isset ($firma)) {
                    $mail_client .= "<strong>Firma</strong>: $firma <strong>C.I.F.</strong>: $cif";
                }
                $mail_client .= '<br /><strong>Contact</strong>: ' . $_POST['nume'] . ' <strong>Tel</strong>: ' . $_POST['telefon'] . ' <strong>Email</strong>: ' . $_POST['email'] . '<br />';
                if (!empty($extra)) {
                    $mail_client .= "<strong>Cereri Suplimentare: </strong><pre><i>$extra</i></pre>";
                }
                $mail_client .= '</p>
                <p>Ati solicitat oferta de pret pentru <strong>seria ' . $seria . '</strong> pe care nu ati gasit-o pe stoc.';
        $mail_client .= '</table>
                    <p>Ne angajam sa va trimitem cea mai buna oferta de pe piata in cel mai scurt timp posibil.</p>
                    <p><a href="http://www.erulment.ro/contact.html">Nu ezitati sa ne contactati pentru orice detaliu sau modificare.</a></p>
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
  } //END OF IF cere_oferta = cos
}

    if (isset($_POST['adauga'])) {
        $pid = $_POST['id_produs'];
        $cant = ($_POST['cantitate'] > 0)?$_POST['cantitate']:1;
        $verifica_produs = mysqli_query($dbc, "SELECT COUNT(*) AS total FROM lista WHERE user_session_id = '$uid' AND id_produs = $pid");
        $rezultat_verificare = mysqli_fetch_array($verifica_produs, MYSQLI_ASSOC);
        $produs_in_lista = $rezultat_verificare['total'];
        if ($produs_in_lista == 0) {
            $q_adauga = "INSERT INTO lista (user_session_id, id_produs, cantitate) VALUES ('$uid', $pid, $cant)";
            $adauga = mysqli_query($dbc, $q_adauga);
            $message_action = '<h3 class="center-align" id="messageAction"><span>Rulment adaugat in lista (' . $cant . ' buc)</span></h3>';
        } else if ($produs_in_lista == 1){
            $q_adauga = "UPDATE lista SET cantitate = cantitate+$cant WHERE user_session_id = '$uid' AND id_produs = $pid";
            $adauga = mysqli_query($dbc, $q_adauga);
            $message_action = '<h3 class="center-align" id="messageAction"><span>Cantitate actualizata (' . $cant .' buc)</span></h3>';
    }
        $_POST = array();
    }

    if (isset($_POST['remove_from_cart'])) {
        $r_pid = $_POST['remove_rulment'];
        $verifica_produs = mysqli_query($dbc, "SELECT COUNT(*) AS total FROM lista WHERE user_session_id = '$uid' AND id_produs = $r_pid");
        $rezultat_verificare = mysqli_fetch_array($verifica_produs, MYSQLI_ASSOC);
        $produs_in_lista = $rezultat_verificare['total'];
        if ($produs_in_lista == 1) {
            $q_remove = "DELETE FROM lista WHERE user_session_id = '$uid' AND id_produs = $r_pid LIMIT 1";
            $remove_prod = mysqli_query($dbc, $q_remove);
            $message_action = '<h3 class="center-align" id="messageAction"><span>Rulment scos din lista</span></h3>';
        }
        $_POST = array();
    }

    if (isset($_POST['subscriere'])) {
        $email = $_POST['email'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $catre = "info@bearingsmarket.ro";
        $subiect = "Subscriere erulment";
        $mail = '<p><strong>' . $email . '</strong> Doreste subscriere pentru noi oferte erulment.</p>
                <p>Adresa ip cerere: <a style="text-decoration:none; text-weight:bold;" href="http://ip-api.com/#' . $ip . '" target="_blank">' . $ip . '</a></p>';
         // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . $email . ' < ' . $email . '>' . "\r\n";
        if (mail($catre, $subiect, $mail, $headers)) {
           $_POST = array();
            $location = './index.php?cerere=true&email=' . $email;
            header("Location: $location");
        }
    }
 }

if (!empty($reg_errors)) {
    $message_action = '<h3 class="center-align" id="messageAction"><span>Completeaza toate detaliile obligatorii pentru a putea solicita oferta</span></h3>';
}
// OMIT THE CLOSING TAG TO AVOID HEADERS ALREADY SENT ERROR
