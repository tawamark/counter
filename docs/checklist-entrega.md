# Checklist de Entrega Acadêmica

## Código-Fonte

- [ ] Confirmar que o repositório GitHub está atualizado.
- [ ] Confirmar que `.env` não foi versionado.
- [ ] Confirmar que `AGENTS.md` não foi versionado.
- [ ] Confirmar que `Documentação Projeto Prog lll.txt` não foi versionado.
- [ ] Rodar testes antes da entrega.
- [ ] Rodar build antes da entrega.

## Documento Técnico

- [x] Nome do projeto.
- [x] Objetivo da solução.
- [x] Tecnologias utilizadas.
- [x] Arquitetura adotada.
- [x] Explicação dos Design Patterns.
- [x] Estrutura do banco.
- [x] Diagrama simplificado.
- [ ] Prints da aplicação.
- [x] Explicação da API.
- [x] Dificuldades encontradas.

## Prints Necessários

- [ ] Login.
- [ ] Dashboard.
- [ ] Produtos.
- [ ] Movimentações.
- [ ] Contagens.
- [ ] Divergências.
- [ ] Relatórios.
- [ ] Auditoria.
- [ ] API REST.

## Apresentação Final

- [ ] Apresentar o objetivo do Counter.
- [ ] Demonstrar login e perfis.
- [ ] Demonstrar dashboard.
- [ ] Demonstrar cadastro de produto.
- [ ] Demonstrar movimentação.
- [ ] Demonstrar contagem.
- [ ] Demonstrar divergência.
- [ ] Demonstrar aprovação de ajuste.
- [ ] Demonstrar relatório.
- [ ] Explicar API REST.
- [ ] Explicar arquitetura.
- [ ] Explicar Design Patterns.

## Design Patterns Para Citar

- [x] Service Layer.
- [x] Repository Pattern.
- [x] DTO.
- [x] Dependency Injection.
- [x] Facade.

## Verificações Finais

```powershell
php artisan test
npm run build
composer audit
npm audit --audit-level=critical
git diff --check
```

```powershell
rg -n "\x{00C3}[\x{0080}-\x{00BF}]|\x{00C2}[\x{0080}-\x{00BF}]|\x{FFFD}" . -g "!vendor/**" -g "!node_modules/**" -g "!public/build/**"
```

```powershell
rg -n "[\x{200B}-\x{200F}\x{202A}-\x{202E}\x{2060}\x{FEFF}]" . -g "!vendor/**" -g "!node_modules/**" -g "!public/build/**"
```
