# 📦 Sistema de Achados e Perdidos - UTFPR

Plataforma web desenvolvida para otimizar o gerenciamento de itens perdidos e encontrados no ambiente acadêmico da UTFPR, facilitando a devolução de pertences aos seus donos por meio de um fluxo automatizado de notificações e controle de acesso por níveis de permissão.

## 🚀 Tecnologias Utilizadas

* **Backend:** PHP 8.3+ / Laravel 13+

* **Banco de Dados:** MySQL / MariaDB

* **Frontend:** Blade Templates, Bootstrap 5, FontAwesome

* **Ambiente:** Docker & Docker Compose

## ⚙️ Pré-requisitos

Certifique-se de ter as seguintes ferramentas instaladas em sua máquina:

* [Docker](https://www.docker.com/) e [Docker Compose](https://docs.docker.com/compose/)

* Git

## 🛠️ Passo a Passo Completo para Configuração e Execução

Siga os passos abaixo rigorosamente para configurar o ambiente do zero, incluindo a base de dados:

### 1. Clonar o Repositório

Abra o seu terminal e clone o projeto para a sua máquina:

```
git clone https://github.com/cassigui/sistema-achados.git
cd sistema-achados

```

### 2. Configurar o Arquivo de Ambiente (`.env`)

Copie o arquivo de exemplo de ambiente para criar o seu arquivo `.env` oficial:

```
cp .env.example .env

```

Abra o arquivo `.env` gerado e certifique-se de que as configurações de conexão com o banco de dados estão alinhadas com o seu container Docker (geralmente vêm configuradas por padrão):

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=sistema_achados
DB_USERNAME=root
DB_PASSWORD=root

```

### 3. Subir os Containers com o Docker

Inicie os serviços do projeto em segundo plano utilizando o Docker Compose:

```
docker compose up -d --build ou docker-compose up -d --build

```

*(Aguarde alguns segundos até que os containers do PHP/Nginx e do Banco de Dados estejam totalmente inicializados).*

### 4. Configurar e Popular a Base de Dados (Migrate & Seed)

Para criar a estrutura de tabelas no banco de dados e logo em seguida preenchê-la com os usuários padrão e os itens iniciais de teste, execute o comando de refresh com seed:

```
docker exec sistema_achados_app php artisan migrate:fresh --seed

```

Este comando irá:

1. Apagar e recriar todas as tabelas do banco de dados (Migrations).

2. Executar o `DatabaseSeeder`, populando a base com o Administrador, o Usuário Comum e os 5 itens de exemplo.

## 🔑 Credenciais Padrão para Testes

Após rodar os seeders, você poderá aceder ao sistema utilizando as seguintes contas:

### 👑 Administrador (Acesso Total & Notificações)

* **E-mail:** `admin@utfpr.br`

* **Senha:** `SenhaTeste123!`

### 👤 Usuário Comum (Gestão de Itens Próprios)

* **E-mail:** `usuario@utfpr.br`

* **Senha:** `SenhaTeste123!`

## 🌐 Acesso à Aplicação

Com tudo configurado e rodando, abra o seu navegador e acesse:

```
http://localhost:8300

```