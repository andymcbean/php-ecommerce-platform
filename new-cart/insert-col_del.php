<?php
$sql = "UPDATE
            country_list
        SET
            col_del=:col_del
        ";
$statement = $db->prepare($sql);

$statement->bindParam(':col_del'    ,	$col_del, PDO::PARAM_STR);
try 
    {
        $statement->execute();
    }
catch(PDOException $e)
    {
        echo $e;
    }