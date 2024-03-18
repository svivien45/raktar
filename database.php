<?php

class Store {
    protected $mysqli;

    function __construct($host = 'localhost', $user = 'root', $password = null, $database = 'store') {
        $this->mysqli = mysqli_connect($host, $user, $password, $database);

        if(!$this->mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    private function createTablesStores()
    {
        $this->mysqli->query('CREATE TABLE IF NOT EXISTS Stores(
            id INT PRIMARY KEY auto_increment,
            name VARCHAR(200) NOT NULL,
            address VARCHAR(200) NOT NULL)');
    }

    private function createTableRows()
    {
        $this->mysqli->query('CREATE TABLE IF NOT EXISTS Trows(
            id INT PRIMARY KEY auto_increment,
            name VARCHAR(200) NOT NULL)');
    }

    private function createTableColumns()
    {
        $this->mysqli->query('CREATE TABLE IF NOT EXISTS Columns(
            id INT PRIMARY KEY auto_increment,
            name VARCHAR(200) NOT NULL)');
    }

    private function createTableShelves()
    {
        $this->mysqli->query('CREATE TABLE IF NOT EXISTS Shelves(
            id INT PRIMARY KEY auto_increment,
            name VARCHAR(200) NOT NULL)');
    }

    private function createTableProducts()
    {
        $this->mysqli->query('CREATE TABLE IF NOT EXISTS Products(
            id INT PRIMARY KEY auto_increment,
            id_store INT,
            id_row INT,
            id_column INT,
            id_shelf INT,
            name VARCHAR(200) NOT NULL,
            min_qty INT,
            quantity INT,
            price INT)');
    }

    private function insertStores($csvFile)
    {
        if(($handle = fopen($csvFile, "r")) !== FALSE) {
            fgetcsv($handle);
            while (($data = fgetcsv($handle, 1000, ";")) !==FALSE) {
                $name = $data[0];
                $address = $data[1];
                $query = "INSERT INTO Stores (name, address) VALUES ('$name', '$address')";
                $this->mysqli->query($query);
            }
            fclose($handle);
        } else{
            die("Error opening CSV file.");
        }
    }

    private function insertRows($csvFile)
    {
        if(($handle = fopen($csvFile, "r")) !== FALSE) {
            fgetcsv($handle);
            while (($data = fgetcsv($handle, 1000, ";")) !==FALSE) {
                $name = $data[0];
                $query = "INSERT INTO Trows (name) VALUES ('$name')";
                $this->mysqli->query($query);
            }
            fclose($handle);
        } else{
            die("Error opening CSV file.");
        }
    }

    private function insertColumns($csvFile)
    {
        if(($handle = fopen($csvFile, "r")) !== FALSE) {
            fgetcsv($handle);
            while (($data = fgetcsv($handle, 1000, ";")) !==FALSE) {
                $name = $data[0];
                $query = "INSERT INTO Columns (name) VALUES ('$name')";
                $this->mysqli->query($query);
            }
            fclose($handle);
        } else{
            die("Error opening CSV file.");
        }
    }

    private function insertShelves($csvFile)
    {
        if(($handle = fopen($csvFile, "r")) !== FALSE) {
            fgetcsv($handle);
            while (($data = fgetcsv($handle, 1000, ";")) !==FALSE) {
                $name = $data[0];
                $query = "INSERT INTO Shelves (name) VALUES ('$name')";
                $this->mysqli->query($query);
            }
            fclose($handle);
        } else{
            die("Error opening CSV file.");
        }
    }

    private function insertProducts($csvFile)
    {
        if(($handle = fopen($csvFile, "r")) !== FALSE) {
            fgetcsv($handle);
            while(($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $name = $data[0];
                $price = $data[1];
                $quantity = $data[2];
                $min_qty = $data[3];
                $id_store = $this->getStoreId($data[4]);
                $id_row = $this->getRowId($data[5]);
                $id_column = $this->getColumnId($data[6]);
                $id_shelf = $this->getShelfId($data[7]);

                $query = "INSERT INTO Products (id_store, id_row, id_column, id_shelf, name, min_qty, quantity, price) VALUES ('$id_store', '$id_row', '$id_column', '$id_shelf', '$name', '$min_qty', '$quantity', '$price')";
                $this->mysqli->query($query);
            }
            fclose($handle);
        }else {
            die("Error opening CSV file");
        }
    }

    private function getStoreId($storeName)
    {
        $query = "SELECT id FROM Stores WHERE name = '$storeName'";
        $result = $this->mysqli->query($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id'];
        }
    }

    private function getRowId($rowName)
    {
        $query = "SELECT id FROM Trows WHERE name = '$rowName'";
        $result = $this->mysqli->query($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id'];
        }
    }

    private function getColumnId($columnName)
    {
        $query = "SELECT id FROM Columns WHERE name = '$columnName'";
        $result = $this->mysqli->query($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id'];
        }
    }

    private function getShelfId($shelfName)
    {
        $query = "SELECT id FROM Shelves WHERE name = '$shelfName'";
        $result = $this->mysqli->query($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id'];
        }
    }


    public function createTables()
    {
        $this->createTablesStores();
        $this->createTableRows();
        $this->createTableColumns();
        $this->createTableProducts();
        $this->createTableShelves();
    }

    public function insertData()
    {
        $this->insertStores('stores.csv');
        $this->insertRows('rows.csv');
        $this->insertColumns('columns.csv');
        $this->insertShelves('shelves.csv');
        $this->insertProducts('products.csv');
    }
}

?>