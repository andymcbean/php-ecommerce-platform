<?php
include '../includes/header.php';
include '../filters/retailers.php';
include '../filters/buttons.php';

$sql = "SELECT
              id,
              name,
              address,
              country,
              state,
              url1,
              fb
        FROM
              retailers
        ORDER by country DESC
        ";
//echo $sql;
$statement = $db->prepare($sql);
$statement->execute();
$result = $statement->fetchAll();
$output = "";
$url1 = "";
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
        $url1 = "<a href='".$row['url1']."' target='_blank'><i class='fas fa-globe fa-2x'></i></a>";
      }
    else
      {
        $url1 = "";
      }
    if($row['fb'] != '')
      {
        $fb = "<a href='".$row['fb']."' target='_blank'><i class='fab fa-facebook-square fa-2x'></i></a>";
      }
    else
      {
        $fb = "";
      }

    $output .= "<div class='col-12 col-md-4' style='margin-top: 15px;'>
                  <div class='card h-100'>
                    <div class='card-body'>
                        <h4 class='card-title'>".$name."</h4>
                        <h5 class='card-text'>".$country." ".$state."</h5>
                        <p class='card-text'>".$address."</p>
                        $url1
                        $fb
                    </div>
                  </div>
              </div>";
  }
?>
<div class="hero-image">
    <div class="hero-text">
      <h1 style="font-size:50px; color: #fff" class="nav-icon">Retailers</h1>
    </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=MAPS_API_KEY&callback=initMap"></script>

<div id="map" class="mt-3"></div>
<script id="jsbin-javascript">
var map;
function myMap() {
  var mapCanvas = document.getElementById("map");
  var mapOptions = {  
    center: new google.maps.LatLng(34.4227427,-2.7051041), 
    zoom: 3
    
  };
  map = new google.maps.Map(mapCanvas, mapOptions);
  
}


myMap();

var coords = {
  'Colorado': '38.8696205,-104.4537444',
  'Texas': '31.1003665,-104.5684904',
  'Georgia': '33.7678358,-84.4906438',
  'Nevada' : '38.9563755,-115.1352477',
  'Pennsylvania' : '41.1136189,-78.7257495',
  'Washington' : '46.9557289,-118.4007263',
  'Nebraska' : '41.3183298,-98.3861938',
  'Oregon' : '43.5728576,-118.6513425',
  'Missouri' : '38.3134821,-92.6916304',
  'Virginia' : '38.2974725,-78.4559893',
  'Ohio' : '40.3375464,-80.9021926',
  'Idaho' : '45.027297,-115.2685143',
  'Arkansas' : '34.7986816,-92.1263884',
  'New York' : '42.8738776,-75.4969937',
  'California' : '36.6661882,-117.5342069',
  'Virginia': '37.9517692,-78.8075518',
  'Tokyo': '35.6684415,139.6007843',
  'Schwerin': '53.6159958,11.3307932',
  'Wisconsin': '44.4921782,-90.1862177',
  'Ontario': '50.3214635,-84.7386798',
  'Wyoming': '42.982447,-109.7973754',
  'Florida': '28.4730177,-82.9358937',
  'Michigan': '43.7894645,-85.5469458'
};

function changeMap(city) {
  var c = coords[city].split(',');
  map.setCenter(new google.maps.LatLng(c[0], c[1]));
  map.setZoom(7);
}

var ncoords = {
  'A Quaint Market': '39.0158169,-104.7034878',
  'Vintage Retail Therapy By Mara': '33.3826671,-117.2533176',
  'TH Decor': '34.0180866,-84.4926917',
  'Boho Craft Co': '30.7646094,-97.7495617',
  'Recollection reliKS': '38.4549847,-78.0654621',
  'Simply Living @ The Vintage Industry': '44.046399,-122.9963994',
  'Queenie’s Painting Nook': '43.0330005,-78.8776511',
  'Extending Grace': '41.156712,-80.5717527',
  '2chattychicks.com': '34.8282912,-92.3841293',
  'Life Beautiful Design': '37.0477078,-93.3073162',
  'The Withered Barn': '43.6264099,-116.6350257',
  'Boutique Paint': '44.046513,-122.9965812',
  'WeJo Arts': '41.5754001,-75.2591414',
  'Craft of Hearts': '51.6105146,-3.3888458',
  'Ella Bella Vintage Finds': '48.3401063,-122.3420279',
  'Penny Farthing Bespoke': '36.2982292,-115.2828038',
  'Junkin Chic Treasures': '40.7856966,-97.8105998',
  'Simply Timeless': '36.6972179,-82.0123487',
  'Zakkaya Maeda (Rose-rose-rose)': '35.7155827,139.7088966',
  'Shabby AndreaM': '53.6462707,11.4207381',
  'Rapoza': '43.440254,-79.677318',
  'WildWind Treasures': '42.7602945,-105.3865989',
  'Anew Vintage Dream': '43.0041079,-89.0201857',
  'The Random Donkey':'39.2629135,-121.0228533',
  'Treasures': '26.5863416,-81.8766444',
  'The Vintage Farmhouse Market':'31.9955173,-81.115796',
  'Funkiture Gifts & DIY Studio':'34.9537008,-83.761436',
  'Kay Davis (Just For You)':'32.6111962,-93.2936621',
  'Sweet and Sassy Treasures':'42.6616,-83.0323286',
  'Lily’s Urban Home Interiors':'41.6737503,-83.7081228',
  'Gracious Seasons':'42.246559,-83.1934436',
  'Kims Painted Treasures':'39.6164429,-104.9889565'
  
};

function zoom(name) {
  var d = ncoords[name].split(',');
  map.setCenter(new google.maps.LatLng(d[0], d[1]));
  map.setZoom(16);
}
addMarker({
        coords:{lat:39.6164429, lng:-104.9889565},
        content:'<h6>Kims Painted Treasures<h6>'
        });
  addMarker({
        coords:{lat:42.6616, lng:-83.0323286},
        content:'<h6>Sweet and Sassy Treasures<h6>'
        });
  addMarker({
        coords:{lat:41.6737503, lng:-83.7081228},
        content:'<h6>Lily’s Urban Home Interiors<h6>'
        });
  addMarker({
        coords:{lat:42.246559, lng:-83.1934436},
        content:'<h6>Gracious Seasons<h6>'
        });
  addMarker({
        coords:{lat:31.99551731, lng:-81.115796},
        content:'<h6>The Vintage Farmhouse Marketa<h6>'
        });
  addMarker({
        coords:{lat:32.6111962, lng:-93.2936621},
        content:'<h6>Kay Davis (Just For You)<h6>'
        });
  addMarker({
        coords:{lat:34.9537008, lng:-83.761436},
        content:'<h6>Funkiture Gifts & DIY Studio<h6>'
        });
  addMarker({
        coords:{lat:33.3826671, lng:-117.2533176},
        content:'<h6>Vintage Retail Therapy By Mara<h6>'
        });
  addMarker({
        coords:{lat:26.5863416, lng:-81.8766444},
        content:'<h6>Treasures<h6>'
        });
  addMarker({
        coords:{lat:39.2629135, lng:-121.02285336},
        content:'<h6>The Random Donkey<h6>'
        });
  addMarker({
        coords:{lat:43.0041079, lng:-89.0201857},
        content:'<h6>Anew Vintage Dream<h6>'
        });
  addMarker({
        coords:{lat:42.7602945, lng:-105.3865989},
        content:'<h6>WildWind Treasures<h6>'
        });
  addMarker({
        coords:{lat:43.440254, lng:-79.677318},
        content:'<h6>Rapoza<h6>'
        });
  addMarker({
        coords:{lat:53.6462707, lng:11.4207381},
        content:'<h6>Shabby AndreaM<h6>'
        });
  addMarker({
        coords:{lat:35.7155827, lng:139.7088966},
        content:'<h6>Zakkaya Maeda (Rose-rose-rose)<h6>'
        });
  addMarker({
        coords:{lat:39.0158169, lng:-104.7034878},
        content:'<h6>A Quaint Market<h6>'
        });
  addMarker({
        coords:{lat:30.7646094, lng:-97.7495617},
        content:'<h6>Boho Craft Co<h6>'
        });
    addMarker({
        coords:{lat:38.4549847, lng:-78.0654621},
        content:'<h6>Recollection reliKS<h6>'
        });
    addMarker({
        coords:{lat:44.046399, lng:-122.9963994},
        content:'<h6>Simply Living @ The Vintage Industry<h6>'
        });
    addMarker({
        coords:{lat:36.6972179, lng:-82.0123487},
        content:'<h6>Simply Timeless<h6>'
        });
    addMarker({
        coords:{lat:43.0330005, lng:-78.8776511},
        content:'<h6>Queenie’s Painting Nook<h6>'
        });
    addMarker({
        coords:{lat:34.0180866, lng:-84.4926917},
        content:'<h6>TH Decor<h6>'
        });
    addMarker({
        coords:{lat:41.156712, lng:-80.5717527},
        content:'<h6>Extending Grace<h6>'
        });
    addMarker({
        coords:{lat:34.0179157, lng:-84.4949908},
        content:'<h6>Kats Vintage Korner<h6>'
        });
    addMarker({
        coords:{lat:34.8282912, lng:-92.3841293},
        content:'<h6>2chattychicks.com<h6>'
        });
    addMarker({
        coords:{lat:37.0477078, lng:-93.3073162},
        content:'<h6>Life Beautiful Design<h6>'
        });
    addMarker({
        coords:{lat:44.046513, lng:-122.9965812},
        content:'<h6>Boutique Paint<h6>'
        });
    addMarker({
        coords:{lat:43.6264099, lng:-116.6350257},
        content:'<h6>The Withered Barn<h6>'
        });
    addMarker({
        coords:{lat:41.5754001, lng:-75.2591414},
        content:'<h6>WeJo Arts<h6>'
        });
    addMarker({
        coords:{lat:48.3403575, lng:-122.3453387},
        content:'<h6>Ella Bella Vintage Finds<h6>'
        });
    addMarker({
        coords:{lat:51.6105146, lng:-3.3888458},
        content:'<h6>Craft of Hearts<h6>'
        });
    addMarker({
        coords:{lat:36.2982292, lng:-115.2828038},
        content:'<h6>Penny Farthing Bespoke<h6>'
        });  
    addMarker({
        coords:{lat:40.7856966, lng:-97.8105998},
        content:'<h6>Junkin Chic Treasures<h6>'
        });

function addMarker(props){
            var marker = new google.maps.Marker({
            position:props.coords,
            map:map
        });

        if(props.content){
            var infoWindow = new google.maps.InfoWindow({
            content:props.content
            });

            marker.addListener('click', function(){
                infoWindow.open(map, marker);
            });
        }
    }
</script>

<div class="container-fluid margin-top mb-5">
  <div class="row">
    <div class="col-12 col-md-2">
    
      <p><strong>Filter map by country/state</strong></p>
      <a class="btn btn-info btn-block mb-3" data-toggle="collapse" href="#collapse-country" role="button" aria-expanded="true" aria-controls="collapse-country">
        Country <i class="fas fa-chevron-circle-down"></i>
        </a>
          <div class="list-group collapse" id="collapse-country">
              <?php echo retailer_country() ?>
          </div>
        <a class="btn btn-info btn-block mb-3" data-toggle="collapse" href="#collapse-items" role="button" aria-expanded="true" aria-controls="collapse-items">
        State <i class="fas fa-chevron-circle-down"></i>
        </a>
        <div class="list-group collapse" id="collapse-items">
            <?php echo retailer_state() ?>
        </div> 
    </div>
    <div class="col-12 col-md-10 " id="units">
        <div class="row filter_retailers">
            <!--load ajax retailers-->
        </div>
    </div>
  <!--<div class="row">
        <?php //echo $output ?>
  -->
</div></div>
<?php
include '../includes/footer.php';
?>
<script>
//search product name
$('#search_button').click(function(){
var method = 'fetch_product';
var product = $('#product').val();
$.ajax({
    url: '../filters/retailers',
    method:"POST",
    data: {
        method:method,
        product:product
    },
    success:function(data){
        $('.filter_retailers').html(data);
    }
});
});
</script>
