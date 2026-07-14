<?php
$sm_share = "";

ini_set('post_max_size', '64M'); 
ini_set('upload_max_filesize', '64M');
include '../includes/header.php';
include 'functions.php';
//phpinfo();
if(logged_in() AND user_admin())
    {
        require '../../../vendor/autoload.php';
        if(isset($_POST['submit']))
            {
                $skus = implode(",", $_POST['sku']);
                $sku = trim_array($skus);
                $uploadsDir = "uploads/";
                $allowedFileType = array('JPG', 'PNG', 'JPEG', 'jpg', 'jpeg', 'png', 'gif');
                
                // Velidate if files exist
                if (!empty(array_filter($_FILES['img']['name']))) 
                    {
                        // Loop through file items
                        foreach($_FILES['img']['name'] as $id=>$val)
                            {
                                // Get files upload path
                                $img        = $_FILES['img']['name'][$id];
                                $tempLocation    = $_FILES['img']['tmp_name'][$id];
                                $targetFilePath  = $img;
                                $fileType        = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

                                $sql = "INSERT INTO 
                                            scrap_images (img, sku)
                                        VALUES
                                            (:img, :sku)
                                        ";

                                $statement = $db->prepare($sql);
                                $statement->bindParam(':img'	,	$img  , PDO::PARAM_STR);
                                $statement->bindParam(':sku'	,	$sku  , PDO::PARAM_STR);
                                try
                                    {
                                        $statement->execute();
                                        $success = "<div class='alert alert-success'>Upload successful</div>";
                                    }
                                catch(PDOException $e)
                                    {
                                        echo $e;
                                        //$failed = "<div class='alert alert-danger'><strong>There was an issue, please try again</strong></div>";
                                    }

                                if(in_array($fileType, $allowedFileType))
                                    {
                                        $s3 = new Aws\S3\S3Client([
                                            'region'  => 'eu-west-1',
                                            'version' => 'latest',
                                            'credentials' => [
                                                'key'    => S3_KEY,
                                                'secret' => S3_SECRET,
                                            ]
                                        ]);		
                                        try
                                            {
                                                $result = $s3->putObject([
                                                'Bucket' => 'S3_BUCKET',
                                                'Key'    => $img,
                                                'SourceFile' => $tempLocation,
                                                'ACL'    => 'public-read',
                                                'ContentType' => 'image/png'		
                                                ]);
                                            }
                                        catch(S3Exception $e)
                                            {
                                                echo $e;
                                            }
                                        
                                            
                                        echo $result['ObjectURL'] . PHP_EOL;
                                        
                                    } 
                                else 
                                    {
                                        $response = array(
                                            "status" => "alert-danger",
                                            "message" => "Only .jpg, .jpeg and .png file formats allowed."
                                        );
                                    }
                            }
                    } 
            }    
          

?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Add new product</h1>
  </div>
</div>

<div class="container margin-top">
    <div class="row">
        <div class="col-12">
        <?php
            if(isset($success))
                {
                    echo $success;
                }
        ?>
        </div>
        
    </div>
    <div class="row">
        <div class="col-12">
            <form name="form" action="<?php $_SERVER["PHP_SELF"] ?>" method="post" id="details_contact" enctype="multipart/form-data">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>SKU</label>
                        <select name="sku[]" class="form-control">
                            <option value="">Select SKU</option>
                            <?=get_sku()?>
                        </select>
                    </div>
                </div>
                <!-- pricing for item in size -->
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Image</label>
                        <input type="file" name="img[]" class="form-control" multiple/>
                    </div>
                </div>
                <button name="submit" class="btn btn-info">Submit</button><br>
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