<?php
include("./ex/functions.php");

$markers = give_me_all_stops_location_with_traffic();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Transit</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">
  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700|Raleway:300,400,400i,500,500i,700,800,900" rel="stylesheet">
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="lib/nivo-slider/css/nivo-slider.css" rel="stylesheet">
  <link href="lib/owlcarousel/owl.carousel.css" rel="stylesheet">
  <link href="lib/owlcarousel/owl.transitions.css" rel="stylesheet">
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/venobox/venobox.css" rel="stylesheet">
  <link href="css/nivo-slider-theme.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/responsive.css" rel="stylesheet">
  <!-- Page CSS -->
  <link rel="stylesheet" type="text/css" href="css/service.css">
  <!-- GOOGLE CHARTS -->
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>

<body data-spy="scroll" data-target="#navbar-example">

  <div id="preloader"></div>
  <div class="se-pre-con"></div>
  <header>
    <!-- header-area start -->
    <div  class="header-area stick">
      <div class="container">
        <div class="row">
          <div class="col-md-12 col-sm-12">

            <!-- Navigation -->
            <nav class="navbar navbar-default">
              <!-- Brand and toggle get grouped for better mobile display -->
              <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Brand -->
                <a class="navbar-brand page-scroll sticky-logo" href="index.html">
                  <h1><span>Silk</span>Road Transit</h1>
                </a>
              </div>
              <!-- Collect the nav links, forms, and other content for toggling -->
              <div class="collapse navbar-collapse main-menu bs-example-navbar-collapse-1" id="navbar-example">
                <ul class="nav navbar-nav navbar-right">
                  <li class="active">
                    <a class="page-scroll"  href="/index.html">Home</a>
                  </li>
                  <li>
                      <a href="">Logout</a>
                  </li>
                </ul>
              </div>
              <!-- navbar-collapse -->
            </nav>
            <!-- END: Navigation -->
          </div>
        </div>
      </div>
    </div>
    <!-- header-area end -->
  </header>

  <div class="blog-page area-padding">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
          <div class="page-head-blog">
            <div class="single-blog-page">
            </div>
            <div class="single-blog-page" style="padding-top: 25px;">
              <!-- recent start -->
              <div class="left-blog" style="min-height: 800px;">
                <h4 style="text-transform: capitalize; text-align: center;">Analysis Dashboard</h4>
                <div class="recent-post">
                  <div class="recent-single-post">
                    <div class="pst-content">
                      <span class="custom-dropdown big">
                        <!-- Modes drop-down -->
                        <select onchange="filter(this.value)">
                            <option value="google_map">Map Mode</option>
                            <option value="density_mode">Density Mode</option>
                            <option value="delay_mode">Delay Mode</option>
                        </select>
                        <!-- End Modes drop-down-->
                    </span>
                    </div>
                  </div>
                  <div class="recent-single-post">
                    <div class="pst-content">
                      <span class="custom-dropdown big">
                        <!-- Week days Options -->
                        <select id="weekdays_options" onchange="changeDay(this.value)">
                            <option value="<?php echo date("N")-1 ?> ">Today</option>
                            <option value="0">Monday</option>
                            <option value="1">Tuesday</option>
                            <option value="2">Wednesday</option>
                            <option value="3">Thursday</option>
                            <option value="4">Friday</option>
                            <option value="5">Saturday</option>
                            <option value="6">Sunday</option>
                        </select>
                        <!-- END Week days Options -->
                    </span>
                    </div>
                  </div>
                  <!-- CHART MODES OPTIONS -->
                  <div class="chart-icons">
                    <ul>
                      <li>
                        <a href="#" onclick="changeChart('h_chart')"><i class="fa fa-align-left"></i></a>
                      </li>
                      <li>
                        <a href="#" onclick="changeChart('v_chart')"><i class="fa fa-bar-chart"></i></a>
                      </li>
                      <li>
                        <a href="#" onclick="changeChart('l_chart')"><i class="fa fa-line-chart"></i></a>
                      </li>
                    </ul>
                  </div>
                  <!--END CHART MODES OPTIONS -->

                  <!--========================================== MAP MODE dashboard -->
                  <div class="recent-single-post" id="map_dash">
                    <div class="tab">
                      <button class="tablinks" onclick="openCity(event, 'views')" id="defaultOpen">Views</button>
                      <button class="tablinks" onclick="openCity(event, 'filters')">Filters</button>
                    </div>
                    <!-- views ---->
                    <div id="views" class="tabcontent">
                      <ul style="list-style: none;">
                        <li id="trafficPlatforms_view"><a href="#" onclick="filter('trafficPlatforms_view')">Platforms with traffic</a></li>
                        <li id="allPlatforms_view"><a href="#" onclick="filter('allPlatforms_view')">All platforms</a></li>
                        <li id="allStations_view"><a href="#" onclick="filter('allStations_view')">All stations</a></li>
                      </ul>
                    </div>
                    <!-- filters -->
                    <div id="filters" class="tabcontent" style="min-height: 350px;">
                      <p style="margin-bottom: 5px; padding-top: 5px;"><b>Search by Name</b></p>
                      <input type="text" id="search-box" name="keyword" placeholder="keyword..." autocomplete="off" value="">
                      <div id="suggesstion-box" class="autocomplete-items" style="width: 100%;  margin-top: opx;"></div>
                      <div style="padding-top: 107px;">
                        <hr style="margin-bottom: 8px; border-color: black;">
                        <p style="font-size: 12px;">Search Platforms or Stations to get more information!</p>
                      </div>
                    </div>
                  </div>

                  <!--========================================== DENSITY MODE dashboard -->
                  <div class="recent-single-post" id="chart_dash">
                    <div class="tab">
                      <button class="tablinks" onclick="openCity(event, 'chart_view')" id="defaultOpen2">Views</button>
                      <button class="tablinks" onclick="openCity(event, 'chart_filter')">Filters</button>
                    </div>
                    <!-- views ---->
                    <div id="chart_view" class="tabcontent">
                      <ul style="list-style: none;">
                        <li id="stations_density"><a href="#" onclick="filter('stations_density')">Dense Stations</a></li>
                        <li id="platforms_density"><a href="#" onclick="filter('platforms_density')">Dense Platforms</a></li>
                        <li id="routes_density"><a href="#" onclick="filter('routes_density')">Dense Routes</a></li>
                      </ul>
                    </div>
                    <!-- filters -->
                    <div id="chart_filter" class="tabcontent">
                      <p style="margin-bottom: 5px; padding-top: 5px;"><b>Search by Name</b></p>
                      <input type="text" id="search-box2" name="keyword" placeholder="keyword..." autocomplete="off" value="">
                      <div id="suggesstion-box2" class="autocomplete-items" style="width: 100%;  margin-top: opx;"></div>
                      <div style="padding-top: 107px;">
                        <hr style="margin-bottom: 8px; border-color: black;">
                        <p style="font-size: 12px;">Search Platforms or Stations to get daily density information!</p>
                      </div>
                    </div>
                  </div>

                  <!--========================================== DELAY MODE dashboard -->
                  <div class="recent-single-post" id="delay_dash">
                    <div class="tab">
                      <button class="tablinks" onclick="openCity(event, 'delay_view')" id="defaultOpen3">Views</button>
                      <button class="tablinks" onclick="openCity(event, 'delay_filter')">Filters</button>
                    </div>
                    <!-- views ---->
                    <div id="delay_view" class="tabcontent">
                      <ul style="list-style: none;">
                        <li id="hourly_delays"><a href="#" onclick="filter('hourly_delays')">Delays per Hour</a></li>
                        <li id="delayed_entity"><a href="#" onclick="filter('delayed_entity')">Delayed Entity</a></li>
                        <li id="delay_cause"><a href="#" onclick="filter('delay_cause')">Delays Causes</a></li>
                        <li id="delay_effect"><a href="#" onclick="filter('delay_effect')">Delays Effects</a></li>
                      </ul>
                    </div>
                    <!-- filters -->
                    <div id="delay_filter" class="tabcontent">
                      <p style="margin-bottom: 5px; padding-top: 5px;"><b>Search by Name</b></p>
                      <input type="text" id="search-box3" name="keyword" placeholder="keyword..." autocomplete="off" value="">
                      <div id="suggesstion-box3" class="autocomplete-items" style="width: 100%;  margin-top: opx;"></div>
                      <div style="padding-top: 107px;">
                        <hr style="margin-bottom: 8px; border-color: black;">
                        <p style="font-size: 12px;">Search Routes to get daily delay information!</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- End left sidebar -->
        <!-- Start VISUALIZATION SECTION -->
        <div class="col-md-9 col-sm-9 col-xs-12" style="padding-top: 25px;">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <!-- single-blog start -->
              <article class="blog-post-wrapper">
                <div class="post-thumbnail">
                  <!-- <img src="img/stat.jpg" alt="" /> -->
                </div>
                <div class="post-thumbnail">
                  <!-- <img src="img/stat2.jpg" alt="" /> -->
                </div>
              <h3 style="text-align: center;">Sydney Transit Data</h3>
              <h5>Visualization</h5>
              <!-- MAP and CHARTS container -->
              <div id="map"></div>
              <!-- TABLE container -->
              <h5 id="data_header"></h5>
              <div id="table_show"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End VISUALIZATION Area -->
  <div class="clearfix"></div>


  <!-- Start Footer  Area -->
  <footer>
    <div class="footer-area">
      <div class="container">
        <div class="row">
          <div class="col-xs-12" style="text-align: center;">
            <div class="footer-content">
              <div class="footer-head">
                <div class="footer-logo">
                  <h2><span>Silk</span> Road</h2>
                </div>

                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem ac
                    cusantium doloremque laudantium,</p>
                <div class="footer-icons">
                  <ul>
                    <li>
                      <a href="#"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-google"></i></a>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-pinterest"></i></a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <!-- end single footer -->

        </div>
      </div>
    </div>
    <div class="footer-area-bottom">
      <div class="container">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="copyright text-center">
              <p>
                &copy; Copyright <strong>Silk Road</strong>. All Rights Reserved
              </p>
            </div>
            <div class="credits">

              Designed by <a href="#">Saeed ET</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!-- JavaScript Libraries -->
  <!-- <script src="https://use.fontawesome.com/99f6a1f1e7.js"></script> -->
  <script src="js/jquery.js"></script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="lib/venobox/venobox.min.js"></script>
  <script src="lib/knob/jquery.knob.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <script src="lib/parallax/parallax.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/nivo-slider/js/jquery.nivo.slider.js" type="text/javascript"></script>
  <script src="lib/appear/jquery.appear.js"></script>
  <script src="lib/isotope/isotope.pkgd.min.js"></script>

  <!-- Contact Form JavaScript File -->
  <script src="contactform/contactform.js"></script>

  <script src="js/main.js"></script>
  <script type="text/javascript">
    // Declare all needed variables
    var barData, tableData, tableColumn1, tableColumn2;
    var map_zoom = 10;
    var map_center = {lat:-33.8688,lng:151.2093};
    var markersData = <?php  echo  json_encode($markers);  ?>;
    var d = new Date();
    var weekday = d.getDay() -1
    document.getElementById("defaultOpen").click();

    //function for view tabs change
    function openCity(evt, tabName) {
      // Declare all variables
      var i, tabcontent, tablinks;

      // Get all elements with class="tabcontent" and hide them
      tabcontent = document.getElementsByClassName("tabcontent");
      for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
      }

      // Get all elements with class="tablinks" and remove the class "active"
      tablinks = document.getElementsByClassName("tablinks");
      for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
      }

      // Show the current tab, and add an "active" class to the button that opened the tab
      document.getElementById(tabName).style.display = "block";
      evt.currentTarget.className += " active";
    }
    // function for changin the charts
    function changeChart(mod){
      if (mod == 'h_chart') {
        drawStuff();
      }
      else if (mod == 'v_chart') {
        drawVertical();
      }
      else if (mod == 'l_chart'){
        google.charts.load("current", {packages: ["line"]});
        google.charts.setOnLoadCallback(lineChart);
        lineChart();
      }
    }
    // JS for drawing the map
    function initMap(){
      // Map options
      var options = {
        zoom: map_zoom,
        center: map_center
      }

      // New map
      var map = new google.maps.Map(document.getElementById('map'), options);

      // Array of markers
      var markers = markersData
      // Loop through markers
      for(var i = 0;i < markers.length;i++){
        // Add marker
        addMarker(markers[i]);
      }

      // Add Marker Function
      function addMarker(props){
        var marker = new google.maps.Marker({
          position:props.coords,
          map:map,
          //icon:props.iconImage
        });

        // Check for customicon
        if(props.iconImage){
          // Set icon image
          marker.setIcon(props.iconImage);
        }

        // Check content
        if(props.content){
          var infoWindow = new google.maps.InfoWindow({
            content:props.content
          });

          marker.addListener('click', function(){
            infoWindow.open(map, marker);
          });
        }
      }
    }
    // day definition
    function changeDay(vall){
      weekday = vall;
    }
    // filter function
    function filter(item){

      // ---------- MAP mode--------------------------------//
      if(item == 'trafficPlatforms_view' || item == 'allStations_view' || item == 'allPlatforms_view' || item == 'google_map'){
        $('#table_show').empty();
        if ($('#map_dash').css('display') != 'block') {
          $('#chart_dash').css('display', 'none');
          $('#delay_dash').css('display', 'none');
          $('#map_dash').css('display', 'block');
          document.getElementById("defaultOpen").click();
        }
        if (document.getElementById("data_header").innerHTML == "Data") {
          document.getElementById("data_header").innerHTML = "";
        }
        if ($('#weekdays_options').css('visibility') != 'hidden') {
          $('#weekdays_options').css('visibility','hidden');
        }
        if ($('.chart-icons').css('visibility') != 'hidden') {
          $('.chart-icons').css('visibility','hidden');
        }
        // taking care of borders
        $('#trafficPlatforms_view').css('border', 'none');
        $('#allStations_view').css('border', 'none');
        $('#allPlatforms_view').css('border', 'none');
        if (item == 'google_map') {
          $('#trafficPlatforms_view').css('border', '2px solid #3ec1d5');
        }
        else{
          var selectedId = '#'+item
          $(selectedId).css('border', '2px solid #3ec1d5');
        }
        // ajax request
        $.ajax({
            type: "POST",
            url: "ex/filter.php",
            beforeSend: function(){
              $('#map').empty();
              $('.se-pre-con').css('visibility','visible');
            },
            data: { value: item},
            success:function(data){
              $('.se-pre-con').css('visibility','hidden');
              markersData = data;
              map_zoom = 10;
              map_center = {lat:-33.8688,lng:151.2093};
              initMap()
            },
            error:function(data){
              console.log(data)
            },
            dataType:"json"
        });
      }
      // ---------- DENSITY mode--------------------------------//
      else if (item == 'density_mode' || item == 'stations_density' || item == 'platforms_density' || item == 'routes_density'){
        $('#table_show').empty();
        if ($('#chart_dash').css('display') != 'block') {
          $('#chart_dash').css('display', 'block')
          $('#map_dash').css('display', 'none')
          $('#delay_dash').css('display', 'none')
          document.getElementById("defaultOpen2").click();
        }
        if (document.getElementById("data_header").innerHTML == "") {
          document.getElementById("data_header").innerHTML = "Data";
        }
        if ($('#weekdays_options').css('visibility') != 'visible') {
          $('#weekdays_options').css('visibility','visible');
        }
        if ($('.chart-icons').css('visibility') != 'visible') {
          $('.chart-icons').css('visibility','visible');
        }
        // taking care of borders
        $('#stations_density').css('border', 'none');
        $('#platforms_density').css('border', 'none');
        $('#routes_density').css('border', 'none');
        if (item == 'density_mode') {
          $('#stations_density').css('border', '2px solid #3ec1d5');
        }
        else{
          var selectedId2 = '#'+item
          $(selectedId2).css('border', '2px solid #3ec1d5');
        }
        // ajax request
        $.ajax({
            type: "POST",
            url: "ex/filter.php",
            beforeSend: function(){
              $('#map').empty();
              $('.se-pre-con').css('visibility','visible');
            },
            data: { value: item, day:weekday},
            success:function(data){
              $('.se-pre-con').css('visibility','hidden');
              barData = data;
              // console.log(data)
              google.charts.load('current', {'packages':['bar']});
              google.charts.setOnLoadCallback(drawStuff);
              tableColumn1 = data[0][0];
              tableColumn2 = data[0][1];
              tableData = data.slice(0);
              tableData.shift();
              google.charts.load('current', {'packages':['table']});
              google.charts.setOnLoadCallback(drawTable);
            },
            error:function(data){
              console.log(data)
            },
            dataType:"json"
        });
      }
      // ---------- DELAY mode--------------------------------//
      else if ( item == 'delay_mode' || item == 'delayed_entity' || item == 'delay_cause' || item == 'delay_effect' || item == 'hourly_delays'){
        $('#table_show').empty();
        if ($('#delay_dash').css('display') != 'block') {
          $('#chart_dash').css('display', 'none')
          $('#map_dash').css('display', 'none')
          $('#delay_dash').css('display', 'block')
          document.getElementById("defaultOpen3").click();
        }
        if (document.getElementById("data_header").innerHTML == "") {
          document.getElementById("data_header").innerHTML = "Data";
        }
        if ($('#weekdays_options').css('visibility') != 'hidden') {
          $('#weekdays_options').css('visibility','hidden');
        }
        if ($('.chart-icons').css('visibility') != 'visible') {
          $('.chart-icons').css('visibility','visible');
        }
        // taking care of borders
        $('#hourly_delays').css('border', 'none');
        $('#delayed_entity').css('border', 'none');
        $('#delay_cause').css('border', 'none');
        $('#delay_effect').css('border', 'none');
        if (item == 'delay_mode') {
          $('#hourly_delays').css('border', '2px solid #3ec1d5');
        }
        else{
          var selectedId3 = '#'+item
          $(selectedId3).css('border', '2px solid #3ec1d5');
        }
        // ajax request
        $.ajax({
            type: "POST",
            url: "ex/filter.php",
            beforeSend: function(){
              $('#map').empty();
              $('.se-pre-con').css('visibility','visible');
            },
            data: { value: item},
            success:function(data){
              $('.se-pre-con').css('visibility','hidden');
              barData = data;
              // console.log(data)
              google.charts.load('current', {'packages':['bar']});
              google.charts.setOnLoadCallback(drawStuff);
              tableColumn1 = data[0][0];
              tableColumn2 = data[0][1];
              tableData = data.slice(0);
              tableData.shift();
              google.charts.load('current', {'packages':['table']});
              google.charts.setOnLoadCallback(drawTable);
            },
            error:function(data){
              console.log(data)
            },
            dataType:"json"
        });
      }
    }
    // keyword search
    $(document).ready(function(){
        $("#search-box").keyup(function(event){
          if (event.keyCode === 13) {
            selectText(document.getElementById("search-box").value);
          }
          $.ajax({
            type: "POST",
            url: "./ex/auto_complete.php",
            data:'keyword='+$(this).val(),

            success: function(data){
                $("#suggesstion-box").show();
                $("#suggesstion-box").html(data);
                $("#search-box").css("background","#FFF");
            }
          });
        });
        $("#search-box2").keyup(function(event){
          if (event.keyCode === 13) {
            selectText2(document.getElementById("search-box2").value);
          }
          $.ajax({
            type: "POST",
            url: "./ex/auto_complete.php",
            data:'keyword2='+$(this).val(),

            success: function(data){
                $("#suggesstion-box2").show();
                $("#suggesstion-box2").html(data);
                $("#search-box2").css("background","#FFF");
            }
          });
        });
        $("#search-box3").keyup(function(event){
          if (event.keyCode === 13) {
            selectText3(document.getElementById("search-box3").value);
          }
          $.ajax({
            type: "POST",
            url: "./ex/auto_complete.php",
            data:'keyword3='+$(this).val(),

            success: function(data){
                $("#suggesstion-box3").show();
                $("#suggesstion-box3").html(data);
                $("#search-box3").css("background","#FFF");
            }
          });
        });
    });
    // for maps
    function selectText(val) {
        $("#search-box").val(val);
        $("#suggesstion-box").hide();
        $.ajax({
            type: "POST",
            url: "ex/filter.php",
            beforeSend: function(){
              $('#map').empty();
              $('.se-pre-con').css('visibility','visible');
            },
            data: { filter: val},
            success:function(data){
              $('.se-pre-con').css('visibility','hidden');
              markersData = data;
              // console.log(data)
              map_zoom = 15;
              map_center = {lat: data[0]['coords'].lat ,lng: data[0]['coords'].lng};
              initMap()
            },
            error:function(data){
              console.log(data)
            },
            dataType:"json"
        });
    }
    //for charts
    function selectText2(val) {
        $("#search-box2").val(val);
        $("#suggesstion-box2").hide();
        $.ajax({
          type: "POST",
          url: "ex/filter.php",
          beforeSend: function(){
            $('#map').empty();
            $('#table_show').empty();
            $('.se-pre-con').css('visibility','visible');
          },
          data: { filter2: val},
          success:function(data){
            $('.se-pre-con').css('visibility','hidden');
            barData = data;
            // console.log(data)
            google.charts.load('current', {'packages':['bar']});
            google.charts.setOnLoadCallback(drawStuff);
            tableColumn1 = data[0][0];
            tableColumn2 = data[0][1];
            tableData = data.slice(0);
            tableData.shift();
            google.charts.load('current', {'packages':['table']});
            google.charts.setOnLoadCallback(drawTable);
          },
          error:function(data){
            console.log(data)
          },
          dataType:"json"
        });
    }
    function selectText3(val) {
        $("#search-box3").val(val);
        $("#suggesstion-box3").hide();
        $.ajax({
          type: "POST",
          url: "ex/filter.php",
          beforeSend: function(){
            $('#map').empty();
            $('#table_show').empty();
            $('.se-pre-con').css('visibility','visible');
          },
          data: { filter3: val},
          success:function(data){
            $('.se-pre-con').css('visibility','hidden');
            barData = data;
            // console.log(data)
            google.charts.load('current', {'packages':['bar']});
            google.charts.setOnLoadCallback(drawStuff);
            tableColumn1 = data[0][0];
            tableColumn2 = data[0][1];
            tableData = data.slice(0);
            tableData.shift();
            google.charts.load('current', {'packages':['table']});
            google.charts.setOnLoadCallback(drawTable);
          },
          error:function(data){
            console.log(data)
          },
          dataType:"json"
        });
    }
    /////////////////// JS for charts
    //vertical bars
    function drawVertical(){
      var data = new google.visualization.arrayToDataTable(barData);

      var options = {
        title: '',
        width: 900,
        legend: { position: 'none' },
        chart: { title: '',
                 subtitle: '' },
        bars: 'vertical', // Required for Material Bar Charts.
        axes: {
          x: {
            0: { side: 'bottom', label: barData[0][0]} // Top x-axis.
          },
          y: {
            0: {side: 'left', label: barData[0][1]}
          }
        },
        bar: { groupWidth: "90%" }
      };

      var chart = new google.charts.Bar(document.getElementById('map'));
      chart.draw(data, options);
    }
    //horizontal bars
    function drawStuff() {
      var data = new google.visualization.arrayToDataTable(barData);

      var options = {
        title: '',
        width: 900,
        legend: { position: 'none' },
        chart: { title: '',
                 subtitle: '' },
        bars: 'horizontal', // Required for Material Bar Charts.
        axes: {
          x: {
            0: { side: 'top', label: barData[0][1]} // Top x-axis.
          },
          y: {
            0: {side: 'left', label: barData[0][0]}
          }
        },
        bar: { groupWidth: "90%" }
      };

      var chart = new google.charts.Bar(document.getElementById('map'));
      chart.draw(data, options);
    };
    //line chart
    function lineChart() {
      var data = new google.visualization.arrayToDataTable(barData);

      var options = {
        title: '',
        width: 900,
        legend: { position: 'none' },
        chart: { title: '',
                 subtitle: '' },

        axes: {
          x: {
            0: { side: 'bottom', label: barData[0][1]} // Top x-axis.
          },
          y: {
            0: {side: 'left', label: barData[0][0]}
          }
        }
      };
      var chart = new google.charts.Line(document.getElementById('map'));
      chart.draw(data, options);
    };
    // JS for drawing table
    function drawTable() {

      var data = new google.visualization.DataTable();
      data.addColumn('string', tableColumn1);
      data.addColumn('number', tableColumn2);
      data.addRows(tableData);

      var options = {
        showRowNumber: true,
        width: '100%',
        height: '100%'
      };

      var table = new google.visualization.Table(document.getElementById('table_show'));
      table.draw(data, options);
    };
  </script>
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap&language=En">
  </script>
</body>

</html>
