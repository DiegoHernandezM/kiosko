<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subarea extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'area_id'];

    public function area()
    {
        return $this->hasMany('App\Models\Area', 'id');
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
