<?php
$sm_share = "";
include '../includes/connect.php';
include 'functions.php';
include '../includes/constants.php';

require_once '../../../vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(
    ['tempDir' => '/tmp']
);
  
//$post_order = array($_POST['order_no']);
$post_order = array('DQ1617564610','DQ1617565734', 'DQ1617647505');
$this_order = implode(",",$post_order);
//$this_order = 'DQ1617564610';
$date = date("Y-m-d H:i:s");
$table_items = "";
$tax = "";
$tax_header = "";
$total_price = "";
$line_2 = "";
$td_line_2 = "";
$td_co_line = "";
$co_line = "";
$data = "";

foreach($post_order as $item)
    {
        $sql = "SELECT
                    orders.order_no,
                    orders.description,
                    orders.sku,
                    orders.qty,
                    orders.size,
                    orders.img,
                    orders.price,
                    orders.o_status
                FROM
                    orders
                WHERE
                    order_no = '$item'
                LIMIT  0,1
                ";
                //echo $sql;
                $statement = $db->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll();
                $products = "";
                $t_products = "";
                $img = "";
                $table_products = "";
                foreach($result as $row)
                    {
                        $sql = "SELECT
                                    orders.order_no,
                                    orders.description,
                                    orders.sku,
                                    orders.qty,
                                    orders.size,
                                    orders.img,
                                    orders.price,
                                    orders.o_status
                                FROM
                                    orders
                                WHERE
                                    order_no = '$item'
                                
                                ";
                        $statement = $db->prepare($sql);
                        $statement->execute();
                        $result = $statement->fetchAll();
                        foreach($result as $row)
                            {
                                //$total_count = $row['qty'];
                                $total = ($row['price'] * $row['qty']);
                                $desc = $row['description'];
                                $item_sku = $row['sku'];
                                //$img .= "<img src='".IMAGE_URL."".$row['img']."' class='img-fluid mb-2' style='max-width:25%;'><br>";
                                $products .= "<td>".$desc." - ".$item_sku." (Quantity - ".$row['qty'].") (".$row['size'].")</td>";
                                $t_products .= "<li>".$desc." - ".$item_sku." (Quantity - ".$row['qty'].")(".$row['size'].")</li>";
                                //$item_total .= $row['qty'] * 

                                $table_products .= "<tr>
                                                <td><img src='".IMAGE_URL."".$row['img']."' class='img-fluid' style='max-width:7%; border-raduis:50%;'></td>
                                                <td><strong>".$desc."</strong> | </td><br><p>".$item_sku."</p>
                                                <td>Size: ".$row['size']." | </td>
                                                <td>$".$row['price']." | </td>
                                                <td>x".$row['qty']." | </td>
                                                <td>$".number_format($total,2)."</td>
                                                </tr>
                                                ";
                            }
                        

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
                                WHERE  order_no = '$item'
                                ";
                        //echo $sql;
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
                                        $promo = $row['promo'];
                                        $date = date('Y-n-j', strtotime($created));
                                        $print_tax = "";
                                    //$total_price = sprintf('%.2f', $price / 100);
                                if($promo == 0)
                                    {
                                        $promo = "N/A";
                                    }
                                else
                                    {
                                        $promo .= $promo;
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
                                        $sql = "SELECT
                                                    *
                                                FROM
                                                    payments
                                                WHERE
                                                    order_no = '$item'
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
                                                    product = '$item'
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
                                                                    <p style='margin-top: -15px;'>Customer ID: ".$row['customer_id']."</p>
                                                                    <p style='margin-top: -15px;'>Staus: ".$row['status']."</p>
                                                                    <p style='margin-top: -15px;'>Total paid: $".$total_price." ".$row['currency']."</p>
                                                                    <p style='margin-top: -15px;'>Date: ".$row['created_at']."</p>";
                                                    }
                                            }
                                        else
                                            {
                                                $stripe_table = "";
                                            }
                                        if(isset($print_tax))
                                            {
                                                $pt = $print_tax;
                                            }
                                        else
                                            {
                                                $pt =  "";
                                            } 
                                        if(isset($paypal_table))
                                            {
                                                $paid_table = $paypal_table;
                                            }
                                        else
                                            {
                                                $paid_table = "";
                                            }
                                        if(isset($stripe_table))
                                            {
                                                $paid_table = $stripe_table;
                                            }
                                        else
                                            {
                                                $paid_table = "";
                                            }
                                        if($paypal_table != "")
                                            {
                                                $paid_by = "<p>Paid by PayPal</p>";
                                            }
                                        else
                                            {
                                                $paid_by = "";
                                            }
                                        if($stripe_table != "")
                                            {
                                                $paid_by = "<p>Paid by Credit/Debit card</p>";
                                            }
                                        else
                                            {
                                                $paid_by = "";
                                            }
                                
                                $data = "<div id='print'>
                                            <div class='row'>
                                                <div class='col-12 col-sm-6'>
                                                    <h2>Order #".$order_no."</h2>
                                                    <p>".$name." | ".$email."</p>
                                                </div>
                                                <div class='col-12 col-sm-6'>
                                                    <h4>Order placed: ".$created."</h4>
                                                </div>
                                            </div>
                                            <div class='row'>
                                                <table class='table table-responsive table-striped'>
                                                    ".$table_products."
                                                    <tr><td></td><td></td><td></td><td></td><td>Total products:</td><td> ".$row['qty']." </td></tr>
                                                    <tr><td></td><td></td><td></td><td></td><td>Tax:</td><td> $".$pt." </td></tr>
                                                    <tr><td></td><td></td><td></td><td></td><td>Shipping:</td><td> $ ".$del_price."</td></tr>
                                                    <tr><td></td><td></td><td></td><td></td><td>Total:</td><td> $".$total_price."</td></tr>
                                                </table>
                                            
                                            </div>
                                            <div class='row'>
                                                <div class='col-12 col-sm-6'>
                                                    <h2>Customer Info</h2>
                                                    <p>".$name."</p>
                                                    <p>".$address."<br></p>
                                                    <p>".$add_2." ".$town."</p>
                                                    <p>".$state.",".$country.",".$zip."</p>
                                                    <h2>Payment info</h2>
                                                    ".$paid_table."
                                                </div>
                                                <div class='col-12 col-sm-6'>
                                                    <h2>Billing Address</h2>
                                                    <p>".$name."</p>
                                                    <p>".$address."<br></p>
                                                    <p>".$add_2." ".$town."</p>
                                                    <p>".$state.",".$country.",".$zip."</p>
                                                    ".$paid_by."
                                                </div>
                                            </div>
                                        </div>"; 
                                        //echo $data;
                                        $mpdf->autoPageBreak = true;

                                        $mpdf->AddPage();
                                        $mpdf->WriteHTML($data);
                            } 
                    }
    }     
$mpdf->Output('"'.$date.'".pdf', 'D');  
exit();                                      
?>