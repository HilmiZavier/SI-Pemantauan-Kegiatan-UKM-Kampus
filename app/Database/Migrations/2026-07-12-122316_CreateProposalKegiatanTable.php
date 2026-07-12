<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProposalKegiatanTable extends Migration
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
            'kegiatan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'file_proposal' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'anggaran' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'status_kemahasiswaan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'catatan_kemahasiswaan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'verified_by_kemahasiswaan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'verified_at_kemahasiswaan' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status_wakilrektor3' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'catatan_wakilrektor3' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'verified_by_wakilrektor3' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'verified_at_wakilrektor3' => [
                'type' => 'DATETIME',
                'null' => true,
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
        $this->forge->addForeignKey('kegiatan_id', 'kegiatan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('verified_by_kemahasiswaan', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('verified_by_wakilrektor3', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('proposal_kegiatan', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('proposal_kegiatan');
    }
}
