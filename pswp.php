<?php  
//version control test
 //cart.php  
 session_start();  
 $connect = mysqli_connect("localhost", "root", "", "shopping_cart");  
 ?>  
 <!DOCTYPE html>  
 <html>  
      <head>  
           <title>Webslesson Tutorial | Multi Tab Shopping Cart By Using PHP Ajax Jquery Bootstrap Mysql</title>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
      </head>  
      <body>  
           <br />  
           <div class="container" style="width:800px;">  
                <?php  
                if(isset($_POST["place_order"]))  
                {  
                     $insert_order = "INSERT INTO tbl_order(customer_id, creation_date, order_status)  
                     VALUES('1', '".date('Y-m-d')."', 'pending')  
                     ";  
                     $order_id = "";  
                     if(mysqli_query($connect, $insert_order))  
                     {  
                          $order_id = mysqli_insert_id($connect);  
                     }  
                     $_SESSION["order_id"] = $order_id;  
                     $order_details = "";  
                     foreach($_SESSION["shopping_cart"] as $keys => $values)  
                     {  
                          $order_details .= "  
                          INSERT INTO tbl_order_details(order_id, product_name, product_price, product_quantity)  
                          VALUES('".$order_id."', '".$values["product_name"]."', '".$values["product_price"]."', '".$values["product_quantity"]."');  
                          ";  
                     }  
                     if(mysqli_multi_query($connect, $order_details))  
                     {  
                          unset($_SESSION["shopping_cart"]);  
                          echo '<script>alert("You have successfully place an order...Thank you")</script>';  
                          echo '<script>window.location.href="cart.php"</script>';  
                     }  
                }  
                if(isset($_SESSION["order_id"]))  
                {  
                     $customer_details = '';  
                     $order_details = '';  
                     $total = 0;  
                     $query = 'SELECT * FROM tbl_order  
                     INNER JOIN tbl_order_details  
                     ON tbl_order_details.order_id = tbl_order.order_id  
                     INNER JOIN tbl_customer  
                     ON tbl_customer.CustomerID = tbl_order.customer_id  
                     WHERE tbl_order.order_id = "'.$_SESSION["order_id"].'"  
                     ';  
                     $result = mysqli_query($connect, $query);  
                     while($row = mysqli_fetch_array($result))  
                     {  
                          $customer_details = '  
                          <label>'.$row["CustomerName"].'</label>  
                          <p>'.$row["Address"].'</p>  
                          <p>'.$row["City"].', '.$row["PostalCode"].'</p>  
                          <p>'.$row["Country"].'</p>  
                          ';  
                          $order_details .= "  
                               <tr>  
                                    <td>".$row["product_name"]."</td>  
                                    <td>".$row["product_quantity"]."</td>  
                                    <td>".$row["product_price"]."</td>  
                                    <td>".number_format($row["product_quantity"] * $row["product_price"], 2)."</td>  
                               </tr>  
                          ";  
                          $total = $total + ($row["product_quantity"] * $row["product_price"]);  
                     }  
                     echo '  
                     <h3 align="center">Order Summary for Order No.'.$_SESSION["order_id"].'</h3>  
                     <div class="table-responsive">  
                          <table class="table">  
                               <tr>  
                                    <td><label>Customer Details</label></td>  
                               </tr>  
                               <tr>  
                                    <td>'.$customer_details.'</td>  
                               </tr>  
                               <tr>  
                                    <td><label>Order Details</label></td>  
                               </tr>  
                               <tr>  
                                    <td>  
                                         <table class="table table-bordered">  
                                              <tr>  
                                                   <th width="50%">Product Name</th>  
                                                   <th width="15%">Quantity</th>  
                                                   <th width="15%">Price</th>  
                                                   <th width="20%">Total</th>  
                                              </tr>  
                                              '.$order_details.'  
                                              <tr>  
                                                   <td colspan="3" align="right"><label>Total</label></td>  
                                                   <td>'.number_format($total, 2).'</td>  
                                              </tr>  
                                         </table>  
                                    </td>  
                               </tr>  
                          </table>  
                     </div>  
                     ';  
                }  
                ?>  
           </div>  
      </body>  
 </html> 

 <?php  
 //action.php  
 session_start();  
 $connect = mysqli_connect("localhost", "root", "", "test");  
 if(isset($_POST["product_id"]))  
 {  
      $order_table = '';  
      $message = '';  
      if($_POST["action"] == "add")  
      {  
           if(isset($_SESSION["shopping_cart"]))  
           {  
                $is_available = 0;  
                foreach($_SESSION["shopping_cart"] as $keys => $values)  
                {  
                     if($_SESSION["shopping_cart"][$keys]['product_id'] == $_POST["product_id"])  
                     {  
                          $is_available++;  
                          $_SESSION["shopping_cart"][$keys]['product_quantity'] = $_SESSION["shopping_cart"][$keys]['product_quantity'] + $_POST["product_quantity"];  
                     }  
                }  
                if($is_available < 1)  
                {  
                     $item_array = array(  
                          'product_id'               =>     $_POST["product_id"],  
                          'product_name'               =>     $_POST["product_name"],  
                          'product_price'               =>     $_POST["product_price"],  
                          'product_quantity'          =>     $_POST["product_quantity"]  
                     );  
                     $_SESSION["shopping_cart"][] = $item_array;  
                }  
           }  
           else  
           {  
                $item_array = array(  
                     'product_id'               =>     $_POST["product_id"],  
                     'product_name'               =>     $_POST["product_name"],  
                     'product_price'               =>     $_POST["product_price"],  
                     'product_quantity'          =>     $_POST["product_quantity"]  
                );  
                $_SESSION["shopping_cart"][] = $item_array;  
           }  
      }  
      if($_POST["action"] == "remove")  
      {  
           foreach($_SESSION["shopping_cart"] as $keys => $values)  
           {  
                if($values["product_id"] == $_POST["product_id"])  
                {  
                     unset($_SESSION["shopping_cart"][$keys]);  
                     $message = '<label class="text-success">Product Removed</label>';  
                }  
           }  
      }  
      if($_POST["action"] == "quantity_change")  
      {  
           foreach($_SESSION["shopping_cart"] as $keys => $values)  
           {  
                if($_SESSION["shopping_cart"][$keys]['product_id'] == $_POST["product_id"])  
                {  
                     $_SESSION["shopping_cart"][$keys]['product_quantity'] = $_POST["quantity"];  
                }  
           }  
      }  
      $order_table .= '  
           '.$message.'  
           <table class="table table-bordered">  
                <tr>  
                     <th width="40%">Product Name</th>  
                     <th width="10%">Quantity</th>  
                     <th width="20%">Price</th>  
                     <th width="15%">Total</th>  
                     <th width="5%">Action</th>  
                </tr>  
           ';  
      if(!empty($_SESSION["shopping_cart"]))  
      {  
           $total = 0;  
           foreach($_SESSION["shopping_cart"] as $keys => $values)  
           {  
                $order_table .= '  
                     <tr>  
                          <td>'.$values["product_name"].'</td>  
                          <td><input type="text" name="quantity[]" id="quantity'.$values["product_id"].'" value="'.$values["product_quantity"].'" class="form-control quantity" data-product_id="'.$values["product_id"].'" /></td>  
                          <td align="right">$ '.$values["product_price"].'</td>  
                          <td align="right">$ '.number_format($values["product_quantity"] * $values["product_price"], 2).'</td>  
                          <td><button name="delete" class="btn btn-danger btn-xs delete" id="'.$values["product_id"].'">Remove</button></td>  
                     </tr>  
                ';  
                $total = $total + ($values["product_quantity"] * $values["product_price"]);  
           }  
           $order_table .= '  
                <tr>  
                     <td colspan="3" align="right">Total</td>  
                     <td align="right">$ '.number_format($total, 2).'</td>  
                     <td></td>  
                </tr>  
                <tr>  
                     <td colspan="5" align="center">  
                          <form method="post" action="cart.php">  
                               <input type="submit" name="place_order" class="btn btn-warning" value="Place Order" />  
                          </form>  
                     </td>  
                </tr>  
           ';  
      }  
      $order_table .= '</table>';  
      $output = array(  
           'order_table'     =>     $order_table,  
           'cart_item'          =>     count($_SESSION["shopping_cart"])  
      );  
      echo json_encode($output);  
 }  
 ?>

<?php   
 session_start();  
 $connect = mysqli_connect("localhost", "root", "", "test");  
 ?>  
 <!DOCTYPE html>  
 <html>  
      <head>  
           <title>Webslesson Tutorial | Multi Tab Shopping Cart By Using PHP Ajax Jquery Bootstrap Mysql</title>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
      </head>  
      <body>  
           <br />  
           <div class="container" style="width:800px;">  
                <h3 align="center">Multi Tab Shopping Cart By Using PHP Ajax Jquery Bootstrap Mysql</h3><br />  
                <ul class="nav nav-tabs">  
                     <li class="active">
                         <a data-toggle="tab" href="#products">Product</a>
                     </li>  
                     <li>
                         <a data-toggle="tab" href="#cart">Cart 
                              <span class="badge"><?php if(isset($_SESSION["shopping_cart"])) { echo count($_SESSION["shopping_cart"]); } else { echo '0';}?></span>
                         </a>
                     </li>  
                </ul>  
                <div class="tab-content">  
                     <div id="products" class="tab-pane fade in active">  
                     <?php  
                     $query = "SELECT * FROM tbl_product ORDER BY id ASC";  
                     $result = mysqli_query($connect, $query);  
                     while($row = mysqli_fetch_array($result))  
                     {  
                     ?>  
                     <div class="col-md-4" style="margin-top:12px;">  
                          <div style="border:1px solid #333; background-color:#f1f1f1; border-radius:5px; padding:16px; height:350px;" align="center">  
                               <img src="images/<?php echo $row["image"]; ?>" class="img-responsive" /><br />  
                               <h4 class="text-info"><?php echo $row["name"]; ?></h4>  
                               <h4 class="text-danger">$ <?php echo $row["price"]; ?></h4>  
                               <input type="text" name="quantity" id="quantity<?php echo $row["id"]; ?>" class="form-control" value="1" />  
                               <input type="hidden" name="hidden_name" id="name<?php echo $row["id"]; ?>" value="<?php echo $row["name"]; ?>" />  
                               <input type="hidden" name="hidden_price" id="price<?php echo $row["id"]; ?>" value="<?php echo $row["price"]; ?>" />  
                               <input type="button" name="add_to_cart" id="<?php echo $row["id"]; ?>" style="margin-top:5px;" class="btn btn-warning form-control add_to_cart" value="Add to Cart" />  
                          </div>  
                     </div>  
                     <?php  
                     }  
                     ?>  
                     </div>  <div id="cart" class="tab-pane fade">  
                          <div class="table-responsive" id="order_table">  
                               <table class="table table-bordered">  
                                    <tr>  
                                         <th width="40%">Product Name</th>  
                                         <th width="10%">Quantity</th>  
                                         <th width="20%">Price</th>  
                                         <th width="15%">Total</th>  
                                         <th width="5%">Action</th>  
                                    </tr>  
                                    <?php  
                                    if(!empty($_SESSION["shopping_cart"]))  
                                    {  
                                         $total = 0;  
                                         foreach($_SESSION["shopping_cart"] as $keys => $values)  
                                         {                                               
                                    ?>  
                                    <tr>  
                                         <td><?php echo $values["product_name"]; ?></td>  
                                         <td><input type="text" name="quantity[]" id="quantity<?php echo $values["product_id"]; ?>" value="<?php echo $values["product_quantity"]; ?>" data-product_id="<?php echo $values["product_id"]; ?>" class="form-control quantity" /></td>  
                                         <td align="right">$ <?php echo $values["product_price"]; ?></td>  
                                         <td align="right">$ <?php echo number_format($values["product_quantity"] * $values["product_price"], 2); ?></td>  
                                         <td><button name="delete" class="btn btn-danger btn-xs delete" id="<?php echo $values["product_id"]; ?>">Remove</button></td>  
                                    </tr>  
                                    <?php  
                                              $total = $total + ($values["product_quantity"] * $values["product_price"]);  
                                         }  
                                    ?>  
                                    <tr>  
                                         <td colspan="3" align="right">Total</td>  
                                         <td align="right">$ <?php echo number_format($total, 2); ?></td>  
                                         <td></td>  
                                    </tr>  
                                    <tr>  
                                         <td colspan="5" align="center">  
                                              <form method="post" action="cart.php">  
                                                   <input type="submit" name="place_order" class="btn btn-warning" value="Place Order" />  
                                              </form>  
                                         </td>  
                                    </tr>  
                                    <?php  
                                    }  
                                    ?>  
                               </table>  
                          </div>  
                     </div>  
                </div>  
           </div>  
      </body>  
 </html>  
 <script>  
 $(document).ready(function(data){  
      $('.add_to_cart').click(function(){  
           var product_id = $(this).attr("id");  
           var product_name = $('#name'+product_id).val();  
           var product_price = $('#price'+product_id).val();  
           var product_quantity = $('#quantity'+product_id).val();  
           var action = "add";  
           if(product_quantity > 0)  
           {  
                $.ajax({  
                     url:"action.php",  
                     method:"POST",  
                     dataType:"json",  
                     data:{  
                          product_id:product_id,   
                          product_name:product_name,   
                          product_price:product_price,   
                          product_quantity:product_quantity,   
                          action:action  
                     },  
                     success:function(data)  
                     {  
                          $('#order_table').html(data.order_table);  
                          $('.badge').text(data.cart_item);  
                          alert("Product has been Added into Cart");  
                     }  
                });  
           }  
           else  
           {  
                alert("Please Enter Number of Quantity")  
           }  
      });  
      $(document).on('click', '.delete', function(){  
           var product_id = $(this).attr("id");  
           var action = "remove";  
           if(confirm("Are you sure you want to remove this product?"))  
           {  
                $.ajax({  
                     url:"action.php",  
                     method:"POST",  
                     dataType:"json",  
                     data:{product_id:product_id, action:action},  
                     success:function(data){  
                          $('#order_table').html(data.order_table);  
                          $('.badge').text(data.cart_item);  
                     }  
                });  
           }  
           else  
           {  
                return false;  
           }  
      });  
      $(document).on('keyup', '.quantity', function(){  
           var product_id = $(this).data("product_id");  
           var quantity = $(this).val();  
           var action = "quantity_change";  
           if(quantity != '')  
           {  
                $.ajax({  
                     url :"action.php",  
                     method:"POST",  
                     dataType:"json",  
                     data:{product_id:product_id, quantity:quantity, action:action},  
                     success:function(data){  
                          $('#order_table').html(data.order_table);  
                     }  
                });  
           }  
      });  
 });  
 </script>