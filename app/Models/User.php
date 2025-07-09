<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
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
        'email',
        'password',
    ];
    private $client_id = "test";
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

    public function getCurrentRol()
    {
        $roles = $this->belongsToMany(User::class, 'users_rol', 'users_id', 'users_id')
            ->select('rol.name')
            ->join('rol', 'rol.rol_id', 'users_rol.rol_id')
            ->where('users_rol.users_id', $this->id);
        return $roles;
    }

    public function getClient()
    {
        $client = $this->hasOne(UserClient::class)
            ->select('client.*')
            ->join('client', 'client.client_id', 'user_client.client_id')
            ->where('user_client.client_id', session('client_id'))/* ->toSql() */;
        /* dd($client, session('client_id')); */
        return $client;
    }
}
