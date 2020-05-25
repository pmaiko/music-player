<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UsersTable extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'users';
    protected $fillable = array('name', 'surname', 'mail', 'password', 'image', 'api_token');

    //public $timestamps = false;

    protected $hidden = [
        'password', 'remember_token',
    ];
}
