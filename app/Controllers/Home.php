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
}
