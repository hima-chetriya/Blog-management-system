<?php

namespace App\Models;
use App\Models\Like;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Blog extends Model
{
    use SoftDeletes;
     protected $table = 'blogs';
     protected $fillable = ['id','title', 'description', 'image'];


    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function isLikedBy($user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

}
