<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $usuarios = [
            ['nome' => 'Ana Souza',    'email' => 'ana@ifma.edu.br',    'senha' => '123456', 'salario' => 3200.00],
            ['nome' => 'Bruno Lima',   'email' => 'bruno@ifma.edu.br',  'senha' => '123456', 'salario' => 2800.50],
            ['nome' => 'Carla Nunes',  'email' => 'carla@ifma.edu.br',  'senha' => '123456', 'salario' => 4100.00],
            ['nome' => 'Diego Alves',  'email' => 'diego@ifma.edu.br',  'senha' => '123456', 'salario' => 3550.75],
        ];

        $this->db->table('usuarios')->insertBatch($usuarios);
    }
}
