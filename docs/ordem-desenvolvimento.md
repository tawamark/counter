# Ordem de Desenvolvimento do Counter

## 1. Base do banco

- Criar migrations principais.
- Criar tabela `companies`.
- Ajustar tabela `users`.
- Criar tabela `categories`.
- Criar tabela `suppliers`.
- Criar tabela `products`.
- Criar tabela `stock_movements`.
- Criar tabela `inventory_counts`.
- Criar tabela `inventory_count_items`.

## 2. Models e relacionamentos

- Definir relações entre empresa e usuários.
- Definir relações entre empresa, produtos, categorias e fornecedores.
- Definir relações entre produtos e movimentações.
- Definir relações entre contagens e itens contados.

## 3. Autenticação web

- Implementar login.
- Implementar logout.
- Proteger rotas internas.
- Identificar usuário autenticado nas operações.

## 4. Perfis de usuário

- Criar perfis `admin`, `stockist` e `counter`.
- Aplicar regras básicas de acesso.
- Restringir funcionalidades conforme perfil.

## 5. Layout base web

- Criar layout interno padrão.
- Criar sidebar ou menu principal.
- Criar topbar.
- Configurar componentes visuais com Tailwind CSS, Alpine.js e Lucide Icons.

## 6. Dashboard

- Exibir total de produtos.
- Exibir total de categorias.
- Exibir total de fornecedores.
- Exibir últimas movimentações.
- Exibir contagens abertas ou em andamento.

## 7. Categorias

- Listar categorias.
- Cadastrar categorias.
- Editar categorias.
- Excluir categorias.

## 8. Fornecedores

- Listar fornecedores.
- Cadastrar fornecedores.
- Editar fornecedores.
- Excluir fornecedores.

## 9. Produtos

- Listar produtos.
- Cadastrar produtos.
- Editar produtos.
- Excluir produtos.
- Controlar nome, SKU, código de barras, categoria, fornecedor, preços e quantidade atual.

## 10. Movimentações de estoque

- Registrar entradas.
- Registrar saídas.
- Registrar ajustes.
- Atualizar quantidade do produto de forma controlada.
- Impedir saídas inválidas quando não houver saldo suficiente.

## 11. Histórico de movimentações

- Listar movimentações.
- Filtrar por produto.
- Filtrar por tipo.
- Filtrar por usuário.
- Filtrar por período.

## 12. Contagem de estoque

- Criar contagens.
- Selecionar produtos da contagem.
- Controlar status da contagem.
- Permitir status aberta, em andamento, finalizada e aprovada.

## 13. Itens da contagem

- Registrar quantidade do sistema.
- Registrar quantidade contada.
- Calcular diferença entre quantidade contada e quantidade registrada.

## 14. Divergências

- Listar produtos com diferença.
- Mostrar sobra física.
- Mostrar falta física.
- Separar itens sem divergência dos itens divergentes.

## 15. Aprovação de ajustes

- Permitir que o administrador aprove ajustes.
- Gerar movimentação de ajuste.
- Atualizar quantidade atual do produto.
- Manter rastreabilidade da aprovação.

## 16. API REST

- Criar login para o aplicativo mobile.
- Criar rota para listar produtos.
- Criar rota para listar contagens abertas.
- Criar rota para listar itens de contagem.
- Criar rota para enviar itens contados.
- Criar rota para sincronização.

## 17. Seeders

- Criar empresa padrão.
- Criar usuário administrador.
- Criar categorias de exemplo.
- Criar fornecedores de exemplo.
- Criar produtos de exemplo.

## 18. Refino visual

- Melhorar telas principais.
- Ajustar responsividade.
- Revisar textos em português.
- Criar estados vazios.
- Criar mensagens de erro e sucesso.

## 19. Testes

- Criar testes de models.
- Criar testes de services.
- Criar testes de rotas principais.
- Criar testes das regras de movimentação de estoque.
- Criar testes das regras de contagem e divergência.

## 20. Aplicativo mobile

- Iniciar após a API estar estável.
- Implementar login.
- Listar contagens abertas.
- Listar produtos da contagem.
- Registrar quantidades contadas.
- Salvar dados locais com Room Database.
- Sincronizar dados com a API REST.

## Próximo passo recomendado

Começar pela base do banco e pelos models, porque essas partes definem a estrutura central do sistema.
