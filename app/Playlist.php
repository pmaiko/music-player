<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'playLists';
    protected $fillable = ['playlist', 'track_id', 'user_id'];

    public $timestamps = false;
}
