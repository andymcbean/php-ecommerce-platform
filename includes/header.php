<?php
$deny_ips = array(
  '5.188.84.119',
  '5.188.84.115'
 );

$ip = isset($_SERVER['REMOTE_ADDR']) ? trim($_SERVER['REMOTE_ADDR']) : '';
// search current IP in $deny_ips array
if ((array_search($ip, $deny_ips))!== FALSE) {
  // address is blocked:
  echo 'Your blocked!';
  exit;
 }

session_start();
include '../includes/connect.php';
include '../includes/functions.php';
include '../includes/social-login.php';
//include '../includes/constants.php';
include 'meta-data.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
  <title><?php echo $title ?></title>
  <meta name='description' content='<?php echo $description ?>'>
  <meta name='keywords' content='<?php echo $keywords ?>'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge' />
  <link rel='shortcut icon' href='../favicon.ico'>
  <!-- Google font-->
  <link href='https://fonts.googleapis.com/css?family=Catamaran:200,300,400&display=swap' rel='stylesheet'>
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
  <!--Font awesome-->
  <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.8.2/css/all.css' integrity='sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay' crossorigin='anonymous'>
   <!--Styles-->
  <link rel='stylesheet' type='text/css' href='https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css' />
  <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
  <link rel='stylesheet' type='text/css' href='../styles/style.css'>
  <link rel="stylesheet" href="../styles/image-magnifier.css">
  <!-- Import PhotoSwipe Styles -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/default-skin/default-skin.css">
  <!--JS-->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js' integrity='sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1' crossorigin='anonymous'></script>
  <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js' integrity='sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM' crossorigin='anonymous'></script>
  <?php
    if($_SERVER['REQUEST_URI'] == "/designers/")
      {
        echo "";
      }
    else
      {
        echo "<script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=600715d5e15b2000184b7036&product=sticky-share-buttons' async='async'></script>";
      }
  ?>
  
  <script src="../js/filters.js"></script>
  <!-- Cookie Consent by https://www.TermsFeed.com -->
  <script type="text/javascript" src="//www.termsfeed.com/public/cookie-consent/3.1.0/cookie-consent.js"></script>
  <script src="../js/js.js"></script>
  <script src="../js/fb.js"></script>
  <!--<script src="../js/cart.js"></script>-->
  <script src="../js/cart-sc.js"></script>
  <script src="../js/logout.js"></script>
  <script src="../js/jquery.TableCSVExport.js"></script>
  <script src='../js/select2.min.js' type='text/javascript'></script>
  <script src="../js/image-magnifier.js"></script>
  <script src="../js/slideshow.js"></script>
  <script src="https://apis.google.com/js/api.js"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script src="https://cdn.tiny.cloud/1/a67ukybbfrudtzeb7k2u52qwt4lusckfpmzuoqt6crdoiuiq/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <?php $sm_share = ""; echo $sm_share ?>
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
<body data-spy="scroll">
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v9.0&appId=209765480590396&autoLogAppEvents=1" nonce="jfQxSvvC"></script>

<nav class="navbar navbar-expand-md navbar-dark">
    <button class="navbar-toggler navbar-toggler-right collapsed" type="button" data-toggle="collapse" data-target="#collapsibleNavbar" aria-expanded="false">
        <span> </span>
        <span> </span>
        <span> </span>
    </button>
        <a id="cart-popover-a" class="btn hide-cart" href="../new-cart/shopping-cart"><i class="fas fa-shopping-bag"></i> 
            <span class="badge"><?php if(isset($_SESSION["shopping_cart"])) { echo count($_SESSION["shopping_cart"]); } else { echo '0';}?></span>
        </a>
    
  <div class="navbar-collapse collapse" id="collapsibleNavbar" style="">
        <a href="../" class="navbar-brand">
          <img src="../images/nav-logo.png" width="90" alt="crown logo" class="d-inline-block align-middle mb-3">
          <span class="nav-icon">Decoupage Queen</span>
        </a>
        <ul class="nav navbar-nav ml-auto mb-3">
        <?php
            $admin = "";
            if(user_admin())
                {
        ?>
            <li class="nav-item">
              <a data-toggle="tooltip" data-placement="bottom" title="Admin Home" class="nav-link" href="../admin/"> <i class="fas fa-cogs"></i></a>
            </li>
        <?php
                }
        ?>
            <li class="nav-item">
              <a data-toggle="tooltip" data-placement="bottom" title="Home" class="nav-link" href="../"> <i class="fas fa-home"></i></a>
            </li>
            <li class='nav-item dropdown'>
            <a class='nav-link dropdown-toggle' href='#' id='navbarDropdownLoggedinLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Products</a>
                <div class='dropdown-menu' aria-labelledby='navbarDropdownLoggedinLink'>
                  <a class="nav-link" href="../decoupage/">Decoupage Paper</a>
                  <a class="nav-link" href="../scrapbooks/">Scrapbooks</a>
                  <?php
                    if(user_retailer())
                      {
                        echo "<a class='nav-link' href='../retail-only/'>Retailer only</a>";
                      }
                    if(retailer_us())
                      {
                        echo "<a class='nav-link' href='../us-retail/'>US Retailers</a>";
                      }
                  ?>
                </div>
            </li>
            <li class="nav-item dropdown">
              <a class='nav-link' href='../retailers/'>Retailers</a>
            </li>
            <?php
            if(logged_in() || logged_in_fb() || logged_in_google())
              {
                  echo "";
              }
            else
              {
                  echo "<li class='nav-item'>
                          <a class='nav-link' href='../register'>Register</a>
                        </li>
                        <li class='nav-item'>
                          <a class='nav-link' href='../login'>Log in</a>
                        </li>";
              }
            ?>
            <li class='nav-item dropdown'>
            <a class='nav-link dropdown-toggle' href='#' id='navbarDropdownLoggedinLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>More</a>
                <div class='dropdown-menu' aria-labelledby='navbarDropdownLoggedinLink'>
                  <a class="nav-link" href="../designers/">Design Team</a>
                  <a class="nav-link" href="../tutorials">Tutorials</a>
                  <a class='nav-link' href='../contact/'>Contact</a>
                  <a class='nav-link' href='../faq/'>FAQ</a>
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
                <a class="nav-link" href="../new-cart/shopping-cart"><i class="fas fa-shopping-bag"></i> 
                    <span class="badge"><?php if(isset($_SESSION["shopping_cart"])) { echo count($_SESSION["shopping_cart"]); } else { echo '0';}?></span>
                </a>
            </li>
            <?php
            if(logged_in() || logged_in_fb() || logged_in_google())
                {
                  $admin = "";
                  if(user_admin())
                      {
                          $admin .= "<a class='nav-link' href='../admin/'>Management</a>";
                      }

                  echo "<li class='nav-item dropdown'><a class='nav-link dropdown-toggle' href='#' id='navbarDropdownLoggedinLink1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  Account
                        </a>
                        <div class='dropdown-menu' aria-labelledby='navbarDropdownLoggedinLink1'>
                          $admin
                          <a class='nav-link' href='../users/'>Dashboard</a>
                          <a class='nav-link' href='../includes/logout'>Logout</a>
                        </div></li>";
                }
                ?>
            
          </ul>
      
  </div>
</nav>
<!--<div id="loading"></div>-->
<div id="popover_content_wrapper" class="hide">
    <span id="cart_details"></span>
    <div class="col-12">
     
     <a href="#" class="btn btn-default" id="clear_cart">
     <span><i class="fas fa-trash"></i></span> Clear
     </a>
    </div>
</div>
<div class="modal" id="inCart">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Item added to cart</h4>
        <button type="button" class="close" onClick="window.location.reload();">&times;</button>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <a href="../new-cart/shopping-cart" class="btn btn-info" style="float: left;">View cart?</a>
        <button type="button" class="btn btn-info" onClick="window.history.back();">Continue shopping?</button>
      </div>

    </div>
  </div>
</div>
<!--<script>
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
</script>-->
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
<!--<button onClick="window.location.reload();">Refresh Page</button>-->
