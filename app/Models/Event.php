<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';
    
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'title',
        'nama_lengkap',
        'perusahaan',
        'jenis_konsultasi',
        'deskripsi',
        'google_event_id',
        'zoom_link',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
