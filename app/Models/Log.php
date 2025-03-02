<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Log extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip',
        'device_type',
        'platform'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
