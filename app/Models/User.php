<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'time_zone', 'time_format', 'available_time'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function AVAILABLETIME()
    {
        return '{"hours":[{"value":"Sunday","data":[{"start":9,"end":15}],"available":false},{"value":"Monday","data":[{"start":9,"end":15}],"available":true},{"value":"Tuesday","data":[{"start":9,"end":2}],"available":true},{"value":"Wednesday","data":[{"start":9,"end":15}],"available":true},{"value":"Thursday","data":[{"start":9,"end":15}],"available":true},{"value":"Friday","data":[{"start":9,"end":15}],"available":true},{"value":"Saturday","data":[{"start":9,"end":15}],"available":false}]}';
    }

}
