<?php
$db['db_host'] = "localhost";
$db['db_user'] = "root";
$db['db_pass'] = "root";
$db['db_name'] = "cms";

// Loop through the $db array and define constants
foreach ($db as $key => $value) {
    define(strtoupper($key), $value);
}

// Establish the database connection using defined constants
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// if ($connection) {
//     echo "we are connected";
// } else {
//     die("Database connection failed: " . mysqli_connect_error());
// }
?>