<?php

echo '<link rel="stylesheet" type="text/css" href="style.css">';

class DataWriter {
    protected $mysqli;

    function __construct($host = 'localhost', $user = 'root', $password = null, $database = 'store') {
        $this->mysqli = new mysqli($host, $user, $password, $database);

        if($this->mysqli->connect_error) {
            die("Connection failed: " . $this->mysqli->connect_error);
        }
    }

    public function writeTable($csvFile) {
        $query = "SELECT * FROM Products";
        $result = $this->mysqli->query($query);

        if (!$result) {
            die("Query failed: " . $this->mysqli->error);
        }

        echo "<table border='1'>";
        echo "<tr><th>Name</th><th>Price</th><th>Quantity</th><th>Minimum quantity</th><th>Store</th><th>Row</th><th>Column</th><th>Shelf</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['price'] . "</td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "<td>" . $row['min_qty'] . "</td>";
            $storeId = $row['id_store'];
            $storeName = $this->getNameById('Stores', 'id', $storeId);
            echo "<td>" . $storeName . "</td>";

            $rowId = $row['id_row'];
            $rowName = $this->getNameById('Trows', 'id', $rowId);
            echo "<td>" . $rowName . "</td>";

            $columnId = $row['id_column'];
            $columnName = $this->getNameById('Columns', 'id', $columnId);
            echo "<td>" . $columnName . "</td>";

            $shelfId = $row['id_shelf'];
            $shelfName = $this->getNameById('Shelves', 'id', $shelfId);
            echo "<td>" . $shelfName . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }


    private function getNameById($tableName, $idColumn, $idValue) {
        $query = "SELECT name FROM $tableName WHERE $idColumn = '$idValue'";
        $result = $this->mysqli->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['name'];
        }
    }

    public function searchByName($productName) {
        $query = "SELECT * FROM products WHERE name LIKE '%$productName%' ";
        $result = $this->mysqli->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()){
                echo "<h2>Search result</h2>";
                echo "<div class='search'>";
                echo "<p>Name:" . $row['name'] . "</p>";
                echo "<p>Price:" . $row['price'] . "</p>";
                echo "<p>Quantity:" . $row['quantity'] . "</p>";
                $storeId = $row['id_store'];
                $storeName = $this->getNameById('Stores', 'id', $storeId);
                echo  "<p> Store: " . $storeName . "</p>";

                $rowId = $row['id_row'];
                $rowName = $this->getNameById('Trows', 'id', $rowId);
                echo  "<p> Row: " . $rowName . "</p>";

                $columnId = $row['id_column'];
                $columnName = $this->getNameById('Columns', 'id', $columnId);
                echo "<p> Column: " . $columnName . "</p>";

                $shelfId = $row['id_shelf'];
                $shelfName = $this->getNameById('Shelves', 'id', $shelfId);
                echo  "<p> Shelf: " . $shelfName . "</p>";
                echo "</div>";
            }
        }else{
            echo "This item not in stock";
        }
    }

    public function addProduct($storeName, $rowName, $columnName, $shelfName, $name, $min_qty, $quantity, $price) {
        $storeQuery = "SELECT id FROM stores WHERE name ='$storeName'";
        $storeResult = $this->mysqli->query($storeQuery);
        if($storeResult && $storeResult->num_rows > 0) {
            $storeRow = $storeResult->fetch_assoc();
            $id_store = $storeRow['id'];
        }

        $rowQuery = "SELECT id FROM Trows WHERE name = '$rowName'";
        $rowResult = $this->mysqli->query($rowQuery);
        if ($rowResult && $rowResult->num_rows > 0) {
            $rowRow = $rowResult->fetch_assoc();
            $id_row = $rowRow['id'];
        }

        $columnQuery = "SELECT id FROM Columns WHERE name = '$columnName'";
        $columnResult = $this->mysqli->query($columnQuery);
        if ($columnResult && $columnResult->num_rows > 0) {
            $columnRow = $columnResult->fetch_assoc();
            $id_column = $columnRow['id'];
        }


        $shelfQuery = "SELECT id FROM Shelves WHERE name = '$shelfName'";
        $shelfResult = $this->mysqli->query($shelfQuery);
        if ($shelfResult && $shelfResult->num_rows > 0) {
            $shelfRow = $shelfResult->fetch_assoc();
            $id_shelf = $shelfRow['id'];
        }
        $existingProductQuery = "SELECT COUNT(*) as count FROM products WHERE name = '$name' AND id_store = (SELECT id FROM stores WHERE name = '$storeName') AND id_row = (SELECT id FROM Trows WHERE name = '$rowName') AND id_column = (SELECT id FROM Columns WHERE name = '$columnName') AND id_shelf = (SELECT id FROM Shelves WHERE name = '$shelfName')";
        $existingProductResult = $this->mysqli->query($existingProductQuery);
        $existingProductData = $existingProductResult->fetch_assoc();
        $existingProductCount = $existingProductData['count'];

        if ($existingProductCount > 0) {

            return "A termék már létezik az adatbázisban.";
        } else {
            $query = "INSERT INTO products (id_store, id_row, id_column, id_shelf, name, min_qty, quantity, price)
                      VALUES ($id_store, $id_row, $id_column, $id_shelf, '$name', $min_qty, $quantity, $price)";
            return $this->mysqli->query($query);
        }
    }

    public function getLowStockProducts() {
        $query = "SELECT * FROM products WHERE quantity < min_qty";
        $result = $this->mysqli->query($query);

        if ($result->num_rows > 0) {
            $lowStockProducts = [];
            while ($row = $result->fetch_assoc()) {
                $lowStockProducts[] = $row;
            }
            return $lowStockProducts;
        } else {
            return false;
        }
    }
}

?>