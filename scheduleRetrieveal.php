<?php
$dsn = "mysql:host=localhost;dbname=ClubDatabase";
$username = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Correct constant name

    $sql = "SELECT * FROM events";
    $stmt = $pdo->query($sql); // Use PDO statement

    if ($stmt) {
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC); // More efficient fetching
        header('Content-Type: application/json');
        echo json_encode($events);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => "Database query failed: " . $stmt->errorInfo()[2]]); // Use PDO errorInfo
    }
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => "Connection failed: " . $e->getMessage()]);
}
?>