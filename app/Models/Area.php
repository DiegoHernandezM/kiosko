<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    public function associates()
    {
        return $this->hasMany('App\Models\Associate', 'associate_id');
    }

    public function subareas()
    {
        return $this->hasMany('App\Models\Subarea', 'area_id');
    }

    public function getNameAttribute($value)
    {
        return $this->attributes['name'] = mb_strtoupper($value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = mb_strtoupper($value);
    }
}
