<?php  
$sm_share = "";
include_once '../includes/header.php';
include_once '../includes/email-functions.php';
include '../paypal/sample.php';

//unset($_SESSION['cart']);

$order_no = $_SESSION['order_no'];

//echo  $order_no;
// Once the transaction has been approved, we need to complete it.
if (array_key_exists('paymentId', $_GET) && array_key_exists('PayerID', $_GET) && ($order_no)) 
    {
        $transaction = $gateway->completePurchase(array(
            'order_number'         => $order_no,
            'payer_id'             => $_GET['PayerID'],
            'transactionReference' => $_GET['paymentId'],
        ));
        $response = $transaction->send();
        
    
        if ($response->isSuccessful()) 
            {
                $paid_by = "PayPal";
                $sql = "UPDATE 
                            delivery
                        SET
                            paid_by=:paid_by
                        WHERE
                            order_no = '$order_no'
                        ";

                $statement = $db->prepare($sql);    
                $statement->bindParam(':paid_by',	$paid_by, PDO::PARAM_STR);
                try
                    {
                        $statement->execute();
                    }
                catch(PDOException $e)
                    {   
                        echo $e;    
                    }  

                    $sql = "SELECT
                                id,
                                order_no,
                                description,
                                qty,
                                size,
                                stock_adjusted,
                                sku
                            FROM
                                orders
                            WHERE
                                order_no = '$order_no'
                            ";
                    $statement = $db->prepare($sql);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    $qty = "";
                    $size = "";
                    $description = "";
                    foreach($result as $row)
                        {
                            $qty = $row['qty'];
                            $size = $row['size'];
                            $description = $row['description'];
                            $adjusted = $row['stock_adjusted'];
                            $new_sku = $row['sku'];
                            $sku = substr($new_sku, 0, -2);

                            $sql = "SELECT
                                    id,
                                    type,
                                    sku,
                                    description,
                                    stock_$size AS stock,
                                    $size
                                FROM
                                    items
                                WHERE
                                    sku = '$sku'
                                AND $size = 'yes'
                                ";
                            //echo $sql;
                            $statement = $db->prepare($sql);
                            $statement->execute();
                            $result = $statement->fetchAll();
                            foreach($result as $row)
                                {
                                    $stock = $row['stock'];
                                    $type = $row['type'];
                                    $item = $description;
                                    $new_stock_a4 = "";
                                    $new_stock_a3 = "";
                                    $new_stock_xl = "";
                                    $new_stock_sc = "";
                                    $new_stock_chip = "";
                                    if($adjusted == 0)
                                        {
                                            if($size == 'A4')
                                                {
                                                    $stock_adjusted = 1;
                                                    $new_stock_a4 = ($stock - $qty);
                                                    new_stock_a4($new_stock_a4, $sku);
                                                    stock_adjusted($stock_adjusted, $order_no);
                                                }
                                            if($size == 'A3')
                                                {
                                                    $stock_adjusted = 1;
                                                    $new_stock_a3 = ($stock - $qty);
                                                    new_stock_a3($new_stock_a3, $sku);
                                                    stock_adjusted($stock_adjusted, $order_no);
                                                }
                                            if($size == 'XL')
                                                {
                                                    $stock_adjusted = 1;
                                                    $new_stock_xl = ($stock - $qty);
                                                    new_stock_xl($new_stock_xl, $sku);
                                                    stock_adjusted($stock_adjusted, $order_no);
                                                }
                                            if($type == 'Scrapbook')
                                                {
                                                    $stock_adjusted = 1;
                                                    $new_stock_sc = ($stock - $qty);
                                                    new_stock_sc($new_stock_sc, $sku);
                                                    stock_adjusted($stock_adjusted, $order_no);
                                                }
                                            if($type == 'Chipboard')
                                                {
                                                    $stock_adjusted = 1;
                                                    $new_stock_chip = ($stock - $qty);
                                                    new_stock_chip($new_stock_chip, $sku);
                                                    stock_adjusted($stock_adjusted, $order_no);
                                                }
                                        }
                                }
                        }

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
                        AND order_no = '$order_no'
                        ";
                $statement = $db->prepare($sql1);
                $statement ->execute();
                $result = $statement ->fetchAll();
                $delivery = "";
                foreach($result as $row)
                    {
                        $delivery .= $row['del_price'];
                    }

                $sql = "SELECT 
                            *
                        FROM 
                            orders
                        WHERE
                            status = 0
                        AND
                            order_no = '$order_no'
                        ";
                $statement = $db->prepare($sql);
                $statement ->execute();
                $result = $statement ->fetchAll();
                $total_row = $statement->rowCount();
                $table = "";
                $total = 0;
                if ($total_row > 0)
                    {
                        foreach($result as $row)
                            {
                                $delivery .= $row['del_price'];
                                $cost = $row['qty'] * $row['price'];
                                $table .= "<tr>
                                                <td>".$row['description']."</td>
                                                <td>".$row['sku']."</td>
                                                <td><strong>".$row['qty']." </strong>(£".$row['price']." per item)</td>
                                                <td>£".$cost."</td>
                                                <input type='hidden' name='order_no[]' value='".$row['order_no']."'/>
                                            </tr>";

                                $total = $total + ($row['qty'] * $row['price']);
                                //echo $delivery;
                            }
                    }

                // The customer has successfully paid.
                $arr_body = $response->getData();
        
                $payment_id = $arr_body['id'];
                $payer_id = $arr_body['payer']['payer_info']['payer_id'];
                $payer_email = $arr_body['payer']['payer_info']['email'];
                $amount = $arr_body['transactions'][0]['amount']['total'];
                $currency = PAYPAL_CURRENCY;
                $payment_status = $arr_body['state'];
        
                // Insert transaction data into the database
                $sql2 = "SELECT 
                            *
                        FROM 
                            payments 
                        WHERE 
                            payment_id = '$payment_id'
                        ";
                $statement = $db->prepare($sql2);
                $statement->execute();
                $result = $statement ->fetchAll();
                $total_row = $statement->rowCount();
        
                if($total_row > 0)
                    {
                        foreach($result as $row) 
                            {
                                $payment_id = $row['payment_id']; 
                                $payment_gross = $row['amount']; 
                                $payment_status = $row['payment_status']; 
                            }
                        
                    }
                else
                    { 
                        // Insert tansaction data into the database 
                        $date = date("Y-m-d H:i:s");
                        $sql3 = "INSERT INTO 
                                        payments(order_no, payment_id, payer_id, payer_email, amount, currency, payment_status, date) 
                                VALUES
                                        (:order_no, :payment_id, :payer_id, :payer_email, :amount, :currency, :payment_status, :date)
                                "; 
                        $statement = $db->prepare($sql3);
                        
                        $statement->bindParam(':order_no'      ,	$order_no       ,     PDO::PARAM_STR);
                        $statement->bindParam(':payment_id'    ,	$payment_id     ,     PDO::PARAM_STR);
                        $statement->bindParam(':payer_id'	   ,	$payer_id       ,     PDO::PARAM_STR);
                        $statement->bindParam(':payer_email'   ,	$payer_email    ,     PDO::PARAM_STR);
                        $statement->bindParam(':amount'	       ,	$amount         ,     PDO::PARAM_STR);
                        $statement->bindParam(':currency'	   ,	$currency       ,     PDO::PARAM_STR);
                        $statement->bindParam(':payment_status',	$payment_status ,     PDO::PARAM_STR);
                        $statement->bindParam(':date'	       ,	$date           ,     PDO::PARAM_STR);

                        try 
                            {
                                $statement->execute();
                            }
                        catch(PDOException $e)
                            {
                                echo $e;
                            }
                        
                    } 
        
                $p_success = "Payment is successful. Your transaction id is: ". $payment_id;
                echo order_received_admin($order_no);
                echo order_received($order_no);
            } 
        else 
            {
                echo $response->getMessage();   
                $failed = 'Transaction is failed';
            }
    } 
else 
    {
        $failed = 'Transaction is failed';
    }
?>

<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Thank you</h1>
  </div>
</div>
<div class="container margin-top">
    <div class="row">
        <div class="col-12">
        <?php 
        echo "Order number: "; echo $order_no;
        echo "<br>";
            if(isset($p_success))
                {
                    echo $p_success; 
                }
            else
                {
                    echo $failed;
                }
              
        ?>
            <br><button class="btn btn-info" id="unset">Back to Products</button>
        </div>
    </div>
</div>
<br><br><br><br><br>
<?php
include_once '../includes/footer.php'; 
unset($_SESSION['shopping_cart']);
unset($_SESSION['order_no']);

?>
<script type="text/javascript">
$( document ).ready(function() {
    $("#unset").on('click',function() {
        
         $.ajax({
            type: "GET",
            url: 'destroy',
            data: {unset:1},
            success:function(data){
                if(data == 'session unset'){
                     window.location.href = '../decoupage/';
                }
               
            }
            });
        });
    });
</script>