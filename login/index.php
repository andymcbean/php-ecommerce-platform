<?php
include '../includes/header.php';
?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Log in</h1>
  </div>
</div>
    <div class="container margin-top">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-6">
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
                <form action="login-script" method="post" style="padding: 15px;" id="login">
                    <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password_id" type="password" name="password" class="form-control" placeholder="Password"><span toggle="#password_id" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                    </div>
                    <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input">
                    <label class="form-check-label">Remember me</label>
                    </div>
                    <button name="submit" type="submit" class="btn btn-info btn-max">Log in</button><br><br>
                    <a role="button " class="btn btn-warning btn-max" href="../password-reset/">Forgot password?</a>
                </form>
            </div>
            
        </div>
            
        <div class="container">

   <div class="panel panel-default">

   </div>
  </div>        
    </div>
    <br><br><br><br><br>
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