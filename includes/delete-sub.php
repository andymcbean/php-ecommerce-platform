<?php
include '../includes/connect.php';
include '../includes/functions.php';

$name = test_input_pw($_POST['name']);
$email = test_input_pw($_POST['email']);

$sql = "DELETE 
            
        FROM
            subscribers
        WHERE
            email = '$email'
        ";
$statement = $db->prepare($sql);
$statement->bindParam(':email', $email, PDO::PARAM_INT); 

try 
    {
        $statement->execute();
        echo json_encode(array("statusCode"=>200));
    }
catch(PDOException $e)
    {
        echo json_encode(array("statusCode"=>201));
    }
    
?>