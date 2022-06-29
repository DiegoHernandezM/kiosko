<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'all_day',
        'textColor',
        'title',
        'description',
        'start',
        'end'
    ];

    public function getAllDayAttribute($value)
    {
        return $this->attributes['all_day'] = (boolean)$value;
    }
}
