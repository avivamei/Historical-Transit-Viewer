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

            <br>
            <select id="stop_id" name="stop_id">
                <option value="*" selected>-- Select stop --</option>
                <?php /*todo*/ ?>
            </select>
            <input type="submit" value="Go!" name="submitName">
        </form>
        <table>
            <!-- todo render taps -->
        </table>
    </body>
</html>
<html
