<html>
    <head>
        <title>Aggregation with Group By</title>
    </head>

    <body>
        <a href="."> <p>&lt; Go home</p> </a>
        <h2>Find all the drivers that have driven every bus</h2>
        <form method="GET" action="division.php"> <!--refresh page when submitted-->
            <input type="hidden" id="divisionRequest" name="divisionRequest">
            SELECT d.id, d.first_name, d.last_name <br />
            FROM Driver d <br />
            WHERE NOT EXISTS <br />
            &emsp;    ((SELECT b.id FROM Bus b) <br />
            &emsp;    MINUS <br />
            &emsp;    (SELECT da.bus_id FROM DriverAssignment da WHERE d.id = da.driver_id))<br /> <br />
            <input type="submit" name="division"></p>
        </form>

        <?php
         include 'secrets.php';
		//this tells the system that it's no longer just parsing html; it's now parsing PHP

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            //echo "<br>running ".$cmdstr."<br>";
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr);
            //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
            }

			return $statement;
		}

        function executeBoundSQL($cmdstr, $list) {
            /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don\'t need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

			global $db_conn, $success;
			$statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            foreach ($list as $tuple) {
                foreach ($tuple as $bind => $val) {
                    //echo $val;
                    //echo "<br>".$bind."<br>";
                    OCIBindByName($statement, $bind, $val);
                    unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
				}

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                    echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
        }

        function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example,
			// ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon(getenv('ORACLE_USERNAME'), getenv('ORACLE_PASSWORD'), "dbhost.students.cs.ubc.ca:1522/stu");

            if ($db_conn) {
                debugAlertMessage("Database is Connected");
                return true;
            } else {
                debugAlertMessage("Cannot connect to Database");
                $e = OCI_Error(); // For OCILogon errors pass no handle
                echo htmlentities($e['message']);
                return false;
            }
        }

        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            OCILogoff($db_conn);
        }

        function handleDivisionRequest() {
            global $db_conn;

            $sql = "SELECT d.id, d.first_name, d.last_name FROM Driver d WHERE NOT EXISTS ((SELECT b.id FROM Bus b) MINUS (SELECT da.bus_id FROM DriverAssignment da WHERE d.id = da.driver_id))";

            $result = executePlainSQL($sql);
            printResult($result, array("Driver ID", "First Name", "Last Name"), "Group By Table");

            
        }

        function printResult($result, $header, $name) { 
            $size = count($header);

            $includeHeader = true;
            
            //table name
            echo "<b>" . $name . "<b>";

            echo "<table border='1'>";
            
            //rows
            while ($rows = OCI_Fetch_Array($result, OCI_BOTH)) {

                // header
                if ($includeHeader == true) {
                    $includeHeader = false;
                    echo "<tr>";

                    foreach($header as $value){
                        echo  "<th>" . $value . "</th>";
                    }
                    echo "</tr>";
                }

                echo "<tr>";

                for($x = 0; $x < $size; $x++){
                    echo "<td>" . $rows[$x] . "</td>";
                }

                echo "</tr>";
            }
    
            echo "</table>";
        }

        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('division', $_GET)) {
                    handleDivisionRequest();
                }

                disconnectFromDB();
            }
        }

		if (isset($_GET['divisionRequest'])) {
            handleGETRequest();
        }
		?>
	</body>
</html>
