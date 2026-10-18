<?php
declare(strict_types=1);
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit('Método não permitido.');
}

$numerosSorteados = $pdo->query("SELECT numero FROM numeros_sorteados")->fetchAll(PDO::FETCH_COLUMN);

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Content-Type: application/json');
echo json_encode($numerosSorteados);
