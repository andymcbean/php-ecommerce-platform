<?php
/*session_start();
if(isset($_POST['action']))
{
	if($_POST['action'] == 'add')
	{
		if(isset($_SESSION['shopping_cart']))
		{
			$is_available = 0;
			foreach($_SESSION['shopping_cart'] as $keys => $values)
			{
				if($_SESSION['shopping_cart'][$keys]['product_code'] == $_POST['product_code'])
				{
					$is_available++;
					$_SESSION['shopping_cart'][$keys]['product_quantity'] = $_SESSION['shopping_cart'][$keys]['product_quantity'] + $_POST['product_quantity'];
				}
			}
			if($is_available == 0)
			{
				$item_array = array(
					'product_code'              =>     $_POST['product_code'],  
					'product_image'             =>     $_POST['product_image'],
					'product_name'             	=>     $_POST['product_name'],  
					'product_price'            	=>     $_POST['product_price'], 
					'product_size'            	=>     $_POST['product_size'], 
					'product_quantity'         	=>     $_POST['product_quantity'],
				);
				$_SESSION["shopping_cart"][] = $item_array;
			}
		}
		else
		{
			$item_array = array(
				'product_code'            =>     $_POST['product_code'], 
				'product_image'           =>     $_POST['product_image'],  
				'product_name'            =>     $_POST['product_name'],  
				'product_price'           =>     $_POST['product_price'],
				'product_size'            =>     $_POST['product_size'],  
				'product_quantity'        =>     $_POST['product_quantity'],
			);
			$_SESSION['shopping_cart'][] = $item_array;
		}
	}

	if($_POST['action'] == 'remove')
	{
		foreach($_SESSION['shopping_cart'] as $keys => $values)
		{
			if($values['product_code'] == $_POST['product_code'])
			{
				unset($_SESSION['shopping_cart'][$keys]);
				
			}
		}
	}
	if($_POST['action'] == 'empty')
		{
			unset($_SESSION['shopping_cart']);
		}
	if($_POST['action'] == 'clear')
		{
			unset($_SESSION['shopping_cart']);
		}
}*/

//action.php  
session_start();  
include '../includes/connect.php';
include '../includes/functions.php'; 

if(isset($_POST["product_code"]))  
     {  
          if(user_retailer())
               {
                    $retailer  = "yes";
               }
          else
               {
                    $retailer = "no";
               }
          if(isset($_SESSION['order_no']))
               {
                    $order_no = $_SESSION['order_no'];
               }
          else    
               {
                    $order_no = "DQ".time();
               }
          $order_table = '';  
          $message = '';  
          if($_POST['action'] == 'add')
	          {
		          if(isset($_SESSION['shopping_cart']))
                         {
                              $is_available = 0;
                              foreach($_SESSION['shopping_cart'] as $keys => $values)
                                   {
                                        if($_SESSION['shopping_cart'][$keys]['product_code'] == $_POST['product_code'])
                                             {
                                                  $is_available++;
                                                  $_SESSION['shopping_cart'][$keys]['product_quantity'] = $_SESSION['shopping_cart'][$keys]['product_quantity'] + $_POST['product_quantity'];
                                             }
                                   }
                         if($is_available == 0)
                         {
                              $item_array = array(
                                   'product_code'              =>     $_POST['product_code'],  
                                   'product_image'             =>     $_POST['product_image'],
                                   'product_name'             	=>     $_POST['product_name'],  
                                   'product_price'            	=>     $_POST['product_price'], 
                                   'product_size'            	=>     $_POST['product_size'], 
                                   'product_quantity'         	=>     $_POST['product_quantity'],
                         );
                         $_SESSION["shopping_cart"][] = $item_array;
                         }
                         }
		          else
                         {
                         $item_array = array(
                              'product_code'            =>     $_POST['product_code'], 
                              'product_image'           =>     $_POST['product_image'],  
                              'product_name'            =>     $_POST['product_name'],  
                              'product_price'           =>     $_POST['product_price'],
                              'product_size'            =>     $_POST['product_size'],  
                              'product_quantity'        =>     $_POST['product_quantity'],
                         );
                         $_SESSION['shopping_cart'][] = $item_array;
                         }
	          } 

      if($_POST['action'] == 'remove')
          {
               foreach($_SESSION['shopping_cart'] as $keys => $values)
               {
                    if($values['product_code'] == $_POST['product_code'])
                    {
                         unset($_SESSION['shopping_cart'][$keys]);
                         
                    }
               }
          } 
      if($_POST["action"] == "quantity_change")  
          {  
           foreach($_SESSION["shopping_cart"] as $keys => $values)  
               {  
                if($_SESSION["shopping_cart"][$keys]['product_code'] == $_POST["product_code"])  
                    {  
                         $_SESSION["shopping_cart"][$keys]['product_quantity'] = $_POST["quantity"];  
                    }  
               }  
          }  
               $order_table .= '  
               '.$message.'  
               <form method="post" action="../includes/insert-order">
               <table class="table table-bordered table-responsive">  
               
                    <tr>  
                         <th width="40%">Product Name</th> 
                         <th width="5%">Size</th> 
                         <th width="10%">Quantity</th>  
                         <th width="15%">Price</th>  
                         <th width="15%">Total</th>  
                         <th width="5%">Action</th>  
                    </tr>  
               ';  
          if(!empty($_SESSION["shopping_cart"]))  
               {  
                    $total = 0;  
                    foreach($_SESSION["shopping_cart"] as $keys => $values)  
                         {  
                              $too_much = "";
						$class = "";
                              $sku = $values["product_code"];
                              $size = $values["product_size"];
                              $qty = $values["product_quantity"];
                              $prod_qty = product_qty($sku, $size);
                              if($prod_qty < $qty)
                                   {
                                        $too_much = "<div class='alert alert-danger'>You have too much of this product in your cart!</div>";
                                   }
                              $order_table .= '  
                              <tr>  
                                   <td>'.$values["product_name"].'</td>  
                                   <td>'.$values["product_size"].'</td>
                                   
                                   <td><input style="cursor:pointer;" name='.$prod_qty.' type="tel" id='.$values["product_code"].' value='.$values["product_quantity"].' class="form-control quantity" data-product_id='.$values["product_code"].' />In stock: '.$prod_qty.'<br>'.$too_much.'</td>
                                   <td align="right">$ '.$values["product_price"].'</td>
                                   <td align="right">$ '.number_format($values["product_quantity"] * $values["product_price"], 2).'</td>
                                   <input type="hidden" id="in_stock" name="in_stock'.$values["product_code"].'" value="'.$prod_qty.'" />
                                   <input type="hidden" name="product_image[]" value="'.$values["product_image"].'">
                                   <input type="hidden" name="product_name[]" value="'.$values["product_name"].'">
                                   <input type="hidden" name="product_size[]" value="'.$values["product_size"].'">
                                   <input type="hidden" name="product_quantity[]" value="'.$values["product_quantity"].'">
                                   <input type="hidden" name="product_price[]" value="'.$values["product_price"].'">
                                   <input type="hidden" name="order_no[]" value="'.$order_no.'">
                                   <input type="hidden" name="product_code[]" value="'.$values["product_code"].'">
                                   <input type="hidden" name="is_retailer" value='.$retailer.' id="is_retailer"  />
                                   <td><button name="delete" class="btn btn-danger btn-xs delete" id="'.$values["product_code"].'">Remove</button></td> 
                              </tr>';  
                              $total = $total + ($values["product_quantity"] * $values["product_price"]);  
                         }  
                              if($too_much == "")
                                   {
                                        $class = "";
                                   }
                              else 
                                   {
                                        $class = "hide";
                                   }
								
                              $order_table .= '  
                              <tr>  
                                   <td colspan="4" class="text-right">Total</td>  
                                   <td>$ '.number_format($total, 2).'</td>  
                                   <td></td>  
                              </tr>  
                              <tr>
                                   <td class="text-right" colspan="6">
                                        <span>
                                             <p>Please check quantities and confirm 
                                             <input style="margin-left: 5px; margin-top: -10px;" type="checkbox" class="form-control float-right '.$class.'" onclick="proceed()"/></p>
                                        </span>
                                   </td>
					     </tr>
                              <tr>  
                                   <td colspan="6" class="text-right">  
                                             <input id="submit" type="submit" name="place_order" class="btn btn-info hide" value="I&#39;m finished, Proceed to checkout" />  
                                        </form>  
                                   </td>  
                              </tr>';  
               }  
          $order_table .= '</table>';  
          $output = array(  
               'order_table'     =>     $order_table,  
               'cart_item'          =>     count($_SESSION["shopping_cart"])  
          );  

          /*if($_POST['action'] == 'empty')
               //{
                    unset($_SESSION['shopping_cart']);
               //}
          if($_POST['action'] == 'clear')
               //{
                    unset($_SESSION['shopping_cart']);
               }*/
          echo json_encode($output);  
     }
?>      

