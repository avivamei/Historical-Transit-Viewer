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
            <select name="route_number" id="route_number" required="true">
                <!-- <option value="*" selected>-- Select route --</option> -->
                <?php
                if (!connectToDB()) {
                    echo 'failed to connect to db. Probably too many open connections.';
                    return false;
                }

                $stid = executePlainSQL('select distinct route_number from CompassTap');
                while (($row = oci_fetch_object($stid)) != false) {
                ?>

                    <option value="<?= $row->ROUTE_NUMBER?>"><?= $row->ROUTE_NUMBER; ?></option>

                <?php } ?>
                <?php disconnectFromDB(); ?>
            </select>
            <select id="stop_id" name="stop_id">
                <!-- <option value="*" selected>-- Select stop --</option> -->
                <?php
                if (!connectToDB()) {
                    echo 'failed to connect to db. Probably too many open connections.';
                    return false;
                }

                $stid = executePlainSQL('select distinct stop from CompassTap');
                while (($row = oci_fetch_object($stid)) != false) {
                ?>
                    <option value="<?= $row->STOP?>"><?= $row->STOP; ?></option>

                <?php } ?>
                <?php disconnectFromDB(); ?>
            </select>

            <!-- <input type="date"  name="after"> -->
            <!-- <input type="date"  name="before"> -->
            <input type="submit" value="Go!" name="submitName">
        </form>
        <table>
            <tr>
                <th>Card ID</th>
                <th>Time</th>
                <th>Route number</th>
                <th>Route name</th>
                <th>Stop</th>
            </tr>
            <?php
            if (isset($_POST['submitName'])) {
                if (!connectToDB()) {
                    echo 'failed to connect to db. Probably too many open connections.';
                    return false;
                }
                $tap_spec = array(
                    ':route_number' => $_POST['route_number'],
                    ':stop_id' => $_POST['stop_id'],
                    /* ':after' => strtotime($_POST['after']), */
                    /* ':before' => strtotime($_POST['before']) */
                );
                $specs = array($tap_spec);
                $temp = executeBoundSQL("select * from CompassTap where (route_number = :route_number and stop = :stop_id)", $specs);
                $taps = sql_rows($temp);
                /* while($row = (oci_fetch_object($temp) != false) {
                 *     array_push(taps, row);
                 * } */
                disconnectFromDB();

                foreach($taps as $tap) {
            ?>
                <tr>
                    <td><?= $tap->CARD_ID; ?> </td>
                    <td><?= $tap->TIME; ?> </td>
                    <td><?= $tap->ROUTE_NUMBER; ?> </td>
                    <td><?= $tap->ROUTE_NAME; ?> </td>
                    <td><?= $tap->STOP; ?> </td>
                </tr>
            <?php }} ?>
        </table>
    </body>
</html>
