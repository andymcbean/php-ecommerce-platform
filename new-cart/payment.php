<?php
$sm_share = "";
include '../includes/header.php';
include '../includes/constants.php';
require_once '../../../vendor/autoload.php';
 
use Omnipay\Omnipay;
 
$gateway = Omnipay::create('PayPal_Rest');
$gateway->setClientId(PP_CLIENT_DEV_ID);
$gateway->setSecret(PP_CLIENT_DEV_SECRET);
$gateway->setTestMode(true); //set it to 'TRUE' when in development mode

if(isset($_GET['order']))
    {
        $order_no = $_GET['order'];
        $sql = "SELECT
                    *
                FROM
                    delivery
                WHERE
                    order_no = '$order_no'
                ";
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        
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
                $tube_mail = $row['tube_mail'];
                $promo_code = $row['promo'];
                $del_type = $row['del_type'];
                
                if($country == "Select country")
                    {
                        $country = "";
                    }
                if($tube_mail == 'Yes')
                    {
                        $tube = 7;
                        $up_del = "(Includes tube mailer upgrade)";
                    }
                else
                    {
                        $tube = 0;
                        $up_del = "";
                    }
                if($del_type == 2)
                    {
                        $method =  "You are collceting your order";
                        $del = 0;
                    }
                else
                    {
                        $method = "Delivery Address";
                    }
            }  
            
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
                    if($qty > 49 && $qty < 250 && user_retailer() && $row['size'] != 'Scrapbook')
                        {
                            $total_price = $total_price + ($row["qty"] * $row["price"] * '.5');
                            $retail_discount = "You have received 50% off your rice paper!";
                        }
                    elseif($qty >= 250 && $qty < 500 && user_retailer() && $row['size'] != 'Scrapbook')
                        {
                            $total_price = $total_price + ($row["qty"] * $row["price"] * '.48');
                            $retail_discount = "You have received 52% off your rice paper!";
                        }
                    elseif($qty > 499 && user_retailer() && $row['size'] != 'Scrapbook')
                        {
                            $total_price = $total_price + ($row["qty"] * $row["price"] * '.46');
                            $retail_discount = "You have received 54% off your rice paper!";
                        }
                    else
                        {
                            $total_price = $total_price + ($row["qty"] * $row["price"]);
                        }
                        
                    $output .= '<tr>
                                    <td>'.$row["description"].'</td>
                                    <td>'.$row["size"].'</td>
                                    <td>'.$row["qty"].'</td>
                                    <td>$ '.$row["price"].'</td>
                                    <td>$ '.number_format($row["qty"] * $row["price"], 2).'</td>
                                </tr>';
                    $total_price;
                    $total = number_format($total_price, 2);
                    
                    $_SESSION['order_no'] = $row['order_no'];
                    $_SESSION['product_total'] = $total;
                }
    }

    if(isset($_POST['promo_submit']))
        {
            $promo = test_input_pw($_POST['promo']);
            $sql = "SELECT
                        *
                    FROM
                        promo_codes
                    WHERE
                        code = '$promo'
                    AND 
                        status = 1
                    ";
            $statement = $db->prepare($sql);
            $statement->execute();
            $result = $statement->fetchAll();
            $total_row = $statement->rowCount();
            if($total_row > 0)
                {
                    foreach($result as $row)
                        {
                            $code = $row['code'];
                            $type = $row['type'];
                            $value = $row['value'];
                            
                            if($type == 'delivery')
                                {
                                    $del = 0;
                                }
                            else
                                {
                                    $del = $del;
                                }

                            if($type == 'total') 
                                {
                                    if($row['off'] == 'percentage')
                                        {
                                            $dis = ($total_price * $value);
                                            $total_price = ($total_price - $dis);
                                        }
                                    else
                                        {
                                            if ($value >= $total_price)
                                                {
                                                    $total_price = 0;
                                                }
                                            else
                                                {
                                                    $total_price = ($total_price - $value);
                                                }
                                        }
                                    
                                }
                            if($type == 'gift_card')
                                {
                                    if ($value >= $total_price)
                                        {
                                            $total_price = 0;
                                        }
                                    else
                                        {
                                            $total_price = ($total_price - $value);
                                        }
                                    if($code == 'DESIGNTEAM500')
                                        {
                                            $status = 1;
                                        }
                                    else
                                        {
                                            $status = 0;
                                        }
                                    $sql = "UPDATE 
                                                promo_codes
                                            SET
                                                status=:status
                                            WHERE
                                                code = '$code'
                                            ";
                                    $statement = $db->prepare($sql);
                                    $statement->bindParam(':status'	,   $status,   PDO::PARAM_STR);
                                    try
                                        {
                                            $statement->execute();
                                        }
                                    catch(PDOException $e)
                                        {   
                                            echo $e;
                                        } 

                                    //$total_price = ($total_price - $value);
                                    $valid_code = "<div class='alert alert-success'>The code you entered has been applied.</div>";
                                }

                                $sql = "UPDATE 
                                            delivery
                                        SET
                                            promo=:promo
                                        WHERE
                                            order_no = '$order_no'
                                        ";
                                $statement = $db->prepare($sql);
                                $statement->bindParam(':promo'	,   $promo,   PDO::PARAM_STR);
                                try
                                    {
                                        $statement->execute();
                                    }
                                catch(PDOException $e)
                                    {   
                                        echo $e;
                                    }
                        }
                }
            else
                {
                    $no_code = "<div class='alert alert-warning'>The code you entered is not valid or has expired.</div>";
                }
        }

        if (isset($_POST['pp-submit'])) 
            {
                $sql = "SELECT
                            order_no,
                            sum_total
                        FROM
                            delivery
                        WHERE
                            order_no = '$order_no'
                        ";
                $statement = $db->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll();
                foreach($result as $row)
                    {
                        $cost = $row['sum_total'];
                    }
                try 
                    {
                        $response = $gateway->purchase(array(
                            'amount'    => $cost,
                            'order_no'  => $_POST['order_number'],
                            'currency'  => PAYPAL_CURRENCY,
                            'returnUrl' => PAYPAL_RETURN_URL,
                            'cancelUrl' => PAYPAL_CANCEL_URL,
                        ))->send();
                
                        if ($response->isRedirect()) 
                            {
                                $response->redirect(); // this will automatically forward the customer
                            } 
                        else 
                            {
                                // not successful
                                echo $response->getMessage();
                            }
                    } 
                catch(Exception $e) 
                    {
                        echo $e->getMessage();
                    }
            }  
      
?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Payment</h1>
  </div>
</div>
<div class="container margin-top">
    <div class="row">
            <div class="col-12">
                    <table class="table table-striped table-responsive">
                        <tr>  
                            <th width="40%">Product Name</th>  
                            <th width="5%">Size</th>
                            <th width="10%">Quantity</th>  
                            <th width="15%">Price</th>  
                            <th width="15%">Total</th>   
                        </tr>
                        <?php echo $output ?>
                        <tr>  
                            <td colspan="3">Total</td>  
                            <th></th> 
                            <!--<th colspan="2">Qty: <?=$row['qty_count'];?></th>-->
                            <td>$ <?php echo $total; echo "<br>"; if(isset($retail_discount)){echo $retail_discount;}?></td>
                              
                        </tr>
                    </table>
            </div>
        </div>
    <div class="row">
        <div class="col-12">
            <?php 
                if(isset($no_code))
                    {
                        echo $no_code;
                    }
                if(isset($valid_code))
                    {
                        echo $valid_code;
                    }
            ?>
        </div>
        <div class="col-12 col-md-4">
            <div class="card d-flex admin-card" style="padding: 10px;">
            <label><?php echo $method ?></label>
            <p><?php echo $full_name ?></p>
            <p><?php echo $add_1 ?></p>
            <p><?php echo $add_2 ?></p>
            <p><?php echo $town ?></p>
            <p><?php echo $state ?></p>
            <p><?php echo $country ?></p>
            <p>Product total: $<?php echo number_format($total_price,2) ?>
            <p>Delivery: $<?php echo number_format($del + $tube,2); echo "<br>"; echo $up_del; ?>
            <?php 
                if($ga_tax != 0)
                    {
                        if(user_retailer())
                            {
                                $ga_tax = 0;
                                $sql = "UPDATE 
                                            delivery
                                        SET
                                            ga_tax=:ga_tax
                                        WHERE
                                            order_no = '$order_no'
                                        ";

                                $statement = $db->prepare($sql);
                                $statement->bindParam(':ga_tax'	,	$ga_tax, PDO::PARAM_STR);
                                try
                                    {
                                        $statement->execute();
                                    }
                                catch(PDOException $e)
                                    {
                                        echo $e;
                                    }
                            }
                        $tax_unit = ($ga_tax * ($total_price + $del + $tube));
                        echo "<p>GA Tax: $".number_format($tax_unit,2)."</p>";
                    }
                else
                    {
                        $tax_unit = 0;
                        echo "";
                    }
                    $sum = ($tax_unit + $del + $total_price + $tube);
                    $sum_total = number_format($sum,2);
                    echo "<p>Total: $".$sum_total."</p>";

                    $sql = "UPDATE 
                                delivery
                            SET
                                sum_total=:sum_total
                            WHERE
                                order_no = '$order_no'
                            ";

                    $statement = $db->prepare($sql);
                    $statement->bindParam(':sum_total'	,	$sum_total, PDO::PARAM_STR);
                    try
                        {
                            $statement->execute();
                        }
                    catch(PDOException $e)
                        {
                            echo $e;
                        }

                    if($sum_total == '0.00')
                        {
                            $zero_pay = "<a href='free-purchase?order=".$order_no."' class='btn btn-info btn-block'>Nothing to pay</a>";
                            $hide_pp = 'hide';
                            $hide_stripe = 'hide';
                        }
                    else
                        {
                            $zero_pay = "";
                            $hide_pp = "";
                            $hide_stripe = "";
                        }
            ?>
            </div>
        </div>
        <?php
            if(isset($type))
                {
                    $hide = 'hide';
                }
            else
                {
                    $hide = "";
                }
        ?>
        <div class="col-12 col-md-4 <?php echo $hide ?>">
            <div class="card d-flex align-items-center admin-card" style="padding: 10px;">
                
                <form class="" action="" method="post">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="">Promo code</label>
                            <input type="text" name="promo" id="promo" class="form-control" placeholder="Promo code" required><br>
                            <button type="submit" name="promo_submit" class="btn btn-info">Apply code</button>
                        </div>
                    </div>
                </form>
            </div>
            <?php
                $checked = ""; 
                $subscribed = "";
                if(logged_in_fb() || logged_in() || logged_in_google())
                    {
                        if(user_subscriber())
                            {
                                $value = 1;
                                $checked .= "checked";
                                $subscribed .= "You are subscribed";
                            }
                        else
                            {
                                $value = 0;
                                $checked .= "";
                                $subscribed .= "You are not subscribed";
                            }
                    }
                elseif(non_user_subscriber($email))
                    {
                        $value = 1;
                        $checked .= "checked";
                        $subscribed .= "You are subscribed";
                    }
                else
                    {
                        $value = 0;
                        $checked .= "";
                        $subscribed .= "You are not subscribed";
                    }
            ?>
            <div class="text-center card d-flex align-items-center admin-card mb-5 mt-3" style="padding: 10px;">
                <h3 class="card-text"><strong>Subscribe</strong></h3>
                <i class="far fa-paper-plane" style="font-size: 75px; margin-top: 10px;"></i>
                <div class="card-body">
                <br><h4>Subscribe to our newsletter</h4>
                </div>
                <div>
                    <label class="switch">
                    <input class="form-control" type="checkbox" name="subscribe" id="sub" value="<?=$value?>" <?=$checked?>>
                        <span class="slider round"></span>
                    </label>
                    <input type="hidden" name="email" id="email" value="<?php echo $email ?>">
                    <input type="hidden" name="name" id="name" value="<?php echo $full_name ?>">
                    <p><?=$subscribed?></p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <?=$zero_pay?>
            <div class="card d-flex align-items-center admin-card mx-auto" style="padding: 10px;">
                <form action="../includes/charge" method="post" id="payment-form" class="mb-3 <?=$hide_stripe?>">
                    <div class="form-row">
                        <input id="s-fname" type="text" name="fname" class="form-control mb-3 StripeElemenet stripeElement--empty" placeholder="First name" required>
                        <input id="s-lname" type="text" name="lname" class="form-control mb-3 StripeElemenet stripeElement--empty" placeholder="Last name" required>
                        <input id="s-email" type="email" name="email" class="form-control mb-3 StripeElemenet stripeElement--empty" placeholder="Email address" required>
                        <div id="card-element" class="form-control card">
                    </div>
                    <div id="card-errors" role="alert"></div>
                        <input type="hidden" name="order_number" value="<?php echo $order_no ?>">
                        <input type="hidden" name="amount" id="final_price" value="<?php echo number_format($sum_total,2) ?>" />
                    </div>
                    <br>
                    
                    <button id="stripe" class="btn btn-info" style="min-width: 100%"> Submit Payment</button>
                    <div id="alert" class="alert alert-info hide">Your payment is being processed. If you are not redirected in 30 seconds, please refresh this page and try again.</div>
                    <img src="../images/stripe.png" class="img-fluid align-items-center" style="max-width: 70%;">
                    
                </form>
            </div>
            <hr>
            <div class="card d-flex align-items-center admin-card" style="padding: 10px;">
                <form action="" method="post" class="mt-3 <?=$hide_pp?>">
                    <input type="hidden" name="item_name" value="<?php echo $order_no ?>">
                    <input type="hidden" name="order_number" value="<?php echo $order_no ?>">
                    <input type="hidden" name="amount" value="<?php echo number_format($sum_total,2) ?>">
                    <button type="submit" name="pp-submit" class="pp-btn">
                        <img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-large.png" alt="submit"/>
                    </button>
                </form>
            </div>
        </div>     
    </div>
</div><br>
<script src="https://js.stripe.com/v3/"></script>
<script src="../js/charge.js"></script>
<script>
    $('#stripe').on('click', function() {
        var fname = $('#s-fname').val();
        var sname = $('#s-lname').val();  
        var semail = $('#s-email').val();
        if(fname != "" && sname != "" && semail != "")
            {
                $('#stripe').addClass("hide");
                $('#alert').removeClass("hide");
            }
        
    });

$(document).ready(function() {
    $('#sub').on('click', function() {
    var subscribe = $('#sub').val();
    var email = $('#email').val();
    var name = $('#name').val();
    if(subscribe == 0)
        {
            $.ajax({
            url: "../includes/add-sub",
            type: "POST",
            data: {
                subscribe: subscribe,
                email: email,
                name: name			
            },
            cache: false,
            success: function(dataResult){
                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode==200){
                    alert("You are subscribed to our newsletter!");	
                    window.location.reload();			
                }
                else if(dataResult.statusCode==201){
                alert("Error occured !");
                }  
            
            }
        });  
        }
    else
        {
            $.ajax({
                url: "../includes/delete-sub",
                type: "POST",
                data: {
                    subscribe: subscribe,
                    email: email,
                    name: name			
                },
                cache: false,
                success: function(dataResult){
                    var dataResult = JSON.parse(dataResult);
                    if(dataResult.statusCode==200){
                        alert("Unsubscribed");	
                        window.location.reload();			
                    }
                    else if(dataResult.statusCode==201){
                    alert("Error occured !");
                    }  
                
                }
            });
        }
                 
    });
});
</script>
<?php
include '../includes/footer.php';
?>