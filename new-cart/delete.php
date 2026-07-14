<?php

include '../includes/connect.php';

if (isset($_POST['id']))
    {
        $id = $_POST['id'];
        
        $sql = "DELETE 
                    
                FROM
                    orders
                WHERE
                    id = '$id'
                ";
        $statement = $db->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT); 

        try
            {
                $statement->execute();
                header("location: /new-cart/?delete=success&order=".$order_no."");
            }
        catch(PDOException $e)
            {
                echo $e;
            }
    }

