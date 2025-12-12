<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    protected $table = 'post_comments';

    protected $fillable = [
        'post_id',
        'user_id',
        'content'
    ];

    // Relationship with post
    public function post()
    {
        return $this->belongsTo(ClassPost::class, 'post_id');
    }

    // Relationship with author
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
