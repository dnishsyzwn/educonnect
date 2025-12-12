<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    protected $table = 'post_likes';

    protected $fillable = [
        'post_id',
        'user_id'
    ];

    // Relationship with post
    public function post()
    {
        return $this->belongsTo(ClassPost::class, 'post_id');
    }

    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
