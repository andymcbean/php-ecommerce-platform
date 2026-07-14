<?php
include '../includes/connect.php';

if (isset($_POST['submit']))
    {
        $selector        = $_POST['selector'];
        $validator       = $_POST['validator'];
        $password        = $_POST['password'];

        if (empty($password))
            {
                echo "Password required";
            }
  
        $date = date("U");
        
        $sql = "SELECT
                    *
                FROM
                    password_reset
                WHERE
                    selector = '$selector'
                AND 
                    expires >= '$date'
                ";
        
        try
            {
                $statement = $db->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll();
            }
        catch(PDOException $e)
            {
                echo $e;
            }
        

        foreach($result as $row)
            {
                $token_bin = hex2bin($validator);
                $token_check = password_verify($token_bin, $row['token']);

                if ($token_check === false)
                    {
                        echo "There was an error. Please re-submit your reset request";
                    }
                else
                    {
                        if ($token_check === true)
                            {
                                $token_email = $row['email'];

                                $sql = "SELECT
                                            users.email
                                        FROM
                                            users
                                        WHERE
                                            email = '$token_email'
                                        ";
                                try
                                    {
                                        $statement = $db->prepare($sql);
                                        $statement->execute();
                                        $result = $statement->fetchAll();
                                    }
                                catch(PDOException $e)
                                    {
                                        echo $e;
                                    }

                                foreach ($result as $row)
                                    {
                                        $sql = "UPDATE
                                                    users
                                                SET
                                                    password=:password
                                                WHERE
                                                    email = '$token_email'
                                                ";
                                        $hash_password = password_hash($password, PASSWORD_DEFAULT);

                                        try
                                            {
                                                $statement = $db->prepare($sql);
                                                $statement->bindParam(':password'   ,	$hash_password, PDO::PARAM_STR);
                                                $statement->execute();
                                            }
                                        catch(PDOException $e)
                                            {
                                                echo $e;
                                            }  
                                        
                                        if ($statement->execute())
                                            {
                                                $sql = "DELETE 
                            
                                                        FROM
                                                            password_reset
                                                        WHERE
                                                            email = :token_email
                                                        ";
                                                try
                                                    {
                                                        $statement = $db->prepare($sql);
                                                        $statement->bindParam(':token_email', $token_email, PDO::PARAM_INT); 
                                                        $statement->execute();
                                                    }
                                                catch(PDOException $e)
                                                    {
                                                        echo $e;
                                                    }
                                                
                                                    
                                                header ("Location: ../login?reset=success");
                                            }
                                        
                                    }
                            }
                    }
            }

    }
else
    {
        header("Location: ../login/");
    }
?>