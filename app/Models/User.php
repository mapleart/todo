<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'middle_name',
        'login',
        'password',
        'parent_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function childs() {
        return $this->hasMany('App\Models\User','parent_id','id') ;
    }

    public function toArray()
    {
        $data = parent::toArray();
        $data['avatar'] = "https://avatars.dicebear.com/api/avataaars/{$this->login}.svg";
        $data['display_name'] = $this->name . ' ' . $this->surname;
        return $data;
    }
}
