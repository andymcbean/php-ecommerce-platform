<?php
$host = "YOUR_HOST";
$database = "DATABASE_NAME"; 
$username = "USERNAME"; 
$password = "PASSWORD";

try 
    {
        $db = new PDO('mysql:host='.$host.';dbname='.$database, $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "DB connected";
    } 

catch (PDOException $e) 
    {
        echo  "Could not connect to database";
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
?>