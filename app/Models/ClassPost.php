<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassPost extends Model
{
    protected $table = 'class_posts';

    protected $fillable = [
        'class_id',
        'user_id',
        'content',
        'media_url',
        'media_type'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship with class - make sure foreign key is correct
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    // Relationship with author
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship with comments
    public function comments()
    {
        return $this->hasMany(PostComment::class, 'post_id');
    }

    // Relationship with likes
    public function likes()
    {
        return $this->hasMany(PostLike::class, 'post_id');
    }

    // Check if user liked this post
    public function isLikedBy($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    // Get likes count
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    // Get comments count
    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }
}
