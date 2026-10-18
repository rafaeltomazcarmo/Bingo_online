<?php
// reset.php
require 'db.php';

try {
    $pdo->exec("DELETE FROM numeros_sorteados");
} catch (PDOException $e) {
    die("Erro ao resetar o jogo: " . htmlspecialchars($e->getMessage()));
}

header("Location: bingo.php");
exit;
