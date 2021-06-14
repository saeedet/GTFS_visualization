<?php
include_once(__DIR__."./../database/connection_string.php");



// Entities that affected by Delays ( alert real-time)
function give_me_affected_entity(){
  GLOBAL $link;
  $result = array();

  $effect_list = [['STOP',0], ['TRIP',0], ['ROUTE',0]];

  $query = "SELECT `delays`.`type`, COUNT(type) AS total FROM `delays` GROUP BY `type`";
  $sql = mysqli_query($link,$query);
  while ($row = mysqli_fetch_array($sql)) {
      array_push($result, array((int)$row['type'], (int)$row['total']));
  }

  foreach ($result as $this_effect) {
    $effect_list[$this_effect[0]][1] = $this_effect[1];
  }
  array_unshift($effect_list, ['Affected Entity', 'Total']);
  return $effect_list;
}

// Effects of delays ( alert real-time)
function give_me_delays_effect(){
  GLOBAL $link;
  $result = array();

  $effect_list = [['NO_SERVICE',0], ['REDUCED_SERVICE',0], ['SIGNIFICANT_DELAYS',0], ['DETOUR',0], ['ADDITIONAL_SERVICE',0],
                  ['MODIFIED_SERVICE',0], ['OTHER_EFFECT',0], ['UNKNOWN_EFFECT',0], ['STOP_MOVED',0]];

  $query = "SELECT `delays`.`effect`, count(`effect`) as total FROM `delays` group by `delays`.`effect`";
  $sql = mysqli_query($link,$query);
  while ($row = mysqli_fetch_array($sql)) {
      array_push($result, array((int)$row['effect'], (int)$row['total']));
  }

  foreach ($result as $this_effect) {
    $effect_list[$this_effect[0] - 1][1] = $this_effect[1];
  }
  array_unshift($effect_list, ['Effect', 'Total']);
  return $effect_list;
}

// Causes of Delays ( alert real-time)
function give_me_delays_causes(){
  GLOBAL $link;
  $result = array();

  $cause_list = [['UNKNOWN_CAUSE',0], ['OTHER_CAUSE',0], ['TECHNICAL_PROBLEM',0], ['STRIKE',0], ['DEMONSTRATION',0], ['ACCIDENT',0],
                  ['HOLIDAY',0], ['WEATHER',0], ['MAINTENANCE',0], ['CONSTRUCTION',0], ['POLICE_ACTIVITY',0], ['MEDICAL_EMERGENCY',0]];

  $query = "SELECT `delays`.`cause`, count(`cause`) as total FROM `delays` group by `delays`.`cause`";
  $sql = mysqli_query($link,$query);
  while ($row = mysqli_fetch_array($sql)) {
      array_push($result, array((int)$row['cause'], (int)$row['total']));
  }

  foreach ($result as $this_cause) {
    $cause_list[$this_cause[0] - 1][1] = $this_cause[1];
  }
  array_unshift($cause_list, ['Cause', 'Total']);
  return $cause_list;
}

// Station density on a given day
function give_me_stations_density($day){

    GLOBAL $link;
    $result = array();
    $week_days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
    array_push($result, ['Station', 'Number of visits']);

    $query = "SELECT SUBSTRING_INDEX(`stops`.`stop_name`, 'Station', 1) AS StationName,COUNT(`stop_times`.`stop_id`) AS Density
                    FROM `stops` RIGHT JOIN `stop_times` ON `stops`.`stop_id` = `stop_times`.`stop_id`
                            JOIN `trips` ON `trips`.`trip_id` = `stop_times`.`trip_id` JOIN calendar ON calendar.service_id = trips.service_id
                                    WHERE `stop_times`.`trip_id` IS NOT NULL AND calendar.".$week_days[$day]." = 1 GROUP BY StationName ORDER BY Density DESC LIMIT 10";
    $sql = mysqli_query($link,$query);
    while ($row = mysqli_fetch_array($sql)) {
      array_push($result, array($row['StationName'], (int)$row['Density']));
    }
    return $result;
}

// platform density on a given day
function give_me_platforms_ex_density($day){
    GLOBAL $link;
    $result = array();
    $week_days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
    array_push($result, ['Platform', 'Number of visits']);

    $query = "SELECT `stops`.`stop_name` AS StopName,COUNT(`stop_times`.`stop_id`) AS Density
                    FROM `stops` RIGHT JOIN `stop_times` ON `stops`.`stop_id` = `stop_times`.`stop_id`
                            JOIN `trips` ON `trips`.`trip_id` = `stop_times`.`trip_id` JOIN calendar ON calendar.service_id = trips.service_id
                                    WHERE `stop_times`.`trip_id` IS NOT NULL AND calendar.".$week_days[$day]." = 1 GROUP BY StopName ORDER BY Density DESC LIMIT 10";
    $sql = mysqli_query($link,$query);
    while ($row = mysqli_fetch_array($sql)) {
      array_push($result, array($row['StopName'], (int)$row['Density']));
    }
    return $result;
}

// routes density on a given day
function give_me_routes_density($day){

    GLOBAL $link;
    $result = array();
    $week_days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
    array_push($result, ['Route', 'Number of Stops']);

    $query = "SELECT `routes`.`route_long_name` AS RouteName,COUNT(routes.route_id) AS Density
                    FROM `stops` RIGHT JOIN `stop_times` ON `stops`.`stop_id` = `stop_times`.`stop_id`
                            JOIN `trips` ON `trips`.`trip_id` = `stop_times`.`trip_id` JOIN calendar ON calendar.service_id = trips.service_id JOIN routes ON routes.route_id = trips.route_id
                                    WHERE `stop_times`.`trip_id` IS NOT NULL AND calendar.".$week_days[$day]." = 1 GROUP BY RouteName ORDER BY Density DESC LIMIT 10";
    $sql = mysqli_query($link,$query);
    while ($row = mysqli_fetch_array($sql)) {
      array_push($result, array($row['RouteName'], (int)$row['Density']));
    }
    return $result;
}

// Number of daily trains visit for a given platform or station
function give_me_station_daily_density($name){
  ini_set('max_execution_time', 300);
  GLOBAL $link;
  $result = array();
  array_push($result, [$name, 'Number of visits']);
  $week_days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
  foreach ($week_days as $this_day) {
    $query = "SELECT count(stop_times.stop_id) As density FROM stop_times JOIN stops ON stop_times.stop_id = stops.stop_id
                        JOIN trips ON trips.trip_id = stop_times.trip_id JOIN calendar ON calendar.service_id = trips.service_id
                                    WHERE calendar.". $this_day ." = 1 and stops.stop_name LIKE '".$name."%'";
    $sql = mysqli_query($link,$query);
    if ($row = mysqli_fetch_array($sql)) {
        array_push($result, array(ucfirst($this_day), (int)$row['density']));
    }
  }

  return $result;
}

// Number of delays per hour
function give_me_number_of_delays(){
  GLOBAL $link;
  $result = array();
  array_push($result, ['Time', 'Number of Delays']);
  $query = "SELECT CONCAT( STR_TO_DATE(stop_times.departure_time,'%H'), ' - ',
                DATE_ADD( STR_TO_DATE(stop_times.departure_time,'%H'), INTERVAL 1 HOUR ) ) as time,
                     IFNULL(sum( CASE WHEN stop_update.departureDelay > 1 THEN 1 ELSE 0 END ), 0 ) as numberDelayed
                        FROM stop_update JOIN stop_times ON stop_update.trip_id = stop_times.trip_id AND stop_update.stop_id = stop_times.stop_id
                            WHERE stop_times.TRIP_ID IN ( SELECT train_position.TRIP_ID FROM train_position
                                    WHERE FROM_UNIXTIME(train_position.Timestamp,'%Y-%m-%d') = CURDATE())
                                        GROUP BY HOUR(stop_times.departure_time)";
  $sql = mysqli_query($link,$query);
  while ($row = mysqli_fetch_array($sql)) {
      array_push($result, array($row['time'], (int)$row['numberDelayed']));
  }
  return $result;
}

// stations and platforms keyword search
function keyword_search($keyword){
    GLOBAL $link;
    $result = array();
    $query = "SELECT stop_name FROM `stops` WHERE stop_name LIKE '".$keyword."%' LIMIT 4";
    $sql = mysqli_query($link,$query);
    while ($row = mysqli_fetch_array($sql)) {
      array_push($result, $row['stop_name']);
    }
    return $result;
}

// All informations for a given station or platform name
function give_me_this_location_info($name){
    ini_set('max_execution_time', 800);
    GLOBAL $link;
    $result = array();
    $first_catch = array();
    $today_density = 0;
    $weekly_density = 0;
    $week_days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
    $today = $week_days[date("N")-1];

    $query = "SELECT `stops`.`stop_id`,`stops`.stop_lat,`stops`.stop_lon FROM `stops` WHERE stops.stop_name = '".$name."'";
    $sql = mysqli_query($link,$query);
    if ($row = mysqli_fetch_array($sql)) {
        array_push($first_catch, array('stop_id'=>$row['stop_id'],'lat' => (float)$row['stop_lat'],'lng' => (float)$row['stop_lon']));
    }

    #today density
    $query2 = "SELECT count(stop_times.stop_id) As density FROM stop_times JOIN stops ON stop_times.stop_id = stops.stop_id
                            JOIN trips ON trips.trip_id = stop_times.trip_id JOIN calendar ON calendar.service_id = trips.service_id
                                    WHERE calendar.". $today ." = 1 and stops.stop_name LIKE '".$name."%'";
    $sql2 = mysqli_query($link,$query2);
    if ($row = mysqli_fetch_array($sql2)) {
        $today_density = (int)$row['density'];
    }

    #weekly density
    foreach ($week_days as $this_day) {
        $query3 = "SELECT count(stop_times.stop_id) As density FROM stop_times JOIN stops ON stop_times.stop_id = stops.stop_id
                            JOIN trips ON trips.trip_id = stop_times.trip_id JOIN calendar ON calendar.service_id = trips.service_id
                                        WHERE calendar.". $this_day ." = 1 and stops.stop_name LIKE '".$name."%'";
        $sql3 = mysqli_query($link,$query3);
        if ($row = mysqli_fetch_array($sql3)) {
            $weekly_density += (int)$row['density'];
        }
    }


    array_push($result,array( "content" => "<ul><li>Name: <span class='map_loc'>".$name."</span></li><li>Today Density: <span class='map_loc'>".$today_density."</span></li><li>Weekly Density: <span class='map_loc'>".$weekly_density."</span></li></ul>",
                                "coords" => array("lat" => $first_catch[0]['lat'], 'lng' => $first_catch[0]['lng'])));

    return $result;
}

// all platforms locations that have traffic
function give_me_all_stops_location_with_traffic(){
  GLOBAL $link;
  $result = array();

  $query = "SELECT DISTINCT(stop_name),`stops`.stop_lat,`stops`.stop_lon FROM `stop_times` RIGHT JOIN `stops` ON stops.stop_id = stop_times.stop_id where stop_times.trip_id IS NOT NULL";
  $sql = mysqli_query($link,$query);
  while ($row = mysqli_fetch_array($sql)) {
      array_push($result, array('coords' => array('lat' => (float)$row['stop_lat'],'lng' => (float)$row['stop_lon']), 'content' => "<ul><li>Platform Name: <span class='map_loc'>".$row['stop_name']."</span></li></ul>"));
  }
  return $result;
}

// all stations locations
function give_me_all_stations_location(){
  GLOBAL $link;
  $result = array();

  $query = "SELECT `stops`.stop_lat,`stops`.stop_lon, stop_name AS StationName FROM `stops` WHERE `stops`.location_type = 1";
  $sql = mysqli_query($link,$query);
  while ($row = mysqli_fetch_array($sql)) {
      array_push($result, array('coords' => array('lat' => (float)$row['stop_lat'],'lng' => (float)$row['stop_lon']), 'content' => "<ul><li>Station Name: <span class='map_loc'>".$row['StationName']." Station</span></li></ul>"));
  }
  return $result;
}

// all platform locations
function give_me_all_stops_location(){
  GLOBAL $link;
  $result = array();

  $query = "SELECT stop_lat,stop_lon,stop_name FROM `990project`.`stops` WHERE `stops`.location_type = 0";
  $sql = mysqli_query($link,$query);
  while ($row = mysqli_fetch_array($sql)) {
      array_push($result, array('coords' => array('lat' => (float)$row['stop_lat'],'lng' => (float)$row['stop_lon']), 'content' => "<ul><li>Platform Name: <span class='map_loc'>".$row['stop_name']."</span></li></ul>"));
  }
  return $result;
}

// number of delays for a given route
function give_me_route_delay($route){
    GLOBAL $link;
    $result = array();
    array_push($result, ['Route Name', 'Number of Delays']);

    $query2 = "SELECT train_position.train_label, COUNT(train_position.train_label) AS Delays FROM train_position
                    WHERE trip_id IN (SELECT trip_id FROM `stop_update` GROUP BY trip_id HAVING AVG(stop_update.arrivalDelay) > 18)
                                                        AND `route_id` IN (SELECT route_id FROM ROUTES WHERE ROUTE_LONG_NAME = '".$route."')";

    $sql = mysqli_query($link,$query2);
    while ($row = mysqli_fetch_array($sql)) {
      array_push($result, array($row['train_label'], (int)$row['Delays']));
    }
    return $result;
}

// keyword search for route names
function route_keyword_search($keyword){
    GLOBAL $link;
    $result = array();
    $query = "SELECT route_long_name FROM `routes` WHERE route_long_name LIKE '".$keyword."%' LIMIT 4";
    $sql = mysqli_query($link,$query);
    while ($row = mysqli_fetch_array($sql)) {
      array_push($result, $row['route_long_name']);
    }
    return $result;
}



?>
