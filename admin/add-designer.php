<?php
include '../includes/header.php';
include 'functions.php';
if(logged_in() AND user_admin())
    {
if (isset($_POST['submit']))
    {
        $name           = test_input_pw($_POST['name']);
        $bio            = htmlentities($_POST['bio']);
        $fb             = test_input_pw($_POST['fb']);
        $site           = test_input_pw($_POST['site']);
        $insta          = test_input_pw($_POST['insta']);
        $yt             = test_input_pw($_POST['yt']);

        $img = $_FILES['img']['name'];
        $path_info = pathinfo($img);
        if(isset($_FILES['img']) AND is_valid_filetype($path_info['extension']))
            {
                
                $temp_file_location = $_FILES['img']['tmp_name']; 

                require '../../../vendor/autoload.php';

                $s3 = new Aws\S3\S3Client([
                    'region'  => 'eu-west-1',
                    'version' => 'latest',
                    'credentials' => [
                        'key'    => S3_KEY,
                        'secret' => S3_SECRET,
                    ]
                ]);		

                $result = $s3->putObject([
                    'Bucket' => 'S3_BUCKET',
                    'Key'    => $img,
                    'SourceFile' => $temp_file_location,
                    'ACL'    => 'public-read',
                    'ContentType' => 'image/png'		
                ]);

                //echo $result['ObjectURL'] . PHP_EOL;
            }
        else
            {
                echo "<br><div class='container alert alert-danger'>Image required or file type not supported. Image must be .png, .jpg or .jpeg</div>";
            }
        try
            {
                $sql = "INSERT INTO 
                            designers (name, bio, img, fb, site, insta, yt)
                        VALUES
                            (:name, :bio, :img, :fb, :site, :insta, :yt)
                        ";

                $statement = $db->prepare($sql);

                $statement->bindParam(':name'	    ,	$name       , PDO::PARAM_STR);
                $statement->bindParam(':bio'	    ,	$bio        , PDO::PARAM_STR);
                $statement->bindParam(':img'	    ,	$img        , PDO::PARAM_STR);
                $statement->bindParam(':site'	    ,	$site       , PDO::PARAM_STR);
                $statement->bindParam(':fb'	        ,	$fb         , PDO::PARAM_STR);
                $statement->bindParam(':insta'	    ,	$insta      , PDO::PARAM_STR);
                $statement->bindParam(':yt'	        ,	$yt         , PDO::PARAM_STR);
        
                $statement->execute();
                $success = "<div class='alert alert-success'><strong>Designer added.</strong><a href='#'>Add another?</a></div>";
            }
        catch(PDOException $e)
            {
                echo $e;
                $failed = "<div class='alert alert-danger'><strong>There was an issue, please try again</strong></div>";
            }
            
    }
        

?>
<script>
    tinymce.init({
      selector: '#bio'
    });
 </script>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Add new product</h1>
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
        <form name="form" action="<?php $_SERVER["PHP_SELF"] ?>" method="post" id="details_contact" enctype="multipart/form-data">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Name</label>
                    <input name="name" type="text" class="form-control" placeholder="Name">
                </div>
            </div>
            <div class="form-group">
                <label>Bio</label>
                <textarea id="bio" name="bio" rows="30"></textarea>
            </div>
            
            <!-- pricing for item in size -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Facebook (optional)</label>
                    <input name="fb" type="text" class="form-control" placeholder="Link to Facebook page">
                    
                </div>
                
                <div class="form-group col-md-6">
                <label>Website (optional)</label>
                    <input name="site" type="text" class="form-control" placeholder="Link to website">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Youtube Channel (optional)</label>
                    <input name="yt" type="text" class="form-control" placeholder="Link to YouTube channel">
                    
                </div>
                
                <div class="form-group col-md-6">
                <label>Instagram (optional)</label>
                    <input name="insta" type="text" class="form-control" placeholder="Link to Instagram">
                </div>
            </div>
            <div class="form-row">
                
                <div class="form-group col-md-6">
                    <label>Profile Image</label>
                    <input name="img" type="file" class="form-control"/>
                </div>
            </div>

            <button name="submit" class="btn btn-info btn-block">Submit</button><br>
        </form>
    </div><br><br><br><br><br>
<?php
    }
    else
        {
            echo "<div class='alert alert-danger'><strong>You do not have permission to access this page</strong></div>";
        }

    include '../includes/footer.php';
?>