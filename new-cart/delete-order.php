<?php
include '../includes/connect.php';

$order_no = $_POST['order_no'];

$sql = "DELETE 
            
        FROM
            orders
        WHERE
            order_no = '$order_no'
        ";
$statement = $db->prepare($sql);
$statement->bindParam(':order_no', $order_no, PDO::PARAM_INT); 

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