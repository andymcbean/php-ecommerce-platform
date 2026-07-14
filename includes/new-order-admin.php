<?php
//use PHPMailer\PHPMailer\PHPMailer;

include 'connect.php';

//require '../../../vendor/autoload.php';

function order_received_admin($order_no)
    {
        global $db;
        $order_no = $_SESSION['order_no'];
        $sql = "SELECT 
                    order_no,
                    email_sent
                FROM
                    delivery
                WHERE
                    order_no = '$order_no'
                ";
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        foreach($result as $row)
            {
                if ($row['email_sent'] == 0)
                    {
                        //remove AND in query once live
                        $order_no = $_SESSION['order_no'];
                        $sql = "SELECT
                                    id,
                                    email,
                                    user_level
                                FROM
                                    users
                                WHERE
                                    user_level = 'admin'
                                ";
                        $statement = $db->prepare($sql);
                        $statement->execute();
                        $result = $statement->fetchAll();
                
                        foreach ($result as $row)
                            {
                                $recipient = $row['email'];
                                $order_no = $_SESSION['order_no'];
                                
                                $sql = "SELECT 
                                            template
                                        FROM
                                            email_templates
                                        WHERE
                                            template_name = 'order_received_admin'
                                        ";
                                $statement = $db->prepare($sql);
                                $statement->execute();
                                $result = $statement->fetchAll();
                
                                foreach ($result as $row)
                                    {
                                        
                                        $template = $row['template'];
                                        $template = str_replace("%email%", $recipient, $template);
                                        $template = str_replace("%order_no%", $order_no, $template);
                                        //echo $template;
                                        $sender = 'andy@mcbean.me';
                                        $senderName = 'Decoupage Queen - New order received';
                
                                        $mail = new PHPMailer(true);
                
                                        try
                                            {
                                                $mail->IsSMTP();
                                                $mail->CharSet = 'UTF-8';
                
                                                $mail->Host       = "email-smtp.eu-west-1.amazonaws.com"; // SMTP server example
                
                                                $mail->SMTPAuth   = true;                  // enable SMTP authentication
                                                $mail->Port       = 587;                    // set the SMTP port for the GMAIL server
                                                $mail->Username   = "AWS_SECRET_KEY"; // SMTP account username example
                                                $mail->Password   = "BBTt/REDVt+Vg2RZ5goldyEEPSz8lBkcUQDkfWT5fxwd";        // SMTP account password example
                                                $mail->SMTPSecure = 'tls';
                                                $mail->AddEmbeddedImage('../images/nav-logo.png', 'logo', '../images/nav-logo.png');
                                                $mail->AddEmbeddedImage('../images/facebook.png', 'facebook', '../images/facebook.png');
                                                $mail->AddEmbeddedImage('../images/insta.png', 'insta', '../images/insta.png');
                                                $mail->AddEmbeddedImage('../images/youtube.png', 'youtube', '../images/youtube.png'); 
                                                $mail->setFrom($sender, $senderName);
                
                                                $mail->addAddress($recipient);
                                                
                                                $mail->isHTML(true);                                  // Set email format to HTML
                                                $mail->Subject = 'New order received';
                                                $mail->Body    = $template;
                                                $mail->AltBody = $template;
                
                                                $mail->send();
                                            }
                
                                        catch (Exception $e) 
                                            {
                                                echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
                                            }
                                    }
                            }
                    }
                
            }
    }
?>