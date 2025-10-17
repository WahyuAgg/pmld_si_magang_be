<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'email',
        'username',
        'password',
        'role', // constraint roles: admin, mahasiswa, supervisor, dosbing
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function username()
    {
        return 'username'; // agar Auth pakai kolom username
    }

    // 🔗 Relasi
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'user_id', 'user_id');
    }

    public function dataAdmin()
    {
        return $this->hasOne(Admin::class, 'user_id', 'user_id');
    }

    public function supervisor()
    {
        return $this->hasOne(Supervisor::class, 'user_id', 'user_id');
    }
}
