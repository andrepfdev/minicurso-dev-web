<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsuarios extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'      => ['type' => 'INTEGER', 'auto_increment' => true],
            'nome'    => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'   => ['type' => 'VARCHAR', 'constraint' => 150],
            'senha'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'salario' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
    }
}
