<?php
include '../includes/header.php';

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
?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Shopping Cart</h1>
  </div>
</div>
<div class="container mt-5">
	<div class="row mb-3">
		<div class="col-3">
			<button class="btn btn-info" onclick="goBack()">Continue shopping</button>
		</div>
		<?php echo $_SESSION['order_no']?>
	</div>
	<?php
		if(user_retailer())
			{
				echo "<div class='row text-center'>
						<div class='col-12'>
							<div class='alert alert-info'>Retailer discounts will applied once you place your order and proceed to the delivery page.</div>
						</div>
					</div>";
			}
	?>
	

	
	<div class="row">
		<div id="cart" class="col-12">  
			<div id="order_table">  
			<form action="../includes/insert-order" method="post">  
				<table class="table table-bordered table-responsive">  
					<tr>  
                    <th width="40%">Product Name</th>  
                    <th width="5%">Size</th>
                    <th width="10%">Quantity</th>  
                    <th width="15%">Price</th>  
                    <th width="15%">Total</th>  
                    <th width="5%">Action</th> 
					</tr>  
					<?php  
					if(!empty($_SESSION["shopping_cart"]))  
					{  
							$total = 0;  
							foreach($_SESSION["shopping_cart"] as $keys => $values)  
							{                                               
					?>  
					<tr>  
							<?php 
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
								else
									{
										$too_much = "";
									}
							?>
							<td><?php echo $values["product_name"]; ?></td>  
							<td><?php echo $values["product_size"]; ?></td>
							<td>
							<input style="cursor:pointer;" name="<?=$prod_qty?>" type="tel" id="<?php echo $values["product_code"]; ?>" value="<?php echo $values["product_quantity"]; ?>" data-product_id="<?php echo $values["product_code"]; ?>" class="form-control quantity" /> 
							In stock: <?=$prod_qty?><br><?=$too_much?>
								
							</td>  
							<td class="text-right">$ <?php echo $values["product_price"]; ?></td>  
							<td class="text-right">$ <?php echo number_format($values["product_quantity"] * $values["product_price"], 2); ?></td> 
							<input type="hidden" id="in_stock" name="in_stock" value="<?=$prod_qty?>"> 
							<input type="hidden" name="product_image[]" value="<?=$values["product_image"]?>">
							<input type="hidden" name="product_name[]" value="<?=$values["product_name"]?>">
							<input type="hidden" name="product_size[]" value="<?=$values["product_size"]?>">
							<input type="hidden" name="product_quantity[]" value="<?=$values["product_quantity"]?>">
							<input type="hidden" name="product_price[]" value="<?=$values["product_price"]?>">
							<input type="hidden" name="order_no[]" value="<?=$order_no?>">
							<input type="hidden" name="product_code[]" value="<?=$values["product_code"]?>">
							<td><button name="delete" class="btn btn-danger btn-xs delete" id="<?=$values["product_code"]?>">Remove</button></td>  
					</tr>  
					<?php  
								$total = $total + ($values["product_quantity"] * $values["product_price"]);  

								
							}  
					?>  
					<tr>  
							<td colspan="4" class="text-right">Total</td>  
							<td>$ <?php echo number_format($total, 2); ?></td>  
							<td></td>  
					</tr>  
					<tr>
						<td class="text-right" colspan="6">
							<span>
								<p>Please check quantities and confirm 
								<?php
								if($too_much == "")
									{
										$class = "";
									}
								else 
									{
										 $class = "hide";
									}
								?>
								<input style="margin-left: 5px; margin-top: -10px;" type="checkbox" class="form-control float-right <?=$class?>" onclick="proceed()"/></p>
							</span>
						</td>
					</tr>
					<tr>  
						<td class="text-right" colspan="6"> 
							<input id='submit' type='submit' name='place_order' class='btn btn-info hide' value='Im finished, Proceed to checkout'/>
							</form>  
						</td>  
					</tr>  
					<?php  
					}  
					?>  
				</table>  
			</div>  
		</div>  
	</div>
</div>
<?php
include '../includes/footer.php';
?>