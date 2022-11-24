<html>
    <?php include 'sql-fns.php'; ?>
    <h1>Tap search</h1>
    <form method="POST" action="oracle-test.php"> <!--refresh page when submitted-->
        <select name="route" id="route" required="true">
            <?
             executeBoundSQL(select ())
             ?>
        </select>
        <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
        Old Name: <input type="text" name="oldName"> <br /><br />
        New Name: <input type="text" name="newName"> <br /><br />
        <input type="submit" value="Search" name="searchSubmit"></p>
</form>
<p></p>
</html>
