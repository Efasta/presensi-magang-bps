<?php

namespace App\Models;

use App\Models\Notif;
use App\Models\Fungsi;
use App\Models\Status;
use App\Models\Absensi;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'nim',
        'jurusan',
        'universitas',
        'email',
        'telepon',
        'alamat',
        'tanggal_masuk',
        'tanggal_keluar',
        'jenis_kelamin',
        'keahlian',
        'password',
        'foto',
        'fungsi_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'tanggal_masuk' => 'date',
            'tanggal_keluar' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($user) {
            do {
                $slug = Str::slug($user->name) . '-' . uniqid();
            } while (static::where('slug', $slug)->exists());

            $user->slug = $slug;
        });
    }

    protected $with = ['status', 'fungsi'];

    public function notifs(): HasMany
    {
        return $this->hasMany(Notif::class);
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function fungsi(): BelongsTo
    {
        return $this->belongsTo(Fungsi::class);
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        // Filter berdasarkan nama
        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        });

        // Filter berdasarkan status (relasi)
        if (!empty($filters['status']) && is_array($filters['status'])) {
            $query->whereHas('absensis.status', function ($q) use ($filters) {
                $q->whereIn('nama', $filters['status']);
            });
        }

        // Filter berdasarkan fungsi (relasi)
        if (!empty($filters['fungsi']) && is_array($filters['fungsi'])) {
            $query->whereHas('fungsi', function ($q) use ($filters) {
                $q->whereIn('nama', $filters['fungsi']);
            });
        }

        // Filter berdasarkan jenis kelamin (kolom langsung)
        if (!empty($filters['jenis_kelamin']) && is_array($filters['jenis_kelamin'])) {
            $query->whereIn('jenis_kelamin', $filters['jenis_kelamin']);
        }
    }
}
