<html>
    <?php include 'sql-fns.php'; ?>
    <h1>Tap search</h1>
    <form method="POST" action="oracle-test.php"> <!--refresh page when submitted-->
        <select name="route_name" id="route_name" required="true">
        <?php
         if (!connectToDB()) {
         echo 'failed to connect to db';
         return false;
         }
         $stid = executePlainSQL('SELECT route_number, route_name FROM CompassTap');
         while (($row = oci_fetch_object($stid)) != false) {
         echo var_dump($row);
         ?>
        <option value="<?= $row->ROUTE_NAME; ?>"><?= $row->ROUTE_NAME; ?></option>
         <?php } ?>
        </select>
        <!-- <input type="hidden" id="updateQueryRequest" name="updateQueryRequest"> -->
        <!-- Old Name: <input type="text" name="oldName"> <br /><br /> -->
        <!-- New Name: <input type="text" name="newName"> <br /><br /> -->
        <input type="submit" value="Search" name="searchSubmit"></p>
</form>
<p></p>
</html>
