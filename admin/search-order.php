<?php
include '../includes/connect.php';

if(isset($_POST['submit']))
    {
        $sql = 'SELECT 
                    * 
                FROM 
                    orders
                ';
        $statement = $connect->prepare($sql);
        $statement->execute();
        $data = $statement->fetchAll();
        foreach($data as $row)
            {
                
            }   
    }