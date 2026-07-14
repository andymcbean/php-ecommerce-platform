<?php
$sm_share = "";
include '../includes/header.php';
include '../includes/constants.php';

if(logged_in() || logged_in_fb() || logged_in_google())
    {
        if(isset($_POST['submit-search']))
            {
                $this_order = test_input_pw($_POST['search']);
            }
        elseif(isset($_GET['order']))
            {
                $this_order = $_GET['order'];
            }
            $sql = "SELECT
                        order_no,
                        description,
                        sku,
                        qty,
                        size,
                        img,
                        price
                    FROM
                        orders
                    WHERE  order_no = '$this_order'
                    ";
            $statement = $db->prepare($sql);
            $statement->execute();
            $result = $statement->fetchAll();
            $products = "";
            $t_products = "";
            $img = "";
            $table_products = "";
            $item_sku = "";
            $pop_cart = "";
            foreach($result as $row)
                {
                    $_SESSION['order_no'] = $row['order_no'];
                    $sku = $row['sku'];
                    $size = $row['size'];
                    if(user_retailer())
                        {
                            $retailer  = "yes";
                        }
                    else
                        {
                            $retailer = "no";
                        }
                    $prod_qty = product_qty($sku, $size);
                    $total = ($row['price'] * $row['qty']);
                    $desc = $row['description'];
                    $item_sku = $sku;
                    //$img .= "<img src='".IMAGE_URL."".$row['img']."' class='img-fluid mb-2' style='max-width:25%;'><br>";
                    $products .= "<td>".$desc." - ".$item_sku." (Quantity - ".$row['qty'].") (".$size.")</td>";
                    $t_products .= "<li>".$desc." - ".$item_sku." (Quantity - ".$row['qty'].")(".$size.")</li>";
                    //$item_total .= $row['qty'] * 

                    $table_products .= "<tr>
                                        <td><img src='".IMAGE_URL."".$row['img']."' class='img-fluid' style='max-width:100px;'></td>
                                        <td><strong>".$desc."<br>".$item_sku."</strong></td>
                                        <td>Size: ".$size." | </td>
                                        <td>$".$row['price']." | </td>
                                        <td>x".$row['qty']." | </td>
                                        <td>$".number_format($total,2)."</td>
                                        </tr>
                                        ";
                    $pop_cart .= "<input type='hidden' name='hidden_image[]' id='image' value='".$row['img']."' />
                                    <input type='hidden' id='in_stock' name='in_stock[]' value='".$prod_qty."'> 
                                    <input type='hidden' name='quantity[]' value='".$row['qty']."' id='quantity' readonly/>
                                    <input type='hidden' name='hidden_name[]' id='name' value='".$row['description']."' />
                                    <input type='hidden' name='hidden_size[]' value='".$size."' id='size' /></span>
                                    <input type='hidden' name='hidden_price[]' id='price' value='".$row['price']."' />
                                    <input type='hidden' name='sku[]' value='".$sku."' id='sku' />
                                    <input type='hidden' name='is_retailer[]' value='".$retailer."' id='is_retailer'  />";
                }

        $table_items = "";
        $tax = "";
        $tax_header = "";
        $total_price = "";
        $line_2 = "";
        $td_line_2 = "";
        $td_co_line = "";
        $co_line = "";

        $sql = "SELECT
                    delivery.order_no,
                    delivery.name,
                    delivery.email,
                    delivery.del_price,
                    delivery.add_one,
                    delivery.add_two,
                    delivery.country,
                    delivery.town,
                    delivery.county,
                    delivery.del_type,
                    delivery.post_code,
                    delivery.ga_tax,
                    delivery.company,
                    delivery.promo,
                    delivery.contact_no,
                    delivery.date,
                    delivery.dispatched,
                    delivery.sum_total
                FROM
                    delivery
                WHERE  delivery.order_no = '$this_order'
                ";
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        foreach($result as $row)
            {
                $created = $row['date'];
                $name = $row['name'];
                $email = $row['email'];
                $total_price = $row['sum_total'];
                $address = $row['add_one'];
                $add_2 = $row['add_two'];
                $country = $row['country'];
                $state = $row['county'];
                $town = $row['town'];
                $zip = $row['post_code'];
                $phone = $row['contact_no'];
                $order_no = $row['order_no'];
                $ga_tax = $row['ga_tax'];
                $dispatched = $row['dispatched'];
                if($row['del_type'] == 2)
                    {
                        $del_price = "Collection";
                    }
                elseif($row['del_type'] == 0)
                    {
                        $del_price = $row['del_price'];
                    }
                $company = $row['company'];
                $promo = "";
                $date = date('Y-n-j', strtotime($created));
                $print_tax = "";
                //$total_price = sprintf('%.2f', $price / 100);
                if($row['promo']  == '0')
                    {
                        $promo = "N/A";
                    }
                else
                    {
                       $promo = $row['promo'];
                    }
                if($ga_tax > 0)
                    {
                        $tax_unit = $ga_tax * $total_price;
                        //echo $ga_tax;
                        $tax .= "<td>$".number_format($tax_unit,2)."</td>";
                        $print_tax .= number_format($tax_unit,2);
                        $tax_header .= "<th>GA Tax</th>";
                    }
                else
                    {
                        $tax .= "0.00";
                        $print_tax .= "0.00";
                    }
                if($add_2)
                    {
                        $line_2 .= "<th>Line 2<th>";
                        $td_line_2 .= "<td>".$add_2."</td>";
                    }
                else
                    {
                        $line_2 = "";
                        $td_line_2 = "";
                    }
                if($company)
                    {
                        $co_line .= "<th>Company<th>";
                        $td_co_line .= "<td>".$company."</td>";
                    }
                else
                    {
                        $co_line = "";
                        $td_co_line = "";
                    }
                
                $table_items .= "<tr>
                                    <td>".$email."</td>
                                    <td>".$name."</td>
                                    $td_co_line
                                    <td>".$address."</td>
                                    $td_line_2
                                    <td>".$town."</td>
                                    <td>".$state."</td>
                                    <td>".$country."</td>
                                    <td>".$zip."</td>
                                    <td class='hide'>".$order_no."</td>
                                    <td>".$date."</td>
                                    <td>$".$del_price."</td>
                                    $tax
                                    <td>$".$total_price."</td>
                                    $products
                                </tr>";
            }
            
$sql = "SELECT
            *
        FROM
            payments
        WHERE
            order_no = '$this_order'
        "; 
$statement = $db->prepare($sql);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();
$paypal_table = "";
if($total_row > 0)
    {
        foreach($result as $row)
            {
                $row['payment_id'];
                $row['payer_email'];
                $row['payment_status'];
                $row['amount'];
                $row['date'];

                $paypal_table .= "<p>Payment method: Pay Pal<p>
                                    <p style='margin-top: -15px;'>Payment ID: ".$row['payment_id']."</p>
                                    <p style='margin-top: -15px;'>Payer email: ".$row['payer_email']."</p>
                                    <p style='margin-top: -15px;'>Staus: ".$row['payment_status']."</p>
                                    <p style='margin-top: -15px;'>Total paid: ".$row['amount']."</p>
                                    <p style='margin-top: -15px;'>Date: ".$row['date']."</p>";
            }
    }
else
    {
        $paypal_table = "";
    }

$sql = "SELECT
            *
        FROM
            stripe_payments
        WHERE
            product = '$this_order'
        ";
$statement = $db->prepare($sql);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();
$stripe_table = "";
if($total_row != 0)
    {
        foreach($result as $row)
            {
                $total_price = sprintf('%.2f', $row['amount'] / 100);
                
                $stripe_table .= "<p>Payment method: Credit/Debit card<p>
                                    <p style='margin-top: -15px;'>Payment ID: ".$row['id']."</p>
                                    <p style='margin-top: -15px;'>Payer email: ".$row['customer_id']."</p>
                                    <p style='margin-top: -15px;'>Staus: ".$row['status']."</p>
                                    <p style='margin-top: -15px;'>Total paid: $".$total_price." ".$row['currency']."</p>
                                    <p style='margin-top: -15px;'>Date: ".$row['created_at']."</p>";
            }
    }
else
    {
        $stripe_table = "";
    }
    
?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Order Details</h1>
  </div>
</div>
<div class="container margin-top">
    <div class="row text-center" id="no-print">
        <div class="col-12">
            <a class="btn btn-info float-left mb-3" href="../users/my-orders"><i class="fas fa-chevron-circle-left"></i> Back to orders</a>
        </div>
    </div>
    <div id="print">
        <div class="row">
            <div class="col-12 col-sm-6">
                <h2>Order #<?php echo $order_no; ?></h2>
                <p><?php echo $name ?> | <?php echo $email ?></p>
            </div>
            <div class="col-12 col-sm-6">
                <h4>Order placed: <?php echo $created ?></h4>
            </div>
        </div>
        <div class="row">
            <table class="table table-responsive table-striped">
                <?php echo $table_products; ?>
                <?php echo "<tr><td></td><td></td><td></td><td></td><td>Tax:</td><td> $"; if(isset($print_tax)){echo $print_tax;}else{echo "";} echo"</td></tr>" ?>
                <?php echo "<tr><td></td><td></td><td></td><td></td><td>Shipping:</td><td> $"; echo $del_price; echo "</td></tr>" ?>
                <?php echo "<tr><td></td><td></td><td></td><td></td><td>Total:</td><td> $"; echo $total_price; echo "</td></tr>" ?>
            </table>
            
        </div>
        <div class="row">
            <div class="col-12 col-sm-6">
                <h2>Customer Info</h2>
                <p><?php echo $name ?></p>
                <p><?php echo $address; echo "<br>"; echo $add_2; echo $town; echo ", "; echo $state; echo ", "; echo $country; echo ", "; echo $zip; ?></p>
                
                <h2>Payment info</h2>
                <?php
                if(isset($paypal_table))
                    {
                        echo $paypal_table;
                    }
                else
                    {
                        echo "";
                    }
                if(isset($stripe_table))
                    {
                        echo $stripe_table;
                    }
                else
                    {
                        echo "";
                    }
                ?>
            </div>
            <div class="col-12 col-sm-6">
                <h2>Billing Address</h2>
                <p><?php echo $name ?></p>
                <p><?php echo $address; echo "<br>"; echo $add_2; echo $town; echo ", "; echo $state; echo ", "; echo $country; echo ", "; echo $zip; ?></p>
                <?php
                if($paypal_table != "")
                    {
                        echo "<p>Paid by PayPal</p>";
                    }
                else
                    {
                        echo "";
                    }
                if($stripe_table != "")
                    {
                        echo "<p>Paid by Credit/Debit card</p>";
                    }
                else
                    {
                        echo "";
                    }
                ?>
            </div>
        </div>
    </div>

    <div class="row mx-auto">
        <table id="orders_table" class="orders table table-striped table-responsive">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Name</th>
                    <?php echo $co_line ?>
                    <th>Address</th>
                    <?php echo $line_2 ?>
                    <th>Town/City</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>Zip</th>
                    <th>Date</th>
                    <th>Delivery paid</th>
                    <?php echo $tax_header ?>
                    <th>Amount paid</th>
                    <th>Product Items</th>
                </tr>
            </thead>
            <?php echo $table_items; ?>
        </table>
        <button class="btn btn-info mb-4" onclick="exportTableToCSV('<?php echo $order_no ?>.csv')">Export To CSV <i class="fas fa-file-csv"></i></button>
        <button class="btn btn-info mb-4 ml-2" onclick="window.print();">Print order</button>
        <?php
        if($dispatched == 0)
            {
                echo "<div class='alert alert-warning'>Awaiting Dispatch</div>";
            }
        elseif($dispatched == 1)
            {
                echo "<div class='alert alert-success'>This order has been dispatched</div>";
            }
            echo $pop_cart;
        ?>
        <button class="btn btn-info mb-4 ml-2 re_add_to_cart" onclick="complete_order();">Complete this order?</button>
        <input type="hidden" value="<?php echo $order_no; ?>" id="order_no">
    </div>
    <div class="row hideprint">
        <div class="col-12 col-md-4">
            <h3>Customer</h3>
            <?php
                if(user_retailer() != FALSE)
                    {
                        echo "<p>This user is a registered retailer</p>";
                    }
            ?>
            <p>Name: <?php echo $name ?></p>
            <p>Email: <?php echo $email ?></p>
            <p>Address: <?php echo "<br>"; echo $address; echo "<br>"; echo $add_2; echo "<br>"; echo $town; echo "<br>"; echo $state; echo "<br>"; echo $country; echo "<br>"; echo $zip; ?></p>
            <p>Phone: <?php echo $phone ?></p>
        </div>
        <div class="col-12 col-md-4">
            <h3>Payment</h3>
            <p>Total paid: $<?=$total_price ?></p>
            <p>GA Tax: <?=$tax ?></p>
            <p>Delivery: $<?=$del_price ?></p>
            <p>Promo used: <?=$promo?></p>
        </div>
        <div class="col-12 col-md-4">
            <h3>Products</h3>
            <?php echo $img ?>
            <ul>
                <?php echo $t_products ?>
            </ul>
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