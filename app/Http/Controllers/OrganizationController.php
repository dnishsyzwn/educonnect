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

        // Get current user ID
        $currentUserId = Auth::id();

        // Load ALL organizations with their classes and check membership
        $organizations = $query->with(['classes' => function ($q) use ($currentUserId) {
            $q->select('classes.id', 'classes.nama', 'classes.status')
                ->when($currentUserId, function ($query) use ($currentUserId) {
                    // Load membership status for each class
                    return $query->with(['members' => function ($query) use ($currentUserId) {
                        $query->where('user_id', $currentUserId);
                    }]);
                });
        }])->get(); // Changed from paginate() to get()

        // Process each organization
        foreach ($organizations as $org) {
            // Determine organization type (use type_org if exists, otherwise infer from name)
            if ($org->type_org) {
                $org->type = strtolower($org->type_org);
            } else {
                $org->type = $this->determineOrganizationType($org->nama);
            }
            $org->typeInfo = $this->getTypeInfo($org->type);

            // Count active classes
            $activeClasses = $org->classes->where('status', true);
            $org->active_classes_count = $activeClasses->count();

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

    // Get members (with limit for initial load)
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
        ->orderBy('users.name', 'asc')
        ->limit(12) // Initial load of 12 members
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

    // Get classes for this organization - INITIAL LOAD (9 classes)
    $initialClasses = collect();
    $classData = DB::table('class_organization')
        ->where('organization_id', $id)
        ->limit(9) // Initial load of 9 classes
        ->get();

    if ($classData->count() > 0) {
        $classIds = $classData->pluck('class_id');

        // Get class details using ClassModel with proper relationships
        $initialClasses = ClassModel::whereIn('id', $classIds)
            ->with(['owner']) // Load owner relationship
            ->withCount(['members as member_count']) // Add member count
            ->orderBy('id', 'desc') // Use ID instead of created_at
            ->get()
            ->map(function ($class) use ($classData, $currentUserId, $id) {
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

                // Get teacher name
                $class->teacher_name = $class->owner ? $class->owner->name : 'Unknown Teacher';

                // Generate class code
                $class->code = 'CLS' . str_pad($class->id, 3, '0', STR_PAD_LEFT);

                // Status
                $class->formatted_status = $class->status ? 'active' : 'inactive';

                // Name alias
                $class->name = $class->nama;

                // Created date - use a default since we don't have created_at
                $class->created_at_formatted = 'Unknown date';

                // Check if user can join (must be organization member)
                $class->can_join = $currentUserId ?
                    DB::table('organization_user')
                        ->where('organization_id', $id)
                        ->where('user_id', $currentUserId)
                        ->exists() : false;

                return $class;
            });
    }

    // Get total classes count for the card
    $totalClassesCount = DB::table('class_organization')
        ->where('organization_id', $id)
        ->count();

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
            'tahap' => $announcement->tahap,
            'author' => $announcement->author_name,
            'created_at' => $announcement->created_at,
            'formatted_date' => Carbon::parse($announcement->created_at)->format('M d, Y'),
            'is_pinned' => $announcement->is_pinned ?? false,
            'status' => $announcement->status ?? 'published'
        ];
    });

    // Get counts
    $memberCount = DB::table('organization_user')
        ->where('organization_id', $id)
        ->count();
    $classesCount = $totalClassesCount;
    $teachersCount = DB::table('organization_user')
        ->where('organization_id', $id)
        ->join('organization_roles', 'organization_user.role_id', '=', 'organization_roles.id')
        ->where('organization_roles.nama', 'like', '%teacher%')
        ->count();
    $adminsCount = DB::table('organization_user')
        ->where('organization_id', $id)
        ->join('organization_roles', 'organization_user.role_id', '=', 'organization_roles.id')
        ->whereIn('organization_roles.nama', ['Superadmin', 'Admin'])
        ->count();

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
        'initialClasses',
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
    'tahap' => 'required|in:1,2,both',
    'is_pinned' => 'boolean',
    'send_notification' => 'boolean'
]);

        $announcementId = DB::table('organization_announcements')->insertGetId([
    'organization_id' => $id,
    'user_id' => Auth::id(),
    'title' => $validated['title'],
    'content' => $validated['content'],
    'tahap' => $validated['tahap'],
    'is_pinned' => $validated['is_pinned'] ?? false,
    'send_notification' => $validated['send_notification'] ?? false,
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

    /**
     * Get members for organization with pagination (for AJAX)
     */
    public function getMembers(Request $request, $id)
    {
        $perPage = $request->get('per_page', 12);
        $page = $request->get('page', 1);
        $search = $request->get('search', '');
        $role = $request->get('role', '');

        $query = DB::table('organization_user')
            ->join('users', 'organization_user.user_id', '=', 'users.id')
            ->leftJoin('organization_roles', 'organization_user.role_id', '=', 'organization_roles.id')
            ->where('organization_user.organization_id', $id);

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%")
                    ->orWhere('users.username', 'like', "%{$search}%");
            });
        }

        // Apply role filter
        if ($role && in_array($role, ['admin', 'teacher', 'member'])) {
            if ($role === 'admin') {
                $query->where(function ($q) {
                    $q->where('organization_roles.nama', 'like', '%admin%')
                        ->orWhere('organization_roles.nama', 'like', '%superadmin%');
                });
            } elseif ($role === 'teacher') {
                $query->where('organization_roles.nama', 'like', '%teacher%');
            } else {
                $query->where(function ($q) {
                    $q->where('organization_roles.nama', 'like', '%member%')
                        ->orWhereNull('organization_roles.nama');
                });
            }
        }

        // Get total count
        $total = $query->count();

        // Get paginated results
        $members = $query->select(
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
            ->orderBy('users.name', 'asc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
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
                    'joined_date' => $member->user_created_at ?? now(),
                    'formatted_date' => $member->user_created_at ?
                        Carbon::parse($member->user_created_at)->format('M d, Y') :
                        Carbon::now()->format('M d, Y')
                ];
            });

        return response()->json([
            'success' => true,
            'members' => $members,
            'total' => $total,
            'has_more' => ($page * $perPage) < $total,
            'current_page' => $page,
            'per_page' => $perPage
        ]);
    }

    /**
     * Get classes for organization with pagination (for AJAX)
     */
    public function getClasses(Request $request, $id)
{
    $perPage = $request->get('per_page', 9);
    $page = $request->get('page', 1);
    $search = $request->get('search', '');
    $status = $request->get('status', '');

    // Get current user ID
    $currentUserId = Auth::id();

    // Get class IDs from class_organization
    $query = DB::table('class_organization')
        ->join('classes', 'class_organization.class_id', '=', 'classes.id')
        ->where('class_organization.organization_id', $id);

    // Apply search filter
    if ($search) {
        $query->where('classes.nama', 'like', "%{$search}%");
    }

    // Apply status filter
    if ($status === 'active') {
        $query->where('classes.status', true);
    } elseif ($status === 'inactive') {
        $query->where('classes.status', false);
    }

    // Get total count
    $total = $query->count();

    // Calculate offset
    $offset = ($page - 1) * $perPage;

    // Get class IDs for this page - order by class ID instead of created_at
    $classIds = $query->select('classes.id')
        ->orderBy('classes.id', 'desc') // Use ID instead of created_at
        ->skip($offset)
        ->take($perPage)
        ->pluck('id');

    // Get classes with details
    $classes = ClassModel::whereIn('id', $classIds)
        ->with(['owner'])
        ->withCount(['members as member_count'])
        ->orderBy('id', 'desc') // Use ID instead of created_at
        ->get()
        ->map(function ($class) use ($currentUserId, $id) {
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

            // Check if user can join (is organization member)
            $isOrgMember = DB::table('organization_user')
                ->where('organization_id', $id)
                ->where('user_id', $currentUserId)
                ->exists();
            $class->can_join = $isOrgMember;

            return [
                'id' => $class->id,
                'name' => $class->nama,
                'description' => $class->description ?? 'Class description here',
                'teacher_name' => $class->owner ? $class->owner->name : 'Unknown Teacher',
                'member_count' => $class->member_count,
                'code' => 'CLS' . str_pad($class->id, 3, '0', STR_PAD_LEFT),
                'status' => $class->status,
                'formatted_status' => $class->status ? 'active' : 'inactive',
                'is_member' => $class->is_member,
                'can_join' => $class->can_join,
                'created_at_formatted' => 'Unknown date' // Default value since no created_at
            ];
        });

    return response()->json([
        'success' => true,
        'classes' => $classes,
        'total' => $total,
        'has_more' => ($page * $perPage) < $total,
        'current_page' => $page,
        'per_page' => $perPage
    ]);
}
}
