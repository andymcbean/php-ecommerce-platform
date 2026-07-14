<?php
include '../includes/header.php';
?>
<div class="container">
    <div class="status">
        <h1 class="error">Your PayPal Transaction has been Canceled</h1>
    </div>
    <button class="btn btn-info" id="unset">Back to Products</button>
</div>
<?php
include '../includes/footer.php';
?>
<script type="text/javascript">
$( document ).ready(function() {
    $("#unset").on('click',function() {
        
         $.ajax({
            type: "GET",
            url: 'destroy',
            data: {unset:1},
            success:function(data){
                if(data == 'session unset'){
                     window.location.href = '../kitchen-ranges/';
                }
               
            }
            });
        });
    });
</script>