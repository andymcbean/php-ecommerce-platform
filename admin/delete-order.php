<?php
include '../includes/connect.php';

if (isset($_POST['id']))
    {
        $id = $_POST['id'];
        
        $sql = "DELETE 
                    
                FROM
                    delivery
                WHERE
                    id = '$id'
                ";
        $statement = $db->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT); 

        try
            {
                $statement->execute();
            }
        catch(PDOException $e)
            {
                echo $e;
            }
    }
?>