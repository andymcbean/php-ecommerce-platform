<?php

include '../includes/connect.php';
include '../includes/constants.php';
include '../includes/functions.php';

if(isset($_POST['name']))
    {
        $id = $_POST['id'];
        $name = test_input_pw($_POST['name']);
        $email = test_input_pw($_POST['email']);
        $retailer = test_input_pw($_POST['retailer']);
        $us_retailer = test_input_pw($_POST['us_retailer']);
        $user_level = test_input_pw($_POST['user_level']);

        $sql = "UPDATE 
                    users
                SET
                    name=:name, email=:email, retailer=:retailer, user_level=:user_level, us_retailer=:us_retailer
                WHERE
                    id = '$id'
                ";

        $statement = $db->prepare($sql);
        
        $statement->bindParam(':name'	,   $name,   PDO::PARAM_STR);
        $statement->bindParam(':email'   ,	$email,   PDO::PARAM_STR);
        $statement->bindParam(':retailer'	,   $retailer,    PDO::PARAM_STR);
        $statement->bindParam(':us_retailer'	,   $us_retailer,    PDO::PARAM_STR);
        $statement->bindParam(':user_level'	,   $user_level,  PDO::PARAM_STR);

        try
            {
                $statement->execute();
                $success = "<div class='container'><div class='alert alert-success'> Unit edited sucessfully. View <a href='../users/my-portfolio' style='color: green'>portofilo</a> or <a href='../users/add-domain' style='color: green'>add another doamin</a></div></div>";
            }

        catch(PDOException $e)
            {   
                echo $e;
                $error = "<div class='container'><div class='alert-danger'> Update failed. Please <a style='color: red;' href='../users/my-portfolio'> try again.</a></div></div>";
            }    
      $db = null;
    }

?>