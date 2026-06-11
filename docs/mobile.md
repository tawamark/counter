# Aplicativo Mobile Counter

O aplicativo mobile do Counter foi criado em Android nativo com Kotlin, seguindo o padrão apresentado em aula.

## Objetivo

Permitir que o perfil Contador acesse contagens de estoque, registre quantidades físicas no celular e sincronize os itens contados com a API REST do Laravel.

## Tecnologias

- Android nativo
- Kotlin
- XML com ViewBinding
- AppCompatActivity
- ViewModel
- Repository
- Room Database
- DAO
- Retrofit
- OkHttp
- RecyclerView
- Gradle Kotlin DSL
- KSP

## Arquitetura

```text
Activity
ViewModel
Repository
API REST / DAO
Room Database
```

Responsabilidades:

- Activity: interface e interação com o usuário.
- ViewModel: estado da tela e chamadas assíncronas.
- Repository: acesso à API e ao banco local.
- DAO: operações no banco local.
- Room Database: armazenamento local das contagens e itens.
- Retrofit: comunicação com a API REST do Laravel.

## Telas

- Login.
- Lista de contagens.
- Resumo mobile.
- Itens da contagem.
- Registro local da quantidade contada.
- Sincronização de itens contados.

## API Consumida

Base URL padrão para emulador Android:

```text
http://10.0.2.2:8000/api/
```

Rotas utilizadas:

```text
POST /api/login
POST /api/logout
GET  /api/mobile/summary
GET  /api/inventory-counts
GET  /api/inventory-counts/{id}/items
POST /api/inventory-counts/{id}/sync
```

## Como Compilar

Com Android SDK e JDK 17 configurados:

```powershell
cd mobile
.\gradlew.bat assembleDebug
```

O APK debug é gerado em:

```text
mobile/app/build/outputs/apk/debug/app-debug.apk
```

## Como Testar no Emulador

1. Subir o Laravel:

```powershell
cd backend
php artisan serve --host=0.0.0.0 --port=8000
```

2. Garantir que o banco MySQL esteja ativo.

3. Instalar o APK no emulador:

```powershell
adb install -r mobile/app/build/outputs/apk/debug/app-debug.apk
```

4. Entrar com usuário contador:

```text
contador@counter.test
password
```

## Observações

- O emulador Android acessa o servidor local pelo endereço `10.0.2.2`.
- Para testar em celular físico, a base URL deve apontar para o IP da máquina na rede local.
- Os itens contados são salvos localmente no Room antes da sincronização.
