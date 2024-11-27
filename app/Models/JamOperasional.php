<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamOperasional extends Model
{
    use HasFactory;

    protected $table = 'jam_operasionals';

    protected $fillable = [
        'tanggal_mulai',
        'tanggal_selesai',
        'hari_mulai',
        'hari_selesai',
        'jam_mulai',
        'jam_selesai',
    ];
}
