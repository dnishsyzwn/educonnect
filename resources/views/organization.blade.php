<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $organization->nama }} - EduGlass</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <!-- Add CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Keep all your CSS styles */
        .glass-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        .glass-input {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow:
                0 4px 20px rgba(0, 0, 0, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        .glass-input:focus {
            background: rgba(255, 255, 255, 0.6);
            border-color: rgba(59, 130, 246, 0.5);
            box-shadow:
                0 4px 20px rgba(59, 130, 246, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        .glass-button {
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow:
                0 4px 20px rgba(0, 0, 0, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }

        .glass-button:hover {
            background: rgba(255, 255, 255, 0.4);
            border-color: rgba(255, 255, 255, 0.6);
            transform: translateY(-1px);
            box-shadow:
                0 6px 25px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        .tab-button {
            position: relative;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            color: #64748b;
            transition: all 0.2s ease;
            background: transparent;
        }

        .tab-button.active {
            color: #1d4ed8;
            font-weight: 600;
        }

        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background: #3b82f6;
            border-radius: 2px 2px 0 0;
        }

        /* Status badges */
        .status-active {
            background: rgba(34, 197, 94, 0.15);
            color: #166534;
        }

        .status-inactive {
            background: rgba(239, 68, 68, 0.15);
            color: #991b1b;
        }

        /* Role badges */
        .role-admin {
            background: rgba(59, 130, 246, 0.15);
            color: #1d4ed8;
        }

        .role-teacher {
            background: rgba(168, 85, 247, 0.15);
            color: #7c3aed;
        }

        .role-member {
            background: rgba(6, 182, 212, 0.15);
            color: #155e75;
        }

        /* Toast animations */
        .toast {
            animation: slideIn 0.3s ease-out, fadeOut 0.3s ease-out 2.7s forwards;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }
        .line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.prose {
    line-height: 1.75;
}

.prose p {
    margin-top: 1.25em;
    margin-bottom: 1.25em;
}

.prose a {
    color: #3b82f6;
    text-decoration: underline;
    font-weight: 500;
}

.prose a:hover {
    color: #2563eb;
}
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 text-slate-800 font-sans">
    <!-- Toast Container -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Main Layout -->
    <div class="flex min-h-screen max-w-full">
        <!-- Left Sidebar -->
        <div class="hidden lg:flex w-20 sticky top-0 h-screen flex-col items-center py-8 gap-6">
            <div class="glass-card w-12 h-12 rounded-2xl flex items-center justify-center mb-4">
                <i data-feather="book-open" class="text-blue-600 w-6 h-6"></i>
            </div>
            <a href="/home" class="glass-card p-3 rounded-xl hover:bg-white/30 transition-all duration-200 group">
                <i data-feather="home" class="w-5 h-5 text-slate-600 group-hover:text-blue-600"></i>
            </a>
            <a href="/search" class="glass-card p-3 rounded-xl hover:bg-white/30 transition-all duration-200 group">
                <i data-feather="search" class="w-5 h-5 text-slate-600 group-hover:text-blue-600"></i>
            </a>
            <a href="/my-organizations"
                class="glass-card p-3 rounded-xl hover:bg-white/30 transition-all duration-200 group">
                <i data-feather="users" class="w-5 h-5 text-slate-600 group-hover:text-blue-600"></i>
            </a>
            <div class="mt-auto">
                <a href="/profile"
                    class="glass-card p-3 rounded-xl hover:bg-white/30 transition-all duration-200 group">
                    <i data-feather="user" class="w-5 h-5 text-slate-600 group-hover:text-blue-600"></i>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 w-full p-4 lg:p-8">
            <div class="max-w-7xl mx-auto">
                <!-- Back Button -->
                <div class="mb-6">
                    <a href="/search"
                        class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800 transition-colors">
                        <i data-feather="arrow-left" class="w-4 h-4"></i>
                        Back to Discover
                    </a>
                </div>

                <!-- Organization Header -->
                <div class="glass-card rounded-2xl p-6 mb-8">
                    <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                        <!-- Organization Logo/Icon -->
                        <div
                            class="w-24 h-24 rounded-2xl flex items-center justify-center backdrop-blur-sm bg-gradient-to-br {{ $organization->typeInfo['bg'] }} flex-shrink-0">
                            <i data-feather="{{ $organization->typeInfo['icon'] }}"
                                class="w-12 h-12 {{ $organization->typeInfo['text'] }}"></i>
                        </div>

                        <!-- Organization Info -->
                        <div class="flex-1">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-4">
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <h1 class="text-3xl lg:text-4xl font-bold text-slate-800">
                                            {{ $organization->nama }}</h1>
                                        <!-- User role badge if they're a member -->
                                        @auth
                                            @if(Auth::user()->organizations()->where('organizations.id', $organization->id)->exists())
                                                @php
                                                    // Get user role
                                                    $userRole = DB::table('organization_user')
                                                        ->where('organization_id', $organization->id)
                                                        ->where('user_id', Auth::id())
                                                        ->join('organization_roles', 'organization_user.role_id', '=', 'organization_roles.id')
                                                        ->select('organization_roles.nama as role_name')
                                                        ->first();
                                                @endphp
                                                @if($userRole)
                                                    <span
                                                        class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-medium">
                                                        {{ ucfirst($userRole->role_name) }}
                                                    </span>
                                                @endif
                                            @endif
                                        @endauth
                                    </div>
                                    <div class="flex items-center gap-4 text-slate-600">
                                        <div class="flex items-center gap-2">
                                            <i data-feather="map-pin" class="w-4 h-4"></i>
                                            <span>{{ $organization->district ?: $organization->state }}, Malaysia</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i data-feather="calendar" class="w-4 h-4"></i>
                                            <span>Est. {{ $organization->created_at->format('Y') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-wrap gap-2">
                                    @auth
                                        @if(Auth::user()->organizations()->where('organizations.id', $organization->id)->exists())
                                            <button
                                                class="glass-button px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2">
                                                <i data-feather="edit" class="w-4 h-4"></i>
                                                Edit Organization
                                            </button>
                                            <button
                                                class="glass-button px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2 bg-blue-500/20 text-blue-700">
                                                <i data-feather="settings" class="w-4 h-4"></i>
                                                Manage
                                            </button>
                                        @else
                                            @php
                                                // Check if user is already a member via classes
                                                $isMemberViaClasses = false;
                                                if (Auth::check()) {
                                                    $isMemberViaClasses = DB::table('class_user')
                                                        ->join('class_organization', 'class_user.class_id', '=', 'class_organization.class_id')
                                                        ->where('class_organization.organization_id', $organization->id)
                                                        ->where('class_user.user_id', Auth::id())
                                                        ->exists();
                                                }
                                            @endphp

                                            @if($isMemberViaClasses)
                                                <button
                                                    class="glass-button px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2 bg-blue-500/20 text-blue-700"
                                                    disabled>
                                                    <i data-feather="check" class="w-4 h-4"></i>
                                                    Member via Class
                                                </button>
                                            @else
                                                <button
                                                    class="glass-button px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2 bg-green-500/20 text-green-700 join-organization-btn"
                                                    data-org-id="{{ $organization->id }}">
                                                    <i data-feather="user-plus" class="w-4 h-4"></i>
                                                    Join Organization
                                                </button>
                                            @endif
                                        @endif
                                    @endauth
                                </div>
                            </div>

                            <p class="text-slate-600 mb-6 leading-relaxed">
                                {{ $organization->address ? $organization->address . ', ' . $organization->postcode . ' ' . $organization->district . ', ' . $organization->state : 'No address provided' }}
                            </p>

                            <!-- Organization Stats -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="glass-card p-4 rounded-xl">
                                    <div class="text-2xl font-bold text-slate-800 mb-1">
                                        {{ number_format($memberCount) }}</div>
                                    <div class="text-sm text-slate-600 flex items-center gap-2">
                                        <i data-feather="users" class="w-4 h-4"></i>
                                        Total Members
                                    </div>
                                </div>
                                <div class="glass-card p-4 rounded-xl">
                                    <div class="text-2xl font-bold text-slate-800 mb-1">{{ $classesCount }}</div>
                                    <div class="text-sm text-slate-600 flex items-center gap-2">
                                        <i data-feather="book" class="w-4 h-4"></i>
                                        Active Classes
                                    </div>
                                </div>
                                <div class="glass-card p-4 rounded-xl">
                                    <div class="text-2xl font-bold text-slate-800 mb-1">{{ $teachersCount }}</div>
                                    <div class="text-sm text-slate-600 flex items-center gap-2">
                                        <i data-feather="user-check" class="w-4 h-4"></i>
                                        Teachers/Staff
                                    </div>
                                </div>
                                <div class="glass-card p-4 rounded-xl">
                                    <div class="text-2xl font-bold text-slate-800 mb-1">{{ $adminsCount }}</div>
                                    <div class="text-sm text-slate-600 flex items-center gap-2">
                                        <i data-feather="shield" class="w-4 h-4"></i>
                                        Administrators
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <div class="glass-card rounded-t-2xl px-6 pt-4 pb-0 mb-0">
                    <div class="flex overflow-x-auto">
                        <button class="tab-button active" data-tab="overview">
                            <i data-feather="info" class="w-4 h-4 mr-2"></i>
                            Overview
                        </button>
                        <button class="tab-button" data-tab="members">
                            <i data-feather="users" class="w-4 h-4 mr-2"></i>
                            Members
                            <span
                                class="ml-2 text-xs bg-slate-200 text-slate-700 px-2 py-0.5 rounded-full">{{ $memberCount }}</span>
                        </button>
                        <button class="tab-button" data-tab="classes">
                            <i data-feather="book" class="w-4 h-4 mr-2"></i>
                            Classes
                            <span
                                class="ml-2 text-xs bg-slate-200 text-slate-700 px-2 py-0.5 rounded-full">{{ $classesCount }}</span>
                        </button>
                        <!-- Add Announcements tab -->
                        <button class="tab-button" data-tab="announcements">
                            <i data-feather="bell" class="w-4 h-4 mr-2"></i>
                            Announcements
                            <span
                                class="ml-2 text-xs bg-slate-200 text-slate-700 px-2 py-0.5 rounded-full">{{ count($announcements) }}</span>
                        </button>
                        @auth
                            @if(Auth::user()->organizations()->where('organizations.id', $organization->id)->exists())
                                <button class="tab-button" data-tab="settings">
                                    <i data-feather="settings" class="w-4 h-4 mr-2"></i>
                                    Settings
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="min-h-[500px]">
                    <!-- Overview Tab -->
                    <div id="overviewTab" class="tab-content active">
                        <div class="glass-card rounded-b-2xl rounded-tr-2xl p-6">
                            <div class="grid lg:grid-cols-3 gap-6">
                                <!-- Left Column -->
                                <div class="lg:col-span-2 space-y-6">
                                    <!-- About Section -->
                                    <div>
                                        <h2 class="text-xl font-semibold text-slate-800 mb-4">About This Organization
                                        </h2>
                                        <div class="space-y-4">
                                            <p class="text-slate-600">
                                                {{ $organization->nama }} is a {{ $organization->type }} organization
                                                based in {{ $organization->district ?: $organization->state }}.
                                                @if($organization->type == 'school')
                                                    We are committed to providing quality education and holistic development
                                                    for our students.
                                                @elseif($organization->type == 'masjid')
                                                    We serve the community through religious activities, education, and
                                                    community services.
                                                @elseif($organization->type == 'tuition')
                                                    We provide supplementary education to help students excel in their
                                                    academic pursuits.
                                                @else
                                                    We are dedicated to serving our community and members.
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="space-y-6">
                                    <!-- Contact Information -->
                                    <div class="glass-card p-5 rounded-xl">
                                        <h3 class="font-semibold text-slate-800 mb-4">Contact Information</h3>
                                        <div class="space-y-3">
                                            @if($organization->email)
                                                <div class="flex items-center gap-3">
                                                    <i data-feather="mail" class="w-4 h-4 text-slate-500"></i>
                                                    <span class="text-sm text-slate-700">{{ $organization->email }}</span>
                                                </div>
                                            @endif
                                            @if($organization->telno)
                                                <div class="flex items-center gap-3">
                                                    <i data-feather="phone" class="w-4 h-4 text-slate-500"></i>
                                                    <span class="text-sm text-slate-700">{{ $organization->telno }}</span>
                                                </div>
                                            @endif
                                            @if($organization->address)
                                                <div class="flex items-center gap-3">
                                                    <i data-feather="map-pin" class="w-4 h-4 text-slate-500"></i>
                                                    <span class="text-sm text-slate-700">{{ $organization->address }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Organization Code -->
                                    <div class="glass-card p-5 rounded-xl">
                                        <h3 class="font-semibold text-slate-800 mb-3">Organization Code</h3>
                                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
                                            <div class="text-3xl font-bold text-blue-700 tracking-wider mb-2">
                                                {{ $organization->code }}</div>
                                            <p class="text-sm text-slate-600">Share this code with others to join this
                                                organization</p>
                                        </div>
                                    </div>

                                    <!-- Organization Type -->
                                    <div class="glass-card p-5 rounded-xl">
                                        <h3 class="font-semibold text-slate-800 mb-3">Organization Type</h3>
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="{{ $organization->typeInfo['badge'] }} text-xs px-3 py-1 rounded-full font-medium">
                                                {{ ucfirst($organization->type) }}
                                            </span>
                                            <i data-feather="{{ $organization->typeInfo['icon'] }}"
                                                class="w-5 h-5 {{ $organization->typeInfo['text'] }}"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Members Tab -->
                    <div id="membersTab" class="tab-content hidden">
                        <div class="glass-card rounded-b-2xl rounded-tr-2xl p-6">
                            <!-- Members Header -->
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 gap-4">
                                <div>
                                    <h2 class="text-xl font-semibold text-slate-800">Organization Members</h2>
                                    <p class="text-slate-600 text-sm">All users in this organization</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i data-feather="search" class="w-4 h-4 text-slate-500"></i>
                                        </div>
                                        <input type="text" id="memberSearch" placeholder="Search members..."
                                            class="glass-input pl-10 pr-4 py-2 rounded-xl focus:outline-none placeholder-slate-500 text-slate-700 text-sm w-full lg:w-64">
                                    </div>
                                    <select id="roleFilter"
                                        class="glass-input px-3 py-2 rounded-xl text-sm text-slate-700 focus:outline-none">
                                        <option value="">All Roles</option>
                                        <option value="admin">Administrators</option>
                                        <option value="teacher">Teachers/Staff</option>
                                        <option value="member">Members</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Members Grid -->
                            <div id="membersGrid" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                @foreach($members as $member)
                                    <div class="member-card p-4 rounded-xl cursor-pointer"
                                        data-member-id="{{ $member['id'] }}">
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center flex-shrink-0">
                                                <i data-feather="user" class="w-6 h-6 text-blue-600"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between mb-1">
                                                    <h3 class="font-semibold text-slate-800 truncate">{{ $member['name'] }}
                                                    </h3>
                                                    <span
                                                        class="role-{{ $member['role'] }} text-xs px-2 py-1 rounded-full font-medium ml-2">{{ $member['role'] }}</span>
                                                </div>
                                                <p class="text-sm text-slate-600 truncate mb-2">{{ $member['email'] }}</p>
                                                <div class="flex items-center justify-between">
                                                    <span
                                                        class="status-{{ $member['status'] }} text-xs px-2 py-1 rounded-full">{{ $member['status'] }}</span>
                                                    <span class="text-xs text-slate-500">Member</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 pt-4 border-t border-white/20 flex justify-between">
                                            <button
                                                class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1 view-member-btn"
                                                data-member-id="{{ $member['id'] }}">
                                                <i data-feather="eye" class="w-4 h-4"></i>
                                                View
                                            </button>
                                            @if($member['is_admin'])
                                                <span
                                                    class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Admin</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- No Members State -->
                            <div id="noMembersMessage"
                                class="{{ $members->count() > 0 ? 'hidden' : '' }} text-center py-12">
                                <i data-feather="users" class="w-16 h-16 text-slate-400 mx-auto mb-4"></i>
                                <h3 class="text-xl font-semibold text-slate-700 mb-2">No members found</h3>
                                <p class="text-slate-500">This organization doesn't have any members yet</p>
                            </div>
                        </div>
                    </div>

                    <!-- Classes Tab -->
                    <div id="classesTab" class="tab-content hidden">
                        <div class="glass-card rounded-b-2xl rounded-tr-2xl p-6">
                            <!-- Classes Header -->
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 gap-4">
                                <div>
                                    <h2 class="text-xl font-semibold text-slate-800">Organization Classes</h2>
                                    <p class="text-slate-600 text-sm">All classes within this organization</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i data-feather="search" class="w-4 h-4 text-slate-500"></i>
                                        </div>
                                        <input type="text" id="classSearch" placeholder="Search classes..."
                                            class="glass-input pl-10 pr-4 py-2 rounded-xl focus:outline-none placeholder-slate-500 text-slate-700 text-sm w-full lg:w-64">
                                    </div>
                                    @auth
                                        @if($userCanCreateClass)
                                            {{-- <button
                                                class="glass-button px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2 bg-blue-500/20 text-blue-700 hover:bg-blue-500/30 transition-colors"
                                                onclick="openCreateClassModal()">
                                                <i data-feather="plus" class="w-4 h-4"></i>
                                                Create Class
                                            </button> --}}
                                        @endif
                                    @endauth
                                </div>
                            </div>

                            <!-- Classes Grid -->
                            <div id="classesGrid" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                                @foreach($classes as $class)
                                    <div class="class-card p-5 rounded-xl">
                                        <div class="flex items-start justify-between mb-4">
                                            <div
                                                class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                                                <i data-feather="book" class="w-6 h-6 text-blue-600"></i>
                                            </div>
                                            <span
                                                class="status-{{ $class->formatted_status }} text-xs px-3 py-1 rounded-full font-medium">{{ $class->formatted_status }}</span>
                                        </div>
                                        <h3 class="font-bold text-slate-800 mb-2 text-lg">{{ $class->name }}</h3>
                                        <p class="text-slate-600 text-sm mb-4 leading-relaxed">{{ $class->description }}</p>

                                        <div class="flex items-center justify-between text-sm mb-4">
                                            <div class="flex items-center gap-2">
                                                <div class="flex items-center gap-1">
                                                    <i data-feather="user" class="w-4 h-4 text-slate-500"></i>
                                                    <span
                                                        class="font-medium text-slate-700">{{ $class->teacher_name }}</span>
                                                </div>
                                                <span class="text-slate-400">|</span>
                                                <div class="flex items-center gap-1">
                                                    <i data-feather="users" class="w-4 h-4 text-slate-500"></i>
                                                    <span
                                                        class="font-medium text-slate-700">{{ $class->member_count }}</span>
                                                    <span class="text-slate-500">members</span>
                                                </div>
                                            </div>
                                            <div class="text-slate-500 text-xs">{{ $class->code }}</div>
                                        </div>

                                        <div class="flex justify-between items-center">
                                            <div class="flex gap-2">
                                                @auth
                                                    @php
                                                        // Check if user is a member of this class
                                                        $isClassMember = $class->is_member;
                                                    @endphp

                                                    @if($isClassMember)
                                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-sm">✓
                                                            Joined</span>
                                                    @elseif(Auth::user()->organizations()->where('organizations.id', $organization->id)->exists())
                                                        <form action="{{ route('classes.join', $class->id) }}" method="POST"
                                                            class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="glass-button px-3 py-1 rounded-lg text-sm flex items-center gap-1 bg-blue-500/20 text-blue-700">
                                                                <i data-feather="user-plus" class="w-3 h-3"></i>
                                                                Join
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endauth
                                                <button
                                                    class="glass-button px-3 py-1 rounded-lg text-sm flex items-center gap-1 bg-blue-500/20 text-blue-700"
                                                    onclick="window.location.href='/classes/{{ $class->id }}'">
                                                    <i data-feather="arrow-right" class="w-3 h-3"></i>
                                                    Enter
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- No Classes State -->
                            <div id="noClassesMessage"
                                class="{{ $classes->count() > 0 ? 'hidden' : '' }} text-center py-12">
                                <i data-feather="book" class="w-16 h-16 text-slate-400 mx-auto mb-4"></i>
                                <h3 class="text-xl font-semibold text-slate-700 mb-2">No classes found</h3>
                                <p class="text-slate-500">Create your first class to get started</p>
                            </div>
                        </div>
                    </div>

                    <!-- Announcements Tab -->
                    <div id="announcementsTab" class="tab-content hidden">
                        <div class="glass-card rounded-b-2xl rounded-tr-2xl p-6">
                            <!-- Announcements Header -->
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 gap-4">
                                <div>
                                    <h2 class="text-xl font-semibold text-slate-800">Organization Announcements</h2>
                                    <p class="text-slate-600 text-sm">Important updates and news from the organization</p>
                                </div>
                                @auth
                                    @if(Auth::user()->organizations()->where('organizations.id', $organization->id)->exists())
                                        @php
                                            $currentUserRole = DB::table('organization_user')
                                                ->where('organization_id', $organization->id)
                                                ->where('user_id', Auth::id())
                                                ->join('organization_roles', 'organization_user.role_id', '=', 'organization_roles.id')
                                                ->select('organization_roles.nama as role_name')
                                                ->first();
                                            $canCreateAnnouncement = $currentUserRole && in_array(strtolower($currentUserRole->role_name), ['superadmin', 'admin']);
                                        @endphp
                                        @if($canCreateAnnouncement)
                                            <button class="glass-button px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2 bg-blue-500/20 text-blue-700 hover:bg-blue-500/30 transition-colors"
                                                    onclick="openCreateAnnouncementModal()">
                                                <i data-feather="plus" class="w-4 h-4"></i>
                                                New Announcement
                                            </button>
                                        @endif
                                    @endif
                                @endauth
                            </div>

                            <!-- Announcements List -->
                            <div id="announcementsList" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
    @if(count($announcements) > 0)
        @foreach($announcements as $announcement)
            <div class="announcement-card glass-card p-5 rounded-xl hover:bg-white/40 transition-all duration-200 cursor-pointer transform hover:-translate-y-1"
                 onclick="viewFullAnnouncement({{ $announcement['id'] }})"
                 data-announcement-id="{{ $announcement['id'] }}">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        @if($announcement['is_pinned'])
                            <i data-feather="pin" class="w-4 h-4 text-blue-600"></i>
                        @endif
                        <h3 class="font-bold text-slate-800 text-lg truncate">{{ $announcement['title'] }}</h3>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-xs text-slate-500">{{ $announcement['formatted_date'] }}</span>
                        @if($announcement['is_pinned'])
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Pinned</span>
                        @endif
                    </div>
                </div>

                <!-- Preview content (first 150 characters) -->
                <div class="text-slate-600 mb-4 leading-relaxed text-sm line-clamp-3">
                    {{ Str::limit(strip_tags($announcement['content']), 150) }}
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-white/20">
                    <div class="flex items-center gap-2">
                        <i data-feather="user" class="w-4 h-4 text-slate-500"></i>
                        <span class="text-sm text-slate-600">{{ $announcement['author'] }}</span>
                    </div>
                    <div class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1">
                        <span>Read more</span>
                        <i data-feather="arrow-right" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <!-- No Announcements State -->
        <div class="col-span-3 text-center py-12">
            <i data-feather="bell-off" class="w-16 h-16 text-slate-400 mx-auto mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-700 mb-2">No announcements yet</h3>
            <p class="text-slate-500">Check back later for updates from the organization</p>
        </div>
    @endif
</div>
                        </div>
                    </div>

                    <!-- Settings Tab (Only for members) -->
                    @auth
                        @if(Auth::user()->organizations()->where('organizations.id', $organization->id)->exists())
                            <div id="settingsTab" class="tab-content hidden">
                                <div class="glass-card rounded-b-2xl rounded-tr-2xl p-6">
                                    <h2 class="text-xl font-semibold text-slate-800 mb-6">Organization Settings</h2>
                                    <div class="max-w-2xl space-y-6">
                                        <!-- General Settings -->
                                        <div class="space-y-4">
                                            <h3 class="font-medium text-slate-800">General Settings</h3>
                                            <div class="glass-card p-4 rounded-xl">
                                                <form id="organizationSettingsForm" method="POST"
                                                    action="/organization/{{ $organization->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-slate-700 mb-1">Organization
                                                                Name</label>
                                                            <input type="text" name="nama" value="{{ $organization->nama }}"
                                                                class="glass-input w-full px-3 py-2 rounded-xl">
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                                                            <input type="email" name="email" value="{{ $organization->email }}"
                                                                class="glass-input w-full px-3 py-2 rounded-xl">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-slate-700 mb-1">Phone
                                                                Number</label>
                                                            <input type="text" name="telno" value="{{ $organization->telno }}"
                                                                class="glass-input w-full px-3 py-2 rounded-xl">
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-slate-700 mb-1">Address</label>
                                                            <textarea name="address"
                                                                class="glass-input w-full px-3 py-2 rounded-xl"
                                                                rows="3">{{ $organization->address }}</textarea>
                                                        </div>
                                                        <div class="flex gap-2">
                                                            <button type="submit"
                                                                class="glass-button px-4 py-2 rounded-xl text-sm font-medium bg-blue-500/20 text-blue-700">
                                                                Save Changes
                                                            </button>
                                                            <button type="button"
                                                                class="glass-button px-4 py-2 rounded-xl text-sm font-medium">
                                                                Cancel
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Danger Zone -->
                                        <div class="space-y-4 pt-6 border-t border-white/20">
                                            <h3 class="font-medium text-red-800">Danger Zone</h3>
                                            <div class="glass-card p-4 rounded-xl border border-red-200">
                                                <div class="space-y-3">
                                                    <p class="text-sm text-slate-700">
                                                        <span class="font-medium text-red-700">Delete Organization</span><br>
                                                        Once deleted, all data including members, classes, and settings will be
                                                        permanently removed.
                                                    </p>
                                                    <form method="POST" action="/organization/{{ $organization->id }}"
                                                        onsubmit="return confirm('Are you sure you want to delete this organization? This action cannot be undone.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-medium transition-colors">
                                                            Delete Organization
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Create Class Modal -->
    <div id="createClassModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
        <div class="glass-card rounded-2xl p-6 max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-slate-800">Create New Class</h3>
                <button class="p-2 hover:bg-white/30 rounded-xl transition-colors" onclick="closeCreateClassModal()">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form id="createClassForm" method="POST" action="{{ route('classes.store') }}">
                @csrf
                <input type="hidden" name="organization_id" id="modalOrganizationId" value="{{ $organization->id }}">

                <div class="space-y-4">
                    <!-- Class Name -->
                    <div>
                        <label for="className" class="block text-sm font-medium text-slate-700 mb-1">Class Name
                            *</label>
                        <input type="text" id="className" name="nama" required
                            class="glass-input w-full px-3 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                            placeholder="e.g., Mathematics 101">
                        <p class="text-xs text-slate-500 mt-1">Give your class a descriptive name</p>
                    </div>

                    <!-- Description (Optional) -->
                    <div>
                        <label for="classDescription"
                            class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                        <textarea id="classDescription" name="description" rows="3"
                            class="glass-input w-full px-3 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/30 resize-none"
                            placeholder="Optional: Describe what this class is about..."></textarea>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                        <div class="flex gap-4">
                            <label class="flex items-center">
                                <input type="radio" name="status" value="1" checked class="mr-2">
                                <span class="text-sm text-slate-700">Active</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="status" value="0" class="mr-2">
                                <span class="text-sm text-slate-700">Inactive</span>
                            </label>
                        </div>
                    </div>

                    <!-- Error Messages -->
                    <div id="createClassErrors" class="hidden">
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                            <div id="errorMessages"></div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeCreateClassModal()"
                            class="flex-1 glass-button py-2.5 rounded-xl text-sm font-medium hover:bg-white/40 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="createClassSubmit"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl text-sm font-medium transition-colors flex items-center justify-center gap-2">
                            <i data-feather="plus" class="w-4 h-4" id="submitIcon"></i>
                            <span id="submitText">Create Class</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Announcement Modal -->
    <div id="createAnnouncementModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
    <div class="glass-card rounded-2xl p-6 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-slate-800" id="announcementModalTitle">Create New Announcement</h3>
            <button class="p-2 hover:bg-white/30 rounded-xl transition-colors" onclick="closeCreateAnnouncementModal()">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="createAnnouncementForm" method="POST" action="{{ route('organization.announcements.store', $organization->id) }}">
            @csrf
            <input type="hidden" name="organization_id" value="{{ $organization->id }}">
            <input type="hidden" id="announcement_id" name="announcement_id" value="">

            <div class="space-y-4">
                <!-- Announcement Title -->
                <div>
                    <label for="announcementTitle" class="block text-sm font-medium text-slate-700 mb-1">Title *</label>
                    <input type="text" id="announcementTitle" name="title" required
                           class="glass-input w-full px-3 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                           placeholder="e.g., Important Update or Event Reminder">
                </div>

                <!-- Announcement Content -->
                <div>
                    <label for="announcementContent" class="block text-sm font-medium text-slate-700 mb-1">Content *</label>
                    <textarea id="announcementContent" name="content" rows="10" required
                              class="glass-input w-full px-3 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/30 resize-none"
                              placeholder="Write your announcement here..."></textarea>
                </div>

                <!-- Error Messages -->
                <div id="createAnnouncementErrors" class="hidden">
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                        <div id="announcementErrorMessages"></div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeCreateAnnouncementModal()"
                            class="flex-1 glass-button py-2.5 rounded-xl text-sm font-medium hover:bg-white/40 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="createAnnouncementSubmit"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl text-sm font-medium transition-colors flex items-center justify-center gap-2">
                        <i data-feather="bell" class="w-4 h-4" id="announcementSubmitIcon"></i>
                        <span id="announcementSubmitText">Create Announcement</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add this modal for viewing full announcement -->
<div id="viewAnnouncementModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
    <div class="glass-card rounded-2xl p-6 max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-slate-800" id="viewAnnouncementTitle"></h3>
            <button class="p-2 hover:bg-white/30 rounded-xl transition-colors" onclick="closeViewAnnouncementModal()">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="flex items-center justify-between mb-4 text-sm text-slate-600">
            <div class="flex items-center gap-2">
                <i data-feather="user" class="w-4 h-4"></i>
                <span id="viewAnnouncementAuthor"></span>
            </div>
            <div class="flex items-center gap-2">
                <i data-feather="calendar" class="w-4 h-4"></i>
                <span id="viewAnnouncementDate"></span>
            </div>
        </div>

        <div id="viewAnnouncementContent" class="prose prose-blue max-w-none text-slate-700 leading-relaxed mb-6">
            <!-- Content will be inserted here -->
        </div>

        <div class="pt-4 border-t border-white/20">
            <button onclick="closeViewAnnouncementModal()"
                    class="glass-button px-4 py-2 rounded-xl text-sm font-medium hover:bg-white/40 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

    <!-- Member Modal Template -->
    <div id="memberModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
        <div class="glass-card rounded-2xl p-6 max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-slate-800">Member Details</h3>
                <button class="p-2 hover:bg-white/30 rounded-xl transition-colors" onclick="closeMemberModal()">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div id="memberModalContent">
                <!-- Dynamic content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
    feather.replace();

    // Store members data in JavaScript for filtering
    const membersData = @json($members);
    const organizationId = {{ $organization->id }};

    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function () {
            const tabId = this.dataset.tab;

            // Update active tab button
            tabButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Show active tab content
            tabContents.forEach(content => {
                content.classList.remove('active');
                content.classList.add('hidden');
            });

            const activeContent = document.getElementById(`${tabId}Tab`);
            if (activeContent) {
                activeContent.classList.remove('hidden');
                activeContent.classList.add('active');
            }
        });
    });

    // Toast notification function
    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const toastId = 'toast-' + Date.now();

        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        const icon = type === 'success' ? 'check-circle' : 'alert-circle';

        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `toast glass-card p-4 rounded-xl flex items-center gap-3 ${bgColor}/20 backdrop-blur-sm border-l-4 ${type === 'success' ? 'border-green-500' : 'border-red-500'}`;
        toast.innerHTML = `
            <i data-feather="${icon}" class="w-5 h-5 ${type === 'success' ? 'text-green-600' : 'text-red-600'}"></i>
            <span class="text-sm font-medium text-slate-800">${message}</span>
        `;

        container.appendChild(toast);
        feather.replace();

        // Auto-remove after 3 seconds
        setTimeout(() => {
            if (document.getElementById(toastId)) {
                document.getElementById(toastId).remove();
            }
        }, 3000);
    }

    // Modal functions
    function openCreateClassModal() {
        // Reset form
        document.getElementById('createClassForm').reset();
        document.getElementById('createClassErrors').classList.add('hidden');

        // Show modal
        document.getElementById('createClassModal').classList.remove('hidden');

        // Focus on first input
        setTimeout(() => {
            document.getElementById('className').focus();
        }, 100);

        feather.replace();
    }

    function closeCreateClassModal() {
        document.getElementById('createClassModal').classList.add('hidden');
    }

    // Handle create class form submission
    document.getElementById('createClassForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = this;
        const submitButton = document.getElementById('createClassSubmit');
        const submitText = document.getElementById('submitText');
        const submitIcon = document.getElementById('submitIcon');
        const errorDiv = document.getElementById('createClassErrors');
        const errorMessages = document.getElementById('errorMessages');

        // Disable button and show loading state
        submitButton.disabled = true;
        submitText.textContent = 'Creating...';
        submitIcon.setAttribute('data-feather', 'loader');
        submitIcon.classList.add('animate-spin');
        feather.replace();

        // Hide any previous errors
        errorDiv.classList.add('hidden');

        try {
            const formData = new FormData(form);

            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Success - redirect to the new class page
                showToast('Class created successfully!', 'success');
                setTimeout(() => {
                    window.location.href = data.redirect_url || `/classes/${data.class_id}`;
                }, 1500);
            } else {
                // Show errors
                errorMessages.innerHTML = '';

                if (data.errors) {
                    // Laravel validation errors
                    Object.values(data.errors).forEach(errorArray => {
                        errorArray.forEach(error => {
                            errorMessages.innerHTML += `<p>${error}</p>`;
                        });
                    });
                } else if (data.message) {
                    // Single error message
                    errorMessages.innerHTML = `<p>${data.message}</p>`;
                } else {
                    errorMessages.innerHTML = '<p>An error occurred. Please try again.</p>';
                }

                errorDiv.classList.remove('hidden');

                // Re-enable button
                submitButton.disabled = false;
                submitText.textContent = 'Create Class';
                submitIcon.setAttribute('data-feather', 'plus');
                submitIcon.classList.remove('animate-spin');
                feather.replace();
            }

        } catch (error) {
            console.error('Error:', error);

            errorMessages.innerHTML = '<p>Network error. Please check your connection and try again.</p>';
            errorDiv.classList.remove('hidden');

            // Re-enable button
            submitButton.disabled = false;
            submitText.textContent = 'Create Class';
            submitIcon.setAttribute('data-feather', 'plus');
            submitIcon.classList.remove('animate-spin');
            feather.replace();
        }
    });

    // Close create class modal when clicking outside
    document.getElementById('createClassModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeCreateClassModal();
        }
    });

    // Close create class modal with Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !document.getElementById('createClassModal').classList.contains('hidden')) {
            closeCreateClassModal();
        }
    });

    // Announcement Modal Functions
    function openCreateAnnouncementModal(announcementId = null) {
        const form = document.getElementById('createAnnouncementForm');
        const modalTitle = document.getElementById('announcementModalTitle');
        const submitText = document.getElementById('announcementSubmitText');

        // Reset form
        form.reset();
        document.getElementById('createAnnouncementErrors').classList.add('hidden');

        if (announcementId) {
            // Edit mode
            modalTitle.textContent = 'Edit Announcement';
            submitText.textContent = 'Update Announcement';
            // Load announcement data via AJAX
            loadAnnouncementData(announcementId);
        } else {
            // Create mode
            modalTitle.textContent = 'Create New Announcement';
            submitText.textContent = 'Create Announcement';
            document.getElementById('announcement_id').value = '';
        }

        // Show modal
        document.getElementById('createAnnouncementModal').classList.remove('hidden');

        // Focus on title input
        setTimeout(() => {
            document.getElementById('announcementTitle').focus();
        }, 100);

        feather.replace();
    }

    function closeCreateAnnouncementModal() {
        document.getElementById('createAnnouncementModal').classList.add('hidden');
    }

    // View announcement functions
    function viewFullAnnouncement(announcementId) {
        // Find the announcement data
        const announcement = @json($announcements).find(a => a.id == announcementId);
        if (!announcement) return;

        // Populate the modal
        document.getElementById('viewAnnouncementTitle').textContent = announcement.title;
        document.getElementById('viewAnnouncementAuthor').textContent = 'Posted by ' + announcement.author;
        document.getElementById('viewAnnouncementDate').textContent = announcement.formatted_date;

        // Format and display content with line breaks
        const contentDiv = document.getElementById('viewAnnouncementContent');
        const formattedContent = announcement.content.replace(/\n/g, '<br>');
        contentDiv.innerHTML = formattedContent;

        // Show modal
        document.getElementById('viewAnnouncementModal').classList.remove('hidden');
        feather.replace();
    }

    function closeViewAnnouncementModal() {
        document.getElementById('viewAnnouncementModal').classList.add('hidden');
    }

    // Load announcement data for editing
    async function loadAnnouncementData(announcementId) {
        try {
            const response = await fetch(`/organization/${organizationId}/announcements/${announcementId}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (data.success) {
                const announcement = data.announcement;
                document.getElementById('announcement_id').value = announcement.id;
                document.getElementById('announcementTitle').value = announcement.title;
                document.getElementById('announcementContent').value = announcement.content;
            }
        } catch (error) {
            console.error('Error loading announcement:', error);
            showToast('Failed to load announcement data', 'error');
        }
    }

    // Handle announcement form submission
    document.getElementById('createAnnouncementForm')?.addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = this;
        const submitButton = document.getElementById('createAnnouncementSubmit');
        const submitText = document.getElementById('announcementSubmitText');
        const submitIcon = document.getElementById('announcementSubmitIcon');
        const errorDiv = document.getElementById('createAnnouncementErrors');
        const errorMessages = document.getElementById('announcementErrorMessages');
        const isEditMode = document.getElementById('announcement_id').value !== '';

        // Disable button and show loading state
        submitButton.disabled = true;
        submitText.textContent = isEditMode ? 'Updating...' : 'Creating...';
        submitIcon.setAttribute('data-feather', 'loader');
        submitIcon.classList.add('animate-spin');
        feather.replace();

        // Hide any previous errors
        errorDiv.classList.add('hidden');

        try {
            const formData = new FormData(form);
            const url = isEditMode
                ? `/organization/${organizationId}/announcements/${document.getElementById('announcement_id').value}`
                : form.action;
            const method = isEditMode ? 'PUT' : 'POST';

            const response = await fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showToast(data.message || (isEditMode ? 'Announcement updated!' : 'Announcement created!'), 'success');
                closeCreateAnnouncementModal();

                // Reload the page to show updated announcements
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                // Show errors
                errorMessages.innerHTML = '';

                if (data.errors) {
                    Object.values(data.errors).forEach(errorArray => {
                        errorArray.forEach(error => {
                            errorMessages.innerHTML += `<p>${error}</p>`;
                        });
                    });
                } else if (data.message) {
                    errorMessages.innerHTML = `<p>${data.message}</p>`;
                } else {
                    errorMessages.innerHTML = '<p>An error occurred. Please try again.</p>';
                }

                errorDiv.classList.remove('hidden');

                // Re-enable button
                submitButton.disabled = false;
                submitText.textContent = isEditMode ? 'Update Announcement' : 'Create Announcement';
                submitIcon.setAttribute('data-feather', 'bell');
                submitIcon.classList.remove('animate-spin');
                feather.replace();
            }

        } catch (error) {
            console.error('Error:', error);
            errorMessages.innerHTML = '<p>Network error. Please check your connection and try again.</p>';
            errorDiv.classList.remove('hidden');

            // Re-enable button
            submitButton.disabled = false;
            submitText.textContent = isEditMode ? 'Update Announcement' : 'Create Announcement';
            submitIcon.setAttribute('data-feather', 'bell');
            submitIcon.classList.remove('animate-spin');
            feather.replace();
        }
    });

    // Delete announcement
    async function deleteAnnouncement(announcementId) {
        // Prevent the card click event from firing
        event.stopPropagation();

        if (!confirm('Are you sure you want to delete this announcement? This action cannot be undone.')) {
            return;
        }

        try {
            const response = await fetch(`/organization/${organizationId}/announcements/${announcementId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (data.success) {
                showToast('Announcement deleted successfully!', 'success');
                // Remove announcement card from DOM
                const announcementCard = document.querySelector(`[data-announcement-id="${announcementId}"]`);
                if (announcementCard) {
                    announcementCard.remove();
                }

                // If no announcements left, show empty state
                const announcementsList = document.getElementById('announcementsList');
                if (announcementsList && announcementsList.querySelectorAll('.announcement-card').length === 0) {
                    announcementsList.innerHTML = `
                        <div class="col-span-3 text-center py-12">
                            <i data-feather="bell-off" class="w-16 h-16 text-slate-400 mx-auto mb-4"></i>
                            <h3 class="text-xl font-semibold text-slate-700 mb-2">No announcements yet</h3>
                            <p class="text-slate-500">Check back later for updates from the organization</p>
                        </div>
                    `;
                    feather.replace();
                }
            } else {
                showToast(data.message || 'Failed to delete announcement', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Network error. Please try again.', 'error');
        }
    }

    // Edit announcement
    function editAnnouncement(announcementId) {
        // Prevent the card click event from firing
        event.stopPropagation();
        openCreateAnnouncementModal(announcementId);
    }

    // View member details
    function viewMemberDetails(memberId) {
        const member = membersData.find(m => m.id == memberId);
        if (!member) return;

        const modalContent = document.getElementById('memberModalContent');
        const roleClass = `role-${member.role}`;
        const statusClass = `status-${member.status}`;

        modalContent.innerHTML = `
            <div class="text-center mb-6">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center mx-auto mb-4">
                    <i data-feather="user" class="w-10 h-10 text-blue-600"></i>
                </div>
                <h4 class="text-xl font-semibold text-slate-800 mb-1">${member.name}</h4>
                <div class="flex items-center justify-center gap-2 mb-3">
                    <span class="${roleClass} text-xs px-3 py-1 rounded-full font-medium">${member.role}</span>
                    <span class="${statusClass} text-xs px-3 py-1 rounded-full">${member.status}</span>
                </div>
                <p class="text-slate-600">${member.email}</p>
            </div>

            <div class="space-y-4">
                <div class="glass-card p-4 rounded-xl">
                    <h5 class="font-medium text-slate-700 mb-3">Personal Information</h5>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <div class="text-slate-500 mb-1">Username</div>
                            <div class="font-medium text-slate-800">${member.username}</div>
                        </div>
                        <div>
                            <div class="text-slate-500 mb-1">IC Number</div>
                            <div class="font-medium text-slate-800">${member.icno}</div>
                        </div>
                        <div>
                            <div class="text-slate-500 mb-1">Phone</div>
                            <div class="font-medium text-slate-800">${member.telno}</div>
                        </div>
                        <div>
                            <div class="text-slate-500 mb-1">Joined Date</div>
                            <div class="font-medium text-slate-800">${new Date(member.joined_date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            })}</div>
                        </div>
                    </div>
                </div>

                <div class="glass-card p-4 rounded-xl">
                    <h5 class="font-medium text-slate-700 mb-3">Organization Role</h5>
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-slate-800">${member.role.charAt(0).toUpperCase() + member.role.slice(1)}</div>
                            <div class="text-sm text-slate-500">Organization permissions</div>
                        </div>
                        ${member.is_admin ? `
                        <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-medium">Administrator</span>
                        ` : ''}
                    </div>
                </div>

                <div class="flex gap-2 pt-4">
                    <button class="flex-1 glass-button py-2 rounded-xl text-sm font-medium" onclick="closeMemberModal()">
                        Close
                    </button>
                </div>
            </div>
        `;

        feather.replace();
        document.getElementById('memberModal').classList.remove('hidden');
    }

    function closeMemberModal() {
        document.getElementById('memberModal').classList.add('hidden');
    }

    // Close modals when clicking outside
    document.getElementById('createAnnouncementModal')?.addEventListener('click', function (e) {
        if (e.target === this) {
            closeCreateAnnouncementModal();
        }
    });

    document.getElementById('viewAnnouncementModal')?.addEventListener('click', function (e) {
        if (e.target === this) {
            closeViewAnnouncementModal();
        }
    });

    document.getElementById('memberModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeMemberModal();
        }
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            if (!document.getElementById('createAnnouncementModal').classList.contains('hidden')) {
                closeCreateAnnouncementModal();
            }
            if (!document.getElementById('createClassModal').classList.contains('hidden')) {
                closeCreateClassModal();
            }
            if (!document.getElementById('memberModal').classList.contains('hidden')) {
                closeMemberModal();
            }
            if (!document.getElementById('viewAnnouncementModal').classList.contains('hidden')) {
                closeViewAnnouncementModal();
            }
        }
    });

    // Join organization button with membership check
    document.querySelector('.join-organization-btn')?.addEventListener('click', function () {
        const orgId = this.dataset.orgId;
        const button = this;

        // Check if user is already a member via classes
        fetch(`/api/check-org-membership/${orgId}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.is_member) {
                showToast('You are already a member of this organization through your class membership.', 'info');
                button.innerHTML = '<i data-feather="check" class="w-4 h-4"></i> Member via Class';
                button.disabled = true;
                button.classList.remove('bg-green-500/20', 'text-green-700');
                button.classList.add('bg-blue-500/20', 'text-blue-700');
                feather.replace();
                return;
            }

            // Proceed with joining organization
            button.innerHTML = '<i data-feather="loader" class="w-4 h-4 animate-spin"></i> Joining...';
            button.disabled = true;

            fetch('/api/join-organization', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    organization_id: orgId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message || 'Join request sent successfully!', 'success');
                    button.innerHTML = '<i data-feather="check" class="w-4 h-4"></i> Pending Approval';
                    button.classList.remove('bg-green-500/20', 'text-green-700');
                    button.classList.add('bg-yellow-500/20', 'text-yellow-700');
                } else {
                    showToast(data.message || 'Failed to join organization', 'error');
                    button.innerHTML = '<i data-feather="user-plus" class="w-4 h-4"></i> Join Organization';
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Network error. Please try again.', 'error');
                button.innerHTML = '<i data-feather="user-plus" class="w-4 h-4"></i> Join Organization';
                button.disabled = false;
            });

            feather.replace();
        })
        .catch(error => {
            console.error('Error checking membership:', error);
            showToast('Error checking membership status', 'error');
        });
    });

    // Filter members
    document.getElementById('memberSearch')?.addEventListener('input', function (e) {
        filterMembers(e.target.value, document.getElementById('roleFilter').value);
    });

    document.getElementById('roleFilter')?.addEventListener('change', function (e) {
        filterMembers(document.getElementById('memberSearch').value, e.target.value);
    });

    function filterMembers(searchTerm = '', roleFilter = '') {
        const filteredMembers = membersData.filter(member => {
            const matchesSearch = searchTerm === '' ||
                member.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                member.email.toLowerCase().includes(searchTerm.toLowerCase()) ||
                member.username.toLowerCase().includes(searchTerm.toLowerCase());

            const matchesRole = roleFilter === '' || member.role === roleFilter;

            return matchesSearch && matchesRole;
        });

        const membersGrid = document.getElementById('membersGrid');
        const noMembersMessage = document.getElementById('noMembersMessage');

        if (filteredMembers.length === 0) {
            membersGrid.innerHTML = '';
            noMembersMessage.classList.remove('hidden');
            return;
        }

        noMembersMessage.classList.add('hidden');
        membersGrid.innerHTML = '';

        filteredMembers.forEach(member => {
            const roleClass = `role-${member.role}`;
            const statusClass = `status-${member.status}`;

            const memberCard = document.createElement('div');
            memberCard.className = 'member-card p-4 rounded-xl cursor-pointer';
            memberCard.dataset.memberId = member.id;
            memberCard.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center flex-shrink-0">
                        <i data-feather="user" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between mb-1">
                            <h3 class="font-semibold text-slate-800 truncate">${member.name}</h3>
                            <span class="${roleClass} text-xs px-2 py-1 rounded-full font-medium ml-2">${member.role}</span>
                        </div>
                        <p class="text-sm text-slate-600 truncate mb-2">${member.email}</p>
                        <div class="flex items-center justify-between">
                            <span class="${statusClass} text-xs px-2 py-1 rounded-full">${member.status}</span>
                            <span class="text-xs text-slate-500">Joined ${new Date(member.joined_date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                })}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-white/20 flex justify-between">
                    <button class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1 view-member-btn" data-member-id="${member.id}">
                        <i data-feather="eye" class="w-4 h-4"></i>
                        View
                    </button>
                    ${member.is_admin ? `
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Admin</span>
                    ` : ''}
                </div>
            `;

            membersGrid.appendChild(memberCard);
        });

        // Re-attach event listeners to view buttons
        document.querySelectorAll('.view-member-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                viewMemberDetails(this.dataset.memberId);
            });
        });

        feather.replace();
    }

    // Filter classes
    document.getElementById('classSearch')?.addEventListener('input', function (e) {
        const searchTerm = e.target.value.toLowerCase();
        const classCards = document.querySelectorAll('#classesGrid .class-card');

        let visibleCount = 0;

        classCards.forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            const teacher = card.querySelector('.font-medium.text-slate-700').textContent.toLowerCase();
            const description = card.querySelector('p.text-slate-600').textContent.toLowerCase();
            const code = card.querySelector('.text-slate-500.text-xs').textContent.toLowerCase();

            if (searchTerm === '' ||
                name.includes(searchTerm) ||
                teacher.includes(searchTerm) ||
                description.includes(searchTerm) ||
                code.includes(searchTerm)) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const noClassesMessage = document.getElementById('noClassesMessage');
        if (visibleCount === 0) {
            noClassesMessage.classList.remove('hidden');
        } else {
            noClassesMessage.classList.add('hidden');
        }
    });

    // Initialize event listeners for view member buttons
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.view-member-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                viewMemberDetails(this.dataset.memberId);
            });
        });
    });
</script>
</body>

</html>
