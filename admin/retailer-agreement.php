<?php
$sm_share = "";
include '../includes/header.php';
include 'functions.php';
if(logged_in() AND user_admin())
    {
        if(isset($_GET['store']))
            {
                $store_name = $_GET['store'];

                $sql = "SELECT
                            *
                        FROM
                            retail_agreements
                        WHERE
                            store_name = '$store_name'
                        ";
                $statement = $db->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll();
                foreach($result as $row)
                    {
                        $email = $row['email'];
                        $agreement = html_entity_decode($row['retailer_copy']);
                        $store_name = $row['store_name'];
                        $signed = $row['signed'];
                        $date = $row['date'];
                        $name = $row['name'];
                    } 
            }

?>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon"><?=$store_name?> Agreement</h1>
  </div>
</div>

<div class="container mt-5 mb-5">
    <div class="row text-center mb-3">
        <div class="col-12">
            <h4>This is the signed agreement between yourself and <?=$store_name?></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <?=$agreement?>
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