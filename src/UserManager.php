<?php
declare(strict_types=1);

namespace App;

class UserManager
{
    /** @var User[] */
    private array $users = [];
    private array $emailIndex = []; // Índice para otimizar buscas

    public function __construct(array $usersArray = [])
    {
        foreach ($usersArray as $u) {
            // Validação dos dados de entrada
            if (!isset($u['id'], $u['nome'], $u['email'], $u['senha'])) {
                throw new \InvalidArgumentException("Dados do usuário incompletos");
            }
            
            $user = new User((int)$u['id'], $u['nome'], $u['email'], $u['senha']);
            $this->users[$user->getId()] = $user;
            $this->emailIndex[strtolower($user->getEmail())] = $user;
        }
    }

    public function listUsers(): array
    {
        $out = [];
        foreach ($this->users as $u) {
            $out[] = $u->toArray(); // Dados seguros
        }
        return $out;
    }

    public function findByEmail(string $email): ?User
    {
        return $this->emailIndex[strtolower($email)] ?? null;
    }

    public function addUser(string $nome, string $email, string $senha): array
    {
        if (!Validator::validarEmail($email)) {
            return ['success' => false, 'message' => 'E-mail inválido'];
        }

        if (!Validator::senhaForte($senha)) {
            return ['success' => false, 'message' => 'Senha não atende os requisitos de força'];
        }

        if ($this->findByEmail($email) !== null) {
            return ['success' => false, 'message' => 'E-mail já está em uso'];
        }

        $newId = $this->nextId();
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $user = new User($newId, $nome, $email, $hash);
        
        $this->users[$newId] = $user;
        $this->emailIndex[strtolower($email)] = $user;

        return [
            'success' => true, 
            'message' => 'Usuário cadastrado com sucesso', 
            'user' => $user->toArray() // Dados seguros
        ];
    }

    public function login(string $email, string $senha): array
    {
        $user = $this->findByEmail($email);
        if ($user === null) {
            return ['success' => false, 'message' => 'Credenciais inválidas'];
        }

        if (!password_verify($senha, $user->getSenhaHash())) {
            return ['success' => false, 'message' => 'Credenciais inválidas'];
        }

        return [
            'success' => true, 
            'message' => 'Login realizado com sucesso', 
            'user' => $user->toArray() // Dados seguros
        ];
    }

    public function resetSenha(int $id, string $novaSenha): array
    {
        if (!Validator::senhaForte($novaSenha)) {
            return ['success' => false, 'message' => 'Senha não atende os requisitos de força'];
        }

        if (!isset($this->users[$id])) {
            return ['success' => false, 'message' => 'Usuário não encontrado'];
        }

        $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $user = $this->users[$id];
        $user->setSenhaHash($hash);
        
        // Atualizar índice
        $this->emailIndex[strtolower($user->getEmail())] = $user;

        return ['success' => true, 'message' => 'Senha alterada com sucesso'];
    }

    private function nextId(): int
    {
        if (empty($this->users)) {
            return 1;
        }
        return max(array_keys($this->users)) + 1;
    }

    // Método auxiliar para testes
    public function getUserCount(): int
    {
        return count($this->users);
    }
}
