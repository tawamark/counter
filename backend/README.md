# Counter Backend

Backend web e API REST do Counter, sistema para controle, movimentação e contagem de estoque.

O projeto usa Laravel como base da aplicação web, da API REST e das regras de negócio que integram o sistema web ao aplicativo mobile Android.

## Tecnologias

- PHP 8.2 ou superior
- Laravel 12
- MySQL 8
- Composer
- Node.js
- npm
- Vite
- Tailwind CSS
- Alpine.js
- Lucide Icons

## Documentação do Projeto

Consulte também:

- [`../docs/guia-desenvolvimento.md`](../docs/guia-desenvolvimento.md)
- [`../AGENTS.md`](../AGENTS.md)
- [`../Documentação Projeto Prog lll.txt`](../Documentação%20Projeto%20Prog%20lll.txt)

## Requisitos Locais

Antes de rodar o backend, confirme que estes serviços e ferramentas estão disponíveis:

```powershell
php -v
composer --version
node -v
npm -v
```

O projeto foi configurado localmente com MySQL do Laragon:

```text
host: 127.0.0.1
porta: 3306
banco: counter
usuário: root
senha: vazia
```

## Instalação

Instale as dependências PHP:

```powershell
composer install
```

Instale as dependências front-end:

```powershell
npm install
```

Crie o arquivo de ambiente se ele ainda não existir:

```powershell
Copy-Item .env.example .env
```

Gere a chave da aplicação:

```powershell
php artisan key:generate
```

## Configuração do Banco

Crie o banco no MySQL:

```sql
CREATE DATABASE IF NOT EXISTS counter CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Configure o `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=counter
DB_USERNAME=root
DB_PASSWORD=
```

Limpe o cache de configuração:

```powershell
php artisan config:clear
```

Execute as migrations:

```powershell
php artisan migrate
```

## Executando o Projeto

Suba o servidor Laravel:

```powershell
php artisan serve
```

Em outro terminal, rode o Vite:

```powershell
npm run dev
```

Acesse:

```text
http://127.0.0.1:8000
```

## Build

Para gerar os assets de produção:

```powershell
npm run build
```

## Testes

Para executar os testes:

```powershell
php artisan test
```

Ou pelo script do Composer:

```powershell
composer test
```

## Verificações Recomendadas

Após alterar código, rode:

```powershell
php artisan test
npm run build
npm audit --audit-level=critical
git diff --check
```

Verifique também possíveis problemas de encoding:

```powershell
rg -n "\x{00C3}[\x{0080}-\x{00BF}]|\x{00C2}[\x{0080}-\x{00BF}]|\x{FFFD}" . -g "!vendor/**" -g "!node_modules/**" -g "!public/build/**"
```

## Estrutura Esperada

A implementação deve seguir a arquitetura definida no guia de desenvolvimento:

```text
Controller
Service
Repository
Model
Banco MySQL
```

As principais áreas previstas são:

- Autenticação
- Dashboard
- Produtos
- Categorias
- Fornecedores
- Movimentações de estoque
- Histórico
- Contagens de estoque
- Divergências
- API REST para o aplicativo mobile

## Observações

- O MySQL precisa estar ativo antes de executar migrations ou usar a aplicação.
- O arquivo `.env` não deve ser versionado.
- O arquivo `.env.example` deve manter valores seguros e reutilizáveis.
- Textos exibidos ao usuário devem estar em português do Brasil com acentuação correta.
