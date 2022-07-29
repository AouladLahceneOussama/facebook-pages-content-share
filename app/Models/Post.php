<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'page_id',
        'post_page_id',
        'description',
        'media',
        'scheduled',
        'share_date_time',
    ];
    
    protected $casts = [
        'share_date_time'  => 'datetime:Y-m-d\TH:i:s'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function page(){
        return $this->belongsTo(Page::class);
    }
}


