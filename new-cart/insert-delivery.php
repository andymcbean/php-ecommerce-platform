<?php
include '../includes/connect.php';
include '../includes/functions.php';

$sql = "SELECT
            amount
        FROM
            sales_tax
        ";
$statement = $db->prepare($sql);
$statement->execute();
$result = $statement->fetchAll();
foreach($result as $row)
    {
        $tax = $row['amount'];
    }

$order_no = $_POST['order_no'];
$del_price = $_POST['delivery'];
$name = test_input_pw($_POST['name']);
$email = test_input_pw($_POST['email']);
$country = test_input_pw($_POST['country']);
$add_one = test_input_pw($_POST['add_one']);
$add_two = test_input_pw($_POST['add_two']);
$town = test_input_pw($_POST['town']);
$county = test_input_pw($_POST['county']);
$tube_mail = test_input_pw($_POST['tube_mail']);
$del_type = test_input_pw($_POST['del_type']);
$sum_total = test_input_pw($_POST['sum_total']);
// && user_retailer()
if($_POST['county'] == 'GA' || $_POST['county'] == 'Georgia')
    {
        $ga_tax = $tax;
    }
else
    {
        $ga_tax = 0;
    }
$post_code = test_input_pw($_POST['post_code']);
$contact_no = test_input_pw($_POST['contact_no']);

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
$total_row = $statement->rowCount();

if($total_row > 0)
    {
        $sql = "UPDATE
                    delivery
                SET
                    order_no=:order_no, sum_total=:sum_total, del_price=:del_price, name=:name, email=:email, country=:country, add_one=:add_one, add_two=:add_two, town=:town, county=:county, contact_no=:contact_no, post_code=:post_code, ga_tax=:ga_tax, tube_mail=:tube_mail, del_type=:del_type
                WHERE
                    order_no = '$order_no'
                ";
        $statement = $db->prepare($sql);

        $statement->bindParam(':order_no'	    ,	$order_no   , PDO::PARAM_STR);
        $statement->bindParam(':del_price'	    ,	$del_price  , PDO::PARAM_STR);
        $statement->bindParam(':name'	        ,	$name       , PDO::PARAM_STR);
        $statement->bindParam(':email'	        ,	$email      , PDO::PARAM_STR);
        $statement->bindParam(':country'	    ,	$country    , PDO::PARAM_STR);
        $statement->bindParam(':add_one'      	,	$add_one    , PDO::PARAM_STR);
        $statement->bindParam(':add_two'      	,	$add_two    , PDO::PARAM_STR);
        $statement->bindParam(':town'      	    ,	$town       , PDO::PARAM_STR);
        $statement->bindParam(':county'      	,	$county     , PDO::PARAM_STR);
        $statement->bindParam(':ga_tax'         ,	$ga_tax     , PDO::PARAM_STR);
        $statement->bindParam(':contact_no'     ,	$contact_no , PDO::PARAM_STR);
        $statement->bindParam(':post_code'      ,	$post_code  , PDO::PARAM_STR);
        $statement->bindParam(':tube_mail'      ,	$tube_mail  , PDO::PARAM_STR);
        $statement->bindParam(':del_type'       ,	$del_type   , PDO::PARAM_STR);
        $statement->bindParam(':sum_total'      ,	$sum_total  , PDO::PARAM_STR);
        try 
            {
                $statement->execute();
                echo json_encode(array("statusCode"=>200));
            }
        catch(PDOException $e)
            {
                echo json_encode(array("statusCode"=>201));
            }
    }
else
    {
        $sql = "INSERT INTO 
                        delivery (order_no, sum_total, del_price, name, email, country, add_one, add_two, town, county, contact_no, post_code, ga_tax, tube_mail, del_type)
                VALUES
                    (:order_no, :sum_total, :del_price, :name, :email, :country, :add_one, :add_two, :town, :county, :contact_no, :post_code, :ga_tax, :tube_mail, :del_type)
                ";

                $statement = $db->prepare($sql);

                $statement->bindParam(':order_no'	    ,	$order_no   , PDO::PARAM_STR);
                $statement->bindParam(':del_price'	    ,	$del_price  , PDO::PARAM_STR);
                $statement->bindParam(':name'	        ,	$name       , PDO::PARAM_STR);
                $statement->bindParam(':email'	        ,	$email      , PDO::PARAM_STR);
                $statement->bindParam(':country'	    ,	$country    , PDO::PARAM_STR);
                $statement->bindParam(':add_one'      	,	$add_one    , PDO::PARAM_STR);
                $statement->bindParam(':add_two'      	,	$add_two    , PDO::PARAM_STR);
                $statement->bindParam(':town'      	    ,	$town       , PDO::PARAM_STR);
                $statement->bindParam(':county'      	,	$county     , PDO::PARAM_STR);
                $statement->bindParam(':ga_tax'         ,	$ga_tax     , PDO::PARAM_STR);
                $statement->bindParam(':contact_no'     ,	$contact_no , PDO::PARAM_STR);
                $statement->bindParam(':post_code'      ,	$post_code  , PDO::PARAM_STR);
                $statement->bindParam(':tube_mail'      ,	$tube_mail  , PDO::PARAM_STR);
                $statement->bindParam(':del_type'       ,	$del_type   , PDO::PARAM_STR);
                $statement->bindParam(':sum_total'      ,	$sum_total  , PDO::PARAM_STR);
                try 
                    {
                        $statement->execute();
                        echo json_encode(array("statusCode"=>200));
                    }
                catch(PDOException $e)
                    {
                        echo json_encode(array("statusCode"=>201));
                    }
    }
$db = null;
?>        


