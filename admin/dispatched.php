<?php

include '../includes/connect.php';
include '../includes/constants.php';
include '../includes/functions.php';
include '../includes/dispatched.php';

if(isset($_POST['order_no']))
    {
        order_dispatched($order_no);
        $order_no = $_POST['order_no'];
        $dispatched = 1;
        $sql = "UPDATE 
                    delivery
                SET
                    dispatched=:dispatched
                WHERE
                    order_no = '$order_no'
                ";
        $statement = $db->prepare($sql);
        $statement->bindParam(':dispatched'	,   $dispatched,   PDO::PARAM_INT);
        try
            {
                $statement->execute();
            }
        catch(PDOException $e)
            {   
                echo $e;
            }    
      $db = null;
    }

?>