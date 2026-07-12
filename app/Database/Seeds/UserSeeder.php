<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $roles = $this->db->table('roles')->get()->getResultArray();
        $roleMap = [];
        foreach ($roles as $r) {
            $roleMap[$r['nama_role']] = $r['id'];
        }

        $data = [
            [
                'role_id'    => $roleMap['Admin Kemahasiswaan'] ?? 2,
                'ukm_id'     => null,
                'nama'       => 'Admin Kemahasiswaan',
                'email'      => 'kemahasiswaan@unitomo.ac.id',
                'password'   => password_hash('password', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'role_id'    => $roleMap['Wakil Rektor III'] ?? 3,
                'ukm_id'     => null,
                'nama'       => 'Wakil Rektor III',
                'email'      => 'wr3@unitomo.ac.id',
                'password'   => password_hash('password', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $ukms = $this->db->table('ukm')->get()->getResultArray();
        
        foreach ($ukms as $ukm) {
            $slug = strtolower(str_replace(' ', '', $ukm['nama_ukm']));
            $data[] = [
                'role_id'    => $roleMap['Admin UKM'] ?? 1,
                'ukm_id'     => $ukm['id'],
                'nama'       => 'Admin ' . $ukm['nama_ukm'],
                'email'      => 'admin_' . $slug . '@unitomo.ac.id',
                'password'   => password_hash('password', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        if (!empty($data)) {
            $this->db->table('users')->insertBatch($data);
        }
    }
}
