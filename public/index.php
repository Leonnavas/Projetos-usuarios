<?php
declare(strict_types=1);

require __DIR__ . '/../src/User.php';
require __DIR__ . '/../src/UserManager.php';
require __DIR__ . '/../src/Validator.php';

use App\Auth\UserManager;

$usersArray = require __DIR__ . '/../data/users.php';
$manager = new UserManager($usersArray);

// Example usage through query params (for simple tests):
$action = $_GET['action'] ?? 'help';

header('Content-Type: application/json; charset=utf-8');

switch ($action) {
    case 'list':
        echo json_encode($manager->listUsers(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
    case 'register':
        $nome = $_GET['nome'] ?? '';
        $email = $_GET['email'] ?? '';
        $senha = $_GET['senha'] ?? '';
        echo json_encode($manager->addUser($nome, $email, $senha), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
    case 'login':
        $email = $_GET['email'] ?? '';
        $senha = $_GET['senha'] ?? '';
        echo json_encode($manager->login($email, $senha), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
    case 'reset':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $nova = $_GET['senha'] ?? '';
        echo json_encode($manager->resetSenha($id, $nova), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
    default:
        echo json_encode([
            'usage' => [
                'list' => '/public/index.php?action=list',
                'register' => '/public/index.php?action=register&nome=Nome&email=meu@email.com&senha=Senha123',
                'login' => '/public/index.php?action=login&email=meu@email.com&senha=Senha123',
                'reset' => '/public/index.php?action=reset&id=1&senha=NovaSenha1',
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
}
