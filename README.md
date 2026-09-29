# Minicurso IFMA — Desenvolvimento Web (3h)

Uma aplicação só: **"Painel de Usuários"**, um login simples + listagem/cadastro de usuários — o
mesmo domínio do `api-exemplo` (referência anterior), agora em CI4 + SQLite + Vue. Estrutura real
de projeto, com backend e frontend em pastas irmãs, como em qualquer repositório do dia a dia.

Plano completo (contexto, roteiro detalhado, identidade visual dos slides) em:
`/home/andre/.claude/plans/baseado-no-que-eu-mighty-bentley.md`

## 📂 Estrutura do projeto

```text
minicurso-dev-web/
├── 00-ambiente/         Checklist e script de verificação da máquina antes da aula
├── backend/             Projeto CodeIgniter 4 completo (instalado via composer create-project),
│   │                    roda em :8080
│   └── app/
│       ├── Controllers/Usuarios.php     Só HTTP, delega tudo pro Service (listar/buscar/criar)
│       ├── Controllers/Auth.php         Só HTTP, delega o login pro Service
│       ├── Services/UsuarioService.php  Regra de negócio dos usuários
│       ├── Services/AuthService.php     Regra de negócio do login
│       ├── Models/UsuarioModel.php      Acesso a dados
│       ├── Config/Cors.php             Origem liberada (frontend, porta 5173)
│       ├── Config/Filters.php          Registra o Cors como filtro **required/before**
│       ├── Config/Routes.php           Rotas da API (api/usuarios, api/auth/login)
│       └── Database/                   Migration + seeder da tabela usuarios
└── frontend/             Projeto Vue 3 + Vite completo (instalado via npm create vue@latest),
    │                     roda em :5173
    └── src/
        ├── App.vue                      Orquestra qual tela mostrar (login ou painel)
        ├── components/TelaLogin.vue      Formulário de login (POST /api/auth/login)
        ├── components/PainelUsuarios.vue Cadastro + tabela (editar/excluir), consome a API
        └── main.js
```

Backend e frontend já estão **instalados de verdade** (`backend/vendor/` via Composer,
`frontend/node_modules/` via npm) e o banco SQLite já está migrado e populado — testado aqui num
Linux. **As máquinas da aula são Windows** — antes de levar o material, veja o aviso em
`00-ambiente/README.md` sobre o que pode ser só copiado entre sistemas operacionais e o que precisa
ser reinstalado (resumo: `node_modules/` do frontend precisa ser gerado de novo no Windows;
`vendor/` do backend e o banco `.db` podem ser copiados direto).

## 🚀 Como rodar

1. Veja `00-ambiente/README.md` e confirme os requisitos da máquina.
2. Suba o backend:
   ```bash
   cd backend
   php spark serve
   ```
3. Em outro terminal, suba o frontend:
   ```bash
   cd frontend
   npm run dev
   ```
4. Acesse `http://localhost:5173`.

Login de teste (do seeder): `ana@ifma.edu.br` / `123456` (ou `bruno@ifma.edu.br`,
`carla@ifma.edu.br`, `diego@ifma.edu.br`, todos com a mesma senha).

Se for montar o material do zero em outra máquina (sem copiar `vendor/`/`node_modules/`), os
comandos completos de instalação estão em `backend/README.md` e `frontend/README.md`.

## Ordem de execução na aula

Fundamentos de HTML/CSS/JS puro (sem pasta própria — live-coding do professor) → `backend/`
(rotas → Controller → **Service** → Model → SQLite) → `frontend/` (comparando o JS vanilla escrito
ao vivo com o Vue já pronto) → integração ponta a ponta (login no Vue → POST → Service → SQLite →
resposta com token → UI mostra a tabela).

**Refatoração do "controller gordo" ao vivo:** `update()` e `delete()` em
`backend/app/Controllers/Usuarios.php` já funcionam de ponta a ponta (o front tem os botões
"Editar"/"Excluir" prontos), mas de propósito ainda falam direto com o `UsuarioModel`, sem
`UsuarioService` — tem um `// TODO` marcando onde. Esse é o momento de extrair
`UsuarioService::atualizar()`/`remover()` na frente da turma, do mesmo jeito que já foi feito com
`index`/`show`/`create`/`login`.

## 🎯 Desafios de Implementação

Antes de codar, abra o **DevTools** (F12) → aba **Network** → filtre por `Fetch/XHR`. Cada desafio
abaixo pede pra olhar ali antes de mexer no código — é assim que se descobre se um problema é do
front ou do back, na vida real.

### Desafio 0: Ler a resposta da API e mostrar o salário
Recarregue a página (já logado) com o Network aberto, clique na requisição `usuarios` e veja a aba
**Response**: cada usuário já vem com um campo `salario`, mesmo a tabela não mostrando essa coluna.
Depois de confirmar isso pela resposta (não adivinhando), adicione a coluna `salario` na tabela em
`components/PainelUsuarios.vue`.

### Desafio 1: o formulário de cadastro não funciona — ache o bug
O formulário já existe em `components/PainelUsuarios.vue` e já chama `POST /api/usuarios` com
`fetch()`, mas ao clicar em "+" a API responde **422** e nenhum usuário novo aparece na lista. Abra
o Network, submeta o formulário, compare a aba **Payload** (o que foi enviado) com a aba
**Response** (a mensagem de erro). Um dos campos enviados não bate com o nome que o backend espera.
Ache a linha em `cadastrar()` e corrija.

### Desafio 2: o login aceita qualquer senha — front ou back?
Entre com um e-mail válido (ex.: `ana@ifma.edu.br`) e **qualquer senha errada** — o login funciona
mesmo assim! Use o Network pra confirmar que o front está enviando a senha certa no Payload (o
problema não é o `components/TelaLogin.vue`). O bug está em `AuthService::login()`, no backend: a
comparação que deveria checar a senha está comparando o campo errado.

### Desafio 3: buscar um usuário que não existe
A rota `GET /api/usuarios/:id` já existe. Adicione um campo de busca por ID em
`components/PainelUsuarios.vue`: se o usuário existir, mostre os dados; se a API responder 404
(confira no Network o status e o corpo da resposta), mostre a mensagem de erro que ela já devolve
(`corpo.mensagem`) em vez de deixar a tela sem retorno nenhum.

### Desafio 4: cadastrar um e-mail repetido — leia a validação pronta
Tente cadastrar um usuário com o e-mail `ana@ifma.edu.br` (já existe no seeder). A API responde
**422** com uma mensagem amigável ("O email informado já está em uso.") em vez de quebrar — quem
bloqueia isso é a regra `is_unique[usuarios.email,id,{id}]` em `Models/UsuarioModel.php`
(`$validationRules`), antes mesmo de chegar no banco. Abra o Network pra ver o 422 tratado, depois
abra o Model e ache a regra. Bônus pra pensar: por que o campo `id` também precisa de uma regra
própria ali (`'id' => 'permit_empty|is_natural_no_zero'`) pro placeholder `{id}` funcionar? (Dica:
sem ela, o CI4 nem sabe qual linha excluir da checagem de duplicidade — e sem *nenhuma* regra pra
`id`, ele nem deixa usar o placeholder, e quebra com um erro interno.)

### Desafio 5: a API está vazando a senha de todo mundo — ache os dois vazamentos
Abra o Network, dê F5 na tabela de usuários (`GET /api/usuarios`) e depois faça login de novo
(`POST /api/auth/login`): as duas respostas trazem o campo `senha` **em texto puro**, pra qualquer
um que abrir o DevTools ver. Isso não devia acontecer — compare com `UsuarioService::buscar()`
(que já esconde a senha direito) e ache as duas linhas comentadas que deveriam estar ativas:
`UsuarioService::listar()` e `AuthService::login()`. Descomente as duas e confirme no Network que
o campo `senha` sumiu da resposta nos dois casos.

### Desafio 6: ordenar a lista por nome
Repare que a tabela em `components/PainelUsuarios.vue` mostra os usuários na ordem que a API
devolve — por `id`, não por nome (dá pra confirmar no seeder: os quatro não estão em ordem
alfabética de propósito, pra esse desafio ficar visível de verdade). Adicione um botão "Ordenar
A-Z" que reordena `this.usuarios` pelo campo `nome`, usando o método `.sort()` de array em JS — sem
precisar mexer no backend. Reforça manipulação de array, tema que já apareceu lá no bloco de
"Dados: JSON e estruturas" da aula.

### Desafio 7: formatar o salário como dinheiro
Depois do Desafio 0 (mostrar a coluna `salario`), o número aparece cru na tabela (`3200`,
`2800.5`) — sem `R$`, sem separador de milhar, sem duas casas decimais. Formate isso no
`components/PainelUsuarios.vue` antes de exibir.

**Pista:** o JavaScript já vem com um formatador de moeda pronto, o `Intl.NumberFormat`:
```js
new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(3200)
// "R$ 3.200,00"
```
Pode criar um `method` tipo `formatarSalario(valor)` que devolve esse `.format(valor)` e usar no
template (`{{ formatarSalario(usuario.salario) }}`), ou um `computed` se preferir. A API continua
devolvendo o número puro — formatação de exibição é responsabilidade do front, não do back.
