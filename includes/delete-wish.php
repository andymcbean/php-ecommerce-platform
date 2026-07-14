<?php
include '../includes/connect.php';
include '../includes/functions.php';

$email = test_input_pw($_POST['email']);
$description = test_input_pw($_POST['description']);
$sku = $_POST['id_sku'];

$sql = "DELETE         
        FROM
            wishlist
        WHERE
            sku = '$sku'
        AND
            email = '$email'
        AND
            description = '$description'
        ";
$statement = $db->prepare($sql);
$statement->bindParam(':sku'            , $sku           , PDO::PARAM_INT);
$statement->bindParam(':email'          , $email         , PDO::PARAM_INT);
$statement->bindParam(':description'    , $description   , PDO::PARAM_INT);
    try 
        {
            $statement->execute();
            echo json_encode(array("statusCode"=>200));
        }
    catch(PDOException $e)
        {
            echo json_encode(array("statusCode"=>201));
        }
$db = null;
?>