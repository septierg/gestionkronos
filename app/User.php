<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    //i use mysql connection local+pretabc
    //protected $connection = 'mysql';
    protected $connection_kreditpret= 'mysql2';



    public function role(){

        return $this->belongsTo('App\Role');
    }

    public function isAdmin(){
        if($this->role->name == "Administrateur" || $this->role->name == "Employe"){
            return true;
        }
        return false;
    }






}
