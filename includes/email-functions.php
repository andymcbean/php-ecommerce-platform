<?php
use PHPMailer\PHPMailer\PHPMailer;
include 'connect.php';
require '../../../vendor/autoload.php';

function test_newsletter($id, $email)
    {
        global $db;
        $sql = "SELECT 
                    *
                FROM
                    newsletter
                WHERE
                    id = '$id'
                ";
                //echo $sql;
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        foreach($result as $row)
            {
                $body = html_entity_decode($row['post']);
                $title = $row['title'];
                $sql = "SELECT
                            users.id,
                            users.name AS name,
                            users.email AS email
                        FROM
                            users
                        WHERE
                            users.email = '$email'
                        ";
                $statement = $db->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll();

                foreach ($result as $row)
                    {
                        $name = $row['name'];
                        $recipient = $row['email'];
                        $sql = "SELECT 
                                    template
                                FROM
                                    email_templates
                                WHERE
                                    template_name = 'news_letter'
                                ";
                        $statement = $db->prepare($sql);
                        $statement->execute();
                        $result = $statement->fetchAll();

                        foreach ($result as $row)
                            {
                                
                                $template = $row['template'];
                                $template = str_replace("%title%", $title, $template);
                                $template = str_replace("%name%", $name, $template);
                                $template = str_replace("%email%", $recipient, $template);
                                $template = str_replace("%body%", $body, $template);
                                //echo $template;
                                $sender = 'decoupagequeenpaper@gmail.com';
                                $senderName = 'Decoupage Queen - Newsletter';

                                $mail = new PHPMailer(true);

                                try
                                    {
                                        $mail->IsSMTP();
                                        $mail->CharSet = 'UTF-8';

                                        $mail->Host       = "email-smtp.eu-west-1.amazonaws.com"; // SMTP server example

                                        $mail->SMTPAuth   = true;                  // enable SMTP authentication
                                        $mail->Port       = 587;                    // set the SMTP port for the GMAIL server
                                        $mail->Username   = "S3_KEY"; // SMTP account username example
                                        $mail->Password   = "S3_SECRET";        // SMTP account password example
                                        $mail->SMTPSecure = 'tls';
                                        $mail->AddEmbeddedImage('../images/nav-logo.png', 'logo', '../images/nav-logo.png');
                                        $mail->AddEmbeddedImage('../images/facebook.png', 'facebook', '../images/facebook.png');
                                        $mail->AddEmbeddedImage('../images/insta.png', 'insta', '../images/insta.png');
                                        $mail->AddEmbeddedImage('../images/youtube.png', 'youtube', '../images/youtube.png'); 
                                        $mail->setFrom($sender, $senderName);

                                        $mail->addAddress($recipient);
                                        
                                        $mail->isHTML(true);                                  // Set email format to HTML
                                        $mail->Subject = 'Decoupage Queen Newsletter';
                                        $mail->Body    = $template;
                                        $mail->AltBody = $template;

                                        $mail->send();

                                        if($mail)
                                            {
                                                $sent_test = 1;
                                                $sql = "UPDATE 
                                                            newsletter 
                                                        SET
                                                            sent_test=:sent_test
                                                        WHERE id = '$id'
                                                        ";
                        
                                                $statement = $db->prepare($sql);
                                                $statement->bindParam(':sent_test', $sent_test, PDO::PARAM_STR);
                                                
                                                try 
                                                    {
                                                        $statement->execute();
                                                        echo json_encode(array("statusCode"=>200));
                                                    }
                                                catch(PDOException $e)
                                                    {
                                                        echo json_encode(array("statusCode"=>201));
                                                    }
                                            }
                                    }

                                catch (Exception $e) 
                                    {
                                        echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
                                    }
                            }
                    }
                    
                
            }
    }

function order_received($order_no)
    {
        global $db;
        $order_no = $_SESSION['order_no'];
        $sql = "SELECT
                    id,
                    order_no,
                    email,
                    email_sent
                FROM
                    delivery
                WHERE
                    email_sent = 0
                AND order_no = '$order_no'
                ";
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();

        foreach ($result as $row)
            {
                $recipient = $row['email'];
                $order_num = $_SESSION['order_no'];
                
                $sql = "SELECT 
                            template
                        FROM
                            email_templates
                        WHERE
                            template_name = 'order_received'
                        ";
                $statement = $db->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll();

                foreach ($result as $row)
                    {
                        
                        $template = $row['template'];
                        $template = str_replace("%email%", $recipient, $template);
                        $template = str_replace("%order_no%", $order_num, $template);
                        //echo $template;
                        $sender = 'decoupagequeenpaper@gmail.com';
                        $senderName = 'Decoupage Queen - Order received';

                        $mail = new PHPMailer(true);

                        try
                            {
                                $mail->IsSMTP();
                                $mail->CharSet = 'UTF-8';

                                $mail->Host       = "email-smtp.eu-west-1.amazonaws.com"; // SMTP server example

                                $mail->SMTPAuth   = true;                  // enable SMTP authentication
                                $mail->Port       = 587;                    // set the SMTP port for the GMAIL server
                                $mail->Username   = "S3_KEY"; // SMTP account username example
                                $mail->Password   = "S3_SECRET";        // SMTP account password example
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
                                
                                if($mail)
                                    {
                                        $email_sent = 1;
                                        $order_no = $order_num;
                                        $sql = "UPDATE 
                                                    delivery 
                                                SET
                                                    email_sent=:email_sent
                                                WHERE
                                                    order_no = '$order_no'
                                                ";
                
                                        $statement = $db->prepare($sql);
                                        $statement->bindParam(':email_sent', $email_sent, PDO::PARAM_STR);
                                        $statement->execute();
                                    }
                            }

                        catch (Exception $e) 
                            {
                                echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
                            }
                    }
            }

        
    }

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
                                    user_level,
                                    admin_emails
                                FROM
                                    users
                                WHERE
                                    user_level = 'admin'
                                AND email = 'info@kingdomdesign.com'
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
                                        $sender = 'decoupagequeenpaper@gmail.com';
                                        $senderName = 'Decoupage Queen - New order received';
                
                                        $mail = new PHPMailer(true);
                
                                        try
                                            {
                                                $mail->IsSMTP();
                                                $mail->CharSet = 'UTF-8';
                
                                                $mail->Host       = "email-smtp.eu-west-1.amazonaws.com"; // SMTP server example
                
                                                $mail->SMTPAuth   = true;                  // enable SMTP authentication
                                                $mail->Port       = 587;                    // set the SMTP port for the GMAIL server
                                                $mail->Username   = "S3_KEY"; // SMTP account username example
                                                $mail->Password   = "S3_SECRET";        // SMTP account password example
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

function newsletter($id)
    {
        global $db;
        
        $sql = "SELECT 
                    *
                FROM
                    newsletter
                WHERE
                    id = '$id'
                ";
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        foreach($result as $row)
            {
                if ($row['sent'] == 0)
                    {
                        $body = html_entity_decode($row['post']);
                        $title = $row['title'];
                        $sql = "SELECT
                                    users.id,
                                    users.name AS name,
                                    users.email AS email,
                                    users.subscriber
                                FROM
                                    users
                                WHERE
                                    users.subscriber = 1
                                UNION
                                SELECT
                                    subscribers.id,
                                    subscribers.name AS name,
                                    subscribers.email AS email,
                                    subscribers.subscriber
                                FROM
                                    subscribers
                                WHERE subscribers.subscriber = 1
                                ";
                        $statement = $db->prepare($sql);
                        $statement->execute();
                        $result = $statement->fetchAll();
                
                        foreach ($result as $row)
                            {
                                $name = $row['name'];
                                $recipient = $row['email'];
                                $sql = "SELECT 
                                            template
                                        FROM
                                            email_templates
                                        WHERE
                                            template_name = 'news_letter'
                                        ";
                                $statement = $db->prepare($sql);
                                $statement->execute();
                                $result = $statement->fetchAll();
                
                                foreach ($result as $row)
                                    {
                                        
                                        $template = $row['template'];
                                        $template = str_replace("%title%", $title, $template);
                                        $template = str_replace("%name%", $name, $template);
                                        $template = str_replace("%email%", $recipient, $template);
                                        $template = str_replace("%body%", $body, $template);
                                        //echo $template;
                                        $sender = 'decoupagequeenpaper@gmail.com';
                                        $senderName = 'Decoupage Queen - Newsletter';
                
                                        $mail = new PHPMailer(true);
                
                                        try
                                            {
                                                $mail->IsSMTP();
                                                $mail->CharSet = 'UTF-8';
                
                                                $mail->Host       = "email-smtp.eu-west-1.amazonaws.com"; // SMTP server example
                
                                                $mail->SMTPAuth   = true;                  // enable SMTP authentication
                                                $mail->Port       = 587;                    // set the SMTP port for the GMAIL server
                                                $mail->Username   = "S3_KEY"; // SMTP account username example
                                                $mail->Password   = "S3_SECRET";        // SMTP account password example
                                                $mail->SMTPSecure = 'tls';
                                                $mail->AddEmbeddedImage('../images/nav-logo.png', 'logo', '../images/nav-logo.png');
                                                $mail->AddEmbeddedImage('../images/facebook.png', 'facebook', '../images/facebook.png');
                                                $mail->AddEmbeddedImage('../images/insta.png', 'insta', '../images/insta.png');
                                                $mail->AddEmbeddedImage('../images/youtube.png', 'youtube', '../images/youtube.png'); 
                                                $mail->setFrom($sender, $senderName);
                
                                                $mail->addAddress($recipient);
                                                
                                                $mail->isHTML(true);                                  // Set email format to HTML
                                                $mail->Subject = 'Decoupage Queen Newsletter';
                                                $mail->Body    = $template;
                                                $mail->AltBody = $template;
                
                                                $mail->send();

                                                if($mail)
                                                    {
                                                        $sent = 1;
                                                        $sql = "UPDATE 
                                                                    newsletter 
                                                                SET
                                                                    sent=:sent
                                                                WHERE id = '$id'
                                                                ";
                                
                                                        $statement = $db->prepare($sql);
                                                        $statement->bindParam(':sent', $sent, PDO::PARAM_STR);
                                                        $statement->execute();
                                                    }
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

function send_agreement($name, $email)
    {
        global $db;
        if(isset($_POST['name']) && isset($_POST['email']))
            {
                $name = $_POST['name'];
                $email = $_POST['email'];
            }
        $sql = "SELECT 
                    template
                FROM
                    email_templates
                WHERE
                    template_name = 'retailer_agreement'
                ";
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();

        foreach ($result as $row)
            {
                $recipient = $email;
                $template = $row['template'];
                $template = str_replace("%name%", $name, $template);
                //echo $template;
                $sender = 'decoupagequeenpaper@gmail.com';
                $senderName = 'Decoupage Queen - Retailer Application';

                $mail = new PHPMailer(true);

                try
                    {
                        $mail->IsSMTP();
                        $mail->CharSet = 'UTF-8';

                        $mail->Host       = "email-smtp.eu-west-1.amazonaws.com"; // SMTP server example

                        $mail->SMTPAuth   = true;                  // enable SMTP authentication
                        $mail->Port       = 587;                    // set the SMTP port for the GMAIL server
                        $mail->Username   = "S3_KEY"; // SMTP account username example
                        $mail->Password   = "S3_SECRET";        // SMTP account password example
                        $mail->SMTPSecure = 'tls';
                        $mail->AddEmbeddedImage('../images/nav-logo.png', 'logo', '../images/nav-logo.png');
                        $mail->AddEmbeddedImage('../images/facebook.png', 'facebook', '../images/facebook.png');
                        $mail->AddEmbeddedImage('../images/insta.png', 'insta', '../images/insta.png');
                        $mail->AddEmbeddedImage('../images/youtube.png', 'youtube', '../images/youtube.png');
                        $mail->setFrom($sender, $senderName);

                        $mail->addAddress($recipient);
                        
                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = 'Retailer Application';
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

function agreement_fao_admin()
    {
        global $db;
        $sql = "SELECT
                    id,
                    email,
                    name,
                    user_level,
                    admin_emails
                FROM
                    users
                WHERE
                    user_level = 'admin'
                AND email = 'info@kingdomdesign.com'
                ";
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        foreach ($result as $row)
            {
                $name = $row['name'];
                $email = $row['email'];

                $sql = "SELECT 
                            template
                        FROM
                            email_templates
                        WHERE
                            template_name = 'signed_agreement'
                        ";
                $statement = $db->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll();

                foreach ($result as $row)
                    {
                        $recipient = $email;
                        $template = $row['template'];
                        $template = str_replace("%name%", $name, $template);
                        //echo $template;
                        $sender = 'decoupagequeenpaper@gmail.com';
                        $senderName = 'Decoupage Queen - Retailer Signed Agreement';
                        $mail = new PHPMailer(true);

                        try
                            {
                                $mail->IsSMTP();
                                $mail->CharSet = 'UTF-8';
                                $mail->Host       = "email-smtp.eu-west-1.amazonaws.com"; // SMTP server example
                                $mail->SMTPAuth   = true;                  // enable SMTP authentication
                                $mail->Port       = 587;                    // set the SMTP port for the GMAIL server
                                $mail->Username   = "S3_KEY"; // SMTP account username example
                                $mail->Password   = "S3_SECRET";        // SMTP account password example
                                $mail->SMTPSecure = 'tls';
                                $mail->AddEmbeddedImage('../images/nav-logo.png', 'logo', '../images/nav-logo.png');
                                $mail->AddEmbeddedImage('../images/facebook.png', 'facebook', '../images/facebook.png');
                                $mail->AddEmbeddedImage('../images/insta.png', 'insta', '../images/insta.png');
                                $mail->AddEmbeddedImage('../images/youtube.png', 'youtube', '../images/youtube.png');
                                $mail->setFrom($sender, $senderName);

                                $mail->addAddress($recipient);
                                
                                $mail->isHTML(true);                                  // Set email format to HTML
                                $mail->Subject = 'Retailer Agreement';
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

?>