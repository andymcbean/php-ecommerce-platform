<?php
include '../includes/header.php';


if(logged_in() AND user_admin())
    {

        $sql = "SELECT
                    *
                FROM
                    sales_tax
                ";
        //echo $sql;
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $tax_btn = "";
        foreach($result as $row)
            {
                $tax_btn .= "<tr id='".$row['id']."'>
                             <td data-target='amount'>".$row['amount']."</td>
                             <td><a class='btn btn-info' href='#' data-role='update' data-id='".$row['id']."'>Update GA sales tax</a></td>
                            </tr>";
            }
            $hash = password_hash('https://kingdomdesign.s3-eu-west-1.amazonaws.com/kingdomnames.mp4', PASSWORD_DEFAULT);
            $src = 'https://kingdomdesign.s3-eu-west-1.amazonaws.com/kingdomnames.mp4';
?>

<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Admin</h1>
  </div>
</div>
    <div class="container margin-top">
        <div class="row">
            <div class="col-12">
                <?php echo '<h4>Welcome <strong> '.$_SESSION['email'].' </strong></h4>'; ?>
            </div>  
        </div>
        <!--<video width="100%" height="100%" controls>
            <source src="<?=$src?>" type="video/mp4">
            
            Your browser does not support the video tag.
        </video>-->
        <div class="row">  
            <div class="col-sm-4">
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>Value</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <?php echo $tax_btn; ?>
                </table>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-4">
                <div class="text-center card d-flex align-items-center h-100 admin-card" style=" padding: 10px;">
                    <h4>Orders</h4>
                    <i class="fas fa-file" style="font-size: 75px; margin-top: 10px;"></i>
                    <div class="card-body">
                      <br><h3 class="card-text"><strong>View orders</strong></h3>
                    </div>
                    <div>
                        <a type="button" class="btn btn-info" href="orders">View</a>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-12 col-sm-6 col-md-4">
                <div class="text-center card d-flex align-items-center h-100 admin-card" style=" padding: 10px;">
                    <h4>Users</h4>
                    <i class="fas fa-users" style="font-size: 75px; margin-top: 10px;"></i>
                    <div class="card-body">
                    <br><h3 class="card-text"><strong>View users</strong></h3>
                    </div>
                    <div>
                        <a type="button" class="btn btn-info" href="view-users">View</a>
                        <a type="button" class="btn btn-info" href="view-subscribers">View Subscribers</a>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-12 col-sm-6 col-md-4">
                <div class="text-center card d-flex align-items-center h-100 admin-card" style=" padding: 10px;">
                    <h4>Retailers</h4>
                    <i class="fas fa-store" style="font-size: 75px; margin-top: 10px;"></i>
                    <div class="card-body">
                    <br><h3 class="card-text"><strong>Add a retailer</strong></h3>
                    </div>
                    <div>
                        <a type="button" class="btn btn-info" href="add-retailer">Add Retailer</a>
                        <a type="button" class="btn btn-info" href="edit-retailer">Edit Retailer</a>
                    </div>
                </div>
            </div>
        </div><br>

        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-4">
                <div class="text-center card d-flex align-items-center h-100 admin-card" style=" padding: 10px;">
                    <h4>products</h4>
                    <i class="fas fa-file-import" style="font-size: 75px; margin-top: 10px;"></i>
                    <div class="card-body">
                    <br><h3 class="card-text"><strong>Add products</strong></h3>
                    </div>
                    <div>
                        <a type="button" class="btn btn-info" href="add-item">Add</a>
                        <a type="button" class="btn btn-info" href="add-scrapbook">Add multi images</a>
                    </div>
                </div>
            </div>
            
            <div class="mb-3 col-12 col-sm-6 col-md-4">
                <div class="text-center card d-flex align-items-center h-100 admin-card" style=" padding: 10px;">
                    <h4>products</h4>
                    <i class="fas fa-edit" style="font-size: 75px; margin-top: 10px;"></i>
                    <div class="card-body">
                    <br><h3 class="card-text"><strong>Edit products</strong></h3>
                    </div>
                    <div>
                        <a type="button" class="btn btn-info" href="items">Edit</a>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-12 col-sm-6 col-md-4">
                <div class="text-center card d-flex align-items-center h-100 admin-card" style=" padding: 10px;">
                    <h4>Send Newsletter</h4>
                    <i class="far fa-envelope" style="font-size: 75px; margin-top: 10px;"></i>
                    <div class="card-body">
                    <br><h3 class="card-text"><strong>Send newsletter to subscribers</strong></h3>
                    </div>
                    <div>
                        <a type="button" class="btn btn-info" href="newsletter">Compose</a>
                        <a type="button" class="btn btn-info" href="composed-newsletters">Newsletters</a>
                    </div>
                </div>
            </div>
            
        </div><br>

        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-4">
                <div class="text-center card d-flex align-items-center h-100 admin-card" style=" padding: 10px;">
                    <h4>Promo code</h4>
                    <i class="fas fa-percentage" style="font-size: 75px; margin-top: 10px;"></i>
                    <div class="card-body">
                    <br><h3 class="card-text"><strong>Add a promo code</strong></h3>
                    </div>
                    <div>
                        <a type="button" class="btn btn-info" href="add-promo">Add</a>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-12 col-sm-6 col-md-4">
                <div class="text-center card d-flex align-items-center h-100 admin-card" style=" padding: 10px;">
                    <h4>Promo code</h4>
                    <i class="fas fa-edit" style="font-size: 75px; margin-top: 10px;"></i>
                    <div class="card-body">
                    <br><h3 class="card-text"><strong>Edit promo codes</strong></h3>
                    </div>
                    <div>
                        <a type="button" class="btn btn-info" href="edit-promos">Edit</a>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-12 col-sm-6 col-md-4">
                <div class="text-center card d-flex align-items-center h-100 admin-card" style=" padding: 10px;">
                    <h4>Retailer Requests</h4>
                    <i class="fas fa-store" style="font-size: 75px; margin-top: 10px;"></i>
                    <div class="card-body">
                    <br><h3 class="card-text"><strong>View retailer applications</strong></h3>
                    </div>
                    <div>
                        <a type="button" class="btn btn-info" href="retail-applications">View</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-4">
                <div class="text-center card d-flex align-items-center h-100 admin-card" style=" padding: 10px;">
                    <h4>Design Team</h4>
                    <i class="fas fa-paint-brush" style="font-size: 75px; margin-top: 10px;"></i>
                    <div class="card-body">
                    <br><h3 class="card-text"><strong>Manage Designers</strong></h3>
                    </div>
                    <div>
                        <a type="button" class="btn btn-info" href="add-designer">Add</a>
                    </div>
                </div>
            </div>
        </div>
    </div><br>
    
        
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
                        <div class="form-group col-md-6">
                            <label style="color: #000;">GA Tax rate(0.05 = 5%)</label>
                            <input type="text" id="amount" class="form-control">
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
        
<script>
$(document).ready(function(){

// add the values to input fields
$(document).on('click','a[data-role=update]',function(){
    var id = $(this).data('id');
    var amount = $('#'+id).children('td[data-target=amount]').text();

    $('#amount').val(amount);
    $('#item-id').val(id);
    $('#myModal').modal('toggle');

});

//get data and update db
$('#save').click(function(){
    var id = $('#item-id').val();
    var amount = $('#amount').val();
    $.ajax({
        url     : 'update-tax',
        method  : 'post',
        data    : {amount:amount, id:id},
        success : function(response){
                    $('#'+id).children('td[data-target=amount]').text(amount);
                    $('#myModal').modal('toggle');
        }
    });

});
});
</script>
<?php
    }
    else
        {
            
            echo "<br><div class='container alert alert-danger margin-top'><strong>You do not have permission to access this page</strong></div>";
        }

    include '../includes/footer.php';
?>