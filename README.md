# Café Grão Dourado — Gestão de Cardápio

CRUD completo em **PHP procedural + MySQLi** para o painel administrativo de uma cafeteria.
O gerente faz login e pode **cadastrar, listar, editar e excluir** itens do cardápio.

## Como rodar

### 1. Pré-requisitos
- [XAMPP](https://www.apachefriends.org/) (Apache + PHP) e MySQL instalado (pode ser o do XAMPP ou o MySQL Server separado)
- Git (opcional, para clonar)

### 2. Clonar dentro do `htdocs`
```bash
cd C:/XAMPP/htdocs
git clone https://github.com/Fernando-NVIDIA/Atividade_Classroom_Extra.git
```

### 3. Criar o banco de dados
Abra o MySQL Workbench (ou o phpMyAdmin) e execute:

```sql
CREATE DATABASE gestao_cafeteria;
USE gestao_cafeteria;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE cardapio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_item VARCHAR(100) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    disponivel BOOLEAN DEFAULT TRUE
);

INSERT INTO usuarios (nome, usuario, senha) VALUES ('Gerente', 'admin', 'admin123');
```

### 4. Configurar a conexão
O `conexao.php` **não vai para o GitHub** (está no `.gitignore`, pois tem a senha do banco). Crie o seu:

1. Copie `conexao.example.php` e renomeie a cópia para `conexao.php`.
2. Edite e coloque a senha do seu MySQL em `$senha_db`.

### 5. Ligar o Apache e acessar
Abra o **XAMPP Control Panel**, clique em **Start** no Apache e acesse:

```
http://localhost/Atividade_Classroom_Extra/login.php
```

**Login de teste:** usuário `admin` · senha `admin123`

## O que cada arquivo faz

| Arquivo | Função |
|---|---|
| `login.php` | Tela de login (valida usuário e senha com prepared statement) |
| `logout.php` | Encerra a sessão |
| `auth.php` | Bloqueia quem não está logado e guarda os alertas entre páginas |
| `painel.php` | Painel: resumo, formulário de cadastro e tabela de itens (**C** e **R**) |
| `salvar_item.php` | Insere um novo item no banco (**C**) |
| `editar_item.php` | Mostra o formulário preenchido e salva a atualização (**U**) |
| `excluir_item.php` | Remove um item pelo `?id=` da URL (**D**) |
| `conexao.php` | Conexão com o MySQL *(local, fora do Git)* |
| `conexao.example.php` | Modelo do `conexao.php`, sem senha |
| `topo.php` / `rodape.php` | Estrutura compartilhada das páginas internas (menu lateral) |
| `style.css` | Estilo visual do site |
| `grao.svg` | Desenho do grão de café usado no visual |
| `.gitignore` | Evita enviar o `conexao.php` ao GitHub |

## Segurança já implementada
- Todas as consultas usam **Prepared Statements** (`mysqli_prepare`), protegendo contra SQL Injection.
- Páginas internas exigem login (sessão PHP).
- Dados exibidos na tela passam por `htmlspecialchars` (proteção contra XSS).

## Trabalhos futuros
- [ ] Guardar senhas com hash (`password_hash` / `password_verify`) em vez de texto puro
- [ ] Excluir via POST com token CSRF (hoje é por link GET, como pede a atividade)
- [ ] Usar o campo `disponivel` (marcar item como indisponível sem excluir)
- [ ] Busca e filtro por categoria na tabela
- [ ] Upload de foto do item
- [ ] Cadastro e gerenciamento de vários usuários
- [ ] Paginação da listagem
- [ ] Revisão de responsividade para celular

## Tecnologias
PHP (procedural) · MySQL / MySQLi · HTML + CSS · SweetAlert2 (alertas)
