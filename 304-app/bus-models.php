<!doctype html>
<?php
include 'sql-fns.php';

function handleGETRequest() {
		if (connectToDB()) {
				if (array_key_exists('projjoinRequest', $_GET)) {
						handleProjectJoinRequest();
				}
				disconnectFromDB();
		}
}

if (isset($_POST['projjoinRequest'])) {
		handleGETRequest();
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

function handleProjectJoinRequest() {
		global $db_conn;

		$fields = "b.id";
		$resultfields = "Bus ID, ";

		 if (isset($_GET['capacity'])) {
				$fields .= ", m.capacity";
				$resultfields .= ", Capacity ";
		 }
		 if (isset($_GET['name'])) {
				$fields .= ", m.name";
				$resultfields .= ", Name ";
		 }
		 if (isset($_GET['fuel_type'])) {
				$fields .= ", m.fuel_type";
				$resultfields .= ", Fuel Type ";
		}
		 if (isset($_GET['purchasing_cost'])) {
				$fields .= ",m.purchasing_cost";
				$resultfields .= ", Purchasing Cost ";
		}
		if (isset($_GET['operating_cost'])) {
			 $fields .= ", m.operating_cost";
			 $resultfields .= ", Operating Cost ";
	 }

	 $tuple = array (
			 ":bind1" => $_GET['bus_id']
	 );

	 $alltuples = array ($tuple);

	 $result = executeBoundSQL("SELECT $fields BusModel1 m, JOIN Bus b ON m1.id = b.id WHERE m1.id = :bind1", $alltuples);
	  printResult($result, $resultfields, "");

		OCICommit($db_conn);
}

?>
<html>
    <head>
        <title>Bus model finder</title>
    </head>
    <body>
	<a href="."> <p>&lt; Go home</p> </a>
        <h1>Bus model finder</h1>
        <form method="POST" action="bus-models.php">

            <br><label for="bus_id">Bus ID:</label>
            <input name="bus_id" type="text"/>

            <br><label for="capacity">Capacity</label>
            <input type="checkbox" name="capacity"/>

            <br><label for="model_name">Model name:</label>
            <input type="checkbox" name="name"/>

            <br><label for="fuel_type">Fuel type:</label>
            <input type="checkbox" name="fuel_type"/>

            <br><label for="purchasing_cost">Purchasing cost:</label>
            <input type="checkbox" name="purchasing_cost"/>

            <br><label for="operating_cost">Operating cost:</label>
            <input type="checkbox" name="operating_cost"/>


						<input type="submit" name="projjoinRequest"></p>


        </form>

    </body>
</html>
<html
