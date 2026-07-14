<?php
include '../includes/connect.php';
include '../includes/functions.php';

$name = test_input_pw($_POST['name']);
$email = test_input_pw($_POST['email']);
$subscriber = 1;

$sql = "INSERT INTO
            subscribers (name, email, subscriber)
        VALUES
            (:name, :email, :subscriber) 
        
        ";
        $statement = $db->prepare($sql);
        $statement->bindParam(':subscriber'  ,	$subscriber   , PDO::PARAM_INT);
        $statement->bindParam(':email'       ,	$email        , PDO::PARAM_STR);
        $statement->bindParam(':name'        ,	$name         , PDO::PARAM_STR);
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