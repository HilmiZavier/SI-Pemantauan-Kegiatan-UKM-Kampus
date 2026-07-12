<?php

namespace App\Controllers;

use App\Models\KegiatanModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class KegiatanController extends BaseController
{
    protected $kegiatanModel;

    public function __construct()
    {
        $this->kegiatanModel = new KegiatanModel();
    }

    /**
     * Menampilkan daftar kegiatan
     */
    public function index()
    {
        $roleId = session()->get('role_id');
        
        if ($roleId == 2) {
            // Admin UKM: Hanya melihat kegiatan UKM sendiri
            $ukmId = session()->get('ukm_id');
            $kegiatan = $this->kegiatanModel
                ->select('kegiatan.*, ukm.nama_ukm')
                ->join('ukm', 'ukm.id = kegiatan.ukm_id')
                ->where('kegiatan.ukm_id', $ukmId)
                ->orderBy('kegiatan.created_at', 'DESC')
                ->findAll();
        } else {
            // Admin Kemahasiswaan (1) & Wakil Rektor III (3): Melihat seluruh kegiatan
            $kegiatan = $this->kegiatanModel
                ->select('kegiatan.*, ukm.nama_ukm')
                ->join('ukm', 'ukm.id = kegiatan.ukm_id')
                ->orderBy('kegiatan.created_at', 'DESC')
                ->findAll();
        }

        $data = [
            'title'    => 'Daftar Kegiatan',
            'kegiatan' => $kegiatan
        ];

        return view('kegiatan/index', $data);
    }

    /**
     * Menampilkan detail kegiatan
     */
    public function show($id)
    {
        $roleId = session()->get('role_id');
        
        $kegiatan = $this->kegiatanModel
            ->select('kegiatan.*, ukm.nama_ukm, users.username as pembuat')
            ->join('ukm', 'ukm.id = kegiatan.ukm_id')
            ->join('users', 'users.id = kegiatan.created_by', 'left')
            ->find($id);

        if (!$kegiatan) {
            return redirect()->to('/kegiatan')->with('error', 'Kegiatan tidak ditemukan.');
        }

        // Jika Admin UKM, pastikan hanya bisa melihat detail kegiatannya sendiri
        if ($roleId == 2 && $kegiatan['ukm_id'] != session()->get('ukm_id')) {
            return redirect()->to('/kegiatan')->with('error', 'Anda tidak memiliki akses ke detail kegiatan ini.');
        }

        $data = [
            'title'    => 'Detail Kegiatan',
            'kegiatan' => $kegiatan
        ];

        return view('kegiatan/show', $data);
    }

    /**
     * Menyimpan kegiatan baru (Hanya Admin UKM)
     */
    public function store()
    {
        $roleId = session()->get('role_id');
        
        if ($roleId != 2) {
            return redirect()->back()->with('error', 'Hanya Admin UKM yang dapat menambahkan kegiatan.');
        }

        $rules = [
            'judul'           => 'required|max_length[255]',
            'deskripsi'       => 'required',
            'tanggal_mulai'   => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
            'tempat'          => 'required|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $data = [
                'ukm_id'          => session()->get('ukm_id'),
                'judul'           => $this->request->getPost('judul'),
                'deskripsi'       => $this->request->getPost('deskripsi'),
                'tanggal_mulai'   => $this->request->getPost('tanggal_mulai'),
                'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
                'tempat'          => $this->request->getPost('tempat'),
                'status'          => 'MENUNGGU_PROPOSAL', // Status awal default
                'created_by'      => session()->get('user_id')
            ];

            $this->kegiatanModel->insert($data);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan kegiatan.');
            }

            $db->transCommit();
            return redirect()->to('/kegiatan')->with('success', 'Kegiatan berhasil ditambahkan.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui kegiatan (Hanya Admin UKM)
     */
    public function update($id)
    {
        $roleId = session()->get('role_id');
        
        if ($roleId != 2) {
            return redirect()->back()->with('error', 'Hanya Admin UKM yang dapat mengedit kegiatan.');
        }

        $kegiatan = $this->kegiatanModel->find($id);
        
        if (!$kegiatan || $kegiatan['ukm_id'] != session()->get('ukm_id')) {
            return redirect()->to('/kegiatan')->with('error', 'Kegiatan tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $rules = [
            'judul'           => 'required|max_length[255]',
            'deskripsi'       => 'required',
            'tanggal_mulai'   => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
            'tempat'          => 'required|max_length[255]',
            'status'          => 'required|in_list[MENUNGGU_PROPOSAL,PROPOSAL_DISETUJUI,SEDANG_BERJALAN,MENUNGGU_LPJ,SELESAI,DIBATALKAN]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $data = [
                'judul'           => $this->request->getPost('judul'),
                'deskripsi'       => $this->request->getPost('deskripsi'),
                'tanggal_mulai'   => $this->request->getPost('tanggal_mulai'),
                'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
                'tempat'          => $this->request->getPost('tempat'),
                'status'          => $this->request->getPost('status')
            ];

            $this->kegiatanModel->update($id, $data);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kegiatan.');
            }

            $db->transCommit();
            return redirect()->to('/kegiatan')->with('success', 'Kegiatan berhasil diperbarui.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus kegiatan (Hanya Admin UKM)
     */
    public function delete($id)
    {
        $roleId = session()->get('role_id');
        
        if ($roleId != 2) {
            return redirect()->back()->with('error', 'Hanya Admin UKM yang dapat menghapus kegiatan.');
        }

        $kegiatan = $this->kegiatanModel->find($id);
        
        if (!$kegiatan || $kegiatan['ukm_id'] != session()->get('ukm_id')) {
            return redirect()->to('/kegiatan')->with('error', 'Kegiatan tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $this->kegiatanModel->delete($id);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Gagal menghapus kegiatan.');
            }

            $db->transCommit();
            return redirect()->to('/kegiatan')->with('success', 'Kegiatan berhasil dihapus.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
