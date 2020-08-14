<?php

/**
 * @author cristian.devmm.net
 * @copyright 2016
 */

/* The first is the name that will be given
to the element. The second is the element type, which will be either text or
password in this chapter, and textarea in the next. The third argument will
be an array of errors. */
function create_form_input($name, $type, $errors, $extra = '') {
    /* First, the function assumes that no value exists. Then, if a value does
    exist for this input in $_POST, that value is assigned to $value. The third
    step strips extraneous slashes from the value, but only if Magic Quotes
    is enabled. */
    $value = false;
    if (isset($_POST[$name])) {
        $value = $_POST[$name];
    }
    if ($value && get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }
    /* This function will create text inputs, password inputs, and textareas. The
    first two are virtually the same in syntax, except for the type value used in
    the HTML. The function starts by handling those two types. */
    if ( ($type == 'text') || ($type == 'password')) {
        
        echo '<input type="' . $type . '" name="' . $name . '" id="' . $name . '"';
        //If the $value variable has a value, then it should be added to the input, after running it through htmlspecialchars( ).
        if (!empty($extra)) {
            echo " $extra";
        }
        if ($value) {
            echo ' value="' . htmlspecialchars($value) . '"';
        }
        if (array_key_exists($name, $errors)) {
            echo ' class="error" />';
        } else {
            echo ' />';
        }// END OF IF/ELSE ERROR STATEMENT
    } else if ($type == 'textarea') {
        /* Unlike with the text and password inputs, where the error message will be
        displayed to the right of the input itself, for textareas, I want to display the
        error message above the textarea, so that it’s most obvious */
            
        /* Here, the textarea’s opening tag is created, providing dynamic name and
        id values. I’ve chosen to hardcode the textarea’s size into this function to
        make the default scale a bit bigger than what the browser would otherwise
        create. */
        echo '<textarea name="' . $name . '" id="' . $name . '" rows="5" cols="75"';
        
        if (!empty($extra)) {
            echo " $extra";
        }
        
        //The error class must be added to the opening textarea tag, if an error exists with this element.
        if (array_key_exists($name, $errors)) {
            echo ' class="error">';
        } else {
            echo '>';
        }// END OF ADDING ERROR CLASS
        
        // The value for textareas is written between opening and closing textarea tags.
        if ($value) {
            echo $value;
        }
        echo '</textarea>';
    }// END OF PRIMARY IF-ELSE.
}// END OF THE create_form_input( ) FUNCTION.

// included PHP files will omit the closing PHP tag to prevent “headers already sent” errors.