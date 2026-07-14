<?php
include '../includes/connect.php';

if(isset($_POST['action']) OR isset($_POST['method']))
    {
        
        $sql = "SELECT
                    *
                FROM
                    retailers
                WHERE status = 1
                ";
        if(isset($_POST['state']))
            {
                $state = implode("','", $_POST['state']);
                $sql .= "AND state IN ('$state') ";
            }
        if(isset($_POST['country']))
            {
                $country = implode("','", $_POST['country']);
                $sql .= "AND country IN ('$country') ";
            }
        if(isset($_POST['name']))
            {
                $name = $_POST['name'];
                $sql .= "AND name LIKE '%$name%' ";
            }
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $total_row = $statement->rowCount();
        $output = "";
        $url1 = "";
        if($total_row > 0)
            {
                foreach($result as $row)
                    {
                        $name = $row['name'];
                        $address = htmlspecialchars_decode($row['address']);
                        $country = $row['country'];
                        $state = $row['state'];

                        if($row['state'] != '')
                            {
                                $state = "- ".$row['state'];
                            }
                        else
                            {
                                $state = "";
                            }
                        if($row['url1'] != '')
                            {
                                $url1 = "<a data-toggle='tooltip' data-placement='top' data-original-title='Website' class='btn btn-info' href='".$row['url1']."' target='_blank'><i class='fas fa-globe fa-1x'></i></a>";
                            }
                        else
                            {
                                $url1 = "";
                            }
                        if($row['fb'] != '')
                            {
                                $fb = "<a data-toggle='tooltip' data-placement='top' data-original-title='Facebook' class='btn btn-info' href='".$row['fb']."' target='_blank'><i class='fab fa-facebook-square fa-1x'></i></a>";
                            }
                        else
                            {
                                $fb = "";
                            }
                        ?>
                                <div class='col-12 col-md-4' style='margin-top: 15px;'>
                                    <div class='card h-100'>
                                        <div class='card-body'>
                                            <h4 class='card-title'><?=$name?></h4>
                                            <h5 class='card-text'><?=$country?> <?=$state?></h5>
                                            <p class='card-text'><?=$address?></p>
                                            <?=$url1?>
                                            <?=$fb?>
                                            <?php
                                            if($address != "")
                                                {
                                                    echo "<button data-toggle='tooltip' data-placement='top' data-original-title='Zoom in' class='btn btn-info' style='cursor: pointer;' onclick='zoom(this.value)' value='".$name."'><a href='#map'> <i style='color:#fff;' class='fas fa-map-marker-alt fa-1x'></i></a></button>";
                                                }
                                            else
                                                {
                                                    echo "";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
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