<?php
include '../includes/header.php';
include '../includes/constants.php';
if(retailer_us())
    {

if(isset($_GET['sku']))
    {
        $sku = $_GET['sku'];
    }

$sql = "SELECT 
            id,
            sku,
            description,
            a4,
            a3,
            xl,
            img,
            price_a4, 
            price_a3,
            price_xl,
            price_scrap,
            discount,
            stock_a4,
            stock_a3,
            stock_xl,
            active,
            type
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
$size = "";
$stock_count = "";
foreach($result as $row)
    {
        $original_a4 =  '<s>'.number_format($row['price_a4'],2).'</s>';
        $original_a3 =  '<s>'.number_format($row['price_a3'],2).'</s>';
        $original_xl =  '<s>'.number_format($row['price_xl'],2).'</s>';
        $price_a4 = $row['price_a4'];
        $price_a3 = $row['price_a3'];
        $price_xl = $row['price_xl'];
        $id = $row['id'];
        $img = $row['img'];
        $discount_a4 = ($price_a4 * $row['discount']);
        $discount_a3 = ($price_a3 * $row['discount']);
        $discount_xl = ($price_xl * $row['discount']);
        
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

if(user_retailer())
    {
        $retailer  = "yes";
    }
else
    {
        $retailer = "no";
    }
?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon"><?php echo $row['description'] ?></h1>
  </div>
</div>

<div class="container margin-top">
    <div class="row">
        <div class="col-md-3 mb-3">
            <button class="btn btn-info" onclick="goBack()">Continue shopping</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <img type="button" src="<?php echo IMAGE_URL ?><?php echo $img ?>" alt="" class="image-fluid" style="max-width: 75%;" data-toggle="modal" data-target="#myModal">
            <br>
            <?php
                if($row['type'] == 'Scrapbook')
                    {
                        echo "Scrap image gallery here!";
                    }
                else
                    {
                        echo "";
                    }
            ?>

            <br><br>
            <p>Decoupage Queen papers are designed in the USA and come in three sizes. All designs are availble in A4 size, and select designs are offered in A3 and XL.</p>
            <p>A4 - 8.3" x 11.7"</p>
            <p>A3 - 11.7" x 16.5"</p>
            <p>XL - 20" x 30"</p>
            <p>The A4 and A3 sizes are printed on rice paper in Italy. We  print on the highest quality paper available on the market today. Rice paper has been used for decades in arts and crafts with decoupage. Torn edges will blend beautifully into a background. You should start with a white or light colored background to make the colors appear brighter and more vivid. Our XL paper is printed in the US and is 18 lb tissue paper and is the perfect weight and thickness for decoupage on furniture.</p>
        </div>
        
        <div class="col-md-6">
            <div class='card product-item'>
                <div class='card-body'>
                <h4>Selct size</h4>
                    <select name="range" id="colours" class="form-control price-range">
                        <option>Select an option</option>
                        <?php
                            echo $option_a4;
                            echo $option_a3;
                            echo $option_xl;
                        ?>
                    </select>
                    <div id="show_colours">
                    </div><br>
                    <strong id="product_name" style='text-align: center; color: #007bff;' class="product-title"><h5><?php echo $row['description'] ?></h5></strong>
                    <p>In stock:<input style="border: 0;" type='text' name='stock' value='' id='stock' readonly/></p>
                    <input type="text" name="quantity" id="quantity" class="form-control" value="1" />
                    <input type="hidden" name="hidden_image" id="image" value="<?php echo $row['img']; ?>" />
                    <input type="hidden" name="hidden_name" id="name" value="<?php echo $row['description']; ?>" />
                    <input type='hidden' name='hidden_size' value='' id='size' /></span>
                    <span style="font-weight: 800;">$<input style="border: 0;" type="text" name="hidden_price" id="price" value="0.00" />
                    <input type='hidden' name='sku' value="<?php echo $row['sku'] ?>" id='sku' />
                    <input type='hidden' name="is_retailer" value="<?=$retailer?>" id="is_retailer"  />
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
                                            <input type='hidden' name='description' id='description' value='".$row['description']."'/>";
                                }
                            else
                                {
                                    echo "<input type='button' name='save' class='btn btn-info mt-3' value='Add to wishlist' id='btn_wish'>
                                            <input type='hidden' name='email' id='email' value='".$email."'/>
                                            <input type='hidden' name='sku' id='id_sku' value='".$row['sku']."'/>
                                            <input type='hidden' name='description' id='description' value='".$row['description']."'/>";
                                }
                        }
                    ?>
                </div>
            </div><br>
                <?php //$url = "https://dqdev.co.uk/decoupage/details?sku=".$row['sku'].""; ?>
                        
                <!--<div class="fb-share-button" data-href="https://dqdev.co.uk/decoupage/details?sku=<?php //echo $row['sku'] ?>" data-layout="button_count" data-size="small"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdqdev.co.uk%2Fproducts%2Fdetails%3Fsku%3Dangelandtree&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Share</a>
                </div>

                <a data-pin-do="buttonBookmark" data-pin-tall="true" data-pin-round="true" href="https://www.pinterest.com/pin/create/button/"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_round_red_32.png" /></a>
                
                <a class="twitter-share-button" href="https://twitter.com/intent/tweet?url=<?//=urlencode($url)?>" data-size="">Tweet</a>-->
                

                <!--<i class="fab fa-facebook"><div class="button share-button facebook-share-button" data-image="<?php echo IMAGE_URL ?><?php echo $img ?>" data-url="https://dqdev.co.uk/decoupage/details?sku=<?=$row['sku']?>"></div></i>
                
                <div class="button share-button twitter-share-button">tweet</div>-->
        </div>
    </div>
</div><br>
<div class="modal" id="myModal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content details-modal-content">

      <!-- Modal Header -->
      <div class="modal-header modal-border">
        <button type="button" class="close" data-dismiss="modal" style="color: #fff; font-size: 40px;">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body mx-auto">
        <div class="img-magnifier-container">
            <img src="<?php echo IMAGE_URL ?><?php echo $img ?>" alt="" class="image-fluid" id="myimage">
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer modal-border">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<?php
    }
else
    {
        echo "<div class='alert alert-danger'>You do not have permission to access this page</div>";
    }
include '../includes/footer.php';
?>
<script>
/* Initiate Magnify Function
with the id of the image, and the strength of the magnifier glass:*/
magnify("myimage", 3);
</script>
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
<script>
function magnify(imgID, zoom) {
  var img, glass, w, h, bw;
  img = document.getElementById(imgID);
  /*create magnifier glass:*/
  glass = document.createElement("DIV");
  glass.setAttribute("class", "img-magnifier-glass");
  /*insert magnifier glass:*/
  img.parentElement.insertBefore(glass, img);
  /*set background properties for the magnifier glass:*/
  glass.style.backgroundImage = "url('" + img.src + "')";
  glass.style.backgroundRepeat = "no-repeat";
  glass.style.backgroundSize = (img.width * zoom) + "px " + (img.height * zoom) + "px";
  bw = 3;
  w = glass.offsetWidth / 2;
  h = glass.offsetHeight / 2;
  /*execute a function when someone moves the magnifier glass over the image:*/
  glass.addEventListener("mousemove", moveMagnifier);
  img.addEventListener("mousemove", moveMagnifier);
  /*and also for touch screens:*/
  glass.addEventListener("touchmove", moveMagnifier);
  img.addEventListener("touchmove", moveMagnifier);
  function moveMagnifier(e) {
    var pos, x, y;
    /*prevent any other actions that may occur when moving over the image*/
    e.preventDefault();
    /*get the cursor's x and y positions:*/
    pos = getCursorPos(e);
    x = pos.x;
    y = pos.y;
    /*prevent the magnifier glass from being positioned outside the image:*/
    if (x > img.width - (w / zoom)) {x = img.width - (w / zoom);}
    if (x < w / zoom) {x = w / zoom;}
    if (y > img.height - (h / zoom)) {y = img.height - (h / zoom);}
    if (y < h / zoom) {y = h / zoom;}
    /*set the position of the magnifier glass:*/
    glass.style.left = (x - w) + "px";
    glass.style.top = (y - h) + "px";
    /*display what the magnifier glass "sees":*/
    glass.style.backgroundPosition = "-" + ((x * zoom) - w + bw) + "px -" + ((y * zoom) - h + bw) + "px";
  }
  function getCursorPos(e) {
    var a, x = 0, y = 0;
    e = e || window.event;
    /*get the x and y positions of the image:*/
    a = img.getBoundingClientRect();
    /*calculate the cursor's x and y coordinates, relative to the image:*/
    x = e.pageX - a.left;
    y = e.pageY - a.top;
    /*consider any page scrolling:*/
    x = x - window.pageXOffset;
    y = y - window.pageYOffset;
    return {x : x, y : y};
  }
}
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