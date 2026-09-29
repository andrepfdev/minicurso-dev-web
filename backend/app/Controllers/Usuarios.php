<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Services\UsuarioService;
use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;

class Usuarios extends Controller
{
    protected UsuarioService $service;

    public function __construct()
    {
        $this->service = new UsuarioService();
    }

    public function index()
    {
        return $this->response->setJSON([
            'sucesso' => true,
            'dados'   => $this->service->listar(),
        ]);
    }

    // Erro PROPOSITAL (usado na tela /busca do frontend para demonstrar
    // tratamento de falhas). Remover este método para a busca funcionar.
    public function busca()
    {
        // return $this->response->setStatusCode(500)->setJSON([
        //     'sucesso'  => false,
        //     'mensagem' => 'Erro proposital: falha ao buscar usuários no servidor.',
        // ]);
        
        $usuarios = $this->service->buscarPorNome($this->request->getGet('nome') ?? '');

        return $this->response->setJSON([
            'sucesso' => true,
            'dados'   => $usuarios,
        ]);
    }

    public function show($id = null)
    {
        try {
            $usuario = $this->service->buscar((int) $id);
            return $this->response->setJSON(['sucesso' => true, 'dados' => $usuario]);
        } catch (PageNotFoundException $e) {
            return $this->response->setStatusCode(404)->setJSON([
                'sucesso'  => false,
                'mensagem' => $e->getMessage(),
            ]);
        }
    }

    public function create()
    {
        try {
            $dados   = $this->request->getJSON(true) ?? [];
            $usuario = $this->service->criar($dados);
            return $this->response->setStatusCode(201)->setJSON([
                'sucesso' => true,
                'dados'   => $usuario,
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->response->setStatusCode(422)->setJSON([
                'sucesso'  => false,
                'mensagem' => $e->getMessage(),
            ]);
        }
    }

    // TODO (ao vivo em aula): mover a regra de negocio daqui pra dentro de um
    // UsuarioService::atualizar(), do jeito que ja foi feito com index/show/create.
    // Por enquanto o Controller esta "gordo" de proposito: fala direto com o Model.
    public function update($id = null)
    {
        $model   = new UsuarioModel();
        $usuario = $model->find((int) $id);

        if (! $usuario) {
            return $this->response->setStatusCode(404)->setJSON([
                'sucesso'  => false,
                'mensagem' => "Usuário {$id} não encontrado",
            ]);
        }

        $dados  = $this->request->getJSON(true) ?? [];
        $campos = array_intersect_key($dados, array_flip(['nome', 'email', 'salario']));

        if (empty($campos)) {
            return $this->response->setStatusCode(422)->setJSON([
                'sucesso'  => false,
                'mensagem' => 'nada para atualizar (envie nome, email e/ou salario)',
            ]);
        }

        // O Model tem a regra is_unique[usuarios.email,id,{id}] — o placeholder {id} só
        // resolve se o id estiver nos dados validados (Model::update() valida ANTES de
        // saber o id da query). O campo 'id' não está em $allowedFields, então é
        // descartado antes de ir pro banco; serve só pra validação enxergar o {id}.
        $campos['id'] = (int) $id;

        if (! $model->update((int) $id, $campos)) {
            return $this->response->setStatusCode(422)->setJSON([
                'sucesso'  => false,
                'mensagem' => implode(' ', $model->errors() ?? ['Não foi possível atualizar.']),
            ]);
        }

        $atualizado = $model->find((int) $id);
        unset($atualizado['senha']);

        return $this->response->setJSON(['sucesso' => true, 'dados' => $atualizado]);
    }

    // TODO (ao vivo em aula): mover pra UsuarioService::remover(), igual ao update().
    public function delete($id = null)
    {
        $model   = new UsuarioModel();
        $usuario = $model->find((int) $id);

        if (! $usuario) {
            return $this->response->setStatusCode(404)->setJSON([
                'sucesso'  => false,
                'mensagem' => "Usuário {$id} não encontrado",
            ]);
        }

        $model->delete((int) $id);

        return $this->response->setJSON(['sucesso' => true, 'mensagem' => 'Usuário removido']);
    }
}
