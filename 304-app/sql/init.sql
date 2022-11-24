DROP TABLE Bus CASCADE CONSTRAINTS;
DROP TABLE Driver CASCADE CONSTRAINTS;
DROP TABLE Skytrain CASCADE CONSTRAINTS;
DROP TABLE SkytrainStation CASCADE CONSTRAINTS;
DROP TABLE BusStop CASCADE CONSTRAINTS;
DROP TABLE Zone CASCADE CONSTRAINTS;
DROP TABLE CompassTap CASCADE CONSTRAINTS;
DROP TABLE DriverAssignment CASCADE CONSTRAINTS;
DROP TABLE AvailableStop CASCADE CONSTRAINTS;
DROP TABLE BusModel1 CASCADE CONSTRAINTS;
DROP TABLE BusModel2 CASCADE CONSTRAINTS;
DROP TABLE SkytrainModel1 CASCADE CONSTRAINTS;
DROP TABLE SkytrainModel2 CASCADE CONSTRAINTS;
DROP TABLE Route1 CASCADE CONSTRAINTS;
DROP TABLE Route2 CASCADE CONSTRAINTS;
DROP TABLE Stop CASCADE CONSTRAINTS;


CREATE TABLE Bus(
route_number CHAR(3),
route_name CHAR(50),
id CHAR(10),
model CHAR(20),
license_plate CHAR(6),
PRIMARY KEY (id),
UNIQUE (license_plate)
);


CREATE TABLE Driver(
id CHAR(8),
first_name CHAR(30),
last_name CHAR(30),
PRIMARY KEY (id)
);


CREATE TABLE SkyTrain(
route_number CHAR(3),
route_name CHAR(50),
id CHAR(6),
model CHAR(25),
PRIMARY KEY (id)
);


CREATE TABLE SkyTrainStation(
stopid CHAR(10),
name CHAR(50),
platforms INT,
PRIMARY KEY (stopid, name)
);


CREATE TABLE BusStop(
stopid CHAR(10),
name CHAR(50),
PRIMARY KEY (stopid, name)
);


CREATE TABLE Stop(
id CHAR(10),
postcode CHAR(6),
city CHAR(20),
PRIMARY KEY (id)
);


CREATE TABLE Zone(
zone_number INT,
city CHAR(20),
PRIMARY KEY (city)
);


CREATE TABLE CompassTap(
card_id CHAR(20),
time INT,
stop CHAR(10),
PRIMARY KEY (card_id, time, stop)
);


CREATE TABLE DriverAssignment(
driver_id CHAR(8),
bus_id CHAR(10),
PRIMARY KEY (driver_id, bus_id)
);


CREATE TABLE AvailableStop(
route_number CHAR(3),
route_name CHAR(50),
stop CHAR(10),
PRIMARY KEY (route_name, route_number, stop)
);


CREATE TABLE BusModel1(
name CHAR(20),
capacity INT,
fuel_type CHAR(6),
purchase_cost INT,
operating_cost INT,
PRIMARY KEY (name)
);


CREATE TABLE BusModel2(
purchase_cost INT,
operating_cost INT,
cost INT,
PRIMARY KEY (purchase_cost, operating_cost)
);


CREATE TABLE SkyTrainModel1(
name CHAR(25),
capacity INT,
cars INT,
purchase_cost INT,
operating_cost INT,
PRIMARY KEY (name)
);


CREATE TABLE SkyTrainModel2(
purchase_cost INT,
operating_cost INT,
cost INT,
PRIMARY KEY (purchase_cost, operating_cost)
);


CREATE TABLE Route1(
route_number CHAR(3),
route_name CHAR(50),
distance REAL,
PRIMARY KEY (route_number, route_name)
);


CREATE TABLE Route2(
route_number CHAR(3),
bus_route_type CHAR(20),
rail_type CHAR(20),
PRIMARY KEY (route_number)
);



ALTER TABLE Bus
ADD FOREIGN KEY (model) REFERENCES BusModel1 (name)
ON DELETE SET NULL;



ALTER TABLE Bus
ADD FOREIGN KEY (route_number, route_name) REFERENCES Route1 (route_number, route_name)
ON DELETE CASCADE;



ALTER TABLE SkyTrain
ADD FOREIGN KEY (model) REFERENCES SkytrainModel1 (name)
ON DELETE SET NULL;



ALTER TABLE SkyTrain
ADD FOREIGN KEY (route_number, route_name) REFERENCES Route1 (route_number, route_name)
ON DELETE CASCADE;



ALTER TABLE SkyTrainStation
ADD FOREIGN KEY (stopid) REFERENCES Stop (id)
ON DELETE CASCADE;



ALTER TABLE BusStop
ADD FOREIGN KEY (stopid) REFERENCES Stop (id)
ON DELETE CASCADE;



ALTER TABLE Stop
ADD FOREIGN KEY (city) REFERENCES Zone (city)
ON DELETE CASCADE;



ALTER TABLE CompassTap
ADD FOREIGN KEY (stop) REFERENCES Stop (id)
ON DELETE CASCADE;



ALTER TABLE DriverAssignment
ADD FOREIGN KEY (driver_id) REFERENCES Driver (id)
ON DELETE CASCADE;



ALTER TABLE DriverAssignment
ADD FOREIGN KEY (bus_id) REFERENCES Bus (id)
ON DELETE CASCADE;



ALTER TABLE AvailableStop
ADD FOREIGN KEY (stop) REFERENCES Stop (id)
ON DELETE CASCADE;


ALTER TABLE AvailableStop
ADD FOREIGN KEY (route_number, route_name) REFERENCES Route1 (route_number, route_name)
ON DELETE CASCADE;



ALTER TABLE BusModel1
ADD FOREIGN KEY (purchase_cost, operating_cost) REFERENCES BusModel2
(purchase_cost, operating_cost);



ALTER TABLE SkyTrainModel1
ADD FOREIGN KEY (purchase_cost, operating_cost) REFERENCES SkytrainModel2 (purchase_cost, operating_cost);

ALTER TABLE Route1
ADD FOREIGN KEY (route_number) REFERENCES Route2(route_number);



INSERT ALL
INTO Zone(zone_number, city) VALUES (1, 'Vancouver')
INTO Zone(zone_number, city) VALUES (2, 'Richmond')
INTO Zone(zone_number, city) VALUES (2, 'Burnaby')
INTO Zone(zone_number, city) VALUES (2, 'North Vancouver')
INTO Zone(zone_number, city) VALUES (2, 'West Vancouver')
INTO Zone(zone_number, city) VALUES (3, 'Surrey')
INTO Zone(zone_number, city) VALUES (3, 'Delta')
INTO Zone(zone_number, city) VALUES (3, 'Port Moody')
INTO Zone(zone_number, city) VALUES (3, 'Coquitlam')
INTO Zone(zone_number, city) VALUES (3, 'Pitt Meadows')
SELECT 1 FROM DUAL;



INSERT ALL
INTO Route2(route_number, bus_route_type, rail_type) VALUES ('4', NULL, NULL)
INTO Route2(route_number, bus_route_type, rail_type) VALUES ('6', NULL, NULL)
INTO Route2(route_number, bus_route_type, rail_type) VALUES ('16', NULL, NULL)
INTO Route2(route_number, bus_route_type, rail_type) VALUES ('25', NULL, NULL)
INTO Route2(route_number, bus_route_type, rail_type) VALUES ('65', 'Commuity Shuttle', NULL)
INTO Route2(route_number, bus_route_type, rail_type) VALUES ('99', 'B-Line', NULL)
INTO Route2(route_number, bus_route_type, rail_type) VALUES ('R2', 'RapidBus', NULL)
INTO Route2(route_number, bus_route_type, rail_type) VALUES ('R4', 'RapidBus', NULL)
INTO Route2(route_number, bus_route_type, rail_type) VALUES ('CL', NULL, 'Conventional')
INTO Route2(route_number, bus_route_type, rail_type) VALUES ('EL', NULL, 'Linear Induction')
INTO Route2(route_number, bus_route_type, rail_type) VALUES ('ML', NULL, 'Linear Induction')
SELECT 1 FROM DUAL;



INSERT ALL
INTO Route1(route_number, route_name, distance) VALUES ('CL', 'Waterfront', NULL)
INTO Route1(route_number, route_name, distance) VALUES ('CL', 'Richmond-Brighouse', NULL)
INTO Route1(route_number, route_name, distance) VALUES ('CL', 'YVR-Airport', NULL)
INTO Route1(route_number, route_name, distance) VALUES ('EL', 'Waterfront', NULL)
INTO Route1(route_number, route_name, distance) VALUES ('EL', 'King George', NULL)
INTO Route1(route_number, route_name, distance) VALUES ('EL', 'Production Way-University', NULL)
INTO Route1(route_number, route_name, distance) VALUES ('ML', 'VCC-Clark', NULL)
INTO Route1(route_number, route_name, distance) VALUES ('ML', 'Lafarge Lake-Douglas', NULL)
INTO Route1(route_number, route_name, distance) VALUES ('99', 'Commercial-Broadway Station', NULL)
INTO Route1(route_number, route_name, distance) VALUES ('99', 'UBC', NULL)
INTO Route1(route_number, route_name, distance) VALUES ('16', '29th Avenue Stn', NULL)
INTO Route1(route_number, route_name, distance) VALUES ('25', 'Brentwood Station', NULL)
INTO Route1(route_number, route_name, distance) VALUES ('R4', '41st Ave', NULL)
INTO Route1(route_number, route_name, distance) VALUES ('R2', 'Marine Dr', NULL)
SELECT 1 FROM DUAL;



INSERT ALL
INTO Stop(id, postcode, city) VALUES ('BW', 'V5N4B9', 'Vancouver')
INTO Stop(id, postcode, city) VALUES ('CB', 'VVVVVV', 'Vancouver')
INTO Stop(id, postcode, city) VALUES ('MT', 'VVVVVV', 'Burnaby')
INTO Stop(id, postcode, city) VALUES ('61304', 'V3Y2J4', 'Pitt Meadows')
INTO Stop(id, postcode, city) VALUES ('ST', 'V6B2L3', 'Vancouver')
INTO Stop(id, postcode, city) VALUES ('50136', 'V5Z0E3', 'Vancouver')
INTO Stop(id, postcode, city) VALUES ('56474', 'V6X3M2', 'Richmond')
SELECT 1 FROM DUAL;


INSERT ALL
INTO BusStop(stopid, name) VALUES ('50136', 'Oakridge-41st Ave Station @ Bay 2')
INTO BusStop(stopid, name) VALUES ('61304', 'Eastbound Lougheed Hwy @ Harris Rd')
SELECT 1 FROM DUAL;

INSERT ALL
INTO SkyTrainStation(stopid, name, platforms) VALUES ('ST', 'Stadium-Chinatown', 3)
INTO SkyTrainStation(stopid, name, platforms) VALUES ('BW', 'Commercial-Broadway', 5)
SELECT 1 FROM DUAL;



INSERT ALL
INTO BusModel2(purchase_cost, operating_cost, cost) VALUES (500000, 143, 19000)
INTO BusModel2(purchase_cost, operating_cost, cost) VALUES (1200000, 522, 1269000)
INTO BusModel2(purchase_cost, operating_cost, cost) VALUES (700000, 433, 760000)
INTO BusModel2(purchase_cost, operating_cost, cost) VALUES (700000, 302, 740000)
INTO BusModel2(purchase_cost, operating_cost, cost) VALUES (400000, 92, 412000)
SELECT 1 FROM DUAL;



INSERT ALL
INTO BusModel1(name, capacity, fuel_type, purchase_cost, operating_cost) VALUES ('NFI XN40', 70, 'N Gas', 500000, 143)
INTO BusModel1(name, capacity, fuel_type, purchase_cost, operating_cost) VALUES ('NFI XDE60', 120, 'D-E', 1200000, 522)
INTO BusModel1(name, capacity, fuel_type, purchase_cost, operating_cost) VALUES ('Nova Bus LFS', 70, 'Diesel', 700000, 433)
INTO BusModel1(name, capacity, fuel_type, purchase_cost, operating_cost) VALUES ('Nova Bus LFS HEV', 70, 'D-E', 700000, 302)
INTO BusModel1(name, capacity, fuel_type, purchase_cost, operating_cost) VALUES ('Nova Bus LFSe', 70, 'B-E', 400000, 92)
SELECT 1 FROM DUAL;



INSERT ALL
INTO SkyTrainModel2(purchase_cost, operating_cost, cost) VALUES (2450000, 2000, 3180000)
INTO SkyTrainModel2(purchase_cost, operating_cost, cost) VALUES (3500000, 1000, 3865000)
INTO SkyTrainModel2(purchase_cost, operating_cost, cost) VALUES (3250000, 500, 3432500)
INTO SkyTrainModel2(purchase_cost, operating_cost, cost) VALUES (3300000, 1125, 3660625)
INTO SkyTrainModel2(purchase_cost, operating_cost, cost) VALUES (3500000, 750, 3773750)
SELECT 1 FROM DUAL;



INSERT ALL
INTO SkyTrainModel1(name, capacity, cars, purchase_cost, operating_cost) VALUES ('Bombardier ICTS Mark I', 80, 6, 2450000, 2000)
INTO SkyTrainModel1(name, capacity, cars, purchase_cost, operating_cost) VALUES ('Hyundai Rotem EMU', 200, 2, 3250000, 500)
INTO SkyTrainModel1(name, capacity, cars, purchase_cost, operating_cost) VALUES ('Bombardier ART Mark II', 130, 4, 3300000, 1125)
INTO SkyTrainModel1(name, capacity, cars, purchase_cost, operating_cost) VALUES ('Bombardier ART Mark III', 135, 4, 3500000, 1000)
INTO SkyTrainModel1(name, capacity, cars, purchase_cost, operating_cost) VALUES ('Alstom Mark V', 160, 3, 3500000, 750)
SELECT 1 FROM DUAL;



INSERT ALL
INTO Driver(id, first_name, last_name) VALUES ('28903460', 'Derek', 'Lee')
INTO Driver(id, first_name, last_name) VALUES ('90020439', 'Cynthia', 'Newman')
INTO Driver(id, first_name, last_name) VALUES ('18834306', 'Evan', 'Holmes')
INTO Driver(id, first_name, last_name) VALUES ('44947234', 'Lan', 'Duong')
INTO Driver(id, first_name, last_name) VALUES ('63057871', 'Aman', 'Shah')
SELECT 1 FROM DUAL;


INSERT ALL
INTO CompassTap(card_id, time, stop) VALUES ('53426693990928605252', 35263, '61304')
INTO CompassTap(card_id, time, stop) VALUES ('32761835554193258185', 32007, 'BW')
INTO CompassTap(card_id, time, stop) VALUES ('29700392471242162660', 71191, '61304')
INTO CompassTap(card_id, time, stop) VALUES ('11327375108351674279', 80104, 'BW')
INTO CompassTap(card_id, time, stop) VALUES ('32761835554193258185', 67420, 'BW')
SELECT 1 FROM DUAL;

INSERT ALL
INTO AvailableStop(route_number, route_name, stop) VALUES ('R4', '41st Ave', '50136')
INTO AvailableStop(route_number, route_name, stop) VALUES ('EL', 'Expo Line', 'ST')
INTO AvailableStop(route_number, route_name, stop) VALUES ('EL', 'Expo Line', 'CB')
INTO AvailableStop(route_number, route_name, stop) VALUES ('ML', 'Millennium Line', 'CB')
INTO AvailableStop(route_number, route_name, stop) VALUES ('ML', 'Millennium Line', 'MT')
SELECT 1 FROM DUAL;



INSERT ALL
INTO SkyTrain(route_number, route_name, id, model) VALUES ('CL', 'Canada Line', '111', 'Hyundai Rotem EMU')
INTO SkyTrain(route_number, route_name, id, model) VALUES ('EL', 'Expo Line', '337', 'Bombardier ART Mark II')
INTO SkyTrain(route_number, route_name, id, model) VALUES ('EL', 'Expo Line', '52', 'Bombardier ICTS Mark I')
INTO SkyTrain(route_number, route_name, id, model) VALUES ('ML', 'Millennium Line', '144', 'Bombardier ICTS Mark I')
INTO SkyTrain(route_number, route_name, id, model) VALUES ('CL', 'Canada Line', '219', 'Hyundai Rotem EMU')
SELECT 1 FROM DUAL;



INSERT ALL
INTO Bus(route_number, route_name, id, model, license_plate) VALUES ('R4', '18022', 'NG5745', 'NFI XDE60', '41st Ave', )
INTO Bus(route_number, route_name, id, model, license_plate) VALUES ('9409', 'BY4048', 'Nova Bus LFS HEV', 'Brentwood Station', '25')
INTO Bus(route_number, route_name, id, model, license_plate) VALUES ('19027', 'NN9907', 'NFI XDE60', 'Marine Dr', 'R2')
INTO Bus(route_number, route_name, id, model, license_plate) VALUES ('9660', 'KX3049', 'Nova Bus LFS', '29th Avenue Stn', '16')
SELECT 1 FROM DUAL;



INSERT ALL
INTO DriverAssignment(driver_id, bus_id) VALUES ('44947234', '9660')
INTO DriverAssignment(driver_id, bus_id) VALUES ('90020439', '18022')
INTO DriverAssignment(driver_id, bus_id) VALUES ('18834306', '19027')
INTO DriverAssignment(driver_id, bus_id) VALUES ('28903460', '9409')
SELECT 1 FROM DUAL;
