<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'code',
        'email',
        'nama',
        'telno',
        'address',
        'postcode',
        'state',
        'district',
        'city',
        'organization_picture',
        'type_org',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship with users through organization_user table
    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_user')
            ->withPivot('role_id')
            ->withTimestamps(false);
    }

    // FIX THIS: Relationship with classes through class_organization table
    public function classes()
{
    return $this->belongsToMany(ClassModel::class, 'class_organization', 'organization_id', 'class_id')
        ->withPivot('start_date', 'organ_user_id', 'updated_at');
}

    // Count methods
    public function getUsersCountAttribute()
    {
        if (array_key_exists('users_count', $this->attributes)) {
            return $this->attributes['users_count'];
        }
        return $this->users()->count();
    }

    public function getClassesCountAttribute()
    {
        if (array_key_exists('classes_count', $this->attributes)) {
            return $this->attributes['classes_count'];
        }
        return $this->classes()->count();
    }
}
