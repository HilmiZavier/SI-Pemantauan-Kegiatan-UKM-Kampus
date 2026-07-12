<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UkmModel;

class UKMController extends BaseController
{
    protected $ukmModel;

    public function __construct()
    {
        $this->ukmModel = new UkmModel();
    }

    public function index()
    {
        $roleId = session()->get('role_id');
        $ukmId = session()->get('ukm_id');

        if ($roleId == 1) { // Admin UKM
            $data = [
                'ukm' => $this->ukmModel->where('id', $ukmId)->findAll()
            ];
        } else { // Admin Kemahasiswaan
            $data = [
                'ukm' => $this->ukmModel->findAll()
            ];
        }

        return view('ukm/index', $data);
    }

    public function create()
    {
        $roleId = session()->get('role_id');
        if ($roleId == 1) {
            return redirect()->to('/ukm')->with('error', 'Anda tidak memiliki hak akses.');
        }

        return view('ukm/create');
    }

    public function store()
    {
        $roleId = session()->get('role_id');
        if ($roleId == 1) {
            return redirect()->to('/ukm')->with('error', 'Anda tidak memiliki hak akses.');
        }

        $rules = [
            'nama_ukm' => 'required|min_length[3]|max_length[255]',
            'ketua'    => 'required|min_length[3]|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->ukmModel->insert([
            'nama_ukm' => $this->request->getPost('nama_ukm'),
            'ketua'    => $this->request->getPost('ketua')
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data UKM.');
        }

        return redirect()->to('/ukm')->with('success', 'Data UKM berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $roleId = session()->get('role_id');
        $sessionUkmId = session()->get('ukm_id');

        // Jika Admin UKM, hanya boleh edit profil UKM miliknya sendiri
        if ($roleId == 1 && $id != $sessionUkmId) {
            return redirect()->to('/ukm')->with('error', 'Anda tidak memiliki hak akses.');
        }

        $ukm = $this->ukmModel->find($id);
        if (!$ukm) {
            return redirect()->to('/ukm')->with('error', 'Data UKM tidak ditemukan.');
        }

        $data = [
            'ukm' => $ukm
        ];
        return view('ukm/edit', $data);
    }

    public function update($id)
    {
        $roleId = session()->get('role_id');
        $sessionUkmId = session()->get('ukm_id');

        if ($roleId == 1 && $id != $sessionUkmId) {
            return redirect()->to('/ukm')->with('error', 'Anda tidak memiliki hak akses.');
        }

        $ukm = $this->ukmModel->find($id);
        if (!$ukm) {
            return redirect()->to('/ukm')->with('error', 'Data UKM tidak ditemukan.');
        }

        $rules = [
            'nama_ukm' => 'required|min_length[3]|max_length[255]',
            'ketua'    => 'required|min_length[3]|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->ukmModel->update($id, [
            'nama_ukm' => $this->request->getPost('nama_ukm'),
            'ketua'    => $this->request->getPost('ketua')
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data UKM.');
        }

        return redirect()->to('/ukm')->with('success', 'Data UKM berhasil diupdate.');
    }

    public function delete($id)
    {
        $roleId = session()->get('role_id');
        if ($roleId == 1) {
            return redirect()->to('/ukm')->with('error', 'Anda tidak memiliki hak akses.');
        }

        $ukm = $this->ukmModel->find($id);
        if (!$ukm) {
            return redirect()->to('/ukm')->with('error', 'Data UKM tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->ukmModel->delete($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/ukm')->with('error', 'Gagal menghapus data UKM.');
        }

        return redirect()->to('/ukm')->with('success', 'Data UKM berhasil dihapus.');
    }
}
