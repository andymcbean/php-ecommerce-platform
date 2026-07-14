<?php
session_start();
include 'includes/connect.php';
include 'includes/constants.php';
include 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
  <title>DecoupageQueen.com - Home</title>
  <meta name='description' content='Welcome to Decoupage Queen'>
  <meta name='keywords' content='decoupage queen, decoupage, rice paper, creativity'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge' />
  <link rel='shortcut icon' href='favicon.ico'>
  <!-- Google font-->
  <link href='https://fonts.googleapis.com/css?family=Catamaran:200,300,400&display=swap' rel='stylesheet'>
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
  <!--Font awesome-->
  <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.8.2/css/all.css' integrity='sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay' crossorigin='anonymous'>
   <!--Styles-->
  <link rel='stylesheet' type='text/css' href='https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css' />
  <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
  <link rel='stylesheet' type='text/css' href='styles/style.css'>
  <!--JS-->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js' integrity='sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1' crossorigin='anonymous'></script>
  <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js' integrity='sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM' crossorigin='anonymous'></script>
  <script src="/js/cart-sc.js"></script>
  <script src="/js/logout.js"></script>
  <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=600715d5e15b2000184b7036&product=sticky-share-buttons' async='async'></script>
  <script id="mcjs">!function(c,h,i,m,p){m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)}(document,"script","https://chimpstatic.com/mcjs-connected/js/users/07a7d1e846b66c48a1c758efc/f8e42ba1da5529730bb2d3a09.js");</script>
  <!-- Cookie Consent by https://www.TermsFeed.com -->
  <script type="text/javascript" src="//www.termsfeed.com/public/cookie-consent/3.1.0/cookie-consent.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
  <script type="text/javascript" src="js/slider.js"></script>
  <script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function () {
  cookieconsent.run({"notice_banner_type":"interstitial","consent_type":"express","palette":"light","language":"en","website_name":"dqdev.co.uk","cookies_policy_url":"https://dqdev.co.uk/policies/cookie"});
  });
  </script>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <!--<script async src="https://www.googletagmanager.com/gtag/js?id=UA-154973567-2"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-154973567-2');
  </script>-->
</head>
<?php
if(!empty($_SESSION["cart"])){
  $count = count($_SESSION["cart"]);
} else {
  $count = 0;
} 
?>
<body>

<nav class="navbar navbar-expand-md navbar-dark">
    <button class="navbar-toggler navbar-toggler-right collapsed" type="button" data-toggle="collapse" data-target="#collapsibleNavbar" aria-expanded="false">
        <span> </span>
        <span> </span>
        <span> </span>
    </button>
    <a id="cart-popover-a" class="btn hide-cart" href="/new-cart/shopping-cart"><i class="fas fa-shopping-bag"></i> 
        <span class="badge"><?php if(isset($_SESSION["shopping_cart"])) { echo count($_SESSION["shopping_cart"]); } else { echo '0';}?></span>
    </a>
  <div class="navbar-collapse collapse" id="collapsibleNavbar" style="">
        <a href="#" class="navbar-brand">
          <img src="images/nav-logo.png" width="90" alt="/" class="d-inline-block align-middle mb-3">
          <span class="nav-icon">Decoupage Queen</span>
        </a>
        <ul class="nav navbar-nav ml-auto mb-3">
            <li class="nav-item">
              <a class="nav-link" href="../"> <i class="fas fa-home"></i></a>
            </li>
            <li class='nav-item dropdown'>
            <a class='nav-link dropdown-toggle' href='#' id='navbarDropdownLoggedinLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Products</a>
                <div class='dropdown-menu' aria-labelledby='navbarDropdownLoggedinLink'>
                  <a class="nav-link" href="/decoupage/">Decoupage Paper</a>
                  <a class="nav-link" href="/scrapbooks/">Scrapbooks</a>
                  <?php
                    if(user_retailer())
                      {
                        echo "<a class='nav-link' href='/retail-only/'>Retailer only</a>";
                      }
                    if(retailer_us())
                      {
                        echo "<a class='nav-link' href='/us-retail/'>US Retailers</a>";
                      }
                  ?>
                </div>
            </li>
            <li class="nav-item dropdown">
              <a class='nav-link' href='/retailers/'>Retailers</a>
            </li>
            <?php
            if(logged_in())
              {
                  echo "";
              }
            else
              {
                  echo "<li class='nav-item'>
                          <a class='nav-link' href='register'>Register</a>
                        </li>
                        <li class='nav-item'>
                          <a class='nav-link' href='login'>Log in</a>
                        </li>";
              }
            ?>
            <li class='nav-item dropdown'>
            <a class='nav-link dropdown-toggle' href='#' id='navbarDropdownLoggedinLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>More</a>
                <div class='dropdown-menu' aria-labelledby='navbarDropdownLoggedinLink'>
                  <a class="nav-link" href="/designers/">Design Team</a>
                  <a class="nav-link" href="/tutorials">Tutorials</a>
                  <a class='nav-link' href="/contact/">Contact</a>
                  <a class='nav-link' href="/faq/">FAQ</a>
                </div>
            </li>
            <li class="nav-item dropdown">
              <a target="_blank" class='nav-link' href='https://decoupagequeen.teachable.com/'>Academy</a>
            </li>
            
            <!--<li class="nav-item">
            <a id="cart-popover" class="btn nav-link" data-placement="bottom" title="Shopping Cart">
                <span><i class="fas fa-shopping-bag"></i></span>
                <span class="badge"></span>
                <span class="total_price">$ 0.00</span>
            </a>
            </li>-->
            
            <li class="nav-item">
                <a class="nav-link" href="/new-cart/shopping-cart"><i class="fas fa-shopping-bag"></i> 
                    <span class="badge"><?php if(isset($_SESSION["shopping_cart"])) { echo count($_SESSION["shopping_cart"]); } else { echo '0';}?></span>
                </a>
            </li>
            <?php
            if(logged_in())
                {
                  $admin = "";
                  if(user_admin())
                      {
                          $admin .= "<a class='nav-link' href='../admin/'>Management</a>";
                      }

                  echo "<li class='nav-item dropdown'><a class='nav-link dropdown-toggle' href='#' id='navbarDropdownLoggedinLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  Management
                        </a>
                        <div class='dropdown-menu' aria-labelledby='navbarDropdownLoggedinLink'>
                          $admin
                          <a class='nav-link' href='../users/'>Dashboard</a>
                          <a class='nav-link' href='../includes/logout'>Logout</a>
                        </div></li>";
                }
                ?>
          </ul>
      
  </div>
</nav>
<div id="popover_content_wrapper" class="hide">
    <span id="cart_details"></span>
    <div class="col-12">
     
     <a href="#" class="btn btn-default" id="clear_cart">
     <span><i class="fas fa-trash"></i></span> Clear
     </a>
    </div>
</div>
<script>
$('#cart-popover').popover({
		html : true,
        container: 'body',
        content:function(){
        	return $('#popover_content_wrapper').toggleClass("hide");
        }
	});
</script>
<script>
$('#cart-popover-a').popover({
		html : true,
        container: 'body',
        content:function(){
        	return $('#popover_content_wrapper').toggleClass("hide");
        }
	});
</script>
<?php 
if(isset($_SESSION['email']) OR isset($_SESSION['user_email_address']))
    {
?>  


<div class="modal" id="timeModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Inactivity</h4>
        <button type="button" class="close" onClick="window.location.reload();">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <script>start_countdown()</script>
        <p id="countdown"></p>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <a href="../includes/logout" class="btn btn-danger" style="float: left;">Logout</a>
        <button type="button" class="btn btn-info" onClick="window.location.reload();">Stay logged in?</button>
      </div>

    </div>
  </div>
</div>
<?php
    }
?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Decoupage Queen</h1>
    <h4>Inspiring Creativity</h4>
  </div>
</div>
<section>
    <div class="container margin-top">
      <div class="row text-center">
        <div class="col-12">
          <h2>Welcome to Decoupage Queen</h2>
          <p>Our number one priority is to inspire creativity for artists and crafters everywhere.</p>
          <p>We offer the highest quality and most beautiful paper for your creative projects in three sizes.</p>
          <p>Small - A4 Rice Paper is for smaller craft items</p>
          <p>*New* Medium - A3 Rice Paper is for craft items and smaller pieces of furniture</p>
          <p>XL Furniture Size - 20" x 30" 18 lb tissue paper perfect for furniture</p>
        </div>
      </div>
    </div>
</section>

<br>

<section>
  <div class="container">
    <div class="row text-center">
      <div class="col-12">
        <div class="items">
          <?php echo homepage_items(); ?>
        </div>
        
      </div>
      <div class="col-12 mt-3">
        <a href="/decoupage/" class="btn btn-info">Browse our designs <i class="fas fa-chevron-circle-right"></i></a>
      </div>
  </div>
  </div>
</section>

<br>

<section>
  <div class="container">
    <div class="row text-center">
      <div class="col-12">
      <div class="powr-social-feed" id="92b55e72_1617882325"></div><script src="https://www.powr.io/powr.js?platform=html"></script>
      </div>
    </div>
  </div>
</section>

<footer class="bg-light footer-fixed">
    <div class="container">
        <div class="row text-center">
            <div class="col-sm-4 col-12" style="margin-top: 20px;">
                <h5>Decoupage Queen</h5>
                <h5><span><a href="login"> Login <i class="fas fa-sign-in-alt"></i></a></span></h5>
                <h5><span><a href="register"> Register <i class="fas fa-user-plus"></i></a></span></h5>
            </div>
            <div class="col-sm-4 col-12" style="margin-top: 20px;">
                <h5>Policies</h5>
                <h5><span><a href="/policies/privacy"> Privacy Policy <i class="fas fa-file-alt"></i></a></span></h5>
                <h5><span><a href="/policies/cookie"> Cookie policy <i class="fas fa-cookie"></i></a></span></h5>
            </div>
            <div class="col-sm-4 col-12" style="margin-top: 20px;">
                <h5>Follow</h5>
                <a href="https://www.facebook.com/decoupagequeenpaper" target="_blank"><i class="fab fa-facebook-square fa-2x"></i></a>
                <a href="https://www.instagram.com/decoupagequeen/" target="_blank"><i class="fab fa-instagram fa-2x"></i></a>
                <a href="https://www.youtube.com/channel/UCjr838JpvmdV5VFm7emQHVg" target="_blank"><i class="fab fa-youtube-square fa-2x"></i></a><br>
                <img src="/images/stripe.png" class="img-fluid" style="max-width: 70%;"/>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top: 20px;">
        <div class="row d-flex justify-content-center">
            <p>© DecoupageQueen.com <?php echo date("Y") ?> All rights reserved</p>
        </div>
    </div>
</footer>
<div class="d-flex justify-content-center kd">
    <p style="margin-top: 10px; color: #000; font-size: 12px;">Developed by <a style="color: #000;" href="https://kingdomdesign.com" target="_blank">KingdomDesign.com</a></p>
</div>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
  /*var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
  (function(){
  var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
  s1.async=true;
  s1.src='https://embed.tawk.to/60781e66f7ce1827093ab0fa/1f3ajdgb5';
  s1.charset='UTF-8';
  s1.setAttribute('crossorigin','*');
  s0.parentNode.insertBefore(s1,s0);
  })();*/
</script>
<!--End of Tawk.to Script-->

</body>
</html>
