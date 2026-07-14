<?php
include ('../includes/connect.php');
include ('../includes/functions.php');

date_default_timezone_set('Etc/Greenwich');

if(isset($_POST['place_order']))  
    {
        $count = count($_POST['product_name']);
        for($i=0; $i < $count; $i++)
            {    
                $order_no       = $_POST['order_no'][$i];
                $img            = $_POST['product_image'][$i];
                $description    = htmlentities($_POST['product_name'][$i]);
                $sku            = $_POST['product_code'][$i];
                $price          = $_POST['product_price'][$i];
                $qty            = $_POST['product_quantity'][$i];
                $size           = $_POST['product_size'][$i];
                $ip             = $_SERVER['REMOTE_ADDR'];
                $date           = date("Y-m-d H:i:s"); 

                $sql = "INSERT INTO 
                            orders (order_no, description, sku, img, size, price, qty, ip, date)
                        VALUES
                            (:order_no, :description, :sku, :img, :size, :price, :qty, :ip, :date)
                        ";

                $statement = $db->prepare($sql);
                
                $statement->bindParam(':order_no'	    ,	$order_no       , PDO::PARAM_STR);
                $statement->bindParam(':description'	,	$description    , PDO::PARAM_STR);
                $statement->bindParam(':sku'	        ,	$sku            , PDO::PARAM_STR);
                $statement->bindParam(':size'	        ,	$size           , PDO::PARAM_STR);
                $statement->bindParam(':price'	        ,	$price          , PDO::PARAM_STR);
                $statement->bindParam(':qty'	        ,	$qty            , PDO::PARAM_INT);
                $statement->bindParam(':img'	        ,	$img            , PDO::PARAM_STR);
                $statement->bindParam(':ip'	            ,	$ip             , PDO::PARAM_STR);
                $statement->bindParam(':date'           ,	$date           , PDO::PARAM_STR);  
                
                $statement->execute();  
                
                header("location: ../new-cart?order=".$order_no."");
            }   
    }
else
    {
        echo "<script>window.history.go(-1)</script>";
    }
$db = null;
?>