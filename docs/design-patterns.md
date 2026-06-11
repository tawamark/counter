# Design Patterns do Projeto Counter

Este documento registra os principais padrões aplicados no sistema Counter para atender à exigência acadêmica de arquitetura organizada e uso explícito de Design Patterns.

## Arquitetura Adotada

O backend usa MVC com camadas auxiliares:

```text
Route
Controller
Form Request
DTO
Service
Repository
Model
Banco MySQL
```

Responsabilidades:

- Controller: recebe a requisição, direciona o fluxo e escolhe a resposta.
- Form Request: valida os dados de entrada.
- DTO: transporta dados validados entre camadas com estrutura clara.
- Service: concentra regras de negócio.
- Repository: concentra consultas e persistência.
- Model: representa entidades, relacionamentos e acesso ORM.
- View/API Response: entrega interface Blade ou JSON padronizado.

## 1. Service Layer

Onde foi usado:

- `app/Services/StockMovementService.php`
- `app/Services/InventoryCountService.php`
- `app/Services/AuditLogService.php`

Por que foi usado:

- Movimentações de estoque alteram saldo, validam saída maior que o estoque e registram auditoria.
- Contagens possuem regras de criação, atualização de itens, finalização e aprovação.
- Auditoria registra operações importantes de forma centralizada.

Benefício:

- Evita regra de negócio espalhada nos controllers.
- Facilita testes e manutenção.
- Deixa o controller responsável apenas por orquestrar a requisição.

## 2. Repository Pattern

Onde foi usado:

- `app/Repositories/ProductRepository.php`
- `app/Repositories/StockMovementRepository.php`
- `app/Repositories/InventoryCountRepository.php`

Por que foi usado:

- Produtos, movimentações e contagens possuem consultas reutilizadas em telas web e API.
- Os filtros de listagem e buscas deixam de ficar repetidos diretamente nos controllers.

Benefício:

- Isola acesso a dados.
- Reduz duplicação.
- Deixa mais fácil trocar ou ajustar consultas sem mexer no fluxo de apresentação.

## 3. DTO

Onde foi usado:

- `app/DTOs/ProductData.php`
- `app/DTOs/StockMovementData.php`
- `app/DTOs/InventoryCountData.php`

Por que foi usado:

- Dados vindos dos formulários são convertidos para objetos com campos claros antes de chegar em repositories ou services.
- Evita passar arrays genéricos de request entre camadas.

Benefício:

- Mais clareza sobre quais dados cada operação precisa.
- Menos acoplamento entre request HTTP e regra de negócio.
- Conversão de tipos centralizada.

## 4. Dependency Injection

Onde foi usado:

- Controllers recebem repositories e services por parâmetro.
- `InventoryCountService` recebe `AuditLogService` e `InventoryCountRepository` pelo construtor.
- `StockMovementService` recebe `AuditLogService` pelo construtor.

Por que foi usado:

- O Laravel resolve dependências automaticamente pelo container.
- As classes deixam de criar suas próprias dependências manualmente.

Benefício:

- Código mais testável.
- Menor acoplamento.
- Melhor separação de responsabilidades.

## 5. Facade

Onde foi usado:

- `DB::transaction()` nos services.

Por que foi usado:

- Operações críticas precisam ser atômicas, como registrar movimentação e alterar saldo.
- Criação e aprovação de contagens também precisam manter consistência.

Benefício:

- Se uma etapa falhar, toda a operação é revertida.
- Mantém integridade do estoque e das contagens.

## Resumo Para Apresentação

O projeto usa mais de três Design Patterns exigidos:

- Service Layer para regra de negócio.
- Repository Pattern para acesso a dados.
- DTO para transporte estruturado de dados.
- Dependency Injection para desacoplamento.
- Facade para transações de banco.

Esses padrões demonstram arquitetura em camadas, organização de responsabilidades e manutenção mais simples do código.
