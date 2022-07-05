<?php
    class Connection {
        public static function connect() {
            $dbHost = "dbHost";
            $dbName = "dbName";
            $dbUser = "dbUser";
            $dbPass = "dbPass";

            try {
                $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                return $conn;

                // var_dump($conn);
                // echo "Connected successfully";

                $conn = null;
            } catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
    }

    $dbConnect = new Connection();
    $dbConnect->connect();