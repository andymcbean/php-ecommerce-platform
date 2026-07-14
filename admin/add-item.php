<?php
include '../includes/header.php';
include 'functions.php';
if(logged_in() AND user_admin())
    {
if (isset($_POST['submit']))
    {
        $sku             = test_input_pw($_POST['sku']);
        $description     = test_input_pw($_POST['description']);
        $retail_only     = test_input_pw($_POST['retail_only']);
        $active          = test_input_pw($_POST['active']);
        $type            = test_input_pw($_POST['type']);
        $stock_a4        = test_input_pw($_POST['stock_a4']);
        $stock_a3        = test_input_pw($_POST['stock_a3']);
        $stock_xl        = test_input_pw($_POST['stock_xl']);
        $price_a4        = test_input_pw($_POST['price_a4']);
        $price_a3        = test_input_pw($_POST['price_a3']);
        $price_xl        = test_input_pw($_POST['price_xl']);
        $price_scrap     = test_input_pw($_POST['price_scrap']);
        $price_chipboard = test_input_pw($_POST['price_chipboard']);
        $stock_scrapbook = test_input_pw($_POST['stock_scrapbook']);
        $stock_chipboard = test_input_pw($_POST['stock_chipboard']);
        
        if($_POST['item_description'] == "")
            {
                $item_description = "";
            }
        else
            {
                $item_description = test_input_pw($_POST['item_description']);
            }

        if($stock_chipboard > 0)
            {
                $chipboard = 'yes';
            }
        else
            {
                $chipboard = 'no';
            }

        if($stock_scrapbook > 0)
            {
                $scrapbook = 'yes';
            }
        else
            {
                $scrapbook = 'no';
            }

        if($stock_a4 > 0)
            {
                $a4 = 'yes';
            }
        else
            {
                $a4 = 'no';
            }
        if($stock_a3 > 0)
            {
                $a3 = 'yes';
            }
        else
            {
                $a3 = 'no';
            }
        if($stock_xl > 0)
            {
                $xl = 'yes';
            }
        else
            {
                $xl = 'no';
            }

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
            }
        else
            {
                echo "<br><div class='container alert alert-danger'>Image required or file type not supported. Image must be .png, .jpg or .jpeg</div>";
            }
        
                $sql = "INSERT INTO 
                            items (sku, description, img, a4, a3, xl, scrapbook, chipboard, type, stock_a4, stock_a3, stock_xl, active, retail_only, stock_scrapbook, stock_chipboard, item_description, price_a4, price_a3, price_xl, price_scrap, price_chipboard)
                        VALUES
                            (:sku, :description, :img, :a4, :a3, :xl, :scrapbook, :chipboard, :type, :stock_a4, :stock_a3, :stock_xl, :active, :retail_only, :stock_scrapbook, :stock_chipboard, :item_description, :price_a4, :price_a3, :price_xl, :price_scrap, :price_chipboard)
                        ";

                $statement = $db->prepare($sql);

                $statement->bindParam(':sku'	            ,	$sku                    , PDO::PARAM_STR);
                $statement->bindParam(':description'	    ,	$description            , PDO::PARAM_STR);
                $statement->bindParam(':img'	            ,	$img                    , PDO::PARAM_STR);
                $statement->bindParam(':a3'	                ,	$a3                     , PDO::PARAM_STR);
                $statement->bindParam(':a4'	                ,	$a4                     , PDO::PARAM_STR);
                $statement->bindParam(':xl'	                ,	$xl                     , PDO::PARAM_STR);
                $statement->bindParam(':scrapbook'	        ,	$scrapbook              , PDO::PARAM_STR);
                $statement->bindParam(':chipboard'	        ,	$chipboard              , PDO::PARAM_STR);
                $statement->bindParam(':type'	            ,	$type                   , PDO::PARAM_STR);
                $statement->bindParam(':price_a4'	        ,	$price_a4               , PDO::PARAM_STR);
                $statement->bindParam(':price_a3'	        ,	$price_a3               , PDO::PARAM_STR);
                $statement->bindParam(':price_xl'	        ,	$price_xl               , PDO::PARAM_STR);
                $statement->bindParam(':price_scrap'	    ,	$price_scrap            , PDO::PARAM_STR);
                $statement->bindParam(':price_chipboard'	,	$price_chipboard        , PDO::PARAM_STR);
                $statement->bindParam(':stock_a3'	        ,	$stock_a3               , PDO::PARAM_INT);
                $statement->bindParam(':stock_a4'	        ,	$stock_a4               , PDO::PARAM_INT);
                $statement->bindParam(':stock_xl'	        ,	$stock_xl               , PDO::PARAM_INT);
                $statement->bindParam(':active'	            ,	$active                 , PDO::PARAM_STR);
                $statement->bindParam(':retail_only'	    ,	$retail_only            , PDO::PARAM_STR);
                $statement->bindParam(':stock_scrapbook'	,	$stock_scrapbook        , PDO::PARAM_INT);
                $statement->bindParam(':stock_chipboard'	,	$stock_chipboard        , PDO::PARAM_INT);
                $statement->bindParam(':item_description'	,	$item_description       , PDO::PARAM_STR);
        try
            {
                $statement->execute();
                $success = "<div class='alert alert-success'><strong>New item added</strong></div>";
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
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Add new product</h1>
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
        <form name="form" action="<?php $_SERVER["PHP_SELF"] ?>" method="post" id="details_contact" enctype="multipart/form-data">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Product Type</label>
                    <select name="type" class="form-control">
                        <option value="Rice Paper">Rice Paper</option>
                        <option value="Scrapbook">Scrapbook</option>
                        <option value="Chipboard">Chipboard</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Display on site</label>
                    <select name="active" class="form-control">
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Retailer only item</label>
                    <select name="retail_only" class="form-control">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Title</label>
                    <input name="description" type="text" class="form-control" placeholder="Title/Description">
                </div>
                <div class="form-group col-md-6">
                    <label>sku</label>
                    <input name="sku" type="text" class="form-control" placeholder="SKU">
                </div>
            </div>
            <hr />
            <h3>Quantities</h3>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Quantity available in A4 (enter 0 if NA)</label>
                    <input name="stock_a4" type="number" class="form-control">
                    
                </div>
                <div class="form-group col-md-6">
                    <label>Quantity available in A3 (enter 0 if NA)</label>
                    <input name="stock_a3" type="number" class="form-control">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Quantity available in XL (enter 0 if NA)</label>
                    <input name="stock_xl" type="number" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label>Quantity available for scrapbook (enter 0 if NA)</label>
                    <input name="stock_scrapbook" type="number" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label>Quantity available for chipboard (enter 0 if NA)</label>
                    <input name="stock_chipboard" type="number" class="form-control">
                </div>
            </div>
            <hr />
            <h3>Pricing</h3>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Price for A4 (enter 0 if NA)</label>
                    <input name="price_a4" type="text" class="form-control" value="2.95">
                </div>
                <div class="form-group col-md-6">
                    <label>Price for A3 (enter 0 if NA)</label>
                    <input name="price_a3" type="text" class="form-control" value="4.95">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Price for XL (enter 0 if NA)</label>
                    <input name="price_xl" type="text" class="form-control" value="6.95">
                </div>
                <div class="form-group col-md-6">
                    <label>Price for scrapbook (enter 0 if NA)</label>
                    <input name="price_scrap" type="text" class="form-control" value="16.00">
                </div>
                <div class="form-group col-md-6">
                    <label>Price for chipboard (enter 0 if NA)</label>
                    <input name="price_chipboard" type="text" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label>Item description (Leave blank if using default description)</label>
                    <textarea name="item_description" class="form-control" rows="8"></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Main Image - Add multiple images for scrap book <a href="/admin/add-scrapbook">here</a></label>
                    <input name="img" type="file" class="form-control"/>
                </div>
            </div>

            <button name="submit" class="btn btn-info btn-block">Submit</button><br>
        </form>
    </div>
<?php
    }
    else
        {
            echo "<div class='alert alert-danger'><strong>You do not have permission to access this page</strong></div>";
        }

    include '../includes/footer.php';
?>