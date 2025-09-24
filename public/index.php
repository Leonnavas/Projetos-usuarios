<?php
declare(strict_types=1);

// Usar autoloader do Composer
require __DIR__ . '/../vendor/autoload.php';

use App\UserManager;

// Carregar dados iniciais
$usersArray = require __DIR__ . '/../data/users.php';
$manager = new UserManager($usersArray);

// Configurar cabeçalho JSON
header('Content-Type: application/json; charset=utf-8');

// Obter ação dos parâmetros
$action = $_GET['action'] ?? 'help';

try {
    switch ($action) {
        case 'list':
            echo json_encode([
                'success' => true,
                'users' => $manager->listUsers(),
                'total' => $manager->getUserCount()
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;

        case 'register':
            $nome = $_GET['nome'] ?? '';
            $email = $_GET['email'] ?? '';
            $senha = $_GET['senha'] ?? '';
            
            if (empty($nome) || empty($email) || empty($senha)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Parâmetros incompletos. Use: nome, email, senha'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                break;
            }
            
            echo json_encode(
                $manager->addUser($nome, $email, $senha), 
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            );
            break;

        case 'login':
            $email = $_GET['email'] ?? '';
            $senha = $_GET['senha'] ?? '';
            
            if (empty($email) || empty($senha)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Parâmetros incompletos. Use: email, senha'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                break;
            }
            
            echo json_encode(
                $manager->login($email, $senha), 
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            );
            break;

        case 'reset':
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            $novaSenha = $_GET['senha'] ?? '';
            
            if ($id === 0 || empty($novaSenha)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Parâmetros incompletos. Use: id, senha'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                break;
            }
            
            echo json_encode(
                $manager->resetSenha($id, $novaSenha), 
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            );
            break;

        default:
            echo json_encode([
                'success' => true,
                'message' => 'Sistema de Autenticação - API',
                'usage' => [
                    'list' => '/public/index.php?action=list',
                    'register' => '/public/index.php?action=register&nome=Nome&email=meu@email.com&senha=Senha123',
                    'login' => '/public/index.php?action=login&email=meu@email.com&senha=Senha123',
                    'reset' => '/public/index.php?action=reset&id=1&senha=NovaSenha1',
                ]
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno: ' . $e->getMessage()
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
