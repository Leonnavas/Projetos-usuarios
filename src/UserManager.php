<?php
declare(strict_types=1);

namespace App\Auth;

class UserManager
{
    /** @var User[] */
    private array $users = [];

    public function __construct(array $usersArray = [])
    {
        foreach ($usersArray as $u) {
            $user = new User((int)$u['id'], (string)$u['nome'], (string)$u['email'], (string)$u['senha']);
            $this->users[$user->getId()] = $user;
        }
    }

    public function listUsers(): array
    {
        $out = [];
        foreach ($this->users as $u) {
            $out[] = $u->toArray();
        }
        return $out;
    }

    public function findByEmail(string $email): ?User
    {
        foreach ($this->users as $u) {
            if (strtolower($u->getEmail()) === strtolower($email)) {
                return $u;
            }
        }
        return null;
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

        return ['success' => true, 'message' => 'Usuário cadastrado com sucesso', 'user' => $user->toArray()];
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

        return ['success' => true, 'message' => 'Login realizado com sucesso', 'user' => $user->toArray()];
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
        $this->users[$id]->setSenhaHash($hash);

        return ['success' => true, 'message' => 'Senha alterada com sucesso'];
    }

    private function nextId(): int
    {
        if (empty($this->users)) {
            return 1;
        }

        return max(array_keys($this->users)) + 1;
    }
}
