<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
    use HasFactory;

    protected $fillable = [
        'associate_id',
        'name',
        'description',
        'weighing',
        'evidence',
        'approved',
        'observation',
        'real_weighing',
        'year',
        'quarter',
        'progress'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = mb_strtoupper($value);
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = mb_strtoupper($value);
    }

    public function getEvidenceAttribute($value)
    {
        return $this->attributes['evidence'] = json_decode($value);
    }

    public function getYearAttribute($value)
    {
        return $this->attributes['year'] = Carbon::parse('12-12-' . $value);
    }

    public function associate()
    {
        return $this->belongsTo(Associate::class);
    }
}
