# Backend — CodeIgniter 4 + SQLite

API do "Painel de Usuários". Segue o padrão:

```
Rota → Controller (só HTTP) → Service (regra de negócio) → Model (acesso a dados) → SQLite
```

Respostas seguem sempre o envelope `{ sucesso, mensagem?, dados }`, igual ao padrão usado no
`api-exemplo`.

Este projeto já está montado e instalado (`composer create-project codeigniter4/appstarter .`
rodado, `vendor/` presente, banco migrado e populado). Para rodar:

```bash
php spark serve
```

## Remontando do zero em outra máquina

Se for gerar este projeto do zero (sem copiar a pasta `vendor/`):

1. `composer create-project codeigniter4/appstarter .`
2. Copie por cima os arquivos já prontos deste material: `app/Controllers/Usuarios.php`,
   `app/Controllers/Auth.php`, `app/Services/UsuarioService.php`, `app/Services/AuthService.php`,
   `app/Models/UsuarioModel.php`, `app/Database/Migrations/..._CreateUsuarios.php`,
   `app/Database/Seeds/UsuarioSeeder.php`.
3. Em `app/Config/Routes.php`, acrescente as rotas de `api/usuarios` e `api/auth/login` (ver o
   arquivo já pronto neste projeto como referência).
4. Configure `app/Config/Cors.php` → `allowedOrigins: ['http://localhost:5173']`,
   `allowedHeaders: ['Content-Type']`, `allowedMethods: ['GET', 'POST', 'PUT', 'DELETE']` — e
   registre `'cors'` em `app/Config/Filters.php`, dentro de **`$required['before']`** (não
   `$globals`! ver "O que observar no código" abaixo).
5. No `.env`: `CI_ENVIRONMENT = development`, `database.default.DBDriver = SQLite3`,
   `database.default.database = database.db`.
6. Rode sempre via **spark**, nunca chamando o PHP embutido diretamente:
   ```bash
   php spark migrate --all
   php spark db:seed UsuarioSeeder
   php spark serve
   ```

## Endpoints

| Método | Rota | O que faz |
|---|---|---|
| GET | `/api/usuarios` | Lista todos os usuários (⚠️ hoje devolve a senha em texto puro de propósito — Desafio 5) |
| GET | `/api/usuarios/:id` | Busca um usuário |
| POST | `/api/usuarios` | Cadastra um usuário (`nome`, `email`, `senha`) |
| PUT | `/api/usuarios/:id` | Atualiza `nome`/`email`/`salario` |
| DELETE | `/api/usuarios/:id` | Remove um usuário |
| POST | `/api/auth/login` | Login (`email`, `senha`) → `{token, usuario}` (⚠️ `usuario` também vem com a senha em texto puro de propósito — Desafio 5) |

Login de teste (seeder): `ana@ifma.edu.br` / `123456` (também `bruno@`, `carla@`, `diego@`
`@ifma.edu.br`, mesma senha).

## O que observar no código

- `Controllers/Usuarios.php` e `Controllers/Auth.php` — magros, só traduzem HTTP para chamadas do
  Service **nas ações `index`/`show`/`create`/`login`**.
- `Services/UsuarioService.php` / `Services/AuthService.php` — toda a regra de negócio (validação,
  "não existe" → 404, login → 401).
- **`update()` e `delete()` em `Controllers/Usuarios.php` estão de propósito "gordos"** — falam
  direto com `UsuarioModel`, sem passar por Service (tem um `// TODO (ao vivo em aula)` marcando o
  lugar). O front já chama essas rotas normalmente (editar/excluir funcionam de ponta a ponta); a
  ideia é usar esse código como material pra refatorar ao vivo durante a aula, criando
  `UsuarioService::atualizar()`/`remover()` na frente da turma, do mesmo jeito que já foi feito com
  as outras ações.
- Excluir no front pede confirmação antes de chamar a API (clique em "Excluir" → aparece
  "Confirmar"/"Cancelar" na própria linha) — decisão tomada depois de excluir um usuário sem querer
  testando o botão sem essa proteção.
- `Config/Cors.php` — filtro de CORS **nativo do CI4** (`CodeIgniter\Filters\Cors`), configurado
  para liberar o frontend (porta 5173) a chamar esta API (porta 8080).
- `Config/Filters.php` — o filtro `cors` está em **`$required['before']`**, não em `$globals`.
  Motivo: um filtro `before` normal só roda depois que uma rota já casou; o preflight `OPTIONS` do
  navegador não bate em rota nenhuma (só temos rotas GET/POST), então cairia em 404 antes do
  filtro rodar. `$required['before']` roda mesmo sem rota casada — é o único jeito de responder o
  preflight corretamente. Isso foi descoberto testando o app pelo navegador de verdade (curl
  sozinho não pega esse problema, porque não manda preflight).
- `Models/UsuarioModel.php` — tem `$validationRules` de verdade (`nome`, `email` com
  `is_unique[...]`, `senha`, `salario`, e um `'id' => 'permit_empty|is_natural_no_zero'` só pra
  permitir o placeholder `{id}` da regra de e-mail único — exigência do próprio CI4, ver Desafio 4
  no README raiz). Isso faz `UsuarioService::criar()` e `Controllers/Usuarios.php::update()`
  checarem o retorno do Model (`insert()`/`update()` podem devolver `false` sem lançar exceção) e
  devolverem os erros de `$model->errors()` — sem isso, uma validação que falha vira um "sucesso"
  fantasma (dado antigo devolvido como se tivesse sido salvo).
- `UsuarioService::listar()` e `AuthService::login()` têm um `unset($usuario['senha'])` comentado
  de propósito — é o Desafio 5 do README raiz (achar os dois vazamentos de senha em texto puro).
