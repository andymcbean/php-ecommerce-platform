<?php
session_start();
unset($_SESSION['shopping_cart']);
unset($_SESSION['order_no']);
echo "session unset";
?>