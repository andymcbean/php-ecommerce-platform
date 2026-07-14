<?php

include '../includes/connect.php';
include '../includes/constants.php';
include '../includes/functions.php';

if(isset($_POST['name']))
    {
        $id = $_POST['id'];
        $name = test_input_pw($_POST['name']);
        $country = test_input_pw($_POST['country']);
        $state = test_input_pw($_POST['state']);
        $url1 = test_input_pw($_POST['url1']);
        $country = test_input_pw($_POST['country']);
        $state = test_input_pw($_POST['state']);
        $url1 = test_input_pw($_POST['url1']);
        $fb = test_input_pw($_POST['fb']);
        $address = test_input_pw($_POST['address']);

        $sql = "UPDATE 
                    retailers
                SET
                    name=:name, fb=:fb, country=:country, state=:state, url1=:url1, address=:address, country=:country, state=:state, url1=:url1
                WHERE
                    id = '$id'
                ";

        $statement = $db->prepare($sql);
        
        $statement->bindParam(':name'	    ,   $name,      PDO::PARAM_STR);
        $statement->bindParam(':country'    ,	$country,   PDO::PARAM_STR);
        $statement->bindParam(':state'	    ,   $state,     PDO::PARAM_STR);
        $statement->bindParam(':url1'	    ,   $url1,      PDO::PARAM_STR);
        $statement->bindParam(':country'    ,	$country,   PDO::PARAM_STR);
        $statement->bindParam(':state'	    ,   $state,     PDO::PARAM_STR);
        $statement->bindParam(':url1'	    ,   $url1,      PDO::PARAM_STR);
        $statement->bindParam(':fb'	        ,   $fb,        PDO::PARAM_STR);
        $statement->bindParam(':address'	,   $address,   PDO::PARAM_STR);

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