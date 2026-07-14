//NEW CART
$(document).ready(function(data){ 
	
	//ADD TO CART
	$('.add_to_cart').click(function(){ 
		
		var product_code = $('#sku').val();
		var product_name = $('#name').val();
		var product_price = $('#price').val();
		var product_size = $('#size').val();
		var product_quantity = $('#quantity').val();
		var product_image = $('#image').val();
		var stock = $('#stock').val();
		var retailer = $('#is_retailer').val();
		var action = 'add';

		if(retailer == 'yes' && (parseInt(product_quantity) < '5'))
			{
				alert('As a retailer you must order a minimum of 5 of each');
			}
		else if( product_quantity > 0 && product_price > 0 && (parseInt(product_quantity) < stock))
			{
				$.ajax({
					url: '../includes/action',
					method: 'POST',
					data: {product_code:product_code, product_name:product_name, product_price:product_price, product_size:product_size, product_quantity:product_quantity, product_image:product_image, stock:stock, retailer:retailer, action:action},
					success:function()
					{
						$('#order_table').html(data.order_table);  
                        $('.badge').text(data.cart_item);
						$("#inCart").modal('show');
					}
				});
			}
		else
			{
				alert('Please check in stock level.');
			}
	});

	//DELETE FROM CART
	$(document).on('click', '.delete', function(){
		var product_code = $(this).attr('id');
		var action = 'remove';
		if(confirm('Are you sure you want to remove this product?'))
			{
				$.ajax({
					url:'../includes/action',
					method:'POST',
					data:{product_code:product_code, action:action},
					success:function()
					{
						alert('Item has been removed from Cart');
					}
				})
			}
			else
			{
				return false;
			}
	});  

	$(document).on('click', '#clear_cart', function(){
		var product_code = $(this).attr('id');
		var action = 'remove';
		if(confirm('Are you sure you want to remove all products?'))
			{
				$.ajax({
					url:'../includes/action',
					method:'POST',
					data:{product_code:product_code, action:action},
					success:function()
					{
						alert('Cart has been cleared');
					}
				})
			}
			else
			{
				return false;
			}
	});

	//INCREASE QUANTITY IN CART
	$(document).on('change', '.quantity', function(){  
		  var product_code = $(this).attr('id');
		  var quantity = $(this).val();  
		  var product_quantity = $(this).attr('name');
		  var action = 'quantity_change';  
		  //alert(retailer);
		  //alert(product_quantity);
		  
		if(parseInt(product_quantity) >= quantity)  
			{  
				$.ajax({  
						url :'../includes/action',  
						method:'POST',  
						dataType:'json',  
						data:{product_code:product_code, quantity:quantity, action:action},  
						success:function(data){  
							$('#order_table').html(data.order_table);  
						}  
				});  
			} 
		else	
			{
				alert('Please check in stock level.');
				window.location.reload();
			}
	 });  
});