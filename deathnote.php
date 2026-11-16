<?php
echo "\033[1;31m=== DEATH NOTE ===\033[0m\n";
echo "Escreva um nome no caderno: ";
$nome = trim(fgets(STDIN));
echo "\033[31m$nome\033[0m foi escrito no caderno...\n";
sleep(2);
echo "40 segundos depois: ataque cardíaco.\n";