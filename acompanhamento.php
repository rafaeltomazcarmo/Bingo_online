<?php
declare(strict_types=1);
require 'db.php';

$resetToken = getenv('RESET_TOKEN') ?: ($_ENV['RESET_TOKEN'] ?? '');

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
    <link rel="stylesheet" href="style2.css">
    <style>
        .bingo-card {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 5px;
            text-align: center;
        }
        .coluna {
            background-color: #f0f0f0;
            border-radius: 6px;
            padding: 10px;
        }
        .cabecalho {
            background-color: #264653;
            color: white;
            font-weight: bold;
            padding: 10px;
            border-radius: 4px;
        }
        .numero {
            padding: 12px;
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
        <div class="botoes">
            <h2>Cartela de Números Sorteados</h2>
            <a href="sorteio.php" class="btn">Sortear Novo Número</a>
            <form method="POST" action="reset.php" style="display:inline; margin-top:10px;">
                <?php if ($resetToken !== ''): ?>
                    <input type="hidden" name="token" value="<?= htmlspecialchars($resetToken, ENT_QUOTES, 'UTF-8'); ?>">
                <?php endif; ?>
                <button type="submit" class="btn reset">Resetar Jogo</button>
            </form>
        </div>
        <div class="bingo-card" id="bingo-card">
            <?php foreach ($colunas as $letra => $numeros): ?>
                <div class="coluna">
                    <div class="cabecalho"><?= $letra ?></div>
                    <?php foreach ($numeros as $numero): ?>
                        <div class="numero" id="num-<?= $numero ?>">
                            <?= $numero ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>

       
    </div>

    <script>
        function atualizarNumeros() {
            fetch('atualizar_numeros.php')
                .then(response => response.json())
                .then(numerosSorteados => {
                    document.querySelectorAll('.numero').forEach(el => {
                        const numero = parseInt(el.textContent);
                        el.classList.toggle('sorteado', numerosSorteados.includes(numero));
                    });
                })
                .catch(error => console.error('Erro ao buscar números:', error));
        }

        // Atualiza a cada 3 segundos
        setInterval(atualizarNumeros, 3000);

        // Atualiza imediatamente ao carregar a página
        atualizarNumeros();
    </script>
</body>
</html>
