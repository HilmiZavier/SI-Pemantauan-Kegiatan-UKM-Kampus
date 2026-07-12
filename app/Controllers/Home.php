<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) {
            $role_id = session()->get('role_id');
            switch ($role_id) {
                case 1:
                    return redirect()->to('/ukm/dashboard');
                case 2:
                    return redirect()->to('/kemahasiswaan/dashboard');
                case 3:
                    return redirect()->to('/wakilrektor3/dashboard');
            }
        }
        return redirect()->to('/login');
    }

    public function unauthorized()
    {
        return view('errors/unauthorized');
    }

    public function migrate()
    {
        try {
            $migrate = \Config\Services::migrations();
            $seeder = \Config\Database::seeder();
            
            $migrate->latest();
            $seeder->call('DatabaseSeeder');
            
            return "Migrasi & Seeding Berhasil!";
        } catch (\Throwable $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
