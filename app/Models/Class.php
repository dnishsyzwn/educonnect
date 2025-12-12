<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'name',
        'levelid',
        'status',
    ];

    protected $casts = [
        'levelid' => 'integer',
        'status' => 'integer',
    ];

    // Relationships
    public function students()
    {
        return $this->belongsToMany(User::class, 'class_student', 'organclass_id', 'student_id')
                    ->withPivot('status');
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'class_organization', 'class_id', 'organization_id');
    }

    public function classOrganizations()
    {
        return $this->hasMany(ClassOrganization::class);
    }
}
