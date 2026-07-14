<?php

include '../includes/connect.php';

//add units
if(isset($_POST['add']))
    {
        $url = $_POST['url'];
        
        if($_POST['hidden_price'] == 0)
            {
                header ("Location: ..".$url."&error=error");
            }
        else
            {
                if(isset($_SESSION['cart']))
                    {
                        $item_array_sku = array_column($_SESSION['cart'], 'sku');
                        if(!in_array($_GET['sku'], $item_array_sku))
                            {
                                $count = count($_SESSION['cart']);
                                $item_array = array(
                                    'sku' => $_GET['sku'],
                                    'description' => $_POST['hidden_description'],
                                    'colour' => $_POST['colour'],
                                    'price' => $_POST['hidden_price'],
                                    'quantity' => $_POST['quantity'],
                                    'width' => $_POST['hidden_width'],
                                );
                                $_SESSION['cart'][$count] = $item_array;
                                echo "<script>alert('Item added to cart')</script>";
                                echo "<script>window.history.go(-1)</script>";
                            }
                        else
                            {
                                echo "<script>alert('Item already in cart')</script>";
                                echo "<script>window.history.go(-1)</script>";
                            }    
                    }
                else
                    {
                        $item_array = array(
                            'sku' => $_GET['sku'],
                            'description' => $_POST['hidden_description'],
                            'colour' => $_POST['colour'],
                            'price' => $_POST['hidden_price'],
                            'quantity' => $_POST['quantity'],
                            'width' => $_POST['hidden_width'],
                        );
                        $_SESSION['cart'][0] = $item_array;
                    }
            }
        
    }
// remove units
    if(isset($_GET['action']))
        {
            if($_GET['action'] == 'delete')
                {
                    foreach($_SESSION['cart'] as $keys => $value)
                        {
                            if($value['sku'] == $_GET['sku'])
                                {
                                    unset($_SESSION['cart'][$keys]);
                                    echo "<script>alert('Item removed from cart')</script>";
                                    echo "<script>window.location='cart'</script>";
                                }
                        }
                }
		}
?>
