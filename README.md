# 🔧 Mecânica Silva — Site + Painel Administrativo

Site institucional da **Mecânica Silva** com **formulário de contato funcional**, **API própria** e **painel administrativo** para gerenciamento dos dados.

Este trabalho contempla as tarefas de **Cosmo** (backend + painel administrativo) e **Vitória** (formulário funcional com validação de dados).

---

## ✨ O que foi implementado

### 📨 Formulário Funcional (Vitória)
- Página **`contato.html`** com formulário de contato/inscrição.
- **Validação dos dados** no lado do cliente (JavaScript) **e** no servidor (PHP):
  - Nome obrigatório (mínimo 3 caracteres);
  - E-mail obrigatório e com formato válido;
  - Telefone opcional (validado e com máscara automática);
  - Mensagem obrigatória (mínimo 10 caracteres).
- Mensagens de erro em tempo real e feedback de sucesso/erro.
- Os dados são enviados para a **API** e ficam salvos no banco de dados.

### ⚙️ Backend / API (Cosmo)
API REST simples em **PHP + PDO (SQLite)** que **recebe e consulta dados**.
O banco de dados e as tabelas são **criados automaticamente** no primeiro acesso.

| Método | Endpoint | Acesso | Descrição |
|--------|----------|--------|-----------|
| `POST` | `api/contato.php` | Público | Recebe um contato do formulário |
| `GET` | `api/contato.php` | Admin | Lista os contatos |
| `PUT` | `api/contato.php` | Admin | Atualiza o status de um contato |
| `DELETE` | `api/contato.php` | Admin | Remove um contato |
| `POST` | `api/agendamentos.php` | Público | Cria um agendamento |
| `GET` | `api/agendamentos.php` | Admin | Lista os agendamentos |
| `PUT` | `api/agendamentos.php` | Admin | Atualiza um agendamento |
| `DELETE` | `api/agendamentos.php` | Admin | Remove um agendamento |
| `GET` | `api/produtos.php` | Público | Lista os produtos |
| `POST` | `api/produtos.php` | Admin | Cadastra um produto |
| `PUT` | `api/produtos.php` | Admin | Atualiza um produto |
| `DELETE` | `api/produtos.php` | Admin | Remove um produto |
| `GET` | `api/stats.php` | Admin | Estatísticas do painel |

As respostas são sempre em **JSON**.

### 🖥️ Painel Administrativo (Cosmo)
Área restrita onde o administrador gerencia os dados:

- **`admin/login.php`** — autenticação (sessão + senha com hash).
- **`admin/index.php`** — dashboard com estatísticas e últimos registros.
- **`admin/contatos.php`** — visualizar, filtrar, marcar como lido/respondido e excluir contatos.
- **`admin/agendamentos.php`** — confirmar, cancelar e excluir agendamentos.
- **`admin/produtos.php`** — **CRUD completo** de produtos (criar, visualizar, editar e excluir).

**Acesso padrão:**

```
Usuário: admin
Senha:   admin123
```

> ⚠️ Troque a senha padrão após o primeiro acesso (ou edite `ADMIN_PADRAO_SENHA` em `api/config.php` antes de subir para produção).

---

## 📂 Estrutura

```
├── contato.html          # Formulário funcional (Vitória)
├── contato.css
├── contato.js
├── api/                  # Backend / API (Cosmo)
│   ├── config.php        # Configurações gerais
│   ├── db.php            # Conexão PDO + criação automática do banco
│   ├── helpers.php       # Funções auxiliares (JSON, validação, auth)
│   ├── contato.php       # Endpoint de contatos
│   ├── agendamentos.php  # Endpoint de agendamentos
│   ├── produtos.php      # Endpoint de produtos
│   ├── stats.php         # Endpoint de estatísticas
│   └── data/             # Banco de dados SQLite (gerado automaticamente)
├── admin/                # Painel administrativo (Cosmo)
│   ├── auth.php          # Sessão + layout
│   ├── login.php / logout.php
│   ├── index.php         # Dashboard
│   ├── contatos.php
│   ├── agendamentos.php
│   ├── produtos.php
│   ├── admin.css
│   └── admin.js
├── mecanica.html         # Página principal do site
├── index.php             # Loja virtual
└── ...
```

---

## ▶️ Como rodar localmente

Pré-requisitos: **PHP 8+** (com as extensões `pdo_sqlite` e `sqlite3`, já inclusas por padrão).

```bash
# Na raiz do projeto:
php -S localhost:8000
```

Depois acesse:

- Formulário de contato: <http://localhost:8000/contato.html>
- Painel administrativo: <http://localhost:8000/admin/login.php>
- API (exemplo): <http://localhost:8000/api/produtos.php>

---

## 🌐 Hospedagem

Como o projeto usa **PHP + SQLite**, ele roda em praticamente qualquer hospedagem com suporte a PHP:

1. **Hospedagem gratuita** (ex.: InfinityFree, 000webhost, AwardSpace) ou paga (Hostinger, HostGator, etc.).
2. Envie **todos os arquivos** para a pasta pública (`public_html` / `htdocs` / `www`).
3. Pronto! O banco de dados SQLite é criado automaticamente na pasta `api/data/`.
4. Garanta que a pasta `api/data/` tenha **permissão de escrita** pelo servidor.

> O projeto também pode ser adaptado para **MySQL**: basta alterar a conexão em `api/db.php` (a extensão `pdo_mysql` já é suportada).

---

## 🛡️ Segurança

- Consultas com **prepared statements** (PDO) — proteção contra SQL Injection.
- Senhas do administrador com **`password_hash` / `password_verify`**.
- Sessão de administrador com **`session_regenerate_id`**.
- Validação de dados no **cliente e no servidor**.
- Banco de dados protegido por `.htaccess` contra acesso direto.

---

## 👥 Divisão de tarefas

| Tarefa | Responsável |
|--------|-------------|
| Formulário funcional + validação | **Vitória** |
| Backend / API | **Cosmo** |
| Painel administrativo | **Cosmo** |
| Responsividade | (já existente) |
| JavaScript, Login, CRUD extra, Hospedagem | (demais integrantes) |

© 2026 Mecânica Silva — *Seu carro em boas mãos.*
