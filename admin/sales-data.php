
<?php
require_once '../includes/header.php';
require_once 'functions.php';
require_once 'filter-sales-data.php';

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
                    *
                FROM
                    delivery
                WHERE delivery.paid_by != 'NULL'
                AND delivery.dispatched = 1
                ORDER BY date DESC
                ";
            $statement = $db->prepare($sql);
            $statement->execute();
            $result = $statement->fetchAll();
            $output = "";  
            $array_tax = ""; 
            $tax = ""; 
            $array_total = "";  
            $total = "";     
            foreach($result as $row)
                {
                    if($row['county'] == 'Georgia')
                        {
                            $row['county'] = 'GA';
                        }
                    else
                        {
                            $row['county'] = $row['county'];
                        }
                    $total_paid = $row['sum_total'];
                    $ga_tax = $row['ga_tax'];
                    $tax_total = $total_paid * $ga_tax;
                    $tax = number_format($tax_total,2);
                    
                    if($total_paid > 0.00 && $total < 10.00)
                        {
                            $array_total .= "000".number_format($tax_total,2)."";
                        }
                    if($total_paid > 10.00 && $total < 100.00) 
                        {
                            $array_total .= "00".number_format($tax_total,2)."";
                        }
                    if($total_paid > 100.00 && $total < 1000)
                        {
                            $array_total .= "0".number_format($tax_total,2)."";
                        }
                    if($total_paid > 1000)
                        {
                            $array_total .= number_format($tax_total,2);
                        }

                    if($tax > 0.00 && $tax < 10.00)
                        {
                            $array_tax .= "00".number_format($tax_total,2)."";
                        }
                    if($tax > 10.00 && $tax < 100.00) 
                        {
                            $array_tax .= "0".number_format($tax_total,2)."";
                        }
                    if($tax > 100.00)
                        {
                            $array_tax .= number_format($tax_total,2);
                        }
                    $date = date('Y-n-j', strtotime($row['date']));
                    $output .= "<tr>
                                    <td>".$row['town']."</td>
                                    <td>".$row['county']."</td>
                                    <td>".$row['country']."</td>
                                    <td>".$date."</td>
                                    <td>".$tax."</td>
                                    <td>".$total_paid."</td>
                                </tr>";
                } 
                $tt = str_split($array_total,8);
                print_r($tt); 
                $total = array_sum($tt);

                $array = str_split($array_tax,6);
                //print_r($array); 
                $gt = array_sum($array);
                //echo $gt;
                     
            
                
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
            <a class="btn btn-info" href="dispatched-orders">View dispatched orders</a>
            <a class="btn btn-info" href="abandoned">View abandoned orders</a>
        </div>
        
    </div>
    <br>
    <h3>Filter orders by date</h3>
    
    <div class="row">
        <div class="col-md-3">  
            <input type="text" name="data_from_date" id="data_from_date" class="form-control" placeholder="From Date" />  
        </div>  
        <div class="col-md-3">  
            <input type="text" name="data_to_date" id="data_to_date" class="form-control" placeholder="To Date" />  
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
            <input type="hidden" name="data_country" id="data_country" />
        </div>
        
    </div><br>
    <h3>Filter GA orders</h3>
    <div class="row mb-3">
        <div class="col-md-3">
            <input type="date" name="date" id="date" class="form-control">
        </div>
        <div class="col-md-3">
            <button class="btn btn-info btn-block" id="data_county" name="data_county" value="GA">View GA orders</button>
        </div>
    </div>
    <?php $now = date("Y-m-d H:i:s") ?>
    <input type="submit" class="btn btn-info" name="print_submit" form="pdf-form" value="Print PDF"/>
    <button class="btn btn-info" id="download-taxcsv">Export To CSV <i class="fas fa-file-csv"></i></button>
    
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
                                    <th class="th-sticky">Town/City</th>
                                    <th class="th-sticky">State</th>
                                    <th class="th-sticky">Country</th>
                                    <th class="th-sticky">Date</th>
                                    <th class="th-sticky">Tax</th>
                                    <th class="th-sticky">Total</th>
                                </tr>
                            </thead>
                            <?php echo $output; ?>
                            <td></td><td></td><td></td><td></td><td><?=$gt?></td><td><?=$total?></td>
                        </table>
                        </form>
                    </div>
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
        $('#hide-col-tax').click(function(){
            $('td:nth-child(1),th:nth-child(1)').toggleClass('hide');
            $('td:nth-child(2),th:nth-child(2)').toggleClass('hide');
            $('td:nth-child(3),th:nth-child(3)').toggleClass('hide');
            $('td:nth-child(4),th:nth-child(4)').toggleClass('hide');
            $('td:nth-child(5),th:nth-child(5)').toggleClass('hide');
            $('td:nth-child(6),th:nth-child(6)').toggleClass('hide');
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
    $('#download-taxcsv').on('click', function(){
        $('#orders-table').TableCSVExport({
            delivery: 'download',
            filename: '<?=$now?>_sales.csv'
        });
    });
</script>
 <script>  
      $(document).ready(function(){  
           $.datepicker.setDefaults({  
                dateFormat: 'yy-mm-dd'   
           });  
           $(function(){  
                $("#data_from_date").datepicker();  
                $("#data_to_date").datepicker();  
           });  
           $('#filter').click(function(){  
                var data_from_date = $('#data_from_date').val();  
                var data_to_date = $('#data_to_date').val();  
                if(data_from_date != '' && data_to_date != '')  
                {  
                     $.ajax({  
                          url: 'filter-sales-data',  
                          method: 'POST',  
                          data:{data_from_date:data_from_date, data_to_date:data_to_date},  
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
           function load_data(data_query='')
           {
               $.ajax({
                   url:"filter-sales-data",
                   method:"POST",
                   data:{data_query:data_query},
                   success:function(data)
                   {
                        $('#order_table_country').html(data); 
                   }
               })
           }
           $('#multi_search_filter').on('change', function(){
                $('#data_country').val($('#multi_search_filter').val());
                var data_query = $('#data_country').val();
                load_data(data_query);
           });
       });

       $(document).ready(function(){
           $('#data_county').on('click', function(){
               var data_county = $('#data_county').val();
               var date = $('#date').val();
            $.ajax({
                   url:"filter-sales-data",
                   method:"POST",
                   data:{data_county:data_county, date:date},
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