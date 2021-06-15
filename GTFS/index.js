var GtfsRealtimeBindings = require('gtfs-realtime-bindings');
var request = require('request');
var express = require('express');
var app = express();
var fs = require('fs');
var unzip = require('unzip-stream');
var mysql = require('mysql');
var cors = require('cors');
// var port = process.env.PORT || 80;
var port = 80;
var path = require('path');
var schedule = require('node-schedule');



// Defining Configuration information
var requestSettings = {
  method: 'GET',
  url: '',
  encoding: null,
  headers: {
    'Accept': 'application/json',
    'Authorization': 'apikey YOUR_API_KEY'
    }
};
var feeds = [
    {
        id: 'RealtimeVehiclePosition',
        name: 'Realtime Vehicle Positions',
        url: 'https://api.transport.nsw.gov.au/v1/gtfs/vehiclepos/sydneytrains',
        method: 'GET'
    },
    {
        id: 'RealtimeTripUpdates',
        name: 'Realtime Trip Updates',
        url: 'https://api.transport.nsw.gov.au/v1/gtfs/realtime/sydneytrains',
        method: 'GET'
    },
    {
        id: 'RealtimeAlerts',
        name: 'Realtime Alerts',
        url: 'https://api.transport.nsw.gov.au/v1/gtfs/alerts/sydneytrains',
        method: 'GET'
    },
    {
        id: 'TimetablesCompleteGTFS',
        name: 'Sydney Trains GTFS Timetables',
        url: 'https://api.transport.nsw.gov.au/v1/gtfs/schedule/sydneytrains',
        method: 'GET',
        outputFile: 'sydneytrains.zip'
    }
];
var files = [
    {
        name: 'agency',
        filename: 'agency.txt',
    },
    {
        name: 'stop_times',
        filename: 'stop_times.txt',
    },
    {
        name: 'stops',
        filename: 'stops.txt',
    },
    {
        name: 'trips',
        filename: 'trips.txt',
    },
    {
        name: 'routes',
        filename: 'routes.txt',
    },
    {
        name: 'calendar',
        filename: 'calendar.txt',
    },
    {
        name: 'shapes',
        filename: 'shapes.txt',
    }
];
var databaseConfiguration = {
    host: "localhost",
    user: "root",
    password: "",
    database: "990project"
};
var con;
var feedStatus = ["OK", "OK", "OK", "OK"];



startMySQL();

requestGTFSFeed(0);
requestGTFSFeed(1);
requestGTFSFeed(2);
requestGTFSFeed(3);

/* SCHEDULED JOBS TO COMPLETE AFTER START */
schedule.scheduleJob({hour:02, minute:00}, function(){
    requestGTFSFeed(3);
});                // Update ZIP every day at 2am
setInterval(requestGTFSFeed, 15000, 0);                                       // Realtime Vehicle Positions (updates every 15 sec)
setInterval(requestGTFSFeed, 30000, 1);                                       // Realtime Trip Updates (updates every 30 sec)
setInterval(requestGTFSFeed, 600000, 2);                                      // Realtime Service Alerts (updates every 10 min)


count = con.query("SELECT COUNT(*) FROM delays", function(err, result){
    log("delays: "+JSON.stringify(result))
})
count = con.query("SELECT COUNT(*) FROM stop_update", function(err, result){
    log("stop_update: "+JSON.stringify(result))
})
count = con.query("SELECT COUNT(*) FROM train_position", function(err, result){
    log("train position: "+JSON.stringify(result))
})

// to get the info from the API
function requestGTFSFeed(type){
    requestSettings.url = feeds[type].url;
    request(requestSettings, function (error, response, body) {
        if (!error && response.statusCode == 200) {
            if (type == 3) {
                var feed = body;
            }
            else{
                var feed = GtfsRealtimeBindings.transit_realtime.FeedMessage.decode(body);
            }

            parseGTFSFeed(type, feed);
        }else{
            updateFeedStatus(type, "There was a problem retrieving "+feeds[type].name);
        }
    });
}
// to parse the info into the database
function parseGTFSFeed(type, feed){
    switch(type){
        // train position
        case 0:
            var vehiclePosition = [];
            for(x in feed.entity){
                var longitude = "", latitude = "";
                if(feed.entity[x].vehicle.position == null || feed.entity[x].vehicle.position.longitude == null || feed.entity[x].vehicle.position.latitude == null){
                    longitude = "";
                    latitude = "";
                }else{
                    longitude = feed.entity[x].vehicle.position.longitude;
                    latitude = feed.entity[x].vehicle.position.latitude;
                }
                vehiclePosition.push([
                    feed.entity[x].vehicle.vehicle.id,
                    feed.entity[x].vehicle.trip.tripId,
                    latitude,
                    longitude,
                    feed.entity[x].vehicle.stopId,
                    feed.entity[x].vehicle.trip.scheduleRelationship,
                    feed.entity[x].vehicle.congestionLevel,
                    feed.entity[x].vehicle.timestamp.low,
                    feed.entity[x].vehicle.trip.routeId,
                    feed.entity[x].vehicle.vehicle.label
                ]);
            }

            con.query("INSERT INTO `990project`.`train_position` VALUES ? ON DUPLICATE KEY UPDATE congestion_level = VALUES(congestion_level), position_lon = VALUES(position_lon), position_lat = VALUES(position_lat);", [vehiclePosition], function(err, result){
                if(err){
                    log("Insert train position Error: "+err);
                    return;
                }
                updateFeedStatus(type, "OK");
            });
            break;
        // trip update
        case 1:
            var stop_update = [];
            for(x in feed.entity){
                var id = feed.entity[x].tripUpdate.trip.tripId;
                var trainScheduleRelationship = feed.entity[x].tripUpdate.trip.scheduleRelationship;
                for(y in feed.entity[x].tripUpdate.stopTimeUpdate){
                    var arrivalTime = 0;
                    var arrivalDelay = 0;
                    var departureTime = 0;
                    var departureDelay = 0;
                    var stop_id;
                    var stopScheduleRelationship = 0;

                    if(feed.entity[x].tripUpdate.stopTimeUpdate[y].arrival){
                        arrivalTime = feed.entity[x].tripUpdate.stopTimeUpdate[y].arrival.time;          // So we don't get a null error
                        arrivalDelay = feed.entity[x].tripUpdate.stopTimeUpdate[y].arrival.delay;        // So we don't get a null error
                    }
                    if(feed.entity[x].tripUpdate.stopTimeUpdate[y].departure){
                        departureTime = feed.entity[x].tripUpdate.stopTimeUpdate[y].departure.time;      // So we don't get null error
                        departureDelay = feed.entity[x].tripUpdate.stopTimeUpdate[y].departure.delay;    // So we don't get a null error
                    }
                    if(feed.entity[x].tripUpdate.stopTimeUpdate[y].scheduleRelationship){
                        stopScheduleRelationship = feed.entity[x].tripUpdate.stopTimeUpdate[y].scheduleRelationship;
                    }

                    stopId = feed.entity[x].tripUpdate.stopTimeUpdate[y].stopId;
                    stop_update.push([
                        id,
                        stopId,
                        arrivalTime,
                        arrivalDelay,
                        departureTime,
                        departureDelay,
                        stopScheduleRelationship,
                        trainScheduleRelationship
                    ]);
                }
            }
            con.query("INSERT INTO `990project`.`stop_update` VALUES ? ON DUPLICATE KEY UPDATE arrivalTime = VALUES(arrivalTime), arrivalDelay = VALUES(arrivalDelay), departureTime = VALUES(departureTime), departureDelay = VALUES(departureDelay), trainScheduleRelationship = VALUES(trainScheduleRelationship), stopScheduleRelationship = VALUES(stopScheduleRelationship);", [stop_update], function(err, result){
                if(err){
                    log("Insert stop_update Error: "+err);
                    return;
                }
                updateFeedStatus(type, "OK");
            });
            break;
        // alerts
        case 2:

            var delays = [];
            for(delay in feed.entity){
                var delayType;
                var delayedEntity = "";
                var delayDescription = "";
                var delayHeader = "";
                var delayCause = "";
                var delayEffect = "";

                for(entity in feed.entity[delay].alert.informedEntity){
                    if(feed.entity[delay].alert.informedEntity[entity].stopId){
                        delayType = 0; // stop delay
                        delayedEntity = feed.entity[delay].alert.informedEntity[entity].stopId;
                    }else if(feed.entity[delay].alert.informedEntity[entity].trip){
                        delayType = 1; // trip delay
                        delayedEntity = feed.entity[delay].alert.informedEntity[entity].trip.tripId;
                    }else{
                        delayType = 2; // route delay
                        delayedEntity = feed.entity[delay].alert.informedEntity[entity].routeId;
                    }

                    if(feed.entity[delay].alert.descriptionText == null || feed.entity[delay].alert.descriptionText.translation[0].text == null){
                        delayDescription = "";
                    }else{
                        delayDescription = feed.entity[delay].alert.descriptionText.translation[0].text;
                    }
                    delayHeader = feed.entity[delay].alert.headerText.translation[0].text;
                    delayCause = feed.entity[delay].alert.cause;
                    delayEffect = feed.entity[delay].alert.effect;
                    delays.push([
                        delayType,
                        delayedEntity,
                        delayHeader,
                        delayDescription,
                        delayCause,
                        delayEffect
                    ]);

                }
            }
            con.query("INSERT INTO `990project`.`delays` VALUES ? ON DUPLICATE KEY UPDATE DESCRIPTION = VALUES(DESCRIPTION);", [delays], function(err, result){
                if(err){
                    log("Insert Delays Error: "+err);
                    return;
                }
                updateFeedStatus(type, "OK");
            });
            break;
        // static schedule tables
        case 3:
            fs.writeFile(feeds[type].outputFile, feed, function(err) {
                if(err){
                    log("Couldn't save the GTFS ZIP File");
                    return;
                }
                fs.createReadStream(feeds[type].outputFile).pipe(unzip.Extract({ path: feeds[type].id }))
                    .on('close', function (close) {
                    log("The ZIP file is downloaded & extracted successfully");
                    parseCSVtoDatabaseTables(type);
                });
            });
            break;
        default:
            log("There was a problem. The parse case was not in range (0-3)");
    }
}
// to parse timetables into the database
function parseCSVtoDatabaseTables(type){
    // Cleaning the database
    for(var i=0;i<files.length;i++){
        con.query("DELETE FROM "+files[i].name, function(err, result){
            if(err){
                log("Couldn't clean the database!!!");
                return;
            }
            log("Database is cleaned. Num of cleaned rows: " + result.affectedRows)
        })
    }
    // Inserting new timetables
    for (var jj = files.length - 1; jj > -1; jj--) {
        con.query("LOAD DATA LOCAL INFILE './TimetablesCompleteGTFS/"+files[jj].filename+"' INTO TABLE 990project."+files[jj].name+" FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES", function(err, result){
            if(err){
                log("Couldn't read the CSV file to the database");
                return;
            }
            log("Updated table in the database. Num of Changed Rows: " + result.affectedRows);
        })
    }
    updateFeedStatus(type, "OK");
    // log("The ZIP file is loaded to database successfully");
}
// connecting to the database
function startMySQL() {
    con = mysql.createConnection(databaseConfiguration);
    con.connect(function(err) {
        if(err) {
            console.log('Error connecting to the database:', err);
            setTimeout(startMySQL, 10000);
        }else{
            console.log("Database is connected");
            con.on('error', function(err) {
                if(err.code === 'PROTOCOL_CONNECTION_LOST'){
                    startMySQL();
                }else{
                    log("The database has lost connection... will attempt to reconnect shortly");
                }
            })
        }
    });
}
// to get the current time and date in an appropriate way
function getCurrentDate(){
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; // January is 0!
    var yyyy = today.getFullYear();

    if(dd<10) {
        dd = '0'+dd
    }
    if(mm<10) {
        mm = '0'+mm
    }
    return yyyy+"-"+mm+"-"+dd;
}
// Updates the feed status
function updateFeedStatus(feedNumber, status){
    feedStatus[feedNumber] = status;
    log(feeds[feedNumber].name+" Update: "+status);
}
// to log infos and statuses
function log(status){
    fs.appendFile('log.txt', new Date(Date.now()).toLocaleString()+' '+status+'\r\n', function (err) {
      if (err) console.log("There was a problem logging to the file: "+err);
    });
    console.log(new Date(Date.now()).toLocaleString()+' '+status);
}

function error(input){
    console.log('\x1b[31m%s\x1b[0m', input); // Show red console text
}                                     // Prints the input in a RED colour to the console
function blue(input){
    console.log('\x1b[35m%s\x1b[0m', input);  // Show blue console text
}                                      // Prints the input in a BLUE colour to the console
function warning(input){
    console.log('\x1b[33m%s\x1b[0m', input); // Show yellow console text
}                                   // Prints the input in a YELLOW colour to the console
function success(input){
    console.log('\x1b[32m%s\x1b[0m', input); // Show Green console text
}                                   // Prints the input in a GREEN colour to the console

