<?php

namespace App\Controllers;

use App\Models\ProposalKegiatanModel;
use App\Models\KegiatanModel;

class ProposalController extends BaseController
{
    protected $proposalModel;
    protected $kegiatanModel;
    protected $db;

    public function __construct()
    {
        $this->proposalModel = new ProposalKegiatanModel();
        $this->kegiatanModel = new KegiatanModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Mengecek apakah role saat ini memiliki akses
     */
    private function checkRole(array $allowedRoles)
    {
        $roleId = session()->get('role_id');
        return in_array($roleId, $allowedRoles);
    }

    /**
     * Menampilkan daftar proposal
     * Admin UKM (Role 1): hanya melihat milik UKM sendiri
     * Admin Kemahasiswaan (Role 2) & WR3 (Role 3): melihat seluruh proposal
     */
    public function index()
    {
        $roleId = session()->get('role_id');
        $ukmId = session()->get('ukm_id');

        $builder = $this->db->table('proposal_kegiatan pk')
            ->select('pk.*, k.judul, k.ukm_id')
            ->join('kegiatan k', 'k.id = pk.kegiatan_id');

        // Jika login sebagai Admin UKM, filter berdasarkan ukm_id di session
        if ($roleId == 1) {
            $builder->where('k.ukm_id', $ukmId);
        }

        $proposals = $builder->orderBy('pk.created_at', 'DESC')->get()->getResultArray();

        return view('proposal/index', [
            'proposals' => $proposals
        ]);
    }

    /**
     * Menampilkan detail proposal
     */
    public function detail($id)
    {
        $roleId = session()->get('role_id');
        $ukmId = session()->get('ukm_id');

        $proposal = $this->db->table('proposal_kegiatan pk')
            ->select('pk.*, k.judul, k.ukm_id, k.deskripsi, k.tanggal_mulai, k.tanggal_selesai, k.tempat')
            ->join('kegiatan k', 'k.id = pk.kegiatan_id')
            ->where('pk.id', $id)
            ->get()->getRowArray();

        if (!$proposal) {
            return redirect()->back()->with('error', 'Proposal tidak ditemukan.');
        }

        // Jika Admin UKM, pastikan proposal ini miliknya
        if ($roleId == 1 && $proposal['ukm_id'] != $ukmId) {
            return redirect()->to('/proposal')->with('error', 'Akses ditolak.');
        }

        return view('proposal/detail', [
            'proposal' => $proposal
        ]);
    }

    /**
     * Menampilkan form tambah proposal (Khusus Admin UKM)
     */
    public function create()
    {
        if (!$this->checkRole([1])) {
            return redirect()->to('/proposal')->with('error', 'Akses ditolak.');
        }

        // Mengambil data kegiatan milik UKM ini yang belum diajukan (MENUNGGU_PROPOSAL)
        $kegiatans = $this->kegiatanModel
            ->where('ukm_id', session()->get('ukm_id'))
            ->where('status', 'MENUNGGU_PROPOSAL')
            ->findAll();

        return view('proposal/create', [
            'kegiatans' => $kegiatans
        ]);
    }

    /**
     * Menyimpan data proposal baru (Khusus Admin UKM)
     */
    public function store()
    {
        if (!$this->checkRole([1])) {
            return redirect()->to('/proposal')->with('error', 'Akses ditolak.');
        }

        $rules = [
            'kegiatan_id' => 'required|numeric',
            'anggaran' => 'required|numeric',
            'file_proposal' => 'uploaded[file_proposal]|max_size[file_proposal,5120]|ext_in[file_proposal,pdf,doc,docx]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $kegiatanId = $this->request->getPost('kegiatan_id');
        $kegiatan = $this->kegiatanModel->where('id', $kegiatanId)->where('ukm_id', session()->get('ukm_id'))->first();

        if (!$kegiatan) {
            return redirect()->back()->with('error', 'Kegiatan tidak valid.');
        }

        $file = $this->request->getFile('file_proposal');
        $fileName = $file->getRandomName();
        $file->move('uploads/proposals', $fileName);

        $this->db->transBegin();
        try {
            $this->proposalModel->insert([
                'kegiatan_id' => $kegiatanId,
                'file_proposal' => $fileName,
                'anggaran' => $this->request->getPost('anggaran'),
                'status' => 'PENGAJUAN',
                'status_kemahasiswaan' => 'PENDING',
                'status_wakil_rektor_3' => 'PENDING',
            ]);

            $this->db->transCommit();
            return redirect()->to('/proposal')->with('success', 'Proposal berhasil diajukan.');
        } catch (\Exception $e) {
            $this->db->transRollback();
            if (file_exists('uploads/proposals/' . $fileName)) {
                unlink('uploads/proposals/' . $fileName);
            }
            return redirect()->back()->with('error', 'Gagal menyimpan proposal.');
        }
    }

    /**
     * Menampilkan form edit proposal (Khusus Admin UKM)
     */
    public function edit($id)
    {
        if (!$this->checkRole([1])) {
            return redirect()->to('/proposal')->with('error', 'Akses ditolak.');
        }

        $proposal = $this->proposalModel->find($id);
        if (!$proposal) {
            return redirect()->to('/proposal')->with('error', 'Proposal tidak ditemukan.');
        }

        $kegiatan = $this->kegiatanModel->find($proposal['kegiatan_id']);
        if ($kegiatan['ukm_id'] != session()->get('ukm_id')) {
            return redirect()->to('/proposal')->with('error', 'Akses ditolak.');
        }

        $allowedStatus = ['PENGAJUAN', 'REVISI_KEMAHASISWAAN', 'REVISI_WAKIL_REKTOR_3'];
        if (!in_array($proposal['status'], $allowedStatus)) {
            return redirect()->to('/proposal')->with('error', 'Proposal tidak dapat diubah pada status ini.');
        }

        return view('proposal/edit', [
            'proposal' => $proposal
        ]);
    }

    /**
     * Memperbarui data proposal (Khusus Admin UKM)
     */
    public function update($id)
    {
        if (!$this->checkRole([1])) {
            return redirect()->to('/proposal')->with('error', 'Akses ditolak.');
        }

        $proposal = $this->proposalModel->find($id);
        if (!$proposal) {
            return redirect()->to('/proposal')->with('error', 'Proposal tidak ditemukan.');
        }

        $kegiatan = $this->kegiatanModel->find($proposal['kegiatan_id']);
        if ($kegiatan['ukm_id'] != session()->get('ukm_id')) {
            return redirect()->to('/proposal')->with('error', 'Akses ditolak.');
        }

        $allowedStatus = ['PENGAJUAN', 'REVISI_KEMAHASISWAAN', 'REVISI_WAKIL_REKTOR_3'];
        if (!in_array($proposal['status'], $allowedStatus)) {
            return redirect()->to('/proposal')->with('error', 'Proposal tidak dapat diubah pada status ini.');
        }

        $rules = [
            'anggaran' => 'required|numeric',
        ];

        $file = $this->request->getFile('file_proposal');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $rules['file_proposal'] = 'max_size[file_proposal,5120]|ext_in[file_proposal,pdf,doc,docx]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->db->transBegin();
        try {
            $updateData = [
                'anggaran' => $this->request->getPost('anggaran'),
                'status' => 'PENGAJUAN',
                'status_kemahasiswaan' => 'PENDING',
                'status_wakil_rektor_3' => 'PENDING',
            ];

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getRandomName();
                $file->move('uploads/proposals', $fileName);
                $updateData['file_proposal'] = $fileName;

                if ($proposal['file_proposal'] && file_exists('uploads/proposals/' . $proposal['file_proposal'])) {
                    unlink('uploads/proposals/' . $proposal['file_proposal']);
                }
            }

            $this->proposalModel->update($id, $updateData);

            $this->db->transCommit();
            return redirect()->to('/proposal')->with('success', 'Proposal berhasil diperbarui.');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal memperbarui proposal.');
        }
    }

    /**
     * Menghapus proposal (Khusus Admin UKM)
     */
    public function delete($id)
    {
        if (!$this->checkRole([1])) {
            return redirect()->to('/proposal')->with('error', 'Akses ditolak.');
        }

        $proposal = $this->proposalModel->find($id);
        if (!$proposal) {
            return redirect()->to('/proposal')->with('error', 'Proposal tidak ditemukan.');
        }

        $kegiatan = $this->kegiatanModel->find($proposal['kegiatan_id']);
        if ($kegiatan['ukm_id'] != session()->get('ukm_id')) {
            return redirect()->to('/proposal')->with('error', 'Akses ditolak.');
        }

        if ($proposal['status'] !== 'PENGAJUAN') {
            return redirect()->to('/proposal')->with('error', 'Proposal yang sudah diverifikasi tidak dapat dihapus.');
        }

        $this->db->transBegin();
        try {
            $this->proposalModel->delete($id);

            if ($proposal['file_proposal'] && file_exists('uploads/proposals/' . $proposal['file_proposal'])) {
                unlink('uploads/proposals/' . $proposal['file_proposal']);
            }

            $this->db->transCommit();
            return redirect()->to('/proposal')->with('success', 'Proposal berhasil dihapus.');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal menghapus proposal.');
        }
    }

    // =========================================================================
    // WORKFLOW ADMIN KEMAHASISWAAN (Role 2)
    // =========================================================================

    public function approveByKemahasiswaan($id)
    {
        if (!$this->checkRole([2])) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $proposal = $this->proposalModel->find($id);
        if (!$proposal) {
            return redirect()->back()->with('error', 'Proposal tidak ditemukan.');
        }

        $this->db->transBegin();
        try {
            $this->proposalModel->update($id, [
                'status' => 'VERIFIKASI_WAKIL_REKTOR_3',
                'status_kemahasiswaan' => 'APPROVED',
                'verified_by_kemahasiswaan' => session()->get('user_id'),
                'verified_at_kemahasiswaan' => date('Y-m-d H:i:s'),
                'catatan_kemahasiswaan' => null // reset catatan jika sebelumnya direvisi
            ]);

            $this->db->transCommit();
            return redirect()->back()->with('success', 'Proposal disetujui oleh Kemahasiswaan.');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal menyetujui proposal.');
        }
    }

    public function revisiByKemahasiswaan($id)
    {
        if (!$this->checkRole([2])) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        if (!$this->validate(['catatan_kemahasiswaan' => 'required'])) {
            return redirect()->back()->with('error', 'Catatan revisi wajib diisi.');
        }

        $proposal = $this->proposalModel->find($id);
        if (!$proposal) {
            return redirect()->back()->with('error', 'Proposal tidak ditemukan.');
        }

        $this->db->transBegin();
        try {
            $this->proposalModel->update($id, [
                'status' => 'REVISI_KEMAHASISWAAN',
                'status_kemahasiswaan' => 'REVISI',
                'catatan_kemahasiswaan' => $this->request->getPost('catatan_kemahasiswaan'),
                'verified_by_kemahasiswaan' => session()->get('user_id'),
                'verified_at_kemahasiswaan' => date('Y-m-d H:i:s')
            ]);

            $this->db->transCommit();
            return redirect()->back()->with('success', 'Proposal dikembalikan untuk direvisi (Kemahasiswaan).');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal merevisi proposal.');
        }
    }

    public function tolakByKemahasiswaan($id)
    {
        if (!$this->checkRole([2])) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        if (!$this->validate(['catatan_kemahasiswaan' => 'required'])) {
            return redirect()->back()->with('error', 'Catatan penolakan wajib diisi.');
        }

        $proposal = $this->proposalModel->find($id);
        if (!$proposal) {
            return redirect()->back()->with('error', 'Proposal tidak ditemukan.');
        }

        $this->db->transBegin();
        try {
            $this->proposalModel->update($id, [
                'status' => 'DITOLAK',
                'status_kemahasiswaan' => 'DITOLAK',
                'catatan_kemahasiswaan' => $this->request->getPost('catatan_kemahasiswaan'),
                'verified_by_kemahasiswaan' => session()->get('user_id'),
                'verified_at_kemahasiswaan' => date('Y-m-d H:i:s')
            ]);

            $this->db->transCommit();
            return redirect()->back()->with('success', 'Proposal ditolak (Kemahasiswaan).');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal menolak proposal.');
        }
    }

    // =========================================================================
    // WORKFLOW WAKIL REKTOR III (Role 3)
    // =========================================================================

    public function approveByWakilRektor3($id)
    {
        if (!$this->checkRole([3])) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $proposal = $this->proposalModel->find($id);
        if (!$proposal) {
            return redirect()->back()->with('error', 'Proposal tidak ditemukan.');
        }

        if ($proposal['status_kemahasiswaan'] !== 'APPROVED') {
            return redirect()->back()->with('error', 'Proposal harus disetujui Kemahasiswaan terlebih dahulu.');
        }

        $this->db->transBegin();
        try {
            $this->proposalModel->update($id, [
                'status' => 'APPROVED',
                'status_wakil_rektor_3' => 'APPROVED',
                'verified_by_wakil_rektor_3' => session()->get('user_id'),
                'verified_at_wakil_rektor_3' => date('Y-m-d H:i:s'),
                'catatan_wakil_rektor_3' => null // reset catatan jika sebelumnya direvisi
            ]);

            $this->kegiatanModel->update($proposal['kegiatan_id'], [
                'status' => 'PROPOSAL_DISETUJUI'
            ]);

            $this->db->transCommit();
            return redirect()->back()->with('success', 'Proposal disetujui oleh Wakil Rektor III.');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal menyetujui proposal.');
        }
    }

    public function revisiByWakilRektor3($id)
    {
        if (!$this->checkRole([3])) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        if (!$this->validate(['catatan_wakil_rektor_3' => 'required'])) {
            return redirect()->back()->with('error', 'Catatan revisi wajib diisi.');
        }

        $proposal = $this->proposalModel->find($id);
        if (!$proposal) {
            return redirect()->back()->with('error', 'Proposal tidak ditemukan.');
        }

        $this->db->transBegin();
        try {
            $this->proposalModel->update($id, [
                'status' => 'REVISI_WAKIL_REKTOR_3',
                'status_wakil_rektor_3' => 'REVISI',
                'catatan_wakil_rektor_3' => $this->request->getPost('catatan_wakil_rektor_3'),
                'verified_by_wakil_rektor_3' => session()->get('user_id'),
                'verified_at_wakil_rektor_3' => date('Y-m-d H:i:s')
            ]);

            $this->db->transCommit();
            return redirect()->back()->with('success', 'Proposal dikembalikan untuk direvisi (Wakil Rektor III).');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal merevisi proposal.');
        }
    }

    public function tolakByWakilRektor3($id)
    {
        if (!$this->checkRole([3])) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        if (!$this->validate(['catatan_wakil_rektor_3' => 'required'])) {
            return redirect()->back()->with('error', 'Catatan penolakan wajib diisi.');
        }

        $proposal = $this->proposalModel->find($id);
        if (!$proposal) {
            return redirect()->back()->with('error', 'Proposal tidak ditemukan.');
        }

        $this->db->transBegin();
        try {
            $this->proposalModel->update($id, [
                'status' => 'DITOLAK',
                'status_wakil_rektor_3' => 'DITOLAK',
                'catatan_wakil_rektor_3' => $this->request->getPost('catatan_wakil_rektor_3'),
                'verified_by_wakil_rektor_3' => session()->get('user_id'),
                'verified_at_wakil_rektor_3' => date('Y-m-d H:i:s')
            ]);

            $this->db->transCommit();
            return redirect()->back()->with('success', 'Proposal ditolak (Wakil Rektor III).');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal menolak proposal.');
        }
    }
}
