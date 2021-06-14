<?php
include_once("./functions.php");

if (isset($_POST['day'])) {
    $day = $_POST['day'];
}

if (isset($_POST['value'])) {

    $analysisMode = $_POST['value'];

    #Fetching data for MAP mode
    if ($analysisMode == 'allStations_view') {
        $markersData = give_me_all_stations_location();
        echo json_encode($markersData);
    }
    elseif ($analysisMode == 'trafficPlatforms_view' || $analysisMode == 'google_map') {
        $markersData = give_me_all_stops_location_with_traffic();
        echo json_encode($markersData);
    }
    elseif ($analysisMode == 'allPlatforms_view') {
        $markersData = give_me_all_stops_location();
        echo json_encode($markersData);
    }

    #Fetching data for DENSITY mode
    elseif ($analysisMode == 'density_mode' || $analysisMode == 'stations_density') {
        $barData = give_me_stations_density($day);
        echo json_encode($barData);
    }
    elseif ($analysisMode == 'platforms_density') {
        $barData = give_me_platforms_ex_density($day);
        echo json_encode($barData);
    }
    elseif ($analysisMode == 'routes_density') {
        $barData = give_me_routes_density($day);
        echo json_encode($barData);
    }

    #Fetching data for DELAY mode
    elseif ($analysisMode == 'delayed_entity'){
        $barData = give_me_affected_entity();
        echo json_encode($barData);
    }
    elseif ($analysisMode == 'delay_cause'){
        $barData = give_me_delays_causes();
        echo json_encode($barData);
    }
    elseif ($analysisMode == 'delay_effect'){
        $barData = give_me_delays_effect();
        echo json_encode($barData);
    }
    elseif ($analysisMode == 'hourly_delays' || $analysisMode == 'delay_mode'){
        $barData = give_me_number_of_delays();
        echo json_encode($barData);
    }
}

//Keyword result for MAP mode
elseif (isset($_POST['filter'])) {

    $filter_name = $_POST['filter'];
    $result = give_me_this_location_info($filter_name);
    echo json_encode($result);
}
//Keyword result for DENSITY mode
elseif (isset($_POST['filter2'])) {

    $filter_name = $_POST['filter2'];
    $result = give_me_station_daily_density($filter_name);
    echo json_encode($result);
}
//Keyword result for DELAY mode
elseif (isset($_POST['filter3'])) {

    $filter_name = $_POST['filter3'];
    $result = give_me_route_delay($filter_name);
    echo json_encode($result);
}


?>
