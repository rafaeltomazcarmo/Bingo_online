<?php
declare(strict_types=1);
require 'db.php';

$numerosSorteados = $pdo->query("SELECT numero FROM numeros_sorteados")->fetchAll(PDO::FETCH_COLUMN);

// Definindo as colunas do bingo
$colunas = [
    'B' => range(1, 15),
    'I' => range(16, 30),
    'N' => range(31, 45),
    'G' => range(46, 60),
    'O' => range(61, 75)
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Números Sorteados - Bingo</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .bingo-card {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            text-align: center;
        }
        .coluna {
            background-color: #f0f0f0;
            border-radius: 6px;
            padding: 5px;
        }
        .cabecalho {
            background-color: #264653;
            color: white;
            font-weight: bold;
            padding: 10px;
            border-radius: 4px;
        }
        .numero {
            padding: 10px;
            background-color: #ddd;
            margin: 4px 0;
            border-radius: 4px;
            font-weight: bold;
        }
        .sorteado {
            background-color: #2a9d8f;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cartela de Números Sorteados</h2>
        <div class="bingo-card">
            <?php foreach ($colunas as $letra => $numeros): ?>
                <div class="coluna">
                    <div class="cabecalho"><?= $letra ?></div>
                    <?php foreach ($numeros as $numero): ?>
                        <div class="numero <?= in_array($numero, $numerosSorteados) ? 'sorteado' : '' ?>">
                            <?= $numero ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="botoes">
            <a href="sorteio.php" class="btn">Sortear Novo Número</a>
            <form method="POST" action="reset.php" style="display:inline;">
                <?php if (getenv('RESET_TOKEN') ?: ($_ENV['RESET_TOKEN'] ?? '') !== ''): ?>
                    <input type="hidden" name="token" value="<?= htmlspecialchars(getenv('RESET_TOKEN') ?: ($_ENV['RESET_TOKEN'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                <?php endif; ?>
                <button type="submit" class="btn reset">Resetar Jogo</button>
            </form>
        </div>
    </div>
</body>
</html>
