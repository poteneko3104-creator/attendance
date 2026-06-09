<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','date_id','start_time', 'end_time', 'category','status'];
    protected $casts = [
    'start_time' => 'datetime',
    'end_time' => 'datetime',
    ];
}
