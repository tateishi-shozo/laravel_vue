<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'category',
        'read_flg',
        'title',
        'ecvaluation',
        'conclude',
        'image',
    ];
}
