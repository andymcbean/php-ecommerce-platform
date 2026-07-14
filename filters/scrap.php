<?php
function filter_retailer()
{
  global $db;
  if(isset($_SESSION['email']))
    {
      $email = $_SESSION['email'];
    }
  elseif(isset($_SESSION['user_email_address']))
    {
      $email = $_SESSION['user_email_address'];
    }
  elseif(isset($_SESSION['g_user_email_address']))
    {
      $email = $_SESSION['g_user_email_address'];
    }
  $sql = "SELECT id, email, user_level, retailer
          FROM users
          WHERE email = '$email'
          AND retailer = 'Yes'
          ";
  $statement = $db->prepare($sql);
  $statement->execute();
  $result = $statement->fetchAll();
  if ($result)
      {
        return true;
      }
  else
      {
        return false;
      }
}
include '../includes/connect.php';
include '../includes/constants.php';
//include '../includes/functions.php';
if(isset($_POST['action']) OR isset($_POST['fetch_scrap']))
    {
        $sql = "SELECT 
                    *
                FROM 
                    items
                WHERE status = 1
                AND active = 'yes'
                AND retail_only = 'no'
                AND type = 'scrapbook'
                OR type = 'chipboard'
                "; 
        if(isset($_POST['product']))
            {
                $product = $_POST['product'];
                $sql .= "AND description LIKE '%$product%' ";
            }
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $total_row = $statement->rowCount();
        $output = '';
        if($total_row > 0)
            {
            foreach($result as $row)
                {
                 ?>   
                    <div class='col-12 col-sm-6 col-lg-3 col-md-3 mb-5'>
                        <div class='card h-100'>
                            <div class='card-img-top'>
                                <a href="../scrapbooks/details?sku=<?php echo $row['sku'] ?>"><img src='<?php echo IMAGE_URL ?><?php echo $row['img'] ?>' alt='' class='img-fluid product-image' id='product_image'></a>
                            </div>
                            <div class='card-body'>
                                <!--<h4 style='text-align:center;' ><?php //echo $row['description'] ?></h4>-->
                                <h4 id="product_name" style='text-align: center;' class="product-title"><a href='../scrapbooks/details?sku=<?php echo $row['sku'] ?>' style="text-decoration: none; color: #000;"><?php echo $row['description'] ?></a></h4>
                                
                                <br>
                                <input type="hidden" name="hidden_description" value="<?php echo $row['description'] ?>">
                                <input type="hidden" name="sku" value="<?php echo $row['sku'] ?>">
                            </div>
                        </div>
                    </div><br>
                    <?php
                    if($row['retail_only'] == 1 && filter_retailer())
                        {

                        }
                }  
            } 
        else    
            {
                echo "<div class='alert alert-warning'>No results. Please adjust filter selection.</div>";
            }
    }
?>
<script>
    $('input.form-control').on('change', function() {
    $('input.form-control').not(this).prop('checked', false);  
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>