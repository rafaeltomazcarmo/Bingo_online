<?php
declare(strict_types=1);
require 'db.php';

$numerosSorteados = $pdo->query("SELECT numero FROM numeros_sorteados")->fetchAll(PDO::FETCH_COLUMN);

header('Content-Type: application/json');
echo json_encode($numerosSorteados);
