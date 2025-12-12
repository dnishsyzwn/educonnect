<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ClassModel extends Model
{
    protected $table = 'classes';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'levelid',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    // Relationship with organizations through class_organization table
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'class_organization', 'class_id', 'organization_id')
            ->withPivot(['start_date', 'organ_user_id', 'updated_at']);
    }

    // Get the primary organization (first organization if multiple)
    public function organization()
    {
        return $this->organizations()->first();
    }

    // Relationship with owner (teacher/creator)
    public function owner()
    {
        return $this->belongsTo(User::class, 'organ_user_id');
    }

    // Relationship with members (students)
    public function members()
    {
        return $this->belongsToMany(User::class, 'class_user', 'class_id', 'user_id')
            ->withTimestamps(); // No role column in pivot
    }

    // Relationship with posts
    public function posts()
    {
        return $this->hasMany(ClassPost::class, 'class_id');
    }

    // Check membership
    public function isMember($userId = null)
    {
        if (!$userId) {
            $userId = auth()->id();
        }

        if (!$userId) {
            return false;
        }

        return $this->members()->where('user_id', $userId)->exists();
    }

    // Check if user can join this class
    public function canJoin($userId)
    {
        // Only check if not already a member
        return !$this->isMember($userId);
    }

    /**
     * Check if user can post (owner/teacher/admin can post)
     * Admin check is done via organization_user.role_id (Superadmin/Admin roles)
     */
    public function canPost($userId)
    {
        // Check if user is the class owner/teacher (organ_user_id)
        if ($this->isClassTeacher($userId)) {
            return true;
        }

        // Check if user is an organization admin/superadmin (role_id 1 or 2)
        if ($this->isOrganizationAdmin($userId)) {
            return true;
        }

        // Regular members can also post
        return $this->isMember($userId);
    }

    /**
     * Check if user is class teacher/owner (organ_user_id)
     */
    public function isClassTeacher($userId) // Changed from private to public
    {
        $orgData = DB::table('class_organization')
            ->where('class_id', $this->id)
            ->first();

        if (!$orgData) {
            return false;
        }

        // Get the organization_user_id from class_organization
        $organUserId = $orgData->organ_user_id;

        // Check if this organ_user_id corresponds to the user
        $userOrgRecord = DB::table('organization_user')
            ->where('id', $organUserId) // This is the organization_user.id, not user_id
            ->where('user_id', $userId)
            ->first();

        return $userOrgRecord !== null;
    }

    /**
     * Check if user is organization admin/superadmin (role_id 1 or 2)
     */
    public function isOrganizationAdmin($userId) // Changed from private to public
    {
        // Get organization ID from class_organization
        $orgData = DB::table('class_organization')
            ->where('class_id', $this->id)
            ->first();

        if (!$orgData) {
            return false;
        }

        // Check if user has role_id 1 or 2 in this organization
        $userOrgRole = DB::table('organization_user')
            ->where('organization_id', $orgData->organization_id)
            ->where('user_id', $userId)
            ->whereIn('role_id', [1, 2]) // Superadmin/Admin roles
            ->first();

        return $userOrgRole !== null;
    }

    /**
     * Get user's role in the organization
     */
    private function getUserOrganizationRole($userId)
    {
        // Get organization ID from class_organization
        $orgData = DB::table('class_organization')
            ->where('class_id', $this->id)
            ->first();

        if (!$orgData) {
            return null;
        }

        // Get user's role in organization
        $role = DB::table('organization_user')
            ->where('organization_id', $orgData->organization_id)
            ->where('user_id', $userId)
            ->join('organization_roles', 'organization_user.role_id', '=', 'organization_roles.id')
            ->value('organization_roles.nama');

        return $role ? strtolower($role) : null;
    }

    /**
     * Check if user is class admin (owner or organization admin/superadmin)
     */
    public function isAdmin($userId = null)
    {
        if (!$userId) {
            $userId = auth()->id();
        }

        if (!$userId) {
            return false;
        }

        // Check if user is the class teacher/owner
        if ($this->isClassTeacher($userId)) {
            return true;
        }

        // Check if user is an organization admin/superadmin
        return $this->isOrganizationAdmin($userId);
    }

    // Get the organ_user_id for this class
    public function getOrganUserId()
    {
        $orgData = DB::table('class_organization')
            ->where('class_id', $this->id)
            ->first();

        return $orgData ? $orgData->organ_user_id : null;
    }

    // Scope for active classes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Get members count
    public function getMembersCountAttribute()
    {
        return $this->members()->count();
    }

    public function announcements()
{
    return $this->hasMany(ClassAnnouncement::class, 'class_id')
        ->orderBy('is_pinned', 'desc')
        ->orderBy('created_at', 'desc');
}

public function activeAnnouncements()
{
    return $this->announcements()->active();
}
}
