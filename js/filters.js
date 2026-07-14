
//ITEMS
$(document).ready(function(){

    filter_items();
    
    function filter_items()
        {
            $('.filter_items').html('<div id="loading" style="" ></div>');  
            var action = 'fetch_data';
            var a4 = get_filter('a4');
            var a3 = get_filter('a3');
            var xl = get_filter('xl');
            $.ajax({
                url:"../filters/items",
                method:"POST",
                data:{action:action, a4:a4, a3:a3, xl:xl},
                success:function(data){
                    $('.filter_items').html(data);
                }
            });
        }
    
    function get_filter(class_name)
        {
            var filter = [];
            $('.'+class_name+':checked').each(function(){
                filter.push($(this).val());
            });
            return filter;
        }
    
    $('.common_selector').click(function(){
        filter_items();
    });
    
    });
//RETAIL ITEMS
$(document).ready(function(){

    filter_items();
    
    function filter_items()
        {
            $('.filter_retail').html('<div id="loading" style="" ></div>');  
            var action = 'fetch_data';
            var a4 = get_filter('a4');
            var a3 = get_filter('a3');
            var xl = get_filter('xl');
            $.ajax({
                url:"../filters/retailer",
                method:"POST",
                data:{action:action, a4:a4, a3:a3, xl:xl},
                success:function(data){
                    $('.filter_retail').html(data);
                }
            });
        }
    
    function get_filter(class_name)
        {
            var filter = [];
            $('.'+class_name+':checked').each(function(){
                filter.push($(this).val());
            });
            return filter;
        }
    
    $('.common_selector').click(function(){
        filter_items();
    });
    
    });

//US RETAIL ITEMS
$(document).ready(function(){

    filter_items();
    
    function filter_items()
        {
            $('.filter_us_retail').html('<div id="loading" style="" ></div>');  
            var action = 'fetch_data';
            var a4 = get_filter('a4');
            var a3 = get_filter('a3');
            var xl = get_filter('xl');
            $.ajax({
                url:"../filters/us-retail",
                method:"POST",
                data:{action:action, a4:a4, a3:a3, xl:xl},
                success:function(data){
                    $('.filter_us_retail').html(data);
                }
            });
        }
    
    function get_filter(class_name)
        {
            var filter = [];
            $('.'+class_name+':checked').each(function(){
                filter.push($(this).val());
            });
            return filter;
        }
    
    $('.common_selector').click(function(){
        filter_items();
    });
    
    });

    //SCRAP
    $(document).ready(function(){

        filter_scrap();
        
        function filter_scrap()
            {
                $('.filter_scrap').html('<div id="loading" style="" ></div>');  
                var action = 'fetch_data';
                $.ajax({
                    url:"../filters/scrap",
                    method:"POST",
                    data:{action:action},
                    success:function(data){
                        $('.filter_scrap').html(data);
                    }
                });
            }
        
        /*function get_filter(class_name)
            {
                var filter = [];
                $('.'+class_name+':checked').each(function(){
                    filter.push($(this).val());
                });
                return filter;
            }*/
        
        $('.common_selector').click(function(){
            filter_scrap();
        });
        
        });
//RETAILERS
$(document).ready(function(){

    filter_retailers();
    
    function filter_retailers()
        {
            $('.filter_retailers').html('<div id="loading" style="" ></div>');  
            var action = 'fetch_data';
            var state = get_filter('state');
            var country = get_filter('country');
            $.ajax({
                url:"../filters/retailers",
                method:"POST",
                data:{action:action, state:state, country:country},
                success:function(data){
                    $('.filter_retailers').html(data);
                }
            });
        }
    
    function get_filter(class_name)
        {
            var filter = [];
            $('.'+class_name+':checked').each(function(){
                filter.push($(this).val());
            });
            return filter;
        }
    
    $('.common_selector').click(function(){
        filter_retailers();
    });
    
    });

