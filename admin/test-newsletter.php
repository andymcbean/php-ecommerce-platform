<?php
$sm_share = "";
include '../includes/header.php';
include '../includes/email-functions.php';

$id = $_POST['id'];

$email = $_POST['email'];

test_newsletter($id, $email);

?>