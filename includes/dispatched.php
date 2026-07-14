<?php
use PHPMailer\PHPMailer\PHPMailer;
include 'connect.php';
require '../../../vendor/autoload.php';

function order_dispatched($order_no)
    {
        global $db;
        $order_no = $_POST['order_no'];
        $sql = "SELECT
                    id,
                    order_no,
                    email,
                    name,
                    dispatched,
                    carrier,
                    code,
                    email_sent
                FROM
                    delivery
                WHERE
                    order_no = '$order_no'
                ";
                //echo $sql; die();
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();

        foreach ($result as $row)
            {
                $carrier = $row['carrier'];
                $code = $row['code'];
                $name = $row['name'];
                $recipient =  'info@kingdomdesign.com';//$row['email'];
                $order_num = $_POST['order_no'];
                $track = "";
                if($carrier == 'UPS')
                    {
                        $track = 'https://www.ups.com/track?loc=null&tracknum='.$code.'&requester=WT/trackdetails';
                    }
                elseif($carrier == 'USPS')
                    {
                        $track = 'https://tools.usps.com/go/TrackConfirmAction.action?tLabels='.$code.'';
                    }
                //echo $track; die();
                $sql = "SELECT 
                            template
                        FROM
                            email_templates
                        WHERE
                            template_name = 'dispatched'
                        ";
                $statement = $db->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll();

                foreach ($result as $row)
                    {
                        $template = $row['template'];
                        $template = str_replace("%name%", $name, $template);
                        $template = str_replace("%order_no%", $order_num, $template);
                        $template = str_replace("%track%", $track, $template);
                        //echo $template;
                        $sender = 'andy@mcbean.me';
                        $senderName = 'Decoupage Queen - Order dispatched';

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
                                $mail->Subject = 'Order dispatched';
                                $mail->Body    = $template;
                                $mail->AltBody = $template;

                                $mail->send();
                                
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

                        catch (Exception $e) 
                            {
                                echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
                            }
                    }
            }

        
    }