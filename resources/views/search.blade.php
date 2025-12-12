<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover Organizations - EduGlass</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <style>
        /* Keep all the CSS styles from before */
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

        .glass-button.active {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.4);
        }

        /* Organization and Class Cards */
        .org-card {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }

        .org-card:hover {
            transform: translateY(-4px);
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .class-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.2s ease;
        }

        .class-card:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }

        /* Category badges */
        .badge-school {
            background: rgba(59, 130, 246, 0.15);
            color: #1d4ed8;
        }

        .badge-masjid {
            background: rgba(34, 197, 94, 0.15);
            color: #166534;
        }

        .badge-music {
            background: rgba(168, 85, 247, 0.15);
            color: #7c3aed;
        }

        .badge-tuition {
            background: rgba(245, 158, 11, 0.15);
            color: #92400e;
        }

        .badge-sports {
            background: rgba(239, 68, 68, 0.15);
            color: #991b1b;
        }

        .badge-community {
            background: rgba(6, 182, 212, 0.15);
            color: #155e75;
        }

        .badge-business {
            background: rgba(139, 92, 246, 0.15);
            color: #6d28d9;
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

        /* Role badges (keeping for organization details page) */
        .role-superadmin {
            background: linear-gradient(45deg, rgba(220, 38, 38, 0.15), rgba(239, 68, 68, 0.15));
            color: #991b1b;
        }

        .role-admin {
            background: linear-gradient(45deg, rgba(59, 130, 246, 0.15), rgba(96, 165, 250, 0.15));
            color: #1d4ed8;
        }

        /* Show More Button */
        .show-more-btn {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.2s ease;
        }

        .show-more-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 text-slate-800 font-sans">
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
                <!-- Header -->
                <div class="mb-8 text-center lg:text-left">
                    <h1 class="text-3xl lg:text-4xl font-bold text-slate-800 mb-3">Discover Organizations</h1>
                    <p class="text-slate-600 text-lg">Find schools, tuition centers, masjids, and other learning
                        communities</p>
                </div>

                <!-- Search Section -->
                <div class="glass-card rounded-2xl p-6 mb-8">
                    <!-- Search Bar -->
                    <div class="relative mb-6">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-feather="search" class="text-slate-500 w-5 h-5"></i>
                        </div>
                        <input type="text" id="searchInput"
                            placeholder="Search organizations by name, location, or type..."
                            class="glass-input w-full pl-12 pr-32 py-4 rounded-xl focus:outline-none placeholder-slate-500 text-slate-700"
                            value="{{ request('search') }}">
                        <button id="searchButton"
                            class="absolute right-2 top-2 bottom-2 bg-blue-500/90 backdrop-blur-sm text-white px-6 rounded-lg font-medium hover:bg-blue-600 transition-all duration-200 flex items-center gap-2 shadow-lg">
                            <i data-feather="search" class="w-4 h-4"></i>
                            Search
                        </button>
                    </div>

                    <!-- Quick Filters -->
                    <div class="flex flex-wrap gap-3 justify-center lg:justify-start mb-4">
                        <button class="glass-button active px-4 py-2 rounded-xl text-sm font-medium" data-filter="all">
                            All Organizations
                        </button>
                        <button class="glass-button px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2"
                            data-filter="school">
                            <i data-feather="school" class="w-4 h-4"></i>
                            Schools
                        </button>
                        <button class="glass-button px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2"
                            data-filter="masjid">
                            <i data-feather="home" class="w-4 h-4"></i>
                            Masjids
                        </button>
                        <button class="glass-button px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2"
                            data-filter="tuition">
                            <i data-feather="book-open" class="w-4 h-4"></i>
                            Tuition Centers
                        </button>
                        <button class="glass-button px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2"
                            data-filter="music">
                            <i data-feather="music" class="w-4 h-4"></i>
                            Music Schools
                        </button>
                    </div>

                    <!-- Location Filter -->
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <i data-feather="map-pin" class="w-4 h-4 text-slate-500"></i>
                            <select id="stateFilter"
                                class="glass-input px-3 py-2 rounded-lg text-sm text-slate-700 focus:outline-none">
                                <option value="">All States</option>
                                <option value="Kuala Lumpur" {{ request('state') == 'Kuala Lumpur' ? 'selected' : '' }}>
                                    Kuala Lumpur</option>
                                <option value="Selangor" {{ request('state') == 'Selangor' ? 'selected' : '' }}>Selangor
                                </option>
                                <option value="Penang" {{ request('state') == 'Penang' ? 'selected' : '' }}>Penang
                                </option>
                                <option value="Johor" {{ request('state') == 'Johor' ? 'selected' : '' }}>Johor</option>
                                <option value="Sabah" {{ request('state') == 'Sabah' ? 'selected' : '' }}>Sabah</option>
                                <option value="Sarawak" {{ request('state') == 'Sarawak' ? 'selected' : '' }}>Sarawak
                                </option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-feather="users" class="w-4 h-4 text-slate-500"></i>
                            <select id="statusFilter"
                                class="glass-input px-3 py-2 rounded-lg text-sm text-slate-700 focus:outline-none">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Results Section -->
                <div class="mb-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 gap-4">
                        <h2 class="text-xl lg:text-2xl font-semibold text-slate-800">
                            {{ $organizations->total() }} Organizations Found
                        </h2>
                        <div class="flex items-center gap-4">
                            <select id="sortFilter"
                                class="glass-input px-3 py-2 rounded-lg text-sm text-slate-700 focus:outline-none">
                                <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>Sort by:
                                    Relevance</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                            </select>
                        </div>
                    </div>

                    @if($organizations->count() > 0)
                        <!-- Organizations Grid -->
                        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($organizations as $org)
                                <div class="org-card rounded-2xl p-6 cursor-pointer"
                                    onclick="window.location.href='/organization/{{ $org->id }}'">
                                    <div class="flex items-start justify-between mb-4">
                                        <div
                                            class="w-16 h-16 rounded-2xl flex items-center justify-center backdrop-blur-sm bg-gradient-to-br {{ $org->typeInfo['bg'] }}">
                                            <i data-feather="{{ $org->typeInfo['icon'] }}"
                                                class="w-8 h-8 {{ $org->typeInfo['text'] }}"></i>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <span
                                                class="{{ $org->typeInfo['badge'] }} text-xs px-3 py-1 rounded-full font-medium mb-2">
                                                {{ ucfirst($org->type) }}
                                            </span>
                                            <div class="flex items-center gap-2">
                                                <div class="flex items-center gap-1 text-sm text-slate-500">
                                                    <i data-feather="map-pin" class="w-3 h-3"></i>
                                                    <span>{{ $org->district ?: $org->state }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h3 class="font-bold text-slate-800 mb-2 text-lg">{{ $org->nama }}</h3>
                                    <p class="text-slate-600 text-sm mb-4 leading-relaxed">
                                        {{ $org->address ? $org->address . ', ' . $org->postcode . ' ' . $org->district . ', ' . $org->state : 'No address provided' }}
                                    </p>

                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center gap-1">
                                            <i data-feather="users" class="w-4 h-4 text-slate-500"></i>
                                            <span class="font-medium text-slate-700">{{ $org->users_count }}</span>
                                            <span class="text-slate-500">members</span>
                                        </div>
                                        <span class="text-slate-400">|</span>
                                        <div class="flex items-center gap-1">
                                            <i data-feather="book" class="w-4 h-4 text-slate-500"></i>
                                            <span class="font-medium text-slate-700">{{ $org->active_classes_count }}</span>
                                            <span class="text-slate-500">active classes</span>
                                        </div>
                                    </div>

                                    <div class="border-t border-white/20 pt-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-sm font-medium text-slate-700">Organization Code:</span>
                                            <span class="text-blue-600 font-mono text-sm">{{ $org->code }}</span>
                                        </div>

                                        <!-- Real Classes Section - Only show active classes -->
                                        <div class="space-y-2">
                                            @php
                                                // Filter and get only active classes
                                                $activeClasses = $org->classes->where('status', true);
                                                $displayClasses = $activeClasses->take(4);
                                                $totalActiveClasses = $activeClasses->count();
                                            @endphp

                                            @if($totalActiveClasses > 0)
                                                <!-- Display only active classes (max 4) -->
                                                @foreach($displayClasses as $class)
                                                    <div class="class-card px-3 py-2 rounded-lg flex items-center justify-between">
                                                        <div>
                                                            <span class="font-medium text-slate-800">{{ $class->nama }}</span>
                                                            <div class="text-xs text-slate-500">
                                                                <span class="text-green-600">Active</span>
                                                            </div>
                                                        </div>
                                                        @if($class->is_member)
                                                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-sm">
                                                                ✓ Joined
                                                            </span>
                                                        @else
                                                            <button
                                                                class="text-blue-600 hover:text-blue-700 text-sm font-medium join-class-btn"
                                                                data-org-id="{{ $org->id }}" data-class-id="{{ $class->id }}"
                                                                data-class-name="{{ $class->nama }}"
                                                                onclick="event.stopPropagation(); joinClass(this);">
                                                                Join
                                                            </button>
                                                        @endif
                                                    </div>
                                                @endforeach

                                                <!-- Show "Show More" button if there are more than 4 active classes -->
                                                @if($totalActiveClasses > 4)
                                                    <div class="text-center">
                                                        <button
                                                            class="show-more-btn w-full py-2 rounded-lg text-sm text-blue-600 font-medium hover:text-blue-700"
                                                            onclick="event.stopPropagation(); window.location.href='/organization/{{ $org->id }}#classes';">
                                                            Show {{ $totalActiveClasses - 4 }} more active classes
                                                            <i data-feather="chevron-down" class="w-4 h-4 inline-block ml-1"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="text-center py-4">
                                                    <i data-feather="book-open" class="w-8 h-8 text-slate-400 mx-auto mb-2"></i>
                                                    <p class="text-slate-500 text-sm">No active classes available</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($organizations->hasPages())
                            <div class="mt-8">
                                {{ $organizations->links() }}
                            </div>
                        @endif
                    @else
                        <!-- No Results Message -->
                        <div class="text-center py-12">
                            <i data-feather="search" class="w-16 h-16 text-slate-400 mx-auto mb-4"></i>
                            <h3 class="text-xl font-semibold text-slate-700 mb-2">No organizations found</h3>
                            <p class="text-slate-500">Try adjusting your search or filters</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();

        // DOM Elements
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');
        const filterButtons = document.querySelectorAll('[data-filter]');
        const stateFilter = document.getElementById('stateFilter');
        const statusFilter = document.getElementById('statusFilter');
        const sortFilter = document.getElementById('sortFilter');

        // Filter button functionality
        filterButtons.forEach(button => {
            button.addEventListener('click', function () {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                updateFilters();
            });
        });

        // Search functionality
        searchButton.addEventListener('click', updateFilters);
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') updateFilters();
        });

        // Filter change listeners
        stateFilter.addEventListener('change', updateFilters);
        statusFilter.addEventListener('change', updateFilters);
        sortFilter.addEventListener('change', updateFilters);

        function updateFilters() {
            const params = new URLSearchParams();
            const searchTerm = searchInput.value.trim();

            if (searchTerm) {
                params.set('search', searchTerm);
            }

            const activeFilter = document.querySelector('[data-filter].active');
            if (activeFilter && activeFilter.dataset.filter !== 'all') {
                params.set('filter', activeFilter.dataset.filter);
            }

            if (stateFilter.value) {
                params.set('state', stateFilter.value);
            }

            if (statusFilter.value) {
                params.set('status', statusFilter.value);
            }

            if (sortFilter.value !== 'relevance') {
                params.set('sort', sortFilter.value);
            }

            window.location.href = '/search' + (params.toString() ? '?' + params.toString() : '');
        }

        function joinClass(button) {
            const orgId = button.getAttribute('data-org-id');
            const classId = button.getAttribute('data-class-id');
            const className = button.getAttribute('data-class-name');

            // Show loading state
            const originalText = button.textContent;
            button.textContent = 'Joining...';
            button.disabled = true;

            // Make API call to join class
            fetch('/api/join-class', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    organization_id: orgId,
                    class_id: classId,
                    class_name: className
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.textContent = 'Pending';
                        alert(data.message || 'Join request sent successfully!');
                    } else {
                        alert(data.message || 'Failed to join class');
                        button.textContent = originalText;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    button.textContent = originalText;
                    button.disabled = false;
                });
        }

        // Initialize filters from URL
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const currentFilter = urlParams.get('filter') || 'all';

            filterButtons.forEach(btn => {
                if (btn.dataset.filter === currentFilter) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            // Keyboard shortcut for search
            document.addEventListener('keydown', function (e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    searchInput.focus();
                }
            });
        });
    </script>
</body>

</html>
