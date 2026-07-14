<?php
include 'connect.php';
include 'functions.php';

$store_name = $_POST['store_name'];
$name = test_input_pw($_POST['name']);
$email = test_input_pw($_POST['email']);
$address = test_input_pw($_POST['address']);
$website = test_input_pw($_POST['website']);
$fb = test_input_pw($_POST['fb']);
$insta = test_input_pw($_POST['insta']);
$phone = test_input_pw($_POST['phone']);
$years = test_input_pw($_POST['years']);
$store_type = test_input_pw($_POST['store_type']);
$other_products = test_input_pw($_POST['other_products']);

$sql = "INSERT INTO 
                retail_request (store_name, name, email, address, website, fb, insta, phone, other_products, years, store_type)
        VALUES
            (:store_name, :name, :email, :address, :website, :fb, :insta, :phone,  :other_products, :years, :store_type)
        ";

        $statement = $db->prepare($sql);

        $statement->bindParam(':store_name'	            ,	$store_name     , PDO::PARAM_STR);
        $statement->bindParam(':name'	                ,	$name           , PDO::PARAM_STR);
        $statement->bindParam(':email'	                ,	$email          , PDO::PARAM_STR);
        $statement->bindParam(':address'	            ,	$address        , PDO::PARAM_STR);
        $statement->bindParam(':website'      	        ,	$website        , PDO::PARAM_STR);
        $statement->bindParam(':fb'      	            ,	$fb             , PDO::PARAM_STR);
        $statement->bindParam(':insta'      	        ,	$insta          , PDO::PARAM_STR);
        $statement->bindParam(':phone'      	        ,	$phone          , PDO::PARAM_STR);
        $statement->bindParam(':other_products'         ,	$other_products , PDO::PARAM_STR);
        $statement->bindParam(':years'                  ,	$years          , PDO::PARAM_STR);
        $statement->bindParam(':store_type'             ,	$store_type     , PDO::PARAM_STR);
        $statement->bindParam(':other_products'         ,	$other_products , PDO::PARAM_STR);
        try 
            {
                $statement->execute();
                echo json_encode(array("statusCode"=>200));
            }
        catch(PDOException $e)
            {
                echo $e;
                echo json_encode(array("statusCode"=>201));
            }
$db = null;
?>