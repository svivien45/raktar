<?php

echo '<link rel="stylesheet" type="text/css" href="style.css">';

class DataWriter {
    public function writeTable($csvFile) {
        $handle = fopen($csvFile, "r");
        if ($handle === FALSE) {
            die("Error opening CSV file.");
        }
        echo "<table border='1'>";
        echo "<tr><th>Name</th><th>Price</th><th>Quantity</th><th>Minimum quantity</th><th>Store</th><th>Row</th><th>Column</th><th>Shelf</th></tr>";

        fgetcsv($handle);
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            echo "<tr>";
            foreach ($data as $value) {
                echo "<td>" . $value . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        fclose($handle);
    }
}
?>