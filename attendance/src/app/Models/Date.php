<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'date', 'application', 'remarks', 'status'];
    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
