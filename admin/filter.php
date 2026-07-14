<?php  
include '../includes/connect.php';
include 'filter-function.php';
 
 if(isset($_POST['from_date'], $_POST['to_date']))  
    {   
        $from = date('Y-n-j 00:00:00', strtotime($_POST['from_date']));
        $to = date('Y-n-j 23:59:59', strtotime($_POST['to_date']));

        $output = '';  
        $sql = "SELECT DISTINCT
                    delivery.id,
                    delivery.order_no,
                    delivery.name,
                    delivery.email,
                    delivery.del_price,
                    delivery.del_type,
                    delivery.paid_by,
                    delivery.add_one,
                    delivery.add_two,
                    delivery.country,
                    delivery.town,
                    delivery.county,
                    delivery.post_code,
                    delivery.date,
                    delivery.dispatched
                FROM 
                    delivery
                WHERE 
                    date BETWEEN '$from' AND '$to' 
                AND delivery.paid_by != 'NULL' 
                AND delivery.dispatched = 0
                ORDER BY date DESC
                "; 
        //echo $sql; die(); 
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $total_row = $statement->rowCount();
        $output .= "<form id='pdf-form' action='pdf-print' method='post'>
                        <table id='orders-table' class='table table-responsive table-striped'>  
                            <tr>
                                <th>Print</th>
                                <th>Order No</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Address line 2</th>
                                <th>Town/City</th>
                                <th>State</th>
                                <th>Zip</th>
                                <th>Country</th>
                                <th>Shipping</th>
                                <th>User retailer</th>
                                <th>Paid via</th>
                                <th>Date</th>
                                <th>Dispatched</th>
                                <th>View</th>
                                <th>Delete</th>
                                <th class='hide'>Products</th>
                            </tr>
                        ";

                if($total_row != 0)
                    {  
                        foreach($result as $row)  
                            {
                                        $email = $row['email'];
                                        $order_no = $row['order_no']; 
                                        $name = $row['name'];
                                        if($row['del_type'] == 2)
                                            {
                                                $del = "Collection";
                                            }
                                        elseif($row['del_type'] == 0)
                                            {
                                                $del = "Delivery";
                                            }
                                        if($row['dispatched'] == 0)
                                            {
                                                $dispatched = "<a style='cursor: pointer;' data-toggle='modal' data-target='#trackModal".$row['id']."'><i class='fas fa-times-circle' style='color: red;'></i><p style='font-size: 10px;'>Add tracking No</p></a>";
                                            }
                                        elseif($row['dispatched'] == 1)
                                            {
                                                $dispatched = "<i data-toggle='tooltip' data-placement='top' data-original-title='".$row['carrier'].": ".$row['code']."' class='fas fa-check-square' style='color: green;'></i>";
                                            }
                                        if(is_retailer($email, $name) != false)
                                            {
                                                $retailer = "<i class='fas fa-check-square' style='color: green;'></i>";
                                            }
                                        else
                                            {
                                                $retailer = "<i class='fas fa-times-circle' style='color: red;'></i>";
                                            }
                                        if($row['paid_by'] != "")
                                            {
                                                $paid_by = $row['paid_by'];
                                            }
                                        else    
                                            {
                                                $paid_by = "<div class='alert alert-warning'>**Payment not complete**</div>";
                                            }
                                        $date = date('Y-n-j', strtotime($row['date'])); 
                                        $list_products = filter_list_products($order_no);
                                        $output .= "<tr>
                                                        <td><input type='checkbox' name='order_nos[]' class='orders' value='".$order_no."'</td>
                                                        <td>".$order_no."</td>
                                                        <td>".$email."</td>
                                                        <td>".$row['name']."</td>
                                                        <td>".$row['add_one']."</td>
                                                        <td>".$row['add_two']."</td>
                                                        <td>".$row['town']."</td>
                                                        <td>".$row['county']."</td>
                                                        <td>".$row['post_code']."</td>
                                                        <td>".$row['country']."</td>
                                                        <td>".$del."</td>
                                                        <td>".$retailer."</td>
                                                        <td>".$paid_by."</td>
                                                        <td>".$date."</td>
                                                        <td>".$dispatched."</td>
                                                        <td><a class=\"btn btn-info\" href='order-details?order=".$row['order_no']."'><i class=\"fas fa-eye\"></i></a></td>
                                                        <td><button class='btn btn-danger delete' id='del_".$row['id']."'> <i class='fas fa-trash-alt'></i></button></td>
                                                        <td class='hide'>".$list_products."</td>
                                                    </tr>"; 
                                    
                            }  
                    }  
                else  
                    {  
                        $output .= "<tr>  
                                        <td>No Order Found</td>  
                                    </tr>";  
                    }  
                $output .= '</table></form>';  
                echo $output;  
            
    } 

    if(isset($_POST['query']))  
        {
            $search = $_POST['query'];
           
            $output = '';  
            $sql = "SELECT 
                        delivery.id,
                        delivery.order_no,
                        delivery.name,
                        delivery.email,
                        delivery.del_price,
                        delivery.del_type,
                        delivery.paid_by,
                        delivery.add_one,
                        delivery.add_two,
                        delivery.country,
                        delivery.town,
                        delivery.county,
                        delivery.post_code,
                        delivery.date,
                        delivery.dispatched
                    FROM 
                        delivery
                    WHERE
                        country = '$search'
                    AND delivery.paid_by != 'NULL'
                    AND delivery.dispatched = 0
                    ORDER BY id DESC
                    "; 
                    
            $statement = $db->prepare($sql);
            $statement->execute();
            $result = $statement->fetchAll();
            $total_row = $statement->rowCount();
            $output .= "<form id='pdf-form' action='pdf-print' method='post'>
                            <table id='orders-table' class='table table-responsive table-striped'>
                            <tr>
                                <th>Print</th>
                                <th>Order No</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Address line 2</th>
                                <th>Town/City</th>
                                <th>State</th>
                                <th>Zip</th>
                                <th>Country</th>
                                <th>Shipping</th>
                                <th>User retailer</th>
                                <th>Paid via</th>
                                <th>Date</th>
                                <th>Dispatched</th>
                                <th>View</th>
                                <th>Delete</th>
                                <th class='hide'>Products</th>
                            </tr>";

                    if($total_row != 0)
                        {  
                            foreach($result as $row)  
                                {   
                                    $email = $row['email'];
                                    $order_no = $row['order_no']; 
                                    $name = $row['name'];
                                    if($row['del_type'] == 2)
                                        {
                                            $del = "Collection";
                                        }
                                    elseif($row['del_type'] == 0)
                                        {
                                            $del = "Delivery";
                                        }
                                    if($row['dispatched'] == 0)
                                        {
                                            $dispatched = "<a style='cursor: pointer;' data-toggle='modal' data-target='#trackModal".$row['id']."'><i class='fas fa-times-circle' style='color: red;'></i><p style='font-size: 10px;'>Add tracking No</p></a>";
                                        }
                                    elseif($row['dispatched'] == 1)
                                        {
                                            $dispatched = "<i data-toggle='tooltip' data-placement='top' data-original-title='".$row['carrier'].": ".$row['code']."' class='fas fa-check-square' style='color: green;'></i>";
                                        }
                                    if(is_retailer($email, $name) != false)
                                        {
                                            $retailer = "<i class='fas fa-check-square' style='color: green;'></i>";
                                        }
                                    else
                                        {
                                            $retailer = "<i class='fas fa-times-circle' style='color: red;'></i>";
                                        }
                                    if($row['paid_by'] != "")
                                        {
                                            $paid_by = $row['paid_by'];
                                        }
                                    else    
                                        {
                                            $paid_by = "<div class='alert alert-warning'>**Payment not complete**</div>";
                                        }
                                    $date = date('Y-n-j', strtotime($row['date'])); 
                                    $list_products = filter_list_products($order_no);
                                    $output .= "<tr>
                                                    <td><input type='checkbox' name='order_nos[]' class='orders' value='".$order_no."'</td>
                                                    <td>".$order_no."</td>
                                                    <td>".$email."</td>
                                                    <td>".$row['name']."</td>
                                                    <td>".$row['add_one']."</td>
                                                    <td>".$row['add_two']."</td>
                                                    <td>".$row['town']."</td>
                                                    <td>".$row['county']."</td>
                                                    <td>".$row['post_code']."</td>
                                                    <td>".$row['country']."</td>
                                                    <td>".$del."</td>
                                                    <td>".$retailer."</td>
                                                    <td>".$paid_by."</td>
                                                    <td>".$date."</td>
                                                    <td>".$dispatched."</td>
                                                    <td><a class=\"btn btn-info\" href='order-details?order=".$row['order_no']."'><i class=\"fas fa-eye\"></i></a></td>
                                                    <td><button class='btn btn-danger delete' id='del_".$row['id']."'> <i class='fas fa-trash-alt'></i></button></td>
                                                    <td class='hide'>".$list_products."</td>
                                                </tr>"; 
                                }  
                        }  
                    else  
                        {  
                            $output .= "<tr>  
                                            <td>No Order Found</td>  
                                        </tr>";  
                        }  
                    $output .= '</table></form>';  
                    echo $output;
        }  
        
    if(isset($_POST['county']))  
        {
            $ga = $_POST['county'];
            $from = date('Y-n-j H:i:s', strtotime($_POST['date']));
            $to = date('Y-n-j 23:59:59', strtotime($_POST['date']));
            $output = '';  
            $sql = "SELECT 
                        delivery.id,
                        delivery.order_no,
                        delivery.name,
                        delivery.email,
                        delivery.del_price,
                        delivery.del_type,
                        delivery.paid_by,
                        delivery.add_one,
                        delivery.add_two,
                        delivery.country,
                        delivery.town,
                        delivery.county,
                        delivery.post_code,
                        delivery.date,
                        delivery.dispatched
                    FROM 
                        delivery
                    WHERE
                        delivery.county = '$ga'
                    AND delivery.date BETWEEN '$from' AND '$to'
                    AND delivery.paid_by != 'NULL'
                    AND delivery.dispatched = 0
                    ORDER BY delivery.date DESC
                    ";   
                    //echo $sql; die();
            $statement = $db->prepare($sql);
            $statement->execute();
            $result = $statement->fetchAll();
            $total_row = $statement->rowCount();
            $output .= "<form id='pdf-form' action='pdf-print' method='post'>
                        <table id='orders-table' class='table table-responsive table-striped'>
                            <tr>
                                <th>Print</th>
                                <th>Order No</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Address line 2</th>
                                <th>Town/City</th>
                                <th>State</th>
                                <th>Zip</th>
                                <th>Country</th>
                                <th>Shipping</th>
                                <th>User retailer</th>
                                <th>Paid via</th>
                                <th>Date</th>
                                <th>Dispatched</th>
                                <th>View</th>
                                <th>Delete</th>
                                <th class='hide'>Products</th>
                            </tr>";

                    if($total_row != 0)
                        {  
                            foreach($result as $row)  
                                {
                                    $email = $row['email'];
                                    $order_no = $row['order_no']; 
                                    $name = $row['name']; 
                                    if($row['del_type'] == 2)
                                        {
                                            $del = "Collection";
                                        }
                                    elseif($row['del_type'] == 0)
                                        {
                                            $del = "Delivery";
                                        }
                                    if($row['dispatched'] == 0)
                                        {
                                            $dispatched = "<a style='cursor: pointer;' data-toggle='modal' data-target='#trackModal".$row['id']."'><i class='fas fa-times-circle' style='color: red;'></i><p style='font-size: 10px;'>Add tracking No</p></a>";
                                        }
                                    elseif($row['dispatched'] == 1)
                                        {
                                            $dispatched = "<i data-toggle='tooltip' data-placement='top' data-original-title='".$row['carrier'].": ".$row['code']."' class='fas fa-check-square' style='color: green;'></i>";
                                        }
                                    if(is_retailer($email, $name) != false)
                                        {
                                            $retailer = "<i class='fas fa-check-square' style='color: green;'></i>";
                                        }
                                    else
                                        {
                                            $retailer = "<i class='fas fa-times-circle' style='color: red;'></i>";
                                        }
                                    if($row['paid_by'] != "")
                                        {
                                            $paid_by = $row['paid_by'];
                                        }
                                    else    
                                        {
                                            $paid_by = "<div class='alert alert-warning'>**Payment not complete**</div>";
                                        }
                                    $date = date('Y-n-j', strtotime($row['date'])); 
                                    $list_products = filter_list_products($order_no);
                                    $output .= "<tr>
                                                    <td><input type='checkbox' name='order_nos[]' class='orders' value='".$order_no."'</td>
                                                    <td>".$order_no."</td>
                                                    <td>".$email."</td>
                                                    <td>".$row['name']."</td>
                                                    <td>".$row['add_one']."</td>
                                                    <td>".$row['add_two']."</td>
                                                    <td>".$row['town']."</td>
                                                    <td>".$row['county']."</td>
                                                    <td>".$row['post_code']."</td>
                                                    <td>".$row['country']."</td>
                                                    <td>".$del."</td>
                                                    <td>".$retailer."</td>
                                                    <td>".$paid_by."</td>
                                                    <td>".$date."</td>
                                                    <td>".$dispatched."</td>
                                                    <td><a class=\"btn btn-info\" href='order-details?order=".$row['order_no']."'><i class=\"fas fa-eye\"></i></a></td>
                                                    <td><button class='btn btn-danger delete' id='del_".$row['id']."'> <i class='fas fa-trash-alt'></i></button></td>
                                                    <td class='hide'>".$list_products."</td>
                                                </tr>";
                                } 
                        }  
                    else  
                        {  
                            $output .= "<tr>  
                                            <td>No Order Found</td>  
                                        </tr>";  
                        }  
                    $output .= '</table></form>';  
                    echo $output;
        } 
 ?>