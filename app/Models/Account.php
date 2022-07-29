<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'fb_id',
        'user_id',
        'name',
        'email',
        'status',
        'access_token'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
