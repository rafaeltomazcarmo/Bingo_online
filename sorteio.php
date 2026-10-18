<?php
declare(strict_types=1);
require 'db.php';

// Função para determinar a letra do número sorteado
function obterLetraBingo(int $numero): string {
    if ($numero >= 1 && $numero <= 15) return 'B';
    if ($numero >= 16 && $numero <= 30) return 'I';
    if ($numero >= 31 && $numero <= 45) return 'N';
    if ($numero >= 46 && $numero <= 60) return 'G';
    return 'O';
}

// Lógica do sorteio - Executada apenas quando o botão for clicado
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
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sorteio de Bingo</title>
    <link rel="stylesheet" href="style2.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 400px;
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
            margin: 20px 0;
            color: #e76f51;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sorteio de Bingo</h1>

        <form method="POST" action="">
            <button type="submit" name="sorteio" class="btn">Sortear Número</button>
        </form>

        <?php if (isset($mensagem)): ?>
            <div class="mensagem"><?= $mensagem ?></div>
        <?php endif; ?>

        <a href="bingo.php" class="btn" style="margin-top: 20px;">Ver Acompanhamento</a>
        <a href="reset.php" class="btn" style="background-color: #e63946; margin-top: 10px;">Resetar Jogo</a>
    </div>
</body>
</html>
