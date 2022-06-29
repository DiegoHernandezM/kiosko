<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Recipient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'email',
        'inquest_id',
        'answer_id',
        'status',
        'uuid'

    ];

    public function inquest()
    {
        return $this->belongsTo(Inquest::class);
    }
}
