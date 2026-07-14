$(document).ready(function(){
	load_cart_data();

	function load_cart_data()
	{
		$.ajax({
			method: 'POST',
			url: '../includes/fetch-cart',
			contentType: 'application/json',
			dataType: 'json',
			data: JSON.stringify({
				product_code: $('#sku').val(),
				product_name: $('#name').val(),
				product_price: $('#price').val(),
				product_size: $('#size').val(),
				product_quantity: $('#quantity').val(),
				product_image: $('#image').val(),
			}),
			success:function(data)
			{
				$('#cart_details').html(data.cart_details);
				$('.total_price').text(data.total_price);
				$('.badge').text(data.total_item);
				console.log(data);
			},
			error: function(data) {
				console.log(data);
			}
		});
	}

	$(document).on('click', '.add_to_cart', function(){
		var stock = $('#stock').val();
		var product_code = $('#sku').val();
		var product_name = $('#name').val();
		var product_price = $('#price').val();
		var product_size = $('#size').val();
		var product_quantity = $('#quantity').val();
		var product_image = $('#image').val();
		var action = 'add';

		if(product_quantity > 0 && product_price > 0 && (parseInt(product_quantity) < stock))
			{
				$.ajax({
					url: '../includes/action',
					method: 'POST',
					data: {product_code:product_code, product_name:product_name, product_price:product_price, product_size:product_size, product_quantity:product_quantity, product_image:product_image, stock:stock, action:action},
					success:function()
					{
						load_cart_data();
						alert('Item has been Added into Cart');
					}
				});
			}
		else
			{
				alert('Please check in stock level.');
			}
	});

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
					load_cart_data();
					$('#cart-popover').popover('hide');
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
		var action = 'empty';
		$.ajax({
			url:'../includes/action',
			method:'POST',
			data:{action:action},
			success:function()
			{
				load_cart_data();
				$('#cart-popover').popover('hide');
				alert('Your Cart has been clear');
			}
		});
	});

	$(document).on('click', '#keep_shopping', function(){
		var action = 'clear';
		$.ajax({
			url:'../includes/action',
			method:'POST',
			data:{action:action},
			success:function()
			{
				load_cart_data();
				$('#cart-popover').popover('hide');
				alert('Your Cart has been cleared but we have saved the items you already added to check out.');
			}
		});
	});
    
});