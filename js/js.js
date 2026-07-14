$(document).ready(function()
{
    // Initialize select2
    $("#selectProduct").select2();

    // Read selected option
    $('#btn_read').click(function()
    {
    var description  = $('#selectProduct option:selected').text();
    var sku  = $('#selectProduct').val();
    var id  = $('#selectProduct').val();

    $('.result').html('<table class="table table-reponsive"><thead><tr><th>Description</th><th>Image</th><th>Edit</th><th>Delete</th></tr></thead><tbody><tr><td> '+ description +' <td><a href="#" data-toggle="modal" data-target="#imgModal'+id+'">Change image</p></a></td><td><a href="#" data-role="update" data-id='+id+'><i class="fas fa-user-edit"></i></a></td><td><button class="btn btn-danger delete" id="del_'+id+'"><i class="fas fa-trash-alt"></i></button></td></tr></tbody></table>');

    });
});

$('#btn_read').on('click', function(){
    $(".p").css({'border' : '1px solid'});
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

function goBack() {
    window.history.back();
  }

function proceed() 
    {
        $('#submit').toggleClass('hide');
    }

function complete_order()
    {
        $('.re_add_to_cart').click(function(){ 
            var product_code = $('#sku').val();
            var product_name = $('#name').val();
            var product_price = $('#price').val();
            var product_size = $('#size').val();
            var product_quantity = $('#quantity').val();
            var product_image = $('#image').val();
            var stock = $('#stock').val();
            var retailer = $('#is_retailer').val();
            var action = 'add';
            $.ajax({
                url: '../includes/re-action',
                method: 'POST',
                data: {product_code:product_code, product_name:product_name, product_price:product_price, product_size:product_size, product_quantity:product_quantity, product_image:product_image, retailer:retailer, stock:stock, action:action},
                success:function()
                {
                    window.location.replace('../new-cart/shopping-cart');
                }
            });
            
        });
    }