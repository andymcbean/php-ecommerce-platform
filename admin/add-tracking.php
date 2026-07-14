<?php
include '../includes/connect.php';
include '../includes/constants.php';
include '../includes/functions.php';
include '../includes/dispatched.php';

if(isset($_POST['carrier']))
    {
        $id = $_POST['id'];
        $order_no = $_POST['order_no'];
        $carrier = test_input_pw($_POST['carrier']);
        $code = test_input_pw($_POST['code']);
        $dispatched = 1;

        $sql = "UPDATE 
                    delivery
                SET
                    carrier=:carrier, code=:code, dispatched=:dispatched
                WHERE
                    id = '$id'
                ";

        $statement = $db->prepare($sql);
        
        $statement->bindParam(':carrier'	,   $carrier,   PDO::PARAM_STR);
        $statement->bindParam(':code'   ,	$code,   PDO::PARAM_STR);
        $statement->bindParam(':dispatched'   ,	$dispatched,   PDO::PARAM_INT);

        try
            {
                $statement->execute();
                echo order_dispatched($order_no);
                header("location:../admin/orders");
            }

        catch(PDOException $e)
            {   
                echo $e;
            }    
      $db = null;
    }

?>