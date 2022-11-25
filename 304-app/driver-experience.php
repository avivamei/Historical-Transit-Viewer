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

?>
<html>
    <head>
        <title>Driver experience finder</title>
    </head>
    <body>
	<a href="."> <p>&lt; Go home</p> </a>
        <h1>Driver experience finder</h1>
        <form method="POST">
            <select name="route_name" id="route_name" required="true">
                <option value="*" selected>-- Select route --</option>
                <?php
                if (!connectToDB()) {
                    echo 'failed to connect to db. Probably too many open connections.';
                    return false;
                }
                $stid = executePlainSQL('select distinct route_number, route_name from CompassTap');
                while (($row = oci_fetch_object($stid)) != false) {
                    echo var_dump($row);
                ?>
                    <option value="<?= $row->ROUTE_NUMBER." ".$row->ROUTE_NAME ?>"><?= $row->ROUTE_NUMBER." ".$row->ROUTE_NAME; ?></option>
                <?php } ?>
            </select>
            <br><label for="first_name">First name:</label>
            <input type="checkbox" name="first_name"/>

            <br><label for="last_name">Last name:</label>
            <input type="checkbox" name="last_name"/>

            <br><label for="driver_id">Driver ID:</label>
            <input type="checkbox" name="driver_id"/>

            <br><label for="salary">Salary:</label>
            <input type="checkbox" name="salary"/>

            <br>
            <input type="submit" value="Go!" name="submitName">
        </form>
        <table>
            <!-- todo render taps -->
        </table>
    </body>
</html>
<html
