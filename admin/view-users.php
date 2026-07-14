<?php
include '../includes/header.php';
include 'functions.php';

if(logged_in() AND user_admin())
    {
        $table_items = "";
        $sql = "SELECT
                    id,
                    name,
                    email,
                    user_level,
                    retailer,
                    us_retailer
                FROM
                    users
                ";
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $image_modal = "";
        foreach($result as $row)
            {
                $us_retailer = $row['us_retailer'];
                $retailer = $row['retailer'];
                $table_items .= "<tr id='".$row['id']."'>
                                    <td data-target='id'>".$row['id']."</td>
                                    <td data-target='name'>".$row['name']."</td>
                                    <td data-target='email'>".$row['email']."</td>
                                    <td data-target='user_level'>".$row['user_level']."</td>
                                    <td data-target='retailer'>".$retailer."</td>
                                    <td data-target='us_retailer'>".$us_retailer."</td>
                                    <td><a class='btn btn-info' href='#' data-role='update' data-id='".$row['id']."'><i class='fas fa-user-edit'></i></a></td>
                                    <td><button class='btn btn-danger delete-user' id='del_".$row['id']."'> <i class='fas fa-trash-alt'></i></button></td>
                               </tr>";
            }
        if(isset($success))
            {
                echo $success;
            }
    ?>
<div class="hero-image">
    <div class="hero-text">
        <h1 style="font-size:50px; color: #fff" class="nav-icon">Registered users</h1>
    </div>
</div>
<?php echo user_retailer() ?>
    <div class="container margin-top">
        
        <div class="row">
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>email</th>
                        <th>User level</th>
                        <th>Retailer</th>
                        <th>US Retailer</th>
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
                    <div class="form-group">
                        <label style="color: #000;">Email</label>
                        <input type="text" id="email" class="form-control">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label style="color: #000;">Retailer</label>
                            <select id="retailer" class="form-control">
                                <option value="No">No</option>
                                <option value="Yes">Yes</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label style="color: #000;">US Retailer</label>
                            <select id="us_retailer" class="form-control">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label style="color: #000;">User level</label>
                            <select id="user_level" class="form-control">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                        <input type="hidden" id="user-id" class="form-control">
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
        var name = $('#'+id).children('td[data-target=name]').text();
        var email = $('#'+id).children('td[data-target=email]').text();
        var user_level = $('#'+id).children('td[data-target=user_level]').text();
        var retailer = $('#'+id).children('td[data-target=retailer]').text();
        var us_retailer = $('#'+id).children('td[data-target=us_retailer]').text();
    
        $('#name').val(name);
        $('#email').val(email);
        $('#user_level').val(user_level);
        $('#retailer').val(retailer);
        $('#us_retailer').val(us_retailer);
        
        $('#user-id').val(id);
        $('#myModal').modal('toggle');

    });

    //get data and update db
    $('#save').click(function(){
        var id = $('#user-id').val();
        var name = $('#name').val();
        var email = $('#email').val();
        var user_level = $('#user_level').val();
        var retailer = $('#retailer').val();
        var us_retailer = $('#us_retailer').val();

        $.ajax({
            url     : 'update-user',
            method  : 'post',
            data    : {name:name, email:email, user_level:user_level, retailer:retailer, us_retailer:us_retailer, id:id},
            success : function(response){
                        $('#'+id).children('td[data-target=name]').text(name);
                        $('#'+id).children('td[data-target=email]').text(email);
                        $('#'+id).children('td[data-target=user_level]').text(user_level);
                        $('#'+id).children('td[data-target=retailer]').text(retailer);
                        $('#'+id).children('td[data-target=us_retailer]').text(us_retailer);
                        
                        $('#myModal').modal('toggle');
            }
        });

    });
});

//delete users
$(document).ready(function(){

// Delete 
$('.delete-user').click(function(){
  var el = this;
  var id = this.id;
  var splitid = id.split("_");

  // Delete id
  var delete_user = splitid[1];

  // AJAX Request
  var confirmalert = confirm("Are you sure?");
  if (confirmalert == true) {
  $.ajax({
    url: 'delete-user',
    type: 'POST',
    data: { id:delete_user },
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