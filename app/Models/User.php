<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
    'name',
    'email',
    'password',
    'username',
    'icno',
    'telno',
    'address',
    'postcode',
    'state',
    'profile_image' // Add this
];

    protected $hidden = [
        'password',
        'remember_token',
        'device_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship with organizations
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_user')
            ->withPivot('role_id')
            ->withTimestamps(false);
    }

    // Relationship with organization roles
    public function role()
    {
        return $this->belongsTo(OrganizationRole::class, 'role_id', 'id', 'organization_user');
    }

    // Relationship with classes (as member) - REMOVED role from withPivot
    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'class_user', 'user_id', 'class_id')
            ->withTimestamps(); // No role column
    }

    /**
     * Check if user can join a class
     * Now always returns true (remove organization check)
     */
    public function canJoinClass($classId)
    {
        // Simply check if not already a member
        return !$this->classes()->where('class_id', $classId)->exists();
    }

    // Relationship with posts created
    public function posts()
    {
        return $this->hasMany(ClassPost::class, 'user_id');
    }

    // Relationship with comments
    public function comments()
    {
        return $this->hasMany(PostComment::class, 'user_id');
    }

    // Relationship with likes
    public function likes()
    {
        return $this->hasMany(PostLike::class, 'user_id');
    }
}
