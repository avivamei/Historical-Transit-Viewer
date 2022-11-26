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
    connectToDB();
    $tuple = array (
        'card_id' => $_POST['card_id'],
        'tap_date' => $_POST['tap_date'],
        'route_name' => $_POST['route_name'],
        'stop_id' => $_POST['stop_id'],
    );

    $alltuples = array (
        $tuple
    );

    executeBoundSQL("insert into CompassTap values (:card_id, :time, :route_number, :route_name, :stop)", $alltuples);
    OCICommit($db_conn);
    disconnectFromDB();
}

function handleUpdateRequest() {
    global $db_conn;
    connectToDB();
    $tuple = array (
        ":old_tap" => $_POST['old_tap'],
        ":new_time" => $_POST['new_time'],
        ":new_route_number" => $_POST['new_route_number'],
        ":new_route_name" => $_POST['new_route_name'],
        ":new_stop" => $_POST['new_stop'],
    );

    $alltuples = array (
        $tuple
    );

    executeBoundSQL("update CompassTap set time = :new_time, route_number = :new_route_number, route_name = :new_route_name, stop = :new_stop where card_id = :old_id", $alltuples);

    OCICommit($db_conn);
    disconnectFromDB();
}

function printResult($result, $header, $name) {
    $size = count($header);
    $includeHeader = true;
    echo "<b>".$name."<b>";
    echo "<table border='1'>";
    while ($rows = oci_fetch_array($result, OCI_BOTH)) {
        if ($includeHeader == true) {
            $includeHeader = false;
            echo "<tr>";
            foreach($header as $value){
                echo  "<th>".$value."</th>";
            }
            echo "</tr>";
        }
        echo "<tr>";
        for($x = 0; $x < $size; $x++){
            echo "<td>".$rows[$x]."</td>";
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
                $stid = executePlainSQL('select distinct route_number, route_name, stop from AvailableStop');
                while (($row = oci_fetch_object($stid)) != false) {
                    echo var_dump($row);
                ?>
                    <option value="<?= "(".$row->ROUTE_NUMBER.")(".$row->ROUTE_NAME.")(".$row->STOP.")" ?>"><?= $row->ROUTE_NUMBER." ".$row->ROUTE_NAME." stop ".$row->STOP; ?></option>
                <?php } ?>
            </select>
            <input type="submit" value="Go!" name="submitName">
        </form>

        <br><br><br>
        <h3>Edit a compass tap</h3>
        <form method="POST" action="tap-manager.php">
            <select name="old_tap" id="old_tap" required="true">
                <?php
                if (!connectToDB()) {
                    echo 'failed to connect to db. Probably too many open connections.';
                    return false;
                }
                $stid = executePlainSQL('select * from CompassTap');
                while (($row = oci_fetch_object($stid)) != false) {
                ?>
                    <option value="<?= $row->ROUTE_NUMBER." ".$row->ROUTE_NAME ?>"><?= $row->ROUTE_NUMBER." ".$row->ROUTE_NAME; ?></option>
                <?php } ?>
            </select>

            <br><label for="old_tap">Card ID:</label>
            <input type="text" name='old_tap'>

            <br><label for="new_time">New time:</label>
            <input type="number" name='new_time'>

            <br><label for="new_route">New route:</label>
            <select name="new_route" id="new_route" required="true">
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

            <select id="new_stop" name="new_stop">
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
