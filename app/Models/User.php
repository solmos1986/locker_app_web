<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
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
            'password'          => 'hashed',
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

    public function getCompany()
    {
        $company = $this->hasOne(UserCompany::class, 'users_id')
            ->select('user_company.*')
            //->join('company', 'company.company_id', 'user_company.company_id')
            ->join('user_company', 'user_company.users_id', 'company.users_id')
            ->where('user_company.users_id', $this->id) /* ->toSql() */;
        /* dd($client, session('client_id')); */
        return $$company;
    }

    /**
     * Get the identifier that will be stored in the JWT subject claim.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'id_empresa' => $this->id_empresa,
        ];
    }
}
