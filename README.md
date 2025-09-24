RA:2010317 
Leonardo Nunes Navas

# Sistema de Usuários e Autenticação (PHP - simples)

Baseado no PRD fornecido (exercício **Sistema de Usuários e Autenticação**).  
Documento de referência: `projeto-2-user-auth-prd.pdf`. fileciteturn0file0

## Estrutura
- `src/` - Classes PHP (User, UserManager, Validator) seguindo POO.
- `data/users.php` - Lista inicial de usuários (array PHP). Não há persistência.
- `public/index.php` - Mini-API via query string para testar as funcionalidades.
- `tests/run_tests.php` - Script que executa >=5 cenários de teste e imprime JSON.
- `README.md` - Este arquivo.

## Requisitos implementados
- Validação de e-mail.
- Regras de senha forte (mín. 8 caracteres, ao menos 1 número e 1 maiúscula).
- Senhas armazenadas como hash (password_hash) e verificação com password_verify.
- Casos de uso implementados (cadastro, login, reset).
- Código organizado em `src/` e documentação mínima.

#. Rodar testes
php tests/run_tests.php

#. Testar no navegador
# http://localhost/projeto_usuarios_php/public/index.php?action=list

## Casos de teste documentados (exemplos)
1. Cadastro válido → nome: Maria Oliveira, email: maria@email.com, senha: Senha123. Resultado: usuário cadastrado com sucesso.
2. Cadastro com e-mail inválido → email: pedro@@email. Resultado: "E-mail inválido".
3. Tentativa de login com senha errada → email: joao@email.com, senha: Errada123. Resultado: "Credenciais inválidas".
4. Reset de senha válido → id 1, nova senha NovaSenha1. Resultado: senha alterada com sucesso.
5. Cadastro com e-mail duplicado → usar email existente. Resultado: "E-mail já está em uso".
6. Senha fraca no cadastro → senha: abc. Resultado: "Senha não atende os requisitos de força".

## Observações / Limitações
- Dados são mantidos apenas em memória (arrays). Não há banco de dados.
- Para persistência simples, poderia usar SQLite (desafio sugerido no PRD).
- PSR-12 foi considerado na organização básica; este projeto é uma base didática.

