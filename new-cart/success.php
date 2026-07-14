<?php
$sm_share = "";
include '../includes/header.php';
include '../includes/email-functions.php';


if(isset($_GET['product']) && isset($_GET['tid']))
    {
        $paid_by = "Stripe";
        $order_no  = $_GET['product'];
        $tid  = $_GET['tid'];
        echo order_received_admin($order_no);
        echo order_received($order_no);

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
                    //echo $sku;
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
    }
else
    {
        header('location: ../');
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
            <h2>Thank you for your purchase.</h2>
            <p>Order number: <?php echo $order_no ?></p>
            <p>Transaction ID id: <?php echo $tid ?></p>
            <a href="../decoupage/" class="btn btn-info mb-3">Back to Products</a>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
unset($_SESSION['shopping_cart']);
unset($_SESSION['order_no']);
?>