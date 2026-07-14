<?php
include ('../includes/connect.php');
include ('../includes/functions.php');

date_default_timezone_set('Etc/Greenwich');

if(isset($_POST['proceed']))  
    {
        $count = count($_POST['sku']);
        for($i=0; $i < $count; $i++)
            {    
                $order_no       = $_POST['order_no'][$i];
                $description    = $_POST['description'][$i];
                $sku            = $_POST['sku'][$i];
                $price          = $_POST['price'][$i];
                $qty            = $_POST['qty'][$i];
                
                $ip             = $_SERVER['REMOTE_ADDR'];
                $date           = date("Y-m-d H:i:s"); 

                $sql = "INSERT INTO 
                            orders (order_no, description, sku, price, qty, ip, date)
                        VALUES
                            (:order_no, :description, :sku, :price, :qty, :ip, :date)
                        ";

                $statement = $db->prepare($sql);
                
                $statement->bindParam(':order_no'	    ,	$order_no       , PDO::PARAM_STR);
                $statement->bindParam(':description'	,	$description    , PDO::PARAM_STR);
                $statement->bindParam(':sku'	        ,	$sku            , PDO::PARAM_STR);
                $statement->bindParam(':price'	        ,	$price          , PDO::PARAM_STR);
                $statement->bindParam(':qty'	        ,	$qty            , PDO::PARAM_INT);
                
                $statement->bindParam(':ip'	            ,	$ip             , PDO::PARAM_STR);
                $statement->bindParam(':date'           ,	$date           , PDO::PARAM_STR);  
                
                $statement->execute();  
                
                
                        $order_no       = $_POST['order_no'][$i];
                        $cust_name      = trim_array($_POST['cust_name'][$i]);
                        $company        = trim_array($_POST['company'][$i]);
                        $cust_email     = trim_array($_POST['cust_email'][$i]);
                        
                        if ($_POST['basic'] > 0)
                            {
                                $del_price      = $_POST['basic'][$i];
                            }
                        else
                            {
                                $del_price      = $_POST['prem'][$i];
                            }

                        $add_one        = trim_array($_POST['add_one'][$i]);
                        $add_two        = trim_array($_POST['add_two'][$i]);
                        $town           = trim_array($_POST['town'][$i]);
                        $county         = trim_array($_POST['county'][$i]);
                        $post_code      = trim_array($_POST['post_code'][$i]);
                        $contact_no     = trim_array($_POST['contact_no'][$i]);
                        $sql = "INSERT INTO 
                                    delivery (order_no, cust_name, company, cust_email, del_price, add_one, add_two, town, county, post_code, contact_no)
                                VALUES
                                    (:order_no, :cust_name, :company, :cust_email, :del_price, :add_one, :add_two, :town, :county, :post_code, :contact_no)
                                ";
                        $statement = $db->prepare($sql);
                        $statement->bindParam(':order_no'	    ,	$order_no       , PDO::PARAM_STR);
                        $statement->bindParam(':cust_name'	    ,	$cust_name      , PDO::PARAM_STR);
                        $statement->bindParam(':company'	    ,	$company        , PDO::PARAM_STR);
                        $statement->bindParam(':cust_email'	    ,	$cust_email     , PDO::PARAM_STR);
                        $statement->bindParam(':del_price'	    ,	$del_price      , PDO::PARAM_STR);
                        $statement->bindParam(':add_one'	    ,	$add_one        , PDO::PARAM_STR);
                        $statement->bindParam(':add_two'	    ,	$add_two        , PDO::PARAM_STR);
                        $statement->bindParam(':town'	        ,	$town           , PDO::PARAM_STR);
                        $statement->bindParam(':county'	        ,	$county         , PDO::PARAM_STR);
                        $statement->bindParam(':post_code'	    ,	$post_code      , PDO::PARAM_STR);
                        $statement->bindParam(':contact_no'	    ,	$contact_no     , PDO::PARAM_STR);
                        $statement->execute();

                        header("location: payment?order=".$order_no."");
            }

             
    }
    
    $db = null;
?>