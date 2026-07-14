<?php
include '../includes/connect.php';
include '../includes/constants.php';
//include '../includes/functions.php';
if(isset($_POST['action']) OR isset($_POST['method']))
    {
        $sql = "SELECT 
                    *
                FROM 
                    items
                WHERE status = 1
                AND active = 'yes'
                AND type = 'Rice Paper'
                AND us_only = 'yes'
                "; 
        if(isset($_POST['a4']))
            {
                $a4 = implode("','", $_POST['a4']);
                $sql .= "AND a4 IN ('$a4') ";
            } 
        if(isset($_POST['a3']))
            {
                $a3 = implode("','", $_POST['a3']);
                $sql .= "AND a3 IN ('$a3') ";
            }
        if(isset($_POST['xl']))
            {
                $xl = implode("','", $_POST['xl']);
                $sql .= "AND xl IN ('$xl') ";
            }
        if(isset($_POST['product']))
            {
                $product = $_POST['product'];
                $sql .= "AND description LIKE '%$product%' ";
            }
        if(isset($_POST['scrap']))
            {
                $scrap = implode("','", $_POST['scrap']);
                $sql .= "AND type IN ('$scrap') ";
            }
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $total_row = $statement->rowCount();
        $output = '';
        $a4 = "";
        $a3 = "";
        $xl = "";
        if($total_row > 0)
            {
            foreach($result as $row)
                {
                    if($row['a4'] == 'yes')
                        {
                            $a4 = "<p class='product-price'>Price : $2.95</p>";
                        }
                    else
                        {
                            $a4 = "";
                        }
                    if($row['a3'] == 'yes')
                        {
                            $a3 = "<p class='product-price'>Price : $4.95</p>";
                        }
                    else
                        {
                            $a3 = "";
                        }
                    if($row['xl'] == 'yes')
                        {
                            $xl = "<p class='product-price'>Price : $6.95</p>";
                        }
                    else
                        {
                            $xl = "";
                        }
                 ?>   
                    <div class='col-12 col-sm-6 col-lg-3 col-md-3 mb-5'>
                        <div class='card h-100 box'>
                            <div class="ribbon"><span>US EXCLISIVE</span></div>
                            <div class='card-img-top'>
                                <a href="../us-retail/details?sku=<?php echo $row['sku'] ?>"><img src='<?php echo IMAGE_URL ?><?php echo $row['img'] ?>' alt='' class='img-fluid product-image' id='product_image'></a>
                            </div>
                            <div class='card-body'>
                                <!--<h4 style='text-align:center;' ><?php //echo $row['description'] ?></h4>-->
                                <h4 id="product_name" style='text-align: center;' class="product-title"><a href='../us-retail/details?sku=<?php echo $row['sku'] ?>' style="text-decoration: none; color: #000;"><?php echo $row['description'] ?></a></h4>
                                
                                <br>
                                <input type="hidden" name="hidden_description" value="<?php echo $row['description'] ?>">
                                <input type="hidden" name="sku" value="<?php echo $row['sku'] ?>">
                            </div>
                        </div>
                    </div><br>
                    <?php
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