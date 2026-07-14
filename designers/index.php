<?php
$sm_share = "";
include '../includes/header.php';
include '../includes/constants.php';
?>

<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Design Team</h1>
    
  </div>
</div>

<div class="container margin-top">
    <?php
        $sql = "SELECT
                    *
                FROM
                    designers
                ORDER BY site DESC
                ";
                $statement = $db->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll();
                $about = "";
                foreach($result as $row)
                    {
                        if($row['site'] == "")
                            {
                                $site = "";
                            }
                        else
                            {
                                $site = "<a data-toggle='tooltip' data-placement='bottom' title='Vist my website' href='".$row['site']."' target='_blank'><img src='../images/www.png'></a>";
                            }
                        if($row['insta'] == "")
                            {
                                $insta = "";
                            }
                        else
                            {
                                $insta = "<a data-toggle='tooltip' data-placement='bottom' title='Vist my Instagram page' href='".$row['insta']."' target='_blank'><img src='../images/insta.png'></a>";
                            }
                        if($row['yt'] == "")
                            {
                                $yt = "";
                            }
                        else
                            {
                                $yt = "<a data-toggle='tooltip' data-placement='bottom' title='Vist my Youtube channel' href='".$row['yt']."' target='_blank'><img src='../images/youtube.png'></a>";
                            }
                        if($row['fb'] == "")
                            {
                                $fb = "";
                            }
                        else
                            {
                                $fb = "<a data-toggle='tooltip' data-placement='bottom' title='Vist my Facebook page' href='".$row['fb']."' target='_blank'><img src='../images/facebook.png'></a>";
                            }
                        $bio = html_entity_decode($row['bio']);
                        $about .=   "<div class='card pt-2 mb-5' style='border: 1px solid #6e9c75;'>
                                        <div class='row'>
                                            <div class='col-12 col-md-6  text-center'>
                                                <img src='".IMAGE_URL."".$row['img']."' class='img-fluid'>
                                                <br><br><span class=''>".$site." ".$fb." ".$yt." ".$insta."</span>
                                            </div>
                                            <div class='col-12 col-md-6'>
                                                <p class='' style='margin-top: -15px;'>".$bio."</p>
                                            </div>
                                        </div>
                                     </div>";
                    }
                echo $about;
    ?>
</div>

<?php
include '../includes/footer.php';
?>
<script>
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
</script>