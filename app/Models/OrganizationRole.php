<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationRole extends Model
{
    protected $table = 'organization_roles';

    protected $fillable = ['nama'];

    // Relationship with users through organization_user table
    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_user')
                    ->withTimestamps();
    }
}
