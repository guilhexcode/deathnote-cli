<?php
echo "\033[1;31m=== DEATH NOTE ===\033[0m\n";

$caderno = [];

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

    $caderno[] = ["nome" => $nome, "causa" => $causa];
    echo "\033[31m$nome\033[0m foi escrito no caderno.\n";
}

if (count($caderno) === 0) {
    echo "Nenhum nome foi escrito. O caderno está em branco.\n";
    exit;
}

echo "\n\033[1mNomes no caderno:\033[0m\n";
foreach ($caderno as $i => $entrada) {
    echo ($i + 1) . ". {$entrada['nome']} - {$entrada['causa']}\n";
}

for ($i = 40; $i >= 0; $i--) {
    echo "\r\033[33mTempo restante: " . str_pad($i, 2, " ", STR_PAD_LEFT) . "s\033[0m";
    usleep(1000000); // troca por 100000 pra testar mais rápido
}

echo "\n\n";
foreach ($caderno as $entrada) {
    echo "{$entrada['nome']} morreu de {$entrada['causa']}.\n";
}