<?php

class Store {
    protected $mysqli;

    function __construct($host = 'localhost', $user = 'root', $password = null, $database = 'store') {
        $this->mysqli = mysqli_connect($host, $user, $password, $database);

        $this->mysqli->set_charset("utf8");
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

    private function createTableUsers()
    {
        $this->mysqli->query('CREATE TABLE IF NOT EXISTS Users(
            id INT PRIMARY KEY AUTO_INCREMENT,
            is_active TINYINT DEFAULT FALSE,
            name VARCHAR(50) NOT NULL,
            email VARCHAR(25) NOT NULL UNIQUE,
            password VARCHAR(200) not null,
            token VARCHAR(200),
            token_valid_until DATETIME,
            created_at DATETIME DEFAULT NOW(),
            registered_at DATETIME DEFAULT NOW(),
            picture VARCHAR(50),
            deleted_at DATETIME)');

        $this->mysqli->query("CREATE TABLE IF NOT EXISTS user_tokens (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(100),
            token VARCHAR(32),
            expires_at DATETIME
        )");
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
        $this->createTableUsers();
    }

    public function insertData()
    {
        $query = "SELECT COUNT(*) as count FROM Stores";
        $result = $this->mysqli->query($query);
        $row = $result->fetch_assoc();
        $storeCount = $row['count'];

        if($storeCount == 0) {
            $this->insertStores('stores.csv');
            $this->insertRows('rows.csv');
            $this->insertColumns('columns.csv');
            $this->insertShelves('shelves.csv');
            $this->insertProducts('products.csv');
        }
    }

    public function setUserActive($userId)
    {
        $stmt = $this->mysqli->prepare('UPDATE users SET is_active = 1 WHERE id = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->close();
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->mysqli->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function registerUser($name, $email, $password, $token, $token_valid_until)
    {
        if ($name != null) {

            $stmt = $this->mysqli->prepare('INSERT INTO Users (name, email, password, token, token_valid_until, registered_at) VALUES (?, ?, ?, ?, ?, NOW())');
            $stmt->bind_param('sssss', $name, $email, $password, $token, $token_valid_until);
            $stmt->execute();
            $stmt->close();
        }
    }

    public function getUserById($userId)
    {
        $stmt = $this->mysqli->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function setUserInactive($userId)
    {
        $stmt = $this->mysqli->prepare('UPDATE users SET is_active = 0 WHERE id = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->close();
    }

    public function storeToken($email, $token, $expires_at) {
        $stmt = $this->mysqli->prepare("INSERT INTO user_tokens (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $token, $expires_at);
        $stmt->execute();
        $stmt->close();
    }

    public function getUserByToken($token) {
        $stmt = $this->mysqli->prepare("SELECT * FROM users WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function activateUser($email) {
        $stmt = $this->mysqli->prepare("UPDATE users SET is_active = 1 WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteToken($token) {
        $stmt = $this->mysqli->prepare("DELETE FROM user_tokens WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->close();
    }

}

?>