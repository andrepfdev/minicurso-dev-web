<?php

namespace App\Services;

use App\Models\UsuarioModel;

class AuthService
{
    protected UsuarioModel $model;

    public function __construct()
    {
        $this->model = new UsuarioModel();
    }

    public function login(string $email, string $senha): array
    {
        $usuario = $this->model->where('email', $email)->first()
            ?? throw new \RuntimeException('Usuário ou senha inválidos');

        // O parametro $senha não está sendo usado em lugar algum.
        if ($usuario['email'] !== $email) {
            throw new \RuntimeException('Usuário ou senha inválidos');
        }

        // unset($usuario['senha']);

        return [
            'token'   => bin2hex(random_bytes(16)),
            'usuario' => $usuario,
        ];
    }
}
