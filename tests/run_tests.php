<?php
declare(strict_types=1);

// Usar autoloader do Composer
require __DIR__ . '/../vendor/autoload.php';

use App\UserManager;

// Carregar dados iniciais
$usersArray = require __DIR__ . '/../data/users.php';
$manager = new UserManager($usersArray);

// Test scenarios
$tests = [];

echo "=== INICIANDO TESTES ===\n\n";

// Teste 1 — Cadastro válido
echo "1. Cadastro válido... ";
$result = $manager->addUser('Carlos Pereira', 'carlos@example.com', 'Senha1234');
$tests[] = ['case' => 'Cadastro válido', 'result' => $result];
echo $result['success'] ? "✅ PASSOU\n" : "❌ FALHOU - {$result['message']}\n";

// Teste 2 — Cadastro com e-mail inválido
echo "2. Cadastro e-mail inválido... ";
$result = $manager->addUser('Pedro', 'pedroemail.com', 'Senha1234'); // Sem @
$tests[] = ['case' => 'Cadastro e-mail inválido', 'result' => $result];
echo !$result['success'] ? "✅ PASSOU\n" : "❌ FALHOU\n";

// Teste 3 — Tentativa de login com senha errada
echo "3. Login senha errada... ";
$result = $manager->login('joao@email.com', 'Errada123');
$tests[] = ['case' => 'Login senha errada', 'result' => $result];
echo !$result['success'] ? "✅ PASSOU\n" : "❌ FALHOU\n";

// Teste 4 — Login válido
echo "4. Login válido... ";
$result = $manager->login('maria@email.com', 'Senha123');
$tests[] = ['case' => 'Login válido', 'result' => $result];
echo $result['success'] ? "✅ PASSOU\n" : "❌ FALHOU - {$result['message']}\n";

// Teste 5 — Reset de senha válido
echo "5. Reset senha válido... ";
$result = $manager->resetSenha(1, 'NovaSenha1');
$tests[] = ['case' => 'Reset senha válido', 'result' => $result];
echo $result['success'] ? "✅ PASSOU\n" : "❌ FALHOU - {$result['message']}\n";

// Teste 6 — Cadastro com e-mail duplicado
echo "6. Cadastro e-mail duplicado... ";
$result = $manager->addUser('Outro João', 'joao@email.com', 'Senha1234');
$tests[] = ['case' => 'Cadastro e-mail duplicado', 'result' => $result];
echo !$result['success'] ? "✅ PASSOU\n" : "❌ FALHOU\n";

// Teste 7 — Senha fraca no cadastro
echo "7. Cadastro senha fraca... ";
$result = $manager->addUser('Usuario Fraco', 'fraco@example.com', 'abc');
$tests[] = ['case' => 'Cadastro senha fraca', 'result' => $result];
echo !$result['success'] ? "✅ PASSOU\n" : "❌ FALHOU\n";

// Resumo
echo "\n=== RESUMO DOS TESTES ===\n";
$passados = 0;
foreach ($tests as $i => $test) {
    $status = $test['result']['success'] === ($test['case'] === 'Login válido' || $test['case'] === 'Cadastro válido' || $test['case'] === 'Reset senha válido');
    echo ($i + 1) . ". {$test['case']}: " . ($status ? "✅ PASSOU" : "❌ FALHOU") . "\n";
    if ($status) $passados++;
}

echo "\nTotal: {$passados}/" . count($tests) . " testes passaram\n";

// Saída JSON para referência
echo "\n=== DETALHES EM JSON ===\n";
echo json_encode($tests, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
