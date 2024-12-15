<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'title',
        'nama_lengkap',
        'perusahaan',
        'jenis_konsultasi',
        'durasi_konsultasi',
        'deskripsi',
        'google_event_id',
        'zoom_link',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
