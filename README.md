# Counter

<p align="center">
  <img src="backend/public/images/logo-white.svg" alt="Counter" width="220">
</p>

<p align="center">
  <img alt="Laravel" src="https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white">
  <img alt="PHP" src="https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white">
  <img alt="MySQL" src="https://img.shields.io/badge/MySQL-Database-4479A1?logo=mysql&logoColor=white">
  <img alt="Android" src="https://img.shields.io/badge/Android-Kotlin-3DDC84?logo=android&logoColor=white">
  <img alt="Tailwind CSS" src="https://img.shields.io/badge/Tailwind-CSS-06B6D4?logo=tailwindcss&logoColor=white">
</p>

Counter é um sistema web e mobile para controle, movimentação e contagem de estoque.

A primeira versão do sistema está finalizada com interface web, API REST, banco de dados MySQL, arquitetura em camadas, Design Patterns documentados, dados de demonstração e aplicativo Android nativo integrado à API.

## Módulos Implementados

- Autenticação web
- Dashboard com indicadores e gráficos
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
- Relatórios em CSV
- Auditoria de ações
- Toast, modal de confirmação, paginação e loaders globais
- API REST para aplicativo mobile
- Aplicativo Android nativo
- Dados de demonstração

## Tecnologias

| Camada | Tecnologias |
| --- | --- |
| Back-end | PHP 8.2+, Laravel 12, Laravel Sanctum |
| Banco de dados | MySQL |
| Front-end web | Blade, Tailwind CSS, Alpine.js, Lucide Icons, Vite |
| Mobile | Android nativo, Kotlin, XML, Room Database, Retrofit, OkHttp |
| Qualidade | PHPUnit, Gradle, Git |

## Estrutura

```text
backend/   Aplicação Laravel, interface web e API REST
mobile/    Aplicativo Android nativo em Kotlin
docs/      Documentação técnica do projeto
```

## Documentação

- [README do backend](backend/README.md)
- [Documento técnico](docs/documento-tecnico.md)
- [Guia de desenvolvimento](docs/guia-desenvolvimento.md)
- [Design Patterns](docs/design-patterns.md)
- [API mobile](docs/api-mobile.md)
- [Aplicativo mobile](docs/mobile.md)
- [Ordem de desenvolvimento](docs/ordem-desenvolvimento.md)
- [Checklist de entrega](docs/checklist-entrega.md)

## Usuários de Demonstração

Todos usam a senha `password`.

| Perfil | E-mail | Acesso principal |
| --- | --- | --- |
| Administrador | `admin@counter.test` | Cadastros, contagens, divergências, relatórios, auditoria e aprovação de ajustes |
| Estoquista | `estoquista@counter.test` | Produtos e movimentações de estoque |
| Contador | `contador@counter.test` | Contagens web e sincronização de itens pelo mobile |

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

Suba a aplicação web:

```powershell
php artisan serve --host=0.0.0.0 --port=8000
npm run dev
```

Acesse:

```text
http://127.0.0.1:8000
```

## Mobile

O aplicativo Android está na pasta `mobile/`.

Para compilar:

```powershell
cd mobile
.\gradlew.bat assembleDebug
```

No emulador Android, a API local deve ser acessada por:

```text
http://10.0.2.2:8000
```

## Verificações

```powershell
cd backend
php artisan test
npm run build
composer audit
npm audit --audit-level=critical
git diff --check
```

```powershell
cd mobile
.\gradlew.bat assembleDebug
```
