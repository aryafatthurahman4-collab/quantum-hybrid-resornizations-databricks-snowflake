<?php
$host = '127.0.0.1';
$db   = 'hr_management';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    
    $sql = "-- HRIS ITK Database Dump\n";
    $sql .= "-- Created at " . date('Y-m-d H:i:s') . "\n";
    $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

    foreach ($tables as $t) {
        $create = $pdo->query("SHOW CREATE TABLE `$t`")->fetch(PDO::FETCH_ASSOC);
        $createSql = $create['Create Table'] ?? $create['Create View'] ?? '';
        
        $sql .= "-- Structure for table `$t` --\n";
        $sql .= "DROP TABLE IF EXISTS `$t`;\n";
        $sql .= $createSql . ";\n\n";

        $rows = $pdo->query("SELECT * FROM `$t`")->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) > 0) {
            $sql .= "-- Data for table `$t` --\n";
            foreach ($rows as $r) {
                $cols = array_map(fn($c) => "`$c`", array_keys($r));
                $vals = array_map(function($v) use ($pdo) {
                    if ($v === null) return 'NULL';
                    return $pdo->quote($v);
                }, array_values($r));

                $sql .= "INSERT INTO `$t` (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $vals) . ");\n";
            }
            $sql .= "\n";
        }
    }

    $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

    file_put_contents(__DIR__ . '/hr_management.sql', $sql);
    echo "SQL Dump exported successfully to " . __DIR__ . '/hr_management.sql' . " (" . strlen($sql) . " bytes)\n";
} catch (Exception $e) {
    echo "Export Error: " . $e->getMessage() . "\n";
}
