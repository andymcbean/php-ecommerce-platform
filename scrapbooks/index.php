<?php
include '../includes/header.php';
include '../filters/scrap.php';
?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Scrapbooks</h1>
  </div>
</div>
<div class="container-fluid margin-top">
    <div class="row">   
        <div class="col-12" id="add-item-bag"></div>    
            <div class="col-12 col-md-2">
            
              <p><strong>Filter</strong></p>
                <span class="input-group-text">
                    <input name="scrapbook" type="text" class="form-control search-menu" id="scrapbook" placeholder="Search scrapbooks">
                    <a class="btn btn-info" id="scrap_search" type="submit">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </a>
                </span>
                <!--<a class="btn btn-info btn-block" data-toggle="collapse" href="#collapse-items" role="button" aria-expanded="true" aria-controls="collapse-items">
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
                </div> -->
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
                <div class="row filter_scrap">
                    <!--load ajax items-->
                </div>
            </div>
        </div>
</div>

<?php
include '../includes/footer.php';

?>
<script>
//search product name
$('#scrap_search').click(function(){
var fetch_scrap = 'fetch_product';
var product = $('#scrapbook').val();
$.ajax({
    url: '../filters/scrap',
    method:"POST",
    data: {
        fetch_scrap:fetch_scrap,
        product:product
    },
    success:function(data){
        $('.filter_scrap').html(data);
    }
});
});
</script>