<?php
declare(strict_types=1);
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método não permitido.');
}

$resetToken = getenv('RESET_TOKEN') ?: ($_ENV['RESET_TOKEN'] ?? '');
if ($resetToken !== '' && ($_POST['token'] ?? '') !== $resetToken) {
    http_response_code(403);
    exit('Acesso negado.');
}

try {
    $pdo->exec("DELETE FROM numeros_sorteados");
} catch (PDOException $e) {
    http_response_code(500);
    exit('Erro ao resetar o jogo.');
}

header('Location: bingo.php');
exit;
