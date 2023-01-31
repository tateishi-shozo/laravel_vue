<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'category',
        'title',
        'user_id'
    ];

    public function users(){
        return $this->belongsToMany('App\User') -> withPivot('comment');
    }
}
