<!doctype html>
<?php
include 'sql-fns.php';

if (isset($_POST['submitName'])) {
    onSubmit();
}

function onSubmit() {
    echo 'Submitted! This is placeholder text. POST object below:';
    echo '<br><br><br>';
    echo(var_dump($_POST));
    echo '<br><br><br>';
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

function handleJoinRequest() {
		global $db_conn;

		$tuple = array (
				":bind1" => $_GET['id']
		);

		$alltuples = array ($tuple);

		$result = executeBoundSQL("SELECT  b.id, m.name, m.capacity, m.fueltype, m.purchase_cost, m.operating_cost FROM BusModels M1, BusModels M2 JOIN Bus b ON m1.id = m2.id AND m1.id = b.id WHERE m1.id = :bind1", $alltuples);
		printResult($result, array("ID", "Name", "Capacity", "Fuel Type", "Purchasing Cost", "Operating Cost"), "Bus Model");
}

function handleProjectRequest() {
		global $db_conn;

		$result = "";

		 if (isset($_GET['bus_id'])) {
				$result .= ", Bus ID";
		 }
		 if (isset($_GET['capacity'])) {
				$result .= ", Capacity";
		 }
		 if (isset($_GET['name'])) {
				$result .= ", Name";
		 }
		 if (isset($_GET['fuel_type'])) {
				$result .= ", Fuel Type";
		}
		 if (isset($_GET['purchasing_cost'])) {
				$result .= ", Purchasing Cost";
		}
		if (isset($_GET['operating_cost'])) {
			 $result .= ", OperatingCost";
	 }

		$result1 = substr($result, 2);
		$result2 = executePlainSQL("SELECT $result1 FROM BusModel1");
		printResult($result2, $result1, "");

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

						<br><input type="submit" value="Go!" name="submitName">
        </form>

    </body>
</html>
<html
