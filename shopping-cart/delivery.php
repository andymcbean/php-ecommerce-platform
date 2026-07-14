<?php
include '../includes/header.php';
    
if(isset($_GET['order']))
    {
        $order = $_GET['order'];

        $order_no = array($order);

        $sql = "SELECT 
                    *
                FROM 
                    orders
                WHERE
                    status = 0
                ";
        if(!empty($order_no)) 
            {
                $order = implode('","', $order_no);
                $sql .= ' AND order_no IN ("'.$order.'")';
            }
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $total_row = $statement->rowCount();
        $table = "";
        
        if ($total_row > 0)
            { 
                $total = 0;
                foreach($result as $row)
                    {
                        $cost = $row['qty'] * $row['price'];
                        $table .= "<tr>
                                    <td>".$row['description']."</td>
                                    <td>".$row['sku']."</td>
                                    <td><strong>".$row['qty']." </strong>(£".$row['price']." per item)</td>
                                    <td>£".$cost."</td>
                                    <input type='hidden' name='order_no[]' value='".$row['order_no']."'/>
                                    </tr>";

                        $total = $total + ($row['qty'] * $row['price']);
                        
                    }
            }
    }
if(isset($_SESSION['email']))
    {
        $sql = "SELECT
                    name,
                    company,
                    email,
                    add_one,
                    add_two,
                    add_three,
                    town,
                    county,
                    post_code,
                    phone
                FROM
                    users
                WHERE
                    email = '".$_SESSION['email']."'
                ";
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $register = "";
        foreach($result as $row)
            {
                $name = $row['name'];
                $company = $row['company'];
                $email = $row['email'];
                $add_one = $row['add_one'];
                $add_two = $row['add_two'];
                $add_three = $row['add_three'];
                $town = $row['town'];
                $county = $row['county'];
                $post_code = $row['post_code'];
                $phone = $row['phone'];
            }
    }
else    
    {
        $register = "";
        $register .= "<div class='col-12'>
                        <p><span>Register with us?
                        <input id='reg' type='checkbox' name='reg'/></span></p>
                    </div>";
    }
?>

<div class="navbar navbar-dark navbar-expand-md" style="margin-top: 15px;">
    <div class="container">
        <h1 class="h1-details mx-auto">DELIVERY</h1><br>
    </div>
</div><br>

<div class="container">
    <div class="row">
        
        <div class="col-12">
        <form id="payment" method="POST" action="../includes/update-delivery" style="margin-bottom:30px;"> 
        <table class="table-resposive table-bordered">
                <tr>
                    <th width="30%">Item</th>
                    <th width="30%">Sku</th>
                    <th width="30%">Quantity</th>
                    <th width="30%">Price</th>
                </tr>
                <?php echo $table; ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <?php $total_price = number_format($total, 2) ?>
                    <td class="amount">Total: <strong>£<?php echo $total_price; ?></strong></td>
                </tr>
        </table><br>
        </div>
    </div>   
        <!--Delivery name and address-->    
        <div class="form-row">
            <?php echo $register ?>
            <div class="form-group col-md-6">
                <label>Name</label>
                <input value="<?php if(isset($name)){echo $name;} ?>" name="cust_name[]" type="text" class="form-control" placeholder="Full name">
            </div>
            <div class="form-group col-md-6">
                <label>Company</label>
                <input value="<?php if(isset($company)){echo $company;} ?>" name="company[]" type="text" class="form-control" placeholder="company">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Email</label>
                <input value="<?php if(isset($email)){echo $email;} ?>" name="cust_email[]" type="email" class="form-control" placeholder="Email">
            </div>
            <div class="form-group col-md-6">
                <label>Contact</label>
                <input value="<?php if(isset($phone)){echo $phone;} ?>" name="contact_no[]" type="text" class="form-control" placeholder="Contact number">
            </div>
            <div class="form-group col-md-6 hide" id="p_word">
                <label for="password">Password</label>
                <input type="hidden" name="length" value="15">
                <input id="password_id" id="exampleInputPassword1" type="password" name="password" class="form-control" placeholder="Password"><span toggle="#password_id" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                
                <input style="margin-bottom: 15px; margin-top: 10px;" type="button" class="btn btn-info" value="Generate" onClick="generate();" tabindex="2">
                
                <div class="progress password-progress">
                    <div id="strengthBar" class="progress-bar col-md-6" role="progressbar" style="width: 0;"></div>
                </div>
                <div class="text-danger" id="passwordmessage"></div>      
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Dleivery Address</label>
                    <!-- Postcode field -->
                    <div style="padding: 5px; border: none;" class="form-control" id="postcode_lookup">
                    </div>  
               
                <br>
                <!-- Add to your existing form -->
                <label>First Address Line</label>
                <input value="<?php if(isset($add_one)){echo $add_one;} ?>" name="add_one[]" class="form-control" id="line1" type="text"> 
            
                <label>Second Address Line</label>
                <input value="<?php if(isset($add_two)){echo $add_two;} ?>" name="add_two[]" class="form-control" id="line2" type="text">   
            
                <label>Third Address Line</label>
                <input value="<?php if(isset($add_three)){echo $add_three;} ?>" name="add_three[]" class="form-control" id="line3" type="text">  
            
                <label>Town</label>
                <input value="<?php if(isset($town)){echo $town;} ?>" name="town[]" class="form-control" id="town" type="text">
            
                <label>County</label>
                <input value="<?php if(isset($county)){echo $county;} ?>" name="county[]" class="form-control" id="county" type="text">
            
                <label>Postcode</label>
                <input value="<?php if(isset($post_code)){echo $post_code;} ?>" name="post_code[]" class="form-control" id="postcode" type="text">

                
            </div>
               
        </div>
        <br>

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card admin-card">
                    <div class="card-body">
                        <p>Premium Delivery<span style="font-weight: bold;"> £129.00</span></p>
                        <input id="prem" type="checkbox" class="form-control" name="basic[]" value="0"/>
                    </div>
                    
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card admin-card">
                    <div class="card-body">
                    <p>Standard Delivery<span style="font-weight: bold;"> £79.00</span></p>
                        <input id="basic" type="checkbox" class="form-control" name="basic[]" value="0"/>
                        
                    </div>
                    
                </div>
            </div>

        

            <div class="col-12">
                <p class="amount-text">Your final price is £<span class="amount"><span class="amount" value="£<?php echo $total_price; ?>"></span></p>
                
                <button class="btn btn-info" type="submit" name="pay">Pay <span class="amount" value="£<?php echo $total_price; ?>"></span></button>
            </div>
        </form>
    </div>       
</div>
<?php
include '../includes/footer.php';

?>
<script>

$('#basic').on('click', function() {
    var hiddenField = $('#basic'),
        val = hiddenField.val();

    hiddenField.val(val === "79.00" ? "0" : "79.00");
    console.log("new value: " + hiddenField.val());
});

$('#prem').on('click', function() {
    var hiddenField = $('#prem'),
        val = hiddenField.val();

    hiddenField.val(val === "129.00" ? "0" : "129.00");
    console.log("new value: " + hiddenField.val());
});
    
$(document).ready(function(){
  $("#reg").click(function(){
    $("#p_word").toggleClass("hide");
  });
});

$(':checkbox').change(function(){
    var sum = <?php echo $total_price ?>;
    var names = $(':checked').map(function(){
        sum += (this.value - 0);
        return this.name;
    }).get().join(',');
    var spans = $('span.amount');
    spans[1].innerHTML = sum;
});
</script>
<script>
$('#postcode_lookup').getAddress(
    {
    api_key: 'cNh-wyCcjE-4951Vc-D5Uw26910',  
    output_fields:{
        line_1: '#line1',
        line_2: '#line2',
        line_3: '#line3',
        post_town: '#town',
        county: '#county',
        postcode: '#postcode'
    }
});
</script>