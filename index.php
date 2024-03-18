<?php

require_once 'database.php';
require_once 'tools.php';

echo '<link rel="stylesheet" type="text/css" href="style.css">';

$csvFile = 'products.csv';
$database = new Store();

echo "<h1>Furniture storage</h1>";

echo "<form method = 'post' action='' class='btn'>
        <button type ='submit' id = 'crt-tbl' name = 'crt-tbl'>Create Tables</button>
        <button type ='submit' id = 'insert' name = 'insert'>Insert Data</button>
     </form>";



if (isset($_POST['crt-tbl'])){
    $database->createTables();
}

if (isset($_POST['insert'])){
    $database->insertData();
}


$dataWriter = new DataWriter();
$dataWriter->writeTable($csvFile);


?>