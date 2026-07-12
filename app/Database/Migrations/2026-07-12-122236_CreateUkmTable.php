<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUkmTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_ukm' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'ketua' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $attributes = ($this->db->DBDriver === 'MySQLi') ? ['ENGINE' => 'InnoDB'] : [];
        $this->forge->createTable('ukm', true, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('ukm');
    }
}
