<?php

if (!isset($_GET) || !array_key_exists('q', $_GET) && !array_key_exists('lungime_interioara', $_GET) && !array_key_exists('lungime_exterioara', $_GET) && !array_key_exists('latime', $_GET)) {
    // GET THE FULL STOC IF NO SEARCH FILTER IS ON
    $query = "SELECT produs.nume AS rulment, marca.nume AS marca, produs.id_produs, CONCAT_WS(\"x\", produs.interior, produs.exterior, produs.latime) AS dimensiune
                  FROM produs 
                  LEFT JOIN marca ON produs.id_marca = marca.id_marca
                  ORDER BY rulment ASC";
    $mesaj_rezultate = "STOC: Aproximativ ";
    
    
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
    $query = "SELECT produs.nume AS rulment, marca.nume AS marca, produs.id_produs, CONCAT_WS(\"x\", produs.interior, produs.exterior, produs.latime) AS dimensiune
                  FROM produs 
                  LEFT JOIN marca ON produs.id_marca = marca.id_marca";
    if ( isset($_GET['q']) && (strlen($_GET['q']) > 2) ) {
        $page_title = "Cautarea " . $_GET['q'] . " | Rulmenti Romania | Magazin Online";
        $sec = strip_tags(trim(filter_var($_GET['q'], FILTER_SANITIZE_STRING)));
        $cautare = mysqli_real_escape_string($dbc, $sec);
        $query .= " WHERE (produs.nume like \"%$cautare%\" OR marca.nume like \"%$cautare%\")";
        $mesaj_rezultate = "Cautarea $sec ";
        
        $verificaHits = mysqli_query($dbc, "SELECT hits FROM cautari WHERE cautare='$cautare' LIMIT 1");
        if (mysqli_num_rows($verificaHits) == 1) {
            list($hits) = mysqli_fetch_array($verificaHits, MYSQLI_NUM);
            $q_cautare = "UPDATE cautari SET hits=$hits+1, ip='$ip', uid='$uid'  WHERE cautare='$cautare'";
            $update_cautare = mysqli_query($dbc, $q_cautare);
        } else {
            $q_cautare = sprintf("INSERT INTO cautari (cautare, hits, ip, uid, dateAdded) VALUES('%s', %d, '%s', '%s', NULL)", $cautare, 1, $ip, $uid);
            $inserare_cautare = mysqli_query($dbc, $q_cautare);
        }
                
        if ( isset($_GET['lungime_interioara']) && filter_var($_GET['lungime_interioara'], FILTER_VALIDATE_FLOAT, array('min_range' => 1))) {
            $sec_lungimeInt = filter_var($_GET['lungime_interioara'], FILTER_SANITIZE_STRING);
            $lungimeInt = mysqli_real_escape_string($dbc, $sec_lungimeInt);
            $query .= " AND produs.interior like \"%$lungimeInt%\"";
            $mesaj_rezultate .= " " . $sec_lungimeInt . "x";
        } else  {
            $mesaj_rezultate .= "**x";
        }

        if ( isset($_GET['lungime_exterioara']) && filter_var($_GET['lungime_exterioara'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
            $sec_lungimeExt = filter_var($_GET['lungime_exterioara'], FILTER_SANITIZE_STRING);
            $lungimeExt = mysqli_real_escape_string($dbc, $sec_lungimeExt);
            $query .= " AND produs.exterior like \"%$lungimeExt%\"";
            $mesaj_rezultate .= $sec_lungimeExt . "x";
        } else  {
            $mesaj_rezultate .= "**x";
        }
        
        if ( isset($_GET['latime']) && filter_var($_GET['latime'], FILTER_VALIDATE_FLOAT, array('min_range' => 1))) {
            $sec_latime = filter_var($_GET['latime'], FILTER_SANITIZE_STRING);
            $latime = mysqli_real_escape_string($dbc, $sec_latime);
            $query .= " AND produs.latime like \"%$latime%\"";
            $mesaj_rezultate .= $sec_latime;
        } else  {
            $mesaj_rezultate .= "**";
        }
        $mesaj_rezultate .= "mm: ";
    
    } else {
        $cautare = null;
        $query .= " WHERE produs.nume like \"%%\"";
        $mesaj_rezultate = "Cautarea ";
        if ( isset($_GET['lungime_interioara']) && filter_var($_GET['lungime_interioara'], FILTER_VALIDATE_FLOAT, array('min_range' => 1))) {
            $sec_lungimeInt = filter_var($_GET['lungime_interioara'], FILTER_SANITIZE_STRING);
            $lungimeInt = mysqli_real_escape_string($dbc, $sec_lungimeInt);
            $query .= " AND produs.interior like \"%$lungimeInt%\"";
            $mesaj_rezultate .= " " . $sec_lungimeInt . "x";           
        } else  {
            $mesaj_rezultate .= "**x";
        }
        
        if ( isset($_GET['lungime_exterioara']) && filter_var($_GET['lungime_exterioara'], FILTER_VALIDATE_FLOAT, array('min_range' => 1))) {
            $sec_lungimeExt = filter_var($_GET['lungime_exterioara'], FILTER_SANITIZE_STRING);
            $lungimeExt = mysqli_real_escape_string($dbc, $sec_lungimeExt);
            $query .= " AND produs.exterior like \"%$lungimeExt%\"";
            $mesaj_rezultate .= $sec_lungimeExt . "x";
        } else  {
            $mesaj_rezultate .= "**x";
        }
        if ( isset($_GET['latime']) && filter_var($_GET['latime'], FILTER_VALIDATE_FLOAT, array('min_range' => 1))) {
            $sec_latime = filter_var($_GET['latime'], FILTER_SANITIZE_STRING);
            $latime = mysqli_real_escape_string($dbc, $sec_latime);
            $query .= " AND produs.latime like \"%$latime%\"";
            $mesaj_rezultate .= $sec_latime . "mm";
        }else  {
            $mesaj_rezultate .= "**";
        }
    }
    $query .= " ORDER BY rulment ASC";
    
    
}
$rezultate = mysqli_query($dbc, $query);
$num_rezultate = mysqli_num_rows($rezultate);
if ($num_rezultate > 0) {
    $pages = new Paginator;
    $pages->mid_range = 10;
    $pages->items_total = $num_rezultate;
    $pages->paginate();
    $query .= " LIMIT $pages->limit,$pages->items_per_page";
    $meta_keys = str_replace(" ", ",", $mesaj_rezultate); 
    $meta_keys .= ',cauta,cautare,rezultat,rulment cu ace,rulment cu bile,rulment cu role,ace,bile,role,rulment,toate,dimensiunile,dimensiuni';
    $mesaj_rezultate .= " " .  number_format($pages->items_total, 0, ",", ".")  . " (de) rezultate (" . ceil($pages->items_total/$pages->default_ipp) . " de pagini)";
    $meta_desc = $mesaj_rezultate . ' | Rulmenti Romania | Catalog Comenzi Online si Distributie';; 
} else {
    $sec_cautare = (!empty($sec))?"'".$sec."'":'';
    $lungimeInt_cautare = (!empty($lungimeInt))?$lungimeInt.'x':'**x';
    $lungimeExt_cautare = (!empty($lungimeExt))?$lungimeExt.'x':'**x';
    $latime_cautare = (!empty($latime))?$latime:'**';
    $dimensiuni = $lungimeInt_cautare . $lungimeExt_cautare . $latime_cautare;
    $ip = $_SERVER['REMOTE_ADDR'];
    $q_faraStoc = sprintf("INSERT INTO farastoc (cautare, dimensiuni, ip, uid, dateAdded) VALUES('%s', '%s', '%s', '%s', NULL)", $cautare, $dimensiuni, $ip, $uid);
    $inserare_faraStoc = mysqli_query($dbc, $q_faraStoc);   
    $mesaj_rezultate = "Cautarea: $sec_cautare $lungimeInt_cautare$lungimeExt_cautare$latime_cautare" . "mm";
    $meta_keys = str_replace(" ", ",", $mesaj_rezultate); 
    $meta_keys .= ',cauta,cautare,rezultat,rulment cu ace,rulment cu bile,rulment cu role,ace,bile,role,rulment,toate,dimensiunile,dimensiuni';
    $meta_desc = $mesaj_rezultate . ' | Rulmenti Romania | Catalog Comenzi Online si Distributie';
}
// OMIT THE CLOSING TAG TO AVOID HEADERS ALREADY SENT ERROR