<?php

  class DatabaseConnection
  {
    private $serverName = "localhost";
    private $dbName = "bangubank_db";
    private $userName = "root";
    private $password = "root";

    public function connectToDB()
    {
        $conn = mysqli_connect($this->serverName, $this->userName, $this->password, $this->dbName);

        if (!$conn) {
          die("Connection failed = " . mysqli_connect_error());
        }

        return $conn;
    }
  }

?>