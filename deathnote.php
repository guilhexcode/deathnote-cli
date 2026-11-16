<?php
echo "\033[1;31m=== DEATH NOTE ===\033[0m\n";

echo "Nome: ";
$nome = trim(fgets(STDIN));

echo "Causa da morte (Enter = ataque cardíaco): ";
$causa = trim(fgets(STDIN)) ?: "ataque cardíaco";

echo "\n\033[31m$nome\033[0m foi escrito no caderno.\n";

for ($i = 40; $i >= 0; $i--) {
    echo "\r\033[33mTempo restante: " . str_pad($i, 2, " ", STR_PAD_LEFT) . "s\033[0m";
    usleep(1000000); // 1 segundo. Troca por 100000 pra testar mais rápido
}

echo "\n\n$nome morreu de $causa.\n";