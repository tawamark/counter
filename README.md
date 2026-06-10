# Counter

Counter é um sistema web e mobile para controle, movimentação e contagem de estoque.

O projeto está em desenvolvimento como uma solução para administrar produtos, categorias, fornecedores, movimentações, contagens, divergências e sincronização com aplicativo mobile.

## Módulos Implementados

- Autenticação web
- Dashboard
- Perfis de usuário
- Produtos
- Categorias
- Fornecedores
- Movimentações de estoque
- Histórico de movimentações
- Contagens de estoque
- Itens de contagem
- Divergências
- Aprovação de ajustes
- API REST para aplicativo mobile
- Dados de demonstração

## Tecnologias

- PHP 8.2+
- Laravel 12
- MySQL
- Blade
- Tailwind CSS
- Alpine.js
- Lucide Icons
- Laravel Sanctum
- Vite

## Estrutura

```text
backend/   Aplicação Laravel, interface web e API REST
docs/      Documentação técnica e ordem de desenvolvimento
```

## Documentação

- [README do backend](backend/README.md)
- [Guia de desenvolvimento](docs/guia-desenvolvimento.md)
- [Ordem de desenvolvimento](docs/ordem-desenvolvimento.md)

## Usuários de Demonstração

Todos usam a senha `password`.

| Perfil | E-mail | Acesso principal |
| --- | --- | --- |
| Administrador | `admin@counter.test` | Cadastros, contagens, divergências e aprovação de ajustes |
| Estoquista | `estoquista@counter.test` | Produtos e movimentações de estoque |
| Contador | `contador@counter.test` | Contagens e sincronização de itens contados |

## Como Rodar

Entre na pasta do backend:

```powershell
cd backend
```

Instale as dependências:

```powershell
composer install
npm install
```

Configure o ambiente:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Configure o banco `counter` no MySQL e rode:

```powershell
php artisan migrate
php artisan db:seed
```

Suba a aplicação:

```powershell
php artisan serve
npm run dev
```

Acesse:

```text
http://127.0.0.1:8000
```

## Verificações

```powershell
php artisan test
npm run build
composer audit
npm audit --audit-level=critical
git diff --check
```
