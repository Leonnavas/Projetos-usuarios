<?php
declare(strict_types=1);

require __DIR__ . '/../src/User.php';
require __DIR__ . '/../src/UserManager.php';
require __DIR__ . '/../src/Validator.php';

use App\Auth\UserManager;

$usersArray = require __DIR__ . '/../data/users.php';
$manager = new UserManager($usersArray);

// Test scenarios (5+)
$tests = [];

// Caso 1 — Cadastro válido
$tests[] = ['case' => 'Cadastro válido', 'result' => $manager->addUser('Carlos Pereira', 'carlos@example.com', 'Senha1234')];

// Caso 2 — Cadastro com e-mail inválido
$tests[] = ['case' => 'Cadastro e-mail inválido', 'result' => $manager->addUser('Pedro', 'pedro@@email', 'Senha1234')];

// Caso 3 — Tentativa de login com senha errada
$tests[] = ['case' => 'Login senha errada', 'result' => $manager->login('joao@email.com', 'Errada123')];

// Caso 4 — Reset de senha válido
$tests[] = ['case' => 'Reset senha válido', 'result' => $manager->resetSenha(1, 'NovaSenha1')];

// Caso 5 — Cadastro com e-mail duplicado
$tests[] = ['case' => 'Cadastro e-mail duplicado', 'result' => $manager->addUser('Outro', 'joao@email.com', 'Senha1234')];

// Caso 6 — Senha fraca no cadastro
$tests[] = ['case' => 'Cadastro senha fraca', 'result' => $manager->addUser('Fraco', 'fraco@example.com', 'abc')];

echo json_encode($tests, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
