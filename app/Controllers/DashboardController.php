<?php

namespace App\Controllers;

use App\Models\UkmModel;
use App\Models\AnggotaUkmModel;
use App\Models\KegiatanModel;
use App\Models\ProposalKegiatanModel;
use App\Models\LpjKegiatanModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    protected $ukmModel;
    protected $anggotaUkmModel;
    protected $kegiatanModel;
    protected $proposalModel;
    protected $lpjModel;
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->ukmModel = new UkmModel();
        $this->anggotaUkmModel = new AnggotaUkmModel();
        $this->kegiatanModel = new KegiatanModel();
        $this->proposalModel = new ProposalKegiatanModel();
        $this->lpjModel = new LpjKegiatanModel();
        $this->userModel = new UserModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Dashboard untuk Admin UKM (Role 1)
     */
    public function adminDashboard()
    {
        // Cek Role
        if (session()->get('role_id') != 1) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $ukmId = session()->get('ukm_id');

        // Profil UKM
        $profilUkm = $this->ukmModel->find($ukmId);

        // Jumlah Anggota UKM
        $jumlahAnggota = $this->anggotaUkmModel->where('ukm_id', $ukmId)->countAllResults();

        // Jumlah Kegiatan
        $jumlahKegiatan = $this->kegiatanModel->where('ukm_id', $ukmId)->countAllResults();

        // Query Builder untuk Proposal
        $proposalBuilder = $this->db->table('proposal_kegiatan pk')
                                    ->join('kegiatan k', 'k.id = pk.kegiatan_id')
                                    ->where('k.ukm_id', $ukmId);

        // Statistik Proposal
        $jumlahProposal = $proposalBuilder->countAllResults(false); // false agar builder tidak di-reset

        $proposalMenunggu = (clone $proposalBuilder)
                            ->groupStart()
                                ->where('pk.status', 'PENGAJUAN')
                                ->orWhere('pk.status', 'VERIFIKASI_WAKIL_REKTOR_3')
                            ->groupEnd()
                            ->countAllResults();

        $proposalDisetujui = (clone $proposalBuilder)
                             ->where('pk.status', 'APPROVED')
                             ->countAllResults();

        $proposalRevisi = (clone $proposalBuilder)
                          ->groupStart()
                              ->like('pk.status', 'REVISI')
                          ->groupEnd()
                          ->countAllResults();

        // Query Builder untuk LPJ
        $lpjBuilder = $this->db->table('lpj_kegiatan lk')
                               ->join('kegiatan k', 'k.id = lk.kegiatan_id')
                               ->where('k.ukm_id', $ukmId);

        // Statistik LPJ
        $jumlahLpj = $lpjBuilder->countAllResults(false);

        $lpjMenunggu = (clone $lpjBuilder)
                       ->groupStart()
                           ->where('lk.status', 'PENGAJUAN')
                           ->orWhere('lk.status', 'VERIFIKASI_WAKIL_REKTOR_3')
                       ->groupEnd()
                       ->countAllResults();

        $lpjDisetujui = (clone $lpjBuilder)
                        ->where('lk.status', 'APPROVED')
                        ->countAllResults();

        $lpjRevisi = (clone $lpjBuilder)
                     ->groupStart()
                         ->like('lk.status', 'REVISI')
                     ->groupEnd()
                     ->countAllResults();

        // 5 Data Terbaru
        $kegiatanTerbaru = $this->kegiatanModel->where('ukm_id', $ukmId)
                                               ->orderBy('created_at', 'DESC')
                                               ->findAll(5);

        $proposalTerbaru = $this->db->table('proposal_kegiatan pk')
                                    ->select('pk.*, k.judul as nama_kegiatan')
                                    ->join('kegiatan k', 'k.id = pk.kegiatan_id')
                                    ->where('k.ukm_id', $ukmId)
                                    ->orderBy('pk.created_at', 'DESC')
                                    ->limit(5)
                                    ->get()->getResultArray();

        $lpjTerbaru = $this->db->table('lpj_kegiatan lk')
                               ->select('lk.*, k.judul as nama_kegiatan')
                               ->join('kegiatan k', 'k.id = lk.kegiatan_id')
                               ->where('k.ukm_id', $ukmId)
                               ->orderBy('lk.created_at', 'DESC')
                               ->limit(5)
                               ->get()->getResultArray();

        $data = [
            'profil_ukm' => $profilUkm,
            'jumlah_anggota' => $jumlahAnggota,
            'jumlah_kegiatan' => $jumlahKegiatan,
            'jumlah_proposal' => $jumlahProposal,
            'proposal_menunggu' => $proposalMenunggu,
            'proposal_disetujui' => $proposalDisetujui,
            'proposal_revisi' => $proposalRevisi,
            'jumlah_lpj' => $jumlahLpj,
            'lpj_menunggu' => $lpjMenunggu,
            'lpj_disetujui' => $lpjDisetujui,
            'lpj_revisi' => $lpjRevisi,
            'kegiatan_terbaru' => $kegiatanTerbaru,
            'proposal_terbaru' => $proposalTerbaru,
            'lpj_terbaru' => $lpjTerbaru,
        ];

        return view('dashboard/admin_ukm', $data);
    }

    /**
     * Dashboard untuk Admin Kemahasiswaan (Role 2)
     */
    public function kemahasiswaanDashboard()
    {
        // Cek Role
        if (session()->get('role_id') != 2) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        // Total
        $totalUkm = $this->ukmModel->countAllResults();
        $totalUser = $this->userModel->countAllResults();
        $totalAnggota = $this->anggotaUkmModel->countAllResults();
        $totalKegiatan = $this->kegiatanModel->countAllResults();

        // Statistik Proposal Kemahasiswaan
        $proposalMenunggu = $this->proposalModel->where('status_kemahasiswaan', 'PENDING')->countAllResults();
        $proposalDisetujui = $this->proposalModel->where('status_kemahasiswaan', 'APPROVED')->countAllResults();
        $proposalRevisi = $this->proposalModel->where('status_kemahasiswaan', 'REVISI')->countAllResults();

        // Statistik LPJ Kemahasiswaan
        $lpjMenunggu = $this->lpjModel->where('status_kemahasiswaan', 'PENDING')->countAllResults();
        $lpjDisetujui = $this->lpjModel->where('status_kemahasiswaan', 'APPROVED')->countAllResults();
        $lpjRevisi = $this->lpjModel->where('status_kemahasiswaan', 'REVISI')->countAllResults();

        // 10 Data Terbaru
        $proposalTerbaru = $this->db->table('proposal_kegiatan pk')
                                    ->select('pk.*, k.judul as nama_kegiatan, u.nama_ukm')
                                    ->join('kegiatan k', 'k.id = pk.kegiatan_id')
                                    ->join('ukm u', 'u.id = k.ukm_id')
                                    ->orderBy('pk.created_at', 'DESC')
                                    ->limit(10)
                                    ->get()->getResultArray();

        $lpjTerbaru = $this->db->table('lpj_kegiatan lk')
                               ->select('lk.*, k.judul as nama_kegiatan, u.nama_ukm')
                               ->join('kegiatan k', 'k.id = lk.kegiatan_id')
                               ->join('ukm u', 'u.id = k.ukm_id')
                               ->orderBy('lk.created_at', 'DESC')
                               ->limit(10)
                               ->get()->getResultArray();

        $kegiatanTerbaru = $this->db->table('kegiatan k')
                                    ->select('k.*, u.nama_ukm')
                                    ->join('ukm u', 'u.id = k.ukm_id')
                                    ->orderBy('k.created_at', 'DESC')
                                    ->limit(10)
                                    ->get()->getResultArray();

        $data = [
            'total_ukm' => $totalUkm,
            'total_user' => $totalUser,
            'total_anggota' => $totalAnggota,
            'total_kegiatan' => $totalKegiatan,
            'proposal_menunggu' => $proposalMenunggu,
            'proposal_disetujui' => $proposalDisetujui,
            'proposal_revisi' => $proposalRevisi,
            'lpj_menunggu' => $lpjMenunggu,
            'lpj_disetujui' => $lpjDisetujui,
            'lpj_revisi' => $lpjRevisi,
            'proposal_terbaru' => $proposalTerbaru,
            'lpj_terbaru' => $lpjTerbaru,
            'kegiatan_terbaru' => $kegiatanTerbaru,
        ];

        return view('dashboard/kemahasiswaan', $data);
    }

    /**
     * Dashboard untuk Wakil Rektor III (Role 3)
     */
    public function wakilRektor3Dashboard()
    {
        // Cek Role
        if (session()->get('role_id') != 3) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        // Total
        $totalUkm = $this->ukmModel->countAllResults();
        $totalKegiatan = $this->kegiatanModel->countAllResults();

        // Statistik Proposal Wakil Rektor III
        // Biasanya menunggu persetujuan WR3 itu statusnya VERIFIKASI_WAKIL_REKTOR_3
        $proposalMenunggu = $this->proposalModel->where('status', 'VERIFIKASI_WAKIL_REKTOR_3')
                                                ->where('status_wakilrektor3', 'PENDING')
                                                ->countAllResults();
        $proposalDisetujui = $this->proposalModel->where('status_wakilrektor3', 'APPROVED')->countAllResults();
        $proposalRevisi = $this->proposalModel->where('status_wakilrektor3', 'REVISI')->countAllResults();

        // Statistik LPJ Wakil Rektor III
        $lpjMenunggu = $this->lpjModel->where('status', 'VERIFIKASI_WAKIL_REKTOR_3')
                                      ->where('status_wakilrektor3', 'PENDING')
                                      ->countAllResults();
        $lpjDisetujui = $this->lpjModel->where('status_wakilrektor3', 'APPROVED')->countAllResults();
        $lpjRevisi = $this->lpjModel->where('status_wakilrektor3', 'REVISI')->countAllResults();

        // 10 Data Terbaru (Mungkin yang relevan sudah sampai tahap WR3, tapi kita tampilkan global)
        $proposalTerbaru = $this->db->table('proposal_kegiatan pk')
                                    ->select('pk.*, k.judul as nama_kegiatan, u.nama_ukm')
                                    ->join('kegiatan k', 'k.id = pk.kegiatan_id')
                                    ->join('ukm u', 'u.id = k.ukm_id')
                                    ->where('pk.status_kemahasiswaan', 'APPROVED') // sudah masuk radar WR3
                                    ->orderBy('pk.created_at', 'DESC')
                                    ->limit(10)
                                    ->get()->getResultArray();

        $lpjTerbaru = $this->db->table('lpj_kegiatan lk')
                               ->select('lk.*, k.judul as nama_kegiatan, u.nama_ukm')
                               ->join('kegiatan k', 'k.id = lk.kegiatan_id')
                               ->join('ukm u', 'u.id = k.ukm_id')
                               ->where('lk.status_kemahasiswaan', 'APPROVED') // sudah masuk radar WR3
                               ->orderBy('lk.created_at', 'DESC')
                               ->limit(10)
                               ->get()->getResultArray();

        $data = [
            'total_ukm' => $totalUkm,
            'total_kegiatan' => $totalKegiatan,
            'proposal_menunggu' => $proposalMenunggu,
            'proposal_disetujui' => $proposalDisetujui,
            'proposal_revisi' => $proposalRevisi,
            'lpj_menunggu' => $lpjMenunggu,
            'lpj_disetujui' => $lpjDisetujui,
            'lpj_revisi' => $lpjRevisi,
            'proposal_terbaru' => $proposalTerbaru,
            'lpj_terbaru' => $lpjTerbaru,
        ];

        return view('dashboard/wakil_rektor_3', $data);
    }
}
