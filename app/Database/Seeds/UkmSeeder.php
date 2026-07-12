<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class UkmSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID');

        $data = [];
        $ukm_names = ['Pramuka', 'KSR PMI', 'Kesenian', 'Olahraga', 'Rohis', 'Resimen Mahasiswa', 'Penalaran'];

        foreach ($ukm_names as $name) {
            $data[] = [
                'nama_ukm'   => 'UKM ' . $name,
                'ketua'      => $faker->name,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('ukm')->insertBatch($data);
    }
}
