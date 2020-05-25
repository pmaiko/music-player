<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'tracks';
    protected $fillable = ['src', 'name_track', 'user_id'];
//    protected $fillable = ['src', 'name_track', 'playlist', 'user_id'];
    protected $casts = [
        'playlist' => 'array'
    ];

    public $timestamps = false;
}
