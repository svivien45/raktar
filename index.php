<?php

require_once 'database.php';

$database = new Store();

echo "<form method = 'post' action=''>
        <button type ='submit' id = 'crt-tbl' name = 'crt-tbl'>Create Tables</button>
     </form>";


if (isset($_POST['crt-tbl'])){
    $database->createTables();
}
?>