
<?php
include '../includes/header.php';
include 'functions.php';
if(logged_in() AND user_admin())
    {
        if (isset($_POST['submit']))
            {
                $code       = test_input_pw($_POST['code']);
                $type       = test_input_pw($_POST['type']);
                $off        = test_input_pw($_POST['off']);
                $value      = test_input_pw($_POST['value']);

                $sql = "INSERT INTO 
                            promo_codes (code, type, off, value)
                        VALUES
                            (:code, :type, :off, :value)
                        ";
                $statement = $db->prepare($sql);
                $statement->bindParam(':code'	 ,	$code    , PDO::PARAM_STR);
                $statement->bindParam(':type'	 ,	$type    , PDO::PARAM_STR);
                $statement->bindParam(':value'	 ,	$value   , PDO::PARAM_STR);
                $statement->bindParam(':off'	 ,	$off     , PDO::PARAM_STR);
                
                try
                    {    
                        $statement->execute();
                        $success = "<div class='alert alert-success'><strong>New promo code added</strong></div>";
                    }
                catch(PDOException $e)
                    {
                        echo $e;
                        $failed = "<div class='alert alert-danger'><strong>There was an issue, please try again</strong></div>";
                    }     
            }
?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Add promo code</h1>
  </div>
</div>

<div class="container margin-top mb-5">
        <?php
        if (isset($success))
            {
                echo $success;
            }
        if (isset($failed))
            {
                echo $failed;
            }
        ?>
        <div class="row">
            <div class="col-md-6  mx-auto">
                <form name="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" id="details_contact">
                    <div class="form-group">
                        <label>Code</label>
                        <input type="hidden" name="length" value="15">
                        <input name="code" type="text" class="form-control" placeholder="Code">
                        <input type="button" class="btn btn-info mt-3" value="Generate" onclick="generate();" tabindex="2">
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select class="form-control" name="type">
                            <option value="delivery">Discount Delivery</option>
                            <option value="cart">Discount shopping cart total</option>
                            <option value="gift_card">Gift card</option>
                        </select>
                    </div>
                
                    <div class="form-group">
                        <label>Percent/Money</label>
                        <select class="form-control" name="off">
                            <option value="percentage">Percentage off</option>
                            <option value="money">Money off</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Value (Percent off must be set as 0.?? eg 0.15 = 15%. Money must be full amounts eg 10, 30, 45)</label>
                        <input name="value" type="text" class="form-control" placeholder="Value off">
                    </div>
                    <button name="submit" class="btn btn-info" style="min-width: 100%;">Submit</button><br>
                </form>
            </div>
            
        </div>
    </div>
<?php
    }
    else
        {
            echo "<div class='alert alert-danger'><strong>You do not have permission to access this page</strong></div>";
        }

    include '../includes/footer.php';
?>
<script>

function randomPassword(length) {
var chars = "ABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
var pass = "";
for (var x = 0; x < length; x++) {
    var i = Math.floor(Math.random() * chars.length);
    pass += chars.charAt(i);
}
return pass;
}

function generate() {
form.code.value = randomPassword(form.length.value);
}
</script>