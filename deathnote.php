<?php
const ARQUIVO = __DIR__ . "/caderno.json";
const LIMITE_CAUSA = 40; // segundos pra escrever a causa

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

function perguntar(string $texto): string {
    echo $texto;
    return trim(fgets(STDIN));
}

function mostrarRegras(): void {
    echo "\n\033[1mRegras do caderno:\033[0m\n";
    echo "1. Só funciona se você tiver o rosto da pessoa em mente.\n";
    echo "2. Você tem " . LIMITE_CAUSA . " segundos pra escrever a causa da morte.\n";
    echo "3. Passou do tempo: a causa vira ataque cardíaco.\n";
}

function mostrarCapa(): void {
    $caveira = <<<'ARTE'
⠄⠄⠄⠄⠄⠄⠄⣀⣠⣤⣤⣤⣤⣀⡀
⠄⠄⠄⣠⣤⢶⣻⣿⣻⣿⣿⣿⣿⣿⣿⣦⣤⣀
⠄⠄⣼⣺⢷⣻⣽⣾⣿⢿⣿⣷⣿⣿⢿⣿⣿⣿⣇
⠠⡍⢾⣺⢽⡳⣻⡺⣽⢝⢗⢯⣻⢽⣻⣿⣿⣿⣿⢿⡄
⡨⣖⢹⠜⢅⢫⢊⢎⠜⢌⠣⢑⠡⣹⡸⣜⣯⣿⢿⣻⣷
⢜⢔⡹⡭⣪⢼⠽⠷⠧⣳⢘⢔⡝⠾⠽⢿⣷⣿⣟⢷⣟
⢸⢘⢼⠿⠟⠁⠄⠄⡀⠄⠃⠑⡌⠄⠄⠈⠙⠿⣷⢽⣻
⢌⠂⠅⠄⠄⠄⠄⠄⠄⡀⣲⣢⢂⠄⠄⠄⠄⠄⠈⣯⠏
⠐⠨⡂⠄⠄⠄⠄⠄⡀⡔⠋⢻⣤⡀⠄⠄⢀⠄⢸⣯⠇
⠈⣕⠝⠒⠄⠄⠒⢉⠪⠄⠄⠄⢿⠜⠑⠢⠠⡒⡺⣿⠖
⠄⠐⠅⠁⡀⠄⠐⢔⠁⠄⠄⠄⢀⢇⢌⠄⠄⠄⠸⠕
⠄⠄⠂⠄⠄⠨⣔⡝⠼⡄⠂⣦⡆⣿⣲⠐⠑⠁⠄⠃
⠄⠄⠄⠄⠄⠄⠃⢫⢛⣙⡊⣜⣏⡝⣝⠆
⠄⠄⠄⠄⠄⠄⠈⠈⠁⠁⠁⠈⠈⠊

ARTE;
    echo "\033[31m" . $caveira . "\033[0m\n";
}

echo "\033[1;31m=== DEATH NOTE ===\033[0m\n";
mostrarCapa();
mostrarRegras();

$caderno = carregarCaderno();
$novos = [];

if (count($caderno) > 0) {
    echo "\nO caderno já tem " . count($caderno) . " nome(s):\n";
    foreach ($caderno as $i => $entrada) {
        echo ($i + 1) . ". {$entrada['nome']} - {$entrada['causa']}\n";
    }
}

while (true) {
    $nome = perguntar("\nNome (ou 'sair' pra fechar o caderno): ");

    if (strtolower($nome) === "sair") {
        break;
    }
    if ($nome === "") {
        echo "O caderno não aceita nome vazio.\n";
        continue;
    }

    $rosto = strtolower(perguntar("Você está com o rosto dessa pessoa em mente? (s/n): "));
    if ($rosto !== "s") {
        echo "Sem o rosto em mente, o nome não tem efeito. Nada foi escrito.\n";
        continue;
    }

    $inicio = time();
    $causa = perguntar("Causa da morte (Enter = ataque cardíaco): ");
    $demorou = time() - $inicio;

    if ($demorou > LIMITE_CAUSA) {
        echo "\033[33mPassaram-se {$demorou}s. Tarde demais: ataque cardíaco.\033[0m\n";
        $causa = "";
    }
    if ($causa === "") {
        $causa = "ataque cardíaco";
    }

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