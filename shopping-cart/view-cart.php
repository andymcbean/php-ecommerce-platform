<?php
if(!empty($_SESSION["cart_item"])){
	$count = (is_array(count($_SESSION["cart_item"])));
} else {
	$count = 0;
}
include '../includes/header.php';
//include '../shopping-cart/remove.php';
//include '../shopping-cart/add-to-cart.php';
?>
<section class="showcase">
  <div class="container">
    <div class="pb-2 mt-4 mb-2 border-bottom">
      <h2>Shopping Basket <a style="float: right;" href="#" class="btn btn-primary text-right">  Cart <i class="fa fa-shopping-cart" aria-hidden="true"></i> <span class="badge badge-light" id="cart-count"><?php print $count; ?></span></a></h2>
 
    </div>
	<div class="row">
		<div id="shopping-cart">
 
		<?php
		if(isset($_SESSION["cart_item"])){
		    $total_quantity = 0;
		    $total_price = 0;
		?>	
		<table class="tbl-cart table-responsive" cellpadding="10" cellspacing="1">
		<thead>
			<tr>
				<th style="text-align:left;">Name</th>
				<th style="text-align:left;">SKU</th>
				<th style="text-align:right;" width="5%">Quantity</th>
				<th style="text-align:right;" width="10%">Unit Price</th>
				<th style="text-align:right;" width="10%">Price</th>
				<th style="text-align:center;" width="5%">Remove</th>
			</tr>	
		</thead>
		<tbody id="render-cart-data">
		<?php		
		    foreach ($_SESSION["cart_item"] as $item){
		        $item_price = $item["quantity"]*$item["price"];
				?>
						<tr id="<?php echo $item["sku"]; ?>">
						<td><img src="<?php echo $item["img"]; ?>" class="cart-item-image img-fluid" style="max-width: 20%;"/><?php echo $item["description"]; ?> (<?php echo $item["hinged"]; ?> hinged)</td>
						<td><?php echo $item["sku"]; ?></td>
						<td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
						<td  style="text-align:right;"><?php echo "£ ".$item["price"]; ?></td>
						<td  style="text-align:right;"><?php echo "£ ". number_format($item_price,2); ?></td>
						<td style="text-align:center;">
						<a class="text-danger btnRemoveAction" data-sku="<?php echo $item['sku']; ?>"><i class="fa fa-trash" aria-hidden="flase"></i></a></td>
						</tr>
						<?php
						$total_quantity += $item["quantity"];
						$total_price += ($item["price"]*$item["quantity"]);
				}
				?>
			
 
				<tr>
					<td colspan="2">Total:</td>
					<td id="render-qty"><?php echo $total_quantity; ?></td>
					<td colspan="2" id="render-total"><strong><?php echo "£ ".number_format($total_price, 2); ?></strong></td>
					<td></td>
				</tr>
				</tbody>
			<tfoot>
				<tr>
					
					<td colspan="2"><button onclick="window.history.go(-1); return false;" class="btn btn-info"><i class="fa fa-chevron-left"></i> Continue shopping</button> </td>
					<td ></td>	
					<td colspan="3"></td>
				</tr>
			</tfoot>
 
		</table>		
		  <?php
		} else {
		?>
		<table class="tbl-cart" cellpadding="10" cellspacing="1">
 
		<tfoot>
			<tr>
				<td colspan="4"><div class="no-records">Your Cart is Empty</div></td>
			</tr>
			<tr>
				
				<td colspan="2"><button onclick="window.history.go(-1); return false;" class="btn btn-info"><i class="fa fa-chevron-left"></i> Continue shopping</button></td>
				<td></td>	
				<td></td>
			</tr>
		</tfoot>
		</table>
 
		<?php 
		}
		?>
		</div>
 
 </div>
 
</div>
</section>

<?php include('../includes/footer.php');?> 
 
<script type="text/javascript">
	$(document).on('click', 'a.btnRemoveAction', function() {
		var sku = $(this).data('sku');
	    $.ajax({
	        type:'POST',
	        url:'remove',
	        data:{sku:sku},
	        dataType:'json',               
	        success: function (json) {
	        	if(json.total_quantity) 
					{
						$('#cart-count').html(json.count);
						$('#render-qty').html(json.total_quantity);
						$('#render-total').html("£ "+json.total_price);
						$("#"+sku).empty();
					} 
				else 
					{
						$('#render-cart-data').empty();
					}
            },
	        error: function (xhr, ajaxOptions, thrownError) {
	            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }        
	    });
	});
</script>

<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>