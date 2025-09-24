<?php
// Usuarios iniciais (senhas em hash)
return [
    ['id' => 1, 'nome' => 'João Silva', 'email' => 'joao@email.com', 'senha' => password_hash('SenhaForte1', PASSWORD_DEFAULT)],
    ['id' => 2, 'nome' => 'Maria Oliveira', 'email' => 'maria@email.com', 'senha' => password_hash('Senha123', PASSWORD_DEFAULT)],
];
