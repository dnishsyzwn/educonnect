<?php

namespace App\Http\Controllers;
use App\Models\ClassModel;
use App\Models\ClassPost;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\Organization;
use App\Models\ClassOrganization;
use App\Models\ClassAnnouncement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ClassController extends Controller
{
    /**
     * Display a listing of classes for an organization or user's classes
     */
    public function index(Request $request)
    {
        $organizationId = $request->get('organization_id');

        if (!$organizationId) {
            // If no organization specified, show user's classes
            $classes = Auth::user()->classes()
                ->with(['organization', 'owner'])
                ->withCount('members')
                ->paginate(12);
            return view('classes.index', compact('classes'));
        }

        $organization = Organization::findOrFail($organizationId);

        // Use custom query to avoid timestamp issues
        $classIds = DB::table('class_organization')
            ->where('organization_id', $organizationId)
            ->pluck('class_id');

        $classes = ClassModel::whereIn('id', $classIds)
            ->with('owner')
            ->withCount('members')
            ->paginate(12);

        return view('classes.index', compact('classes', 'organization'));
    }

    /**
     * Display the specified class with posts
     */
    public function show($id)
{
    $class = ClassModel::with(['owner', 'posts.author', 'posts.comments.author'])
        ->withCount('members')
        ->findOrFail($id);

    // Load organization
    $organization = $this->getClassOrganization($class->id);
    $class->organization = $organization;

    $isMember = $class->isMember(Auth::id());
    $canPost = $class->canPost(Auth::id());

    // Get announcements for this class
    $announcements = $class->announcements()
        ->with('author')
        ->published()
        ->orderBy('is_pinned', 'desc')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    // Get posts with likes and comments count
    $posts = $class->posts()
        ->with(['author', 'comments.author'])
        ->withCount(['likes', 'comments'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // Calculate counts
    $postsCount = $class->posts()->count();
    $commentsCount = $class->posts()->withCount('comments')->get()->sum('comments_count');
    $announcementsCount = $class->announcements()->count();

    // Mark posts as liked by current user
    if ($isMember) {
        $postIds = $posts->pluck('id');
        $likedPosts = PostLike::whereIn('post_id', $postIds)
            ->where('user_id', Auth::id())
            ->pluck('post_id')
            ->toArray();

        foreach ($posts as $post) {
            $post->is_liked = in_array($post->id, $likedPosts);
        }
    }

    return view('classes.show', compact(
        'class',
        'posts',
        'announcements',
        'isMember',
        'canPost',
        'postsCount',
        'commentsCount',
        'announcementsCount'
    ));
}

/**
 * Get organization for a class
 */
private function getClassOrganization($classId)
    {
        $orgData = DB::table('class_organization')
            ->where('class_id', $classId)
            ->first();

        if (!$orgData) {
            return null;
        }

        return Organization::find($orgData->organization_id);
    }


    public function homeFeed(Request $request)
{
    $userId = Auth::id();

    // Get class IDs where user is a member
    $classIds = DB::table('class_user')
        ->where('user_id', $userId)
        ->pluck('class_id')
        ->toArray();

    // Get organization IDs from user's classes
    $organizationIds = DB::table('class_organization')
        ->whereIn('class_id', $classIds)
        ->pluck('organization_id')
        ->unique()
        ->toArray();

    // Also get organizations where user is directly a member (if you want those too)
    $userOrganizationIds = DB::table('organization_user')
        ->where('user_id', $userId)
        ->pluck('organization_id')
        ->toArray();

    $allOrgIds = array_unique(array_merge($organizationIds, $userOrganizationIds));

    // Get announcements from organizations
    $announcements = collect();
    if (!empty($allOrgIds)) {
        $announcements = DB::table('organization_announcements')
            ->whereIn('organization_announcements.organization_id', $allOrgIds)
            ->where('organization_announcements.status', 'published')
            ->leftJoin('users', 'organization_announcements.user_id', '=', 'users.id')
            ->leftJoin('organizations', 'organization_announcements.organization_id', '=', 'organizations.id')
            ->select(
                'organization_announcements.*',
                'users.name as author_name',
                DB::raw('COALESCE(users.profile_image, "images/pfp.jpg") as author_image'),
                'organizations.nama as organization_name',
                'organizations.id as organization_id'
            )
            ->orderBy('organization_announcements.is_pinned', 'desc')
            ->orderBy('organization_announcements.created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'content' => $announcement->content,
                    'author_name' => $announcement->author_name,
                    'author_image' => $announcement->author_image,
                    'organization_name' => $announcement->organization_name,
                    'organization_id' => $announcement->organization_id,
                    'created_at' => $announcement->created_at,
                    'formatted_time' => \Carbon\Carbon::parse($announcement->created_at)->diffForHumans(),
                    'is_pinned' => $announcement->is_pinned,
                    'views' => rand(15, 40)
                ];
            });
    }

    if (empty($classIds)) {
        // User is not in any classes
        return view('welcome', [
            'classPosts' => collect(),
            'userClasses' => collect(),
            'announcements' => $announcements,
            'user' => Auth::user()
        ]);
    }

    // Get posts with all necessary relationships
    $classPosts = ClassPost::whereIn('class_id', $classIds)
        ->with([
            'class' => function($query) {
                $query->with(['owner']);
            },
            'author',
            'comments' => function($query) {
                $query->with('author')->latest()->limit(3);
            }
        ])
        ->withCount(['likes', 'comments'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // Get organization for each post's class
    $classOrganizations = [];
    foreach ($classPosts as $post) {
        $orgData = DB::table('class_organization')
            ->where('class_id', $post->class_id)
            ->first();

        if ($orgData) {
            $organization = Organization::find($orgData->organization_id);
            $classOrganizations[$post->class_id] = $organization;
            $post->class->organization = $organization;
        }
    }

    // Get liked posts for current user
    if ($classPosts->count() > 0) {
        $postIds = $classPosts->pluck('id')->toArray();
        $likedPosts = PostLike::whereIn('post_id', $postIds)
            ->where('user_id', $userId)
            ->pluck('post_id')
            ->toArray();

        foreach ($classPosts as $post) {
            $post->is_liked = in_array($post->id, $likedPosts);

            // Determine user's role in this class
            $class = $post->class;
            if ($class->isClassTeacher($userId) || $class->isOrganizationAdmin($userId)) {
                $post->user_role = 'teacher';
            } else {
                $post->user_role = 'member';
            }
        }
    }

    // Get user's classes for sidebar
    $userClasses = ClassModel::whereIn('id', $classIds)
        ->with(['owner'])
        ->withCount('members')
        ->get()
        ->map(function($class) {
            $orgData = DB::table('class_organization')
                ->where('class_id', $class->id)
                ->first();

            $organization = $orgData ? Organization::find($orgData->organization_id) : null;

            return [
                'id' => $class->id,
                'name' => $class->nama,
                'organization_name' => $organization->nama ?? 'Unknown Organization',
                'teacher_name' => $class->owner->name ?? 'Unknown Teacher',
                'members_count' => $class->members_count,
                'code' => 'CLS' . str_pad($class->id, 3, '0', STR_PAD_LEFT)
            ];
        });

    return view('welcome', [
        'classPosts' => $classPosts,
        'userClasses' => $userClasses,
        'announcements' => $announcements,
        'user' => Auth::user()
    ]);
}


    /**
     * Show the form for creating a new class
     */
    public function create()
    {
        $user = Auth::user();
        $organizations = $user->organizations()->get();

        // Get organization_id from query string if provided
        $organizationId = request()->get('organization_id');

        if ($organizationId) {
            $organization = Organization::findOrFail($organizationId);

            // Check if user has permission to create class in this organization
            $userRole = DB::table('organization_user')
                ->where('organization_id', $organizationId)
                ->where('user_id', $user->id)
                ->join('organization_roles', 'organization_user.role_id', '=', 'organization_roles.id')
                ->select('organization_roles.nama as role_name')
                ->first();

            if (!$userRole || !in_array(strtolower($userRole->role_name), ['superadmin', 'admin'])) {
                return back()->with('error', 'You do not have permission to create classes in this organization.');
            }
        }

        return view('classes.create', compact('organizations'));
    }

    /**
     * Store a newly created class
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'organization_id' => 'required|exists:organizations,id',
        'status' => 'boolean',
        'description' => 'nullable|string|max:1000'
    ]);

    // Check if user has permission to create class in this organization
    $userRole = DB::table('organization_user')
        ->where('organization_id', $validated['organization_id'])
        ->where('user_id', Auth::id())
        ->join('organization_roles', 'organization_user.role_id', '=', 'organization_roles.id')
        ->select('organization_roles.nama as role_name')
        ->first();

    if (!$userRole || !in_array(strtolower($userRole->role_name), ['superadmin', 'admin'])) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to create classes in this organization.'
            ], 403);
        }
        return back()->with('error', 'You do not have permission to create classes in this organization.');
    }

    // Create the class
    $class = ClassModel::create([
        'nama' => $validated['nama'],
        'levelid' => 1, // Default level
        'status' => $validated['status'] ?? true,
    ]);

    // Link class to organization in class_organization table
    DB::table('class_organization')->insert([
        'class_id' => $class->id,
        'organization_id' => $validated['organization_id'],
        'organ_user_id' => Auth::id(), // The creator becomes the teacher
        'start_date' => now(),
        'updated_at' => now()
    ]);

    // Add creator as class member (no role)
    $class->members()->attach(Auth::id());

    // If AJAX request, return JSON response
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Class created successfully!',
            'class_id' => $class->id,
            'redirect_url' => route('classes.show', $class->id),
            'class_name' => $class->nama
        ]);
    }

    return redirect()->route('classes.show', $class->id)
        ->with('success', 'Class created successfully!');
}

    /**
     * Show the form for editing the class
     */
    public function edit($id)
    {
        $class = ClassModel::findOrFail($id);
        $user = Auth::user();

        // Check if user is admin of this class
        if (!$class->members()->where('user_id', $user->id)->where('role', 'admin')->exists()) {
            return back()->with('error', 'You do not have permission to edit this class.');
        }

        $organizations = $user->organizations()->get();

        return view('classes.edit', compact('class', 'organizations'));
    }

    /**
     * Update the specified class
     */
    public function update(Request $request, $id)
    {
        $class = ClassModel::findOrFail($id);

        // Check if user is admin of this class
        if (!$class->members()->where('user_id', Auth::id())->where('role', 'admin')->exists()) {
            return back()->with('error', 'You do not have permission to update this class.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'organization_id' => 'required|exists:organizations,id',
            'status' => 'boolean'
        ]);

        // Update class details
        $class->update([
            'nama' => $validated['nama'],
            'status' => $validated['status'] ?? $class->status
        ]);

        // Update class_organization relationship if organization changed
        $currentOrg = DB::table('class_organization')
            ->where('class_id', $id)
            ->first();

        if ($currentOrg && $currentOrg->organization_id != $validated['organization_id']) {
            DB::table('class_organization')
                ->where('class_id', $id)
                ->update([
                    'organization_id' => $validated['organization_id'],
                    'updated_at' => now()
                ]);
        }

        return redirect()->route('classes.show', $class->id)
            ->with('success', 'Class updated successfully!');
    }

    /**
     * Remove the specified class
     */
    public function destroy($id)
    {
        $class = ClassModel::findOrFail($id);

        // Check if user is admin of this class
        if (!$class->members()->where('user_id', Auth::id())->where('role', 'admin')->exists()) {
            return back()->with('error', 'You do not have permission to delete this class.');
        }

        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Class deleted successfully!');
    }

    /**
     * Join a class (for members) - Updated to allow anyone to join
     */
    public function join(Request $request, $id)
{
    $class = ClassModel::findOrFail($id);
    $user = Auth::user();

    // Check if already a member
    if ($class->isMember($user->id)) {
        return back()->with('info', 'You are already a member of this class.');
    }

    // Add user to class - no role parameter
    $class->members()->attach($user->id);

    return back()->with('success', 'Successfully joined the class!');
}

    /**
     * Leave a class
     */
    public function leave(Request $request, $id)
    {
        $class = ClassModel::findOrFail($id);
        $user = Auth::user();

        // Check if user is a member
        if (!$class->isMember($user->id)) {
            return back()->with('error', 'You are not a member of this class.');
        }

        // Don't allow class owner/admin to leave if they're the only admin
        $userRole = $class->members()->where('user_id', $user->id)->first()->pivot->role;
        if ($userRole === 'admin') {
            $adminCount = $class->members()->where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'You cannot leave the class as you are the only admin. Please assign another admin first.');
            }
        }

        // Remove user from class
        $class->members()->detach($user->id);

        return back()->with('success', 'Successfully left the class.');
    }

    /**
     * Create a new post in class
     */
    public function createPost(Request $request, $id)
{
    $class = ClassModel::findOrFail($id);

    // Check if user can post (must be member)
    if (!$class->canPost(Auth::id())) {
        return back()->with('error', 'You need to be a class member to post.');
    }

    $validated = $request->validate([
        'content' => 'required|string|max:5000',
        'media_url' => 'nullable|url',
        'media_type' => 'nullable|in:image,video,document,link'
    ]);

    ClassPost::create([
        'class_id' => $class->id,
        'user_id' => Auth::id(),
        'content' => $validated['content'],
        'media_url' => $validated['media_url'] ?? null,
        'media_type' => $validated['media_type'] ?? null
    ]);

    return back()->with('success', 'Post created successfully!');
}

    /**
     * Delete a post
     */
    public function deletePost(Request $request, $classId, $postId)
{
    $post = ClassPost::findOrFail($postId);
    $class = $post->class;

    // Check if user is the author or class admin (teacher/owner or organization admin)
    $isAuthor = $post->user_id === Auth::id();
    $isClassAdmin = $class->isAdmin(Auth::id());

    if (!$isAuthor && !$isClassAdmin) {
        return back()->with('error', 'You do not have permission to delete this post.');
    }

    $post->delete();

    return back()->with('success', 'Post deleted successfully!');
}

    /**
     * Like/Unlike a post
     */
    public function toggleLike(Request $request, $classId, $postId)
    {
        $post = ClassPost::findOrFail($postId);

        // Check if user is a class member
        if (!$post->class->isMember(Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => 'You must be a class member to like posts.'
            ], 403);
        }

        $existingLike = PostLike::where('post_id', $postId)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingLike) {
            // Unlike
            $existingLike->delete();
            $liked = false;
        } else {
            // Like
            PostLike::create([
                'post_id' => $postId,
                'user_id' => Auth::id()
            ]);
            $liked = true;
        }

        $likesCount = $post->likes()->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $likesCount
        ]);
    }

    /**
     * Add comment to post
     */
public function addComment(Request $request, $classId, $postId)
    {
        $post = ClassPost::findOrFail($postId);

        // Check if user is a class member
        if (!$post->class->isMember(Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => 'You must be a class member to comment.'
            ], 403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment = PostComment::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
            'content' => $validated['content']
        ]);

        // Load author for display
        $comment->load('author');

        // Return JSON response for AJAX
        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully!',
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'created_at' => $comment->created_at->diffForHumans(),
                'author' => [
                    'name' => $comment->author->name,
                    'profile_image' => $comment->author->profile_image ?? 'images/pfp.jpg'
                ]
            ]
        ]);
    }

    /**
     * Delete a comment
     */
    public function deleteComment(Request $request, $classId, $postId, $commentId)
{
    $comment = PostComment::findOrFail($commentId);
    $post = $comment->post;
    $class = $post->class;

    // Check if user is the author or post author or class admin
    $isCommentAuthor = $comment->user_id === Auth::id();
    $isPostAuthor = $post->user_id === Auth::id();
    $isClassAdmin = $class->isAdmin(Auth::id());

    if (!$isCommentAuthor && !$isPostAuthor && !$isClassAdmin) {
        return back()->with('error', 'You do not have permission to delete this comment.');
    }

    $comment->delete();

    return back()->with('success', 'Comment deleted successfully!');
}

    /**
     * Get class members
     */
    public function members($id)
    {
        $class = ClassModel::with(['members' => function ($query) {
            $query->select('users.id', 'users.name', 'users.email', 'users.username');
        }])->findOrFail($id);

        // Check if user is class member
        if (!$class->isMember(Auth::id())) {
            return back()->with('error', 'You must be a class member to view members.');
        }

        return view('classes.members', compact('class'));
    }

    /**
     * Update member role (for class admins)
     */
    public function updateMemberRole(Request $request, $classId, $userId)
    {
        $class = ClassModel::findOrFail($classId);

        // Check if current user is class admin
        $currentUserRole = $class->members()
            ->where('user_id', Auth::id())
            ->first()
            ->pivot->role ?? null;

        if ($currentUserRole !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Only class admins can update roles.'
            ], 403);
        }

        $validated = $request->validate([
            'role' => 'required|in:member,teacher,admin'
        ]);

        // Update role
        DB::table('class_user')
            ->where('class_id', $classId)
            ->where('user_id', $userId)
            ->update(['role' => $validated['role']]);

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully.'
        ]);
    }

    /**
     * Remove member from class (for class admins)
     */
    public function removeMember(Request $request, $classId, $userId)
    {
        $class = ClassModel::findOrFail($classId);

        // Check if current user is class admin
        $currentUserRole = $class->members()
            ->where('user_id', Auth::id())
            ->first()
            ->pivot->role ?? null;

        if ($currentUserRole !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Only class admins can remove members.'
            ], 403);
        }

        // Don't allow removing the last admin
        $targetUserRole = $class->members()
            ->where('user_id', $userId)
            ->first()
            ->pivot->role ?? null;

        if ($targetUserRole === 'admin') {
            $adminCount = $class->members()->where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot remove the last admin. Please assign another admin first.'
                ], 400);
            }
        }

        // Remove member
        $class->members()->detach($userId);

        return response()->json([
            'success' => true,
            'message' => 'Member removed successfully.'
        ]);
    }

    /**
 * Create a new announcement in class
 */
public function createAnnouncement(Request $request, $id)
{
    $class = ClassModel::findOrFail($id);

    // Check if user has permission to create announcements (admins only)
    if (!$class->isAdmin(Auth::id())) {
        return back()->with('error', 'Only teachers and admins can create announcements.');
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'is_pinned' => 'nullable|boolean',
        'send_notification' => 'nullable|boolean'
    ]);

    $announcement = ClassAnnouncement::create([
        'class_id' => $class->id,
        'user_id' => Auth::id(),
        'title' => $validated['title'],
        'content' => $validated['content'],
        'is_pinned' => $validated['is_pinned'] ?? false,
        'send_notification' => $validated['send_notification'] ?? false,
        'status' => 'published'
    ]);

    return back()->with('success', 'Announcement created successfully!');
}

/**
 * Update an announcement
 */
public function updateAnnouncement(Request $request, $classId, $announcementId)
{
    $class = ClassModel::findOrFail($classId);
    $announcement = ClassAnnouncement::findOrFail($announcementId);

    // Check if user has permission to update announcements
    if (!$class->isAdmin(Auth::id()) || $announcement->user_id !== Auth::id()) {
        return response()->json([
            'success' => false,
            'message' => 'You do not have permission to update this announcement.'
        ], 403);
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'is_pinned' => 'nullable|boolean',
        'send_notification' => 'nullable|boolean'
    ]);

    $announcement->update($validated);

    return response()->json([
        'success' => true,
        'message' => 'Announcement updated successfully!'
    ]);
}

/**
 * Delete an announcement
 */
public function deleteAnnouncement(Request $request, $classId, $announcementId)
{
    $class = ClassModel::findOrFail($classId);
    $announcement = ClassAnnouncement::findOrFail($announcementId);

    // Check if user has permission to delete announcements
    if (!$class->isAdmin(Auth::id()) || $announcement->user_id !== Auth::id()) {
        return back()->with('error', 'You do not have permission to delete this announcement.');
    }

    $announcement->delete();

    return back()->with('success', 'Announcement deleted successfully!');
}
}
