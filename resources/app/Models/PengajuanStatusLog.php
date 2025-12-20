<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanStatusLog extends Model
{
    protected $table = 'pengajuan_status_logs';

    protected $primaryKey = 'id_log';

    protected $fillable = [
        'history_id',
        'status',
        'user_role',
        'user_id',
        'catatan',
    ];

    public function history()
    {
        return $this->belongsTo(HistoryPengajuan::class, 'history_id', 'id_history');
    }
}
