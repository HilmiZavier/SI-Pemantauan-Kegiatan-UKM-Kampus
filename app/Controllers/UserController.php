<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'users' => $this->userModel->findAll()
        ];
        return view('user/index', $data);
    }

    public function create()
    {
        return view('user/create');
    }

    public function store()
    {
        $rules = [
            'role_id'  => 'required|numeric',
            'ukm_id'   => 'permit_empty|numeric',
            'nama'     => 'required|min_length[3]|max_length[255]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->userModel->insert([
            'role_id'  => $this->request->getPost('role_id'),
            'ukm_id'   => $this->request->getPost('ukm_id') ?: null,
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT)
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data user.');
        }

        return redirect()->to('/user')->with('success', 'Data user berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/user')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'user' => $user
        ];
        return view('user/edit', $data);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/user')->with('error', 'Data tidak ditemukan.');
        }

        $rules = [
            'role_id'  => 'required|numeric',
            'ukm_id'   => 'permit_empty|numeric',
            'nama'     => 'required|min_length[3]|max_length[255]',
            'email'    => "required|valid_email|is_unique[users.email,id,{$id}]"
        ];

        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $dataUpdate = [
            'role_id'  => $this->request->getPost('role_id'),
            'ukm_id'   => $this->request->getPost('ukm_id') ?: null,
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
        ];

        if ($this->request->getPost('password')) {
            $dataUpdate['password'] = password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $dataUpdate);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data user.');
        }

        return redirect()->to('/user')->with('success', 'Data user berhasil diupdate.');
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/user')->with('error', 'Data tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->userModel->delete($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/user')->with('error', 'Gagal menghapus data user.');
        }

        return redirect()->to('/user')->with('success', 'Data user berhasil dihapus.');
    }
}
