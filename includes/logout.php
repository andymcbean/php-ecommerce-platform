<?php
include '../includes/connect.php';
include '../includes/functions.php';


// Initialize the session.
// If you are using session_name("something"), don't forget it now!
session_start();
user_logged_out();


// Finally, destroy the session.
unset($_SESSION['email']);
header('location: ../');
?>