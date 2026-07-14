<?php
include '../includes/header.php';

$sql = "SELECT
              id,
              message
        FROM
              faq
        ";
//echo $sql;
$statement = $db->prepare($sql);
$statement->execute();
$result = $statement->fetchAll();

?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">FAQ</h1>
  </div>
</div>

<div class="container margin-top mb-5">
  <div class="row">
    <div class="col-12 mb-5" style="text-align: center;">
      <h4>All sales are final. No returns unless product is damaged. Please note that due to COVID-19, USPS is taking longer than normal for delivery of packages. We cannot control this, so please expect longer shipping times. We do ship internationally, but please expect longer delivery times.</h4>
    </div>
  </div>
  <div class="row">
      <div class="col-md-4 center-txt">
        <img src="../images/custom.jpg" class="img-fluid" alt="wholesale">
        <div class="centered">Wholesale</div>
      </div>
      <div class="col-12 col-md-8">
        <p>Thank you for your interest in becoming a retailer for Decoupage Queen papers. We are now accepting wholesale applications! Feel free to contact us at decoupagequeenpaper@gmail.com and let us know about your business and how you think it could align with our products. We'll contact you as soon as possible. Alternatively, you can login <a href="../login/">here</a> and submit an application online.</p>
      </div>
  </div>
  <hr>
  <div class="row">
    <div class="col-md-4 center-txt">
      <img src="../images/custom.jpg" class="img-fluid" alt="glue">
      <div class="centered">What glue?</div>
    </div>
    <div class="col-12 col-md-8">
      <p>The mediums for applying decoupage are endless. We love and use Aleene's Collage Pauge in the matte finish because of it's quick drying time and durable finish. There is no "wrong" medium to use. It is really a matter of personal preference and the results may be different with each method. 
      Demonstrations for how to use the products and DIY inspiration can be found at http://www.thdecoratl.com</p>
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="col-md-4 center-txt">
      <img src="../images/custom.jpg" class="img-fluid" alt="folded">
      <div class="centered">My paper is folded or wrinkled</div>
    </div>
    <div class="col-12 col-md-8">
      <p>The great thing about decoupage paper is that although it is thin, it is very forgiving. If your paper is folded or wrinkled when it arrives, there is no need to try to flatten it out before using. Once the paper is wet with your decoupage medium, it will even out and the folds / wrinkles will not be noticeable.</p>
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="col-md-4 center-txt">
      <img src="../images/custom.jpg" class="img-fluid" alt="custom">
      <div class="centered">Custom Designs</div>
    </div>
    <div class="col-12 col-md-8">
      <p>We spend a lot of time working on the designs so that you can have the most on trend resources for your project. We would love to hear your feedback for things you might be looking for or that we can add to our offering. While we do not do custom, made to order designs, feedback is welcome and encouraged.</p>
    </div>
  </div>
</div>
<?php
include '../includes/footer.php';
?>