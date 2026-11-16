<?php
const ARQUIVO = __DIR__ . "/caderno.json";

function carregarCaderno(): array {
    if (!file_exists(ARQUIVO)) {
        return [];
    }
    $dados = json_decode(file_get_contents(ARQUIVO), true);
    return is_array($dados) ? $dados : [];
}

function salvarCaderno(array $caderno): void {
    file_put_contents(
        ARQUIVO,
        json_encode($caderno, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}

echo "\033[1;31m=== DEATH NOTE ===\033[0m\n";

$caderno = carregarCaderno();
$novos = [];

if (count($caderno) > 0) {
    echo "\nO caderno já tem " . count($caderno) . " nome(s):\n";
    foreach ($caderno as $i => $entrada) {
        echo ($i + 1) . ". {$entrada['nome']} - {$entrada['causa']}\n";
    }
}

while (true) {
    echo "\nNome (ou 'sair' pra fechar o caderno): ";
    $nome = trim(fgets(STDIN));

    if (strtolower($nome) === "sair") {
        break;
    }
    if ($nome === "") {
        echo "O caderno não aceita nome vazio.\n";
        continue;
    }

    echo "Causa da morte (Enter = ataque cardíaco): ";
    $causa = trim(fgets(STDIN)) ?: "ataque cardíaco";

    $entrada = ["nome" => $nome, "causa" => $causa];
    $caderno[] = $entrada;
    $novos[] = $entrada;
    salvarCaderno($caderno);

    echo "\033[31m$nome\033[0m foi escrito no caderno.\n";
}

if (count($novos) === 0) {
    echo "Nenhum nome novo. Caderno fechado.\n";
    exit;
}

for ($i = 40; $i >= 0; $i--) {
    echo "\r\033[33mTempo restante: " . str_pad($i, 2, " ", STR_PAD_LEFT) . "s\033[0m";
    usleep(1000000); // troca por 100000 pra testar mais rápido
}

echo "\n\n";
foreach ($novos as $entrada) {
    echo "{$entrada['nome']} morreu de {$entrada['causa']}.\n";
}