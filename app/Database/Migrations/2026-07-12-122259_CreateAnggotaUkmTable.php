<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnggotaUkmTable extends Migration
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
            'ukm_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'divisi_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'nim' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'prodi' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'jabatan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'angkatan' => [
                'type'       => 'VARCHAR',
                'constraint' => '4',
            ],
            'no_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'email' => [
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
        $this->forge->addForeignKey('ukm_id', 'ukm', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('divisi_id', 'divisi_ukm', 'id', 'CASCADE', 'CASCADE');
        $attributes = ($this->db->DBDriver === 'MySQLi') ? ['ENGINE' => 'InnoDB'] : [];
        $this->forge->createTable('anggota_ukm', true, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('anggota_ukm');
    }
}
