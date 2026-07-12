<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnggotaUkmModel;
use App\Models\DivisiUkmModel;

class AnggotaUKMController extends BaseController
{
    protected $anggotaModel;
    protected $divisiModel;

    public function __construct()
    {
        $this->anggotaModel = new AnggotaUkmModel();
        $this->divisiModel = new DivisiUkmModel();
    }

    public function index()
    {
        $ukmId = session()->get('ukm_id');
        
        $data = [
            'anggota' => $this->anggotaModel->where('ukm_id', $ukmId)->findAll()
        ];

        return view('anggota_ukm/index', $data);
    }

    public function create()
    {
        $ukmId = session()->get('ukm_id');
        
        $data = [
            'divisi' => $this->divisiModel->where('ukm_id', $ukmId)->findAll()
        ];

        return view('anggota_ukm/create', $data);
    }

    public function store()
    {
        $ukmId = session()->get('ukm_id');

        $rules = [
            'divisi_id' => 'required|numeric',
            'nama'      => 'required|min_length[3]|max_length[255]',
            'nim'       => 'required|alpha_numeric|max_length[20]',
            'prodi'     => 'required|max_length[100]',
            'jabatan'   => 'required|max_length[100]',
            'angkatan'  => 'required|exact_length[4]|numeric',
            'no_hp'     => 'required|max_length[20]',
            'email'     => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Pastikan divisi_id yang dipilih valid dan milik ukm tersebut
        $divisiId = $this->request->getPost('divisi_id');
        $divisi = $this->divisiModel->find($divisiId);
        if (!$divisi || $divisi['ukm_id'] != $ukmId) {
            return redirect()->back()->withInput()->with('error', 'Bagian UKM tidak valid.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->anggotaModel->insert([
            'ukm_id'    => $ukmId,
            'divisi_id' => $divisiId,
            'nama'      => $this->request->getPost('nama'),
            'nim'       => $this->request->getPost('nim'),
            'prodi'     => $this->request->getPost('prodi'),
            'jabatan'   => $this->request->getPost('jabatan'),
            'angkatan'  => $this->request->getPost('angkatan'),
            'no_hp'     => $this->request->getPost('no_hp'),
            'email'     => $this->request->getPost('email'),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data Anggota.');
        }

        return redirect()->to('/anggota-ukm')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $ukmId = session()->get('ukm_id');
        $anggota = $this->anggotaModel->find($id);

        if (!$anggota || $anggota['ukm_id'] != $ukmId) {
            return redirect()->to('/anggota-ukm')->with('error', 'Data tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $data = [
            'anggota' => $anggota,
            'divisi'  => $this->divisiModel->where('ukm_id', $ukmId)->findAll()
        ];
        
        return view('anggota_ukm/edit', $data);
    }

    public function update($id)
    {
        $ukmId = session()->get('ukm_id');
        $anggota = $this->anggotaModel->find($id);

        if (!$anggota || $anggota['ukm_id'] != $ukmId) {
            return redirect()->to('/anggota-ukm')->with('error', 'Data tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $rules = [
            'divisi_id' => 'required|numeric',
            'nama'      => 'required|min_length[3]|max_length[255]',
            'nim'       => 'required|alpha_numeric|max_length[20]',
            'prodi'     => 'required|max_length[100]',
            'jabatan'   => 'required|max_length[100]',
            'angkatan'  => 'required|exact_length[4]|numeric',
            'no_hp'     => 'required|max_length[20]',
            'email'     => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $divisiId = $this->request->getPost('divisi_id');
        $divisi = $this->divisiModel->find($divisiId);
        if (!$divisi || $divisi['ukm_id'] != $ukmId) {
            return redirect()->back()->withInput()->with('error', 'Bagian UKM tidak valid.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->anggotaModel->update($id, [
            'divisi_id' => $divisiId,
            'nama'      => $this->request->getPost('nama'),
            'nim'       => $this->request->getPost('nim'),
            'prodi'     => $this->request->getPost('prodi'),
            'jabatan'   => $this->request->getPost('jabatan'),
            'angkatan'  => $this->request->getPost('angkatan'),
            'no_hp'     => $this->request->getPost('no_hp'),
            'email'     => $this->request->getPost('email'),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data Anggota.');
        }

        return redirect()->to('/anggota-ukm')->with('success', 'Data Anggota berhasil diupdate.');
    }

    public function delete($id)
    {
        $ukmId = session()->get('ukm_id');
        $anggota = $this->anggotaModel->find($id);

        if (!$anggota || $anggota['ukm_id'] != $ukmId) {
            return redirect()->to('/anggota-ukm')->with('error', 'Data tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->anggotaModel->delete($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/anggota-ukm')->with('error', 'Gagal menghapus data Anggota.');
        }

        return redirect()->to('/anggota-ukm')->with('success', 'Data Anggota berhasil dihapus.');
    }
}
