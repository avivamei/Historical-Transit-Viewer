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

function handleInsertRequest() {
		global $db_conn;

		//Getting the values from user and insert data into the table
		$tuple = array (
				":bind1" => $_POST['card_id'],
				":bind2" => $_POST['tap_date'],
				":bind3" => $_POST['route_name'],
				":bind4" => $_POST['stop_id'],
		);

		$alltuples = array (
				$tuple
		);

		executeBoundSQL("insert into tap values (:bind1, :bind2, :bind3, :bind4)", $alltuples);
		OCICommit($db_conn);
}

function handleUpdateRequest() {
		global $db_conn;

		$oldID = $_POST['oldID']
		$newID = $_POST['newID']

		$oldtime = $_POST['oldTime'];
		$newtime = $_POST['newTime'];

		$oldstop = $_POST['oldStop']
		$newstop = $_POST['newStop']

		executePlainSQL("UPDATE CompassTap SET card_id = " . $newid .", time =  " . $newtime .", stop = " . $newstop ." WHERE card_id = " . $oldid ." AND time = " . $oldtime ." AND stop = " . $oldstop);

		OCICommit($db_conn);
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

?>



<html>
    <head>
        <title>Tap manager</title>
    </head>
    <body>
	<a href="."> <p>&lt; Go home</p> </a>
        <h1>Tap manager</h1>
		<h3>Add a compass tap</h3>
        <form method="POST" action="tap-manager.php">
			<input name="card_id" type="text"/>
			<input name="tap_date" type="date"/>
            <select name="route_name" id="route_name" required="true">
                <option value="*" selected>-- Select route --</option>
                <?php
                if (!connectToDB()) {
                    echo 'failed to connect to db. Probably too many open connections.';
                    return false;
                }
                $stid = executePlainSQL('select distinct route_number, route_name from Route1');
                while (($row = oci_fetch_object($stid)) != false) {
                    echo var_dump($row);
                ?>
                    <option value="<?= $row->ROUTE_NUMBER." ".$row->ROUTE_NAME ?>"><?= $row->ROUTE_NUMBER." ".$row->ROUTE_NAME; ?></option>
                <?php } ?>
            </select>
            <select id="stop_id" name="stop_id">
                <option value="*" selected>-- Select stop --</option>
								<?php
								if (!connectToDB()) {
										echo 'failed to connect to db. Probably too many open connections.';
										return false;
								}
								$stid = executePlainSQL('select distinct id, name from Stop');
								while (($row = oci_fetch_object($stid)) != false) {
										echo var_dump($row);
								?>
										<option value="<?= $row->ID." ".$row->NAME ?>"><?= $row->ID." ".$row->NAME; ?></option>
								<?php } ?>
            </select>
            <input type="submit" value="Go!" name="submitName">
        </form>

			<h3>Edit a compass tap</h3>
				<form method="POST" action="tap-manager.php">

					Old Card ID: <input type="text" name='oldid'> <br /><br />
					New Card ID: <input type="text" name='newid'> <br /><br />

					Old Date: <input type="date" name='olddate'> <br /><br />
					New Date: <input type="date" name='newdate'> <br /><br />

					<select name="oldroute" id="oldroute" required="true">
							<option value="*" selected>-- Select old route --</option>
							<?php
							if (!connectToDB()) {
									echo 'failed to connect to db. Probably too many open connections.';
									return false;
							}
							$stid = executePlainSQL('select distinct route_number, route_name from Route1');
							while (($row = oci_fetch_object($stid)) != false) {
									echo var_dump($row);
							?>
									<option value="<?= $row->ROUTE_NUMBER." ".$row->ROUTE_NAME ?>"><?= $row->ROUTE_NUMBER." ".$row->ROUTE_NAME; ?></option>
							<?php } ?>
					</select>
					<select name="newroute" id="newroute" required="true">
							<option value="*" selected>-- Select new route --</option>
							<?php
							if (!connectToDB()) {
									echo 'failed to connect to db. Probably too many open connections.';
									return false;
							}
							$stid = executePlainSQL('select distinct route_number, route_name from Route1');
							while (($row = oci_fetch_object($stid)) != false) {
									echo var_dump($row);
							?>
									<option value="<?= $row->ROUTE_NUMBER." ".$row->ROUTE_NAME ?>"><?= $row->ROUTE_NUMBER." ".$row->ROUTE_NAME; ?></option>
							<?php } ?>
					</select>

					<select id="oldstop" name="oldstop">
							<option value="*" selected>-- Select old stop --</option>
							<?php
							if (!connectToDB()) {
									echo 'failed to connect to db. Probably too many open connections.';
									return false;
							}
							$stid = executePlainSQL('select distinct id, name from Stop');
							while (($row = oci_fetch_object($stid)) != false) {
									echo var_dump($row);
							?>
									<option value="<?= $row->ID." ".$row->NAME ?>"><?= $row->ID." ".$row->NAME; ?></option>
							<?php } ?>
					</select>
					<select id="newstop" name="newstop">
							<option value="*" selected>-- Select new stop --</option>
							<?php
							if (!connectToDB()) {
									echo 'failed to connect to db. Probably too many open connections.';
									return false;
							}
							$stid = executePlainSQL('select distinct id, name from Stop');
							while (($row = oci_fetch_object($stid)) != false) {
									echo var_dump($row);
							?>
									<option value="<?= $row->ID." ".$row->NAME ?>"><?= $row->ID." ".$row->NAME; ?></option>
							<?php } ?>
					</select>


					<input type="hidden" id="updateRequest" name='updateRequest'>
					<input type="submit" value ="Confirm and Update" name="updateTuples"></p>
			</form>


		<table>
			<!-- todo render taps -->
		</table>
    </body>
</html>
<html
