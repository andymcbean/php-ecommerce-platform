<?php
include '../includes/header.php';
include '../filters/items.php';
?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">US Retailer Exclusives</h1>
  </div>
</div>
<?php
if(retailer_us())
    {
?>
<div class="container-fluid margin-top">
    <div class="row">   
        <div class="col-12" id="add-item-bag"></div>    
            <div class="col-12 col-md-2">
            
              <p><strong>Filter</strong></p>
                <span class="input-group-text">
                    <input name="product" type="text" class="form-control search-menu" id="product" placeholder="Search products">
                    <a class="btn btn-info" id="search_button" type="submit">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </a>
                </span>
                <a class="btn btn-info btn-block mb-3" data-toggle="collapse" href="#collapse-items" role="button" aria-expanded="true" aria-controls="collapse-items">
                Size <i class="fas fa-chevron-circle-down"></i>
                </a>
                <div class="list-group collapse" id="collapse-items">
                    <div class='list-group-item checkbox'>
                        <label style='font-size: 12px; color: #000;'><input type='checkbox' class='common_selector a4' value="yes"> A4</label>
                    </div>
                    <div class='list-group-item checkbox'>
                        <label style='font-size: 12px; color: #000;'><input type='checkbox' class='common_selector a3' value="yes"> A3</label>
                    </div>
                    <div class='list-group-item checkbox'>
                        <label style='font-size: 12px; color: #000;'><input type='checkbox' class='common_selector xl' value="yes"> XL</label>
                    </div>
                </div> 
                <?php
                    /*if(user_retailer())
                        {
                            echo "<a class='btn btn-info btn-block' data-toggle='collapse' href='#collapse-r_items' role='button' aria-expanded='true' aria-controls='collapse-r_items'>
                            Retailer only (coming soon)<i class='fas fa-chevron-circle-down'></i>
                                </a>";
                        }
                    else
                        {
                            echo "";
                        }*/
                ?>
            </div>
            <div class="col-12 col-md-10 " id="units">
                <div class="row filter_us_retail">
                    <!--load ajax items-->
                </div>
            </div>
        </div>
</div>

<?php
    }
    else
        {
            echo "<div class='alert alert-danger'>You do not have permission to access this page.</div>";
        }
include '../includes/footer.php';

?>
<script>
//search product name
$('#search_button').click(function(){
var method = 'fetch_product';
var product = $('#product').val();
$.ajax({
    url: '../filters/us-retail',
    method:"POST",
    data: {
        method:method,
        product:product
    },
    success:function(data){
        $('.filter_us_retail').html(data);
    }
});
});
</script>