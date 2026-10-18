<?php
declare(strict_types=1);
require 'db.php';

$resetToken = getenv('RESET_TOKEN') ?: ($_ENV['RESET_TOKEN'] ?? '');

// Total de números do bingo
const TOTAL_NUMEROS = 75;

// Verificar se todos já foram sorteados
$stmt = $pdo->query("SELECT COUNT(*) FROM numeros_sorteados");
$totalSorteados = (int) $stmt->fetchColumn();

if ($totalSorteados >= TOTAL_NUMEROS) {
    $mensagem = "Todos os números já foram sorteados!";
} else {
    do {
        $numero = random_int(1, TOTAL_NUMEROS);
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM numeros_sorteados WHERE numero = ?");
        $stmt->execute([$numero]);
        $ja_sorteado = (int) $stmt->fetchColumn();
    } while ($ja_sorteado > 0);

    // Armazena o número sorteado
    $stmt = $pdo->prepare("INSERT INTO numeros_sorteados (numero) VALUES (?)");
    $stmt->execute([$numero]);

    $mensagem = "Número Sorteado: <strong>$numero</strong>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sorteio de Bingo</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="container">
        <h1>Sorteio de Bingo</h1>
        <div class="resultado"><?= $mensagem ?></div>
        <div class="botoes">
            <a href="sorteio.php" class="btn">Sortear Novo Número</a>
            <a href="acompanhamento.php" class="btn">Ver Números Sorteados</a>
            <form method="POST" action="reset.php" style="display:inline;">
                <?php if ($resetToken !== ''): ?>
                    <input type="hidden" name="token" value="<?= htmlspecialchars($resetToken, ENT_QUOTES, 'UTF-8'); ?>">
                <?php endif; ?>
                <button type="submit" class="btn reset">Resetar Jogo</button>
            </form>
        </div>
    </div>
</body>
</html>
