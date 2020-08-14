<?php

/* 	*
	* Title: config.inc.php
	* Created by: Cristian Ceamatu
	* Contact: cristian.ceamatu@gmail.com,
	* Last modified: 29.02.2016
	*
	* Configuration file does the following things:
	* - Has site settings in one location.
	* - Stores URLs and URIs as constants.
	* - Starts the session.
	* - Sets how errors will be handled.
	* - Defines a redirection function.
*/

// ********************************** //
// ************ SETTINGS ************ //

// Are we live?
define('LIVE', true);
if (!defined('LIVE')) DEFINE('LIVE', false);

// Errors are emailed here:
define('CONTACT_EMAIL', 'info@bearingsmarket.ro');
define('ERROR_EMAIL', 'script_error@erulment.ro');

// ************ SETTINGS ************ //
// ********************************** //

// ********************************** //
// ************ CONSTANTS *********** //

// Determine location of files and the URL of the site:
define ('BASE_URI', '/home/xeayddtt/public_html/');
define ('BASE_URL', 'erulment.ro/');
define ('MYSQL', './incl/mysql.inc.php');

// ************ CONSTANTS *********** //
// ********************************** //

// ********************************* //
// ************ SESSIONS *********** //

// Start the session:
session_name('erulment');
session_start();

// ************ SESSIONS *********** //
// ********************************* //

// ****************************************** //
// ************ ERROR MANAGEMENT ************ //

// Function for handling errors.
// Takes five arguments: error number, error message (string), name of the file where the error occurred (string) 
// line number where the error occurred, and the variables that existed at the time (array).
// Returns true.
function my_error_handler($e_number, $e_message, $e_file, $e_line, $e_vars) {

	// Build the error message:
	$message = "An error occurred in script '$e_file' on line $e_line:\n$e_message\n";
	
	// Add the backtrace:
	$message .= "<pre>" . print_r(debug_backtrace(), 1) . "</pre>\n";
	
	// Or just append $e_vars to the message:
	//	$message .= "<pre>" . print_r ($e_vars, 1) . "</pre>\n";

	if (!LIVE) { // Show the error in the browser.
	
		echo '<div class="alert alert-danger">' . nl2br($message) . '</div>';

	} else { // Development (print the error).

		// Send the error in an email:
		error_log ($message, 1, ERROR_EMAIL, 'From:error@erulment.ro');
		
		// Only print an error message in the browser, if the error isn't a notice:
		if ($e_number != E_NOTICE) {
            include('./theme/head.html');
			echo '<div class="alert alert-danger">Upsss! Eroare de sistem. Ne pare rau, deja am primit semnalarea si vom rezolva foarte repede.<br />Va rugam incercati mai tarziu.</div>';
            include('./theme/foot.html');
            exit();
		}

	} // End of $live IF-ELSE.
	
	return true; // So that PHP doesn't try to handle the error, too.

} // End of my_error_handler() definition.

// Use my error handler:
set_error_handler('my_error_handler');

// ************ ERROR MANAGEMENT ************ //
// ****************************************** //

// ******************************************* //
// ************ REDIRECT FUNCTION ************ //

// This function redirects invalid users.
// It takes two arguments: 
// - The session element to check
// - The destination to where the user will be redirected. 
function redirect_invalid_user($check = 'user_id', $destination = 'index.php', $protocol = 'http://') {
	
	// Check for the session item:
	if (!isset($_SESSION[$check])) {
		$url = $protocol . BASE_URL . $destination; // Define the URL.
		header("Location: $url");
		exit(); // Quit the script.
	}
	
} // End of redirect_invalid_user() function.

// ************ REDIRECT FUNCTION ************ //
// ******************************************* //

// Omit the closing PHP tag to avoid 'headers already sent' errors!