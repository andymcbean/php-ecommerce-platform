<?php
$sm_share = "";
include '../includes/header.php';

?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Reset password</h1>
  </div>
</div>
<div class="container margin-top" style="margin-bottom: 250px;">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-6">
        <?php
            if (isset($_GET['reset']))
                {
                    if ($_GET['reset'] == 'success')
                        {
                            echo "<div class='container alert alert-success'>An email has been sent to the address provided. Please click on the link to reset your password.</div>";
                        }
                    elseif ($_GET['reset'] == 'error')
                        {
                            echo "<div class='container alert alert-danger'>The email address you entered is not registered with Decoupage Queen.</div>";
                        }
                }
        ?>
        <form action="reset" method="post" id="details_contact" style="padding: 15px;">
            <div class="form-group">
            <label style="color: black;" for="email">Forgot you password?</label>
            <input type="text" class="form-control" name="email" placeholder="Enter your email address...">
            </div>
            <button name="submit" type="submit" class="btn btn-primary">Reset password</button><br><br>
        </form>
            
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>