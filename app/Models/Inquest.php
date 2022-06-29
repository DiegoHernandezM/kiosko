<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'description',
        'content',
        'expired',
        'deleted_at',
    ];

    public function getContentAttribute($value)
    {
        return $this->attributes['content'] = json_decode($value);
    }

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }
}
