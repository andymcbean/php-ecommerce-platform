<?php
ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("html_errors", 1);
error_reporting(E_ALL);

function view_orders()
    {
        global $db;
        $sql = "SELECT DISTINCT
                    (order_no)
                FROM 
                    orders
            ";
    
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $total_row = $statement->rowCount();

        foreach($result as $row)
            {
                echo "<a style='color: white;' href='view-order?order_no=".$row['order_no']."' class='btn btn-info' type='button'>".$row['order_no']."</a>";
            }
    }


    
function kitchen_range_colours()
	{
		global $db;
		
		$sql = "SELECT
                    id,
                    colour
                FROM
                    colour_palette
            ";
       
		$statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $ranges = '';
		foreach($result as $row)
			{
				$ranges .= "<option id=".$row['id'].">".$row['colour']."</option>";
			}
	
		return $ranges;
    }
    
function appliance_type()
    {
        global $db;
        $sql = "SELECT
                    type
                FROM 
                    appliance_type
                ORDER BY 
                    type ASC
            ";
    echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<option>".$row['type']."</option>";
            }
        return $output;
    }

function appliances()
    {
        global $db;
        $sql = "SELECT
                    description,
                    sku,
                    manufacturer,
                    type
                FROM 
                    appliances
                ORDER BY 
                    manufacturer ASC
            ";
    //echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<option value='".$row['description']."'>".$row['manufacturer']." ".$row['sku']." ".$row['description']."</option>";
            }
        return $output;
    }

function storage()
    {
        global $db;
        $sql = "SELECT
                    id,
                    description,
                    sku
                FROM 
                    storage
                ORDER BY 
                    description ASC
            ";
    //echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<option value='".$row['id']."'>".$row['sku']." - ".$row['description']."</option>";
            }
        return $output;
    }

function base_units()
    {
        global $db;
        $sql = "SELECT
                    id,
                    description,
                    sku
                FROM 
                    items
                ORDER BY 
                    description ASC
            ";
    //echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<option value='".$row['id']."'>".$row['sku']." - ".$row['description']."</option>";
            }
        return $output;
    }    

function fill_product()  
    {  
        global $db;
        $sql = "SELECT
                    description,
                    sku
                FROM 
                    appliances
                ORDER BY 
                    description ASC
            ";
    //echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row) 
            {  
                $output .= '<div class="col-md-3">';  
                $output .= '<div style="border:1px solid #ccc; padding:20px; margin-bottom:20px;">'.$row["description"].'';  
                $output .=     '</div>';  
                $output .=     '</div>';  
            }  
        return $output;  
    } 
function worktops()
    {
        global $db;
        $sql = "SELECT
                    description,
                    sku
                FROM 
                    worktops
                ORDER BY 
                    description ASC
            ";
    //echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<option>".$row['description']."</option>";
            }
        return $output;
    }

function fill_worktop()  
    {  
        global $db;
        $sql = "SELECT
                    description,
                    sku
                FROM 
                    worktops
                ORDER BY 
                    description ASC
            ";
    //echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row) 
            {  
                $output .= '<div class="col-md-3">';  
                $output .= '<div style="border:1px solid #ccc; padding:20px; margin-bottom:20px;">'.$row["description"].'';  
                $output .=     '</div>';  
                $output .=     '</div>';  
            }  
        return $output;  
    }

function sinks()
    {
        global $db;
        $sql = "SELECT
                    description,
                    sku
                FROM 
                    sinks_taps
                ORDER BY 
                    description ASC
            ";
    //echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<option>".$row['description']."</option>";
            }
        return $output;
    }

function handles()
    {
        global $db;
        $sql = "SELECT
                    description,
                    sku
                FROM 
                    handles
                ORDER BY 
                    description ASC
            ";
    //echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<option>".$row['description']."</option>";
            }
        return $output;
    }

function flooring()
    {
        global $db;
        $sql = "SELECT
                    description,
                    sku
                FROM 
                    flooring
                ORDER BY 
                    description ASC
            ";
    //echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<option>".$row['description']."</option>";
            }
        return $output;
    }

function storage_type()
    {
        global $db;
        $sql = "SELECT DISTINCT
                    (type)
                FROM 
                    storage
                ORDER BY 
                    type ASC
            ";
        //echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<option>".$row['type']."</option>";
            }
        return $output;
    }

function base_type()
    {
        global $db;
        $sql = "SELECT DISTINCT
                    (type)
                FROM 
                    units
                ORDER BY 
                    type ASC
            ";
        //echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<option>".$row['type']."</option>";
            }
        return $output;
    }

function list_products($this_order)
    {
        global $db;
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
        foreach($result as $row)
            {
                $total = ($row['price'] * $row['qty']);
                $desc = $row['description'];
                $item_sku = $row['sku'];
                $products .= "<p>".$desc." - ".$item_sku." (Quantity - ".$row['qty'].") (".$row['size'].")</p>";
                $t_products .= "<li>".$desc." - ".$item_sku." (Quantity - ".$row['qty'].")(".$row['size'].")</li>";
            }
            return $products;
    }
    //style='display: none'

function get_sku()
    {
        global $db;
        $sql = "SELECT
                    description,
                    sku
                FROM
                    items
                WHERE  type = 'Scrapbook'
                ";
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $sku = "";
        foreach($result as $row)
            {
                $sku .= "<option value='".$row['sku']."'>".$row['sku']." (".$row['description'].")</option>";
            }
        return $sku;
    }

?>