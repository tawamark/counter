# Counter - Guia de Desenvolvimento

Este guia resume a documentacao original do projeto Counter em um formato direto para orientar a implementacao do sistema.

## 1. Visao Geral

O Counter e um sistema web e mobile para controle, movimentacao e contagem de estoque.

A aplicacao web sera usada para administrar produtos, categorias, fornecedores, movimentacoes, contagens e divergencias. O aplicativo mobile sera usado principalmente pelo contador para registrar a quantidade fisica encontrada no estoque.

## 2. Tecnologias

- Back-end: PHP com Laravel
- Banco de dados: MySQL
- Front-end web: Laravel Blade, HTML, Tailwind CSS, Alpine.js e Lucide Icons
- API: REST com respostas em JSON
- Mobile: Android nativo com Kotlin
- Banco local mobile: Room Database
- Versionamento: Git e GitHub

## 3. Perfis de Usuario

### Administrador

Responsavel pelo gerenciamento geral do sistema.

Permissoes principais:

- Gerenciar usuarios
- Gerenciar produtos
- Gerenciar categorias
- Gerenciar fornecedores
- Criar contagens de estoque
- Visualizar divergencias
- Aprovar ajustes de estoque
- Acompanhar dashboard e historico

### Estoquista

Responsavel pelas operacoes diarias de estoque.

Permissoes principais:

- Consultar produtos
- Registrar entradas de estoque
- Registrar saidas de estoque
- Consultar historico de movimentacoes

### Contador

Responsavel pela contagem fisica dos produtos.

Permissoes principais:

- Acessar contagens abertas
- Localizar produtos por nome, SKU ou codigo de barras
- Informar quantidade fisica encontrada
- Enviar itens contados para a API

## 4. Modulos do Sistema Web

### Autenticacao

Controla o acesso dos usuarios ao sistema.

Requisitos:

- Login com email e senha
- Usuario autenticado em todas as telas internas
- Registro do usuario responsavel por movimentacoes e contagens
- Separacao de permissoes por perfil

### Dashboard

Tela inicial apos o login.

Indicadores previstos:

- Total de produtos
- Total de categorias
- Total de fornecedores
- Ultimas movimentacoes
- Contagens abertas ou em andamento
- Produtos com divergencia em contagens recentes

### Produtos

Cadastro e manutencao dos produtos do estoque.

Campos principais:

- Nome
- Descricao
- SKU
- Codigo de barras
- Unidade de medida
- Preco de custo
- Preco de venda
- Quantidade atual
- Categoria
- Fornecedor
- Empresa

Regras:

- Cada produto pertence a uma empresa.
- Cada produto pode estar vinculado a uma categoria.
- Cada produto pode estar vinculado a um fornecedor.
- SKU e codigo de barras devem ser usados para localizar produtos.
- A quantidade atual deve ser alterada por movimentacoes ou ajustes aprovados, nao diretamente sem controle.

### Categorias

Organizam os produtos em grupos.

Campos principais:

- Nome
- Descricao
- Empresa

Regras:

- Uma categoria pertence a uma empresa.
- Uma categoria pode possuir varios produtos.

### Fornecedores

Armazenam os fornecedores relacionados aos produtos.

Campos principais:

- Nome
- CNPJ
- Telefone
- Email
- Endereco
- Empresa

Regras:

- Um fornecedor pertence a uma empresa.
- Um fornecedor pode possuir varios produtos.

### Movimentacoes de Estoque

Registram entradas, saidas e ajustes.

Campos principais:

- Produto
- Usuario responsavel
- Tipo: entrada, saida ou ajuste
- Quantidade
- Motivo
- Data da movimentacao

Regras:

- Entrada aumenta a quantidade atual do produto.
- Saida diminui a quantidade atual do produto.
- Ajuste altera o estoque apos aprovacao de divergencia.
- Toda movimentacao deve ficar registrada no historico.
- Nao permitir saida maior que o saldo disponivel, salvo regra explicita futura.

### Historico de Movimentacoes

Lista todas as movimentacoes realizadas.

Filtros uteis:

- Produto
- Tipo de movimentacao
- Usuario
- Periodo

### Contagem de Estoque

Permite criar processos de conferencia fisica do estoque.

Campos principais da contagem:

- Titulo
- Empresa
- Status: aberta, em andamento, finalizada ou aprovada
- Usuario criador
- Data de criacao
- Data de finalizacao

Campos principais dos itens da contagem:

- Contagem
- Produto
- Quantidade registrada no sistema
- Quantidade fisica contada
- Diferenca
- Status de sincronizacao

Regras:

- O administrador cria uma contagem.
- A contagem inicia como aberta.
- O contador acessa a contagem pelo mobile.
- O contador informa as quantidades fisicas.
- O sistema compara quantidade registrada e quantidade contada.
- Divergencias devem ser analisadas antes de qualquer ajuste no estoque.
- O estoque so deve ser atualizado apos aprovacao do administrador.

### Divergencias

Mostram diferencas entre estoque registrado e estoque contado.

Calculo:

```text
diferenca = quantidade_contada - quantidade_sistema
```

Regras:

- Se a diferenca for zero, nao ha divergencia.
- Se a diferenca for positiva, existe sobra fisica.
- Se a diferenca for negativa, existe falta fisica.
- O administrador decide se aprova o ajuste.

## 5. Arquitetura Laravel

O projeto deve seguir MVC com camadas auxiliares.

Fluxo principal:

```text
View Blade
Controller
Service
Repository
Model
Banco MySQL
```

Responsabilidades:

- Controller: recebe requisicoes, valida entrada inicial e chama services.
- Service: concentra regras de negocio.
- Repository: isola consultas e persistencia.
- Model: representa entidades e relacionamentos.
- DTO: transporta dados estruturados entre camadas quando fizer sentido.
- Observer: executa acoes automaticas em eventos importantes, como finalizacao de contagem.

## 6. Estrutura Sugerida do Banco

Tabelas principais:

- companies
- users
- categories
- suppliers
- products
- stock_movements
- inventory_counts
- inventory_count_items

Relacionamentos:

- Uma empresa possui varios usuarios.
- Uma empresa possui varias categorias.
- Uma empresa possui varios fornecedores.
- Uma empresa possui varios produtos.
- Uma categoria possui varios produtos.
- Um fornecedor possui varios produtos.
- Um produto possui varias movimentacoes.
- Um usuario registra varias movimentacoes.
- Uma empresa possui varias contagens.
- Uma contagem possui varios itens.
- Um produto pode aparecer em varios itens de contagem.

## 7. API REST

A API sera usada pelo aplicativo mobile.

Rotas esperadas:

```text
POST   /api/login
POST   /api/logout
GET    /api/me

GET    /api/products
GET    /api/products/{id}
GET    /api/products/search

GET    /api/inventory-counts
GET    /api/inventory-counts/{id}
GET    /api/inventory-counts/{id}/items
POST   /api/inventory-counts/{id}/items
POST   /api/inventory-counts/{id}/sync
```

Padrao de resposta:

```json
{
  "success": true,
  "data": {},
  "message": "Operacao realizada com sucesso"
}
```

Padrao de erro:

```json
{
  "success": false,
  "message": "Erro de validacao",
  "errors": {}
}
```

## 8. Aplicativo Mobile

Objetivo principal: registrar contagens fisicas de estoque.

Fluxo:

1. Usuario faz login no aplicativo.
2. Aplicativo consulta contagens abertas na API.
3. Usuario seleciona uma contagem.
4. Aplicativo lista produtos da contagem.
5. Usuario localiza produto por nome, SKU ou codigo de barras.
6. Usuario informa quantidade fisica encontrada.
7. Aplicativo salva localmente com Room Database.
8. Aplicativo envia dados para a API.
9. Sistema web processa divergencias.

Arquitetura mobile:

```text
Activity
ViewModel
Repository
DAO
Room Database
```

## 9. Design Patterns

### Repository Pattern

Usado para separar regras de acesso a dados da logica de negocio.

Exemplos:

- ProductRepository
- CategoryRepository
- SupplierRepository
- StockMovementRepository
- InventoryCountRepository

### Service Layer

Usado para concentrar regras de negocio.

Exemplos:

- ProductService
- StockMovementService
- InventoryCountService
- DivergenceService

### DTO

Usado para transportar dados entre camadas quando o array ou request direto ficaria confuso.

Exemplos:

- ProductData
- StockMovementData
- InventoryCountItemData

### Observer

Usado para reagir a eventos do sistema.

Exemplo:

- Ao finalizar uma contagem, gerar divergencias automaticamente.

## 10. Checklist Inicial de Implementacao

1. Criar projeto Laravel.
2. Configurar banco MySQL.
3. Criar autenticacao.
4. Criar migrations principais.
5. Criar models e relacionamentos.
6. Criar seeders basicos.
7. Implementar produtos.
8. Implementar categorias.
9. Implementar fornecedores.
10. Implementar movimentacoes.
11. Implementar dashboard.
12. Implementar contagens.
13. Implementar divergencias.
14. Implementar aprovacao de ajustes.
15. Criar API REST para o mobile.
16. Criar aplicativo Android em Kotlin.
17. Integrar mobile com API.
18. Inserir prints finais na documentacao academica.

## 11. Fora do Escopo Inicial

Funcionalidades que nao precisam ser implementadas na primeira versao:

- Alerta de estoque minimo
- Emissao de notas fiscais
- Integracao com sistemas externos de venda
- Controle financeiro completo
- Gestao de compras
