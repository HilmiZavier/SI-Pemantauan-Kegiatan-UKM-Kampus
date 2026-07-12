<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function index()
    {
        // Jika user sudah login, arahkan ke dashboard masing-masing sesuai role
        if (session()->get('logged_in')) {
            return $this->redirectBasedOnRole(session()->get('role_id'));
        }

        return view('auth/login');
    }

    public function login()
    {
        // 1. Aturan validasi form
        $rules = [
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.'
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password wajib diisi.'
                ]
            ]
        ];

        // 2. Lakukan validasi
        if (!$this->validate($rules)) {
            // Jika validasi gagal, kembalikan dengan error dan input lama
            return redirect()->to('/login')->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');

        $userModel = new UserModel();

        // 3. Cek apakah email ada di database
        $user = $userModel->where('email', $email)->first();

        if ($user) {
            // 4. Verifikasi password dengan password_verify()
            if (password_verify($password, $user['password'])) {

                // 5. Simpan data ke Session
                $sessionData = [
                    'user_id'   => $user['id'],
                    'nama'      => $user['nama'],
                    'email'     => $user['email'],
                    'role_id'   => $user['role_id'],
                    'ukm_id'    => $user['ukm_id'],
                    'foto'      => $user['foto'],
                    'logged_in' => true
                ];

                session()->set($sessionData);

                // 6. Redirect berdasarkan role
                return $this->redirectBasedOnRole($user['role_id']);
            } else {
                // Password salah
                session()->setFlashdata('error', 'Password salah.');
                return redirect()->to('/login')->withInput();
            }
        } else {
            // Email tidak ditemukan
            session()->setFlashdata('error', 'Email tidak ditemukan.');
            return redirect()->to('/login')->withInput();
        }
    }

    public function logout()
    {
        // Hapus session dan redirect ke halaman login
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda berhasil logout.');
    }

    /**
     * Helper untuk redirect dashboard sesuai Role
     */
    private function redirectBasedOnRole($role_id)
    {
        switch ($role_id) {
            case 1:
                return redirect()->to('/ukm/dashboard');
            case 2:
                return redirect()->to('/kemahasiswaan/dashboard');
            case 3:
                return redirect()->to('/wakilrektor3/dashboard');
            default:
                session()->destroy();
                return redirect()->to('/login')->with('error', 'Role tidak valid.');
        }
    }
}
