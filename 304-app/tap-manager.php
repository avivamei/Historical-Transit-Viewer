<!doctype html>
<?php
include 'sql-fns.php';

if (isset($_POST['submitName'])) {
    onSubmit();
} else if (isset($_POST['insertName'])) {
    handleInsertRequest();
} else if (isset($_POST['updateName'])) {
    handleUpdateRequest();
} else if (isset($_POST['deleteName'])) {
    handleDeleteRequest();
}


function onSubmit() {
    echo 'Submitted! This is placeholder text. POST object below:';
    echo '<br><br><br>';
    echo(var_dump($_POST));
    echo '<br><br><br>';
}

function handleInsertRequest() {
    if (trim(strlen($_POST['card_id'])) != 20 or !ctype_digit(trim(strlen($_POST['card_id'])))) {
        echo '<p>Warning: card ID length is not 20 numbers. Proceeding anyway.</p>';
        // It would make more sense in a real application to reject,
        // but for students and TAs to debug, just allow all inserts.
    }

    global $db_conn;
    connectToDB();
    $tuple = array (
        ':card_id' => trim($_POST['card_id']),
        ':tap_date' => trim($_POST['tap_date']),
        ':route_number' => unparen($_POST['route_number_name_stop'])[0],
        ':route_name' => unparen($_POST['route_number_name_stop'])[1],
        ':stop' => unparen($_POST['route_number_name_stop'])[2],
    );

    $alltuples = array (
        $tuple
    );

    executeBoundSQL("insert into CompassTap values (:card_id, :tap_date, :route_number, :route_name, :stop)", $alltuples);
    global $success;
    if ($success) {
        echo '<p>Inserted!</p>';
    }
    OCICommit($db_conn);
    disconnectFromDB();
}

function handleUpdateRequest() {
    if (trim(strlen($_POST['new_id'])) != 20 or !ctype_digit(trim(strlen($_POST['new_id'])))) {
        echo '<p>Warning: card ID length is not 20 numbers. Proceeding anyway.</p>';
        // It would make more sense in a real application to reject,
        // but for students and TAs to debug, just allow all inserts.
    }
    global $db_conn;
    connectToDB();
    /* $tuple = array (
     *     ":old_id"  => unparen($_POST['old_tap'])[0],
     *     ":old_time"  => unparen($_POST['old_tap'])[1],
     *     ":old_stop" => unparen($_POST['old_tap'])[4],

     *     ":new_id" => trim($_POST['new_id']),
     *     ":new_time" => (int)$_POST['new_time'],
     *     ':new_route_number' => unparen($_POST['new_route_number_name_stop'])[0],
     *     ':new_route_name' => unparen($_POST['new_route_number_name_stop'])[1],
     *     ':new_stop' => unparen($_POST['new_route_number_name_stop'])[2],
     * );
     * echo var_dump($tuple);

     * $alltuples = array (
     *     $tuple
     * ); */

    /* $result = executeBoundSQL("UPDATE CompassTap SET card_id = :new_id, time = :new_time, route_number = :new_route_number, route_name = :new_route_name, stop = :new_stop WHERE (card_id = :old_id and time = :old_time and stop = :old_stop)", $alltuples); */
    $result = executePlainSQL("UPDATE CompassTap SET
    card_id = '".trim($_POST['new_id'])."',
    time = '".trim($_POST['new_time'])."',
    route_number = '".unparen($_POST['new_route_number_name_stop'])[0]."',
    route_name = '".unparen($_POST['new_route_number_name_stop'])[1]."',
    stop = '".unparen($_POST['new_route_number_name_stop'])[2]."'

    WHERE (
    card_id = '".unparen($_POST['old_tap'])[0]."'
    and stop = '".unparen($_POST['old_tap'])[4]."'
    and time = '".unparen($_POST['old_tap'])[1]."'
    )");

    OCICommit($db_conn);
    global $success;
    if ($success) {
        echo '<p>OK!</p>';
    }
    disconnectFromDB();
}

function handleDeleteRequest() {
    global $db_conn;
    connectToDB();


    $result = executePlainSQL("DELETE FROM CompassTap
    WHERE (
    card_id = '".unparen($_POST['deletable_tap'])[0]."'
    and stop = '".unparen($_POST['deletable_tap'])[4]."'
    and time = '".unparen($_POST['deletable_tap'])[1]."'
    )");

    OCICommit($db_conn);
    global $success;
    if ($success) {
        echo '<p>deleted (or not found)!</p>';
    }
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
        <h1>Compass tap manager</h1>

        <!-- SECTION 1 -->
        <h3>Add a compass tap</h3>
        <form method="POST" action="tap-manager.php">
            <br><label for="card_id">Card ID:</label>
            <input name="card_id" type="text" required="true"/>
            <br><label for="tap_date">Time:</label>
            <input name="tap_date" type="number" required="true"/>
            <br><label for="route_number_name_stop">Stop:</label>
            <select id="route_number_name_stop" name="route_number_name_stop" required="true">
                <?php
                if (!connectToDB()) {
                    echo 'failed to connect to db. Probably too many open connections.';
                    return false;
                }
                $stid = executePlainSQL('select distinct route_number, route_name, stop from AvailableStop');
                while (($row = oci_fetch_object($stid)) != false) {
                ?>
                    <option value="<?= "(".$row->ROUTE_NUMBER.")(".$row->ROUTE_NAME.")(".$row->STOP.")" ?>"><?= $row->ROUTE_NUMBER." ".$row->ROUTE_NAME." stop ".$row->STOP; ?></option>
                    <?php disconnectFromDB(); ?>
                <?php } ?>
            </select>
            <input type="submit" value="Insert compass tap" name="insertName">
        </form>

        <br><br><br>


        <!-- SECTION 2 -->
        <h3>Edit a compass tap</h3>
        <form method="POST" action="tap-manager.php">
            <br><label for="old_tap">Compass tap to edit:</label>
            <select name="old_tap" id="old_tap" required="true">
                <?php
                if (!connectToDB()) {
                    echo 'failed to connect to db. Probably too many open connections.';
                    return false;
                }
                $stid = executePlainSQL('select * from CompassTap');
                while (($row = oci_fetch_object($stid)) != false) {
                ?>
                    <option value="<?= "(".$row->CARD_ID.")(".$row->TIME.")(".$row->ROUTE_NUMBER.")(".$row->ROUTE_NAME.")(".$row->STOP.")" ?>"><?= "Card ".$row->CARD_ID." tapped at ".$row->TIME." on route ".$row->ROUTE_NUMBER." ".$row->ROUTE_NAME." stop ".$row->STOP; ?></option>
                    <?php disconnectFromDB(); ?>
                <?php } ?>
            </select>
            <br><br>

            <br><label for="new_id">New card ID:</label>
            <input type="text" name='new_id' required="true">

            <br><label for="new_time">New time:</label>
            <input type="number" name='new_time' required="true">

            <br><label for="new_route_number_name_stop">New stop:</label>
            <select id="new_route_number_name_stop" name="new_route_number_name_stop" required="true">
                <?php
                if (!connectToDB()) {
                    echo 'failed to connect to db. Probably too many open connections.';
                    return false;
                }
                $stid = executePlainSQL('select distinct route_number, route_name, stop from AvailableStop');
                while (($row = oci_fetch_object($stid)) != false) {
                ?>
                    <option value="<?= "(".$row->ROUTE_NUMBER.")(".$row->ROUTE_NAME.")(".$row->STOP.")" ?>"><?= $row->ROUTE_NUMBER." ".$row->ROUTE_NAME." stop ".$row->STOP; ?></option>
                    <?php disconnectFromDB(); ?>
                <?php } ?>
            </select>

            <input type="hidden" id="updateRequest" name='updateRequest'>
            <input type="submit" value ="Confirm and Update" name="updateName"></p>
        </form>

        <br><br><br>

        <!-- SECTION 3 -->
        <h3>Delete a compass tap</h3>
        <form method="POST" action="tap-manager.php">
            <br><label for="deletable_tap">Compass tap to delete:</label>
            <select name="deletable_tap" id="deletable_tap" required="true">
                <?php
                if (!connectToDB()) {
                    echo 'failed to connect to db. Probably too many open connections.';
                    return false;
                }
                $stid = executePlainSQL('select * from CompassTap');
                while (($row = oci_fetch_object($stid)) != false) {
                ?>
                    <option value="<?= "(".$row->CARD_ID.")(".$row->TIME.")(".$row->ROUTE_NUMBER.")(".$row->ROUTE_NAME.")(".$row->STOP.")" ?>"><?= "Card ".$row->CARD_ID." tapped at ".$row->TIME." on route ".$row->ROUTE_NUMBER." ".$row->ROUTE_NAME." stop ".$row->STOP; ?></option>
                    <?php disconnectFromDB(); ?>
                <?php } ?>
            </select>
            <br><br>

            <input type="hidden" id="deleteRequest" name='deleteRequest'>
            <input type="submit" value ="Delete tap" name="deleteName"></p>
        </form>

        <table>
            <!-- todo render taps -->
        </table> 
    </body>
</html>
<html
