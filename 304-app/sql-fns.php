<?php
include 'secrets.php';
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in connectToDB()
$show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

function debugAlertMessage($message) {
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}

function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
    //echo "<br>running ".$cmdstr."<br>";
    global $db_conn, $success;

    if ($db_conn == NULL) {
        echo '<br><br>executePlainSQL called with no DB connection.<br><br>';
        $success = false;
        // return $success;
    }
    $statement = OCIParse($db_conn, $cmdstr);
    //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = oci_error($db_conn); // For OCIParse errors pass the connection handle
        echo htmlentities($e['message']);
        $success = False;
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
        echo "<pre>Cannot execute the following command: " . $cmdstr . "</pre>";
        $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
        echo htmlentities($e['message']);
        $success = False;
    }

    return $statement;
}

function executeBoundSQL($cmdstr, $list) {
    /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
       In this case you don\'t need to create the statement several times. Bound variables cause a statement to only be
       parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
       See the sample code below for how this function is used */

    global $db_conn, $success;
    if ($db_conn == NULL) {
        echo '<br><br>executeBoundSQL called with no DB connection.<br><br>';
        $success = false;
        // return $success;
    }
    $statement = OCIParse($db_conn, $cmdstr);

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = oci_error($db_conn);
        echo htmlentities($e['message']);
        $success = False;
    }

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            // echo "<br>"." binding ".$bind." to ".$val."<br>";
            oci_bind_by_name($statement, $bind, $val);
            unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
        }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = oci_error($statement); // For OCIExecute errors, pass the statementhandle
            echo htmlentities($e['message']);
            echo "<br>";
            $success = False;
        }
    }
    return $statement;
}

// Return an array of objects, so each object is a result row.
// Call with the result of executePlainSQL or executeBoundSQL
function sql_rows($res) {
    $sql_out = [];
    if ($res == null) {
        return $out;
    }
    while(($sql_row = oci_fetch_object($res)) != false) {
        array_push($sql_out, $sql_row);
    }
    return $sql_out;
}

// "(hi)(two)(3)" =>       ['hi', 'two', '3']
// "(  hi)(two  )( 3 )" => ['hi', 'two', '3']
// used for multiple fields stored in a dropdown
function unparen($input) {
    $out = [];
    $pos = 0;

    do {
        $lparen = strpos($input, '(', $pos);
        $rparen = strpos($input, ')', $pos);
        $substring = trim(substr($input, $lparen + 1, $rparen - $lparen - 1));
        array_push($out, $substring);
        $pos = $rparen + 1;
    } while($lparen !== false and $rparen !== false);
    array_pop($out);
    return $out;
}

function connectToDB() {
    global $db_conn;

    // Your username is ora_(CWL_ID) and the password is a(student number). For example,
    // ora_platypus is the username and a12345678 is the password.
    $db_conn = OCILogon(getenv('ORACLE_USERNAME'), getenv('ORACLE_PASSWORD'), "dbhost.students.cs.ubc.ca:1522/stu");

    if ($db_conn) {
        debugAlertMessage("Database is Connected");
        return true;
    } else {
        debugAlertMessage("Cannot connect to Database");
        $e = oci_error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
        return false;
    }
}

function disconnectFromDB() {
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    OCILogoff($db_conn);
}


?>
