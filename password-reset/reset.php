<?php
use PHPMailer\PHPMailer\PHPMailer;

include '../includes/connect.php';
include '../includes/constants.php';
require '../../../vendor/autoload.php';

if (isset($_POST['submit']))
    {
        $selector = bin2hex(random_bytes(8));
        $token = random_bytes(32);

        $url = (SITE_URL) . "password-reset/new-password?selector=" . $selector . "&validator=" . bin2hex($token);

        $expires = date("U") + 1800;
        
        $email = $_POST['email'];

        $sql = "SELECT
                    email
                FROM
                    users
                WHERE
                    email = '$email'
                ";
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $total_row = $statement->rowCount();
        if($total_row > 0)
          {
            foreach($result as $row)
                {
                            $sql = "DELETE FROM
                                        password_reset
                                    WHERE
                                        email = '$email'
                                    ";

                            $statement = $db->prepare($sql);
                            $statement->bindParam(':email', $email, PDO::PARAM_STR); 
                            $statement->execute();
                                
                            $sql = "INSERT INTO
                                            password_reset (email, selector, token, expires)
                                    VALUES
                                            (:email, :selector, :token, :expires)
                                    ";

                            $statement = $db->prepare($sql);

                            $hash_token = password_hash($token, PASSWORD_DEFAULT);

                            $statement->bindParam(':email'       ,	$email,         PDO::PARAM_STR);
                            $statement->bindParam(':selector'	 ,	$selector,      PDO::PARAM_STR);
                            $statement->bindParam(':token'	     ,  $hash_token,    PDO::PARAM_STR);
                            $statement->bindParam(':expires'     ,	$expires,       PDO::PARAM_STR);
                            $statement->execute();
                }

                

                $sql = "SELECT
                    template
                FROM
                    email_templates
                WHERE
                    template_name = 'password_reset'
                ";
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();

        foreach ($result as $row)
            {
                $recipient = $email;
                $template = $row['template'];
                $template = str_replace("%email%", $email, $template);
                $template = str_replace("%link%", $url, $template);
                //echo $template;
                $sender = 'andy@mcbean.me';
                $senderName = 'Decoupage Queen - password reset request';

                $mail = new PHPMailer(true);

                try
                    {
                        $mail->IsSMTP();
                        $mail->CharSet = 'UTF-8';

                        $mail->Host       = "email-smtp.eu-west-1.amazonaws.com"; // SMTP server example

                        $mail->SMTPAuth   = true;                   // enable SMTP authentication
                        $mail->Port       = 587;                    // set the SMTP port for the GMAIL server
                        $mail->Username   = AWS_U_NAME;             // SMTP account username example
                        $mail->Password   = AWS_SES_PASSWORD;       // SMTP account password example
                        $mail->SMTPSecure = 'tls';
                        $mail->AddEmbeddedImage('../images/nav-logo.png', 'logo', '../images/nav-logo.png');
                        $mail->AddEmbeddedImage('../images/facebook.png', 'facebook', '../images/facebook.png');
                        $mail->AddEmbeddedImage('../images/insta.png', 'insta', '../images/insta.png');
                        $mail->AddEmbeddedImage('../images/youtube.png', 'youtube', '../images/youtube.png'); 
                        $mail->setFrom($sender, $senderName);

                        $mail->addAddress($recipient);
                        
                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = 'Password reset';
                        $mail->Body    = $template;
                        $mail->AltBody = $template;

                        $mail->send();
                        header ("Location: /password-reset?reset=success");
                        
                    }
                /*catch (phpmailerException $e) 
                    {
                        echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
                    } */
                catch (Exception $e) 
                    {
                        echo $e;
                        echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
                    }
                
                
            }
            
          }
        else
          {
            header ("Location: /password-reset?reset=error");
          }

        
    }  
else
    {
        header('Location: ../login/');
    }               
          

        

        
    $db = null; 

?>
