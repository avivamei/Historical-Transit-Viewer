CREATE TABLE Bus(
        id CHAR(10),
        license_plate CHAR(6),
        model CHAR(20),
        route_name CHAR(50),
        route_number CHAR(3),
        PRIMARY KEY (id),
    UNIQUE (license_plate),
        FOREIGN KEY (model) REFERENCES BusModel1 (name)
ON DELETE SET NULL
ON UPDATE CASCADE,
        FOREIGN KEY (route_name, route_number) REFERENCES Route1 (name, number)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Driver(
        id CHAR(8),
        first_name CHAR(30),
        last_name CHAR(30),
        PRIMARY KEY (id)
);

CREATE TABLE SkyTrain(
        id CHAR(3),
        route_name CHAR(50),
    route_number CHAR(3)
        model CHAR(20),
        PRIMARY KEY (id),
        FOREIGN KEY (model) REFERENCES SkytrainModel1 (name)
ON DELETE SET NULL
        ON UPDATE CASCADE,
        FOREIGN KEY (route_name, route_number) REFERENCES Route1 (name, number)
ON DELETE CASCADE
        ON UPDATE CASCADE

);

CREATE TABLE SkyTrainStation(
        stopid CHAR(10),
        name CHAR(50),
        platforms INT,
        PRIMARY KEY (stopid, name),
        FOREIGN KEY (stopid) REFERENCES Stop (id)
ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE BusStop(
        stopid CHAR(10),
        name CHAR(50),
        PRIMARY KEY (stopid, name),
        FOREIGN KEY (stopid) REFERENCES Stop (id)
ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Stop(
        id CHAR(10),
        postcode CHAR(6),
        city CHAR(20),
        PRIMARY KEY (id),
        FOREIGN KEY (city) REFERENCES Zone (city)
ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Zone(
        number INT,
    city CHAR(20),
        PRIMARY KEY (city),
);

CREATE TABLE CompassTap(
        card_id CHAR(20),
        time INT,
        stop CHAR(10),
        PRIMARY KEY (card_id, time, stop),
        FOREIGN KEY (stop) REFERENCES Stop (id)
ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE DriverAssignment(
        driver_id CHAR(8),
        bus_id CHAR(10),
        PRIMARY KEY (driver_id, bus_id),
        FOREIGN KEY (driver_id) REFERENCES Driver (id)
ON DELETE CASCADE
        ON UPDATE CASCADE,
        FOREIGN KEY (bus_id) REFERENCES Bus (id)
ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE AvailableStop(
        stop CHAR(10),
        route_name CHAR(50),
        route_number CHAR(3)
        PRIMARY KEY (stop, route_name, route_number),
        FOREIGN KEY (stop) REFERENCES Stop (id)
ON DELETE CASCADE
        ON UPDATE CASCADE,
        FOREIGN KEY (route_name, route_number) REFERENCES Route1 (name, number)
ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE BusModel1(
        name CHAR(20),
        capacity INT,
        fuel_type CHAR(5),
        purchase_cost INT,
        operating_cost INT,
        PRIMARY KEY (name),
    FOREIGN KEY (purchasing_cost, operating_cost) REFERENCES BusModel2
            (purchasing_cost, operating_cost)
);

CREATE TABLE BusModel2(
        purchase_cost INT,
        operating_cost INT,
        cost INT,
        PRIMARY KEY (purchase_cost, operating_cost)
);

CREATE TABLE SkyTrainModel1(
        name CHAR(20),
        capacity INT,
        cars INT,
        purchase_cost INT,
        operating_cost INT,
        PRIMARY KEY (name),
    FOREIGN KEY (purchasing_cost, operating_cost) REFERENCES SkytrainModel2
            (purchasing_cost, operating_cost)
);

CREATE TABLE SkyTrainModel2(
        purchase_cost INT,
        operating_cost INT,
        cost INT,
        PRIMARY KEY (purchase_cost, operating_cost)
);

CREATE TABLE Route1(
    name CHAR(50),
    number CHAR(3),
    origin CHAR(10),
    destination CHAR(10),
    rail_type CHAR(20),
    distance REAL,
    PRIMARY KEY (name, number),
FOREIGN KEY (origin) REFERENCES Stop (id),
        FOREIGN KEY (destination) REFERENCES Stop (id),
    FOREIGN KEY (number) REFERENCES Route2(number)
);

CREATE TABLE Route2(
    number CHAR(3),
    bus_route_type CHAR(20),
    PRIMARY KEY (number)
);
