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
        <title>Tap search</title>
    </head>
    <body>
	<a href="."> <p>&lt; Go home</p> </a>
        <h1>Tap search</h1>
        <form method="POST" action="tap-search.php">
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
            <select id="stop_id" name="stop_id">
                <option value="*" selected>-- Select stop --</option>
                <?php /*todo*/ ?>
            </select>
            <input type="date" value="after" name="after">
            <input type="date" value="before" name="before">
            <input type="submit" value="Go!" name="submitName">
        </form>
    </body>
</html>
