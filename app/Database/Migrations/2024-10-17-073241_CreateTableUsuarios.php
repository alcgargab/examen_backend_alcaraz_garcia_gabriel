<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableUsuarios extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'ID' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'Name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'Email' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'Password' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
            ]
        ]);
        $this->forge->addKey('ID', true);
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
    }
}
