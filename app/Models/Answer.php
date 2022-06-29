<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Answer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'content',
        'comments',
        'deleted_at',
    ];

    public function getContentAttribute($value)
    {
        return $this->attributes['content'] = json_decode($value);
    }
}


