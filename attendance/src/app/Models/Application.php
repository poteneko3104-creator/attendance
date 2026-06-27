<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','date_id','application_date', 'approved_date', 'status'];
    public function date(){ 
                return $this->belongsTo(Date::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    }

