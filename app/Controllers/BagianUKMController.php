<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DivisiUkmModel;

class BagianUKMController extends BaseController
{
    protected $divisiModel;

    public function __construct()
    {
        $this->divisiModel = new DivisiUkmModel();
    }

    public function index()
    {
        $ukmId = session()->get('ukm_id');
        
        $data = [
            'divisi' => $this->divisiModel->where('ukm_id', $ukmId)->findAll()
        ];

        return view('bagian_ukm/index', $data);
    }

    public function create()
    {
        return view('bagian_ukm/create');
    }

    public function store()
    {
        $ukmId = session()->get('ukm_id');

        $rules = [
            'nama_divisi' => 'required|min_length[3]|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->divisiModel->insert([
            'ukm_id'      => $ukmId,
            'nama_divisi' => $this->request->getPost('nama_divisi')
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data Bagian UKM.');
        }

        return redirect()->to('/bagian-ukm')->with('success', 'Bagian UKM berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $ukmId = session()->get('ukm_id');
        $divisi = $this->divisiModel->find($id);

        if (!$divisi || $divisi['ukm_id'] != $ukmId) {
            return redirect()->to('/bagian-ukm')->with('error', 'Data tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $data = [
            'divisi' => $divisi
        ];
        return view('bagian_ukm/edit', $data);
    }

    public function update($id)
    {
        $ukmId = session()->get('ukm_id');
        $divisi = $this->divisiModel->find($id);

        if (!$divisi || $divisi['ukm_id'] != $ukmId) {
            return redirect()->to('/bagian-ukm')->with('error', 'Data tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $rules = [
            'nama_divisi' => 'required|min_length[3]|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->divisiModel->update($id, [
            'nama_divisi' => $this->request->getPost('nama_divisi')
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data Bagian UKM.');
        }

        return redirect()->to('/bagian-ukm')->with('success', 'Bagian UKM berhasil diupdate.');
    }

    public function delete($id)
    {
        $ukmId = session()->get('ukm_id');
        $divisi = $this->divisiModel->find($id);

        if (!$divisi || $divisi['ukm_id'] != $ukmId) {
            return redirect()->to('/bagian-ukm')->with('error', 'Data tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->divisiModel->delete($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/bagian-ukm')->with('error', 'Gagal menghapus data Bagian UKM.');
        }

        return redirect()->to('/bagian-ukm')->with('success', 'Bagian UKM berhasil dihapus.');
    }
}
