<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleCalendarToken extends Model
{
    use HasFactory;

    protected $table = 'google_calendar_tokens';

    protected $fillable = [
        'user_id',
        'google_id',
        'google_access_token',
        'google_refresh_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
