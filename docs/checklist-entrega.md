# Checklist de Entrega Acadêmica

## Código-Fonte

- [x] Repositório GitHub atualizado.
- [x] `.env` fora do versionamento.
- [x] `AGENTS.md` fora do versionamento.
- [x] `Documentação Projeto Prog lll.txt` fora do versionamento.
- [x] `COUNTER_Documentacao_Projeto_Programacao_III_2026.md` fora do versionamento.
- [x] Backend Laravel implementado.
- [x] Interface web implementada.
- [x] API REST implementada.
- [x] Aplicativo Android implementado.
- [x] Seeders de demonstração implementados.
- [x] Testes do backend implementados.
- [x] Build do mobile validado.

## Documento Técnico

- [x] Nome do projeto.
- [x] Objetivo da solução.
- [x] Tecnologias utilizadas.
- [x] Arquitetura adotada.
- [x] Explicação dos Design Patterns.
- [x] Estrutura do banco.
- [x] Diagrama simplificado.
- [x] Explicação da API.
- [x] Explicação do aplicativo mobile.
- [x] Dificuldades encontradas.
- [ ] Prints finais da aplicação.

## Prints Necessários

- [ ] Login web.
- [ ] Dashboard.
- [ ] Produtos.
- [ ] Categorias.
- [ ] Fornecedores.
- [ ] Movimentações.
- [ ] Contagens.
- [ ] Itens da contagem.
- [ ] Divergências.
- [ ] Relatórios.
- [ ] Auditoria.
- [ ] API REST.
- [ ] Mobile - Login.
- [ ] Mobile - Resumo e contagens.
- [ ] Mobile - Itens da contagem.
- [ ] Mobile - Busca e sincronização.

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
- [ ] Demonstrar auditoria.
- [ ] Demonstrar aplicativo Android.
- [ ] Explicar API REST.
- [ ] Explicar arquitetura.
- [ ] Explicar Design Patterns.
- [ ] Explicar banco de dados.

## Design Patterns Para Citar

- [x] Service Layer.
- [x] Repository Pattern.
- [x] DTO.
- [x] Dependency Injection.
- [x] Facade.
- [x] Repository no mobile.

## Verificações Finais

Backend:

```powershell
cd backend
php artisan test
npm run build
composer audit
npm audit --audit-level=critical
git diff --check
```

Mobile:

```powershell
cd mobile
.\gradlew.bat assembleDebug
```

Encoding e caracteres invisíveis:

```powershell
rg -n "\x{00C3}[\x{0080}-\x{00BF}]|\x{00C2}[\x{0080}-\x{00BF}]|\x{FFFD}" . -g "!vendor/**" -g "!node_modules/**" -g "!public/build/**" -g "!mobile/app/build/**"
```

```powershell
rg -n "[\x{200B}-\x{200F}\x{202A}-\x{202E}\x{2060}\x{FEFF}]" . -g "!vendor/**" -g "!node_modules/**" -g "!public/build/**" -g "!mobile/app/build/**"
```
