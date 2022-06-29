<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission;

class Associate extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $fillable = [
        'id',
        'name',
        'lastnames',
        'entry_date',
        'employee_number',
        'birthday',
        'area_id',
        'subarea_id',
        'user_id',
        'shift_id',
        'vacations_available',
        'deleted_at',
    ];

    const VACATIONS = [
        1 => 10,
        2 => 11,
        3 => 12,
        4 => 13,
        5 => 15,
        6 => 15,
        7 => 15,
        8 => 15,
        9 => 15,
        10 => 17,
        11 => 17,
        12 => 17,
        13 => 17,
        14 => 17,
        15 => 19,
        16 => 19,
        17 => 19,
        18 => 19,
        19 => 19,
        20 => 21,
        21 => 21,
        22 => 21,
        23 => 21,
        24 => 21,
        25 => 23,
        26 => 23,
        27 => 23,
        28 => 23,
        29 => 23,
        30 => 25,
        31 => 25,
        32 => 25,
        33 => 25,
        34 => 25,
        35 => 27,
        36 => 27,
        37 => 27,
        38 => 27,
        39 => 27,
        40 => 29,
        41 => 29,
        42 => 29,
        43 => 29,
        44 => 29,
        45 => 31,
        46 => 31,
        47 => 31,
        48 => 31,
        49 => 31
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = mb_strtoupper($value);
    }

    public function setLastnamesAttribute($value)
    {
        $this->attributes['lastnames'] = mb_strtoupper($value);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function subarea()
    {
        return $this->belongsTo(Subarea::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function objectives()
    {
        return $this->hasMany(Objective::class);
    }
}
