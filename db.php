<?php
// db.php

$host = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? '127.0.0.1');
$db = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? '');
$user = getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? '');
$pass = getenv('DB_PASS') ?: ($_ENV['DB_PASS'] ?? '');

if ($db === '' || $user === '') {
    http_response_code(500);
    die('Configuração do banco ausente. Defina DB_HOST, DB_NAME, DB_USER e DB_PASS no ambiente.');
}

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
    die("Erro na conexão com o banco de dados.");
}

?>
