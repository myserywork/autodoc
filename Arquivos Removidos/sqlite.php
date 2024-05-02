<?php

class SQLiteDB {
    private $pdo;

    public function __construct($dbname = "database.sqlite") {
        $dsn = "sqlite:" . $dbname;
        try {
            $this->pdo = new PDO($dsn);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Failed to connect to the database: " . $e->getMessage());
        }
    }

    public function query($sql) {
        try {
            $stmt = $this->pdo->query($sql);
            return $stmt;
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function displayResults($stmt) {
        echo "<table border='1' style='width:100%; text-align:left;'>";
        echo "<tr>";
        for ($i = 0; $i < $stmt->columnCount(); $i++) {
            $col = $stmt->getColumnMeta($i);
            echo "<th>{$col['name']}</th>";
        }
        echo "</tr>";
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
}

