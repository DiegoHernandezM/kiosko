<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Petition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'date',
        'petition_description',
        'comment',
        'approved',
        'associate_id',
        'approved_by',
        'period',
        'comment_by_admin',
        'files',
        'deleted_at'
    ];

    const PETITIONS = [
        1 => 'RETARDO',
        2 => 'FALTA',
        3 => 'VACACIONES',
        4 => 'PERMISO',
        5 => 'OTRO'
    ];


    public function associate()
    {
        return $this->belongsTo(Associate::class);
    }

    public function getPeriodAttribute($value)
    {
        if (gettype($value) !== 'array') {
            return $this->attributes['period'] = json_decode($value);
        }
    }

    public function getPetitionDescriptionAttribute($value)
    {
        return $this->attributes['petition_description'] = mb_strtoupper($value);
    }

    public function setPetitionDescriptionAttribute($value)
    {
        $this->attributes['petition_description'] = mb_strtoupper($value);
    }

    public function getFilesAttribute($value)
    {
        return $this->attributes['files'] = json_decode($value);
    }
}
