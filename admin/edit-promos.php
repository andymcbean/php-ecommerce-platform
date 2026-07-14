<?php
include '../includes/header.php';
include 'functions.php';

if(logged_in() AND user_admin())
    {
        $table_items = "";

        $sql = "SELECT
                    *
                FROM
                    promo_codes
                ";
        //echo $sql;
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $image_modal = "";
        foreach($result as $row)
            {
                $type = $row['type'];
                $table_items .= "<tr id='".$row['id']."'>
                                    <td data-target='type'>".$type."</td>
                                    <td data-target='code'>".$row['code']."</td>
                                    <td data-target='off'>".$row['off']."</td>
                                    <td data-target='value'>".$row['value']."</td>
                                    <td><a class='btn btn-info' href='#' data-role='update' data-id='".$row['id']."'><i class='fas fa-user-edit'></i></a></td>
                                    <td><button class='btn btn-danger delete-promo' id='del_".$row['id']."'> <i class='fas fa-trash-alt'></i></button></td>
                               </tr>";
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
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Edit promo codes</h1>
  </div>
</div>
    <div class="container margin-top">
        
        <div class="row">

            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Code</th>
                        <th>%/$</th>
                        <th>Discount</th>
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
                        <label style="color: #000;">Type</label>
                        <select id="type" class="form-control">
                            <option value="delivery">Delivery</option>
                            <option value="total">Total</option>
                            <option value="gift_card">Gift card</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="color: #000;">Code</label>
                        <input type="text" id="code" class="form-control">
                    </div>
                    <div class="form-group">
                        <label style="color: #000;">Money/Percent Off</label>
                        <select id="off" class="form-control">
                            <option value="percentage">Percentage</option>
                            <option value="money">Money</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label style="color: #000;">Discount</label>
                            <input type="text" id="value" class="form-control">
                        </div>
                    </div>
                        <input type="hidden" id="item-id" class="form-control">
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                    <a href="#" id="save" class="btn btn-info pull-right">Update</a>
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
        var type = $('#'+id).children('td[data-target=type]').text();
        var code = $('#'+id).children('td[data-target=code]').text();
        var off = $('#'+id).children('td[data-target=off]').text();
        var value = $('#'+id).children('td[data-target=value]').text();
    
        $('#type').val(type);
        $('#code').val(code);
        $('#off').val(off);
        $('#value').val(value);
        
        $('#item-id').val(id);
        $('#myModal').modal('toggle');

    });

    //get data and update db
    $('#save').click(function(){
        var id = $('#item-id').val();
        var type = $('#type').val();
        var code = $('#code').val();
        var off = $('#off').val();
        var value = $('#value').val();

        $.ajax({
            url     : 'update-promo',
            method  : 'post',
            data    : {type:type, code:code, off:off, value:value,  id:id},
            success : function(response){
                        $('#'+id).children('td[data-target=type]').text(type);
                        $('#'+id).children('td[data-target=code]').text(code);
                        $('#'+id).children('td[data-target=off]').text(off);
                        $('#'+id).children('td[data-target=value]').text(value);
                        
                        $('#myModal').modal('toggle');
            }
        });

    });
});

//delete units
$(document).ready(function(){

// Delete 
$('.delete-promo').click(function(){
  var el = this;
  var id = this.id;
  var splitid = id.split("_");

  // Delete id
  var delete_promo = splitid[1];

  // AJAX Request
  var confirmalert = confirm("Are you sure?");
  if (confirmalert == true) {
  $.ajax({
    url: 'delete-promo',
    type: 'POST',
    data: { id:delete_promo },
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