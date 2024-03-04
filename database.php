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
            name VARCHAR(200) NOT NULL,
            id_store INT)');
    }

    private function createTableColumns()
    {
        $this->mysqli->query('CREATE TABLE IF NOT EXISTS Columns(
            id INT PRIMARY KEY auto_increment,
            name VARCHAR(200) NOT NULL,
            id_row INT)');
    }

    private function createTableProducts()
    {
        $this->mysqli->query('CREATE TABLE IF NOT EXISTS Products(
            id INT PRIMARY KEY auto_increment,
            name VARCHAR(200) NOT NULL,
            id_column INT,
            min_qty INT,
            quantity INT,
            price INT)');
    }

    public function createTables()
    {
        $this->createTablesStores();
        $this->createTableRows();
        $this->createTableColumns();
        $this->createTableProducts();
    }
}

?>