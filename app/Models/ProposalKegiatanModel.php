<?php

namespace App\Models;

use CodeIgniter\Model;

class ProposalKegiatanModel extends Model
{
    protected $table            = 'proposal_kegiatan';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'kegiatan_id',
        'file_proposal',
        'anggaran',
        'status',
        'status_kemahasiswaan',
        'catatan_kemahasiswaan',
        'verified_by_kemahasiswaan',
        'verified_at_kemahasiswaan',
        'status_wakilrektor3',
        'catatan_wakilrektor3',
        'verified_by_wakilrektor3',
        'verified_at_wakilrektor3'
    ];
}
