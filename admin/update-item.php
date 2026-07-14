<?php

include '../includes/connect.php';
include '../includes/constants.php';
include '../includes/functions.php';

if(isset($_POST['description']))
    {
        $id = $_POST['id'];
        $description = test_input_pw($_POST['description']);
        $a4 = test_input_pw($_POST['a4']);
        $a3 = test_input_pw($_POST['a3']);
        $xl = test_input_pw($_POST['xl']);
        $stock_a4 = $_POST['stock_a4'];
        $stock_a3 = $_POST['stock_a3'];
        $stock_xl = $_POST['stock_xl'];
        $sku = test_input_pw($_POST['sku']);
        $discount = test_input_pw($_POST['discount']);
        $active = test_input_pw($_POST['active']);
        $retail_only = test_input_pw($_POST['retail_only']);
        $us_retail_only = test_input_pw($_POST['us_retail_only']);
        $stock_scrapbook = $_POST['stock_scrapbook'];
        $stock_chipboard = $_POST['stock_chipboard'];
        
        $sql = "UPDATE 
                    items
                SET
                    description=:description, sku=:sku, a4=:a4, a3=:a3, xl=:xl, discount=:discount, stock_a4=:stock_a4, stock_a3=:stock_a3, stock_xl=:stock_xl, active=:active, retail_only=:retail_only, us_only=:us_retail_only, stock_scrapbook=:stock_scrapbook, stock_chipboard=:stock_chipboard
                WHERE
                    id = '$id'
                ";

        $statement = $db->prepare($sql);
        
        $statement->bindParam(':description'	    ,   $description,       PDO::PARAM_STR);
        $statement->bindParam(':a4'      	        ,	$a4,                PDO::PARAM_STR);
        $statement->bindParam(':a3'	                ,   $a3,                PDO::PARAM_STR);
        $statement->bindParam(':xl'	                ,   $xl,                PDO::PARAM_STR);
        $statement->bindParam(':stock_a4'      	    ,	$stock_a4,          PDO::PARAM_STR);
        $statement->bindParam(':stock_a3'	        ,   $stock_a3,          PDO::PARAM_STR);
        $statement->bindParam(':stock_xl'	        ,   $stock_xl,          PDO::PARAM_STR);
        $statement->bindParam(':sku'	            ,   $sku,               PDO::PARAM_STR);
        $statement->bindParam(':discount'	        ,   $discount,          PDO::PARAM_STR);
        $statement->bindParam(':stock_scrapbook'    ,   $stock_scrapbook,   PDO::PARAM_STR);
        $statement->bindParam(':stock_chipboard'    ,   $stock_chipboard,   PDO::PARAM_STR);
        $statement->bindParam(':retail_only'	    ,   $retail_only,       PDO::PARAM_STR);
        $statement->bindParam(':us_retail_only'	    ,   $us_retail_only,    PDO::PARAM_STR);
        $statement->bindParam(':active'	            ,   $active,            PDO::PARAM_STR);

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