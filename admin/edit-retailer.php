<?php
$sm_share = "";
include '../includes/header.php';
include 'functions.php';

if(logged_in() AND user_admin())
    {
        $table_items = "";

        $sql = "SELECT
                    *
                FROM
                    retailers
                ";
        //echo $sql;
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $image_modal = "";
        $address = "";
        foreach($result as $row)
            {
                $table_items .= "<tr id='".$row['id']."'>
                                    <td data-target='name'>".$row['name']."</td>
                                    <td data-target='address'>".$row['address']."</td>
                                    <td data-target='country'>".$row['country']."</td>
                                    <td data-target='state'>".$row['state']."</td>
                                    <td class='hide' data-target='url1'>".$row['url1']."</td>
                                    <td class='hide' data-target='fb'>".$row['fb']."</td>
                                    <td><a class='btn btn-info' data-role='update' data-id='".$row['id']."'><i class='fas fa-user-edit'></i></a></td>
                                    <td><button class='btn btn-danger delete-retailer' id='del_".$row['id']."'> <i class='fas fa-trash-alt'></i></button></td>
                               </tr>";

                $image_modal .= "<div class='modal' id='imgModal".$row['id']."'>
                                    <div class='modal-dialog'>
                                        <div class='modal-content'>

                                        <div class='modal-header'>
                                            <h4 class='modal-title'>Update Image</h4>
                                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                        </div>
                                        <div class='modal-body'>
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
    <div class="container margin-top">
        <div class="row">
        
        </div>
            <table class="table table-responsive table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Country</th>
                        <th>State</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <?php echo $table_items; ?>
            </table>
            
            </div>
        </div>
    </div>
    <!-- The Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="color: #000;">Update</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label style="color: #000;">Name</label>
                        <input type="text" id="name" class="form-control">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label style="color: #000;">Country</label>
                            <input type="text" id="country" class="form-control">
                                
                        </div>
                        <div class="form-group col-md-6">
                            <label style="color: #000;">State</label>
                            <input type="text" id="state" class="form-control">
                                
                        </div>
                        <div class="form-group col-md-6">
                            <label style="color: #000;">Website</label>
                            <input type="text" id="url1" class="form-control"> 
                        </div>
                        <div class="form-group col-md-6">
                            <label style="color: #000;">Facebook</label>
                            <input type="text" id="fb" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label style="color: #000;">Address</label>
                        <input type="text" id="address" class="form-control">
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
        var name = $('#'+id).children('td[data-target=name]').text();
        var country = $('#'+id).children('td[data-target=country]').text();
        var state = $('#'+id).children('td[data-target=state]').text();
        var url1 = $('#'+id).children('td[data-target=url1]').text();
        var fb = $('#'+id).children('td[data-target=fb]').text();
        var address = $('#'+id).children('td[data-target=address]').text();
    
        $('#name').val(name);
        $('#country').val(country);
        $('#state').val(state);
        $('#url1').val(url1);
        $('#fb').val(fb);
        $('#address').val(address);
        
        $('#item-id').val(id);
        $('#myModal').modal('toggle');

    });

    //get data and update db
    $('#save').click(function(){
        var id = $('#item-id').val();
        var name = $('#name').val();
        var country = $('#country').val();
        var state = $('#state').val();
        var url1 = $('#url1').val();
        var fb = $('#fb').val();
        var address = $('#address').val();

        $.ajax({
            url     : 'update-retailer',
            method  : 'post',
            data    : {name:name, country:country, state:state, url1:url1, fb:fb, address:address, id:id},
            success : function(response){
                        $('#'+id).children('td[data-target=name]').text(name);
                        $('#'+id).children('td[data-target=country]').text(country);
                        $('#'+id).children('td[data-target=state]').text(state);
                        $('#'+id).children('td[data-target=url1]').text(url1);
                        $('#'+id).children('td[data-target=fb]').text(fb);
                        $('#'+id).children('td[data-target=address]').text(address);
                        
                        $('#myModal').modal('toggle');
            }
        });

    });
});

//delete items
$(document).ready(function(){

// Delete 
$('.delete-retailer').click(function(){
  var el = this;
  var id = this.id;
  var splitid = id.split("_");

  // Delete id
  var del_id = splitid[1];

  // AJAX Request
  var confirmalert = confirm("Are you sure?");
  if (confirmalert == true) {
  $.ajax({
    url: 'delete-retailer',
    type: 'POST',
    data: { id:del_id },
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