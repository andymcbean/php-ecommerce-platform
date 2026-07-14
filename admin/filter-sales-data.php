<?php  
include '../includes/connect.php';
include 'filter-sales.php';
 
 if(isset($_POST['data_from_date'], $_POST['data_to_date']))  
    {   
        $from = date('Y-n-j 00:00:00', strtotime($_POST['data_from_date']));
        $to = date('Y-n-j 23:59:59', strtotime($_POST['data_to_date']));

        $output = '';  
        $sql = "SELECT
                    *
                FROM 
                    delivery
                WHERE 
                    date BETWEEN '$from' AND '$to' 
                AND delivery.paid_by != 'NULL' 
                AND delivery.dispatched = 1
                ORDER BY date DESC
                "; 
        //echo $sql; die(); 
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $total_row = $statement->rowCount();
        $array_tax = ""; 
        $tax = "";
        $output .= "<form id='pdf-form' action='pdf-print' method='post'>
                        <table id='orders-table' class='table table-responsive table-striped'>
                            <tr>
                                <th>Town/City</th>
                                <th>State</th>
                                <th>Country</th>
                                <th>Date</th>
                                <th>Tax</th>
                                <th>Total</th>
                            </tr>";

                    if($total_row != 0)
                        {  
                            foreach($result as $row)  
                                {
                                    if($row['county'] == 'Georgia')
                                        {
                                            $row['county'] = 'GA';
                                        }
                                    else
                                        {
                                            $row['county'] = $row['county'];
                                        }
                                    if($tax > 0.00 && $tax < 10.00)
                                        {
                                            $array_tax .= "00".number_format($tax_total,2)."";
                                        }
                                    if($tax > 10.00 && $tax < 100.00) 
                                        {
                                            $array_tax .= "0".number_format($tax_total,2)."";
                                        }
                                    if($tax > 100.00)
                                        {
                                            $array_tax .= number_format($tax_total,2);
                                        }
                                    $total_paid = $row['sum_total'];
                                    $ga_tax = $row['ga_tax'];
                                    $tax_total = $total_paid * $ga_tax;
                                    $tax = number_format($tax_total,2);
                                    $date = date('Y-n-j', strtotime($row['date']));
                                    $output .= "<tr>
                                                    <td>".$row['town']."</td>
                                                    <td>".$row['county']."</td>
                                                    <td>".$row['country']."</td>
                                                    <td>".$date."</td>
                                                    <td>".$tax."</td>
                                                    <td>".$total_paid."</td>
                                                </tr>";
                                                //echo $ga_tax;
                                } 
                                $array = str_split($array_tax,6);
                                //print_r($array); 
                                $gt = array_sum($array);
                                //echo $gt;
                                $sql = "SELECT  SUM(sum_total) 
                                        AS total
                                        FROM
                                            delivery
                                        WHERE 
                                            date BETWEEN '$from' AND '$to' 
                                        AND paid_by != 'NULL' 
                                        AND dispatched = 1
                                        ";
                                $statement = $db->prepare($sql);
                                $statement->execute();
                                $result = $statement->fetchAll();
                                $total = "";
                                foreach($result as $row)
                                    {
                                        $total = number_format($row['total'],2);
                                    }
                        }  
                    else  
                        {  
                            $output .= "<tr>  
                                            <td>No Order Found</td>  
                                        </tr>";  
                        }  
                $output .= '<td></td><td></td><td></td><td></td><td>'.$gt.'</td><td>'.$total.'</td></table></form>';  
                echo $output;  
            
    } 

    if(isset($_POST['data_query']))  
        {
            $search = $_POST['data_query'];
           
            $output = '';  
            $sql = "SELECT 
                        *
                    FROM 
                        delivery
                    WHERE
                        country = '$search'
                    AND delivery.paid_by != 'NULL'
                    AND delivery.dispatched = 1
                    ORDER BY id DESC
                    "; 
                    
            $statement = $db->prepare($sql);
            $statement->execute();
            $result = $statement->fetchAll();
            $total_row = $statement->rowCount();
            $gt = "";
            $output .= "<form id='pdf-form' action='pdf-print' method='post'>
                        <table id='orders-table' class='table table-responsive table-striped'>
                            <tr>
                                <th>Town/City</th>
                                <th>State</th>
                                <th>Country</th>
                                <th>Date</th>
                                <th>Tax</th>
                                <th>Total</th>
                            </tr>";

                    if($total_row != 0)
                        {  
                            foreach($result as $row)  
                                {
                                    $total_paid = $row['sum_total'];
                                    $ga_tax = $row['ga_tax'];
                                    $tax_total = $total_paid * $ga_tax;
                                    $tax = number_format($tax_total,2);
                                    if($tax > 0.00 && $tax < 10.00)
                                        {
                                            $array_tax .= "00".number_format($tax_total,2)."";
                                        }
                                    if($tax > 10.00 && $tax < 100.00) 
                                        {
                                            $array_tax .= "0".number_format($tax_total,2)."";
                                        }
                                    if($tax > 100.00)
                                        {
                                            $array_tax .= number_format($tax_total,2);
                                        }
                                    
                                    $date = date('Y-n-j', strtotime($row['date']));
                                    $output .= "<tr>
                                                    <td>".$row['town']."</td>
                                                    <td>".$row['county']."</td>
                                                    <td>".$row['country']."</td>
                                                    <td>".$date."</td>
                                                    <td>".$tax."</td>
                                                    <td>".$total_paid."</td>
                                                </tr>";
                                } 
                                $array = str_split($array_tax,6);
                                //print_r($array); 
                                $gt = array_sum($array);
                                //echo $gt;
                                $sql = "SELECT  SUM(sum_total) 
                                        AS total
                                        FROM
                                            delivery
                                        WHERE
                                            country = '$search'
                                        AND delivery.paid_by != 'NULL'
                                        AND delivery.dispatched = 1
                                        ";
                                $statement = $db->prepare($sql);
                                $statement->execute();
                                $result = $statement->fetchAll();
                                $total = "";
                                foreach($result as $row)
                                    {
                                        $total = number_format($row['total'],2);
                                    }
                        }  
                    else  
                        {  
                            $output .= "<tr>  
                                            <td>No Order Found</td>  
                                        </tr>";  
                        }  
                    $output .= '<td></td><td></td><td></td><td></td><td>'.$gt.'</td><td>'.$total.'</td></table></form>';  
                    echo $output;
        }  
        
    if(isset($_POST['data_county']))  
        {
            $ga = $_POST['data_county'];
            $from = date('Y-n-j H:i:s', strtotime($_POST['date']));
            $to = date('Y-n-j 23:59:59', strtotime($_POST['date']));
            $output = '';  
            $sql = "SELECT 
                        *
                    FROM 
                        delivery
                    WHERE
                        (delivery.county = '$ga'
                    AND delivery.date BETWEEN '$from' AND '$to'
                    AND delivery.paid_by != 'NULL'
                    AND delivery.dispatched = 1)
                    OR  (delivery.county = 'Georgia'
                    AND delivery.date BETWEEN '$from' AND '$to'
                    AND delivery.paid_by != 'NULL'
                    AND delivery.dispatched = 1)
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
                                <th>Town/City</th>
                                <th>State</th>
                                <th>Country</th>
                                <th>Date</th>
                                <th>Tax</th>
                                <th>Total</th>
                            </tr>";

                    if($total_row != 0)
                        {  
                            foreach($result as $row)  
                                {
                                    $total_paid = $row['sum_total'];
                                    $ga_tax = $row['ga_tax'];
                                    $tax_total = $total_paid * $ga_tax;
                                    $tax = number_format($tax_total,2);
                                    if($tax > 0.00 && $tax < 10.00)
                                        {
                                            $array_tax .= "00".number_format($tax_total,2)."";
                                        }
                                    if($tax > 10.00 && $tax < 100.00) 
                                        {
                                            $array_tax .= "0".number_format($tax_total,2)."";
                                        }
                                    if($tax > 100.00)
                                        {
                                            $array_tax .= number_format($tax_total,2);
                                        }
                                    $date = date('Y-n-j', strtotime($row['date']));
                                    $output .= "<tr>
                                                    <td>".$row['town']."</td>
                                                    <td>".$row['county']."</td>
                                                    <td>".$row['country']."</td>
                                                    <td>".$date."</td>
                                                    <td>".$tax."</td>
                                                    <td>".$total_paid."</td>
                                                </tr>";
                                }
                                $array = str_split($array_tax,6);
                                //print_r($array); 
                                $gt = array_sum($array);
                                //echo $gt;
                                $sql = "SELECT  SUM(sum_total) 
                                        AS total
                                        FROM
                                            delivery
                                            WHERE
                                            delivery.county = '$ga'
                                        AND delivery.date BETWEEN '$from' AND '$to'
                                        AND delivery.paid_by != 'NULL'
                                        AND delivery.dispatched = 1
                                        ";
                                $statement = $db->prepare($sql);
                                $statement->execute();
                                $result = $statement->fetchAll();
                                $total = "";
                                foreach($result as $row)
                                    {
                                        $total = number_format($row['total'],2);
                                    }
                        }  
                    else  
                        {  
                            $output .= "<tr>  
                                            <td>No Order Found</td>  
                                        </tr>";  
                        }  
                    $output .= '<td></td><td></td><td></td><td></td><td>'.$gt.'</td><td>'.$total.'</td></table></form>';  
                    echo $output;
        } 
 ?>