<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class ProfileController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        $data = [
            'title' => 'Profil Saya',
            'user'  => $user
        ];

        return view('profile/index', $data);
    }

    public function update()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        $validationRule = [
            'photo' => [
                'label' => 'Foto Profil',
                'rules' => [
                    'uploaded[photo]',
                    'is_image[photo]',
                    'mime_in[photo,image/jpg,image/jpeg,image/png]',
                    'max_size[photo,2048]', // max 2MB
                ],
            ],
        ];

        if (!$this->validate($validationRule)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $img = $this->request->getFile('photo');

        if ($img->isValid() && !$img->hasMoved()) {
            // Buat folder jika belum ada
            $uploadPath = FCPATH . 'uploads/profile/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Hapus foto lama jika ada
            if (!empty($user['foto']) && file_exists($uploadPath . $user['foto'])) {
                unlink($uploadPath . $user['foto']);
            }

            // Generate nama unik dan simpan
            $newName = $img->getRandomName();
            $img->move($uploadPath, $newName);

            // Update DB
            $this->userModel->update($userId, [
                'foto' => $newName
            ]);

            // Update Session
            session()->set('foto', $newName);

            return redirect()->to('/profile')->with('success', 'Foto profil berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah foto profil.');
    }

    public function delete()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        $uploadPath = FCPATH . 'uploads/profile/';

        // Hapus foto lama jika ada
        if (!empty($user['foto']) && file_exists($uploadPath . $user['foto'])) {
            unlink($uploadPath . $user['foto']);
        }

        // Update DB
        $this->userModel->update($userId, [
            'foto' => null
        ]);

        // Update Session
        session()->set('foto', null);

        return redirect()->to('/profile')->with('success', 'Foto profil berhasil dihapus.');
    }
}
