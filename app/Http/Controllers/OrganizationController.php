<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationRole;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrganizationController extends Controller
{
    /**
     * Display a listing of organizations for the search page
     */
    public function index(Request $request)
{
    $query = Organization::query();

    // Apply search filter
    if ($request->has('search') && $request->search) {
        $searchTerm = $request->search;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('nama', 'like', "%{$searchTerm}%")
                ->orWhere('email', 'like', "%{$searchTerm}%")
                ->orWhere('address', 'like', "%{$searchTerm}%")
                ->orWhere('code', 'like', "%{$searchTerm}%")
                ->orWhere('state', 'like', "%{$searchTerm}%")
                ->orWhere('district', 'like', "%{$searchTerm}%")
                ->orWhere('city', 'like', "%{$searchTerm}%");
        });
    }

    // Apply state filter
    if ($request->has('state') && $request->state) {
        $query->where('state', $request->state);
    }

    // Apply status filter
    if ($request->has('status') && $request->status) {
        $query->where('status', $request->status === 'active' ? 1 : 0);
    }

    // Apply type filter
    if ($request->has('filter') && $request->filter !== 'all') {
        $type = $request->filter;
        $query->where(function ($q) use ($type) {
            if ($type === 'school') {
                $q->where('nama', 'like', '%school%')
                    ->orWhere('nama', 'like', '%sekolah%')
                    ->orWhere('nama', 'like', '%SMK%')
                    ->orWhere('nama', 'like', '%SJK%');
            } elseif ($type === 'masjid') {
                $q->where('nama', 'like', '%masjid%')
                    ->orWhere('nama', 'like', '%surau%');
            } elseif ($type === 'tuition') {
                $q->where('nama', 'like', '%tuition%')
                    ->orWhere('nama', 'like', '%tuisyen%')
                    ->orWhere('nama', 'like', '%kelas%');
            } elseif ($type === 'music') {
                $q->where('nama', 'like', '%music%')
                    ->orWhere('nama', 'like', '%piano%')
                    ->orWhere('nama', 'like', '%guitar%');
            }
        });
    }

    // Apply sorting
    $sort = $request->get('sort', 'relevance');
    switch ($sort) {
        case 'name':
            $query->orderBy('nama', 'asc');
            break;
        case 'newest':
            $query->orderBy('created_at', 'desc');
            break;
        case 'relevance':
        default:
            // Load counts first
            $query->withCount(['users', 'classes'])
                ->orderBy('users_count', 'desc')
                ->orderBy('classes_count', 'desc')
                ->orderBy('created_at', 'desc');
            break;
    }

    // Paginate results
    $perPage = $request->get('per_page', 12);

    // Get current user ID
    $currentUserId = Auth::id();

    // Load organizations with their classes and check membership
    $organizations = $query->with(['classes' => function ($q) use ($currentUserId) {
        $q->select('classes.id', 'classes.nama', 'classes.status')
            ->when($currentUserId, function ($query) use ($currentUserId) {
                // Load membership status for each class
                return $query->with(['members' => function ($query) use ($currentUserId) {
                    $query->where('user_id', $currentUserId);
                }]);
            });
    }])->paginate($perPage);

    // Process each organization
    foreach ($organizations as $org) {
        // Determine organization type (use type_org if exists, otherwise infer from name)
        if ($org->type_org) {
            $org->type = strtolower($org->type_org);
        } else {
            $org->type = $this->determineOrganizationType($org->nama);
        }
        $org->typeInfo = $this->getTypeInfo($org->type);

        // Process each class in the organization
        foreach ($org->classes as $class) {
            // Check if current user is a member of this class
            $class->is_member = $currentUserId ? $class->members->isNotEmpty() : false;

            // Remove the members relationship to clean up the data
            unset($class->members);
        }
    }

    // Return view for regular requests
    return view('search', compact('organizations'));
}

    /**
     * Determine organization type based on name
     */
    private function determineOrganizationType($name)
    {
        $nameLower = strtolower($name);

        if (
            str_contains($nameLower, 'school') ||
            str_contains($nameLower, 'sekolah') ||
            str_contains($nameLower, 'smk') ||
            str_contains($nameLower, 'sjk')
        ) {
            return 'school';
        } elseif (
            str_contains($nameLower, 'masjid') ||
            str_contains($nameLower, 'surau') ||
            str_contains($nameLower, 'mosque')
        ) {
            return 'masjid';
        } elseif (
            str_contains($nameLower, 'music') ||
            str_contains($nameLower, 'piano') ||
            str_contains($nameLower, 'guitar') ||
            str_contains($nameLower, 'violin')
        ) {
            return 'music';
        } elseif (
            str_contains($nameLower, 'tuition') ||
            str_contains($nameLower, 'tuisyen') ||
            str_contains($nameLower, 'kelas')
        ) {
            return 'tuition';
        } elseif (
            str_contains($nameLower, 'sports') ||
            str_contains($nameLower, 'gym') ||
            str_contains($nameLower, 'fitness')
        ) {
            return 'sports';
        } elseif (
            str_contains($nameLower, 'community') ||
            str_contains($nameLower, 'komuniti') ||
            str_contains($nameLower, 'persatuan')
        ) {
            return 'community';
        } else {
            return 'business';
        }
    }

    /**
     * Get type information (icon, colors)
     */
    private function getTypeInfo($type)
    {
        $types = [
            'school' => [
                'icon' => 'school',
                'bg' => 'from-blue-100 to-blue-200',
                'text' => 'text-blue-600',
                'badge' => 'badge-school'
            ],
            'masjid' => [
                'icon' => 'home',
                'bg' => 'from-green-100 to-green-200',
                'text' => 'text-green-600',
                'badge' => 'badge-masjid'
            ],
            'music' => [
                'icon' => 'music',
                'bg' => 'from-purple-100 to-purple-200',
                'text' => 'text-purple-600',
                'badge' => 'badge-music'
            ],
            'tuition' => [
                'icon' => 'book-open',
                'bg' => 'from-amber-100 to-amber-200',
                'text' => 'text-amber-600',
                'badge' => 'badge-tuition'
            ],
            'sports' => [
                'icon' => 'activity',
                'bg' => 'from-red-100 to-red-200',
                'text' => 'text-red-600',
                'badge' => 'badge-sports'
            ],
            'community' => [
                'icon' => 'users',
                'bg' => 'from-cyan-100 to-cyan-200',
                'text' => 'text-cyan-600',
                'badge' => 'badge-community'
            ],
            'business' => [
                'icon' => 'briefcase',
                'bg' => 'from-indigo-100 to-indigo-200',
                'text' => 'text-indigo-600',
                'badge' => 'badge-business'
            ]
        ];

        return $types[$type] ?? $types['business'];
    }

    /**
     * Show the form for creating a new organization
     */
    public function create()
    {
        return view('organizations.create');
    }

    /**
     * Store a newly created organization
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:organizations',
            'code' => 'required|string|unique:organizations|size:6',
            'telno' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'postcode' => 'required|string|max:10',
            'state' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'status' => 'boolean',
        ]);

        $organization = Organization::create($validated);

        // If user is authenticated, add them to organization as Superadmin
        if (Auth::check()) {
            $user = Auth::user();

            // Get Superadmin role
            $superadminRole = OrganizationRole::where('nama', 'Superadmin')->first();

            // Add user to organization with Superadmin role
            DB::table('organization_user')->insert([
                'organization_id' => $organization->id,
                'user_id' => $user->id,
                'role_id' => $superadminRole->id ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect()->route('organization.show', $organization->id)
            ->with('success', 'Organization created successfully!');
    }

    /**
     * Display the specified organization
     */
    public function show($id)
{
    // Get the organization
    $organization = Organization::findOrFail($id);

    // Determine organization type and get type info
    if ($organization->type_org) {
        $organization->type = strtolower($organization->type_org);
    } else {
        $organization->type = $this->determineOrganizationType($organization->nama);
    }
    $organization->typeInfo = $this->getTypeInfo($organization->type);

    // Get members
    $members = DB::table('organization_user')
        ->join('users', 'organization_user.user_id', '=', 'users.id')
        ->leftJoin('organization_roles', 'organization_user.role_id', '=', 'organization_roles.id')
        ->where('organization_user.organization_id', $id)
        ->select(
            'users.id',
            'users.name',
            'users.email',
            'users.username',
            'users.icno',
            'users.telno',
            'users.created_at as user_created_at',
            'organization_roles.nama as role_name',
            'organization_user.role_id'
        )
        ->get()
        ->map(function ($member) {
            return [
                'id' => $member->id,
                'name' => $member->name ?? 'Unknown',
                'email' => $member->email,
                'username' => $member->username,
                'icno' => $member->icno,
                'telno' => $member->telno,
                'role' => $member->role_name ? strtolower($member->role_name) : 'member',
                'role_name' => $member->role_name ?? 'Member',
                'status' => 'active',
                'is_admin' => $member->role_name ?
                    (str_contains(strtolower($member->role_name), 'admin') ? true : false) : false,
                'joined_date' => $member->user_created_at ?? now()
            ];
        });

    // Get current user ID
    $currentUserId = Auth::id();

    // Get classes for this organization using a custom query to avoid timestamp issues
    $classes = collect();

    // First get class IDs from class_organization
    $classData = DB::table('class_organization')
        ->where('organization_id', $id)
        ->get();

    if ($classData->count() > 0) {
        $classIds = $classData->pluck('class_id');

        // Get class details using ClassModel
        $classes = ClassModel::whereIn('id', $classIds)
    ->select('id', 'nama', 'status') // Remove created_at
    ->with(['owner'])
    ->withCount(['members as member_count'])
    ->get();

        // Add organization-specific data to each class
        $classes->each(function ($class) use ($classData, $currentUserId, $id) {
            // Find the organization data for this class
            $orgData = $classData->where('class_id', $class->id)->first();

            // Check if user is a member of this class
            if ($currentUserId) {
                $isMember = DB::table('class_user')
                    ->where('class_id', $class->id)
                    ->where('user_id', $currentUserId)
                    ->exists();
                $class->is_member = $isMember;
            } else {
                $class->is_member = false;
            }

            $class->teacher_name = $class->owner ? $class->owner->name : 'Unknown Teacher';
            $class->code = 'CLS' . str_pad($class->id, 3, '0', STR_PAD_LEFT);
            $class->formatted_status = $class->status ? 'active' : 'inactive';
            $class->description = 'Class description here';
            $class->name = $class->nama;
            $class->created_at_formatted = $class->created_at ? $class->created_at->format('M d, Y') : 'Unknown date';

            // Add organ_user_id from class_organization
            $class->organ_user_id = $orgData ? $orgData->organ_user_id : null;
        });
    }

    // Get announcements
    $announcements = DB::table('organization_announcements')
        ->where('organization_announcements.organization_id', $id)
        ->leftJoin('users', 'organization_announcements.user_id', '=', 'users.id')
        ->select(
            'organization_announcements.*',
            'users.name as author_name',
            'users.username as author_username'
        )
        ->orderBy('organization_announcements.is_pinned', 'desc')
        ->orderBy('organization_announcements.created_at', 'desc')
        ->get()
        ->map(function ($announcement) {
            return [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'content' => $announcement->content,
                'author' => $announcement->author_name,
                'created_at' => $announcement->created_at,
                'formatted_date' => Carbon::parse($announcement->created_at)->format('M d, Y'),
                'is_pinned' => $announcement->is_pinned ?? false,
                'status' => $announcement->status ?? 'published'
            ];
        });

    // Get counts
    $memberCount = $members->count();
    $classesCount = $classes->count();
    $teachersCount = $members->where('role', 'teacher')->count();
    $adminsCount = $members->where('is_admin', true)->count();

    // Check if current user can create class
    $userCanCreateClass = false;
    if (Auth::check()) {
        $currentUserRole = DB::table('organization_user')
            ->where('organization_id', $id)
            ->where('user_id', $currentUserId)
            ->join('organization_roles', 'organization_user.role_id', '=', 'organization_roles.id')
            ->select('organization_roles.nama as role_name')
            ->first();

        if ($currentUserRole) {
            $roleName = strtolower($currentUserRole->role_name);
            $userCanCreateClass = in_array($roleName, ['superadmin', 'admin']);
        }
    }

    return view('organization', compact(
        'organization',
        'members',
        'classes',
        'announcements',
        'memberCount',
        'classesCount',
        'teachersCount',
        'adminsCount',
        'userCanCreateClass'
    ));
}

    /**
     * Get organization statistics
     */
    public function stats()
    {
        $totalOrganizations = Organization::count();
        $activeOrganizations = Organization::where('status', 1)->count();

        // Get total users across all organizations
        $totalUsers = DB::table('organization_user')->distinct('user_id')->count('user_id');

        return response()->json([
            'total_organizations' => $totalOrganizations,
            'active_organizations' => $activeOrganizations,
            'total_users' => $totalUsers
        ]);
    }

    /**
     * Check if user is already a member via classes
     */
    public function checkMembershipViaClasses($orgId)
{
    $userId = Auth::id();
    $isMember = false;

    if ($userId) {
        // Check if user is member of any class in this organization
        // Using class_organization pivot table
        $isMember = DB::table('class_user')
            ->join('class_organization', 'class_user.class_id', '=', 'class_organization.class_id')
            ->where('class_organization.organization_id', $orgId)
            ->where('class_user.user_id', $userId)
            ->exists();
    }

    return response()->json(['is_member' => $isMember]);
}

    /**
     * Store a new announcement
     */
    public function storeAnnouncement(Request $request, $id)
{
    $organization = Organization::findOrFail($id);

    // Check if user has permission to create announcements
    $userRole = DB::table('organization_user')
        ->where('organization_id', $id)
        ->where('user_id', Auth::id())
        ->join('organization_roles', 'organization_user.role_id', '=', 'organization_roles.id')
        ->select('organization_roles.nama as role_name')
        ->first();

    if (!$userRole || !in_array(strtolower($userRole->role_name), ['superadmin', 'admin'])) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to create announcements.'
            ], 403);
        }
        return back()->with('error', 'You do not have permission to create announcements.');
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        // Removed is_pinned and send_notification validation
    ]);

    $announcementId = DB::table('organization_announcements')->insertGetId([
        'organization_id' => $id,
        'user_id' => Auth::id(),
        'title' => $validated['title'],
        'content' => $validated['content'],
        'is_pinned' => false, // Set default to false
        'send_notification' => false, // Set default to false
        'status' => 'published',
        'created_at' => now(),
        'updated_at' => now()
    ]);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Announcement created successfully!',
            'announcement_id' => $announcementId
        ]);
    }

    return back()->with('success', 'Announcement created successfully!');
}

    /**
     * Edit announcement
     */
    public function editAnnouncement($orgId, $announcementId)
    {
        $announcement = DB::table('organization_announcements')
            ->where('id', $announcementId)
            ->where('organization_id', $orgId)
            ->first();

        if (!$announcement) {
            return response()->json([
                'success' => false,
                'message' => 'Announcement not found.'
            ], 404);
        }

        // Check if user has permission to edit
        $userRole = DB::table('organization_user')
            ->where('organization_id', $orgId)
            ->where('user_id', Auth::id())
            ->join('organization_roles', 'organization_user.role_id', '=', 'organization_roles.id')
            ->select('organization_roles.nama as role_name')
            ->first();

        if (!$userRole || !in_array(strtolower($userRole->role_name), ['superadmin', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to edit announcements.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'announcement' => $announcement
        ]);
    }

    /**
     * Update announcement
     */
    public function updateAnnouncement(Request $request, $orgId, $announcementId)
    {
        // Check if announcement exists
        $announcement = DB::table('organization_announcements')
            ->where('id', $announcementId)
            ->where('organization_id', $orgId)
            ->first();

        if (!$announcement) {
            return response()->json([
                'success' => false,
                'message' => 'Announcement not found.'
            ], 404);
        }

        // Check if user has permission to update announcements
        $userRole = DB::table('organization_user')
            ->where('organization_id', $orgId)
            ->where('user_id', Auth::id())
            ->join('organization_roles', 'organization_user.role_id', '=', 'organization_roles.id')
            ->select('organization_roles.nama as role_name')
            ->first();

        if (!$userRole || !in_array(strtolower($userRole->role_name), ['superadmin', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update announcements.'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_pinned' => 'boolean',
            'send_notification' => 'boolean'
        ]);

        DB::table('organization_announcements')
            ->where('id', $announcementId)
            ->update([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'is_pinned' => $validated['is_pinned'] ?? false,
                'send_notification' => $validated['send_notification'] ?? false,
                'updated_at' => now()
            ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Announcement updated successfully!'
            ]);
        }

        return back()->with('success', 'Announcement updated successfully!');
    }

    /**
     * Delete announcement
     */
    public function destroyAnnouncement($orgId, $announcementId)
    {
        // Check if announcement exists
        $announcement = DB::table('organization_announcements')
            ->where('id', $announcementId)
            ->where('organization_id', $orgId)
            ->first();

        if (!$announcement) {
            return response()->json([
                'success' => false,
                'message' => 'Announcement not found.'
            ], 404);
        }

        // Check if user has permission to delete announcements
        $userRole = DB::table('organization_user')
            ->where('organization_id', $orgId)
            ->where('user_id', Auth::id())
            ->join('organization_roles', 'organization_user.role_id', '=', 'organization_roles.id')
            ->select('organization_roles.nama as role_name')
            ->first();

        if (!$userRole || !in_array(strtolower($userRole->role_name), ['superadmin', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete announcements.'
            ], 403);
        }

        DB::table('organization_announcements')
            ->where('id', $announcementId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Announcement deleted successfully!'
        ]);
    }

    /**
     * Show the form for editing the organization
     */
    public function edit($id)
    {
        $organization = Organization::findOrFail($id);

        // Check if user has permission to edit
        $userRole = DB::table('organization_user')
            ->where('organization_id', $id)
            ->where('user_id', Auth::id())
            ->join('organization_roles', 'organization_user.role_id', '=', 'organization_roles.id')
            ->select('organization_roles.nama as role_name')
            ->first();

        if (!$userRole || !in_array(strtolower($userRole->role_name), ['superadmin', 'admin'])) {
            return back()->with('error', 'You do not have permission to edit this organization.');
        }

        return view('organizations.edit', compact('organization'));
    }

    /**
     * Update the specified organization
     */
    public function update(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);

        // Check if user has permission to update
        $userRole = DB::table('organization_user')
            ->where('organization_id', $id)
            ->where('user_id', Auth::id())
            ->join('organization_roles', 'organization_user.role_id', '=', 'organization_roles.id')
            ->select('organization_roles.nama as role_name')
            ->first();

        if (!$userRole || !in_array(strtolower($userRole->role_name), ['superadmin', 'admin'])) {
            return back()->with('error', 'You do not have permission to update this organization.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:organizations,email,' . $id,
            'telno' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'postcode' => 'required|string|max:10',
            'state' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'status' => 'boolean',
        ]);

        $organization->update($validated);

        return redirect()->route('organization.show', $organization->id)
            ->with('success', 'Organization updated successfully!');
    }

    /**
     * Remove the specified organization
     */
    public function destroy($id)
    {
        $organization = Organization::findOrFail($id);

        // Check if user has permission to delete
        $userRole = DB::table('organization_user')
            ->where('organization_id', $id)
            ->where('user_id', Auth::id())
            ->join('organization_roles', 'organization_user.role_id', '=', 'organization_roles.id')
            ->select('organization_roles.nama as role_name')
            ->first();

        if (!$userRole || !in_array(strtolower($userRole->role_name), ['superadmin'])) {
            return back()->with('error', 'Only superadmins can delete organizations.');
        }

        $organization->delete();

        return redirect()->route('search')
            ->with('success', 'Organization deleted successfully!');
    }
}
