<?php
$sm_share = "";
require_once '../includes/header.php';
require_once 'functions.php';
//include '../includes/email-functions.php';

if(logged_in() AND user_admin())
    {
        if(isset($_POST['submit_img']))
            {
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

        if(isset($_POST['submit']))
            {
                $errors = array();
                if(empty($_POST['title']))
                    {
                        $errors['title'] = "<br><div class='container'><div class='alert alert-danger'> Title required. Please <a style='color: red;' href='../admin/newsletter'> try again.</a></div></div>";
                        
                    }
                else
                    {
                        $title = test_input_pw($_POST['title']);
                    }

                if(empty($_POST['post']))
                    {
                        $errors['post'] = "<br><div class='container'><div class='alert alert-danger'> Article required. Please <a style='color: red;' href='../admin/newsletter'> try again.</a></div></div>";
                        
                    }
                else
                    {
                        $post = htmlentities($_POST['post']);
                    }
                    $total_errors = count($errors);
                    if($total_errors > 0)
                        {
                            $not_sent = implode("\n", $errors);
                        }
                    else
                        {
                            $sql = "INSERT INTO 
                                        newsletter (title, post)
                                    VALUES
                                        (:title, :post)
                                    ";

                            $statement = $db->prepare($sql);

                            $statement->bindParam(':title'	    ,	$title,   PDO::PARAM_STR);
                            $statement->bindParam(':post'	    ,	$post,  PDO::PARAM_STR);
                                try
                                    {
                                        $statement->execute();
                                        //echo newsletter();
                                        $success = "<br><div class='container'><div class='alert alert-success'> Newsletter added to database <a href='composed-newsletters'>View before sending</a></div></div>";
                                    }

                                catch(PDOException $e)
                                    {   
                                        echo $e;
                                        $error = "<br><div class='container'><div class='alert alert-danger'> Insert failed</div></div>";
                                    } 
                        }
                       
            
            }
?>
<script>
    tinymce.init({
  selector: '#mytextarea',
  plugins: 'link image code lists emoticons',
  toolbar: 'undo redo | styleselect | bold italic underline | link image emoticons | align bullist numlist | code removeformat',
  paste_data_images: true,
  /* enable title field in the Image dialog*/
  image_title: true,
  relative_urls : false,
remove_script_host : false,
document_base_url : 'https://dqdev.co.uk/',
  /*
    URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
    images_upload_url: 'postAcceptor.php',
    here we add custom filepicker only to Image dialog
  */
  file_picker_types: 'image',
  /* and here's our custom image picker*/
  file_picker_callback: function (cb, value, meta) {
    var input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');

    /*
      Note: In modern browsers input[type="file"] is functional without
      even adding it to the DOM, but that might not be the case in some older
      or quirky browsers like IE, so you might want to add it to the DOM
      just in case, and visually hide it. And do not forget do remove it
      once you do not need it anymore.
    */

    input.onchange = function () {
      var file = this.files[0];

      var reader = new FileReader();
      reader.onload = function () {
        /*
          Note: Now we need to register the blob in TinyMCEs image blob
          registry. In the next release this part hopefully won't be
          necessary, as we are looking to handle it internally.
        */
        var id = 'blobid' + (new Date()).getTime();
        var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
        var base64 = reader.result.split(',')[1];
        var blobInfo = blobCache.create(id, file, base64);
        blobCache.add(blobInfo);

        /* call the callback and populate the Title field with the file name */
        cb(blobInfo.blobUri(), { title: file.name });
      };
      reader.readAsDataURL(file);
    };

    input.click();
  },
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});
 </script>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Compose Newsletter</h1>
  </div>
</div>
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12 mb-3">
            <?php if(isset($result))
                    {
                        echo $result['ObjectURL'] . PHP_EOL;
                    }
            ?>
        </div>
        <div class="col-12">
            <form name="form" action="<?php $_SERVER["PHP_SELF"] ?>" method="post" id="details_contact" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Upload Image(s) to be intserted into this newsletter. Or right click on an image on the website and copy the image address. If you copy the image address from the website you do not need to upload it here.</label>
                        <input type="file" name="img[]" class="form-control" multiple/>
                        <button name="submit_img" class="btn btn-info">Upload</button>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
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
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Content - To add images, click the image icon and copy the image url eg <strong>https://kingdomdesign.s3.eu-west-1.amazonaws.com/DQRP_0021%20Grecian%20Goddess.jpg</strong>. Paste it into the source box and the image will be displayed. You may need to resize. To link the image, highlight the image image and click the link icon next to the image icon and type the full url you want to link the image to eg: <strong>https://decoupagequeen.com/decoupage/details?sku=DQRp_0021</strong>.</label>
                    <textarea id="mytextarea" name="post" rows="50"></textarea>
                </div>
                <button class="btn btn-info" name="submit" style="min-width: 100%;">Submit</button>
            </form>
        </div>
    </div>
</div>
<?php
    }
    else
        {
            echo "<br><div class='container alert alert-danger'><strong>You do not have permission to access this page</strong></div>";
        }

    include '../includes/footer.php';
?>