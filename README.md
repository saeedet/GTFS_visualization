# GTFS_visualization

GTFS feed provides a common format for public transport timetable and related geographical information while the GTFS-R provides the information regarding the vehicle’s position at a given time and the updates happened for scheduled stop times as well as the alerts for scenarios when a major delay or canceled trip is happened. This project objective is to demonstrate the potentials of the mentioned provided feeds by designing data visualization tools that display analytical patters of the transit services from which an in-depth insight of the network’s performance can be gained.

---

# Instruction to run the project

In order to run the project you need to load the database design which is located in GTFS folder "990project.sql".

Then, install modules in GTFS folder by running "npm install" in cmd.

Next, run node by the following command "node index.js".

At the end you need to have 2 API keys for this project which you can find online how to do that.

A google API key to include it in "./service.php" file line 822 where it shows "YOUR_API_KEY".

A GTFS API key to include it in "./GTFS/index.js" file line 23 where it shows "YOUR_API_KEY".

You are all set now and you can play around with trains data.
