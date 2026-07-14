function start_countdown()
    {
        var counter = 900;
        myVar = setInterval(function(){
            if(counter < 60)
                {
                    $("#timeModal").modal('show');
                    document.getElementById("countdown").innerHTML="You will be logged out in <br>"+counter+" Seconds";
                    //return false;
                }
            if(counter == 0)
                {
                    $.ajax({
                        type:'post',
                        url: '../includes/logout',
                        data:{
                            logout:"logout"
                        },
                        success:function(response)
                            {
                                window.location = "../login/";
                            }
                    });
                }
                counter--;
        }, 1000)
    }