<?php
session_start();

$total_price = 0;
$total_item = 0;

$order_no = "";
if (isset($_SESSION['order_no']))
	{
		$order_no = $_SESSION['order_no'];
	}
else    
	{
		$order_no = "DQ".time();
	}

$output = '
<div id="order_table">
	<div class="co-12">
	<form action="../includes/insert-order" method="post">
	<table class="table table-striped table-responsive">
		<tr>  
			<th></th>
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
	foreach($_SESSION["shopping_cart"] as $keys => $values)
	{
		$output .= '
		<tr>
			<td><img src="https://kingdomdesign.s3.eu-west-1.amazonaws.com/'.$values['product_image'].'" class="img-fluid" style="max-width:30%;">'.$values['product_image'].'</td>
			<td>'.$values["product_name"].'</td>
			<td>'.$values["product_size"].'</td>
			<td>'.$values["product_quantity"].'</td>
			<td >$ '.$values["product_price"].'</td>
			<td >$ '.number_format($values["product_quantity"] * $values["product_price"], 2).'</td>
			<td><button name="delete" class="btn btn-danger delete" id="'.$values["product_code"].'">Remove</button></td>
		</tr>
		<input type="hidden" name="product_image[]" value="'.$values["product_image"].'">
		<input type="hidden" name="product_name[]" value="'.$values["product_name"].'">
		<input type="hidden" name="product_size[]" value="'.$values["product_size"].'">
		<input type="hidden" name="product_quantity[]" value="'.$values["product_quantity"].'">
		<input type="hidden" name="product_price[]" value="'.$values["product_price"].'">
		<input type="hidden" name="order_no[]" value="'.$order_no.'">
		<input type="hidden" name="product_code[]" value="'.$values["product_code"].'">

		';
		$total_price = $total_price + ($values["product_quantity"] * $values["product_price"]);
		$total_item = $total_item + 1;
	}
	$output .= '
	<tr>  
        <td colspan="3" >Total</td>  
        <td>$ '.number_format($total_price, 2).'</td>  
        <td></td>  
    </tr>
	';
}
else
{
	$output .= '
    <tr>
    	<td colspan="5">
    		Your Cart is Empty!
    	</td>
    </tr></table></div></div>
    ';
}
$output .= '</table><button type="submit" class="btn btn-info" name="proceed"><i class="fas fa-shopping-bag"></i> Check out</button></div></div>
<a href="../new-cart/shopping-cart" class="btn btn-danger"><i class="fas fa-shopping-bag"></i> Check out</a>

</form>';
$data = array(
	'cart_details'		=>	$output,
	'total_price'		=>	'$' . number_format($total_price, 2),
	'total_item'		=>	$total_item,
);	

echo json_encode($data);


?>