<?php
include '../includes/connect.php';
include '../includes/constants.php';
include '../includes/functions.php';

if(isset($_POST['type']))
    {
        $id = $_POST['id'];
        $type = test_input_pw($_POST['type']);
        $code = test_input_pw($_POST['code']);
        $off = test_input_pw($_POST['off']);
        $value = test_input_pw($_POST['value']);

        $sql = "UPDATE 
                    promo_codes
                SET
                    type=:type, code=:code, off=:off, value=:value
                WHERE
                    id = '$id'
                ";

        $statement = $db->prepare($sql);
        
        $statement->bindParam(':type'	,   $type,   PDO::PARAM_STR);
        $statement->bindParam(':code'   ,	$code,   PDO::PARAM_STR);
        $statement->bindParam(':off'	,   $off,    PDO::PARAM_STR);
        $statement->bindParam(':value'	,   $value,  PDO::PARAM_STR);

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