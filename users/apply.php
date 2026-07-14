<?php
$sm_share = "";
include '../includes/header.php';

if(logged_in_fb() || logged_in() || logged_in_google())
    {

?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Become A Retailer</h1>
  </div>
</div>
<div class="container mt-5 mb-5">
    <div class="row text-center">
        <div class="col-12">
          <h2>Become a Retailer</h2>
          <p>If you are interested in becoming a retailer and selling our products then please complete in the form below.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="form-group">
                <input id="name" class="form-control" type="text" name="name" placeholder="Name">
            </div>
            <div class="form-group">
                <input id="store_name" class="form-control" type="text" name="store_name" placeholder="Store Name">
            </div>
            <div class="form-group">
                <select id="store_type" class="form-control" name="store_type">
                    <option value="online">Online only</option>
                    <option value="store">Bricks and Mortar</option>
                    <option value="both">Both</option>
                </select>
            </div>
            <div class="form-group">
                <textarea id="address" class="form-control" name="address" rows="5" placeholder="Address (if bricks and mortar)"></textarea>
            </div>
            <div class="form-group">
                <input id="website" class="form-control" type="text" name="website" placeholder="Website URL">
                
            </div>
            <div class="form-group">
                <input id="fb" class="form-control" type="text" name="fb" placeholder="Facebook URL">
            </div>
            <div class="form-group">
                <input id="insta" class="form-control" type="text" name="Insta" placeholder="Instagram username">
            </div>
            <div class="form-group">
                <input id="email" class="form-control" type="email" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input id="phone" class="form-control" type="number" name="phone" placeholder="Contact Number">
            </div>
            <div class="form-group">
                <input id="years" class="form-control" type="number" name="years" placeholder="Years in business">
            </div>
            <div class="form-group">
                <textarea id="other_products" class="form-control" name="other_products" rows="5" placeholder="Other products stocked?"></textarea>
            </div>
            
            <button id="submitBtn" name="submit" type="submit" class="btn btn-info btn-block"><i class="fa fa-paper-plane"></i> Submit</button>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#submitBtn').on('click', function() {
    var store_name = $('#store_name').val();
    var store_type = $('#store_type').val();
    var name = $('#name').val();
    var email = $('#email').val();
    var address = $('#address').val();
    var website = $('#website').val();
    var fb = $('#fb').val();
    var insta = $('#insta').val();
    var phone = $('#phone').val();
    var years = $('#years').val();
    var other_products = $('#other_products').val();
    
    if(name!="" && email!="" && years!="" && store_name!=""){
        $.ajax({
            url: "../includes/insert-apply",
            type: "POST",
            data: {
                store_name: store_name,
                name: name,
                email: email,
                address: address,
                website: website,
                fb: fb,
                insta: insta,
                phone: phone,
                years: years,
                other_products: other_products,
                store_type: store_type				
            },
            cache: false,
            success: function(dataResult){
                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode==200){
                    alert('Form submitted successfully !');
                    window.location.reload();				
                }
                else if(dataResult.statusCode==201){
                alert("Error occured !");
                }
                
            }
        });
    }
    else
        {
            alert('Please fill in name, email, years, store name');
        }
    });
                
});
</script>
<?php
    }
    else
        {
            
            echo "<br><div class='container alert alert-danger margin-top'><strong>You need to be logged in to access this page</strong></div>";
        }

    include '../includes/footer.php';
?>