<html>
	<!-- <?php include 'secrets.php'; ?> -->
	<h1>Reset all data</h1>
		<form method="POST" action="reset-tables.php">
	 <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
	 <p><input type="submit" value="Reset" name="reset"></p>
	 </form>

<?php
	 include 'sql-fns.php';
if (isset($_POST['reset'])) {
	onReset();
}

function onReset() {
	global $db_conn;
	if (!connectToDB()) {
		echo 'failed to connect to db';
		return false;
	}

	executePlainSQL("DROP TABLE Bus CASCADE CONSTRAINTS");
	executePlainSQL("DROP TABLE Driver CASCADE CONSTRAINTS");
	executePlainSQL("DROP TABLE Skytrain CASCADE CONSTRAINTS");
	executePlainSQL("DROP TABLE SkytrainStation CASCADE CONSTRAINTS");
	executePlainSQL("DROP TABLE BusStop CASCADE CONSTRAINTS");
	executePlainSQL("DROP TABLE Zone CASCADE CONSTRAINTS");
	executePlainSQL("DROP TABLE CompassTap CASCADE CONSTRAINTS");
	executePlainSQL("DROP TABLE DriverAssignment CASCADE CONSTRAINTS");
	executePlainSQL("DROP TABLE AvailableStop CASCADE CONSTRAINTS");
	executePlainSQL("DROP TABLE BusModel1 CASCADE CONSTRAINTS");
	executePlainSQL("DROP TABLE BusModel2 CASCADE CONSTRAINTS");
	executePlainSQL("DROP TABLE SkytrainModel1 CASCADE CONSTRAINTS");
	executePlainSQL("DROP TABLE SkytrainModel2 CASCADE CONSTRAINTS");
	executePlainSQL("DROP TABLE Route1 CASCADE CONSTRAINTS");
	executePlainSQL("DROP TABLE Route2 CASCADE CONSTRAINTS");
	executePlainSQL("DROP TABLE Stop CASCADE CONSTRAINTS");

	OCICommit($db_conn);

	executePlainSQL("CREATE TABLE Bus(
	 id CHAR(10),
	 license_plate CHAR(6),
	 model CHAR(20),
	 route_name CHAR(50),
	 route_number CHAR(3),
	 PRIMARY KEY (id),
	 UNIQUE (license_plate)
	 )
	 ");

	executePlainSQL("CREATE TABLE Driver(
	 id CHAR(8),
	 first_name CHAR(30),
	 last_name CHAR(30),
	 PRIMARY KEY (id)
	 )
	 ");
	executePlainSQL("CREATE TABLE SkyTrain(
	 id CHAR(3),
	 route_name CHAR(50),
	 route_number CHAR(3),
	 model CHAR(20),
	 PRIMARY KEY (id)
	 )
	 ");
	executePlainSQL("CREATE TABLE SkyTrainStation(
	 stopid CHAR(10),
	 name CHAR(50),
	 platforms INT,
	 PRIMARY KEY (stopid, name)
	 )
	 ");
	executePlainSQL("CREATE TABLE BusStop(
	 stopid CHAR(10),
	 name CHAR(50),
	 PRIMARY KEY (stopid, name)
	 )
	 ");
	executePlainSQL("CREATE TABLE Stop(
	 id CHAR(10),
	 postcode CHAR(6),
	 city CHAR(20),
	 PRIMARY KEY (id)
	 )
	 ");
	executePlainSQL("CREATE TABLE Zone(
	 zone_number INT,
	 city CHAR(20),
	 PRIMARY KEY (city)
	 )
	 ");
	executePlainSQL("CREATE TABLE CompassTap(
	 card_id CHAR(20),
	 time INT,
	 stop CHAR(10),
	 PRIMARY KEY (card_id, time, stop)
	 )
	 ");
	// TODO
	executePlainSQL("CREATE TABLE DriverAssignment(
	 driver_id CHAR(8),
	 bus_id CHAR(10),
	 PRIMARY KEY (driver_id, bus_id)
	 )
     ");
	executePlainSQL("CREATE TABLE AvailableStop(
	 stop CHAR(10),
	 route_name CHAR(50),
	 route_number CHAR(3),
	 PRIMARY KEY (stop, route_name, route_number)
	 )
	 ");
	executePlainSQL("CREATE TABLE BusModel1(
	 name CHAR(20),
	 capacity INT,
	 fuel_type CHAR(5),
	 purchase_cost INT,
	 operating_cost INT,
	 PRIMARY KEY (name)
	 )
	 ");
	executePlainSQL("CREATE TABLE BusModel2(
	 purchase_cost INT,
	 operating_cost INT,
	 cost INT,
	 PRIMARY KEY (purchase_cost, operating_cost)
	 )
	 ");
	executePlainSQL("CREATE TABLE SkyTrainModel1(
	 name CHAR(20),
	 capacity INT,
	 cars INT,
	 purchase_cost INT,
	 operating_cost INT,
	 PRIMARY KEY (name)
	 )
	 ");
	executePlainSQL("CREATE TABLE SkyTrainModel2(
	 purchase_cost INT,
	 operating_cost INT,
	 cost INT,
	 PRIMARY KEY (purchase_cost, operating_cost)
	 )
	 ");
	executePlainSQL("CREATE TABLE Route1(
	 name CHAR(50),
	 route_number CHAR(3),
	 origin CHAR(10),
	 destination CHAR(10),
	 rail_type CHAR(20),
	 distance REAL,
	 PRIMARY KEY (name, route_number)
	 )
	 ");
	executePlainSQL("CREATE TABLE Route2(
	 route_number CHAR(3),
	 bus_route_type CHAR(20),
	 PRIMARY KEY (route_number)
	 )
	");
	OCICommit($db_conn);

	executePlainSQL("ALTER TABLE Bus
	ADD FOREIGN KEY (model) REFERENCES BusModel1 (name)
	 ON DELETE SET NULL
	");

	executePlainSQL("ALTER TABLE Bus
	ADD FOREIGN KEY (route_name, route_number) REFERENCES Route1 (name, route_number)
	 ON DELETE CASCADE
	");

	executePlainSQL("ALTER TABLE SkyTrain
	ADD FOREIGN KEY (model) REFERENCES SkytrainModel1 (name)
	 ON DELETE SET NULL
	");

	executePlainSQL("ALTER TABLE SkyTrain
	ADD FOREIGN KEY (route_name, route_number) REFERENCES Route1 (name, route_number)
	 ON DELETE CASCADE
	");

	executePlainSQL("ALTER TABLE SkyTrainStation
	ADD FOREIGN KEY (stopid) REFERENCES Stop (id)
	 ON DELETE CASCADE
	");

	executePlainSQL("ALTER TABLE BusStop
	ADD FOREIGN KEY (stopid) REFERENCES Stop (id)
	 ON DELETE CASCADE
	");

	executePlainSQL("ALTER TABLE Stop
	ADD FOREIGN KEY (city) REFERENCES Zone (city)
	 ON DELETE CASCADE
	");

	executePlainSQL("ALTER TABLE CompassTap
	ADD FOREIGN KEY (stop) REFERENCES Stop (id)
	 ON DELETE CASCADE
	 ");

	executePlainSQL("ALTER TABLE DriverAssignment
	ADD FOREIGN KEY (driver_id) REFERENCES Driver (id)
	 ON DELETE CASCADE
	");

	executePlainSQL("ALTER TABLE DriverAssignment
	ADD FOREIGN KEY (bus_id) REFERENCES Bus (id)
	 ON DELETE CASCADE
	");

	executePlainSQL("ALTER TABLE AvailableStop
	ADD FOREIGN KEY (stop) REFERENCES Stop (id)
	 ON DELETE CASCADE
	");

	executePlainSQL("ALTER TABLE Route1
	ADD FOREIGN KEY (origin) REFERENCES Stop (id)
	");

	executePlainSQL("ALTER TABLE AvailableStop
	ADD FOREIGN KEY (route_name, route_number) REFERENCES Route1 (name, route_number)
	 ON DELETE CASCADE
	");

	executePlainSQL("ALTER TABLE BusModel1
	ADD FOREIGN KEY (purchase_cost, operating_cost) REFERENCES BusModel2
	 (purchase_cost, operating_cost)
	");

	executePlainSQL("ALTER TABLE SkyTrainModel1
	ADD FOREIGN KEY (purchase_cost, operating_cost) REFERENCES SkytrainModel2
	 (purchase_cost, operating_cost)
	");

	executePlainSQL("ALTER TABLE Route1
	ADD FOREIGN KEY (destination) REFERENCES Stop (id)
	");

	executePlainSQL("ALTER TABLE Route1
	ADD FOREIGN KEY (route_number) REFERENCES Route2(route_number)
	");

	OCICommit($db_conn);
	disconnectFromDB();
}

?>
	</html>
