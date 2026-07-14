<?php
$sm_share = "";
include '../includes/header.php';
include 'functions.php';
if(logged_in() AND user_admin())
    {
        $table_items = "";
        $signed = "";
        $s_th = "";
        $sql = "SELECT
                    *
                FROM
                    retail_agreements
                WHERE
                    signed = 1
                ";
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $total_row = $statement->rowCount();
        
        foreach($result as $row)
            {
                if($total_row > 0)
                    {
                        $s_th = "<th>Agreement Signed</th>";
                        $signed = "<td><a href='retailer-agreement?store=".$row['store_name']."'>View Signed Agreement</a></td>";
                    }
                else
                    {
                        $s_th = "";
                        $signed = "";
                    }
            }

        $sql = "SELECT
                    *
                FROM
                    retail_request
                ";
        //echo $sql;
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $image_modal = "";
        $fb = "";
        foreach($result as $row)
            {
                $name = $row['name'];
                $table_items .= "<tr id='".$row['id']."'>
                                    $signed
                                    <td>".$name."</td>
                                    <td>".$row['store_name']."</td>
                                    <td>".$row['store_type']."</td>
                                    <td>".$row['address']."</td>
                                    <td>".$row['website']."</td>
                                    <td>".$row['fb']."</td>
                                    <td>".$row['insta']."</td>
                                    <td>".$row['email']."</td>
                                    <td>".$row['phone']."</td>
                                    <td>".$row['years']."</td>
                                    <td>".$row['other_products']."</td>
                                    <td><a class='btn btn-info' href='send-agreement?store=".$row['store_name']."'><i class='fa fa-paper-plane'></i></a></td>
                                    <td><button class='btn btn-danger delete' id='del_".$row['id']."'> <i class='fas fa-trash-alt'></i></button></td>
                               </tr>";
            }
?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Retailer Applications</h1>
  </div>
</div>
<div class="container-fluid mt-5 mb-5">
    <div class="row">
        <div class="col-12">
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <?=$s_th?>
                        <th>Name</th>
                        <th>Store Name</th>
                        <th>Store Type</th>
                        <th>Address</th>
                        <th>Website</th>
                        <th>FB</th>
                        <th>Insta</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Years</th>
                        <th>Other Products</th>
                        <th>Agreement</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <?php echo $table_items; ?>
            </table>
        </div>
    </div>
</div>
<?php
    }
    else
        {
            echo "<div class='alert alert-danger'><strong>You do not have permission to access this page</strong></div>";
        }

    include '../includes/footer.php';
?>