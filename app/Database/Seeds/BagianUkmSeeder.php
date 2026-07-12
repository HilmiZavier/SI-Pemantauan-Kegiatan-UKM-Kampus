<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BagianUkmSeeder extends Seeder
{
    public function run()
    {
        $ukms = $this->db->table('ukm')->get()->getResultArray();
        
        $data = [];
        $divisi_umum = ['Humas', 'Kaderisasi', 'Administrasi', 'Keuangan', 'Operasional'];

        foreach ($ukms as $ukm) {
            foreach ($divisi_umum as $divisi) {
                $data[] = [
                    'ukm_id'      => $ukm['id'],
                    'nama_divisi' => $divisi,
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                ];
            }
        }

        if (!empty($data)) {
            $this->db->table('divisi_ukm')->insertBatch($data);
        }
    }
}
