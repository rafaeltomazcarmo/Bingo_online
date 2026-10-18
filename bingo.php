<?php
declare(strict_types=1);
require 'db.php';

$resetToken = getenv('RESET_TOKEN') ?: ($_ENV['RESET_TOKEN'] ?? '');

// Função para determinar a letra do número sorteado
function obterLetraBingo(int $numero): string {
    if ($numero >= 1 && $numero <= 15) return 'B';
    if ($numero >= 16 && $numero <= 30) return 'I';
    if ($numero >= 31 && $numero <= 45) return 'N';
    if ($numero >= 46 && $numero <= 60) return 'G';
    return 'O';
}

// Função para sortear um novo número
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sorteio'])) {
    $numerosSorteados = $pdo->query("SELECT numero FROM numeros_sorteados")->fetchAll(PDO::FETCH_COLUMN);
    $numerosRestantes = array_diff(range(1, 75), $numerosSorteados);

    if ($numerosRestantes) {
        $novoNumero = $numerosRestantes[array_rand($numerosRestantes)];
        $letra = obterLetraBingo($novoNumero);

        $stmt = $pdo->prepare("INSERT INTO numeros_sorteados (numero) VALUES (:numero)");
        $stmt->execute(['numero' => $novoNumero]);

        $mensagem = "Número sorteado: <strong>$letra-$novoNumero</strong>";
    } else {
        $mensagem = "Todos os números já foram sorteados!";
    }
}

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
    <title>Sorteio e Acompanhamento - Bingo</title>
    <link rel="stylesheet" href="style2.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: auto;
        }
        .btn {
            background-color: #264653;
            color: white;
            padding: 15px 20px;
            border: none;
            cursor: pointer;
            font-size: 18px;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #2a9d8f;
        }
        .mensagem {
            font-size: 28px;
            margin: 15px 0;
            color: #e76f51;
            font-weight: bold;
        }
        .bingo-card {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 5px;
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
        <h1>Sorteio de Bingo</h1>

        <form method="POST">
            <button type="submit" name="sorteio" class="btn">Sortear Número</button>
        </form>

        <?php if (isset($mensagem)): ?>
            <div class="mensagem"><?= $mensagem ?></div>
        <?php endif; ?>

        <h2>Cartela de Números Sorteados</h2>
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

        <div class="botoes" style="margin-top: 20px;">
            <form method="POST" action="reset.php" style="display:inline;">
                <?php if ($resetToken !== ''): ?>
                    <input type="hidden" name="token" value="<?= htmlspecialchars($resetToken, ENT_QUOTES, 'UTF-8'); ?>">
                <?php endif; ?>
                <button type="submit" class="btn">Resetar Jogo</button>
            </form>
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
