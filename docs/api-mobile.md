# API Mobile Counter

Contrato da API REST usada pelo aplicativo mobile.

## Base

- Prefixo das rotas: `/api`
- Autenticação: Bearer Token via Laravel Sanctum
- Header obrigatório após login: `Authorization: Bearer {token}`
- Formato: JSON

## Resposta de sucesso

```json
{
  "success": true,
  "data": {},
  "message": "Operação realizada com sucesso"
}
```

## Resposta paginada

```json
{
  "success": true,
  "data": [],
  "message": "Registros encontrados com sucesso",
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 15,
    "total": 35
  }
}
```

## Resposta de erro

```json
{
  "success": false,
  "message": "Erro de validação",
  "errors": {}
}
```

## Autenticação

### Login

`POST /api/login`

Body:

```json
{
  "email": "admin@counter.test",
  "password": "password"
}
```

Resposta:

```json
{
  "success": true,
  "data": {
    "token": "token",
    "user": {
      "id": 1,
      "name": "Administrador",
      "email": "admin@counter.test",
      "role": "admin",
      "company": {
        "id": 1,
        "name": "Counter Demo"
      }
    }
  },
  "message": "Login realizado com sucesso"
}
```

### Usuário autenticado

`GET /api/me`

### Logout

`POST /api/logout`

## Produtos

### Listar produtos

`GET /api/products`

Query:

- `q`: busca por nome, SKU ou código de barras.
- `per_page`: quantidade por página, entre 1 e 100.

### Buscar produtos

`GET /api/products/search`

Aceita os mesmos parâmetros de `/api/products`.

### Detalhar produto

`GET /api/products/{id}`

Campos principais retornados:

- `id`
- `name`
- `description`
- `sku`
- `barcode`
- `unit`
- `cost_price`
- `sale_price`
- `current_quantity`
- `category`
- `supplier`

## Contagens

### Listar contagens

`GET /api/inventory-counts`

Query:

- `status`: `open`, `in_progress`, `finished` ou `approved`.
- `per_page`: quantidade por página, entre 1 e 100.

Quando `status` não é informado, a API retorna contagens `open` e `in_progress`.

### Detalhar contagem

`GET /api/inventory-counts/{id}`

Campos principais retornados:

- `id`
- `title`
- `status`
- `items_count`
- `started_at`
- `finished_at`
- `approved_at`

### Listar itens da contagem

`GET /api/inventory-counts/{id}/items`

Query:

- `sync_status`: `pending`, `synced` ou `error`.
- `per_page`: quantidade por página, entre 1 e 100.

Campos principais retornados:

- `id`
- `product`
- `system_quantity`
- `counted_quantity`
- `difference`
- `sync_status`
- `counted_at`

### Enviar itens contados

`POST /api/inventory-counts/{id}/items`

Body:

```json
{
  "items": [
    {
      "id": 1,
      "counted_quantity": 12.5
    }
  ]
}
```

### Sincronizar itens contados

`POST /api/inventory-counts/{id}/sync`

Aceita o mesmo body de `/api/inventory-counts/{id}/items`.

## Resumo mobile

`GET /api/mobile/summary`

Retorna:

- `open_counts`
- `pending_items`
- `synced_items`
- `counted_items`
- `last_counted_at`

## Códigos de erro esperados

- `401`: usuário não autenticado.
- `403`: perfil sem permissão.
- `404`: recurso não encontrado ou pertence a outra empresa.
- `422`: erro de validação.
