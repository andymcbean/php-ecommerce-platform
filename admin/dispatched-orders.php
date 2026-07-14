
<?php
require_once '../includes/header.php';
require_once 'functions.php';
require_once 'filter-dispatched.php';

if(logged_in() AND user_admin())
    {
        $sql = "SELECT DISTINCT
                    country
                FROM
                    delivery
                ORDER BY country ASC
                ";
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $country = "";
        foreach($result as $row)
            {
                $country .= "<option value='".$row['country']."'>".$row['country']."</option>";
            }

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
                    delivery.dispatched,
                    delivery.sum_total,
                    delivery.ga_tax
                FROM
                    delivery
                WHERE delivery.paid_by != 'NULL'
                AND delivery.dispatched = 1
                ORDER BY date DESC
                ";
               // echo $sql; die();
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $output = "";
        $tracking_modal = "";
        foreach($result as $row)
            {    
                $order_no = $row['order_no'];
                $email = $row['email'];
                $name = $row['name'];
                if($row['del_type'] == 2)
                    {
                        $del = "Collection";
                    }
                elseif($row['del_type'] == 0)
                    {
                        $del = "Delivery";
                    }
                if($row['dispatched'] == 0)
                    {
                        $dispatched = "<a style='cursor: pointer;' data-toggle='modal' data-target='#trackModal".$row['id']."'><i class='fas fa-times-circle' style='color: red;'></i><p style='font-size: 10px;'>Add tracking No</p></a>";
                    }
                elseif($row['dispatched'] == 1)
                    {
                        $dispatched = "<i data-toggle='tooltip' data-placement='top' data-original-title='".$row['carrier'].": ".$row['code']."' class='fas fa-check-square' style='color: green;'></i>";
                    }
                if(customer_retailer($email, $name))
                    {
                        $retailer = "<i class='fas fa-check-square' style='color: green;'></i>";
                    }
                else
                    {
                        $retailer = "<i class='fas fa-times-circle' style='color: red;'></i>";
                    }
                if($row['paid_by'] != "")
                    {
                        $paid_by = $row['paid_by'];
                    }
                else    
                    {
                        $paid_by = "<div class='alert alert-warning'>**Payment not complete**</div>";
                    }
                $list_products = list_products($order_no);
                //$csv = ".$row['product']."'.csv'";
                $date = date('Y-n-j', strtotime($row['date']));
                $output .= "<tr>
                                <td><input type='checkbox' name='order_nos[]' class='orders' value='".$order_no."'</td>
                                <td>".$order_no."</td>
                                <td>".$email."</td>
                                <td>".$row['name']."</td>
                                <td>".$row['add_one']."</td>
                                <td>".$row['add_two']."</td>
                                <td>".$row['town']."</td>
                                <td>".$row['county']."</td>
                                <td>".$row['post_code']."</td>
                                <td>".$row['country']."</td>
                                <td>".$del."</td>
                                <td>".$retailer."</td>
                                <td>".$paid_by."</td>
                                <td>".$date."</td>
                                <td>".$dispatched."</td>
                                <td><a class=\"btn btn-info\" href='order-details?order=".$order_no."'><i class=\"fas fa-eye\"></i></a></td>
                                <td><button class='btn btn-danger delete' id='del_".$row['id']."'> <i class='fas fa-trash-alt'></i></button></td>
                                <td class='hide'>".$list_products."</td>
                            </tr>";

                $tracking_modal .= "<div class='modal' id='trackModal".$row['id']."'>
                                        <div class='modal-dialog'>
                                            <div class='modal-content'>

                                            <div class='modal-header'>
                                                <h4 class='modal-title'>Add tracking No</h4>
                                                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                            </div>
                                            <div class='modal-body'>
                                                <form action='add-tracking' method='post'>
                                                    <div class='form-group'>
                                                        <label>Carrier</label>
                                                        <select name='carrier' class='form-control' id='carrier'>
                                                            <option>USPS</option>
                                                            <option>UPS</option>
                                                        </select>
                                                        <br>
                                                        <label>Tracking No:</label>
                                                        <input name='code' type='text' id='code' class='code form-control'>
                                                        <input name='id' type='hidden' id='id' value='".$row['id']."' class='form-control'> 
                                                        <input name='order_no' type='hidden' id='order_no' value='".$order_no."' class='form-control'> 
                                                    </div>
                                            </div>
                                            <div class='modal-footer'>
                                                <button id='add-track' class='btn btn-info float-right'>Update</button>
                                                <button type='button' class='btn btn-danger pull-left' data-dismiss='modal'>Close</button>
                                                </form>
                                            </div>
                                        
                                            </div>
                                        </div>
                                    </div>";
                
            }
                
?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Dispatched Orders</h1>
  </div>
</div>

<div class="container-fluid margin-top">
<div id="result"></div>
    <h3>Search order number</h3>
    <div class="row">
        <div class="col-md-3">
            <form action="order-details" method="POST">
                <span class="input-group-text"><input type="text" class="form-control search-menu" name="search" placeholder="Search oder number">
                <button type="submit" name="submit-search" class="btn btn-info">
                
                    <i class="fa fa-search" aria-hidden="true"></i>
                
                </button></span>
            </form>
        </div>
        <div class="col-md-9">
            <a class="btn btn-info" href="orders">View Paid orders</a>
        
            <a class="btn btn-info" href="sales-data">View sales data</a>
        
            <a class="btn btn-info" href="abandoned">View abandoned orders</a>
        </div>
    </div>
    <br>
    <h3>Filter orders by date</h3>
    
    <div class="row">
        <div class="col-md-3">  
            <input type="text" name="dis_from_date" id="dis_from_date" class="form-control" placeholder="From Date" />  
        </div>  
        <div class="col-md-3">  
            <input type="text" name="dis_to_date" id="dis_to_date" class="form-control" placeholder="To Date" />  
        </div>  
        <div class="col-md-3">  
            <input type="button" name="filter" id="filter" value="Filter" class="btn btn-info" />  
        </div>  
        
    </div><br>
    <h3>View orders by country</h3>
    <div class="row">
        <div class="col-md-3">
            <select name="multi_search_filter" id="multi_search_filter" class="form-control">
                <option value="">Select a country</option>
                <?php echo $country ?>
            </select>
            <input type="hidden" name="hidden_country" id="hidden_country" />
        </div>
        
    </div><br>
    <h3>Filter GA orders</h3>
    <div class="row mb-3">
        <div class="col-md-3">
            <input type="date" name="date" id="date" class="form-control">
        </div>
        <div class="col-md-3">
            <button class="btn btn-info btn-block" id="county" name="county" value="GA">View GA orders</button>
        </div>
    </div>
    <?php $now = date("Y-m-d H:i:s") ?>
    <input type="submit" class="btn btn-info" name="print_submit" form="pdf-form" value="Print PDF"/>
    <button class="btn btn-info" id="downloadcsv">Export To CSV <i class="fas fa-file-csv"></i></button>
    <input type="checkbox" id="hide-col">Hide columns for shipping CSV?
    <div class="row mt-2">
    <div id="response"></div>
        <div class="col-12">
            <div id="order_table_country">
                <div id="order_table_data">
                    <div id="order_table_county">
                        <form id="pdf-form" action="pdf-print" method="post">
                        <table id="orders-table" class="table table-hover table-responsive sticky-header">
                            <thead>
                                <tr>
                                    <th class="th-sticky">Print</th>
                                    <th class="th-sticky">Order No</th>
                                    <th class="th-sticky">Email</th>
                                    <th class="th-sticky">Name</th>
                                    <th class="th-sticky">Address</th>
                                    <th class="th-sticky">Address line 2</th>
                                    <th class="th-sticky">Town/City</th>
                                    <th class="th-sticky">State</th>
                                    <th class="th-sticky">Zip</th>
                                    <th class="th-sticky">Country</th>
                                    <th class="th-sticky">Shipping</th>
                                    <th class="th-sticky">User Retailer</th>
                                    <th class="th-sticky">Paid via</th>
                                    <th class="th-sticky">Date</th>
                                    <th class="th-sticky">Dispatched</th>
                                    <th class="th-sticky">View</th>
                                    <th class="th-sticky">Delete</th>
                                    <th class="th-sticky hide">Products</th>
                                    <th class="th-sticky hide">Tax</th>
                                    <th class="th-sticky hide">Total</th>
                                </tr>
                            </thead>
                            <?php echo $output; ?>
                        </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
            
            
    </div>
    <?php echo $tracking_modal ?>
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
        $('#hide-col').click(function(){
            $('td:nth-child(1),th:nth-child(1)').toggleClass('hide');
            $('td:nth-child(11),th:nth-child(11)').toggleClass('hide');
            $('td:nth-child(12),th:nth-child(12)').toggleClass('hide');
            $('td:nth-child(13),th:nth-child(13)').toggleClass('hide');
            $('td:nth-child(15),th:nth-child(15)').toggleClass('hide');
            $('td:nth-child(16),th:nth-child(16)').toggleClass('hide');
            $('td:nth-child(17),th:nth-child(17)').toggleClass('hide');
            $('td:nth-child(18),th:nth-child(18)').toggleClass('hide');
        });
    });
</script>
<script>     
    $('#downloadcsv').on('click', function(){
        $('#orders-table').TableCSVExport({
            delivery: 'download',
            filename: '<?=$now?>_orders.csv'
        });
    });
</script>

<script>
$(document).on('click',function(){
    var id = $(this).data('id');
    $('#trackModal').modal('toggle');
    
    $('#add-tracking').on('click', function() {
    var carrier = $('#carrier').val();
    var code = $('#code').val();
    var order_no = $('#order_no').val();
    if(code != "")
        {
            $.ajax({
            url: 'add-tracking',
            type: 'POST',
            data: {
                carrier: carrier,
                code: code,
                id: id,
                order_no: order_no
            },
            cache: false,
            success: function(dataResult){
                    alert('Tracking code added and order marked as dispatched');
                    location.reload();	
            
            }
            });
        }
    else    
        {
            alert('Tracking code required');	
        }
        

});
});
</script>
 <script>  
      $(document).ready(function(){  
           $.datepicker.setDefaults({  
                dateFormat: 'yy-mm-dd'   
           });  
           $(function(){  
                $("#dis_from_date").datepicker();  
                $("#dis_to_date").datepicker();  
           });  
           $('#filter').click(function(){  
                var dis_from_date = $('#dis_from_date').val();  
                var dis_to_date = $('#dis_to_date').val();  
                if(dis_from_date != '' && dis_to_date != '')  
                {  
                     $.ajax({  
                          url: 'filter-dispatched',  
                          method: 'POST',  
                          data:{dis_from_date:dis_from_date, dis_to_date:dis_to_date},  
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

       $(document).ready(function(){
           function load_data(query='')
           {
               $.ajax({
                   url:"filter-dispatched",
                   method:"POST",
                   data:{query:query},
                   success:function(data)
                   {
                        $('#order_table_country').html(data); 
                   }
               })
           }
           $('#multi_search_filter').on('change', function(){
                $('#hidden_country').val($('#multi_search_filter').val());
                var query = $('#hidden_country').val();
                load_data(query);
           });
       });

       $(document).ready(function(){
           $('#county').on('click', function(){
               var county = $('#county').val();
               var date = $('#date').val();
            $.ajax({
                   url:"filter-dispatched",
                   method:"POST",
                   data:{county:county, date:date},
                   success:function(response)
                    {
                        $('#order_table_county').html(response); 
                    }
               })
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
    url: 'delete-order',
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