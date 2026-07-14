<?php
include '../includes/header.php';
include '../paypal/sample.php';

if (isset($_POST['submit'])) 
    {
    
        try 
            {
                $response = $gateway->purchase(array(
                    'amount'    => $_POST['amount'],
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

if(isset($_GET['order']))
    {
        $order = $_GET['order'];

        $sql1 = "SELECT 
                    delivery.name,
                    delivery.email,
                    delivery.add_one,
                    delivery.add_two,
                    delivery.town,
                    delivery.county,
                    delivery.del_price,
                    delivery.post_code,
                    delivery.contact_no,
                    delivery.order_no
                FROM 
                    delivery
                WHERE
                    status = 0
                AND delivery.order_no = '$order'
                ";
        $statement = $db->prepare($sql1);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $name = "";
        $add_one = "";
        $add_two = "";
        $company = "";
        $town = "";
        $county = "";
        $post_code = "";
        $contact_no = "";
        $email = "";
        $delivery = "";
        foreach($result as $row)
            {
                $delivery .= $row['del_price'];
                $add_one    .= $row['add_one'];

                        if($row['add_two'] != "")
                            {
                                $add_two .= $row['add_two'];
                            }
                        else
                            {
                                $add_two .= "";
                            }
                        
                        if(isset($row['company']))
                            {
                                $company    .= $row['company'];
                            }
                        else
                            {
                                $company = "";
                            }
                        $town       .= $row['town'];
                        $county     .= $row['county'];
                        $post_code  .= $row['post_code'];
                        $contact_no .= $row['contact_no'];
                        $name       .= $row['cust_name'];
                        $email      .= $row['cust_email'];
            }
            
        $order_no = array($order);
        $sql = "SELECT 
                    orders.order_no,
                    orders.description,
                    orders.sku,
                    orders.price,
                    orders.qty
                FROM 
                    orders
                WHERE
                    orders.status = 0
                ";
        if(!empty($order_no)) 
            {
                $order = implode('","', $order_no);
                $sql .= ' AND orders.order_no IN ("'.$order.'")';
            }
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $total_row = $statement->rowCount();
        $table = "";
        
        if ($total_row > 0)
            { 
                $total = 0;
                foreach($result as $row)
                    {
                        $cost = $row['qty'] * $row['price'];
                        $table .= "<tr>
                                        <td>".$row['description']."</td>
                                        <td>".$row['sku']."</td>
                                        <td><strong>".$row['qty']." </strong>($".$row['price']." per item)</td>
                                        <td>$".$cost."</td>
                                        <td><a href='delete?order_no=".$row['order_no']."&sku=".$row['sku']."'data-toggle='tooltip' data-placement='bottom' title='Remove item' type='submit' name='delete'><i class='fa fa-trash' style='color: #fe0400;'></a></td>
                                        <input type='hidden' name='order_no[]' value='".$row['order_no']."'/>
                                    </tr>";

                        $total = $total + ($row['qty'] * $row['price']);
                        $_SESSION['order_no'] = $row['order_no'];
                    }
            }
    }
    echo $_SESSION['order_no'];
?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Payment</h1>
  </div>
</div>
<div class="container margin-top">
    <div class="row">
        
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
        <table class="table-resposive table-bordered">
                <tr>
                    <th width="30%">Item</th>

                    <th width="30%">Product ID</th>
                    <th width="30%">Quantity</th>
                    <th width="30%">Price</th>
                    <th width="30%">Delete</th>
                </tr>
                <?php echo $table; ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount">Delivery: <strong>$<?php echo $delivery; ?></strong></td>
                    <?php if(isset($total)){$total_price = $total + $delivery;} ?>
                    <td class="amount">Total: <strong>$<?php echo $total_price; ?></strong></td>
                </tr>
        </table><br>
        </div>
    </div>
    <div class="row">
        
        <div class="col-md-6">
            <h4>Delivery and contact details</h4>
                Name: <?php echo $name ?><br>
                Company: <?php echo $company ?><br>
                Street: <?php echo $add_one ?><br>
                <?php echo $add_two ?>
                Town: <?php echo $town ?><br>
                County: <?php echo $county ?><br>
                Post code: <?php echo $post_code ?><br>
                Contact number: <?php echo $contact_no ?><br>
                Email: <?php echo $email ?><br>
        </div>
        <div class="col-md-6">
            <h2>Order No:<?php echo $row['order_no']; ?></h2>
            <h4>Pay with credit/debit card</h4>
            <form action="../includes/charge" method="post" id="payment-form">
                <div class="form-row">
                    <input type="text" name="fname" class="form-control mb-3 StripeElemenet stripeElement--empty" placeholder="First name">
                    <input type="text" name="lname" class="form-control mb-3 StripeElemenet stripeElement--empty" placeholder="Last name">
                    <input type="email" name="email" class="form-control mb-3 StripeElemenet stripeElement--empty" value="<?php echo $email ?>">
                    <p>For testing type 42 continuosly for card no, exp, cvc and zip. </p>
                    <input type="hidden" name="order_number" value="<?php echo $row['order_no']; ?>">
                    <input type="hidden" name="amount" value="<?php echo $total_price; ?>">
                    <div id="card-element" class="form-control">
                    <!-- A Stripe Element will be inserted here. -->
                    </div>

                    <!-- Used to display Element errors. -->
                    <div id="card-errors" role="alert"></div>
                </div>
                <br>
                <button class="btn btn-info" style="min-width: 100%">Submit Payment</button>
            </form><br>
            <hr>
            <h4>Pay with PayPal</h4><br>
            <form action="" method="post">
                <input type="hidden" name="item_name" value="<?php echo $row['order_no']; ?>">
                <input type="hidden" name="order_number" value="<?php echo $row['order_no']; ?>">
                <input type="hidden" name="amount" value="<?php echo $total_price; ?>">
                <button type="submit" name="submit">
                    <img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-large.png" alt="submit"/>
                </button>
                
            </form>
        </div>
    </div>
    

  <!--<div id="paypal-button-container"></div>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
            // This function sets up the details of the transaction, including the amount and line item details.
            return actions.order.create({
                purchase_units: [{
                amount: {
                    value: '<?php echo $total_price; ?>'
                }
                }]
            });
            },
            onApprove: function(data, actions) {
            // This function captures the funds from the transaction.
            return actions.order.capture().then(function(details) {
                // This function shows a transaction success message to your buyer.
                alert('Transaction completed by ' + details.payer.name.given_name);
            });
            }
        }).render('#paypal-button-container');
        //This function displays Smart Payment Buttons on your web page.
    </script>-->
</div>

</div>
<script src="https://js.stripe.com/v3/"></script>
<script src="../js/charge.js"></script>
<?php
include '../includes/footer.php';

?>