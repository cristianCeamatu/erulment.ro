<?php
/* 	*
	* Title: config.inc.php
	* Created by: Cristian Ceamatu
	* Contact: cristian@devmm.net,
	* Last modified: 29.02.2016
    * This file contains the database access information. 
    * This file establishes a connection to MySQL and selects the database.
*/

// Set the database access information as constants:
DEFINE ('DB_USER', 'example');
DEFINE ('DB_PASSWORD', 'example');
DEFINE ('DB_HOST', 'example');
DEFINE ('DB_NAME', 'example');

// Make the connection:
$dbc = mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Set the character set:
mysqli_set_charset($dbc, 'utf8');

// Omit the closing PHP tag to avoid 'headers already sent' errors!
