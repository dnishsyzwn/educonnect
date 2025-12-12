<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Organization Routes
    Route::get('/search', [OrganizationController::class, 'index'])->name('search');
    Route::get('/organization/create', [OrganizationController::class, 'create'])->name('organization.create');
    Route::post('/organization', [OrganizationController::class, 'store'])->name('organization.store');
    Route::get('/organization/{id}', [OrganizationController::class, 'show'])->name('organization.show');

    // Organization Management Routes (Organization owners/admins only)
    Route::get('/organization/{id}/edit', [OrganizationController::class, 'edit'])->name('organization.edit');
    Route::put('/organization/{id}', [OrganizationController::class, 'update'])->name('organization.update');
    Route::delete('/organization/{id}', [OrganizationController::class, 'destroy'])->name('organization.delete');

    // Organization Announcement Routes
    Route::post('/organization/{id}/announcements', [OrganizationController::class, 'storeAnnouncement'])->name('organization.announcements.store');
    Route::get('/organization/{id}/announcements/{announcement}/edit', [OrganizationController::class, 'editAnnouncement'])->name('organization.announcements.edit');
    Route::put('/organization/{id}/announcements/{announcement}', [OrganizationController::class, 'updateAnnouncement'])->name('organization.announcements.update');
    Route::delete('/organization/{id}/announcements/{announcement}', [OrganizationController::class, 'destroyAnnouncement'])->name('organization.announcements.destroy');

    // Home Feed Route
    Route::get('/home', [ClassController::class, 'homeFeed'])->name('home');

    // Class Routes
    Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');
    Route::get('/classes/create', [ClassController::class, 'create'])->name('classes.create');
    Route::post('/classes', [ClassController::class, 'store'])->name('classes.store');
    Route::get('/classes/{class}', [ClassController::class, 'show'])->name('classes.show');
    Route::get('/classes/{class}/edit', [ClassController::class, 'edit'])->name('classes.edit');
    Route::put('/classes/{class}', [ClassController::class, 'update'])->name('classes.update');
    Route::delete('/classes/{class}', [ClassController::class, 'destroy'])->name('classes.delete');

    // Class Announcement Routes (NEW)
    Route::post('/classes/{class}/announcements', [ClassController::class, 'createAnnouncement'])->name('classes.announcements.create');
    Route::put('/classes/{class}/announcements/{announcement}', [ClassController::class, 'updateAnnouncement'])->name('classes.announcements.update');
    Route::delete('/classes/{class}/announcements/{announcement}', [ClassController::class, 'deleteAnnouncement'])->name('classes.announcements.delete');

    // Class Membership Routes
    Route::post('/classes/{class}/join', [ClassController::class, 'join'])->name('classes.join');
    Route::post('/classes/{class}/leave', [ClassController::class, 'leave'])->name('classes.leave');

    // Post Routes
    Route::post('/classes/{class}/posts', [ClassController::class, 'createPost'])->name('classes.posts.create');
    Route::delete('/classes/{class}/posts/{post}', [ClassController::class, 'deletePost'])->name('classes.posts.delete');
    Route::post('/classes/{class}/posts/{post}/like', [ClassController::class, 'toggleLike'])->name('classes.posts.like');

    // Comment Routes
    Route::post('/classes/{class}/posts/{post}/comments', [ClassController::class, 'addComment'])->name('classes.posts.comments.create');
    Route::delete('/classes/{class}/posts/{post}/comments/{comment}', [ClassController::class, 'deleteComment'])->name('classes.posts.comments.delete');

    // Member Management Routes
    Route::get('/classes/{class}/members', [ClassController::class, 'members'])->name('classes.members');
    Route::put('/classes/{class}/members/{user}/role', [ClassController::class, 'updateMemberRole'])->name('classes.members.update-role');
    Route::delete('/classes/{class}/members/{user}', [ClassController::class, 'removeMember'])->name('classes.members.remove');

    // Profile Routes (if you have them)
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    // Chat Routes (if you have them)
    Route::get('/chat', function () {
        return view('chat');
    })->name('chat');
});

// API Routes for AJAX requests
Route::prefix('api')->group(function () {
    Route::get('/organizations', [OrganizationController::class, 'index']);
    Route::get('/organizations/stats', [OrganizationController::class, 'stats']);

    // Check organization membership via classes
    Route::get('/check-org-membership/{orgId}', [OrganizationController::class, 'checkMembershipViaClasses']);

    // Class API Routes
    Route::middleware('auth')->group(function () {
        Route::post('/classes/{class}/posts/{post}/like', [ClassController::class, 'toggleLike']);
        Route::put('/classes/{class}/members/{user}/role', [ClassController::class, 'updateMemberRole']);
        Route::delete('/classes/{class}/members/{user}', [ClassController::class, 'removeMember']);

        // Class Announcement API Routes (NEW)
        Route::put('/classes/{class}/announcements/{announcement}', [ClassController::class, 'updateAnnouncement']);
    });

    // Join organization route (for AJAX requests)
    Route::post('/join-organization', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'organization_id' => 'required|exists:organizations,id'
        ]);

        $userId = Auth::id();
        $organizationId = $request->organization_id;

        // Check if already a member
        $isMember = DB::table('organization_user')
            ->where('organization_id', $organizationId)
            ->where('user_id', $userId)
            ->exists();

        if ($isMember) {
            return response()->json([
                'success' => false,
                'message' => 'You are already a member of this organization.'
            ]);
        }

        // Add user to organization with default "member" role
        $memberRole = DB::table('organization_roles')
            ->where('nama', 'Member')
            ->first();

        DB::table('organization_user')->insert([
            'organization_id' => $organizationId,
            'user_id' => $userId,
            'role_id' => $memberRole->id ?? null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully joined the organization!'
        ]);
    })->middleware('auth');
});

// Public routes (accessible without authentication)
Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

// Fallback route
Route::fallback(function () {
    return redirect('/home');
});
