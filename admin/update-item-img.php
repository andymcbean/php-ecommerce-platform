<?php
include '../../includes/connect.php';
include '../../includes/constants.php';
include '../../includes/functions.php';

    if(isset($_POST['update_img']))
        {
            $id = $_POST['id'];
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

                    echo $result['ObjectURL'] . PHP_EOL;
                }
            else
                {
                    echo "<br><div class='container alert alert-danger'>Image required or file type not supported. Image must be .png, .jpg or .jpeg</div>";
                }
                
                try
                    {

                        $sql = "UPDATE 
                                    items
                                SET
                                    img=:img
                                WHERE
                                    id = '$id'
                                ";
            
                        $statement = $db->prepare($sql);    
                        $statement->bindParam(':img',	$img, PDO::PARAM_STR);
                        $statement->execute();
                    
                        $success = "<div class='container'><div class='alert alert-success'> Unit edited sucessfully. View <a href='../users/my-portfolio' style='color: green'>portofilo</a> or <a href='../users/add-domain' style='color: green'>add another doamin</a></div></div>";
    
                    }

                catch(PDOException $e)
                    {   
                        echo $e;
                        $error = "<div class='container'><div class='alert-danger'> Update failed. Please <a style='color: red;' href='../users/my-portfolio'> try again.</a></div></div>";
                    }    
        $db = null;
    }
?>