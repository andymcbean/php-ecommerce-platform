<?php
include '../includes/header.php';
$total_price = "";
//add units
if(isset($_POST['add']))
    {   
        if($_POST['hidden_price'] == 0)
            {
                echo "<br><div class='container alert alert-danger'>You must select a size first to add this item to your cart <button onclick='window.history.go(-1); return false;' class='btn btn-danger'><i class='fa fa-chevron-left'></i> Go back</button></div>";
            }
        else
            {
                if(isset($_SESSION['cart']))
                    {
                        $item_array_sku = array_column($_SESSION['cart'], 'sku');
                        if($item_array_sku)
                            {
                                $count = count($_SESSION['cart']);
                                $item_array = array(
                                    'sku' => $_GET['sku'].$_POST['hidden_sku'],
                                    'description' => $_POST['hidden_description'],
                                    'price' => $_POST['hidden_price'],
                                    'quantity' => $_POST['quantity'],
                                    'size' => $_POST['hidden_size'],
                                );
                                $_SESSION['cart'][$count] = $item_array;
                                echo "<script>alert('Item added to cart')</script>";
                                echo "<script>window.history.go(-1)</script>";
                            }
                        else
                            {
                                $item_array = array(
                                    'sku' => $_GET['sku'].$_POST['hidden_sku'],
                                    'description' => $_POST['hidden_description'],
                                    'price' => $_POST['hidden_price'],
                                    'quantity' => $_POST['quantity'],
                                    'size' => $_POST['hidden_size'],
                                );
                                $_SESSION['cart'][0] = $item_array;
                                echo "<script>window.history.go(-1)</script>";
                            }    
                    }
                else
                    {
                        $item_array = array(
                            'sku' => $_GET['sku'].$_POST['hidden_sku'],
                            'description' => $_POST['hidden_description'],
                            'price' => $_POST['hidden_price'],
                            'quantity' => $_POST['quantity'],
                            'size' => $_POST['hidden_size'],
                        );
                        $_SESSION['cart'][0] = $item_array;
                    }
            }
        
    }
// remove units
    if(isset($_GET['action']))
        {
            if($_GET['action'] == 'delete')
                {
                    foreach($_SESSION['cart'] as $keys => $value)
                        {
                            if($value['sku'] == $_GET['sku'])
                                {
                                    unset($_SESSION['cart'][$keys]);
                                    echo "<script>alert('Item removed from cart')</script>";
                                    echo "<script>window.location='cart'</script>";
                                }
                        }
                }
        }

if(isset($_SESSION['email']))
    {
        $sql = "SELECT
                    name,
                    email,
                    add_one,
                    add_two,
                    add_three,
                    town,
                    county,
                    post_code,
                    phone
                FROM
                    users
                WHERE
                    email = '".$_SESSION['email']."'
                ";
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $register = "";
        foreach($result as $row)
            {
                $name = $row['name'];
                $email = $row['email'];
                $add_one = $row['add_one'];
                $add_two = $row['add_two'];
                $add_three = $row['add_three'];
                $town = $row['town'];
                $county = $row['county'];
                $post_code = $row['post_code'];
                $phone = $row['phone'];
            }
    }
else    
    {
        $register = "";
        $register .= "<div class='col-12'>
                        <p><span>Register with us?
                        <input id='reg' type='checkbox' name='reg'/></span></p>
                    </div>";
    }
?>

<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Shopping Cart</h1>
  </div>
</div>
<div class="container margin-top">
    <div class="row">
        <div class="col-12">
            <form action="insert-order" method="post">
            <table class="table-resposive table-bordered">
                <tr>
                    <th width="30%">Name</th>
                    <th width="30%">Qty</th>
                    <th width="30%">Price</th>
                    <th width="30%">Total</th>
                    <th width="30%">Remove item</th>
                </tr>
            
            <?php
                $order_no = "";
                if (isset($_SESSION['order_no']))
                    {
                        $order_no = $_SESSION['order_no'];
                    }
                else    
                    {
                        $order_no = "DQ".time();
                    }

                    //echo $order_no;
                if (!empty($_SESSION['cart']))
                    {
                        $total = 0;
                        foreach($_SESSION['cart'] as $k => $v)
                            {
                                $sku = $v['sku'];
                                ?>
                                <tr>
                                    <td><?php echo $v['description']; ?> (<?php echo $v['size'] ?>)</td>
                                    <td><?php echo $v['quantity']; ?></td>
                                    <td>$<?php echo $v['price']; ?></td>
                                    <td>$<?php echo number_format($v['quantity'] * $v['price'], 2) ?></td>

                                    <td><a href='cart?action=delete&sku=<?php echo $sku ?>'data-toggle="tooltip" data-placement="bottom" title="Remove item"><i class="fa fa-trash" style="color: #fe0400;"></a></td>
                                                
                                </tr>
                                <?php
                                    $total = $total + ($v['quantity'] * $v['price'])
                                ?>
                                <input type="hidden" name="sku[]" value="<?php echo $v['sku']; ?>">
                                <input type="hidden" name="description[]" value="<?php echo $v['description']; ?>">
                                <input type="hidden" name="qty[]" value="<?php echo $v['quantity']; ?>">
                                <input type="hidden" name="price[]" value="<?php echo $v['price']; ?>">
                                <input type="hidden" name="order_no[]" value="<?php echo $order_no; ?>">
                                <?php  
                            }                          
                    }
                else
                    {
                        echo "<div class='container alert alert-warning'>Your cart is empty! <a href='../decoupage/' class='btn btn-warning'><i class='fa fa-chevron-left'></i> Continue shopping</a></div>";
                        //include '../includes/footer.php';
                        die();
                        
                    }
                    ?>
                <tr>
                    <td>Total</td>
                    <th>$<?php echo number_format($total, 2) ?></th>
                    <td></td>
                </tr>
                
            </table><br>
            <div class="row">
                <div class="col-12">
                    <a href="../decoupage/" class="btn btn-info"><i class="fa fa-chevron-left"></i> Continue shopping</a>
                    <button class="btn btn-info float-right" id="show-del">Proceed to delivery <i class="fa fa-truck"></i></button><br><br>
                    <button class="btn btn-info float-right" id="unset">Clear cart <i class="fa fa-trash"></i></button>
                </div>
                
            </div><br>
            <div class="hide" id="hide-del">
            <div class="navbar navbar-dark navbar-expand-md" style="margin-top: 15px;">
                <div class="container">
            
                    <h1 class="h1-details mx-auto">DELIVERY</h1><br>
                </div>
                <?php
                    if (isset($_GET['error']))
                        {
                            if ($_GET['error'] == 'name')
                                {
                                    echo "<div class='container alert alert-danger'>Name required</div>";
                                }
                        }
                ?>
            </div><br>
            <div class="form-row">
            <div class="form-group col-md-6">
                <label>Name</label>
                <input name="name[]" type="text" class="form-control" placeholder="Full name" required="">
            </div>
            <div class="form-group col-md-6">
                <label>Company</label>
                <input name="company[]" type="text" class="form-control" placeholder="company">
            </div>
        </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Email</label>
                    <input name="cust_email[]" type="email" class="form-control" placeholder="Email" required="">
                </div>
                <div class="form-group col-md-6">
                    <label>Contact</label>
                    <input name="contact_no[]" type="text" class="form-control" placeholder="Contact number" required="">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Dleivery Address</label>
                        <!-- Postcode field -->
                        <div style="padding: 5px; border: none;" class="form-control" id="postcode_lookup">
                        </div>  
                
                    <br>
                    <!-- Add to your existing form -->
                    <label>First Address Line</label>
                    <input name="add_one[]" class="form-control" id="line1" type="text" required=""> 
                
                    <label>Second Address Line</label>
                    <input name="add_two[]" class="form-control" id="line2" type="text">   
                
                    <label>Third Address Line</label>
                    <input name="add_three[]" class="form-control" id="line3" type="text">  
                
                    <label>Town</label>
                    <input name="town[]" class="form-control" id="town" type="text" required="">
                
                    <label>State/County</label>
                    <input name="county[]" class="form-control" id="county" type="text" required="">
                
                    <label>Zip/Post code</label>
                    <input name="post_code[]" class="form-control" id="postcode" type="text" required="">

                    <input name="add_one[]" type="hidden"> 
                    <input name="add_two[]" type="hidden">   
                    <input name="add_three[]" type="hidden">  
                    <input name="town[]" type="hidden">
                    <input name="county[]" type="hidden">
                    <input name="post_code[]" type="hidden">
                    <input name="name[]" type="hidden">
                    <input name="company[]" type="hidden">
                    <input name="cust_email[]" type="hidden">
                    <input name="contact_no[]" type="hidden">
                
                </div>
            </div>
            <br>

            <div class="row">
                          
                    <div class="col-12 col-md-6">
                        <div class="card admin-card">
                            <div class="card-body">
                                <p>USPS (Domestic)<span style="font-weight: bold;"> $7.95</span></p>
                                <input id="prem" type="checkbox" class="form-control" name="prem[]" value="0"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card admin-card">
                            <div class="card-body">
                            <p>International Delivery<span style="font-weight: bold;"> $14.00</span></p>
                                <input id="basic" type="checkbox" class="form-control" name="basic[]" value="0"/>
                            </div>
                        </div>
                    </div><br>
                    <div class="col-12">
                        <h3 style="float: right;" class="amount-text">Your final price is <span class="amount"><strong>$<span class="amount" value=""></span></strong></h3><br><br>
                   
                        <button style="float: right;" type="submit" class="btn btn-info" name="proceed">Proceed to payment <i class="fa fa-chevron-right"></i></button><br><br>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';

?>

<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});

$("#show-del").click(function(){
  $("#hide-del").removeClass("hide");
});

//DELIVERY
$('#basic').on('click', function() {
    var hiddenField = $('#basic'),
        val = hiddenField.val();

    hiddenField.val(val === "14.00" ? "0" : "14.00");
    console.log("new value: " + hiddenField.val());
});

$('#prem').on('click', function() {
    var hiddenField = $('#prem'),
        val = hiddenField.val();

    hiddenField.val(val === "7.95" ? "0" : "7.95");
    console.log("new value: " + hiddenField.val());
});

$(':checkbox').change(function(){
    var sum = <?php echo number_format($total, 2); ?>;
    var names = $(':checked').map(function(){
        sum += (this.value - 0);
        return this.name;
    }).get().join(',');
    var spans = $('span.amount');
    spans[1].innerHTML = sum;
});
</script>