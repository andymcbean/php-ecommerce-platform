<?php
include '../includes/header.php';
include '../includes/constants.php';

if(isset($_GET['sku']))
    {
        $sku = $_GET['sku'];
    }

$sql = "SELECT 
            *
        FROM 
            items
        WHERE
            sku = '$sku'
        ";

$statement = $db->prepare($sql);
$statement ->execute();
$result = $statement ->fetchAll();
$option_a4 = "";
$option_a3 = "";
$option_xl = "";
$option_scrap = "";
$option_chip = "";
$size = "";
$stock_count = "";
foreach($result as $row)
    {
        $original_scrap =  '<s>'.number_format($row['price_scrap'],2).'</s>';
        $price_scrap = $row['price_scrap'];
        $price_chipboard = $row['price_chipboard'];
        $id = $row['id'];
        $img = $row['img'];
        $description = $row['description'];

        if($row['type'] == 'Scrapbook')
            {
                $retail_dis = ($price_scrap * 0.45);
                $discount_scrap = ($price_scrap * $row['discount']);
                if($discount_scrap > 0)
                    {
                        $row_scrap = ($price_scrap - $discount_scrap);
                        $option_scrap .= "<option class='".$row['stock_scrapbook']."' name='".number_format($row_scrap,2)."' value='Scrapbook' id='".$row['sku']."-s'>'".$description."' - $ ".number_format($row_scrap,2)." (was $".$original_scrap.")</option>"; 
                    }
                elseif(user_retailer())
                    {
                        $row_scrap = ($price_scrap - $retail_dis);
                        $option_scrap .= "<option class='".$row['stock_scrapbook']."' name='".number_format($row_scrap,2)."' value='Scrapbook' id='".$row['sku']."-s'>".$description." - $ ".number_format($row_scrap,2)." (Retailer discount)</option>"; 
                    }
                else
                    {
                        $row_scrap = $price_scrap;
                        $option_scrap .= "<option class='".$row['stock_scrapbook']."' name='".$row_scrap."' value='Scrapbook' id='".$row['sku']."-s'>".$description." - $ ".$row_scrap."</option>";
                    }
                $scrap_desc = "";
                if($row['item_description'] == "")
                    {
                        $scrap_desc = "<p>Perfect for crafting projects, stationery, decoupage, stamping,
                        scrapbook sketches and layouts. Decoupage Queen scrapbook sets feature
                        24 designs, 12 double sided pages on 65 lb cardstock measuring 12\" x
                        12\". These designs coordinate with our decoupage paper collections to
                        complete your creative vision. Made in the USA.</p>";
                    }
                else
                    {
                        $scrap_desc = $row['item_description'];
                    }
            }

        if($row['type'] == 'Chipboard')
            {
                $retail_dis_chip = ($price_chipboard * 0.2);
                $discount_chipboard = ($price_chipboard * $row['discount']);
                if($discount_chipboard > 0)
                    {
                        $row_chip = ($price_chipboard - $discount_chipboard);
                        $option_chip .= "<option class='".$row['stock_chipboard']."' name='".number_format($row_chip,2)."' value='Chipboard' id='".$row['sku']."-s'>'".$description."' - $ ".number_format($row_chip,2)." (was $".$original_scrap.")</option>"; 
                    }
                elseif(user_retailer())
                    {
                        $row_chip = ($price_chipboard - $retail_dis_chip);
                        $option_chip .= "<option class='".$row['stock_chipboard']."' name='".number_format($row_chip,2)."' value='Chipboard' id='".$row['sku']."-s'>".$description." - $ ".number_format($row_chip,2)." (Retailer discount)</option>"; 
                    }
                else
                    {
                        $row_chip = $price_chipboard;
                        $option_chip .= "<option class='".$row['stock_chipboard']."' name='".$row_chip."' value='Chipboard' id='".$row['sku']."-s'>".$description." - $ ".$row_chip."</option>";
                    }
                $scrap_desc = "";
                if($row['item_description'] == "")
                    {
                        $scrap_desc = "<p>Perfect for crafting projects, stationery, decoupage, stamping,
                        scrapbook sketches and layouts. Decoupage Queen scrapbook sets feature
                        24 designs, 12 double sided pages on 65 lb cardstock measuring 12\" x
                        12\". These designs coordinate with our decoupage paper collections to
                        complete your creative vision. Made in the USA.</p>";
                    }
                else
                    {
                        $scrap_desc = $row['item_description'];
                    }
            }
        
        if($row['a4'] == 'yes')
            {
                if($discount_a4 > 0)
                    {
                        $row['a4'] = ($price_a4 - $discount_a4);
                        $option_a4 .= "<option class='".$row['stock_a4']."' name='".number_format($row['a4'],2)."' value='A4' id='".$row['sku']."A4'>A4 Rice Paper Single Sheet - $ ".number_format($row['a4'],2)." (was $".$original_a4.")</option>"; 
                    }
                else
                    {
                        $row['a4'] = $price_a4;
                        $option_a4 .= "<option class='".$row['stock_a4']."' name='".$row['a4']."' value='A4' id='".$row['sku']."A4'>A4 Rice Paper Single Sheet - $ ".$row['a4']."</option>";
                    }
            }
        else
            {
                $option_a4 = "";
            }

        if($row['a3'] == 'yes')
            {
                if($discount_a3 > 0)
                    {
                        $row['a3'] = ($price_a3 - $discount_a3);
                        $option_a3 .= "<option class='".$row['stock_a3']."' name='".number_format($row['a3'],2)."' value='A3' id='".$row['sku']."A3'>A3 Rice Paper Single Sheet - $ ".number_format($row['a3'],2)." (was $".$original_a3.")</option>";
                    }
                else
                    {
                        $row['a3'] = $price_a3;
                        $option_a3 .= "<option class='".$row['stock_a3']."' name='".$row['a3']."' value='A3' id='".$row['sku']."A3'>A3 Rice Paper Single Sheet - $ ".$row['a3']."</option>";
                    }
            }
        else
            {
                $option_a3 = "";
            }

        if($row['xl'] == 'yes')
            {
                if($discount_xl > 0)
                    {
                        $row['xl'] = ($price_xl - $discount_xl);
                        $option_xl .= "<option class='".$row['stock_xl']."' name='".number_format($row['xl'],2)."' value='XL' id='".$row['sku']."XL'>XL Rice Paper Single Sheet - $ ".number_format($row['xl'],2)." (was $".$original_xl.")</option>";
                       
                    }
                else
                    {
                        $row['xl'] = $price_xl;
                        $option_xl .= "<option class='".$row['stock_xl']."' name='".$row['xl']."' value='XL' id='".$row['sku']."XL'>XL Rice Paper Single Sheet - $ ".$row['xl']."</option>";
                        
                    }
            }
        else
            {
                $option_xl = "";
            }
    }

?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon mobile-h1"><?php echo $description ?></h1>
  </div>
</div>
<div class="container margin-top">
    <div class="row">
        <div class="col-md-3 mb-3">
            <button class="btn btn-info" onclick="goBack()">Continue shopping</button>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            
            <?php
                echo "<div id='gallery' class='gallery' itemscope itemtype='http://schema.org/ImageGallery'>";
            if($row['type'] == 'Scrapbook')
                {
                        $sql = "SELECT 
                                    items.sku,
                                    items.img as image,
                                    scrap_images.id,
                                    scrap_images.img,
                                    scrap_images.sku
                                FROM 
                                    items
                                INNER JOIN
                                    scrap_images
                                ON
                                    items.sku = scrap_images.sku
                                WHERE
                                    scrap_images.sku = '$sku'
                                ";
                        $statement = $db->prepare($sql);
                        $statement ->execute();
                        $result = $statement ->fetchAll();
                        $disp_thumbs = "";
                        foreach($result as $row)
                            {
                                $id = $row['id'];
                                $thumb_sku = $row['sku'];
                                $thumb = $row['img'];
                                $thumb_main = $row['image'];
                                $disp_thumbs .= "<figure itemprop='associatedMedia' itemscope itemtype='http://schema.org/ImageObject'>
                                                    <a id='pswp' href='".IMAGE_URL."".$thumb."' data-width='1200' data-height='900'>
                                                    <img src='".IMAGE_URL."".$thumb."' itemprop='thumbnail' alt='Image description' style='max-width:50%;'>
                                                    </a>
                                                </figure>";
                            }

                        
                        //figure for a more semantic html -->
                        echo "<figure itemprop='associatedMedia' itemscope itemtype='http://schema.org/ImageObject'>
                            <a id='pswp' href='".IMAGE_URL."".$thumb_main."' data-width='1200' data-height='900'>
                                <img src='".IMAGE_URL."".$thumb_main."' itemprop='thumbnail' alt='Image description' style='max-width:50%;'>
                            </a>
                        </figure>";

                        echo $disp_thumbs;
                    
                }
            elseif($row['type'] == 'Chipboard')
                {
                    echo "<figure itemprop='associatedMedia' itemscope itemtype='http://schema.org/ImageObject'>
                            <a id='pswp' href='".IMAGE_URL."".$img."' data-width='1200' data-height='900'>
                                <img src='".IMAGE_URL."".$img."' itemprop='thumbnail' alt='Image description' style='max-width:290%;'>
                            </a>
                        </figure>";
                }
                    echo "</div>";
            ?>
            
        </div>
        
        <div class="col-sm-6">
            <div class='card product-item'>
                <div class='card-body'>
                <h4>Selct size</h4>
                    <select name="range" id="colours" class="form-control price-range">
                        <option>Select an option</option>
                        <?php
                            echo $option_a4;
                            echo $option_a3;
                            echo $option_xl;
                            echo $option_scrap;
                            echo $option_chip;
                        ?>
                    </select>
                    <div id="show_colours">
                    </div><br>
                    <strong id="product_name" style='text-align: center; color: #007bff;' class="product-title"><h5><?php echo $description ?></h5></strong>
                    <p>In stock:<input style="border: 0;" type='text' name='stock' value='' id='stock' readonly/></p>
                    <input type="text" name="quantity" id="quantity" class="form-control" value="1" />
                    <input type="hidden" name="hidden_image" id="image" value="<?php echo $row['img']; ?>" />
                    <input type="hidden" name="hidden_name" id="name" value="<?php echo $description; ?>" />
                    <input type='hidden' name='hidden_size' value='' id='size' /></span>
                    <span style="font-weight: 800;">$<input style="border: 0;" type="text" name="hidden_price" id="price" value="0.00" />
                    <input type='hidden' name='sku' value="<?php echo $row['sku'] ?>" id='sku' />
                    <input type="button" name="add_to_cart" id="<?php echo $row['sku'] ?>" style="margin-top:5px;" class="btn btn-info form-control add_to_cart" value="Add to Cart" />
                    <?php
                    if(logged_in() || logged_in_fb() || logged_in_google())
                        {
                            $email = "";
                            $email = global_logged_in($email);

                            if(wishlist($row['sku']))
                                {
                                    echo "<input type='button' name='save' class='btn btn-info mt-3' value='Remove from wishlist' id='del_wish'>
                                            <input type='hidden' name='email' id='email' value='".$email."'/>
                                            <input type='hidden' name='sku' id='id_sku' value='".$row['sku']."'/>
                                            <input type='hidden' name='description' id='description' value='".$description."'/>";
                                }
                            else
                                {
                                    echo "<input type='button' name='save' class='btn btn-info mt-3' value='Add to wishlist' id='btn_wish'>
                                            <input type='hidden' name='email' id='email' value='".$email."'/>
                                            <input type='hidden' name='sku' id='id_sku' value='".$row['sku']."'/>
                                            <input type='hidden' name='description' id='description' value='".$description."'/>";
                                }
                        }
                    ?>
                </div>
            </div><br>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-6">
            <?php
                echo $scrap_desc;
            ?>
        </div>
    </div>
</div><br>
    
</div>

<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
      <!-- Background of PhotoSwipe. 
           It's a separate element as animating opacity is faster than rgba(). -->
      <div class="pswp__bg"></div>
      <!-- Slides wrapper with overflow:hidden. -->
      <div class="pswp__scroll-wrap">
          <!-- Container that holds slides. 
              PhotoSwipe keeps only 3 of them in the DOM to save memory.
              Don't modify these 3 pswp__item elements, data is added later on. -->
          <div class="pswp__container">
              <div class="pswp__item"></div>
              <div class="pswp__item"></div>
              <div class="pswp__item"></div>
          </div>
          <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
          <div class="pswp__ui pswp__ui--hidden">
              <div class="pswp__top-bar">
                  <!--  Controls are self-explanatory. Order can be changed. -->
                  <div class="pswp__counter"></div>
                  <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                  <button class="pswp__button pswp__button--share" title="Share"></button>
                  <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                  <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                  <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                  <!-- element will get class pswp__preloader--active when preloader is running -->
                  <div class="pswp__preloader">
                      <div class="pswp__preloader__icn">
                        <div class="pswp__preloader__cut">
                          <div class="pswp__preloader__donut"></div>
                        </div>
                      </div>
                  </div>
              </div>
              <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                  <div class="pswp__share-tooltip"></div> 
              </div>
              <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
              </button>
              <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
              </button>
              <div class="pswp__caption">
                  <div class="pswp__caption__center"></div>
              </div>
          </div>
    </div>
</div>
<?php

include '../includes/footer.php';
?>

<script> 
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

$('select').on('change', function() {
    var product_id = $(this).attr('id');
    var optionSelected = $("option:selected").attr('class');
    var hiddenField = $('#stock')
    hiddenField.val(optionSelected);
    console.log("new value: " + hiddenField.val());
});

$('select').on('change', function() {
    var product_id = $(this).attr('id');
    var optionSelected = $("option:selected").attr('name');
    var hiddenField = $('#price')
    hiddenField.val(optionSelected);
    console.log("new value: " + hiddenField.val());
});

$('select').on('change', function() {
    var optionSelected = $("option:selected").attr('value');
    var hiddenField = $('#size')
    hiddenField.val(optionSelected);

    //$("#size").val(optionSelected);
    console.log("new value: " + hiddenField.val());
});

$('select').on('change', function() {
    var optionSelected = $("option:selected").attr('id');
    var button = $('#sku')
    button.val(optionSelected);

    //$("#sku").val(optionSelected);
    console.log("new value: " + button.val());
});
</script>

<script>
$(document).ready(function() {
	$('#btn_wish').on('click', function() {
        var email = $('#email').val();
		var description = $('#description').val();
        var id_sku = $('#id_sku').val();
		if(email != ""){
			$.ajax({
				url: "../includes/insert-wishlist",
				type: "POST",
				data: {
                    email: email,
					description: description,
                    id_sku: id_sku				
				},
				cache: false,
				success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						alert("Item added to wishlist!");
                        location.reload();				
					}
					else if(dataResult.statusCode==201){
					   alert("Error occured!");
					}
                    else if(dataResult.statusCode==202){
					   alert("Item already in wishlist!");
					}
					
				}
			});
		}
	});
});


$(document).ready(function() {
	$('#del_wish').on('click', function() {
        var email = $('#email').val();
		var description = $('#description').val();
        var id_sku = $('#id_sku').val();

        $.ajax({
            url: "../includes/delete-wish",
            type: "POST",
            data: {
                email: email,
                description: description,
                id_sku: id_sku				
            },
            cache: false,
            success: function(dataResult){
                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode==200){
                    alert("Item removed from wishlist!");
                    location.reload();				
                }
                else if(dataResult.statusCode==201){
                    alert("Error occured!");
                }
                
            }
        });
		
	});
});
</script>

<script>window.twttr = (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0],
    t = window.twttr || {};
  if (d.getElementById(id)) return t;
  js = d.createElement(s);
  js.id = id;
  js.src = "https://platform.twitter.com/widgets.js";
  fjs.parentNode.insertBefore(js, fjs);

  t._e = [];
  t.ready = function(f) {
    t._e.push(f);
  };

  return t;
}(document, "script", "twitter-wjs"));
</script>
<script async defer src="//assets.pinterest.com/js/pinit.js"></script>