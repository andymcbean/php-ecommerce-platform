<?php
session_start();
include '../includes/functions.php';
include '../includes/connect.php';

if(isset($_POST['submit']))
    {
        if(!empty($email = $_POST['email']) AND !empty($password = $_POST['password']))  
            {
                $sql = "SELECT
                            email,
                            password,
                            user_level 
                        FROM 
                            users 
                        WHERE 
                            email = '$email'
                        ";
                $statement = $db->prepare($sql); 
                $statement->execute(array($email, $password));
                $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $email = $statement->fetch();
                if ($email && password_verify($_POST['password'], $email['password'])) 
                    {
                        $_SESSION['email'] = $_POST['email'];
                        header("Location: ../users/");
                    } 
                else 
                    {
                        echo "<div class='container' style='max-height: 50px; max-width: 250px;'><p class='alert-danger' style='align: center'> Password incorrect.</p></div>"; 
                    }
            }  
        else  
            { 
               echo "<div class='container' style='max-height: 50px; max-width: 250px;'><p class='alert-danger' style='align: center'> All fields required.</p></div>"; 
            }   
    }
    $db = null;
?>