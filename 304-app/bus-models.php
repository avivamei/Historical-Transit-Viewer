<html>
	<?php include 'secrets.php'; ?>
	<head>
        <title>CPSC 304 PHP/Oracle Demonstration</title>
    </head>

	<body>

	<h1>Find the bus model of a specific bus</h1>

	<h2>Insert Values into DemoTable</h2>
        <form method="POST" action="bus-models.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            Number: <input type="text" name="insNo"> <br /><br />
            Name: <input type="text" name="insName"> <br /><br />

            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>
	<hr />

	<?php
	?>
	</body>

</html>
