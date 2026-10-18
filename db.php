<?php
// db.php

$host = '127.0.0.1';
$db = 'u937085757_Project_servic';
$user = 'u937085757_sysdba';
$pass = '0V3omDX*6|gW'; // Altere conforme suas configurações

try {
    $pdo = new PDO(
        dsn: "mysql:host=$host;dbname=$db;charset=utf8mb4",
        username: $user,
        password: $pass,
        options: [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die("Erro na conexão com o banco de dados: " . htmlspecialchars($e->getMessage()));
}


?>
