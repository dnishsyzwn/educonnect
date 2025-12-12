<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassAnnouncement extends Model
{
    protected $table = 'class_announcements';

    protected $fillable = [
        'class_id',
        'user_id',
        'title',
        'content',
        'is_pinned',
        'send_notification',
        'status'
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'send_notification' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship with class
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    // Relationship with author
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scope for published announcements
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Scope for pinned announcements
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    // Scope for active (published and not expired)
    public function scopeActive($query)
    {
        return $query->published();
    }
}
