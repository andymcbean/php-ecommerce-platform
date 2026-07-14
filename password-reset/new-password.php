<?php
$sm_share = "";
include '../includes/header.php';
?>

<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">New Password</h1>
  </div>
</div>

<?php
$selector = $_GET['selector'];
$validator = $_GET['validator'];

$date = date("U");
//$expires = "";
$sql = "SELECT 
            expires
        FROM
            password_reset
        WHERE
            selector = '$selector'
        ";
$statement = $db->prepare($sql);
$statement->execute();
$result = $statement->fetchAll();

foreach($result as $row)
    {
        $expires = $row['expires'];
    }

if ($date > $expires)
    {
        echo "<div class='container alert alert-warning' style='margin-bottom: 250px;'>This link has now expired. Please send another request to reset your password <a style='color: black;' href='../password-reset/'>Forgot password</a></div>";
        include '../includes/footer.php';
        die();
    }
else
    {
        if (empty($selector) OR empty($validator))
            {
                echo "<div class='container alert alert-danger'>Could not validate your request</div>";
            }
        else
            {
                if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) 
                    {
?>

            <div class="container margin-top" style="margin-bottom: 250px;">
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-6">
                    <form action="reset-password" method="post" id="details_contact" style="padding: 15px;">
                    <div class="form-group">
                                <input type="hidden" name="selector" value="<?php echo $selector ?>">
                                <input type="hidden" name="validator" value="<?php echo $validator ?>">
                                <label for="password">New password</label>
                                <input type="hidden" name="length" value="15">
                                <input id="password_id" id="exampleInputPassword1" type="password" name="password" class="form-control" placeholder="Enter new password"><span toggle="#password_id" class="fa fa-fw fa-eye field-icon toggle-password"></span><br>
                                
                                
                                <div class="text-danger" id="passwordmessage"></div>      
                            </div>
                        <button name="submit" type="submit" class="btn btn-primary">Submit</button><br><br>
                    </form>
                    </div>
                </div>
            </div>
                

<?php
                }
            }
    }
?>

<?php
include '../includes/footer.php';
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