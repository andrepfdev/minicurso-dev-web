<?php

namespace App\Services;

use App\Models\UsuarioModel;
use CodeIgniter\Exceptions\PageNotFoundException;

// A regra de negócio mora aqui, não no controller.
// Se amanhã um comando de CLI ou outra API precisar "listar usuários",
// ele chama este mesmo Service — não precisa duplicar a regra.
class UsuarioService
{
    protected UsuarioModel $model;

    public function __construct()
    {
        $this->model = new UsuarioModel();
    }

    public function listar(): array
    {
        $usuarios = $this->model->orderBy('id', 'ASC')->findAll();

        // return array_map(fn($usuario) => $this->semSenha($usuario), $usuarios);
        return $usuarios;
    }

    public function buscar(int $id): array
    {
        $usuario = $this->model->find($id)
            ?? throw new PageNotFoundException("Usuário {$id} não encontrado");

        return $this->semSenha($usuario);
    }

    public function criar(array $dados): array
    {
        if (empty($dados['nome']) || empty($dados['email']) || empty($dados['senha'])) {
            throw new \InvalidArgumentException('nome, email e senha são obrigatórios');
        }

        $id = $this->model->insert([
            'nome'    => $dados['nome'],
            'email'   => $dados['email'],
            'senha'   => $dados['senha'],
            'salario' => $dados['salario'] ?? 0,
        ]);

        return $this->semSenha($this->model->find($id));
    }

    private function semSenha(array $usuario): array
    {
        unset($usuario['senha']);
        return $usuario;
    }
}
