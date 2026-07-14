
<?php
require_once '../includes/header.php';
include '../includes/constants.php';
?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Wishlist</h1>
  </div>
</div>
<?php
if(logged_in() || logged_in_fb() || logged_in_google())
    {
        $email = "";
        $email = global_logged_in($email);
        
        $sql = "SELECT
                    wishlist.email,
                    wishlist.description,
                    wishlist.sku,
                    wishlist.date,
                    items.img,
                    items.sku
                FROM
                    wishlist
                INNER JOIN
                    items
                ON
                    items.sku = wishlist.sku
                WHERE
                    wishlist.email = '$email'
                ";
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $total_row = $statement ->rowCount();
        $output = "";
        $no_fav = "";
        if($total_row > 0)
            {
                foreach($result as $row)
                    {
                        $output .= "<tr>
                                        <td><a href='../decoupage/details?sku=".$row['sku']."'><img src='".IMAGE_URL."".$row['img']."' class='img-fluid' style='max-width:50%;'></a></td>
                                        <td>".$row['description']."</td>
                                        <td>".$row['img']."</td>
                                        <td>".$row['date']."</td>
                                        <td><a class=\"btn btn-info\" href='../decoupage/details?sku=".$row['sku']."'><i class=\"fas fa-eye\"></i></a></td>";
        
                                        if(!$row['sku'])
                                            {
                                                $output .= "";
                                            }
                                        else
                                            {
                                                $output .= "<td>
                                                                <input type='button' name='save' class='btn btn-info' value='Remove from wishlist' id='del_wish'>
                                                                <input type='hidden' name='email' id='email' value='".$email."'/>
                                                                <input type='hidden' name='sku' id='id_sku' value='".$row['sku']."'/>
                                                                <input type='hidden' name='description' id='description' value='".$row['description']."'/>
                                                            </td>";
                                            }
                                        $output .= "</tr>";
                    }
            }
        else
            {
                $no_fav .= "<div class='alert alert-info'>You have no items saved to your wishlist</div>";
            }
        
                
?>

<div class="container margin-top">
    <div class="row">
        <div class="col-12">
        <?php echo $no_fav; ?>
            <div id="order_table_country">
                <div id="order_table_data">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                
                            </tr>
                        </thead>
                        <?php echo $output; ?>
                    </table>
                </div>
            </div>
        </div>
            
            
    </div>
</div>

<?php
    }
    else
        {
            echo "<br><div class='container alert alert-danger'><strong>You must be logged in to access this page</strong></div>";
        }

    include '../includes/footer.php';
?>

<script>  
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