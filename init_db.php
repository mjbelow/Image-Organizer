<!DOCTYPE html>
<html>
<head>
<title>Initialize Database</title>
<style>
body {
    font: bold 12pt "courier new", monospace;
    background: #000;
    color: #fff;
}

a {
    color: #fff;
}

.error {
    color: #a00;
}

.success {
    color: #0a0;
}
</style>
</head>
<body>

<?php

error_reporting(E_ALL ^ E_WARNING);

$filename = 'create schema.sql';
$host="127.0.0.1";
$port=3306;
$socket="";
$user="root";
$password="";
$dbname="test";

$con = new mysqli($host, $user, $password, $dbname, $port);

if ($con->connect_errno) {
    printf("<p class='error'>Connect failed: %s</p>\n", $con->connect_error);
    exit("\n</body>\n</html>");
}

// https://stackoverflow.com/questions/19751354/how-do-i-import-a-sql-file-in-mysql-database-using-php

// Temporary variable, used to store current query
$templine = '';

// Read in entire file
$lines = file($filename);

// Loop through each line
foreach ($lines as $line)
{
    // Skip it if it's a comment
    if (substr($line, 0, 2) == '--' || $line == '')
        continue;

    // Add this line to the current segment
    $templine .= $line;

    // If it has a semicolon at the end, it's the end of the query
    if (substr(trim($line), -1, 1) == ';')
    {
        // Perform the query
        if (!$con->query($templine)) {
            printf("<p class='error'>Error message: %s</p>\n", $con->error);
            exit("\n</body>\n</html>");
        }

        // Reset temp variable to empty
        $templine = '';
    }
}

?>

<p class="success">Tables imported successfully</p>

<a href="/www">Click here to go to app index</a>

</body>
</html>
