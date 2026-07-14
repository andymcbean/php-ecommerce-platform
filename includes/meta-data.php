<?php
$page = $_SERVER['REQUEST_URI'];

if(isset($_GET['sku']))
    {
        $sku = $_GET['sku'];   
    }

if(isset($_GET['order']))
    {
        $order = $_GET['order'];   
    }
else
    {
      $order = "";
    }
if(isset($sku) && $page == "/decoupage/details?sku=$sku")
    {
      $admin_class = 'active';
      $title = "Decoupage Queen - $sku";
      $description = "One of Decoupage Queens unique designs on rice paper. $sku";
      $keywords = "Decoupage Queen, $sku, rice paper";
      $sm_share = "<script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=#property=600715d5e15b2000184b7036&product=custom-share-buttons' async='async'></script>";
    }

if($page == "/decoupage/")
    {
      $admin_class = 'active';
      $title = "Decoupage Queen - All products";
      $description = "All of Decoupage Queens unique designs on rice paper.";
      $keywords = "Decoupage Queen, rice paper";
      $sm_share = "<script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=#property=600715d5e15b2000184b7036&product=custom-share-buttons' async='async'></script>";
    }  
if($page == "/retail-only/")
    {
      $admin_class = 'active';
      $title = "Decoupage Queen - Retailer exclusive products";
      $description = "All of Decoupage Queens unique designs on rice paper.";
      $keywords = "Decoupage Queen, rice paper";
      $sm_share = "<script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=#property=600715d5e15b2000184b7036&product=custom-share-buttons' async='async'></script>";
    } 
if($page == "/us-retail/")
    {
      $admin_class = 'active';
      $title = "Decoupage Queen - US Retailer exclusive products";
      $description = "All of Decoupage Queens unique designs on rice paper.";
      $keywords = "Decoupage Queen, rice paper";
      $sm_share = "<script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=#property=600715d5e15b2000184b7036&product=custom-share-buttons' async='async'></script>";
    }
if(isset($sku) && $page == "/us-retail/details?sku=$sku")
    {
      $admin_class = 'active';
      $title = "Decoupage Queen - $sku";
      $description = "One of Decoupage Queens unique designs on rice paper. $sku";
      $keywords = "Decoupage Queen, $sku, rice paper";
      $sm_share = "<script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=#property=600715d5e15b2000184b7036&product=custom-share-buttons' async='async'></script>";
    }
if(isset($sku) && $page == "/retail-only/details?sku=$sku")
    {
      $admin_class = 'active';
      $title = "Decoupage Queen - $sku";
      $description = "One of Decoupage Queens unique designs on rice paper. $sku";
      $keywords = "Decoupage Queen, $sku, rice paper";
      $sm_share = "<script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=#property=600715d5e15b2000184b7036&product=custom-share-buttons' async='async'></script>";
    }
if($page == "/new-cart/shopping-cart")
    {
      $admin_class = 'active';
      $title = "Decoupage Queen - Shopping cart";
      $description = "Shopping cart.";
      $keywords = "Decoupage Queen, shopping cart";
    } 
if(isset($sku) && $page == "/scrapbooks/details?sku=$sku")
    {
      $admin_class = 'active';
      $title = "Decoupage Queen - $sku";
      $description = "One of Decoupage Queens unique designs on scrapbook. $sku";
      $keywords = "Decoupage Queen, $sku, rice paper, scrapbook";
      $sm_share = "<script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=#property=600715d5e15b2000184b7036&product=custom-share-buttons' async='async'></script>";
    }

if($page == '/contact/')
    {
      $contact_class = 'active';
      $admin_class = "";
      $title = "Decoupage Queen - Contact";
      $description = "Contact Decoupage Queen today. Contact us via email or by using the form on this page.";
      $keywords = "Decoupage Queen, contact, contact us, contact Decoupage Queen today";
      $sm_share = "";
    }

if($page == '/retailers/')
    {
      $laser_class = 'active';
      $admin_class = "";
      $title = "Decoupage Queen - Retailers";
      $description = "Decoupage Queen retailers.";
      $keywords = "Retailers, Decoupage Queen";
      $sm_share = "";
    }

if($page == '/designers/')
    {
      $admin_class = "";
      $title = "Decoupage Queen - Design Team";
      $description = "Decoupage Queen design team.";
      $keywords = "Designers, Decoupage Queen";
      $sm_share = "";
    }

if($page == '/decoupage/')
    {
      $laser_class = 'active';
      $admin_class = "";
      $title = "Decoupage Queen - Rice paper designs";
      $description = "Decoupage Queen designs and products.";
      $keywords = "Designs, Decoupage Queen, Products, Rice paper, A4, A3, XL";
      $sm_share = "";
    }

if($page == '/scrapbooks/')
    {
      $laser_class = 'active';
      $admin_class = "";
      $title = "Decoupage Queen - Scrapbooks";
      $description = "Decoupage Queen designs and products.";
      $keywords = "Designs, Decoupage Queen, Products, scrapbooks, scrapbook";
      $sm_share = "";
    }

if($page == '/tutorials/')
    {
      $admin_class = "";
      $title = "Decoupage Queen - Tutorials";
      $description = "Our latest video tutorials";
      $keywords = "Decoupage Queen, tutorials, video, youtube";
      $sm_share = "";
    }
if($page == '/policies/cookie')
    {
      $admin_class = "";
      $title = "Decoupage Queen - Cookie Policy";
      $description = "Please read Decoupage Queens cookie policy";
      $keywords = "cookies, cookie, cookie policy, Decoupage Queen";
      $sm_share = "";
    }
if($page == '/policies/privacy')
    {
      $admin_class = "";
      $title = "Decoupage Queen - Privacy Policy";
      $description = "Please read Decoupage Queens privacy policy";
      $keywords = "privacy, privacy policy, Decoupage Queen";
      $sm_share = "";
    }
if($page == '/admin/edit' || $page == '/admin/add-notice' || $page == '/admin/add-blogpost' || $page == '/admin/edit-blogpost' || $page == '/admin/add-designer')
    {
      $admin_class = 'active';
      $title = "Decoupage Queen - Admin";
      $description = "This is the admin area where logged in users can add, edit and delete content on the Decoupage Queen website";
      $keywords = "Decoupage Queen, admin area, add, edit, delete";
      $sm_share = "";
    }
if($page == '/admin/')
    {
      $admin_class = 'active';
      $title = "Decoupage Queen - Admin";
      $description = "This is the admin area where logged in users can add, edit and delete content on the Decoupage Queen website";
      $keywords = "Decoupage Queen, admin area, add, edit, delete";
      $sm_share = "";
    }
if($page == '/login/' || $page == "/login/?reset=success")
    {
      $title = "Decoupage Queen - Login";
      $description = "Login into Decoupage Queen.co.uk to view orders";
      $keywords = "Decoupage Queen, login";
      $sm_share = "";
    }

if($page == '/register/')
    {
      $title = "Decoupage Queen - Register";
      $description = "Register today with DecoupageQueen.com";
      $keywords = "Decoupage Queen, register";
      $sm_share = "";
    }

if($page == "/faq/")
    {
      $news_class = "active";
      $admin_class = "";
      $title = "Decoupage Queen - FAQ";
      $description = "FAQs and policies";
      $keywords = "";
      $sm_share = "";
    }
if($page == "/users/" || $page == "/users/wishlist" || $page == "/users/my-orders" || $page == "/users/order-details?order=$order")
    {
      $news_class = "active";
      $admin_class = "";
      $title = "Decoupage Queen - User";
      $description = "User section - View orders you have placed and you wishlist items";
      $keywords = "";
      $sm_share = "";
    }

if(isset($_GET['order']))
    {
      $order = $_GET['order'];
      if($page == "/admin/order-details?order=$order")
        {
          $news_class = "active";
          $admin_class = "";
          $title = "Decoupage Queen - Admin";
          $description = "Admin section - View all orders placed by users";
          $keywords = "";
          $sm_share = "";
        }
      if($page == "/new-cart/?order=$order" || $page == "/new-cart/payment?order=$order")
        {
          $news_class = "active";
          $admin_class = "";
          $title = "Decoupage Queen - Shopping cart";
          $description = "Shopping cart and delivery/collction information";
          $keywords = "Cart, shopping, delivery, collection";
          $sm_share = "";
        }
    }

if($page == "/admin/view-users" || $page == "/admin/orders" || $page == "/admin/add-retailer" || $page == "/admin/items" || $page == "/admin/add-item" || $page == "/admin/add-promo" || $page == "/admin/edit-promos" || $page == "/admin/order-details")
    {
      $news_class = "active";
      $admin_class = "";
      $title = "Decoupage Queen - Admin";
      $description = "Admin section - View orders, edit items, add retailers and view user details";
      $keywords = "";
      $sm_share = "";
    }
?>