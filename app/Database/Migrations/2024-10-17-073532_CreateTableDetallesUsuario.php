<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableDetallesUsuario extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'du_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'u_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'direccion' => [
                'type' => 'TEXT',
            ],
            'telefono' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
            ],
            'fNacimiento' => [
                'type' => 'DATE',            ]
        ]);
        $this->forge->addKey('du_id', true);
        $this->forge->createTable('detalles_usuario');
    }

    public function down()
    {
        $this->forge->dropTable('detalles_usuario');
    }
}
