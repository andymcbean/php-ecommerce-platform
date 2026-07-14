<?php
require_once '../includes/header.php';

if(logged_in() || logged_in_fb() || logged_in_google())
    {
        $email = "";
        $email = global_logged_in($email);
        //echo $email;
        $sql = "SELECT
                    delivery.id,
                    delivery.order_no,
                    delivery.name,
                    delivery.email,
                    delivery.del_price,
                    delivery.del_type,
                    delivery.paid_by,
                    delivery.add_one,
                    delivery.add_two,
                    delivery.country,
                    delivery.town,
                    delivery.county,
                    delivery.post_code,
                    delivery.date,
                    delivery.code,
                    delivery.carrier,
                    delivery.dispatched
                FROM
                    delivery
                WHERE
                    delivery.email = '$email'
                ORDER BY delivery.date DESC
                ";
                //echo $sql;
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $output = "";
        foreach($result as $row)
            {
                if($row['dispatched'] == 0)
                    {
                        $dispatched = "<i data-toggle='tooltip' data-placement='top' data-original-title='Not yet dispatched' class='fas fa-times' style='color: red;'></i>";
                    }
                elseif($row['dispatched'] == 1)
                    {
                        $dispatched = "<i data-toggle='tooltip' data-placement='top' data-original-title='".$row['carrier'].": ".$row['code']."' class='fas fa-check-square' style='color: green;'></i>";
                    }
                if($row['paid_by'] != "")
                    {
                        $paid_by = $row['paid_by'];
                    }
                else    
                    {
                        $paid_by = "<div class='alert alert-warning'>**Payment not complete** <br><a href='../new-cart/?order=".$row['order_no']."'>Complete order now</a></div>";
                    }  
                //$csv = ".$row['product']."'.csv'";
                $date = date('Y-n-j', strtotime($row['date']));
                $output .= "<tr>
                                <td>".$row['order_no']."</td>
                                <td>".$row['email']."</td>
                                <td>".$row['name']."</td>
                                <td>".$row['add_one']."</td>
                                <td>".$row['add_two']."</td>
                                <td>".$row['town']."</td>
                                <td>".$row['county']."</td>
                                <td>".$row['post_code']."</td>
                                <td>".$row['country']."</td>
                                <td>".$paid_by."</td>
                                <td>".$date."</td>
                                <td>".$dispatched."</td>
                                <td><a class=\"btn btn-info\" href='order-details?order=".$row['order_no']."'><i class=\"fas fa-eye\"></i></a></td>
                            </tr>";
            }
                
?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Your Orders</h1>
  </div>
</div>
<div class="container-fluid margin-top">
    <div class="row">
        <div class="col-12">
            <div id="order_table_country">
                <div id="order_table_data">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Order No</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Address line 2</th>
                                <th>Town/City</th>
                                <th>State</th>
                                <th>Zip</th>
                                <th>Country</th>
                                
                                <th>Paid via</th>
                                <th>Date</th>
                                <th>Dispatched</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <?php echo $output; ?>
                    </table>
                </div>
            </div>
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

<script>  
    $(document).ready(function(){  
        $.datepicker.setDefaults({  
            dateFormat: 'yy-mm-dd'   
        });  
        $(function(){  
            $("#from_date").datepicker();  
            $("#to_date").datepicker();  
        });  
        $('#filter').click(function(){  
            var from_date = $('#from_date').val();  
            var to_date = $('#to_date').val();  
            if(from_date != '' && to_date != '')  
            {  
                    $.ajax({  
                        url: 'filter',  
                        method: 'POST',  
                        data:{from_date:from_date, to_date:to_date},  
                        success:function(data)  
                        {  
                            $('#order_table_data').html(data);  
                        }  
                    });  
            }  
            else  
            {  
                    alert("Please Select Date");  
            }  
        });  
    }); 

//delete items
$(document).ready(function(){

// Delete 
$('.delete').click(function(){
  var el = this;
  var id = this.id;
  var splitid = id.split("_");

  // Delete id
  var deleteid = splitid[1];

  // AJAX Request
  var confirmalert = confirm("Are you sure?");
  if (confirmalert == true) {
  $.ajax({
    url: 'delete-myorder',
    type: 'POST',
    data: { id:deleteid },
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