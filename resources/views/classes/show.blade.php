<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $class->nama ?? 'Class Details' }} - EduConnect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        @font-face {
            font-family: 'ClashDisplay';
            src: url('/fonts/ClashDisplay-Regular.otf') format('opentype');
        }

        /* Glass morphism styles */
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

        .result-card {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }

        .result-card:hover {
            transform: translateY(-4px);
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .bg-gradient-animated {
            background: linear-gradient(-45deg, #e0f2fe, #f0f9ff, #f0fdf4, #fef7cd);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
            filter: blur(60px);
            animation: float 20s infinite linear;
        }

        .shape:nth-child(1) {
            width: 300px;
            height: 300px;
            top: 10%;
            left: 10%;
            animation-duration: 25s;
        }

        .shape:nth-child(2) {
            width: 400px;
            height: 400px;
            top: 60%;
            right: 10%;
            animation-duration: 30s;
            animation-delay: -5s;
        }

        .shape:nth-child(3) {
            width: 250px;
            height: 250px;
            bottom: 20%;
            left: 20%;
            animation-duration: 20s;
            animation-delay: -10s;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            25% {
                transform: translate(100px, 50px) rotate(90deg);
            }
            50% {
                transform: translate(50px, 100px) rotate(180deg);
            }
            75% {
                transform: translate(-50px, 50px) rotate(270deg);
            }
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.3);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.5);
        }

        .backdrop-blur-sm {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        /* Animation styles */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOutDown {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(20px);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.3s ease-out forwards;
        }

        .fade-out-down {
            animation: fadeOutDown 0.3s ease-out forwards;
        }

        /* Toast animation */
        .custom-toast {
            animation: slideInRight 0.3s ease-out forwards;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }

        /* Loading spinner animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* Button loading state */
        .button-loading {
            position: relative;
            color: transparent !important;
        }

        .button-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-top: -8px;
            margin-left: -8px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        /* Heart icon styles */
        .like-button.text-blue-600 i[data-feather="heart"] {
            fill: currentColor;
            stroke: currentColor;
        }

        .like-button:not(.text-blue-600) i[data-feather="heart"] {
            fill: transparent;
            stroke: currentColor;
        }

        /* Pulse animation for likes */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .like-pulse {
            animation: pulse 0.3s ease;
        }

        /* Tab styles */
        .tab-active {
            border-bottom-color: #3b82f6 !important;
            color: #3b82f6 !important;
        }

        /* Modal backdrop */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        /* Line clamp for text truncation */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-animated text-slate-800 font-sans relative overflow-x-hidden">
    <!-- Floating Background Shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50"></div>

    <!-- ====== PAGE LAYOUT ====== -->
    <div class="flex min-h-screen max-w-full">

        <!-- ================= LEFT NAV SIDEBAR ================= -->
        <div class="hidden lg:flex w-20 sticky top-0 h-screen flex-col items-center py-8 gap-6">
            <!-- Logo -->
            <div class="glass-card w-12 h-12 rounded-2xl flex items-center justify-center mb-4">
                <i data-feather="book-open" class="text-blue-600 w-6 h-6"></i>
            </div>

            <!-- Navigation -->
            <a href="/home" class="glass-card p-3 rounded-xl hover:bg-white/30 transition-all duration-200 group">
                <i data-feather="home" class="w-5 h-5 text-slate-600 group-hover:text-blue-600"></i>
            </a>
            <a href="/search" class="glass-card p-3 rounded-xl hover:bg-white/30 transition-all duration-200 group">
                <i data-feather="search" class="w-5 h-5 text-slate-600 group-hover:text-blue-600"></i>
            </a>
            <a href="/chat" class="glass-card p-3 rounded-xl hover:bg-white/30 transition-all duration-200 group">
                <i data-feather="message-circle" class="w-5 h-5 text-slate-600 group-hover:text-blue-600"></i>
            </a>
            <a href="/classes" class="glass-card p-3 rounded-xl hover:bg-white/30 transition-all duration-200 group">
                <i data-feather="book" class="w-5 h-5 text-slate-600 group-hover:text-blue-600"></i>
            </a>

            <div class="mt-auto">
                <a href="/profile" class="glass-card p-3 rounded-xl hover:bg-white/30 transition-all duration-200 group">
                    <i data-feather="user" class="w-5 h-5 text-slate-600 group-hover:text-blue-600"></i>
                </a>
            </div>
        </div>

        <!-- ================= BOTTOM NAV (Mobile Only) ================= -->
        <div class="fixed bottom-4 left-4 right-4 glass-card rounded-2xl lg:hidden z-50">
            <div class="flex justify-around items-center py-3">
                <a href="/" class="flex flex-col items-center text-slate-600 p-2 rounded-lg hover:bg-white/30 transition-colors">
                    <i data-feather="home" class="w-5 h-5"></i>
                    <span class="text-xs mt-1">Home</span>
                </a>
                <a href="/search" class="flex flex-col items-center text-slate-600 p-2 rounded-lg hover:bg-white/30 transition-colors">
                    <i data-feather="search" class="w-5 h-5"></i>
                    <span class="text-xs mt-1">Search</span>
                </a>
                <a href="/chat" class="flex flex-col items-center text-slate-600 p-2 rounded-lg hover:bg-white/30 transition-colors">
                    <i data-feather="message-circle" class="w-5 h-5"></i>
                    <span class="text-xs mt-1">Chat</span>
                </a>
                <a href="/profile" class="flex flex-col items-center text-slate-600 p-2 rounded-lg hover:bg-white/30 transition-colors">
                    <i data-feather="user" class="w-5 h-5"></i>
                    <span class="text-xs mt-1">Profile</span>
                </a>
            </div>
        </div>

        <div class="flex-1 w-full">
            <!-- Main Content -->
            <div class="w-full">
                <div class="flex">
                    <!-- Main Content Area -->
                    <div class="flex-1 max-w-6xl mx-auto px-6 py-6">
                        <!-- Back Navigation & Actions -->
                        <div class="mb-6 pt-6 flex items-center justify-between">
                            <a href="{{ url()->previous() }}" class="glass-button flex items-center gap-2 px-4 py-2 rounded-xl">
                                <i data-feather="arrow-left" class="w-4 h-4"></i>
                                <span>Back to Classes</span>
                            </a>
                        </div>

                        <!-- Class Header -->
                        <div class="glass-card rounded-2xl overflow-hidden mb-8">
                            <!-- Cover Image -->
                            <div class="relative w-full h-64 overflow-hidden">
                                <img src="{{ asset('images/headercar.png') }}"
                                     alt="{{ $class->nama ?? 'Class Cover Image' }}"
                                     class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-6 left-6 right-6">
                                    <div class="flex items-center gap-3 mb-3">
                                        <img src="{{ asset(($class->owner ? $class->owner->profile_image : null) ?? 'images/pfp.jpg') }}"
                                             alt="{{ $class->owner ? $class->owner->name : 'Class Owner' }}"
                                             class="w-12 h-12 rounded-full border-2 border-white shadow-lg">
                                        <div class="backdrop-blur-sm bg-white/20 rounded-full px-4 py-2">
                                            <span class="text-white font-medium text-sm">{{ $class->owner ? $class->owner->name : 'Class Owner' }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h1 class="text-3xl font-bold text-white mb-2">{{ $class->nama ?? 'Class Name' }}</h1>
                                            <div class="flex flex-wrap gap-2">
                                                <span class="backdrop-blur-sm bg-white/30 text-white text-sm px-3 py-1.5 rounded-full">
                                                    {{ $class->organization ? $class->organization->nama : 'Organization' }}
                                                </span>
                                                <span class="backdrop-blur-sm bg-blue-500/30 text-white text-sm px-3 py-1.5 rounded-full">
                                                    {{ $class->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                        </div>
                                        <!-- Join/Leave Button -->
                                        <div>
                                            @if($isMember)
                                                <form action="{{ route('classes.leave', $class->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="glass-button px-6 py-3 rounded-xl bg-red-50 border-red-100 text-red-600 hover:bg-red-100">
                                                        <i data-feather="log-out" class="w-4 h-4 inline mr-2"></i>
                                                        Leave Class
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('classes.join', $class->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="glass-button px-6 py-3 rounded-xl bg-green-50 border-green-100 text-green-600 hover:bg-green-100">
                                                        <i data-feather="log-in" class="w-4 h-4 inline mr-2"></i>
                                                        Join Class
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Details Section -->
                            <div class="p-8">
                                <!-- Stats Row -->
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                                    <div class="glass-button p-4 rounded-xl flex flex-col items-center">
                                        <i data-feather="users" class="w-6 h-6 text-blue-600 mb-2"></i>
                                        <span class="text-2xl font-bold text-slate-800">{{ $class->members_count ?? '0' }}</span>
                                        <span class="text-sm text-slate-600">Members</span>
                                    </div>
                                    <div class="glass-button p-4 rounded-xl flex flex-col items-center">
                                        <i data-feather="bell" class="w-6 h-6 text-yellow-600 mb-2"></i>
                                        <span class="text-2xl font-bold text-slate-800">{{ $announcementsCount ?? '0' }}</span>
                                        <span class="text-sm text-slate-600">Announcements</span>
                                    </div>
                                    <div class="glass-button p-4 rounded-xl flex flex-col items-center">
                                        <i data-feather="file-text" class="w-6 h-6 text-green-600 mb-2"></i>
                                        <span class="text-2xl font-bold text-slate-800">{{ $postsCount ?? '0' }}</span>
                                        <span class="text-sm text-slate-600">Posts</span>
                                    </div>
                                    <div class="glass-button p-4 rounded-xl flex flex-col items-center">
                                        <i data-feather="message-square" class="w-6 h-6 text-purple-600 mb-2"></i>
                                        <span class="text-2xl font-bold text-slate-800">{{ $commentsCount ?? '0' }}</span>
                                        <span class="text-sm text-slate-600">Comments</span>
                                    </div>
                                </div>

                                <!-- Tabs Section -->
                                <div class="mb-6">
                                    <div class="border-b border-white/30 mb-6">
                                        <nav class="flex space-x-8" aria-label="Tabs">
                                            <button id="announcements-tab" class="py-3 px-1 border-b-2 font-medium text-sm tab-active border-blue-500 text-blue-600">
                                                Announcements ({{ $announcementsCount ?? 0 }})
                                            </button>
                                            <button id="posts-tab" class="py-3 px-1 border-b-2 font-medium text-sm border-transparent text-slate-600 hover:text-slate-800 hover:border-slate-300">
                                                Posts ({{ $postsCount ?? 0 }})
                                            </button>
                                        </nav>
                                    </div>

                                    <!-- Announcements Section (Initially visible) -->
                                    <div id="announcements-content" class="space-y-4">
                                        @if($isMember && $class->isAdmin(Auth::id()))
                                            <!-- Create Announcement Form - Only for Admins -->
                                            <div class="glass-card rounded-xl p-6 mb-6">
                                                <form action="{{ route('classes.announcements.create', $class->id) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-4">
                                                        <input type="text"
                                                               name="title"
                                                               class="glass-input w-full rounded-xl p-3 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                                                               placeholder="Announcement title..."
                                                               required>
                                                        <textarea name="content"
                                                                  class="glass-input w-full rounded-xl p-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                                                                  placeholder="Write your announcement here..."
                                                                  rows="4"
                                                                  required></textarea>
                                                    </div>
                                                    <div class="flex justify-end">
                                                        <button type="submit" class="glass-button px-4 py-2 rounded-xl bg-green-600 text-white hover:bg-green-700">
                                                            Publish Announcement
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif

                                        <!-- Announcements List -->
                                        <div class="space-y-4" id="announcements-list">
                                            @forelse($announcements as $announcement)
                                                <div class="glass-card rounded-xl p-6 announcement-card" data-announcement-id="{{ $announcement->id }}">
                                                    <div class="flex items-start gap-4 mb-4">
                                                        <div class="glass-button p-3 rounded-xl flex flex-col items-center justify-center min-w-12">
                                                            <i data-feather="bell" class="w-5 h-5 text-yellow-600"></i>
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="flex items-center justify-between mb-2">
                                                                <h3 class="font-semibold text-slate-900 text-lg line-clamp-2">{{ $announcement->title }}</h3>
                                                                @if($isMember && $class->isAdmin(Auth::id()))
                                                                    <div class="flex items-center gap-1">
                                                                        <button class="glass-button p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 edit-announcement-btn"
                                                                                data-id="{{ $announcement->id }}"
                                                                                data-title="{{ $announcement->title }}"
                                                                                data-content="{{ $announcement->content }}">
                                                                            <i data-feather="edit" class="w-3 h-3"></i>
                                                                        </button>
                                                                        <form action="{{ route('classes.announcements.delete', [$class->id, $announcement->id]) }}"
                                                                              method="POST"
                                                                              class="delete-announcement-form"
                                                                              onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="glass-button p-1.5 rounded-lg text-red-600 hover:bg-red-50">
                                                                                <i data-feather="trash-2" class="w-3 h-3"></i>
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="flex items-center gap-3 mb-3">
                                                                <img src="{{ asset(($announcement->author ? $announcement->author->profile_image : null) ?? 'images/pfp.jpg') }}"
                                                                     alt="{{ $announcement->author ? $announcement->author->name : 'Author' }}"
                                                                     class="w-6 h-6 rounded-full">
                                                                <span class="text-sm font-medium text-slate-800">{{ $announcement->author ? $announcement->author->name : 'Author' }}</span>
                                                                <span class="text-xs text-slate-500">{{ $announcement->created_at->diffForHumans() }}</span>
                                                            </div>
                                                            <p class="text-slate-700 leading-relaxed whitespace-pre-line">
                                                                {{ $announcement->content }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="glass-card rounded-xl p-8 text-center">
                                                    <i data-feather="bell" class="w-12 h-12 text-slate-400 mx-auto mb-4"></i>
                                                    <h3 class="text-lg font-medium text-slate-700 mb-2">No announcements yet</h3>
                                                    <p class="text-slate-600">
                                                        @if($class->isAdmin(Auth::id()))
                                                            Be the first to post an announcement!
                                                        @else
                                                            No announcements have been posted yet.
                                                        @endif
                                                    </p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>

                                    <!-- Posts Section (Initially hidden) -->
                                    <div id="posts-content" class="space-y-6 hidden">
                                        @if($isMember)
                                            <!-- Create Post Form - Only for Admins -->
                                            @if($class->isAdmin(Auth::id()))
                                            <div class="glass-card rounded-xl p-6 mb-6">
                                                <form action="{{ route('classes.posts.create', $class->id) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-4">
                                                        <textarea name="content"
                                                                  class="glass-input w-full rounded-xl p-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                                                                  placeholder="Share something with the class..."
                                                                  rows="4" required></textarea>
                                                    </div>
                                                    <div class="flex justify-between items-center">
                                                        <div class="flex items-center gap-3">
                                                            <button type="button" class="glass-button p-2 rounded-lg">
                                                                <i data-feather="image" class="w-4 h-4"></i>
                                                            </button>
                                                            <button type="button" class="glass-button p-2 rounded-lg">
                                                                <i data-feather="link" class="w-4 h-4"></i>
                                                            </button>
                                                            <button type="button" class="glass-button p-2 rounded-lg">
                                                                <i data-feather="paperclip" class="w-4 h-4"></i>
                                                            </button>
                                                        </div>
                                                        <button type="submit" class="glass-button px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                                                            Post
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                            @endif

                                            <!-- Posts List Container -->
                                            <div id="postsContainer" class="space-y-6">
                                                @forelse($posts as $post)
                                                <div class="glass-card rounded-2xl p-6" id="post-{{ $post->id }}">
                                                    <div class="flex items-start gap-4 mb-4">
                                                        <img src="{{ asset(($post->author ? $post->author->profile_image : null) ?? 'images/pfp.jpg') }}"
                                                             alt="{{ $post->author ? $post->author->name : 'Author' }}"
                                                             class="w-12 h-12 rounded-full border-2 border-white/50">
                                                        <div class="flex-1">
                                                            <div class="flex items-center gap-2 mb-1">
                                                                <h3 class="font-semibold text-slate-900">{{ $post->author ? $post->author->name : 'Author' }}</h3>
                                                                @if($post->author && $post->author->id === $class->organ_user_id)
                                                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Teacher</span>
                                                                @endif
                                                            </div>
                                                            <p class="text-sm text-slate-500">{{ $post->created_at->diffForHumans() }}</p>
                                                        </div>
                                                        @if($isMember && ($post->user_id === Auth::id() || $class->isAdmin(Auth::id())))
                                                        <form action="{{ route('classes.posts.delete', [$class->id, $post->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="glass-button p-2 rounded-lg text-red-600 hover:bg-red-50">
                                                                <i data-feather="trash-2" class="w-4 h-4"></i>
                                                            </button>
                                                        </form>
                                                        @endif
                                                    </div>

                                                    <p class="text-slate-700 mb-4 leading-relaxed">
                                                        {{ $post->content }}
                                                    </p>

                                                    @if($post->media_url)
                                                    <div class="glass-card rounded-xl p-4 mb-4">
                                                        <div class="flex items-center gap-3 text-slate-700">
                                                            <i data-feather="link" class="w-5 h-5 text-blue-500"></i>
                                                            <a href="{{ $post->media_url }}" target="_blank" class="text-sm hover:text-blue-600 transition-colors">
                                                                View Attachment
                                                            </a>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    <div class="flex items-center gap-6 text-slate-600 text-sm">
                                                        <!-- Like Button -->
                                                        <button type="button"
                                                                class="glass-button flex items-center gap-2 px-4 py-2 rounded-xl hover:text-blue-600 transition-all like-button {{ $post->is_liked ?? false ? 'text-blue-600' : '' }}"
                                                                data-post-id="{{ $post->id }}"
                                                                data-liked="{{ $post->is_liked ? 'true' : 'false' }}"
                                                                data-likes-count="{{ $post->likes_count ?? 0 }}"
                                                                data-like-url="{{ route('classes.posts.like', [$class->id, $post->id]) }}">
                                                            <i data-feather="heart" class="w-5 h-5"></i>
                                                            <span class="like-count">{{ $post->likes_count ?? 0 }}</span>
                                                        </button>

                                                        <button class="glass-button flex items-center gap-2 px-4 py-2 rounded-xl hover:text-blue-600 transition-all comment-toggle" data-post-id="{{ $post->id }}">
                                                            <i data-feather="message-circle" class="w-5 h-5"></i>
                                                            <span class="comment-count">{{ $post->comments_count ?? 0 }}</span>
                                                        </button>

                                                        <button class="glass-button flex items-center gap-2 px-4 py-2 rounded-xl hover:text-blue-600 transition-all">
                                                            <i data-feather="share-2" class="w-5 h-5"></i>
                                                            <span>Share</span>
                                                        </button>
                                                    </div>

                                                    <!-- Comments Section -->
                                                    <div class="mt-4 hidden" id="comments-{{ $post->id }}">
                                                        <!-- Add Comment Form - Available for all members -->
                                                        <div class="glass-card rounded-xl p-4 mb-4">
                                                            <form action="{{ route('classes.posts.comments.create', [$class->id, $post->id]) }}"
                                                                  method="POST"
                                                                  class="comment-form"
                                                                  data-post-id="{{ $post->id }}">
                                                                @csrf
                                                                <div class="flex gap-3">
                                                                    <img src="{{ asset((Auth::user()->profile_image ?? null) ?: 'images/pfp.jpg') }}"
                                                                         alt="{{ Auth::user()->name }}"
                                                                         class="w-8 h-8 rounded-full">
                                                                    <div class="flex-1">
                                                                        <textarea name="content"
                                                                                  class="glass-input w-full rounded-xl p-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-500/30 comment-textarea"
                                                                                  placeholder="Add a comment..."
                                                                                  rows="2" required></textarea>
                                                                        <div class="flex justify-end mt-3">
                                                                            <button type="submit" class="glass-button px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 comment-submit-btn">
                                                                                Comment
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>

                                                        <!-- Comments List -->
                                                        <div class="space-y-4 comments-list" id="comments-list-{{ $post->id }}">
                                                            @foreach($post->comments as $comment)
                                                            <div class="glass-card rounded-xl p-4 fade-in-up" id="comment-{{ $comment->id }}">
                                                                <div class="flex gap-3 mb-3">
                                                                    <img src="{{ asset(($comment->author ? $comment->author->profile_image : null) ?? 'images/pfp.jpg') }}"
                                                                         alt="{{ $comment->author ? $comment->author->name : 'Commenter' }}"
                                                                         class="w-10 h-10 rounded-full">
                                                                    <div class="flex-1">
                                                                        <div class="flex items-center justify-between">
                                                                            <div>
                                                                                <h4 class="font-medium text-slate-900">{{ $comment->author ? $comment->author->name : 'Commenter' }}</h4>
                                                                                <p class="text-xs text-slate-500">{{ $comment->created_at->diffForHumans() }}</p>
                                                                            </div>
                                                                            @if($comment->user_id === Auth::id() || $post->user_id === Auth::id() || $class->isAdmin(Auth::id()))
                                                                            <form action="{{ route('classes.posts.comments.delete', [$class->id, $post->id, $comment->id]) }}"
                                                                                  method="POST"
                                                                                  class="delete-comment-form"
                                                                                  data-comment-id="{{ $comment->id }}">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="glass-button p-1 rounded-lg text-red-600 hover:bg-red-50 delete-comment-btn">
                                                                                    <i data-feather="trash-2" class="w-3 h-3"></i>
                                                                                </button>
                                                                            </form>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <p class="text-slate-700 text-sm pl-13">
                                                                    {{ $comment->content }}
                                                                </p>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                @empty
                                                <div class="glass-card rounded-2xl p-8 text-center">
                                                    <i data-feather="message-square" class="w-12 h-12 text-slate-400 mx-auto mb-4"></i>
                                                    <h3 class="text-lg font-medium text-slate-700 mb-2">No posts yet</h3>
                                                    <p class="text-slate-600">
                                                        @if($class->isAdmin(Auth::id()))
                                                            Be the first to share something with the class!
                                                        @else
                                                            No posts have been created yet. Only teachers and admins can create posts.
                                                        @endif
                                                    </p>
                                                </div>
                                                @endforelse

                                                <!-- Pagination -->
                                                @if($posts->hasPages())
                                                <div class="glass-card rounded-2xl p-4">
                                                    {{ $posts->links() }}
                                                </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="glass-card rounded-2xl p-8 text-center">
                                                <i data-feather="lock" class="w-12 h-12 text-slate-400 mx-auto mb-4"></i>
                                                <h3 class="text-lg font-medium text-slate-700 mb-2">Join to view posts</h3>
                                                <p class="text-slate-600 mb-6">You need to join this class to see and participate in discussions.</p>
                                                <form action="{{ route('classes.join', $class->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="glass-button px-6 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                                                        Join Class
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Sidebar Area -->
                    <div class="w-80 flex-shrink-0 sticky top-0 h-screen overflow-y-auto p-6">
                        <div class="text-xl font-semibold mb-6 text-slate-800">Class Details</div>

                        <!-- Class Information -->
                        <div class="glass-card rounded-2xl p-5 mb-6">
                            <h3 class="font-semibold text-slate-900 mb-4">Class Information</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Organization:</span>
                                    <span class="font-medium text-slate-700">{{ $class->organization ? $class->organization->nama : 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Status:</span>
                                    <span class="font-medium {{ $class->status ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $class->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Created:</span>
                                    <span class="font-medium text-slate-700">{{ $class->created_at ? $class->created_at->format('M d, Y') : 'Unknown' }}</span>
                                </div>
                                @if($isMember && $class->isAdmin(Auth::id()))
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Your Role:</span>
                                    <span class="font-medium text-green-600">
                                        @if($class->isClassTeacher(Auth::id()))
                                            Teacher/Admin
                                        @else
                                            Organization Admin
                                        @endif
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Members Preview -->
                        <div class="glass-card rounded-2xl p-5 mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-slate-900">Members</h3>
                                <span class="text-sm text-slate-600">{{ $class->members_count ?? 0 }} members</span>
                            </div>
                            <div class="space-y-3">
                                @foreach($class->members->take(5) as $member)
                                <div class="flex items-center gap-3 p-2 hover:bg-white/30 rounded-xl cursor-pointer transition-all">
                                    <img src="{{ asset(($member->profile_image ?? null) ?: 'images/pfp.jpg') }}"
                                         alt="{{ $member->name }}"
                                         class="w-8 h-8 rounded-full border border-white/50">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-slate-800">{{ $member->name }}</p>
                                        <p class="text-xs text-slate-600">member</p>
                                    </div>
                                </div>
                                @endforeach
                                @if($class->members_count > 5)
                                <a href="{{ route('classes.members', $class->id) }}" class="glass-button w-full text-center py-2 rounded-xl text-sm">
                                    View All Members
                                </a>
                                @endif
                            </div>
                        </div>

                        <!-- Quick Actions - Only for Admins -->
                        @if($isMember && $class->isAdmin(Auth::id()))
                        <div class="glass-card rounded-2xl p-5">
                            <h3 class="font-semibold text-slate-900 mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <a href="{{ route('classes.members', $class->id) }}" class="glass-button w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl hover:bg-blue-50">
                                    <i data-feather="users" class="w-4 h-4"></i>
                                    <span>Manage Members</span>
                                </a>
                                <button class="glass-button w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl hover:bg-green-50 text-green-600">
                                    <i data-feather="edit" class="w-4 h-4"></i>
                                    <span>Edit Class</span>
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Feather Icons
        feather.replace();

        // Function to show toast notifications
        function showToast(message, type = 'success') {
            // Remove existing toasts
            const existingToasts = document.querySelectorAll('.custom-toast');
            existingToasts.forEach(toast => {
                toast.classList.add('fade-out-down');
                setTimeout(() => toast.remove(), 300);
            });

            // Create toast element
            const toast = document.createElement('div');
            toast.className = `custom-toast fixed top-4 right-4 z-50 px-6 py-4 rounded-xl font-medium shadow-lg backdrop-blur-sm fade-in-up ${
                type === 'success'
                    ? 'bg-green-500/20 text-green-800 border border-green-300'
                    : 'bg-red-500/20 text-red-800 border border-red-300'
            }`;
            toast.innerHTML = `
                <div class="flex items-center gap-3">
                    <i data-feather="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-5 h-5"></i>
                    <span>${message}</span>
                </div>
            `;

            // Add to page
            document.getElementById('toastContainer').appendChild(toast);

            // Initialize feather icons
            feather.replace();

            // Remove after 3 seconds
            setTimeout(() => {
                toast.classList.add('fade-out-down');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }

        // Tab switching functionality
        const announcementsTab = document.getElementById('announcements-tab');
        const postsTab = document.getElementById('posts-tab');
        const announcementsContent = document.getElementById('announcements-content');
        const postsContent = document.getElementById('posts-content');

        // Initialize tab switching
        function switchTab(tab) {
            if (tab === 'announcements') {
                // Update tabs
                announcementsTab.classList.add('tab-active', 'border-blue-500', 'text-blue-600');
                announcementsTab.classList.remove('border-transparent', 'text-slate-600');
                postsTab.classList.remove('tab-active', 'border-blue-500', 'text-blue-600');
                postsTab.classList.add('border-transparent', 'text-slate-600');

                // Update content visibility
                announcementsContent.classList.remove('hidden');
                postsContent.classList.add('hidden');
            } else {
                // Update tabs
                postsTab.classList.add('tab-active', 'border-blue-500', 'text-blue-600');
                postsTab.classList.remove('border-transparent', 'text-slate-600');
                announcementsTab.classList.remove('tab-active', 'border-blue-500', 'text-blue-600');
                announcementsTab.classList.add('border-transparent', 'text-slate-600');

                // Update content visibility
                postsContent.classList.remove('hidden');
                announcementsContent.classList.add('hidden');

                // Initialize posts functionality when tab is shown
                initializePostsFunctionality();
            }
        }

        // Event listeners for tabs
        announcementsTab.addEventListener('click', () => switchTab('announcements'));
        postsTab.addEventListener('click', () => switchTab('posts'));

        // Initialize posts functionality
        function initializePostsFunctionality() {
            // Toggle comments visibility
            document.querySelectorAll('.comment-toggle').forEach(button => {
                button.addEventListener('click', function() {
                    const postId = this.getAttribute('data-post-id');
                    const commentsDiv = document.getElementById(`comments-${postId}`);
                    commentsDiv.classList.toggle('hidden');

                    // Focus on comment textarea when opening
                    if (!commentsDiv.classList.contains('hidden')) {
                        const textarea = commentsDiv.querySelector('.comment-textarea');
                        if (textarea) {
                            setTimeout(() => textarea.focus(), 100);
                        }
                    }
                });
            });

            // Re-attach delete comment listeners
            attachDeleteCommentListeners();
        }

        // Like button functionality - Event delegation
        document.addEventListener('click', async function(e) {
            // Check if the clicked element or its parent is a like button
            const likeButton = e.target.closest('.like-button');
            if (!likeButton) return;

            e.preventDefault();

            const postId = likeButton.getAttribute('data-post-id');
            const isLiked = likeButton.getAttribute('data-liked') === 'true';
            const currentLikes = parseInt(likeButton.getAttribute('data-likes-count'));
            const likeUrl = likeButton.getAttribute('data-like-url');
            const likeCountSpan = likeButton.querySelector('.like-count');

            // Store the original heart icon state BEFORE modifying HTML
            const wasLiked = likeButton.classList.contains('text-blue-600');

            // Show loading state - but keep the structure
            const originalHTML = likeButton.innerHTML;
            likeButton.innerHTML = `<i data-feather="loader" class="w-5 h-5 animate-spin"></i><span class="like-count">${likeCountSpan.textContent}</span>`;
            likeButton.disabled = true;
            feather.replace();

            try {
                const response = await fetch(likeUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                });

                const data = await response.json();

                if (data.success) {
                    // Update the like count immediately
                    const newLikeCount = data.likes_count;
                    likeCountSpan.textContent = newLikeCount;

                    // Update data attributes
                    likeButton.setAttribute('data-likes-count', newLikeCount);
                    likeButton.setAttribute('data-liked', data.liked ? 'true' : 'false');

                    // Update UI based on like state
                    if (data.liked) {
                        likeButton.classList.add('text-blue-600');
                        likeCountSpan.classList.add('like-pulse');
                        showToast('Liked!', 'success');
                    } else {
                        likeButton.classList.remove('text-blue-600');
                        showToast('Unliked', 'info');
                    }

                    // Remove pulse animation after it completes
                    setTimeout(() => {
                        likeCountSpan.classList.remove('like-pulse');
                    }, 300);
                } else {
                    showToast(data.message || 'Failed to like post', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred. Please try again.', 'error');
            } finally {
                // Reset button state - restore with proper icon
                likeButton.disabled = false;
                const isNowLiked = likeButton.classList.contains('text-blue-600');
                likeButton.innerHTML = `<i data-feather="heart" class="w-5 h-5"></i><span class="like-count">${likeCountSpan.textContent}</span>`;
                feather.replace();
            }
        });

        // Handle comment form submission with AJAX
        document.querySelectorAll('.comment-form').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const form = this;
                const postId = this.getAttribute('data-post-id');
                const submitButton = form.querySelector('.comment-submit-btn');
                const textarea = form.querySelector('.comment-textarea');
                const content = textarea.value.trim();

                if (!content) {
                    showToast('Please enter a comment', 'error');
                    return;
                }

                // Show loading state
                const originalButtonText = submitButton.textContent;
                submitButton.disabled = true;
                submitButton.innerHTML = '<i data-feather="loader" class="w-4 h-4 animate-spin"></i>';
                feather.replace();

                const formData = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();
                    console.log('API Response:', data); // Debug log

                    if (data.success && data.comment) {
                        // Add comment to list
                        const commentsList = document.getElementById(`comments-list-${postId}`);
                        const commentHTML = `
    <div class="glass-card rounded-xl p-4 fade-in-up" id="comment-${data.comment.id}">
        <div class="flex gap-3 mb-3">
            <img src="{{ asset('') }}${data.comment.author.profile_image || 'images/pfp.jpg'}"
                 alt="${data.comment.author.name}"
                 class="w-10 h-10 rounded-full">
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-slate-900">${data.comment.author.name}</h4>
                        <p class="text-xs text-slate-500">${data.comment.created_at}</p>
                    </div>
                    ${data.comment.author.id === {{ Auth::id() }} || {{ $class->isAdmin(Auth::id()) ? 'true' : 'false' }} ?
                    `<form action="/classes/{{ $class->id }}/posts/${postId}/comments/${data.comment.id}"
                          method="POST"
                          class="delete-comment-form"
                          data-comment-id="${data.comment.id}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="glass-button p-1 rounded-lg text-red-600 hover:bg-red-50 delete-comment-btn">
                            <i data-feather="trash-2" class="w-3 h-3"></i>
                        </button>
                    </form>` : ''}
                </div>
            </div>
        </div>
        <p class="text-slate-700 text-sm pl-13">
            ${data.comment.content}
        </p>
    </div>
`;

                        if (commentsList) {
                            // Add to top of comments list
                            commentsList.insertAdjacentHTML('afterbegin', commentHTML);
                        } else {
                            // Create comments list if it doesn't exist
                            const commentsDiv = document.getElementById(`comments-${postId}`);
                            const newCommentsList = document.createElement('div');
                            newCommentsList.className = 'space-y-4 comments-list';
                            newCommentsList.id = `comments-list-${postId}`;
                            newCommentsList.innerHTML = commentHTML;

                            // Find where to insert - after the comment form
                            const commentForm = commentsDiv.querySelector('.comment-form').closest('.glass-card');
                            commentForm.insertAdjacentElement('afterend', newCommentsList);
                        }

                        // Update comment count
                        const commentToggle = document.querySelector(`.comment-toggle[data-post-id="${postId}"]`);
                        if (commentToggle) {
                            const commentCount = commentToggle.querySelector('.comment-count');
                            if (commentCount) {
                                commentCount.textContent = parseInt(commentCount.textContent || '0') + 1;
                            }
                        }

                        // Clear form
                        textarea.value = '';

                        // Show success message
                        showToast(data.message || 'Comment added successfully!', 'success');

                        // Reinitialize feather icons
                        feather.replace();

                        // Re-attach delete listeners for the new comment
                        attachDeleteCommentListeners();

                    } else {
                        // Show error message
                        showToast(data.message || 'Failed to add comment', 'error');
                    }

                } catch (error) {
                    console.error('Error:', error);
                    showToast('An error occurred. Please try again.', 'error');
                } finally {
                    // Reset button state
                    submitButton.disabled = false;
                    submitButton.textContent = originalButtonText;
                    feather.replace();
                }
            });
        });

        // Function to attach delete comment listeners
        function attachDeleteCommentListeners() {
            document.querySelectorAll('.delete-comment-form').forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    if (!confirm('Are you sure you want to delete this comment?')) {
                        return;
                    }

                    const form = this;
                    const commentId = this.getAttribute('data-comment-id');
                    const deleteButton = this.querySelector('.delete-comment-btn');

                    // Get post ID from the form action
                    const postId = this.action.split('/')[6]; // Extract post ID from URL

                    // Show loading state
                    const originalHTML = deleteButton.innerHTML;
                    deleteButton.innerHTML = '<i data-feather="loader" class="w-3 h-3 animate-spin"></i>';
                    deleteButton.disabled = true;
                    feather.replace();

                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-HTTP-Method-Override': 'DELETE'
                            }
                        });

                        if (response.ok) {
                            // Remove comment from DOM with animation
                            const commentElement = document.getElementById(`comment-${commentId}`);
                            if (commentElement) {
                                commentElement.classList.add('fade-out-down');

                                setTimeout(() => {
                                    commentElement.remove();

                                    // Update comment count
                                    const commentToggle = document.querySelector(`.comment-toggle[data-post-id="${postId}"]`);
                                    if (commentToggle) {
                                        const commentCount = commentToggle.querySelector('.comment-count');
                                        commentCount.textContent = Math.max(0, parseInt(commentCount.textContent) - 1);
                                    }

                                    showToast('Comment deleted successfully!', 'success');
                                }, 300);
                            }
                        } else {
                            showToast('Failed to delete comment', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showToast('An error occurred. Please try again.', 'error');
                    } finally {
                        // Reset button state
                        deleteButton.innerHTML = originalHTML;
                        deleteButton.disabled = false;
                        feather.replace();
                    }
                });
            });
        }

        // Edit announcement functionality
        document.addEventListener('click', function(e) {
            const editBtn = e.target.closest('.edit-announcement-btn');
            if (editBtn) {
                e.preventDefault();

                const announcementId = editBtn.getAttribute('data-id');
                const title = editBtn.getAttribute('data-title');
                const content = editBtn.getAttribute('data-content');

                // Create edit modal
                showEditAnnouncementModal(announcementId, title, content);
            }
        });

        function showEditAnnouncementModal(id, title, content) {
            // Create modal for editing
            const modalHTML = `
                <div class="fixed inset-0 modal-backdrop z-50 flex items-center justify-center p-4">
                    <div class="glass-card rounded-2xl p-6 max-w-lg w-full">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-slate-800">Edit Announcement</h3>
                            <button onclick="closeModal()" class="glass-button p-2 rounded-lg">
                                <i data-feather="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <form id="edit-announcement-form">
                            <div class="mb-4">
                                <input type="text"
                                       name="title"
                                       value="${title.replace(/"/g, '&quot;')}"
                                       class="glass-input w-full rounded-xl p-3 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                                       placeholder="Announcement title..."
                                       required>
                                <textarea name="content"
                                          class="glass-input w-full rounded-xl p-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                                          placeholder="Write your announcement here..."
                                          rows="4"
                                          required>${content}</textarea>
                            </div>
                            <div class="flex justify-end gap-3">
                                <button type="button" onclick="closeModal()" class="glass-button px-4 py-2 rounded-xl text-slate-600">
                                    Cancel
                                </button>
                                <button type="submit" class="glass-button px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                                    Update Announcement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;

            const modal = document.createElement('div');
            modal.innerHTML = modalHTML;
            document.body.appendChild(modal);
            feather.replace();

            // Handle form submission
            document.getElementById('edit-announcement-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                await updateAnnouncement(id, this);
            });
        }

        function closeModal() {
            const modal = document.querySelector('.fixed.inset-0.modal-backdrop');
            if (modal) modal.remove();
        }

        async function updateAnnouncement(id, form) {
            const formData = new FormData(form);

            try {
                const response = await fetch(`/classes/{{ $class->id }}/announcements/${id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        title: formData.get('title'),
                        content: formData.get('content')
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showToast('Announcement updated successfully!', 'success');
                    closeModal();
                    // Reload page to see changes
                    location.reload();
                } else {
                    showToast(data.message || 'Failed to update announcement', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred. Please try again.', 'error');
            }
        }

        // Handle Enter key for comment submission
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.ctrlKey) {
                const focusedTextarea = document.activeElement;
                if (focusedTextarea && focusedTextarea.classList.contains('comment-textarea')) {
                    e.preventDefault();
                    const form = focusedTextarea.closest('.comment-form');
                    if (form) {
                        form.dispatchEvent(new Event('submit'));
                    }
                }
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize posts functionality
            initializePostsFunctionality();
        });
    </script>
</body>
</html>
