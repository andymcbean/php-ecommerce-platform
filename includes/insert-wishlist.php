<?php
include '../includes/connect.php';
include '../includes/functions.php';

$email = test_input_pw($_POST['email']);
$description = test_input_pw($_POST['description']);
$sku = test_input_pw($_POST['id_sku']);
//echo $sku; die();
$sql = "INSERT INTO 
                wishlist (description, email, sku)
        VALUES
            (:description, :email, :sku)
        ";

        $statement = $db->prepare($sql);

        $statement->bindParam(':description'    ,	$description , PDO::PARAM_STR);
        $statement->bindParam(':email'	        ,	$email       , PDO::PARAM_STR);
        $statement->bindParam(':sku'	        ,	$sku         , PDO::PARAM_STR);

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