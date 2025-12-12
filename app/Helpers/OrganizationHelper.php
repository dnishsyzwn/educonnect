<?php

// app/Helpers/OrganizationHelper.php

namespace App\Helpers;

class OrganizationHelper
{
    /**
     * Determine organization type based on name
     */
    public static function determineOrganizationType($name)
    {
        $nameLower = strtolower($name);

        if (str_contains($nameLower, 'school') ||
            str_contains($nameLower, 'sekolah') ||
            str_contains($nameLower, 'smk') ||
            str_contains($nameLower, 'sjk')) {
            return 'school';
        } elseif (str_contains($nameLower, 'masjid') ||
                 str_contains($nameLower, 'surau') ||
                 str_contains($nameLower, 'mosque')) {
            return 'masjid';
        } elseif (str_contains($nameLower, 'music') ||
                 str_contains($nameLower, 'piano') ||
                 str_contains($nameLower, 'guitar') ||
                 str_contains($nameLower, 'violin')) {
            return 'music';
        } elseif (str_contains($nameLower, 'tuition') ||
                 str_contains($nameLower, 'tuisyen') ||
                 str_contains($nameLower, 'kelas')) {
            return 'tuition';
        } elseif (str_contains($nameLower, 'sports') ||
                 str_contains($nameLower, 'gym') ||
                 str_contains($nameLower, 'fitness')) {
            return 'sports';
        } elseif (str_contains($nameLower, 'community') ||
                 str_contains($nameLower, 'komuniti') ||
                 str_contains($nameLower, 'persatuan')) {
            return 'community';
        } else {
            return 'business';
        }
    }

    /**
     * Get type icon
     */
    public static function getTypeIcon($type)
    {
        $icons = [
            'school' => 'school',
            'masjid' => 'home',
            'music' => 'music',
            'tuition' => 'book-open',
            'sports' => 'activity',
            'community' => 'users',
            'business' => 'briefcase'
        ];

        return $icons[$type] ?? 'briefcase';
    }

    /**
     * Get type colors
     */
    public static function getTypeColor($type, $part = 'bg')
    {
        $colors = [
            'school' => ['bg' => 'from-blue-100 to-blue-200', 'text' => 'text-blue-600', 'badge' => 'badge-school'],
            'masjid' => ['bg' => 'from-green-100 to-green-200', 'text' => 'text-green-600', 'badge' => 'badge-masjid'],
            'music' => ['bg' => 'from-purple-100 to-purple-200', 'text' => 'text-purple-600', 'badge' => 'badge-music'],
            'tuition' => ['bg' => 'from-amber-100 to-amber-200', 'text' => 'text-amber-600', 'badge' => 'badge-tuition'],
            'sports' => ['bg' => 'from-red-100 to-red-200', 'text' => 'text-red-600', 'badge' => 'badge-sports'],
            'community' => ['bg' => 'from-cyan-100 to-cyan-200', 'text' => 'text-cyan-600', 'badge' => 'badge-community'],
            'business' => ['bg' => 'from-indigo-100 to-indigo-200', 'text' => 'text-indigo-600', 'badge' => 'badge-business']
        ];

        return $colors[$type][$part] ?? $colors['business'][$part];
    }

    /**
     * Get role badge class
     */
    public static function getRoleBadgeClass($roleName)
    {
        $roleName = strtolower($roleName);

        if (str_contains($roleName, 'superadmin')) {
            return 'role-superadmin';
        } elseif (str_contains($roleName, 'admin')) {
            return 'role-admin';
        } elseif (str_contains($roleName, 'jaim')) {
            return 'role-jaim';
        } elseif (str_contains($roleName, 'pentadbir')) {
            return 'role-pentadbir';
        } elseif (str_contains($roleName, 'guru')) {
            return 'role-guru';
        } elseif (str_contains($roleName, 'penjaga')) {
            return 'role-penjaga';
        } elseif (str_contains($roleName, 'warden')) {
            return 'role-warden';
        } elseif (str_contains($roleName, 'guard')) {
            return 'role-guard';
        } else {
            return 'role-guru'; // Default to guru style
        }
    }

    /**
     * Get dummy data for missing fields
     */
    public static function getDummyData($orgId)
    {
        $dummyData = [
            1 => ['members' => 1245, 'classes' => 24, 'featuredClasses' => [
                ['name' => "Class 5 Aman", 'students' => 32],
                ['name' => "Class 4 Bestari", 'students' => 28]
            ]],
            2 => ['members' => 480, 'classes' => 8, 'featuredClasses' => [
                ['name' => "Quran Beginners", 'students' => 15],
                ['name' => "Youth Circle", 'students' => 25]
            ]],
            3 => ['members' => 120, 'classes' => 12, 'featuredClasses' => [
                ['name' => "Piano Beginners", 'students' => 8],
                ['name' => "Advanced Theory", 'students' => 6]
            ]],
            4 => ['members' => 320, 'classes' => 18, 'featuredClasses' => [
                ['name' => "Form 4 Physics", 'students' => 12],
                ['name' => "SPM Add Math", 'students' => 15]
            ]]
        ];

        // Use modulo to cycle through dummy data if orgId > 4
        $index = (($orgId - 1) % 4) + 1;
        return $dummyData[$index] ?? $dummyData[1];
    }
}
