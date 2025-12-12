<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduConnect</title>
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

        .glass-button.active {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.4);
            box-shadow:
                0 4px 20px rgba(59, 130, 246, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
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
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
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

            0%,
            100% {
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

        /* Custom scrollbar */
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

    <!-- ====== PAGE LAYOUT: LEFT SIDEBAR | FEED | RIGHT SIDEBAR ====== -->
    <div class="flex min-h-screen max-w-full">

        <!-- ================= LEFT NAV SIDEBAR (Updated to match search page) ================= -->
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
            <a href="/courses" class="glass-card p-3 rounded-xl hover:bg-white/30 transition-all duration-200 group">
                <i data-feather="book" class="w-5 h-5 text-slate-600 group-hover:text-blue-600"></i>
            </a>

            <div class="mt-auto">
                <a href="/profile"
                    class="glass-card p-3 rounded-xl hover:bg-white/30 transition-all duration-200 group">
                    <i data-feather="user" class="w-5 h-5 text-slate-600 group-hover:text-blue-600"></i>
                </a>
            </div>
        </div>

        <!-- ================= BOTTOM NAV (Mobile Only) ================= -->
        <div class="fixed bottom-4 left-4 right-4 glass-card rounded-2xl lg:hidden z-50">
            <div class="flex justify-around items-center py-3">
                <a href="/"
                    class="flex flex-col items-center text-slate-600 p-2 rounded-lg hover:bg-white/30 transition-colors">
                    <i data-feather="home" class="w-5 h-5"></i>
                    <span class="text-xs mt-1">Home</span>
                </a>
                <a href="/search"
                    class="flex flex-col items-center text-slate-600 p-2 rounded-lg hover:bg-white/30 transition-colors">
                    <i data-feather="search" class="w-5 h-5"></i>
                    <span class="text-xs mt-1">Search</span>
                </a>
                <a href="/chat"
                    class="flex flex-col items-center text-slate-600 p-2 rounded-lg hover:bg-white/30 transition-colors">
                    <i data-feather="message-circle" class="w-5 h-5"></i>
                    <span class="text-xs mt-1">Chat</span>
                </a>
                <a href="/profile"
                    class="flex flex-col items-center text-slate-600 p-2 rounded-lg hover:bg-white/30 transition-colors">
                    <i data-feather="user" class="w-5 h-5"></i>
                    <span class="text-xs mt-1">Profile</span>
                </a>
            </div>
        </div>

        <div class="flex-1 w-full">
            <!-- Main Content -->
            <div class="w-full">
                <div class="flex">
                    <!-- Main Content Area (Announcements, Posts, etc.) -->
                    <div class="flex-1 max-w-6xl mx-auto px-6 py-6">
                        <!-- Welcome Header -->
                        <div class="mb-8 pt-6">
                            <h1 class="text-3xl font-bold text-slate-800">Welcome back,
                                {{ Auth::user()->name ?? 'Alex' }}!</h1>
                            <p class="text-slate-600 mt-2">Here's what's happening in your classes today.</p>
                        </div>

                        <!-- Announcements Section -->
                        <div class="mb-10">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-semibold text-slate-800">Announcements</h2>
                            </div>

                            <!-- Updated container with proper horizontal scrolling -->
<div class="relative">
    <div class="flex gap-6 overflow-x-auto pb-4 custom-scrollbar" style="scrollbar-width: thin;">
        @forelse($announcements as $announcement)
            <div class="flex-shrink-0 w-80 glass-card rounded-2xl overflow-hidden flex flex-col">
                <div class="relative w-full h-40 overflow-hidden">
                    <img src="{{ asset('images/headercar.png') }}" alt="Announcement Banner"
                        class="w-full h-full object-cover" />
                    <div class="absolute top-4 left-4 flex items-center gap-2">
                        <img src="{{ asset($announcement['author_image']) }}" alt="Logo"
                            class="w-10 h-10 rounded-full border-2 border-white shadow-lg" />
                        <span
                            class="text-white font-medium text-sm backdrop-blur-sm bg-black/30 px-2 py-1 rounded-full">
                            {{ $announcement['author_name'] }}
                        </span>
                    </div>
                    <div
                        class="absolute bottom-4 left-4 backdrop-blur-sm bg-white/30 text-white text-sm px-3 py-1.5 rounded-full">
                        {{ $announcement['organization_name'] }}
                    </div>
                    @if($announcement['is_pinned'])
                        <div class="absolute top-4 right-4">
                            <div class="bg-yellow-500/90 text-white text-xs px-2 py-1 rounded-full flex items-center gap-1">
                                <i data-feather="pin" class="w-3 h-3"></i>
                                <span>Pinned</span>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="font-bold text-slate-900 text-lg mb-3 line-clamp-2">
                        {{ $announcement['title'] }}
                    </h3>
                    <p class="text-slate-700 text-sm mb-4 flex-1 line-clamp-3">
                        {{ Str::limit($announcement['content'], 150) }}
                    </p>
                    <div class="flex justify-between items-center text-xs text-slate-600">
                        <span>{{ $announcement['formatted_time'] }}</span>
                        <div class="flex items-center gap-1">
                            <ion-icon name="eye-outline" class="w-4 h-4"></ion-icon>
                            <span>{{ $announcement['views'] }}</span>
                        </div>
                    </div>
                </div>
                <a href="/organization/{{ $announcement['organization_id'] }}"
                   class="absolute inset-0 z-10"
                   aria-label="View announcement">
                </a>
            </div>
        @empty
            <!-- Default announcements when no real announcements exist -->
            <div class="flex-shrink-0 w-80 glass-card rounded-2xl overflow-hidden flex flex-col">
                <div class="relative w-full h-40 overflow-hidden">
                    <img src="{{ asset('images/headercar.png') }}" alt="Announcement Banner"
                        class="w-full h-full object-cover" />
                    <div class="absolute top-4 left-4 flex items-center gap-2">
                        <img src="{{ asset('images/pfp.jpg') }}" alt="Logo"
                            class="w-10 h-10 rounded-full border-2 border-white shadow-lg" />
                        <span
                            class="text-white font-medium text-sm backdrop-blur-sm bg-black/30 px-2 py-1 rounded-full">Prof.
                            Johnson</span>
                    </div>
                    <div
                        class="absolute bottom-4 left-4 backdrop-blur-sm bg-white/30 text-white text-sm px-3 py-1.5 rounded-full">
                        Computer Science 101
                    </div>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="font-bold text-slate-900 text-lg mb-3">No New Announcements</h3>
                    <p class="text-slate-700 text-sm mb-4 flex-1">
                        Check back later for updates from your organizations and classes.
                    </p>
                    <div class="flex justify-between items-center text-xs text-slate-600">
                        <span>Just now</span>
                        <div class="flex items-center gap-1">
                            <ion-icon name="eye-outline" class="w-4 h-4"></ion-icon>
                            <span>0</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

                            <!-- Class Posts Section -->
                            <div class="mb-6 mt-10">
                                <div class="flex items-center justify-between mb-6">
                                    <h2 class="text-xl font-semibold text-slate-800">Recent Class Posts</h2>
                                    <a href="/classes"
                                        class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                                        <span>View All Classes</span>
                                        <ion-icon name="arrow-forward-outline" class="w-4 h-4"></ion-icon>
                                    </a>
                                </div>

                                <div class="space-y-6" id="class-posts-container">
                                    @foreach($classPosts as $post)
                                                                    <div class="glass-card rounded-2xl p-6" data-post-id="{{ $post->id }}"
                                                                        data-class-id="{{ $post->class_id }}">
                                                                        <!-- Post Header with Class Info -->
                                                                        <div class="flex items-start gap-4 mb-4">
                                                                            <div
                                                                                class="glass-button p-3 rounded-xl flex flex-col items-center justify-center min-w-14">
                                                                                <span class="font-bold text-sm text-blue-600">CLS</span>
                                                                                <span
                                                                                    class="text-xs text-slate-600">{{ str_pad($post->class->id, 3, '0', STR_PAD_LEFT) }}</span>
                                                                            </div>
                                                                            <div class="flex-1">
                                                                                <div class="flex items-center gap-2 mb-1">
                                                                                    <h3 class="font-semibold text-slate-900">{{ $post->class->nama }}
                                                                                    </h3>
                                                                                    <span class="text-xs text-slate-500">•</span>
                                                                                    <span
                                                                                        class="text-sm text-slate-600">{{ $post->class->organization->nama ?? 'Unknown Organization' }}</span>
                                                                                </div>
                                                                                <div class="flex items-center gap-3">
                                                                                    <div class="flex items-center gap-2">
                                                                                        <img src="{{ asset($post->author->profile_image ?? 'images/pfp.jpg') }}"
                                                                                            alt="User"
                                                                                            class="w-6 h-6 rounded-full border border-white/50">
                                                                                        <span
                                                                                            class="text-sm font-medium text-slate-800">{{ $post->author->name }}</span>
                                                                                        <span class="text-xs px-2 py-0.5 rounded-full
                                        @php
                                            // Determine role for display
                                            $class = $post->class;
                                            $userRole = 'member';
                                            if ($class->isClassTeacher(Auth::id()) || $class->isOrganizationAdmin(Auth::id())) {
                                                $userRole = 'teacher';
                                            }
                                        @endphp
                                        @if($userRole == 'teacher')
                                            bg-blue-100 text-blue-600
                                        @else
                                            bg-slate-100 text-slate-600
                                        @endif">
                                                                                            {{ ucfirst($userRole) }}
                                                                                        </span>
                                                                                    </div>
                                                                                    <span
                                                                                        class="text-sm text-slate-500">{{ $post->created_at->diffForHumans() }}</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Post Content -->
                                                                        <p class="text-slate-700 mb-4 leading-relaxed whitespace-pre-line">
                                                                            {{ $post->content }}
                                                                        </p>

                                                                        <!-- Post Attachment (if exists) -->
                                                                        @if($post->media_url)
                                                                            <div class="glass-card rounded-xl p-4 mb-4">
                                                                                <div class="flex items-center gap-3 text-slate-700">
                                                                                    @if($post->media_type == 'link')
                                                                                        <ion-icon name="link-outline" class="w-5 h-5 text-blue-500"></ion-icon>
                                                                                    @elseif($post->media_type == 'document')
                                                                                        <ion-icon name="document-text-outline"
                                                                                            class="w-5 h-5 text-blue-500"></ion-icon>
                                                                                    @elseif($post->media_type == 'image')
                                                                                        <ion-icon name="image-outline" class="w-5 h-5 text-blue-500"></ion-icon>
                                                                                    @elseif($post->media_type == 'video')
                                                                                        <ion-icon name="videocam-outline"
                                                                                            class="w-5 h-5 text-blue-500"></ion-icon>
                                                                                    @endif
                                                                                    <a href="{{ $post->media_url }}" target="_blank"
                                                                                        class="text-sm hover:text-blue-600 transition-colors">
                                                                                        {{ $post->media_url }}
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        @endif

                                                                        <!-- Post Actions -->
                                                                        <div class="flex items-center gap-6 text-slate-600 text-sm">
                                                                            <button class="glass-button flex items-center gap-2 px-4 py-2 rounded-xl hover:text-red-600 transition-all like-button
                                                                                {{ $post->is_liked ? 'text-red-600' : '' }}"
                                                                                onclick="toggleLike({{ $post->class_id }}, {{ $post->id }}, this)">
                                                                                <ion-icon name="{{ $post->is_liked ? 'heart' : 'heart-outline' }}"
                                                                                    class="w-5 h-5"></ion-icon>
                                                                                <span class="like-count">{{ $post->likes_count }}</span>
                                                                            </button>
                                                                            <button
                                                                                class="glass-button flex items-center gap-2 px-4 py-2 rounded-xl hover:text-blue-600 transition-all"
                                                                                onclick="toggleComments(this)">
                                                                                <ion-icon name="chatbubble-outline" class="w-5 h-5"></ion-icon>
                                                                                <span>{{ $post->comments_count }}</span>
                                                                            </button>
                                                                            <a href="/classes/{{ $post->class_id }}"
                                                                                class="glass-button flex items-center gap-2 px-4 py-2 rounded-xl hover:text-blue-600 transition-all">
                                                                                <ion-icon name="enter-outline" class="w-5 h-5"></ion-icon>
                                                                                <span>View Class</span>
                                                                            </a>
                                                                        </div>

                                                                        <!-- Comments Section (Collapsed by default) -->
                                                                        <div class="mt-4 pt-4 border-t border-white/20 hidden comments-section">
                                                                            <!-- Existing Comments -->
                                                                            <div class="mb-4 space-y-3">
                                                                                @foreach($post->comments as $comment)
                                                                                    <div class="flex gap-3">
                                                                                        <img src="{{ asset($comment->author->profile_image ?? 'images/pfp.jpg') }}"
                                                                                            alt="User"
                                                                                            class="w-8 h-8 rounded-full border border-white/50 flex-shrink-0">
                                                                                        <div class="flex-1">
                                                                                            <div class="flex items-center gap-2 mb-1">
                                                                                                <span
                                                                                                    class="text-sm font-medium text-slate-800">{{ $comment->author->name }}</span>
                                                                                                <span
                                                                                                    class="text-xs text-slate-500">{{ $comment->created_at->diffForHumans() }}</span>
                                                                                            </div>
                                                                                            <p class="text-sm text-slate-700">{{ $comment->content }}</p>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>

                                                                            <!-- Add Comment Form -->
                                                                            <div class="flex items-center gap-3">
                                                                                <img src="{{ asset(Auth::user()->profile_image ?? 'images/pfp.jpg') }}"
                                                                                    alt="User"
                                                                                    class="w-8 h-8 rounded-full border border-white/50 flex-shrink-0">
                                                                                <div class="flex-1 relative">
                                                                                    <input type="text" placeholder="Add a comment..."
                                                                                        class="w-full glass-input px-4 py-2 pr-10 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 comment-input">
                                                                                    <button
                                                                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-blue-500 hover:text-blue-600"
                                                                                        onclick="addComment({{ $post->class_id }}, {{ $post->id }}, this)">
                                                                                        <ion-icon name="send-outline" class="w-4 h-4"></ion-icon>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                    @endforeach

                                    <!-- No Posts Message (when user is not in any classes) -->
                                    @if($classPosts->isEmpty())
                                        <div class="glass-card rounded-2xl p-8 text-center">
                                            <div
                                                class="w-16 h-16 mx-auto mb-4 rounded-full glass-card flex items-center justify-center">
                                                <i data-feather="book" class="w-8 h-8 text-slate-400"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-slate-800 mb-2">No Class Posts Yet</h3>
                                            <p class="text-slate-600 mb-4">Join some classes to see posts from your teachers
                                                and classmates.</p>
                                            <a href="/search"
                                                class="inline-flex items-center gap-2 px-6 py-3 glass-button rounded-xl text-blue-600 hover:text-blue-700 font-medium">
                                                <i data-feather="search" class="w-4 h-4"></i>
                                                <span>Find Classes to Join</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <!-- Pagination -->
                                @if($classPosts->hasPages())
                                    <div class="mt-6 flex justify-center">
                                        <nav class="glass-card rounded-xl p-2">
                                            <ul class="flex items-center gap-1">
                                                <!-- Previous Page Link -->
                                                @if($classPosts->onFirstPage())
                                                    <li class="opacity-50">
                                                        <span class="glass-button px-4 py-2 rounded-lg text-slate-600">
                                                            <ion-icon name="chevron-back-outline" class="w-4 h-4"></ion-icon>
                                                        </span>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a href="{{ $classPosts->previousPageUrl() }}"
                                                            class="glass-button px-4 py-2 rounded-lg text-slate-600 hover:text-blue-600">
                                                            <ion-icon name="chevron-back-outline" class="w-4 h-4"></ion-icon>
                                                        </a>
                                                    </li>
                                                @endif

                                                <!-- Page Numbers -->
                                                @foreach(range(1, $classPosts->lastPage()) as $page)
                                                    @if($page == $classPosts->currentPage())
                                                        <li>
                                                            <span
                                                                class="glass-button active px-4 py-2 rounded-lg text-blue-600 font-medium">{{ $page }}</span>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <a href="{{ $classPosts->url($page) }}"
                                                                class="glass-button px-4 py-2 rounded-lg text-slate-600 hover:text-blue-600">{{ $page }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach

                                                <!-- Next Page Link -->
                                                @if($classPosts->hasMorePages())
                                                    <li>
                                                        <a href="{{ $classPosts->nextPageUrl() }}"
                                                            class="glass-button px-4 py-2 rounded-lg text-slate-600 hover:text-blue-600">
                                                            <ion-icon name="chevron-forward-outline" class="w-4 h-4"></ion-icon>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="opacity-50">
                                                        <span class="glass-button px-4 py-2 rounded-lg text-slate-600">
                                                            <ion-icon name="chevron-forward-outline" class="w-4 h-4"></ion-icon>
                                                        </span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </nav>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Sidebar Area -->
                    <div class="w-80 flex-shrink-0 sticky top-0 h-screen overflow-y-auto p-6">
                        <div class="text-xl font-semibold mb-6 text-slate-800">Your Dashboard</div>

                        <!-- My Classes Card -->
                        <div class="glass-card rounded-2xl p-5 mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-slate-900">My Classes</h3>
                                <a href="/classes" class="text-xs text-blue-600 hover:text-blue-700">View All</a>
                            </div>
                            <div class="space-y-4">
                                @foreach($userClasses as $class)
                                    <a href="/classes/{{ $class['id'] }}"
                                        class="flex items-center gap-3 p-3 hover:bg-white/30 rounded-xl cursor-pointer transition-all group">
                                        <div
                                            class="glass-button p-2.5 rounded-xl flex flex-col items-center justify-center min-w-12">
                                            <span
                                                class="font-bold text-xs text-blue-600">{{ substr($class['code'], 0, 3) }}</span>
                                            <span class="text-xs text-slate-600">{{ substr($class['code'], -3) }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-slate-800 group-hover:text-blue-600">
                                                {{ $class['name'] }}</p>
                                            <div class="flex items-center gap-2 text-xs text-slate-600">
                                                <span>{{ $class['teacher_name'] }}</span>
                                                <span>•</span>
                                                <span>{{ $class['members_count'] }} members</span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach

                                @if($userClasses->isEmpty())
                                    <div class="text-center py-4">
                                        <p class="text-sm text-slate-600 mb-2">You haven't joined any classes yet</p>
                                        <a href="/search" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Find
                                            Classes</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Feather Icons
        feather.replace();

        // CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        // Toggle comments section
        function toggleComments(button) {
            const postElement = button.closest('.glass-card');
            const commentsSection = postElement.querySelector('.comments-section');
            commentsSection.classList.toggle('hidden');
        }

        // Toggle like on a post
        async function toggleLike(classId, postId, button) {
            const heartIcon = button.querySelector('ion-icon');
            const likeCountSpan = button.querySelector('.like-count');
            let currentLikes = parseInt(likeCountSpan.textContent);
            let isLiked = heartIcon.getAttribute('name') === 'heart';

            try {
                const response = await fetch(`/api/classes/${classId}/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error('Failed to toggle like');
                }

                const data = await response.json();

                if (data.success) {
                    if (data.liked) {
                        heartIcon.setAttribute('name', 'heart');
                        button.classList.add('text-red-600');
                    } else {
                        heartIcon.setAttribute('name', 'heart-outline');
                        button.classList.remove('text-red-600');
                    }
                    likeCountSpan.textContent = data.likes_count;
                }
            } catch (error) {
                console.error('Error toggling like:', error);
                alert('Failed to toggle like. Please try again.');
            }
        }

        // Add comment to a post
        async function addComment(classId, postId, button) {
            const postElement = button.closest('.glass-card');
            const commentInput = postElement.querySelector('.comment-input');
            const commentText = commentInput.value.trim();

            if (!commentText) {
                alert('Please enter a comment');
                return;
            }

            try {
                const response = await fetch(`/classes/${classId}/posts/${postId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        content: commentText
                    }),
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error('Failed to add comment');
                }

                const data = await response.json();

                if (data.success) {
                    // Clear input
                    commentInput.value = '';

                    // Add new comment to the list
                    const commentsContainer = postElement.querySelector('.comments-section .space-y-3');
                    const commentHtml = `
                        <div class="flex gap-3">
                            <img src="${data.comment.author.profile_image}" alt="User"
                                class="w-8 h-8 rounded-full border border-white/50 flex-shrink-0">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-medium text-slate-800">${data.comment.author.name}</span>
                                    <span class="text-xs text-slate-500">${data.comment.created_at}</span>
                                </div>
                                <p class="text-sm text-slate-700">${data.comment.content}</p>
                            </div>
                        </div>
                    `;

                    commentsContainer.insertAdjacentHTML('beforeend', commentHtml);

                    // Update comment count
                    const commentCountSpan = postElement.querySelector('.glass-button:nth-child(2) span');
                    const currentCount = parseInt(commentCountSpan.textContent);
                    commentCountSpan.textContent = currentCount + 1;
                }
            } catch (error) {
                console.error('Error adding comment:', error);
                alert('Failed to add comment. Please try again.');
            }
        }

        // Load more posts (infinite scroll)
        let isLoading = false;
        let nextPageUrl = '{{ $classPosts->nextPageUrl() }}';

        window.addEventListener('scroll', async () => {
            if (isLoading || !nextPageUrl) return;

            const scrollPosition = window.innerHeight + window.scrollY;
            const pageHeight = document.documentElement.scrollHeight;

            if (scrollPosition >= pageHeight - 500) {
                isLoading = true;

                try {
                    const response = await fetch(nextPageUrl);
                    const html = await response.text();

                    // Parse the HTML to extract new posts
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newPosts = doc.querySelector('#class-posts-container').innerHTML;
                    const newNextPageUrl = doc.querySelector('[rel="next"]')?.href || null;

                    // Append new posts
                    document.querySelector('#class-posts-container').insertAdjacentHTML('beforeend', newPosts);

                    // Update next page URL
                    nextPageUrl = newNextPageUrl;

                    // Reinitialize Feather icons for new content
                    feather.replace();
                } catch (error) {
                    console.error('Error loading more posts:', error);
                } finally {
                    isLoading = false;
                }
            }
        });
    </script>
</body>

</html>
