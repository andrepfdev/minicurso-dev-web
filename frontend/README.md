# Frontend — Vue 3 + Vite

Tela de login + listagem/cadastro de usuários, consumindo a API do `backend/`.

Este projeto já está montado e instalado (`npm create vue@latest .` rodado, sem TypeScript/Router/
Pinia/testes, `node_modules/` presente). Para rodar:

```bash
npm run dev
```

Abra o endereço que o Vite mostrar (por padrão `http://localhost:5173`). Login de teste:
`ana@ifma.edu.br` / `123456`.

## Pré-requisito

O `backend/` precisa estar rodando (`php spark serve`, porta 8080) — os componentes chamam
`http://localhost:8080/api/...`.

## Estrutura

- `src/App.vue` — só orquestra: mostra `TelaLogin` ou `PainelUsuarios` dependendo se tem token.
- `src/components/TelaLogin.vue` — formulário de login, chama `POST /api/auth/login`.
- `src/components/PainelUsuarios.vue` — cadastro + tabela de usuários (editar/excluir).

## Remontando do zero em outra máquina

```bash
npm create vue@latest . -- --default
```
No assistente interativo, ou pelos flags, não marque TypeScript, Router, Pinia, ESLint nem testes.
Depois copie `src/App.vue`, `src/components/` e `src/main.js` deste material, ajuste o `index.html`
(título + link do Bootstrap via CDN) e rode `npm install && npm run dev`.
