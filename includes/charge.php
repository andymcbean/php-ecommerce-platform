<?php
include 'connect.php';
include 'functions.php';
include 'constants.php';

require_once '../../../vendor/autoload.php';

\Stripe\Stripe::setApiKey(STRIPE_DEV_SECRET);

//Get form data
$fname = test_input_pw($_POST['fname']);
$lname = test_input_pw($_POST['lname']);
$email = test_input_pw($_POST['email']);
$token = test_input_pw($_POST['stripeToken']);
$order_no = $_POST['order_number'];

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

$total = strtr($cost, array('.' => '', ',' => ''));
//echo $total; die();
//Create customer in stripe
$customer = \Stripe\Customer::create(array(
    "email" => $email,
    "source" => $token
));
//Charge customer
$charge = \Stripe\Charge::create(array(
    "amount" => $total,
    "currency" => "usd",
    "description" => $order_no,
    "customer" => $customer->id
));
        $sql = "INSERT INTO 
                    customers (id, fname, email, lname)
                VALUES
                    (:id, :fname, :email, :lname)
                ";

        $statement = $db->prepare($sql);

        $statement->bindParam(':id'	        ,	$customer->id   , PDO::PARAM_STR);
        $statement->bindParam(':fname'	    ,	$fname          , PDO::PARAM_STR);
        $statement->bindParam(':lname'	    ,	$lname          , PDO::PARAM_STR);
        $statement->bindParam(':email'	    ,	$email          , PDO::PARAM_STR);

        try
            {    
                $statement->execute();
            }
        catch(PDOException $e)
            {
                echo $e;
                //$failed = "<div class='alert alert-danger'><strong>There was an issue, please try again</strong></div>";
            }

            $sql = "INSERT INTO 
                        stripe_payments (id, customer_id, product, currency, amount, status)
                    VALUES
                        (:id, :customer_id, :product, :currency, :amount, :status)
                    ";

            $statement = $db->prepare($sql);

            $statement->bindParam(':id'	            ,	$charge->id            , PDO::PARAM_STR);
            $statement->bindParam(':customer_id'	,	$charge->customer       , PDO::PARAM_STR);
            $statement->bindParam(':product'	    ,	$charge->description    , PDO::PARAM_STR);
            $statement->bindParam(':amount'	        ,	$charge->amount         , PDO::PARAM_STR);
            $statement->bindParam(':currency'	    ,	$charge->currency       , PDO::PARAM_STR);
            $statement->bindParam(':status'	        ,	$charge->status         , PDO::PARAM_STR);

            try
                {    
                    $statement->execute();
                }
            catch(PDOException $e)
                {
                    echo $e;
                    //$failed = "<div class='alert alert-danger'><strong>There was an issue, please try again</strong></div>";
                }
//die();
//Re-direct
header('Location: ../new-cart/success?tid='.$charge->id.'&product='.$charge->description)
?>