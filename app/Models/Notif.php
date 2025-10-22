<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notif extends Model
{
    /** @use HasFactory<\Database\Factories\NotifFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_id',
        'foto',
        'nama',
        'slug',
        'pesan',
        'is_read',
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
