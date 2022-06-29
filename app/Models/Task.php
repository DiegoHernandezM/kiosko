<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'task_description',
        'files',
        'task_day',
        'associate_id',
        'status',
        'hours',
        'deleted_at'
    ];

    const INTIME = 1;
    const DELAYED = 2;
    const OUTTIME = 3;

    const STATUS = [
        1 => 'EN TIEMPO',
        2 => 'FUERA DE TIEMPO'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = mb_strtoupper($value);
    }

    public function getFilesAttribute($value)
    {
        return $this->attributes['files'] = json_decode($value);
    }

    public function associate()
    {
        return $this->belongsTo(Associate::class);
    }
}
