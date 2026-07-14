<?php
$sm_share = "";
include '../includes/header.php';
include '../includes/email-functions.php';

if(logged_in_fb() || logged_in() || logged_in_google())
    {
        if(isset($_GET['email']))
            {
                $email = $_GET['email'];

                if(isset($_POST['submit']))
                    {
                        $retailer_copy = htmlentities($_POST['retailer_copy']);
                        $signed = 1;
                        $sql = "UPDATE 
                                    retail_agreements
                                SET
                                    signed=:signed, retailer_copy=:retailer_copy
                                WHERE
                                    email = '$email'
                                ";
                        $statement = $db->prepare($sql);
                        $statement->bindParam(':signed'	    ,   $signed     ,   PDO::PARAM_INT);
                        $statement->bindParam(':retailer_copy'	,   $retailer_copy  ,   PDO::PARAM_STR);
                        try
                            {
                                $statement->execute();
                                $success = "<div class='container alert alert-success'>Agreement has been sent to Decoupage Queen. Return to <a href='users'>Dashboard</a></div>";
                                agreement_fao_admin();
                            }
                        catch(PDOException $e)
                            {   
                                echo $e;
                            } 
                    }
            }

        
?>
<script>
    tinymce.init({
      selector: '#agreement'
    });
 </script>
<div class="hero-image">
  <div class="hero-text">
    <h1 style="font-size:50px; color: #fff" class="nav-icon">Retail Agreement</h1>
  </div>
</div>

<div class="container margin-top">
    <div class="row mb-5">
        <div class="col-12">
        <h4>This is you retail agreement. Please read it over carefully and complete the "RETAILER" section at the bottom of the agreement and submit the form.</h4><br>
        <?php
        echo "<form method='post' action=''><textarea name='retailer_copy' id='agreement' rows='150'>";
                retail_agreement();
        echo "</textarea><button class='btn btn-info mt-3' name='submit' style='min-width: 100%;'>Sign Agreement</button></form>"
        ?>
        </div>
    </div>
</div>
<?php
    }
    else
        {
            
            echo "<br><div class='container alert alert-danger margin-top'><strong>You need to be logged in to access this page</strong></div>";
        }

    include '../includes/footer.php';
?>