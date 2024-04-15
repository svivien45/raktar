<?php

echo "<!DOCTYPE html>
    <html lang='hu'>
    <head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Oldal</title>
    <link rel='stylesheet' href='style.css'>
</head>
<body>";

require_once 'database.php';
require_once 'tools.php';
require 'vendor/autoload.php';

$csvFile = 'products.csv';
$database = new Store();

echo "<div class='header'>";
echo "<h1>Furniture storage</h1>";

echo "<form method='post' action='' class='btn'>
        <button type='submit' id='crt-tbl' name='crt-tbl'>Create Tables</button>
        <button type='submit' id='insert' name='insert'>Insert Data</button>
        <button type='submit' name='checkLowStock'>Out of Stock</button>
     </form><br>";


echo "<form method='post' action='download.php' class='btn'>
        <button type='submit' name='generatePDF'>PDF</button>
    </form><br>";

    echo "<form method='post' action='email.php' class='btn'>
        <button type='submit' name='email'>Email</button>
    </form><br>";

echo "</div><br>";

echo "<form method='post' action='' class='btn1'>
        Enter a name of furniture: <input type='text' name='productName'>
        <button type='submit' name='sumbit'>Search</button>
    </form>";

echo "<div class='add'>";
echo "<h2>Add new product</h2>";
echo "<form method='post' action=''>
        Name: <input type='text' name='newName'><br><br>
        Price: <input type='text' name='newPrice'><br><br>
        Quantity: <input type='text' name='newQuantity'><br><br>
        Minimum quantity: <input type='text' name='newMin'><br><br>
        Store: <select id='newStore' name='newStore'>
            <option value='store1'>store1</option>
            <option value='store2'>store2</option>
            <option value='store3'>store3</option>
            <option value='store4'>store4</option>
        </select>
        Row: <select id='newRow' name='newRow'>
            <option value='firstRow'>firstRow</option>
            <option value='secondRow'>secondRow</option>
            <option value='thirdRow'>thirdRow</option>
            <option value='fourthRow'>fourthRow</option>
        </select>
        Column: <select id='newColumn' name='newColumn'>
            <option value='firstColumn'>firstColumn</option>
            <option value='secondColumn'>secondColumn</option>
            <option value='thirdColumn'>thirdColumn</option>
            <option value='fourthColumn'>fourthColummn</option>
        </select>
        Shelf: <select id='newShelf' name='newShelf'>
            <option value='1A'>1A</option>
            <option value='1B'>1B</option>
            <option value='1C'>1C</option>
            <option value='2A'>2A</option>
            <option value='2B'>2B</option>
            <option value='2C'>2C</option>
        </select><br><br>
        <button type='submit' name='submit' class='addBtn'>Add product</button>
    </form>";
echo "</div>";

if (isset($_POST['submit'])) {
    $name = $_POST['newName'];
    $price = $_POST['newPrice'];
    $quantity = $_POST['newQuantity'];
    $min_qty = $_POST['newMin'];
    $id_store = $_POST['newStore'];
    $id_row = $_POST['newRow'];
    $id_shelf = $_POST['newShelf'];
    $id_column = $_POST['newColumn'];

    $dataWriter = new DataWriter();
    $result = $dataWriter->addProduct($id_store, $id_row, $id_column, $id_shelf, $name, $min_qty, $quantity, $price);

}

if (isset($_POST['checkLowStock'])) {
    $dataWriter = new DataWriter();
    $lowStockProducts = $dataWriter->getLowStockProducts();

    if ($lowStockProducts) {
        echo "<h2>Out of Stock</h2>";
        echo "<div class='outOfStock'>";
        foreach ($lowStockProducts as $product) {
            echo "<p>Name: " . $product['name'] . ", Quantity: " . $product['quantity'] ."</p>";
        }
        echo "</div>";
    }
}

if (isset($_POST['sumbit']) && !empty($_POST['productName'])) {
    $productName = $_POST['productName'];
    $dataWriter = new DataWriter();
    $dataWriter->searchByName($productName);
}

if (isset($_POST['crt-tbl'])){
    $database->createTables();
}

if (isset($_POST['insert'])){
    $database->insertData();
}


$dataWriter = new DataWriter();
$dataWriter->writeTable($csvFile);

echo "</body>";
echo "</html>";

?>