<?php

namespace App\Controllers;

use App\Models\LpjKegiatanModel;
use App\Models\KegiatanModel;

class LPJKegiatanController extends BaseController
{
    protected $lpjModel;
    protected $kegiatanModel;
    protected $db;

    public function __construct()
    {
        // Sesuaikan nama Model dengan model yang sudah Anda buat
        $this->lpjModel = new LpjKegiatanModel();
        $this->kegiatanModel = new KegiatanModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Menampilkan daftar LPJ berdasarkan role
     */
    public function index()
    {
        $roleId = session('role_id');
        
        $builder = $this->db->table('lpj_kegiatan');
        $builder->select('lpj_kegiatan.*, kegiatan.judul as nama_kegiatan, kegiatan.ukm_id');
        $builder->join('kegiatan', 'kegiatan.id = lpj_kegiatan.kegiatan_id');
        
        // Admin UKM hanya melihat data LPJ milik UKM sendiri
        if ($roleId == 1) {
            $builder->where('kegiatan.ukm_id', session('ukm_id'));
        }
        
        $lpj = $builder->get()->getResultArray();
        
        $data = [
            'title' => 'Daftar LPJ Kegiatan',
            'lpj' => $lpj
        ];
        
        return view('lpj/index', $data);
    }

    /**
     * Menampilkan detail LPJ
     */
    public function show($id)
    {
        $roleId = session('role_id');
        
        $builder = $this->db->table('lpj_kegiatan');
        $builder->select('lpj_kegiatan.*, kegiatan.judul as nama_kegiatan, kegiatan.ukm_id, kegiatan.status as status_kegiatan');
        $builder->join('kegiatan', 'kegiatan.id = lpj_kegiatan.kegiatan_id');
        $builder->where('lpj_kegiatan.id', $id);
        
        $lpj = $builder->get()->getRowArray();
        
        if (!$lpj) {
            return redirect()->back()->with('error', 'Data LPJ tidak ditemukan.');
        }
        
        // Pengecekan hak akses Admin UKM
        if ($roleId == 1 && $lpj['ukm_id'] != session('ukm_id')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke LPJ ini.');
        }
        
        $data = [
            'title' => 'Detail LPJ Kegiatan',
            'lpj' => $lpj
        ];
        
        return view('lpj/show', $data);
    }

    /**
     * Form pengajuan LPJ baru
     */
    public function create()
    {
        if (session('role_id') != 1) {
            return redirect()->back()->with('error', 'Hanya Admin UKM yang dapat membuat LPJ.');
        }
        
        $kegiatan = $this->db->table('kegiatan')
            ->where('ukm_id', session('ukm_id'))
            ->whereIn('status', ['MENUNGGU_LPJ', 'SEDANG_BERJALAN']) 
            ->get()->getResultArray();
            
        $data = [
            'title' => 'Tambah LPJ Kegiatan',
            'kegiatan' => $kegiatan
        ];
        
        return view('lpj/create', $data);
    }

    /**
     * Proses simpan LPJ
     */
    public function store()
    {
        if (session('role_id') != 1) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        
        $rules = [
            'kegiatan_id' => 'required',
            'realisasi' => 'required',
            'file_lpj' => 'uploaded[file_lpj]|max_size[file_lpj,5120]|ext_in[file_lpj,pdf]',
            'bukti_file' => 'uploaded[bukti_file]|max_size[bukti_file,5120]|ext_in[bukti_file,pdf,jpg,jpeg,png]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Validasi kepemilikan kegiatan
        $kegiatan = $this->kegiatanModel->find($this->request->getPost('kegiatan_id'));
        if (!$kegiatan || $kegiatan['ukm_id'] != session('ukm_id')) {
            return redirect()->back()->with('error', 'Kegiatan tidak valid.');
        }
        
        $this->db->transBegin();
        
        try {
            $fileLpj = $this->request->getFile('file_lpj');
            $namaFileLpj = $fileLpj->getRandomName();
            $fileLpj->move('uploads/lpj', $namaFileLpj);
            
            $fileBukti = $this->request->getFile('bukti_file');
            $namaFileBukti = $fileBukti->getRandomName();
            $fileBukti->move('uploads/bukti', $namaFileBukti);
            
            $data = [
                'kegiatan_id' => $this->request->getPost('kegiatan_id'),
                'realisasi' => $this->request->getPost('realisasi'),
                'file_lpj' => $namaFileLpj,
                'bukti_file' => $namaFileBukti,
                'status' => 'PENGAJUAN',
                'status_kemahasiswaan' => 'PENDING',
                'status_wakilrektor3' => 'PENDING',
            ];
            
            $this->lpjModel->insert($data);
            
            $this->db->transCommit();
            return redirect()->to('/lpj')->with('success', 'LPJ berhasil diajukan.');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Form edit LPJ
     */
    public function edit($id)
    {
        if (session('role_id') != 1) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        
        $lpj = $this->db->table('lpj_kegiatan')
            ->select('lpj_kegiatan.*, kegiatan.ukm_id')
            ->join('kegiatan', 'kegiatan.id = lpj_kegiatan.kegiatan_id')
            ->where('lpj_kegiatan.id', $id)
            ->get()->getRowArray();
            
        if (!$lpj || $lpj['ukm_id'] != session('ukm_id')) {
            return redirect()->back()->with('error', 'Data LPJ tidak ditemukan atau akses ditolak.');
        }
        
        if (!in_array($lpj['status'], ['PENGAJUAN', 'REVISI_KEMAHASISWAAN', 'REVISI_WAKIL_REKTOR_3'])) {
            return redirect()->back()->with('error', 'LPJ tidak dapat diubah karena sedang dalam proses verifikasi atau sudah disetujui.');
        }
        
        $kegiatan = $this->db->table('kegiatan')
            ->where('ukm_id', session('ukm_id'))
            ->get()->getResultArray();
            
        $data = [
            'title' => 'Edit LPJ Kegiatan',
            'lpj' => $lpj,
            'kegiatan' => $kegiatan
        ];
        
        return view('lpj/edit', $data);
    }

    /**
     * Proses update LPJ
     */
    public function update($id)
    {
        if (session('role_id') != 1) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        
        $lpj = $this->db->table('lpj_kegiatan')
            ->select('lpj_kegiatan.*, kegiatan.ukm_id')
            ->join('kegiatan', 'kegiatan.id = lpj_kegiatan.kegiatan_id')
            ->where('lpj_kegiatan.id', $id)
            ->get()->getRowArray();
            
        if (!$lpj || $lpj['ukm_id'] != session('ukm_id')) {
            return redirect()->back()->with('error', 'Data LPJ tidak ditemukan atau akses ditolak.');
        }
        
        if (!in_array($lpj['status'], ['PENGAJUAN', 'REVISI_KEMAHASISWAAN', 'REVISI_WAKIL_REKTOR_3'])) {
            return redirect()->back()->with('error', 'LPJ tidak dapat diubah karena sedang dalam proses verifikasi atau sudah disetujui.');
        }
        
        $rules = [
            'kegiatan_id' => 'required',
            'realisasi' => 'required',
            'file_lpj' => 'max_size[file_lpj,5120]|ext_in[file_lpj,pdf]',
            'bukti_file' => 'max_size[bukti_file,5120]|ext_in[bukti_file,pdf,jpg,jpeg,png]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $this->db->transBegin();
        
        try {
            $data = [
                'kegiatan_id' => $this->request->getPost('kegiatan_id'),
                'realisasi' => $this->request->getPost('realisasi'),
                'status' => 'PENGAJUAN', // Status direset karena ada perbaikan
                'status_kemahasiswaan' => 'PENDING',
                'status_wakilrektor3' => 'PENDING'
            ];
            
            $fileLpj = $this->request->getFile('file_lpj');
            if ($fileLpj && $fileLpj->isValid() && !$fileLpj->hasMoved()) {
                $namaFileLpj = $fileLpj->getRandomName();
                $fileLpj->move('uploads/lpj', $namaFileLpj);
                $data['file_lpj'] = $namaFileLpj;
            }
            
            $fileBukti = $this->request->getFile('bukti_file');
            if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
                $namaFileBukti = $fileBukti->getRandomName();
                $fileBukti->move('uploads/bukti', $namaFileBukti);
                $data['bukti_file'] = $namaFileBukti;
            }
            
            $this->lpjModel->update($id, $data);
            
            $this->db->transCommit();
            return redirect()->to('/lpj')->with('success', 'LPJ berhasil diperbarui.');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Proses hapus LPJ
     */
    public function delete($id)
    {
        if (session('role_id') != 1) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        
        $lpj = $this->db->table('lpj_kegiatan')
            ->select('lpj_kegiatan.*, kegiatan.ukm_id')
            ->join('kegiatan', 'kegiatan.id = lpj_kegiatan.kegiatan_id')
            ->where('lpj_kegiatan.id', $id)
            ->get()->getRowArray();
            
        if (!$lpj || $lpj['ukm_id'] != session('ukm_id')) {
            return redirect()->back()->with('error', 'Data LPJ tidak ditemukan atau akses ditolak.');
        }
        
        if (!in_array($lpj['status'], ['PENGAJUAN', 'REVISI_KEMAHASISWAAN', 'REVISI_WAKIL_REKTOR_3'])) {
            return redirect()->back()->with('error', 'LPJ tidak dapat dihapus karena sudah dalam proses verifikasi atau disetujui.');
        }
        
        $this->db->transBegin();
        
        try {
            $this->lpjModel->delete($id);
            $this->db->transCommit();
            return redirect()->to('/lpj')->with('success', 'LPJ berhasil dihapus.');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Approve LPJ oleh Admin Kemahasiswaan
     */
    public function approveByKemahasiswaan($id)
    {
        if (session('role_id') != 2) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        
        $lpj = $this->lpjModel->find($id);
        if (!$lpj) {
            return redirect()->back()->with('error', 'Data LPJ tidak ditemukan.');
        }
        
        $this->db->transBegin();
        try {
            $this->lpjModel->update($id, [
                'status' => 'VERIFIKASI_WAKIL_REKTOR_3',
                'status_kemahasiswaan' => 'APPROVED',
                'verified_by_kemahasiswaan' => session('user_id'),
                'verified_at_kemahasiswaan' => date('Y-m-d H:i:s')
            ]);
            $this->db->transCommit();
            return redirect()->back()->with('success', 'LPJ disetujui dan diteruskan ke Wakil Rektor III.');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal memproses persetujuan: ' . $e->getMessage());
        }
    }

    /**
     * Revisi LPJ oleh Admin Kemahasiswaan
     */
    public function revisiByKemahasiswaan($id)
    {
        if (session('role_id') != 2) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        
        $rules = [
            'catatan_kemahasiswaan' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Catatan revisi wajib diisi.');
        }
        
        $this->db->transBegin();
        try {
            $this->lpjModel->update($id, [
                'status' => 'REVISI_KEMAHASISWAAN',
                'status_kemahasiswaan' => 'REVISI',
                'catatan_kemahasiswaan' => $this->request->getPost('catatan_kemahasiswaan')
            ]);
            $this->db->transCommit();
            return redirect()->back()->with('success', 'Status LPJ diubah menjadi Revisi.');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal memproses revisi: ' . $e->getMessage());
        }
    }

    /**
     * Tolak LPJ oleh Admin Kemahasiswaan
     */
    public function tolakByKemahasiswaan($id)
    {
        if (session('role_id') != 2) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        
        $rules = [
            'catatan_kemahasiswaan' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Catatan penolakan wajib diisi.');
        }
        
        $this->db->transBegin();
        try {
            $this->lpjModel->update($id, [
                'status' => 'DITOLAK',
                'status_kemahasiswaan' => 'DITOLAK',
                'catatan_kemahasiswaan' => $this->request->getPost('catatan_kemahasiswaan')
            ]);
            $this->db->transCommit();
            return redirect()->back()->with('success', 'LPJ telah ditolak.');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal memproses penolakan: ' . $e->getMessage());
        }
    }

    /**
     * Approve LPJ oleh Wakil Rektor III
     */
    public function approveByWakilRektor3($id)
    {
        if (session('role_id') != 3) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        
        $lpj = $this->lpjModel->find($id);
        if (!$lpj) {
            return redirect()->back()->with('error', 'Data LPJ tidak ditemukan.');
        }
        
        $this->db->transBegin();
        try {
            $this->lpjModel->update($id, [
                'status' => 'APPROVED',
                'status_wakilrektor3' => 'APPROVED',
                'verified_by_wakilrektor3' => session('user_id'),
                'verified_at_wakilrektor3' => date('Y-m-d H:i:s')
            ]);
            
            // Update status kegiatan menjadi SELESAI
            $this->kegiatanModel->update($lpj['kegiatan_id'], [
                'status' => 'SELESAI'
            ]);
            
            $this->db->transCommit();
            return redirect()->back()->with('success', 'LPJ disetujui, kegiatan dinyatakan Selesai.');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal memproses persetujuan: ' . $e->getMessage());
        }
    }

    /**
     * Revisi LPJ oleh Wakil Rektor III
     */
    public function revisiByWakilRektor3($id)
    {
        if (session('role_id') != 3) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        
        $rules = [
            'catatan_wakilrektor3' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Catatan revisi wajib diisi.');
        }
        
        $this->db->transBegin();
        try {
            $this->lpjModel->update($id, [
                'status' => 'REVISI_WAKIL_REKTOR_3',
                'status_wakilrektor3' => 'REVISI',
                'catatan_wakilrektor3' => $this->request->getPost('catatan_wakilrektor3')
            ]);
            $this->db->transCommit();
            return redirect()->back()->with('success', 'Status LPJ diubah menjadi Revisi.');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal memproses revisi: ' . $e->getMessage());
        }
    }

    /**
     * Tolak LPJ oleh Wakil Rektor III
     */
    public function tolakByWakilRektor3($id)
    {
        if (session('role_id') != 3) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        
        $rules = [
            'catatan_wakilrektor3' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Catatan penolakan wajib diisi.');
        }
        
        $this->db->transBegin();
        try {
            $this->lpjModel->update($id, [
                'status' => 'DITOLAK',
                'status_wakilrektor3' => 'DITOLAK',
                'catatan_wakilrektor3' => $this->request->getPost('catatan_wakilrektor3')
            ]);
            $this->db->transCommit();
            return redirect()->back()->with('success', 'LPJ telah ditolak.');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal memproses penolakan: ' . $e->getMessage());
        }
    }

    /**
     * Route wrapper for Approve LPJ
     */
    public function approve($id)
    {
        $roleId = session()->get('role_id');
        if ($roleId == 2) {
            return $this->approveByKemahasiswaan($id);
        } elseif ($roleId == 3) {
            return $this->approveByWakilRektor3($id);
        }
        return redirect()->back()->with('error', 'Akses ditolak.');
    }

    /**
     * Route wrapper for Revisi LPJ
     */
    public function revisi($id)
    {
        $roleId = session()->get('role_id');
        if ($roleId == 2) {
            return $this->revisiByKemahasiswaan($id);
        } elseif ($roleId == 3) {
            return $this->revisiByWakilRektor3($id);
        }
        return redirect()->back()->with('error', 'Akses ditolak.');
    }

    /**
     * Route wrapper for Tolak LPJ
     */
    public function tolak($id)
    {
        $roleId = session()->get('role_id');
        if ($roleId == 2) {
            return $this->tolakByKemahasiswaan($id);
        } elseif ($roleId == 3) {
            return $this->tolakByWakilRektor3($id);
        }
        return redirect()->back()->with('error', 'Akses ditolak.');
    }
}
