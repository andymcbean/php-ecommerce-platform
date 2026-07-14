<?php
use PHPMailer\PHPMailer\PHPMailer;
require '../../../vendor/autoload.php';
include '../includes/header.php';

function strposa($haystack, $needle, $offset=0) 
    {
        if(!is_array($needle)) $needle = array($needle);
        foreach($needle as $query) {
            if(strpos($haystack, $query, $offset) !== false) return true; // stop on first true result
        }
        return false;
    }

if (isset($_POST['submit']))
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $spam = array(
            'http',
            'lucky',
            '@',
            '.com',
            '.co.uk',
            'claims',
            'lottery',
            'claim',
            'award',
            'congratulations',
            'winner',
            'payout',
            'insurance',
            'win',
            'pounds',
            'www',
            '//'
           );

        $errors = array();

        if (!empty($_POST['name']))
            {
                $name = $_POST['name'];
            }
        else    
            {
                $errors['name'] = "<div class='container'><div class='alert alert-danger'>Name required <a href='../contact/'>Try again</a></div></div>";
            }
        if (!empty($_POST['email']))
            {	
                $email = $_POST['email'];
            }
        else
            {
                $errors['email'] = "<div class='container'><div class='alert alert-danger'>Email required <a href='../contact/'>Try again</a></div></div>";
            }
        if (!empty($_POST['phone']))
            {	
                $phone = $_POST['phone'];
            }
        else
            {
                $errors['phone'] = "<div class='container'><div class='alert alert-danger'>Contact number required <a href='../contact/'>Try again</a></div></div>";
            }
    
        if (empty($_POST['message']))
            {
                $errors['message'] = "<div class='container'><div class='alert alert-danger'>Message required <a href='../contact/'>Try again</a></div></div>";
            }
            
        elseif((strposa($_POST['message'], $spam)))
            {
                $errors['message'] = "<div class='container'><div class='alert alert-danger'>Spam has been detected in this message. Please <a href='../contact/'>Try again</a></div></div>";
            }
        else
            {
                $msg = $_POST['message'];
            }

        $total_errors = count($errors);

        if($total_errors > 0)
            {
                $not_sent = implode("\n", $errors);
            }
        else    
            {
                $mail = new PHPMailer(true);

                try
                    {
                        
                        $recipient = 'andy@mcbean.me';
                        $sender = 'info@kingdomdesign.com';
                        $senderName = 'Decoupage Queen Website';

                        $mail->IsSMTP();
                        $mail->CharSet = 'UTF-8';

                        $mail->Host       = "email-smtp.eu-west-1.amazonaws.com"; // SMTP server example

                        $mail->SMTPAuth   = true;                  // enable SMTP authentication
                        $mail->Port       = 587;                    // set the SMTP port for the GMAIL server
                        $mail->Username   = "AWS_SECRET_KEY"; // SMTP account username example
                        $mail->Password   = "BBTt/REDVt+Vg2RZ5goldyEEPSz8lBkcUQDkfWT5fxwd";        // SMTP account password example
                        $mail->SMTPSecure = 'tls';
                        $mail->setFrom($sender, $senderName);

                        $mail->addAddress($recipient);
                        
                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = 'Enquiry from DecoupageQueen.com';
                        
                        $mail->Body  = "From: $name <br>
                                        Email: $email <br>
                                        Phone: $phone <br>
                                        Message: $msg <br>
                                        IP Address: $ip <br>";

                        $mail->send();

                        if($mail)
                            {
                                $success = '<div class="container mt-5"><div class="alert alert-success">Thank You for your message. We will be in touch</div></div>';
                            }
                    }
                catch (phpmailerException $e) 
                    {
                        echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
                    } 
                catch (Exception $e) 
                    {
                        echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
                    }  
            }
            
    }
?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Contact</h1>
  </div>
</div>
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
<div class="container margin-top"><br>
    
    <div class="row"><br>
        <div class="col-12 col-md-4 mb-3">
            <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" style="border-radius: 4px; margin-top: -30px;" id="login">
                <div class="form-group">
                    <label style="color: white;">Name</label>
                    <input name="name" type="text" class="form-control" placeholder="Name">
                </div>
                <div class="form-group">
                    <label style="color: white;">Email Address</label>
                    <input name="email" type="email" class="form-control" placeholder="Email">
                </div>
                <div class="form-group">
                    <label style="color: white;">Contact Number</label>
                    <input name="phone" type="number" class="form-control" placeholder="Phone">
                </div>
                
                <div class="form-group">
                    <label style="color: white;">Message</label>
                    <textarea name="message" class="form-control" rows="5" placeholder="Message"></textarea>
                </div>
                <div id="captcha" class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="6Lf2YBAaAAAAAOY1nOPZpJ4N-IF-3gxkdKi2X25V"></div>
                <br>
                <button id="submitBtn" name="submit" type="submit" class="btn btn-info btn-block" disabled><i class="fa fa-paper-plane"></i> Send Message</button>
            </form>
        </div>
        <div class="col-12 col-md-4">
            <p><strong>Email:</strong> <a href="mailto:decoupagequeenpaper@gmail.com" style="color:#000;">decoupagequeenpaper@gmail.com</a></p>
            <p><strong>In Person:</strong></p>
            <p style="margin-top: -10px;">Some papers available at:</p>
            <p style="margin-top: -10px;">TH Decor Booth THD</p>
            <p style="margin-top: -10px;">Queen of Hearts</p>
            <p style="margin-top: -10px;">2745 Sandy Plains Rd</p>
            <p style="margin-top: -10px;">Marietta, GA <a href="http://maps.google.com/maps?q=30066" target="_blank">30066</a></p>
        </div>
        <div class="col-12 col-md-4">
            <h3>WE MIGHT BE ABLE TO ANSWER YOUR QUESTIONS RIGHT NOW, CHECK OUT OUR <a href="../faq" style="color:#000;">FAQ PAGE</a></h3>
        </div>
    </div>
</div><br><br><br>
<div>
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6614.038419076292!2d-84.49599929536883!3d34.0177178634673!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88f5133a967b98d7%3A0xd1e87f23fecc57b1!2s2745%20Sandy%20Plains%20Rd%2C%20Marietta%2C%20GA%2030066%2C%20USA!5e0!3m2!1sen!2suk!4v1606837565847!5m2!1sen!2suk" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
</div>

<script>	
    function recaptchaCallback() 
        {
            $('#submitBtn').removeAttr('disabled');
        };
</script>
<?php
include '../includes/footer.php';
?>
