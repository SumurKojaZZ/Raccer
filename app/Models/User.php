<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Nama tabel default Laravel.
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
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
        ];
    }

    /**
     * Memeriksa apakah user memiliki peran Admin.
     */
    public function isAdmin(): bool
    {
        // 1. Cek langsung atribut role pada instance jika ada
        if (!empty($this->role)) {
            return strcasecmp((string) $this->role, 'Admin') === 0;
        }

        // 2. Cek username
        if (!empty($this->username) && strcasecmp((string) $this->username, 'admin') === 0) {
            return true;
        }

        // 3. Cek email
        if (!empty($this->email) && (strcasecmp((string) $this->email, 'admin@raccer.com') === 0 || str_contains(strtolower((string) $this->email), 'admin'))) {
            return true;
        }

        // 4. Cek tabel pengguna jika tabel tersedia
        try {
            $pengguna = \Illuminate\Support\Facades\DB::table('pengguna')
                ->where('username', $this->username)
                ->first();

            if ($pengguna && isset($pengguna->role)) {
                return strcasecmp((string) $pengguna->role, 'Admin') === 0;
            }
        } catch (\Throwable $e) {
            // Abaikan jika tabel tidak ditemukan
        }

        // 5. Cek name jika mengandung admin
        return !empty($this->name) && str_contains(strtolower((string) $this->name), 'admin');
    }
}
