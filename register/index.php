
<?php
use PHPMailer\PHPMailer\PHPMailer;
include ('../includes/header.php');
require '../../../vendor/autoload.php';

date_default_timezone_set('Etc/Greenwich');

if(isset($_POST['submit']))  
    {
        $errors = array();

        if (!empty($_POST['name']))
            {
                $name = test_input_pw($_POST['name']);
            }
        else
            {
                $errors['name'] = "<div class='container alert alert-danger'>Name required</div>";
            }
    
        if (empty($_POST['email']))
            {
                $errors['email'] = "<div class='container alert alert-danger'>Email address required</div>";  
            } 
        else
            {
                if(!empty($_POST['email']))
                    {
                        $email = test_input_pw($_POST['email']);
                        $sql = "SELECT email FROM users WHERE email = '$email'";
                        
                        $statement = $db ->prepare($sql);
                        $statement ->execute();
                        $result = $statement ->fetchAll();
                        $total_row = $statement ->rowCount();

                        if($total_row > 0)
                            {
                                $errors['email'] = "<div class='alert alert-danger'>The email you have enetered is already in use. <a href='../register/'>Try again.</a> If you have forgotten your password please reset it <a href='../password-reset/'>here</a></div>";
                                //exit();
                            } 
                        else
                            {
                                $email = test_input_pw($_POST['email']);
                            }
                    }
            }

        if (!empty($_POST['password']))
            {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            } 
        else
            {   
                $errors['password'] = "<div class='container alert alert-danger'>Password required</div>";
            }
            
            $date         = date("Y-m-d H:i:s");
            $ip           = $_SERVER['REMOTE_ADDR'];
            $user_level   = 'user';
            $total_errors = count($errors);

        if($total_errors > 0)
            {
                $not_sent = implode("\n", $errors);
            }
        else
            {
               try
                {
                    
                    $sql = "INSERT INTO 
                                users (name, email, password, reg_date, ip, user_level)
                            VALUES
                                (:name, :email, :password, :reg_date, :ip, :user_level)
                            ";

                    $statement = $db->prepare($sql);
                    
                    $statement->bindParam(':name'	        ,	$name, PDO::PARAM_STR);
                    $statement->bindParam(':email'	        ,	$email, PDO::PARAM_STR);
                    $statement->bindParam(':password'	    ,	$password, PDO::PARAM_STR);
                    $statement->bindParam(':reg_date'      	,	$date, PDO::PARAM_STR);
                    $statement->bindParam(':ip'      	    ,	$ip, PDO::PARAM_STR);
                    $statement->bindParam(':user_level'     ,	$user_level, PDO::PARAM_STR);

                    $statement->execute();

                    $success = "<div class='container alert alert-success'>Congratulations. Your registration has been successful. You can now <a href='../login'>log in</a></div>";
                    send_welcome($name, $email);
                }

            catch(PDOException $e)
                {
                    echo $e;
                    $failed = "<div class='container alert alert-danger'>Registration failed. Please try again.</div>";
                } 
            }
        

        $db = null;
    } 
?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Register</h1>
  </div>
</div>
    <div class="container margin-top">
        <div class="row">
            <div class="col-12 col-md-6 mx-auto">
                <?php
                    if(isset($success))
                        {
                            echo $success;
                        }
                    if(isset($not_sent))
                        {
                            echo $not_sent;
                        }
                ?>
                <br>
            
                <?php 
                    if(isset($facebook_login_url))
                        {
                            echo $facebook_login_url;
                        }
                ?>
                <br>
                <?php 
                    //if(isset($login_button))
                    // {
                        //   echo '<div align="center">'.$login_button . '</div>';
                //     }
                ?>
                <hr>
                <form name="form" action="<?php $_SERVER["PHP_SELF"] ?>" method="post" id="details_contact">

                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="inputName">Name</label>
                            <input name="name" id="name" type="text" class="form-control" placeholder="Full name">
                        </div>
                        <div class="form-group col-12">
                            <label for="inputEmail">Email</label>
                            <input name="email" type="email" class="form-control" id="email" placeholder="Email">
                        </div>
                        <div class="form-group col-12">
                            <label for="password">Password</label>
                            <input type="hidden" name="length" value="15">
                            <input id="password_id" id="exampleInputPassword1" type="password" name="password" class="form-control" placeholder="Password">
                            
                            <span toggle="#password_id" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            
                        </div>
                        <button name="submit" class="btn btn-info" style="min-width: 100%;">Register your account</button><br>
                    </div>
                </form>
            </div>
        </div>
    </div><br><br><br><br><br>
<?php
include ('../includes/footer.php');
?>
<script>
$(".toggle-password").click(function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") 
        {
        input.attr("type", "text");
        } 
    else 
        {
        input.attr("type", "password");
        }
    });

</script>

<?php
function send_welcome($name, $email)
{
    global $db;
    if(isset($_POST['name']) && isset($_POST['email']))
        {
            $name = $_POST['name'];
            $email = $_POST['email'];
        }
    else
        {
            $name = $_SESSION['user_name'];
            $email = $_SESSION['user_email_address'];
        }
    $sql = "SELECT 
                template
            FROM
                email_templates
            WHERE
                template_name = 'welcome'
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
            $sender = 'andy@mcbean.me';
            $senderName = 'Decoupage Queen - Welcome';

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
                    $mail->Subject = 'Welcome from Decoupage Queen';
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
?>

