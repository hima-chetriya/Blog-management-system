<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
     protected $table = 'likes';
    protected $fillable = ['id','user_id','likeable_type','likeable_id'];

    public function likeable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
