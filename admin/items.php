<?php
include '../includes/header.php';
include '../includes/constants.php';
include 'functions.php';

if(logged_in() AND user_admin())
    {
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
                    
                        $success = "<br><div class='container alert alert-success'> Upload successful. 
                                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>";
    
                    }

                catch(PDOException $e)
                    {   
                        echo $e;
                        $error = "<div class='container'><div class='alert-danger'> Update failed</div></div>";
                    }    
        
        }

        $table_items = "";

        $sql = "SELECT
                    *
                FROM
                    items
                ";
        //echo $sql;
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $image_modal = "";
        $active = "";
        foreach($result as $row)
            {
                $img = $row['img'];
                if($row['type'] == "Rice Paper" && $row['retail_only'] == "no"  && $row['us_only'] == "" && $row['us_only'] == null)
                    {
                        $link = '../decoupage/details?sku=';
                    }
                elseif($row['type'] == "Rice Paper" && $row['retail_only'] == "no"  && $row['us_only'] == "yes")
                    {
                        $link = '../us-retail/details?sku=';
                    }
                elseif($row['type'] == "Scrapbook")
                    {
                        $link = '../scrapbooks/details?sku=';
                    }
                $sku = $row['sku'];
                $table_items .= "<tr id='".$row['id']."'>
                                    <td data-target='sku'><a href='".$link."".$sku."' target='_blank' data-toggle='tooltip' data-placement='bottom' title='View item'>".$sku."</a></td>
                                    <td data-target='type'>".$row['type']."</td>
                                    <td data-target='description'>".$row['description']."</td>
                                    <td data-target='retail_only'>".$row['retail_only']."</td>
                                    <td data-target='us_retail_only'>".$row['us_only']."</td>
                                    <td data-target='a4'>".$row['a4']."</td>
                                    <td data-target='a3'>".$row['a3']."</td>
                                    <td data-target='xl'>".$row['xl']."</td>
                                    <td data-target='img' width='80'><a style='cursor: pointer;' data-toggle='modal' data-target='#imgModal".$row['id']."'><p style='font-size: 10px;'>Change image</p></a></td>
                                    <td data-target='active'>".$row['active']."</td>
                                    <td data-target='stock_a4'>".$row['stock_a4']."</td>
                                    <td data-target='stock_a3'>".$row['stock_a3']."</td>
                                    <td data-target='stock_xl'>".$row['stock_xl']."</td>
                                    <td data-target='stock_scrapbook'>".$row['stock_scrapbook']."</td>
                                    <td data-target='stock_chipboard'>".$row['stock_chipboard']."</td>
                                    <td data-target='discount'>".$row['discount']."</td>
                                    <td><a style='cursor: pointer;' class='btn btn-info' data-role='update' data-id='".$row['id']."'><i class='fas fa-user-edit'></i></a></td>
                                    <td><button class='btn btn-danger delete-item' id='del_".$row['id']."'> <i class='fas fa-trash-alt'></i></button></td>
                               </tr>";

                $image_modal .= "<div class='modal' id='imgModal".$row['id']."'>
                                    <div class='modal-dialog'>
                                        <div class='modal-content'>

                                        <div class='modal-header'>
                                            <h4 class='modal-title'>Update Image</h4>
                                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                        </div>
                                        <div class='modal-body'>
                                            <img src='".IMAGE_URL."".$img."' class='img-fluid' style='max-width: 70%;'>
                                            <form action='' method='post' enctype='multipart/form-data'>
                                                <div class='form-group'>
                                                    <label>New image</label>
                                                    <input type='file' class='form-control' name='img'>
                                                </div>
                                                <input type='hidden' name='id' value='".$row['id']."' class='form-control'>
                                                <button name='update_img' class='btn btn-info pull-right'>Update</button> 
                                            </form>
                                        </div>

                                        <div class='modal-footer'>
                                            <button type='button' class='btn btn-danger pull-left' data-dismiss='modal'>Close</button>
                                            
                                        </div>
                                    
                                        </div>
                                    </div>
                                </div>";
            }


?>

    <?php 
        if(isset($success))
            {
                echo $success;
            }
    ?>
    <div class="hero-image">
        <div class="hero-text">
            <h1 style="font-size:50px; color: #fff" class="nav-icon">Edit Items</h1>
        </div>
    </div>
    <div class="container-fluid margin-top">
        <div class="row no-gutters">

            <table class="table table-responsive table-hover">
                <thead>
                    <tr>
                        <th class="sticky" scope="col">Sku</th>
                        <th class="sticky" scope="col">Product</th>
                        <th class="sticky" scope="col">Description</th>
                        <th class="sticky" scope="col">Retail only</th>
                        <th class="sticky" scope="col">US Retail only</th>
                        <th class="sticky" scope="col">A4</th>
                        <th class="sticky" scope="col">A3</th>
                        <th class="sticky" scope="col">XL</th>
                        <th class="sticky" scope="col">img</th>
                        <th class="sticky" scope="col">Active</th>
                        <th class="sticky" scope="col">Qty A4</th>
                        <th class="sticky" scope="col">Qty A3</th>
                        <th class="sticky" scope="col">Qty XL</th>
                        <th class="sticky" scope="col">Qty Scrapbook</th>
                        <th class="sticky" scope="col">Qty Chipboard</th>
                        <th class="sticky" scope="col">Discount</th>
                        <th class="sticky" scope="col">Edit</th>
                        <th class="sticky" scope="col">Delete</th>
                    </tr>
                </thead>
                <?php echo $table_items; ?>
            </table>
            </div>
        </div>
    </div>
    <!-- The Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="color: #000;">Update</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label style="color: #000;">Description</label>
                        <input type="text" id="description" class="form-control">
                    </div>
                    <div class="form-group">
                        <label style="color: #000;">Unique id (sku)</label>
                        <input type="text" id="sku" class="form-control">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label style="color: #000;">A4</label>
                            <select id="a4" class="form-control">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label style="color: #000;">A3</label>
                            <select id="a3" class="form-control">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label style="color: #000;">XL</label>
                            <select id="xl" class="form-control">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label style="color: #000;">In stock A4</label>
                            <input type="text" id="stock_a4" class="form-control">
                                
                        </div>
                        <div class="form-group col-md-6">
                            <label style="color: #000;">In stock A3</label>
                            <input type="text" id="stock_a3" class="form-control">
                                
                        </div>
                        <div class="form-group col-md-6">
                            <label style="color: #000;">In stock XL</label>
                            <input type="text" id="stock_xl" class="form-control"> 
                        </div>
                        <div class="form-group col-md-6">
                            <label style="color: #000;">In stock Scrapbook</label>
                            <input type="text" id="stock_scrapbook" class="form-control"> 
                        </div>
                        <div class="form-group col-md-6">
                            <label style="color: #000;">In stock Chipboard</label>
                            <input type="text" id="stock_chipboard" class="form-control"> 
                        </div>
                        <div class="form-group col-md-6">
                            <label style="color: #000;">Discount</label>
                            <input type="text" id="discount" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label style="color: #000;">Active on site</label>
                            <select id="active" class="form-control">
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label style="color: #000;">Retailer only item</label>
                            <select id="retail_only" class="form-control">
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                            <label style="color: #000;">US Retailer only item</label>
                            <select id="us_retail_only" class="form-control">
                                <option value="yes">yes</option>
                                <option value="no">no</option>
                            </select>
                        </div>
                    </div>
                        <input type="hidden" id="item-id" class="form-control">
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                    <a id="save" class="btn btn-info pull-right">Update</a>
                </div>

            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <?php echo $image_modal; ?>
<?php
    }
    else
        {
            echo "<br><div class='container alert alert-danger'><strong>You do not have permission to access this page</strong></div>";
        }

    include '../includes/footer.php';
?>
<script>
$(document).ready(function(){

    // add the values to input fields
    $(document).on('click','a[data-role=update]',function(){
        var id = $(this).data('id');
        var description = $('#'+id).children('td[data-target=description]').text();
        var sku = $('#'+id).children('td[data-target=sku]').text();
        var a4 = $('#'+id).children('td[data-target=a4]').text();
        var a3 = $('#'+id).children('td[data-target=a3]').text();
        var xl = $('#'+id).children('td[data-target=xl]').text();
        var stock_a4 = $('#'+id).children('td[data-target=stock_a4]').text();
        var stock_a3 = $('#'+id).children('td[data-target=stock_a3]').text();
        var stock_xl = $('#'+id).children('td[data-target=stock_xl]').text();
        var stock_scrapbook = $('#'+id).children('td[data-target=stock_scrapbook]').text();
        var stock_chipboard = $('#'+id).children('td[data-target=stock_chipboard]').text();
        var retail_only = $('#'+id).children('td[data-target=retail_only]').text();
        var us_retail_only = $('#'+id).children('td[data-target=us_retail_only]').text();
        var discount = $('#'+id).children('td[data-target=discount]').text();
        var active = $('#'+id).children('td[data-target=active]').text();
        
        $('#description').val(description);
        $('#sku').val(sku);
        $('#a4').val(a4);
        $('#a3').val(a3);
        $('#xl').val(xl);
        $('#stock_a4').val(stock_a4);
        $('#stock_a3').val(stock_a3);
        $('#stock_xl').val(stock_xl);
        $('#stock_scrapbook').val(stock_scrapbook);
        $('#stock_chipboard').val(stock_chipboard);
        $('#retail_only').val(retail_only);
        $('#us_retail_only').val(us_retail_only);
        $('#discount').val(discount);
        $('#active').val(active);
        
        $('#item-id').val(id);
        $('#myModal').modal('toggle');

    });

    //get data and update db
    $('#save').click(function(){
        var id = $('#item-id').val();
        var description = $('#description').val();
        var sku = $('#sku').val();
        var a4 = $('#a4').val();
        var a3 = $('#a3').val();
        var xl = $('#xl').val();
        var stock_a4 = $('#stock_a4').val();
        var stock_a3 = $('#stock_a3').val();
        var stock_xl = $('#stock_xl').val();
        var retail_only = $('#retail_only').val();
        var us_retail_only = $('#us_retail_only').val();
        var stock_scrapbook = $('#stock_scrapbook').val();
        var stock_chipboard = $('#stock_chipboard').val();
        var discount = $('#discount').val();
        var active = $('#active').val();

        $.ajax({
            url     : 'update-item',
            method  : 'post',
            data    : {description:description, sku:sku, a4:a4, a3:a3, xl:xl, stock_a4:stock_a4, stock_a3:stock_a3, stock_xl:stock_xl, stock_scrapbook:stock_scrapbook, stock_chipboard:stock_chipboard, discount:discount, active:active, retail_only:retail_only, us_retail_only:us_retail_only, id:id},
            success : function(response){
                        alert('Item updated!');
                        location.reload();
            }
        });

    });
});

//delete items
$(document).ready(function(){

// Delete 
$('.delete-item').click(function(){
  var el = this;
  var id = this.id;
  var splitid = id.split("_");

  // Delete id
  var delete_id = splitid[1];

  // AJAX Request
  var confirmalert = confirm("Are you sure?");
  if (confirmalert == true) {
  $.ajax({
    url: 'delete-item',
    type: 'POST',
    data: { id:delete_id },
    success: function(response){

    if(response)
        {
            // Remove row from HTML Table
            $(el).closest('tr').css('background','#fe0400');
            $(el).closest('tr').fadeOut(800,function(){
            $(this).remove();
            });
        }
    
            }
        });
    }

});

});
</script>