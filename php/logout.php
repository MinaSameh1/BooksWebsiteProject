<?php
/** logout file  */
require_once 'page.php';

if (!isset($_SESSION)) {
    session_start();
}
// remove all session variables
session_unset();

// destroy the session
session_destroy();

// Load homepage
header('Location: ' . $HOMEPAGE);

exit();
