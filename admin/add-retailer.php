
<?php
include '../includes/header.php';
include 'functions.php';
if(logged_in() AND user_admin())
    {
if (isset($_POST['submit']))
    {
        $name       = test_input_pw($_POST['name']);
        $address    = test_input_pw($_POST['address']);
        $country    = test_input_pw($_POST['country']);
        $state      = test_input_pw($_POST['state']);
        $url1       = test_input_pw($_POST['url1']);
        $fb         = test_input_pw($_POST['fb']);

        $sql = "INSERT INTO 
                    retailers (name, address, country, state, url1, fb)
                VALUES
                    (:name, :address, :country, :state, :url1, :fb)
                ";

        $statement = $db->prepare($sql);

        $statement->bindParam(':name'	            ,	$name                    , PDO::PARAM_STR);
        $statement->bindParam(':address'	    ,	$address            , PDO::PARAM_STR);
        $statement->bindParam(':state'	                ,	$state                     , PDO::PARAM_STR);
        $statement->bindParam(':country'	                ,	$country                     , PDO::PARAM_STR);
        $statement->bindParam(':url1'	                ,	$url1                     , PDO::PARAM_STR);
        $statement->bindParam(':fb'	                ,	$fb                     , PDO::PARAM_STR);

        try
            {    
                $statement->execute();
                $success = "<div class='alert alert-success'><strong>New retailer added</strong></div>";
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
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Add new retailer</h1>
  </div>
</div>

<div class="container margin-top">
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
                <form name="form" action="<?php $_SERVER["PHP_SELF"] ?>" method="post" id="details_contact">
                    <div class="form-group">
                        <label>Name</label>
                        <input name="name" type="text" class="form-control" placeholder="Retailer name">
                    </div>
                    <div class="form-group">
                        <label>Country</label>
                        <select class="form-control" name="country">
                            <?php echo country_list() ?>
                        </select>
                    </div>
                
                    <div class="form-group">
                        <label>State/Region</label>
                        <input name="state" type="text" class="form-control" placeholder="">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea class="form-control" name="address" id="" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Website link</label>
                        <input name="url1" type="text" class="form-control" placeholder="https://example1.com">
                    </div>
                    <div class="form-group">
                        <label>Fcaebook link</label>
                        <input name="fb" type="text" class="form-control" placeholder="https://facebook.com/example2">
                    </div>
                    <button name="submit" class="btn btn-info" style="min-width: 100%;">Submit</button><br>
                </form>
            </div>
            
        </div>
    </div><br><br><br><br><br>
<?php
    }
    else
        {
            echo "<div class='alert alert-danger'><strong>You do not have permission to access this page</strong></div>";
        }

    include '../includes/footer.php';
?>