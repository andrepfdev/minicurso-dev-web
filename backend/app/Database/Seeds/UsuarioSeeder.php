<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $usuarios = [
            ['nome' => 'Diego Alves',  'email' => 'diego@ifma.edu.br',  'senha' => '123456', 'salario' => 3550.75],
            ['nome' => 'Bruno Lima',   'email' => 'bruno@ifma.edu.br',  'senha' => '123456', 'salario' => 2800.50],
            ['nome' => 'Carla Nunes',  'email' => 'carla@ifma.edu.br',  'senha' => '123456', 'salario' => 4100.00],
            ['nome' => 'Ana Souza',    'email' => 'ana@ifma.edu.br',    'senha' => '123456', 'salario' => 3200.00],
        ];

        $this->db->table('usuarios')->insertBatch($usuarios);
    }
}
