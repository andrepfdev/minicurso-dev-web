# Checklist de ambiente — antes da aula

**As máquinas dos alunos são Windows.** Este material foi preparado e testado num Linux — o que
pode ser copiado direto e o que precisa ser reinstalado no Windows está detalhado abaixo.

## Requisitos (em cada PC Windows)
- PHP >= 8.1 com extensões: `intl`, `mbstring`, `sqlite3`, `pdo_sqlite` habilitadas no `php.ini`
  (erro clássico: extensão comentada com `;` no `php.ini` do Windows)
- Node.js >= 18 e npm

**Composer NÃO é necessário** pra rodar a aula. `backend/vendor/` já vem pronto dentro do
material (é PHP puro, funciona igual em qualquer SO) — `php spark serve` usa esse `vendor/` direto,
sem precisar do Composer instalado. Composer só entra se alguém precisar remontar o backend do
zero (ver `backend/README.md`), o que não é o caminho esperado no dia da aula.

**PHP escolhido para a aula:** binário oficial (ZIP, não instalador) `php-8.5.11-nts-Win32-vs17-x64`
— atende o requisito do backend (`composer.json` pede `php ^8.2`). Como é um ZIP solto (não
instala sozinho como Laragon/XAMPP), o `php.ini` dele precisa ter `intl`, `mbstring`, `sqlite3` e
`pdo_sqlite` descomentados e o PHP precisa ser colocado no `PATH` — passo a passo completo em
`INSTALACAO-WINDOWS.md` dentro da própria pasta do PHP (o `php.ini` de lá já vem com essas 4
extensões habilitadas). Alternativa mais "no piloto automático", se preferir: instalar via
[Laragon](https://laragon.org/) ou [XAMPP](https://www.apachefriends.org/) (já vêm com PHP
configurado). Node.js: [instalador oficial `.msi`](https://nodejs.org/) (já inclui npm).

## ⚠️ O que NÃO pode ser só copiado de PC para PC

- **`frontend/node_modules/`** — o Vite depende de pacotes com binário nativo por sistema
  operacional (`esbuild`, `rollup`). Um `node_modules` instalado aqui no Linux **não funciona no
  Windows** (vai dar erro ao rodar `npm run dev`). Rode `npm install` em pelo menos uma máquina
  Windows e, se quiser agilizar as outras, copie o `node_modules/` **gerado no Windows** entre elas
  (aí sim é seguro, porque todas são o mesmo SO).
- **`backend/vendor/`** — o CodeIgniter 4 é PHP puro, sem dependência nativa por SO, então copiar
  entre Linux e Windows normalmente funciona e **não precisa reinstalar nada** (Composer não é
  necessário na máquina do aluno). Só existe um fallback com `composer install` se, por algum
  motivo raro, o `vendor/` chegar corrompido/incompleto numa máquina.
- **`backend/writable/database.db`** — arquivo binário do SQLite, esse pode ser copiado
  tranquilamente entre sistemas operacionais.

## Verificar em cada máquina

No **PowerShell** ou **Prompt de Comando**, rode individualmente (não precisa do script `.sh`,
que é para Linux/macOS/Git Bash):
```powershell
php -v
php -m | findstr /I "intl mbstring sqlite3 pdo_sqlite"
node -v
npm -v
```
(`composer -V` só é preciso checar se for remontar o backend do zero — não é requisito pra rodar
o material já pronto.)
Se estiver usando Git Bash ou WSL, pode rodar `bash verificar-ambiente.sh` normalmente.

## Preparar o material

1. Copie a pasta `minicurso-dev-web/` inteira para cada PC (pendrive ou rede local — sem depender
   de internet do laboratório), **sem** o `frontend/node_modules/` (ver aviso acima).
2. Em pelo menos uma máquina Windows: `cd frontend` e rode `npm install` — depois copie esse
   `node_modules/` (gerado no Windows) para as outras máquinas, se quiser economizar tempo/internet.
3. Teste `php spark serve` (dentro de `backend/`) e `npm run dev` (dentro de `frontend/`) juntos em
   pelo menos uma máquina antes do dia.

## Banco de dados

`backend/writable/database.db` já vem com migration e seeder aplicados (tabela `usuarios`
populada) e pode ser copiado direto. Se precisar recriar do zero:
```bash
cd backend
php spark migrate:refresh
php spark db:seed UsuarioSeeder
```
