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
            <a href="/search" class="glass-card p-3 rounded-xl bg-white/40 transition-all duration-200">
                <i data-feather="search" class="w-5 h-5 text-blue-600"></i>
            </a>
            <a href="/my-organizations"
                class="glass-card p-3 rounded-xl hover:bg-white/30 transition-all duration-200 group">
                <i data-feather="users" class="w-5 h-5 text-slate-600 group-hover:text-blue-600"></i>
            </a>
            <a href="/my-classes" class="glass-card p-3 rounded-xl hover:bg-white/30 transition-all duration-200 group">
                <i data-feather="book" class="w-5 h-5 text-slate-600 group-hover:text-blue-600"></i>
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
                                            {{ $organization->nama }}
                                        </h1>
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
                                            {{-- <button
                                                class="glass-button px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2">
                                                <i data-feather="edit" class="w-4 h-4"></i>
                                                Edit Organization
                                            </button>
                                            <button
                                                class="glass-button px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2 bg-blue-500/20 text-blue-700">
                                                <i data-feather="settings" class="w-4 h-4"></i>
                                                Manage
                                            </button> --}}
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
                                        {{ number_format($memberCount) }}
                                    </div>
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
                    <div class="flex overflow-x-auto overflow-y-hidden">
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
                        {{-- @auth
                        @if(Auth::user()->organizations()->where('organizations.id', $organization->id)->exists())
                        <button class="tab-button" data-tab="settings">
                            <i data-feather="settings" class="w-4 h-4 mr-2"></i>
                            Settings
                        </button>
                        @endif
                        @endauth --}}
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
                                                {{ $organization->code }}
                                            </div>
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
                                @foreach($members->take(12) as $member)
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

                            <!-- Load More Members Section -->
                            @if($members->count() > 12)
                                <div class="mt-6 text-center">
                                    <button id="loadMoreMembers"
                                        class="glass-button px-6 py-3 rounded-xl text-sm font-medium flex items-center gap-2 mx-auto hover:bg-white/40 transition-all duration-300 hover:scale-105">
                                        <i data-feather="plus" class="w-5 h-5"></i>
                                        Load More Members
                                    </button>
                                    <div id="membersLoadingSpinner" class="hidden mt-4">
                                        <div class="loading-spinner mx-auto"></div>
                                        <p class="text-slate-600 mt-2">Loading more members...</p>
                                    </div>
                                    <div id="membersEndMessage" class="hidden mt-4">
                                        <i data-feather="check-circle" class="w-10 h-10 text-green-500 mx-auto mb-2"></i>
                                        <p class="text-slate-500">All members have been loaded.</p>
                                    </div>
                                </div>
                            @endif

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
                                            <button
                                                class="glass-button px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2 bg-blue-500/20 text-blue-700 hover:bg-blue-500/30 transition-colors"
                                                onclick="openCreateClassModal()">
                                                <i data-feather="plus" class="w-4 h-4"></i>
                                                Create Class
                                            </button>
                                        @endif
                                    @endauth
                                </div>
                            </div>

                            <!-- Classes Grid -->
                            <div id="classesGrid" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                                @foreach($initialClasses as $class)
                                    <div class="class-card p-5 rounded-xl hover:bg-white/20 transition-colors duration-200">
                                        <div class="flex items-start justify-between mb-4">
                                            <div
                                                class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                                                <i data-feather="book" class="w-6 h-6 text-blue-600"></i>
                                            </div>
                                            <span
                                                class="status-{{ $class->formatted_status }} text-xs px-3 py-1 rounded-full font-medium">
                                                {{ $class->formatted_status }}
                                            </span>
                                        </div>
                                        <h3
                                            class="font-bold text-slate-800 mb-2 text-lg hover:text-blue-600 transition-colors duration-200">
                                            {{ $class->name }}
                                        </h3>
                                        <p class="text-slate-600 text-sm mb-4 leading-relaxed">
                                            {{ $class->description ?? 'Class description here' }}</p>

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
                                                        class="font-medium text-slate-700">{{ $class->member_count ?? 0 }}</span>
                                                    <span class="text-slate-500">members</span>
                                                </div>
                                            </div>
                                            <div class="text-slate-500 text-xs">{{ $class->code }}</div>
                                        </div>

                                        <div class="flex justify-between items-center">
                                            <div class="flex gap-2">
                                                @if($class->is_member)
                                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-sm">✓
                                                        Joined</span>
                                                @elseif($class->can_join)
                                                    <form action="{{ route('classes.join', $class->id) }}" method="POST"
                                                        class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="glass-button px-3 py-1 rounded-lg text-sm flex items-center gap-1 bg-blue-500/20 text-blue-700 hover:bg-blue-500/30 transition-colors duration-200">
                                                            <i data-feather="user-plus" class="w-3 h-3"></i>
                                                            Join
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-sm text-slate-500">Join organization first</span>
                                                @endif
                                                <button
                                                    class="glass-button px-3 py-1 rounded-lg text-sm flex items-center gap-1 bg-blue-500/20 text-blue-700 hover:bg-blue-500/30 transition-colors duration-200"
                                                    onclick="window.location.href='{{ route('classes.show', $class->id) }}'">
                                                    <i data-feather="arrow-right" class="w-3 h-3"></i>
                                                    Enter
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Load More Classes Section -->
                            @if($classesCount > count($initialClasses))
                                <div class="mt-6 text-center">
                                    <button id="loadMoreClasses"
                                        class="glass-button px-6 py-3 rounded-xl text-sm font-medium flex items-center gap-2 mx-auto hover:bg-white/40 transition-all duration-300 hover:scale-105">
                                        <i data-feather="plus" class="w-5 h-5"></i>
                                        Load More Classes
                                    </button>
                                    <div id="classesLoadingSpinner" class="hidden mt-4">
                                        <div class="loading-spinner mx-auto"></div>
                                        <p class="text-slate-600 mt-2">Loading more classes...</p>
                                    </div>
                                    <div id="classesEndMessage" class="hidden mt-4">
                                        <i data-feather="check-circle" class="w-10 h-10 text-green-500 mx-auto mb-2"></i>
                                        <p class="text-slate-500">All classes have been loaded.</p>
                                    </div>
                                </div>
                            @endif

                            <!-- No Classes State -->
                            <div id="noClassesMessage"
                                class="{{ count($initialClasses) > 0 ? 'hidden' : '' }} text-center py-12">
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
                                    <p class="text-slate-600 text-sm">Important updates and news from the organization
                                    </p>
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
                                            <button
                                                class="glass-button px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2 bg-blue-500/20 text-blue-700 hover:bg-blue-500/30 transition-colors"
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
                                                    <h3 class="font-bold text-slate-800 text-lg truncate">
                                                        {{ $announcement['title'] }}
                                                    </h3>
                                                </div>
                                                <div class="flex flex-col items-end gap-1">
                                                    <span
                                                        class="text-xs text-slate-500">{{ $announcement['formatted_date'] }}</span>
                                                    @if($announcement['is_pinned'])
                                                        <span
                                                            class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Pinned</span>
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
                                                <div class="flex items-center gap-2 mt-2">
                                                    <i data-feather="target" class="w-3 h-3 text-slate-500"></i>
                                                    <span class="text-xs text-slate-600">
                                                        Target:
                                                        {{ $announcement['tahap'] == 'both' ? 'All Levels' : ('Tahap ' . $announcement['tahap']) }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1">
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
    <div class="bg-white rounded-2xl p-6 max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-gray-200">
        <!-- Modal Header -->
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
            <div>
                <h3 class="text-2xl font-bold text-slate-800" id="announcementModalTitle">Create New Announcement</h3>
                <p class="text-sm text-slate-500 mt-1">Share important updates with your organization members</p>
            </div>
            <button class="p-2 hover:bg-gray-100 rounded-xl transition-colors duration-200"
                onclick="closeCreateAnnouncementModal()">
                <i data-feather="x" class="w-5 h-5 text-slate-600"></i>
            </button>
        </div>

        <form id="createAnnouncementForm" method="POST"
            action="{{ route('organization.announcements.store', $organization->id) }}">
            @csrf
            <input type="hidden" name="organization_id" value="{{ $organization->id }}">
            <input type="hidden" id="announcement_id" name="announcement_id" value="">

            <div class="space-y-6">
                <!-- Announcement Title -->
                <div>
                    <label for="announcementTitle" class="block text-sm font-semibold text-slate-700 mb-2">
                        Announcement Title *
                        <span class="text-xs font-normal text-slate-400 ml-1">(Be clear and concise)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute left-3 top-3 text-slate-400">
                            <i data-feather="type" class="w-5 h-5"></i>
                        </div>
                        <input type="text" id="announcementTitle" name="title" required
                            class="w-full pl-10 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700 placeholder-slate-400 shadow-sm transition-all duration-200"
                            placeholder="e.g., Important Update: School Holiday Schedule">
                        <div class="absolute right-3 top-3">
                            <span class="text-xs text-slate-400 bg-slate-100 px-2 py-1 rounded-full" id="titleCount">0/100</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Maximum 100 characters</p>
                </div>

                <!-- Announcement Content -->
                <div>
                    <label for="announcementContent" class="block text-sm font-semibold text-slate-700 mb-2">
                        Announcement Content *
                        <span class="text-xs font-normal text-slate-400 ml-1">(What do you want to share?)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute left-3 top-3 text-slate-400">
                            <i data-feather="edit" class="w-5 h-5"></i>
                        </div>
                        <textarea id="announcementContent" name="content" rows="8" required
                            class="w-full pl-10 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700 placeholder-slate-400 shadow-sm resize-none transition-all duration-200"
                            placeholder="Write your announcement here... Be descriptive and include all important details."></textarea>
                        <div class="absolute right-3 bottom-3">
                            <span class="text-xs text-slate-400 bg-slate-100 px-2 py-1 rounded-full" id="contentCount">0/2000</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-2 text-xs text-slate-500">
                        <i data-feather="info" class="w-3 h-3"></i>
                        <span>You can use <strong>**bold**</strong>, <em>*italic*</em>, and `code` formatting</span>
                    </div>
                </div>

                <!-- Target Audience -->
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-3">
                        <div class="flex items-center gap-2">
                            <i data-feather="target" class="w-4 h-4 text-blue-600"></i>
                            Target Audience *
                            <span class="text-xs font-normal text-slate-400 ml-1">(Who should see this?)</span>
                        </div>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <label class="relative">
                            <input type="radio" name="tahap" value="1" class="peer hidden" checked>
                            <div class="flex flex-col items-center justify-center p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 cursor-pointer transition-all duration-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-sm">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mb-2">
                                    <i data-feather="book-open" class="w-4 h-4 text-blue-600"></i>
                                </div>
                                <span class="font-medium text-slate-700">Tahap 1 Only</span>
                                <span class="text-xs text-slate-500 mt-1">Primary Level</span>
                            </div>
                        </label>

                        <label class="relative">
                            <input type="radio" name="tahap" value="2" class="peer hidden">
                            <div class="flex flex-col items-center justify-center p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 cursor-pointer transition-all duration-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-sm">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mb-2">
                                    <i data-feather="book" class="w-4 h-4 text-blue-600"></i>
                                </div>
                                <span class="font-medium text-slate-700">Tahap 2 Only</span>
                                <span class="text-xs text-slate-500 mt-1">Secondary Level</span>
                            </div>
                        </label>

                        <label class="relative">
                            <input type="radio" name="tahap" value="both" class="peer hidden">
                            <div class="flex flex-col items-center justify-center p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 cursor-pointer transition-all duration-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-sm">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mb-2">
                                    <i data-feather="users" class="w-4 h-4 text-blue-600"></i>
                                </div>
                                <span class="font-medium text-slate-700">Both Tahaps</span>
                                <span class="text-xs text-slate-500 mt-1">All Members</span>
                            </div>
                        </label>
                    </div>
                    <p class="text-xs text-slate-500 mt-3">Select who should receive this announcement</p>
                </div>

                <!-- Optional Settings -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                        <i data-feather="settings" class="w-4 h-4 text-slate-600"></i>
                        Optional Settings
                    </h4>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors duration-200">
                            <div class="relative">
                                <input type="checkbox" name="is_pinned" value="1" class="peer hidden">
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-md flex items-center justify-center peer-checked:bg-blue-500 peer-checked:border-blue-500 transition-all duration-200">
                                    <i data-feather="check" class="w-3 h-3 text-white hidden peer-checked:block"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <span class="font-medium text-slate-700">Pin this announcement</span>
                                <p class="text-xs text-slate-500 mt-1">Pinned announcements stay at the top of the list</p>
                            </div>
                            <i data-feather="pin" class="w-4 h-4 text-blue-500"></i>
                        </label>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                        <i data-feather="eye" class="w-4 h-4 text-slate-600"></i>
                        Preview
                    </h4>
                    <div id="announcementPreview" class="bg-white rounded-lg p-4 border border-slate-300 min-h-[80px]">
                        <p class="text-slate-500 italic">Preview will appear here as you type...</p>
                    </div>
                </div>

                <!-- Error Messages -->
                <div id="createAnnouncementErrors" class="hidden">
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                        <div class="flex items-center gap-3 text-red-700 mb-2">
                            <i data-feather="alert-triangle" class="w-5 h-5"></i>
                            <span class="font-semibold">Please fix the following errors:</span>
                        </div>
                        <div id="announcementErrorMessages" class="text-sm text-red-600 ml-8 space-y-1"></div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeCreateAnnouncementModal()"
                        class="flex-1 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 hover:border-gray-400 py-3 rounded-xl text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2 shadow-sm hover:shadow">
                        <i data-feather="x" class="w-4 h-4"></i>
                        Cancel
                    </button>
                    <button type="submit" id="createAnnouncementSubmit"
                        class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white py-3 rounded-xl text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
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
                <button class="p-2 hover:bg-white/30 rounded-xl transition-colors"
                    onclick="closeViewAnnouncementModal()">
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
const classesData = @json($initialClasses);
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

// Load More functionality for Members Tab
let currentMemberPage = 1;
let currentClassPage = 1;
let isLoadingMembers = false;
let isLoadingClasses = false;
let hasMoreMembers = {{ $memberCount > 12 ? 'true' : 'false' }};
let hasMoreClasses = {{ $classesCount > count($initialClasses) ? 'true' : 'false' }};
const membersPerPage = 12;
const classesPerPage = 9;

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Character counter and preview for announcement modal
function initializeAnnouncementModal() {
    const titleInput = document.getElementById('announcementTitle');
    const contentInput = document.getElementById('announcementContent');
    const titleCount = document.getElementById('titleCount');
    const contentCount = document.getElementById('contentCount');
    const previewDiv = document.getElementById('announcementPreview');

    if (titleInput) {
        titleInput.addEventListener('input', function() {
            const length = this.value.length;
            titleCount.textContent = `${length}/100`;

            // Update color based on length
            if (length > 90) {
                titleCount.classList.remove('text-slate-400', 'bg-slate-100');
                titleCount.classList.add('text-red-600', 'bg-red-100');
            } else if (length > 70) {
                titleCount.classList.remove('text-slate-400', 'bg-slate-100', 'text-red-600', 'bg-red-100');
                titleCount.classList.add('text-yellow-600', 'bg-yellow-100');
            } else {
                titleCount.classList.remove('text-yellow-600', 'bg-yellow-100', 'text-red-600', 'bg-red-100');
                titleCount.classList.add('text-slate-400', 'bg-slate-100');
            }

            updatePreview();
        });
    }

    if (contentInput) {
        contentInput.addEventListener('input', function() {
            const length = this.value.length;
            contentCount.textContent = `${length}/2000`;

            // Update color based on length
            if (length > 1900) {
                contentCount.classList.remove('text-slate-400', 'bg-slate-100');
                contentCount.classList.add('text-red-600', 'bg-red-100');
            } else if (length > 1500) {
                contentCount.classList.remove('text-slate-400', 'bg-slate-100', 'text-red-600', 'bg-red-100');
                contentCount.classList.add('text-yellow-600', 'bg-yellow-100');
            } else {
                contentCount.classList.remove('text-yellow-600', 'bg-yellow-100', 'text-red-600', 'bg-red-100');
                contentCount.classList.add('text-slate-400', 'bg-slate-100');
            }

            updatePreview();
        });

        // Add basic formatting help
        contentInput.addEventListener('keydown', function(e) {
            // Basic markdown shortcuts
            if (e.ctrlKey || e.metaKey) {
                if (e.key === 'b') {
                    e.preventDefault();
                    wrapSelection('**', '**');
                } else if (e.key === 'i') {
                    e.preventDefault();
                    wrapSelection('*', '*');
                }
            }
        });
    }

    function updatePreview() {
        if (!previewDiv) return;

        const title = titleInput?.value || 'No Title';
        const content = contentInput?.value || '';

        let previewHTML = '';

        if (title) {
            previewHTML += `<h4 class="font-bold text-lg text-slate-800 mb-2">${escapeHtml(title)}</h4>`;
        }

        if (content) {
            // Simple markdown parsing
            let formattedContent = escapeHtml(content)
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/`(.*?)`/g, '<code class="bg-gray-100 px-1 rounded">$1</code>')
                .replace(/\n/g, '<br>');

            previewHTML += `<div class="text-slate-600 text-sm leading-relaxed">${formattedContent}</div>`;
        } else {
            previewHTML += '<p class="text-slate-500 italic">Preview will appear here as you type...</p>';
        }

        previewDiv.innerHTML = previewHTML;
    }

    function wrapSelection(startTag, endTag) {
        const textarea = contentInput;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const selectedText = textarea.value.substring(start, end);
        const before = textarea.value.substring(0, start);
        const after = textarea.value.substring(end);

        textarea.value = before + startTag + selectedText + endTag + after;
        textarea.selectionStart = start + startTag.length;
        textarea.selectionEnd = end + startTag.length;
        textarea.focus();

        updatePreview();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Initialize preview
    updatePreview();
}

// Initialize everything
document.addEventListener('DOMContentLoaded', function() {
    // Initialize announcement modal
    initializeAnnouncementModal();

    // Initialize load more buttons
    const loadMoreMembersBtn = document.getElementById('loadMoreMembers');
    const loadMoreClassesBtn = document.getElementById('loadMoreClasses');

    if (loadMoreMembersBtn) {
        loadMoreMembersBtn.addEventListener('click', loadMoreMembers);
        updateLoadMoreButton('members', hasMoreMembers === 'true' || hasMoreMembers === true);
    }

    if (loadMoreClassesBtn) {
        loadMoreClassesBtn.addEventListener('click', loadMoreClasses);
        updateLoadMoreButton('classes', hasMoreClasses === 'true' || hasMoreClasses === true);
    }

    // Initialize event listeners for view member buttons
    document.querySelectorAll('.view-member-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            viewMemberDetails(this.dataset.memberId);
        });
    });

    // Initialize member search and filter
    const memberSearch = document.getElementById('memberSearch');
    const roleFilter = document.getElementById('roleFilter');

    if (memberSearch) {
        memberSearch.addEventListener('input', debounce(function(e) {
            currentMemberPage = 1;
            filterMembers(e.target.value, roleFilter.value);
        }, 300));
    }

    if (roleFilter) {
        roleFilter.addEventListener('change', function(e) {
            currentMemberPage = 1;
            filterMembers(memberSearch ? memberSearch.value : '', e.target.value);
        });
    }

    // Initialize class search
    const classSearch = document.getElementById('classSearch');

    if (classSearch) {
        classSearch.addEventListener('input', debounce(function(e) {
            currentClassPage = 1;
            filterClasses(e.target.value);
        }, 300));
    }

    // Initialize scroll loading
    window.addEventListener('scroll', handleScroll);
});

// Load more members function
async function loadMoreMembers() {
    if (isLoadingMembers || !hasMoreMembers) return;

    isLoadingMembers = true;

    const loadMoreBtn = document.getElementById('loadMoreMembers');
    const loadingSpinner = document.getElementById('membersLoadingSpinner');

    if (loadMoreBtn) {
        loadMoreBtn.disabled = true;
        loadMoreBtn.innerHTML = '<i data-feather="loader" class="w-4 h-4 animate-spin"></i> Loading...';
        feather.replace();
    }

    if (loadingSpinner) {
        loadingSpinner.classList.remove('hidden');
    }

    try {
        const searchTerm = document.getElementById('memberSearch')?.value || '';
        const roleFilter = document.getElementById('roleFilter')?.value || '';

        const response = await fetch(`/organization/${organizationId}/members/load?page=${currentMemberPage + 1}&per_page=${membersPerPage}&search=${encodeURIComponent(searchTerm)}&role=${roleFilter}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            // Append new members
            data.members.forEach(member => {
                const memberCard = createMemberCard(member);
                document.getElementById('membersGrid').appendChild(memberCard);
            });

            currentMemberPage++;
            hasMoreMembers = data.has_more;

            // Update button state
            updateLoadMoreButton('members', hasMoreMembers);

            // Re-attach event listeners to new view buttons
            document.querySelectorAll('.view-member-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    viewMemberDetails(this.dataset.memberId);
                });
            });

            // Refresh feather icons
            feather.replace();
        }
    } catch (error) {
        console.error('Error loading more members:', error);
        showToast('Failed to load more members', 'error');
    } finally {
        isLoadingMembers = false;

        if (loadMoreBtn) {
            loadMoreBtn.disabled = false;
            loadMoreBtn.innerHTML = '<i data-feather="plus" class="w-4 h-4"></i> Load More Members';
            feather.replace();
        }

        if (loadingSpinner) {
            loadingSpinner.classList.add('hidden');
        }
    }
}

// Load more classes function
async function loadMoreClasses() {
    if (isLoadingClasses || !hasMoreClasses) return;

    isLoadingClasses = true;

    const loadMoreBtn = document.getElementById('loadMoreClasses');
    const loadingSpinner = document.getElementById('classesLoadingSpinner');
    const classesGrid = document.getElementById('classesGrid');

    if (loadMoreBtn) {
        loadMoreBtn.disabled = true;
        loadMoreBtn.innerHTML = '<i data-feather="loader" class="w-4 h-4 animate-spin"></i> Loading...';
        feather.replace();
    }

    if (loadingSpinner) {
        loadingSpinner.classList.remove('hidden');
    }

    try {
        const searchTerm = document.getElementById('classSearch')?.value || '';

        const response = await fetch(`/organization/${organizationId}/classes/load?page=${currentClassPage + 1}&per_page=${classesPerPage}&search=${encodeURIComponent(searchTerm)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            if (data.classes.length > 0) {
                // Append new classes
                data.classes.forEach(classItem => {
                    const classCard = createClassCard(classItem);
                    classesGrid.appendChild(classCard);
                });

                currentClassPage++;
                hasMoreClasses = data.has_more;

                // Update button state
                updateLoadMoreButton('classes', hasMoreClasses);

                // Refresh feather icons
                feather.replace();
            } else {
                // No more classes to load
                hasMoreClasses = false;
                updateLoadMoreButton('classes', false);
            }
        } else {
            // API error
            showToast('Failed to load more classes', 'error');
            hasMoreClasses = false;
            updateLoadMoreButton('classes', false);
        }
    } catch (error) {
        console.error('Error loading more classes:', error);
        showToast('Failed to load more classes', 'error');
        hasMoreClasses = false;
        updateLoadMoreButton('classes', false);
    } finally {
        isLoadingClasses = false;

        if (loadMoreBtn) {
            loadMoreBtn.disabled = false;
            loadMoreBtn.innerHTML = '<i data-feather="plus" class="w-4 h-4"></i> Load More Classes';
            feather.replace();
        }

        if (loadingSpinner) {
            loadingSpinner.classList.add('hidden');
        }
    }
}

// Update load more button state
function updateLoadMoreButton(type, hasMore) {
    const loadMoreBtn = document.getElementById(`loadMore${type.charAt(0).toUpperCase() + type.slice(1)}`);
    const endMessage = document.getElementById(`${type}EndMessage`);
    const noItemsMessage = document.getElementById(`no${type.charAt(0).toUpperCase() + type.slice(1)}Message`);

    if (!loadMoreBtn) return;

    if (hasMore) {
        loadMoreBtn.classList.remove('hidden');
        if (endMessage) endMessage.classList.add('hidden');
    } else {
        loadMoreBtn.classList.add('hidden');
        if (endMessage) {
            // Only show end message if there are items
            const itemsCount = type === 'members' ?
                document.querySelectorAll('.member-card').length :
                document.querySelectorAll('.class-card').length;

            if (itemsCount > 0) {
                endMessage.classList.remove('hidden');
            } else {
                endMessage.classList.add('hidden');
            }
        }
    }
}

// Create member card HTML
function createMemberCard(member) {
    const roleClass = `role-${member.role || 'member'}`;
    const statusClass = `status-${member.status || 'active'}`;

    const card = document.createElement('div');
    card.className = 'member-card p-4 rounded-xl cursor-pointer hover:bg-white/20 transition-colors duration-200';
    card.dataset.memberId = member.id;
    card.innerHTML = `
        <div class="flex items-start gap-3">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center flex-shrink-0">
                <i data-feather="user" class="w-6 h-6 text-blue-600"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between mb-1">
                    <h3 class="font-semibold text-slate-800 truncate">${member.name || 'Unknown User'}</h3>
                    <span class="${roleClass} text-xs px-2 py-1 rounded-full font-medium ml-2">${member.role || 'member'}</span>
                </div>
                <p class="text-sm text-slate-600 truncate mb-2">${member.email || ''}</p>
                <div class="flex items-center justify-between">
                    <span class="${statusClass} text-xs px-2 py-1 rounded-full">${member.status || 'active'}</span>
                    <span class="text-xs text-slate-500">Joined ${member.formatted_date || 'Unknown date'}</span>
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

    return card;
}

// Create class card HTML
function createClassCard(classItem) {
    const statusClass = `status-${classItem.formatted_status || 'active'}`;

    const card = document.createElement('div');
    card.className = 'class-card p-5 rounded-xl hover:bg-white/20 transition-colors duration-200';
    card.innerHTML = `
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                <i data-feather="book" class="w-6 h-6 text-blue-600"></i>
            </div>
            <span class="${statusClass} text-xs px-3 py-1 rounded-full font-medium">${classItem.formatted_status || 'active'}</span>
        </div>
        <h3 class="font-bold text-slate-800 mb-2 text-lg hover:text-blue-600 transition-colors duration-200">${classItem.name || 'Unknown Class'}</h3>
        <p class="text-slate-600 text-sm mb-4 leading-relaxed">${classItem.description || 'Class description here'}</p>

        <div class="flex items-center justify-between text-sm mb-4">
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-1">
                    <i data-feather="user" class="w-4 h-4 text-slate-500"></i>
                    <span class="font-medium text-slate-700">${classItem.teacher_name || 'Unknown Teacher'}</span>
                </div>
                <span class="text-slate-400">|</span>
                <div class="flex items-center gap-1">
                    <i data-feather="users" class="w-4 h-4 text-slate-500"></i>
                    <span class="font-medium text-slate-700">${classItem.member_count || 0}</span>
                    <span class="text-slate-500">members</span>
                </div>
            </div>
            <div class="text-slate-500 text-xs">${classItem.code || 'N/A'}</div>
        </div>

        <div class="flex justify-between items-center">
            <div class="flex gap-2">
                ${classItem.is_member ?
                    '<span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-sm">✓ Joined</span>' :
                    (classItem.can_join ?
                        `<form action="/classes/${classItem.id}/join" method="POST" class="inline">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                            <button type="submit" class="glass-button px-3 py-1 rounded-lg text-sm flex items-center gap-1 bg-blue-500/20 text-blue-700 hover:bg-blue-500/30 transition-colors duration-200">
                                <i data-feather="user-plus" class="w-3 h-3"></i>
                                Join
                            </button>
                        </form>` :
                        '<span class="text-sm text-slate-500">Join organization first</span>'
                    )
                }
                <button class="glass-button px-3 py-1 rounded-lg text-sm flex items-center gap-1 bg-blue-500/20 text-blue-700 hover:bg-blue-500/30 transition-colors duration-200"
                    onclick="window.location.href='/classes/${classItem.id}'">
                    <i data-feather="arrow-right" class="w-3 h-3"></i>
                    Enter
                </button>
            </div>
        </div>
    `;

    return card;
}

// Filter members with AJAX
async function filterMembers(searchTerm = '', roleFilter = '') {
    try {
        const response = await fetch(`/organization/${organizationId}/members/load?page=1&per_page=${membersPerPage}&search=${encodeURIComponent(searchTerm)}&role=${roleFilter}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            const membersGrid = document.getElementById('membersGrid');
            const noMembersMessage = document.getElementById('noMembersMessage');

            // Clear current members
            membersGrid.innerHTML = '';

            if (data.members.length === 0) {
                noMembersMessage.classList.remove('hidden');
                updateLoadMoreButton('members', false);
                return;
            }

            noMembersMessage.classList.add('hidden');

            // Add filtered members
            data.members.forEach(member => {
                const memberCard = createMemberCard(member);
                membersGrid.appendChild(memberCard);
            });

            currentMemberPage = 1;
            hasMoreMembers = data.has_more;
            updateLoadMoreButton('members', hasMoreMembers);

            // Re-attach event listeners
            document.querySelectorAll('.view-member-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    viewMemberDetails(this.dataset.memberId);
                });
            });

            // Refresh feather icons
            feather.replace();
        }
    } catch (error) {
        console.error('Error filtering members:', error);
        showToast('Error filtering members', 'error');
    }
}

// Filter classes with AJAX
async function filterClasses(searchTerm = '') {
    try {
        const response = await fetch(`/organization/${organizationId}/classes/load?page=1&per_page=${classesPerPage}&search=${encodeURIComponent(searchTerm)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            const classesGrid = document.getElementById('classesGrid');
            const noClassesMessage = document.getElementById('noClassesMessage');

            // Clear current classes
            classesGrid.innerHTML = '';

            if (data.classes.length === 0) {
                noClassesMessage.classList.remove('hidden');
                updateLoadMoreButton('classes', false);
                return;
            }

            noClassesMessage.classList.add('hidden');

            // Add filtered classes
            data.classes.forEach(classItem => {
                const classCard = createClassCard(classItem);
                classesGrid.appendChild(classCard);
            });

            currentClassPage = 1;
            hasMoreClasses = data.has_more;
            updateLoadMoreButton('classes', hasMoreClasses);

            // Refresh feather icons
            feather.replace();
        }
    } catch (error) {
        console.error('Error filtering classes:', error);
        showToast('Error filtering classes', 'error');
    }
}

// Handle scroll for infinite loading
function handleScroll() {
    const tabs = document.querySelectorAll('.tab-content');
    const activeTab = Array.from(tabs).find(tab => !tab.classList.contains('hidden'));

    if (!activeTab) return;

    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const windowHeight = window.innerHeight;
    const documentHeight = document.documentElement.scrollHeight;

    // Load more when 80% scrolled
    if (scrollTop + windowHeight >= documentHeight * 0.8) {
        if (activeTab.id === 'membersTab' && !isLoadingMembers && hasMoreMembers) {
            loadMoreMembers();
        } else if (activeTab.id === 'classesTab' && !isLoadingClasses && hasMoreClasses) {
            loadMoreClasses();
        }
    }
}

// View member details
function viewMemberDetails(memberId) {
    const member = membersData.find(m => m.id == memberId);
    if (!member) return;

    const modalContent = document.getElementById('memberModalContent');
    const roleClass = `role-${member.role || 'member'}`;
    const statusClass = `status-${member.status || 'active'}`;

    modalContent.innerHTML = `
        <div class="text-center mb-6">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center mx-auto mb-4">
                <i data-feather="user" class="w-10 h-10 text-blue-600"></i>
            </div>
            <h4 class="text-xl font-semibold text-slate-800 mb-1">${member.name || 'Unknown User'}</h4>
            <div class="flex items-center justify-center gap-2 mb-3">
                <span class="${roleClass} text-xs px-3 py-1 rounded-full font-medium">${member.role || 'member'}</span>
                <span class="${statusClass} text-xs px-3 py-1 rounded-full">${member.status || 'active'}</span>
            </div>
            <p class="text-slate-600">${member.email || ''}</p>
        </div>

        <div class="space-y-4">
            <div class="glass-card p-4 rounded-xl">
                <h5 class="font-medium text-slate-700 mb-3">Personal Information</h5>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <div class="text-slate-500 mb-1">Username</div>
                        <div class="font-medium text-slate-800">${member.username || 'N/A'}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 mb-1">IC Number</div>
                        <div class="font-medium text-slate-800">${member.icno || 'N/A'}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 mb-1">Phone</div>
                        <div class="font-medium text-slate-800">${member.telno || 'N/A'}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 mb-1">Joined Date</div>
                        <div class="font-medium text-slate-800">${member.joined_date ? new Date(member.joined_date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    }) : 'Unknown date'}</div>
                    </div>
                </div>
            </div>

            <div class="glass-card p-4 rounded-xl">
                <h5 class="font-medium text-slate-700 mb-3">Organization Role</h5>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-medium text-slate-800">${(member.role || 'member').charAt(0).toUpperCase() + (member.role || 'member').slice(1)}</div>
                        <div class="text-sm text-slate-500">Organization permissions</div>
                    </div>
                    ${member.is_admin ? `
                    <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-medium">Administrator</span>
                    ` : ''}
                </div>
            </div>

            <div class="flex gap-2 pt-4">
                <button class="flex-1 glass-button py-2 rounded-xl text-sm font-medium hover:bg-white/40 transition-colors duration-200" onclick="closeMemberModal()">
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
document.getElementById('createClassForm')?.addEventListener('submit', async function (e) {
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

// Announcement Modal Functions
function openCreateAnnouncementModal(announcementId = null) {
    const form = document.getElementById('createAnnouncementForm');
    const modalTitle = document.getElementById('announcementModalTitle');
    const submitText = document.getElementById('announcementSubmitText');
    const submitIcon = document.getElementById('announcementSubmitIcon');

    // Reset form
    form.reset();
    document.getElementById('createAnnouncementErrors').classList.add('hidden');

    // Reset character counters
    const titleCount = document.getElementById('titleCount');
    const contentCount = document.getElementById('contentCount');
    if (titleCount) titleCount.textContent = '0/100';
    if (contentCount) contentCount.textContent = '0/2000';

    // Reset colors
    if (titleCount) {
        titleCount.className = 'text-xs text-slate-400 bg-slate-100 px-2 py-1 rounded-full';
    }
    if (contentCount) {
        contentCount.className = 'text-xs text-slate-400 bg-slate-100 px-2 py-1 rounded-full';
    }

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
        // Trigger preview update
        if (typeof initializeAnnouncementModal === 'function') {
            initializeAnnouncementModal();
        }
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
    document.getElementById('viewAnnouncementTitle').textContent = announcement.title || 'No Title';
    document.getElementById('viewAnnouncementAuthor').textContent = 'Posted by ' + (announcement.author || 'Unknown');
    document.getElementById('viewAnnouncementDate').textContent = announcement.formatted_date || 'Unknown date';

    // Format and display content with line breaks
    const contentDiv = document.getElementById('viewAnnouncementContent');
    const formattedContent = (announcement.content || '').replace(/\n/g, '<br>');
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

            // Set tahap radio button
            const tahapRadios = document.querySelectorAll('input[name="tahap"]');
            tahapRadios.forEach(radio => {
                radio.checked = (radio.value === announcement.tahap);
            });

            // Trigger character counter updates
            document.getElementById('announcementTitle').dispatchEvent(new Event('input'));
            document.getElementById('announcementContent').dispatchEvent(new Event('input'));

            // Initialize modal with loaded data
            initializeAnnouncementModal();
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

// Close modals when clicking outside
document.getElementById('createClassModal')?.addEventListener('click', function (e) {
    if (e.target === this) {
        closeCreateClassModal();
    }
});

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
        if (document.getElementById('createAnnouncementModal') && !document.getElementById('createAnnouncementModal').classList.contains('hidden')) {
            closeCreateAnnouncementModal();
        }
        if (document.getElementById('createClassModal') && !document.getElementById('createClassModal').classList.contains('hidden')) {
            closeCreateClassModal();
        }
        if (document.getElementById('memberModal') && !document.getElementById('memberModal').classList.contains('hidden')) {
            closeMemberModal();
        }
        if (document.getElementById('viewAnnouncementModal') && !document.getElementById('viewAnnouncementModal').classList.contains('hidden')) {
            closeViewAnnouncementModal();
        }
    }
});
</script>
</body>

</html>
