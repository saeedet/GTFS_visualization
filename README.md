# GTFS_visualization

This is a website to fetch GTFS and GTFS-R data appropriately from the API and update it regularly.

All data will be stored in the designed database and will be showed to the user using different Google APIs including google Chart API.

---

In order to run the project you need to load the database design which is located in GTFS folder "990project.sql".

Then, install modules in GTFS folder by running "npm install" in cmd.

Next, run node by the following command "node index.js".

At the end you need to have 2 API keys for this project which you can find online how to do that.

A google API key to include it in "./service.php" file line 822 where it shows "YOUR_API_KEY".

A GTFS API key to include it in "./GTFS/index.js" file line 23 where it shows "YOUR_API_KEY".

You are all set now and you can play around with trains data.
