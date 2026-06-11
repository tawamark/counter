# Counter Backend

Backend web e API REST do Counter, sistema para controle, movimentação, contagem, auditoria e relatórios de estoque.

O backend concentra a aplicação web em Laravel, a API REST usada pelo futuro aplicativo mobile Android e as regras de negócio do estoque.

## Tecnologias

- PHP 8.2 ou superior
- Laravel 12
- MySQL 8
- Composer
- Node.js
- npm
- Vite
- Blade
- Tailwind CSS
- Alpine.js
- Lucide Icons
- Laravel Sanctum

## Documentação do Projeto

Consulte também:

- [`../docs/guia-desenvolvimento.md`](../docs/guia-desenvolvimento.md)
- [`../docs/ordem-desenvolvimento.md`](../docs/ordem-desenvolvimento.md)
- [`../docs/api-mobile.md`](../docs/api-mobile.md)
- [`../docs/design-patterns.md`](../docs/design-patterns.md)
- [`../docs/documento-tecnico.md`](../docs/documento-tecnico.md)
- [`../docs/checklist-entrega.md`](../docs/checklist-entrega.md)

Os arquivos `../AGENTS.md` e `../Documentação Projeto Prog lll.txt` são usados como referência local e não devem ser versionados.

## Módulos Implementados

- Autenticação web
- Dashboard com indicadores e gráficos
- Gestão de usuários e perfis
- Produtos
- Categorias
- Fornecedores
- Movimentações de estoque
- Contagens de estoque
- Itens de contagem
- Finalização e aprovação de contagens
- Ajustes automáticos após aprovação
- Divergências entre saldo do sistema e contagem física
- Relatórios CSV
- Auditoria administrativa
- API REST com Sanctum
- Seeders com dados de demonstração

## Perfis de Acesso

| Perfil | Permissões principais |
| --- | --- |
| Administrador | Dashboard, produtos, categorias, fornecedores, usuários, movimentações, contagens, divergências, relatórios, auditoria e aprovação de ajustes |
| Estoquista | Dashboard, produtos, movimentações e relatórios de estoque/movimentações |
| Contador | Dashboard, contagens e sincronização de itens pela API |

## Requisitos Locais

Confirme que as ferramentas estão disponíveis:

```powershell
php -v
composer --version
node -v
npm -v
```

Configuração local usada com MySQL do Laragon:

```text
host: 127.0.0.1
porta: 3306
banco: counter
usuário: root
senha: vazia
```

## Instalação

Instale as dependências:

```powershell
composer install
npm install
```

Crie o arquivo de ambiente:

```powershell
Copy-Item .env.example .env
```

Gere a chave da aplicação:

```powershell
php artisan key:generate
```

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

Limpe o cache e prepare o banco:

```powershell
php artisan config:clear
php artisan migrate --seed
```

## Usuários de Demonstração

Todos usam a senha `password`.

| Perfil | E-mail | Acesso principal |
| --- | --- | --- |
| Administrador | `admin@counter.test` | Cadastros, contagens, divergências, relatórios, auditoria e aprovação de ajustes |
| Estoquista | `estoquista@counter.test` | Produtos, movimentações e relatórios operacionais |
| Contador | `contador@counter.test` | Contagens e sincronização de itens contados |

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

## Rotas Web Principais

| Rota | Descrição | Perfis |
| --- | --- | --- |
| `/login` | Login web | Público |
| `/dashboard` | Indicadores, gráficos e atalhos | `admin`, `stockist`, `counter` |
| `/products` | Listagem de produtos | `admin`, `stockist` |
| `/products/create` | Cadastro de produtos | `admin` |
| `/categories` | Categorias | `admin` |
| `/suppliers` | Fornecedores | `admin` |
| `/users` | Usuários | `admin` |
| `/stock-movements` | Histórico e filtros de movimentações | `admin`, `stockist` |
| `/stock-movements/create` | Registro de entrada, saída ou ajuste | `admin`, `stockist` |
| `/inventory-counts` | Contagens | `admin`, `counter` |
| `/inventory-counts/create` | Criação de contagens | `admin` |
| `/divergences` | Divergências de contagem | `admin` |
| `/reports` | Relatórios CSV | `admin`, `stockist` |
| `/audit-logs` | Auditoria administrativa | `admin` |

## Relatórios CSV

| Rota | Descrição | Perfis |
| --- | --- | --- |
| `/reports/stock.csv` | Estoque atual com produto, SKU, categoria, fornecedor, unidade, quantidade e preços | `admin`, `stockist` |
| `/reports/movements.csv` | Movimentações com filtros por produto, tipo, usuário e período | `admin`, `stockist` |
| `/reports/divergences.csv` | Divergências por contagem, produto, saldo, quantidade contada e tipo | `admin` |

## Auditoria

A auditoria registra ações importantes com empresa, usuário, módulo, ação, descrição, IP, agente do navegador e metadados.

Módulos auditados:

- Categorias
- Fornecedores
- Produtos
- Usuários
- Movimentações
- Contagens

Ações auditadas:

- Criação
- Atualização
- Exclusão
- Registro de movimentação
- Alteração de itens de contagem
- Finalização de contagem
- Aprovação de contagem

## API REST

A API usa Laravel Sanctum com token Bearer. As rotas protegidas exigem:

```http
Authorization: Bearer token-gerado
Accept: application/json
```

Resposta de sucesso:

```json
{
  "success": true,
  "data": {},
  "message": "Operação realizada com sucesso"
}
```

Resposta de erro de validação:

```json
{
  "success": false,
  "message": "Erro de validação",
  "errors": {}
}
```

## Login API

```http
POST /api/login
```

Payload:

```json
{
  "email": "contador@counter.test",
  "password": "password"
}
```

Resposta:

```json
{
  "success": true,
  "data": {
    "token": "token-gerado",
    "user": {
      "id": 1,
      "name": "Contador",
      "email": "contador@counter.test",
      "role": "counter",
      "company": {
        "id": 1,
        "name": "Counter Demo"
      }
    }
  },
  "message": "Login realizado com sucesso"
}
```

## Rotas da API

### Autenticação

| Método | Rota | Perfil |
| --- | --- | --- |
| `POST` | `/api/login` | Público |
| `POST` | `/api/logout` | Autenticado |
| `GET` | `/api/me` | Autenticado |

### Produtos

| Método | Rota | Perfis |
| --- | --- | --- |
| `GET` | `/api/products?page=1&per_page=15&q=termo` | `admin`, `stockist`, `counter` |
| `GET` | `/api/products/search?q=termo&per_page=15` | `admin`, `stockist`, `counter` |
| `GET` | `/api/products/{id}` | `admin`, `stockist`, `counter` |

### Contagens

| Método | Rota | Perfis |
| --- | --- | --- |
| `GET` | `/api/mobile/summary` | `admin`, `counter` |
| `GET` | `/api/inventory-counts?status=open&per_page=15` | `admin`, `counter` |
| `GET` | `/api/inventory-counts/{id}` | `admin`, `counter` |
| `GET` | `/api/inventory-counts/{id}/items?sync_status=pending&per_page=50` | `admin`, `counter` |
| `POST` | `/api/inventory-counts/{id}/items` | `admin`, `counter` |
| `POST` | `/api/inventory-counts/{id}/sync` | `admin`, `counter` |

## Filtros da API

Produtos:

```text
q          nome, SKU ou código de barras
per_page   quantidade por página, de 1 a 100
page       página atual
```

Contagens:

```text
status     open, in_progress, finished ou approved
per_page   quantidade por página, de 1 a 100
page       página atual
```

Itens de contagem:

```text
sync_status   pending, synced ou error
per_page      quantidade por página, de 1 a 100
page          página atual
```

## Paginação

Listagens paginadas retornam `meta`:

```json
{
  "success": true,
  "data": [],
  "message": "Produtos encontrados com sucesso",
  "meta": {
    "current_page": 1,
    "last_page": 2,
    "per_page": 15,
    "total": 20
  }
}
```

## Resumo Mobile

```http
GET /api/mobile/summary
```

Resposta:

```json
{
  "success": true,
  "data": {
    "open_counts": 2,
    "pending_items": 4,
    "synced_items": 5,
    "counted_items": 5,
    "last_counted_at": "2026-06-10 16:00:00"
  },
  "message": "Resumo mobile encontrado com sucesso"
}
```

## Sincronizar Itens Contados

```http
POST /api/inventory-counts/{id}/sync
```

Payload:

```json
{
  "items": [
    {
      "id": 1,
      "counted_quantity": 7
    },
    {
      "id": 2,
      "counted_quantity": 18
    }
  ]
}
```

Resposta:

```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Contagem piloto",
    "status": "in_progress",
    "items_count": 5,
    "started_at": "2026-06-10T00:00:00.000000Z",
    "finished_at": null,
    "approved_at": null
  },
  "message": "Itens sincronizados com sucesso"
}
```

## Verificações Recomendadas

Após alterar código, rode:

```powershell
php artisan test
npm run build
composer audit
npm audit --audit-level=critical
git diff --check
```

Verifique possíveis problemas de encoding:

```powershell
rg -n "\x{00C3}[\x{0080}-\x{00BF}]|\x{00C2}[\x{0080}-\x{00BF}]|\x{FFFD}" . -g "!vendor/**" -g "!node_modules/**" -g "!public/build/**"
```

Verifique caracteres invisíveis:

```powershell
rg -n "[\x{200B}-\x{200F}\x{202A}-\x{202E}\x{2060}\x{FEFF}]" . -g "!vendor/**" -g "!node_modules/**" -g "!public/build/**"
```

## Estrutura de Implementação

A implementação segue a arquitetura orientada no guia de desenvolvimento:

```text
Controller
Service
Repository
Model
DTO
Migration
View/API Resource
Banco MySQL
```

Regras de negócio relevantes ficam em Services quando o fluxo passa de operações simples de CRUD.

## Observações

- O MySQL precisa estar ativo antes de executar migrations ou usar a aplicação.
- O arquivo `.env` não deve ser versionado.
- O arquivo `.env.example` deve manter valores seguros e reutilizáveis.
- Textos exibidos ao usuário devem estar em português do Brasil com acentuação correta.
- Após recriar o banco, rode `php artisan migrate --seed` para recuperar os dados demo.
