<?php
$sm_share = "";
include '../includes/header.php';

if(isset($_GET['order']))
    {
        $order_no = $_GET['order'];
        /*$sql = "SELECT id, order_no, paid_by FROM delivery WHERE order_no = '$order_no'";
                $statement = $db->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll();
                $total_row = $statement ->rowCount();
                if($total_row > 0)
                    {
                        foreach($result as $row)
                            {
                                $paid_by = $row['paid_by'];
                            }
                    }
                
        if($paid_by == 'null')
            //{

            }*/
        $sql = "SELECT
                    orders.id,
                    orders.order_no,
                    orders.description,
                    orders.qty,
                    orders.price,
                    orders.size,
                (SELECT  SUM(orders.qty) 
                FROM
                    orders
                WHERE
                    orders.order_no = '$order_no') AS qty_count 
                FROM
                    orders
                WHERE
                    orders.order_no = '$order_no'
                GROUP BY orders.id
                ";
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $total_row = $statement->rowCount();
        $output = "";
        $total_price = 0;
        
        foreach($result as $row)
            {
                $qty = $row['qty_count'];
                $retail_discount = "";
                $size = $row['size'];
                $description = $row['description'];
                
                if($qty > 49 && $qty < 250 && user_retailer() && $size != 'Scrapbook')
                    {
                        $total_price = $total_price + ($row["qty"] * $row["price"] * '.5');
                        $retail_discount = "You have received 50% off your rice paper!";
                    }
                elseif($qty >= 250 && $qty < 500 && user_retailer() && $size != 'Scrapbook')
                    {
                        $total_price = $total_price + ($row["qty"] * $row["price"] * '.48');
                        $retail_discount = "You have received 52% off your rice paper!";
                    }
                elseif($qty > 499 && user_retailer() && $size != 'Scrapbook')
                    {
                        $total_price = $total_price + ($row["qty"] * $row["price"] * '.46');
                        $retail_discount = "You have received 54% off your rice paper!";
                    }
                else
                    {
                        $total_price = $total_price + ($row["qty"] * $row["price"]);
                    }
                
                $output .= "<tr>
                                <td>".$description."</td>
                                <td>".$size."</td>
                                <td>".$row["qty"]."</td>
                                <td>$ ".$row["price"]."</td>
                                <td>$ ".number_format($row["qty"] * $row["price"], 2)."</td>
                                <td><button id='del_".$row['id']."' data-toggle='tooltip' class='btn btn-danger delete-item-order' data-placement='bottom' title='Remove item' type='submit' name='delete'><i class='fa fa-trash' style='color: #fff;'></button></td>

                            </tr>";
                           
                $total_price;
                $total = number_format($total_price, 2);
                
                $_SESSION['order_no'] = $row['order_no'];
                $_SESSION['product_total'] = $total;
            }

        $sql2 = "SELECT
                        *
                FROM
                    delivery
                WHERE
                    order_no = '$order_no'
                ";
        $statement = $db->prepare($sql2);
        $statement->execute();
        $result = $statement->fetchAll();
        $total_row = $statement->rowCount();
        if($total_row > 0)
            {
                foreach($result as $row)
                    {
                        $order_num = $row['order_no'];
                        $full_name = $row['name'];
                        $email = $row['email'];
                        $del = $row['del_price'];
                        $add_1 = $row['add_one'];
                        $add_2 = $row['add_two'];
                        $country = $row['country'];
                        $town = $row['town'];
                        $state = $row['county'];
                        $zip = $row['post_code'];
                        $phone = $row['contact_no'];
                        $ga_tax = $row['ga_tax'];
                    }
            }    
    }
else    
    {
        $order_no = "";
    }

if(isset($_POST['promo']))
    {
        $promo = test_input_pw($_POST['promo']);
        $sql = "SELECT 
                    *
                FROM 
                    promo_codes
                WHERE
                    code = '$promo'
                ";
                $statement = $db->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll();
                $expired = "";
                foreach($result as $row)
                    {
                        $code = $row['code'];
                        $type = $row['type'];
                        $amount = $row['off'];
                        $value = $row['value'];

                        if($amount = 'percentage')
                            {
                                $discount = "";
                            }
                    }
    }
?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Shopping Cart</h1>
  </div>
</div>
<div class="container margin-top">
    <div class="row text-center">
        
        <div class="col-12">
        <?php
        if (isset($_GET['delete']))
            {
                if ($_GET['delete'] == 'success')
                    {
                        echo "<div class='container alert alert-success alert-dismissible fade show'>Item deleted<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                      </button></div>";
                    }
            }
        ?>
        <h2 class="my-4">Order no: <?php echo $order_no ?></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-3 mb-3">
            <a class="btn btn-info" href="../decoupage/">Continue shopping</a>
        </div>
    </div>
    <div class="row text-center">
        <div class="col-12">
        <?php
        $disabled = "";
        $duplicates = duplicates($size, $description, $order_no);
        if($duplicates)
            {
                $disabled = "hide";
                echo "<div class='alert alert-danger'>You have duplicate items in your cart. Please remove these before you continue.</div>";
            }
        ?>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div id="accordion">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Your items
                            </button>
                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <table class="table table-striped table-responsive">
                                <tr>  
                                    <th width="40%">Product Name</th>  
                                    <th width="5%">Size</th>
                                    <th width="10%">Quantity</th>  
                                    <th width="15%">Price</th>  
                                    <th width="15%">Total</th>  
                                    <th width="5%">Delete</th>  
                                </tr>
                                <?php echo $output ?>
                                <tr>  
                                    <td colspan="3">Total</td>  
                                    <th></th> 
                                    <!--<th colspan="2">Qty: <?=$qty;?></th>-->
                                    <td>$ <?php echo $total; echo "<br>"; if(isset($retail_discount)){echo $retail_discount;}?></td>
                                    <td></td>  
                                </tr>
                            </table>
                            
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Delivery/Collection details
                        </button>
                    </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            
                            <div class="col-12">
                                <div class="card d-flex align-items-center h-100 admin-card" style="width: 18rem; padding: 10px;">
                                    <h3 class="card-text"><strong>Collection</strong></h3>
                                    <i class="fas fa-hand-holding" style="font-size: 75px; margin-top: 10px;"></i>
                                    <div class="card-body">
                                    <br><h4>Collect in person from our store.</h4>
                                    </div>
                                    <div>
                                        <label class="switch">
                                            <input id="deliver" type="checkbox" class="form-control" name="basic" value="0"/>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            
                                <br>
                                <div class="form-row">
                                    <div class="form-group col-12 col-md-4">
                                        <label for="">Full Name</label>
                                        <input type="text" name="name" id="name" class="form-control" value="<?php if(isset($full_name)){echo $full_name;}else{echo "";} ?>" placeholder="Full name">
                                    </div>
                                    <div class="form-group col-12 col-md-4">
                                        <label for="">Email</label>
                                        <input type="email" name="email" value="<?php if(isset($email)){echo $email;}else{echo "";} ?>" id="email" class="form-control" placeholder="Email address">
                                    </div>
                                    <div class="form-group col-12 col-md-4">
                                        <label for="">Company</label>
                                        <input type="text" name="company" value="<?php if(isset($company)){echo $company;}else{echo "";} ?>" id="company" class="form-control" placeholder="Company">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div  class="form-group col-12 col-md-4">
                                        <label >Country</label>
                                        
                                        <select name="country" id="calc_del" class="form-control country" value="<?php if(isset($country)){echo $country;}else{echo "";} ?>" >
                                            <option name="0">Select country</option>
                                            <?php country_delivery(); ?>
                                        </select>
                                        <script>
                                            $("button").attr("aria-expanded","true");
                                            $("#collapseTwo").addClass('show');
                                            $('#deliver').on('click', function(){
                                                var collect = $('#deliver'),
                                                    val = collect.val();

                                                collect.val(val === "2" ? "0" : "2");
                                                console.log("new value: " + collect.val());
                                                
                                                $("option").attr('name', '0.00');
                                                    
                                                $('#deliver').on('click', function(){
                                                var collect = $('#deliver'),
                                                    val = collect.val();

                                                collect.val(val === "2" ? "0" : "2");
                                                console.log("new value: " + collect.val());
                                                window.location.reload();
                                            
                                            });
                                        });
                                        </script>
                                    </div>
                                    <div  class="form-group col-12 col-md-4">
                                        <label>Address line one</label>
                                        <input type="text" name="add_one" id="add_one" value="<?php if(isset($add_1)){echo $add_1;}else{echo "";} ?>" class="form-control" placeholder="Address line one">
                                    </div>
                                    <div  class="form-group col-12 col-md-4">
                                        <label>Address line two</label>
                                        <input type="text" name="add_two" id="add_two" value="<?php if(isset($add_2)){echo $add_2;}else{echo "";} ?>" class="form-control" placeholder="Address line two">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div  class="form-group col-12 col-md-4">
                                        <label >Town/City</label>
                                        <input type="text" name="town" id="town" value="<?php if(isset($town)){echo $town;}else{echo "";} ?>" class="form-control" placeholder="Town/City" >
                                    </div>
                                    <div class="form-group col-12 col-md-4">
                                        <label for="">State/Region</label>
                                        <input type="text" name="county" id="county" value="<?php if(isset($state)){echo $state;}else{echo "";} ?>" class="form-control" placeholder="State/Region">
                                    </div>
                                    <div  class="form-group col-12 col-md-4">
                                        <label>Zip/Postcode</label>
                                        <input type="text" name="post_code" id="post_code" value="<?php if(isset($zip)){echo $zip;}else{echo "";} ?>" class="form-control" placeholder="Zip/Postcode">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-12 col-md-4">
                                        <label for="">Phone</label>
                                        <input type="text" name="contact_no" id="contact_no" value="<?php if(isset($phone)){echo $phone;}else{echo "";} ?>" class="form-control" placeholder="Phone number">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-12 col-md-4">
                                        <label>Tube mailer upgrade (+$7.00)</label>
                                            <select class="form-control" name="tube_mail" id="tube_mail">
                                                <option id="no">No</option>
                                                <option id="yes">Yes</option>
                                            </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-12 col-md-6">
                                        <label>Delivery price: </label>
                                        $<input style="border: 0;" type="text" name="del_price" id="del_price" value="0.00" readonly/><br>
                                        <label>Total: </label>
                                        $<input style="border: 0;" type="text" name="total_price" id="total_price" value="0.00" readonly/>
                                    </div>
                                </div>
                                <input type="hidden" name="order_no" id="order_no" value="<?php echo $order_no ?>"/>
                                <input type="hidden" name="delivery" id="delivery_price" value=""/>
                                <input type="hidden" name="sum_total" id="sum_total" value="<?=$total?>"/>
                                <input type="button" name="save" class="btn btn-info <?=$disabled?>" value="Confirm delivery/collection details" id="butsave">
                            </div>
                            
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div> 
</div>
<?php
    include '../includes/footer.php';
?>


<script>
$('a').on('click', function(){
    var order_no = $('#order_no').val();
    $.ajax({
            url: "delete-order",
            type: "POST",
            data: {
                order_no: order_no				
            },
            cache: false,
            success: function(dataResult){
                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode==200){
                    $('#fupForm').find('input:text').val('');
                    $("#success").show();
                    $('#success').html('Data added successfully !'); 
                    history.go(-2);				
                }
                else if(dataResult.statusCode==201){
                alert("Error occured !");
                }
                
            }
        });
});


$('select').on('change', function(){
    var optionSelected = $("option:selected").attr('name');
    var hiddenField = $('#del_price');
    hiddenField.val(optionSelected);
    console.log("new value: " + hiddenField.val());
});

$('select').on('change', function() {
    var item_total = <?php echo $total ?>;
    var optionSelected = $("option:selected").attr('name');
    var del = optionSelected;
    var total = (+item_total) + (+del);
    var number = total;
    var rounded = number.toFixed(2);
    var hiddenField = $('#total_price');
    hiddenField.val(rounded);
    console.log("new value: " + hiddenField.val());
    
    var test = del;
    document.getElementById("delivery_price").value = test;
});

$(document).ready(function() {
    $('#butsave').on('click', function() {
    var sum_total = $('#sum_total').val();
    var order_no = $('#order_no').val();
    var tube_mail = $('#tube_mail').val();
    var name = $('#name').val();
    var email = $('#email').val();
    var country = $('.country').val();
    var add_one = $('#add_one').val();
    var add_two = $('#add_two').val();
    var town = $('#town').val();
    var county = $('#county').val();
    var post_code = $('#post_code').val();
    var company = $('#company').val();
    var delivery = $('#del_price').val();
    var contact_no = $('#contact_no').val();
    var del_type = $('#deliver').val();
    
    if(name!="" && email!="" && country!="Select country" && add_one!="" && town!="" && county!="" && post_code!="" && delivery!=""){
        $.ajax({
            url: "insert-delivery",
            type: "POST",
            data: {
                order_no: order_no,
                name: name,
                email: email,
                country: country,
                add_one: add_one,
                add_two: add_two,
                town: town,
                county: county,
                post_code: post_code,
                company: company,
                delivery: delivery,
                tube_mail: tube_mail,
                contact_no: contact_no,
                del_type: del_type,
                sum_total:sum_total				
            },
            cache: false,
            success: function(dataResult){
                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode==200){
                    $('#fupForm').find('input:text').val('');
                    $("#success").show();
                    $('#success').html('Data added successfully !'); 
                    window.location.href = 'payment?order=<?php echo $order_no ?>';					
                }
                else if(dataResult.statusCode==201){
                alert("Error occured !");
                }
                
            }
        });
    }
    else
        {
            alert('Please fill in all fields');
        }
    });
                
});
// href="delete?order_no='.$row['order_no'].'&description='.$description.'&size='.$size.'"
// Delete 
$(document).ready(function(){
$('.delete-item-order').click(function(){
  var el = this;
  var id = this.id;
  var splitid = id.split("_");

  // Delete id
  var delete_item_id = splitid[1];

  // AJAX Request
  var confirmalert = confirm("Are you sure?");
  if (confirmalert == true) {
  $.ajax({
    url: 'delete',
    type: 'POST',
    data: { id:delete_item_id},
    success: function(response){

    if(response)
        {
            // Remove row from HTML Table
            $(el).closest('tr').css('background','#fe0400');
            $(el).closest('tr').fadeOut(800,function(){
            $(this).remove();
            });
            window.location.reload();
        }
    
            }
        });
    }

});

});
</script>