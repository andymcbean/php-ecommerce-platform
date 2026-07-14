<?php
include '../includes/connect.php';
include '../includes/functions.php';

$email = test_input_pw($_POST['email']);
$sub = $_POST['subscribe'];
if($sub == 0)
    {
        $subscriber = 1;
    }
elseif($sub == 1)
    {
        $subscriber = 0;
    }
$sql = "UPDATE
            users
        SET
            subscriber=:subscriber
        WHERE
            email = '$email'
                ";
        $statement = $db->prepare($sql);
        $statement->bindParam(':subscriber'       ,	$subscriber   , PDO::PARAM_STR);
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