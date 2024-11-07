<?php
// Connect to the database labeled Test
$dsn = 'Test';  // The name of the server
$username = '';  // username(There is nothing here)
$password = '';  // password(There is nothing here)

// Create an ODBC connection
$con = odbc_connect($dsn, $username, $password);

if (!$con) {
    // failure and diagnose
    die("Connection failed: " . odbc_errormsg());
}

// Check whether the form was submitted (index.html)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sql_query'])) {
    $sql_query = $_POST['sql_query'];


    // run the query/command
    $result = odbc_exec($con, $sql_query);

    if (!$result) {
        // failure and diagnose(again)
        echo "Error executing query: " . odbc_errormsg($con); 
    } else {
        // If the query is select then display it in a table
        if (stripos(trim($sql_query), "SELECT") === 0) {
            echo "<h2>Query Results:</h2><table border='1'><tr>";

            // obtain and display the column names (headers)
            $num_fields = odbc_num_fields($result);
            for ($i = 1; $i <= $num_fields; $i++) {
                echo "<th>" . odbc_field_name($result, $i) . "</th>";
            }
            echo "</tr>";

            // obtain display each record of the result set
            while ($row = odbc_fetch_array($result)) {
                echo "<tr>";
                foreach ($row as $column => $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            // If the query =/= select then success msg
            echo "Query executed successfully!";
        }
    }
} else {
    echo "Please submit a SQL query.";
}

// Close the ODBC connection
odbc_close($con);
?>
