<?php

include '../includes/connect.php';
include '../includes/constants.php';
include '../includes/functions.php';

if(isset($_POST['name']))
    {
        $id = $_POST['id'];
        $name = test_input_pw($_POST['name']);
        $email = test_input_pw($_POST['email']);

        $sql = "UPDATE 
                    subscribers
                SET
                    name=:name, email=:email
                WHERE
                    id = '$id'
                ";

        $statement = $db->prepare($sql);
        
        $statement->bindParam(':name'	,   $name,   PDO::PARAM_STR);
        $statement->bindParam(':email'   ,	$email,   PDO::PARAM_STR);

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