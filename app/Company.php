<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //
    protected $uploads = '/images/';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom', 'logo', 'responsable',
    ];

    //create function to get the directory of the picture
    public function getFileAttribute($logo){
        return $this->uploads .$logo;
    }

}
