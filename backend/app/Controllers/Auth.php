<?php

namespace App\Controllers;

use App\Services\AuthService;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected AuthService $service;

    public function __construct()
    {
        $this->service = new AuthService();
    }

    public function login()
    {
        $dados = $this->request->getJSON(true) ?? [];

        try {
            $resultado = $this->service->login($dados['email'] ?? '', $dados['senha'] ?? '');
            return $this->response->setJSON(['sucesso' => true] + $resultado);
        } catch (\RuntimeException $e) {
            return $this->response->setStatusCode(401)->setJSON([
                'sucesso'  => false,
                'mensagem' => $e->getMessage(),
            ]);
        }
    }
}
