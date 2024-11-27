<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DurasiKonsultasi extends Model
{
    use HasFactory;

    protected $table = 'durasi_konsultasis';

    protected $fillable = [
        'konsultasi',
        'durasi',
    ];
}
