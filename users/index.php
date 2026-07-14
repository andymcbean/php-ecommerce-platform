<?php
include '../includes/header.php';

if(logged_in_fb() || logged_in() || logged_in_google())
    {
        $checked = "";
        $subscribed = "";
        if(user_subscriber())
            {
                $value = 1;
                $checked = "checked";
                $subscribed = "You are subscribed";
            }
        else
            {
                $value = 0;
                $checked = "";
                $subscribed = "You are not subscribed";
            }

?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">My Account</h1>
  </div>
</div>

    <div class="container margin-top">
        <div class="row mb-3">
            <div class="col-12">
            <?php
                $email = "";
                if(isset($_SESSION["user_image"]) && isset($_SESSION['user_name']) && isset($_SESSION['user_email_address']))
                    {
                        echo '<img src="'.$_SESSION["user_image"].'" class="img-fluid img-circle img-thumbnail" />';
                        echo '<h3><b>Name :</b> '.$_SESSION['user_name'].'</h3>';
                        $email = $_SESSION['user_email_address'];
                        echo '<h3><b>Email :</b> '.$_SESSION['user_email_address'].'</h3>';;
                    }
                else
                    {
                        echo "";
                    }
                if(isset($_SESSION["g_user_image"]) && isset($_SESSION['g_user_first_name']) && isset($_SESSION['g_user_last_name']) && isset($_SESSION['g_user_email_address']))
                    {
                        echo '<img src="'.$_SESSION["g_user_image"].'" class="img-fluid img-circle img-thumbnail" />';
                        echo '<h3><b>Name :</b> '.$_SESSION['g_user_first_name'].' '.$_SESSION['g_user_last_name'].'</h3>';
                        $email = $_SESSION['g_user_email_address'];
                        echo '<h3><b>Email :</b> '.$_SESSION['g_user_email_address'].'</h3>';
                    }
                else
                    {
                        echo "";
                    }
                if(isset($_SESSION["email"]))
                    {
                        $email = $_SESSION['email'];
                        echo '<h3><b>Email :</b> '.$_SESSION['email'].'</h3>';
                    }
                else
                    {
                        echo "";
                    }
            ?>
            </div>  
        </div>
        <?php 
        if(user_retailer())
            {
                echo "You are registered as a Retailer";
            }
        else
            {
                echo "";
            }
        ?>
        <br>

        <h3>Dashboard</h3>
        <div class="row">
            <?php 
            if(user_admin())
                {
               ?> 
                    <div class="col-md-3">
                        <div class="text-center card d-flex align-items-center h-100 admin-card" style="padding: 10px;">
                            <h4>Admin</h4>
                            <i class="fas fa-user-cog" style="font-size: 75px; margin-top: 10px;"></i>
                            <div class="card-body">
                            <br><h3 class="card-text"><strong>Admin Management</strong></h3>
                            </div>
                            <div>
                                <a type="button" class="btn btn-info" href="../admin/">Admin</a>
                            </div>
                        </div>
                    </div>
             <?php
                }
            ?>   
            
            <div class="col-md-3">
                <div class="text-center card d-flex align-items-center h-100 admin-card" style="padding: 10px;">
                    <h4>My Orders</h4>
                    <i class="fas fa-file" style="font-size: 75px; margin-top: 10px;"></i>
                    <div class="card-body">
                      <br><h3 class="card-text"><strong>View your orders</strong></h3>
                    </div>
                    <div>
                        <a type="button" class="btn btn-info" href="my-orders">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center card d-flex align-items-center h-100 admin-card" style="padding: 10px;">
                    <h4>Wishlist</h4>
                    <i class="far fa-heart" style="font-size: 75px; margin-top: 10px;"></i>
                    <div class="card-body">
                      <br><h3 class="card-text"><strong>View wishlist</strong></h3>
                    </div>
                    <div>
                        <a type="button" class="btn btn-info" href="wishlist">View</a>
                    </div>
                </div>
            </div>
            <?php
                if(user_retailer())
                    {
                        echo "";
                    }
                else
                    {
                    
            ?>
            <div class="col-md-3">
                <div class="text-center card d-flex align-items-center h-100 admin-card" style="padding: 10px;">
                    <h4>Retailer</h4>
                    <i class="fas fa-store" style="font-size: 75px; margin-top: 10px;"></i>
                    <div class="card-body">
                      <br><h3 class="card-text"><strong>Become a Retailer</strong></h3>
                    </div>
                    <div>
                        <a type="button" class="btn btn-info" href="apply">Apply</a>
                    </div>
                </div>
            </div>
            <?php
                    }
            ?>
        </div><br>
        <div class="row">
            <div class="col-md-3">
                <div class="text-center card d-flex align-items-center h-100 admin-card" style="padding: 10px;">
                    <h3 class="card-text"><strong>Subscribe</strong></h3>
                    <i class="far fa-paper-plane" style="font-size: 75px; margin-top: 10px;"></i>
                    <div class="card-body">
                    <br><h4>Subscribe to our newsletter</h4>
                    </div>
                    <div>
                        <label class="switch">
                        <input class="form-control" type="checkbox" name="subscribe" id="subscribe" value="<?=$value?>" <?=$checked?>>
                            <span class="slider round"></span>
                        </label>
                        <input type="hidden" name="email" id="email" value="<?php echo $email ?>">
                        <p><?=$subscribed?></p>
                    </div>
                </div>
            </div>
            <?php
                    if(retail_agreement_id())
                        {
                            echo "<div class='col-md-3'>
                                    <div class='text-center card d-flex align-items-center h-100 admin-card' style='padding: 10px;'>
                                        <h3 class='card-text'><strong>Retail Agreement</strong></h3>
                                        <i class='far fa-paper-plane' style='font-size: 75px; margin-top: 10px;'></i>
                                        <div class='card-body'>
                                        <br><h4>Your Retail Agreement</h4>
                                        </div>
                                        <div>
                                            <a type='button' class='btn btn-info' href='retail-agreement?email=".$email."'>View</a>
                                        </div>
                                    </div>
                                </div>";
                        }
                ?>
        </div>
        
    </div><br>
        
        <br>
<script>
$(document).ready(function() {
    $('#subscribe').on('click', function() {
    var subscribe = $('#subscribe').val();
    var email = $('#email').val();
    
        $.ajax({
            url: "update-sub",
            type: "POST",
            data: {
                subscribe: subscribe,
                email: email			
            },
            cache: false,
            success: function(dataResult){
                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode==200){
                    alert("Preference updated!");	
                    window.location.reload();			
                }
                else if(dataResult.statusCode==201){
                alert("Error occured !");
                }  
            
            }
        });           
    });
});
</script>
<?php
    }
    else
        {
            
            echo "<br><div class='container alert alert-danger margin-top'><strong>You need to be logged in to access this page</strong></div>";
        }

    include '../includes/footer.php';
?>