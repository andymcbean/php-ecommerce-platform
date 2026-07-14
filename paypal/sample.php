<?php
include '../includes/connect.php';
include '../includes/constants.php';
require_once '../../../vendor/autoload.php';
 
use Omnipay\Omnipay;
 
$gateway = Omnipay::create('PayPal_Rest');
$gateway->setClientId(PP_CLIENT_DEV_ID);
$gateway->setSecret(PP_CLIENT_DEV_SECRET);
$gateway->setTestMode(true); //set it to 'TRUE' when in development mode

?>