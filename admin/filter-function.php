<?php
function filter_list_products($this_order)
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

function is_retailer($email, $name)
    {
      global $db;
      $sql = "SELECT
                    id,
                    email, 
                    user_level,
                    retailer
              FROM
                    users
              WHERE
                    email = '$email'
              OR name = '$name'
              AND
                    retailer  = 'Yes'
              ";
      $statement = $db->prepare($sql);
      $statement->execute();
      $result = $statement->fetchAll();
  
        if ($result)
            {
              return true;
            }
        else
            {
              return false;
            }
          
    }
?>