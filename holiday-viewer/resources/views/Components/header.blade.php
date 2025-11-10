<div class="sticky top-0 z-40 flex justify-between items-center shadow-sm px-8 py-4 border-b border-gray-200 backdrop-blur-lg bg-white/90">
    <!-- Page Title with Breadcrumb -->
    <div>
        <div class="flex items-center text-sm text-gray-500 mb-1">
            <i class="ri-home-line mr-1"></i>
            <span>Home</span>
            <i class="ri-arrow-right-s-line mx-1"></i>
            <span class="text-orange-500 font-medium">{{ $title ?? 'Dashboard' }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
    </div>

    <!-- Right Section -->
    <div class="flex items-center gap-4">
        <!-- Current Date/Time -->
        <div class="hidden md:flex items-center gap-2 px-4 py-2 bg-gray-50 rounded-xl">
            <i class="ri-calendar-line text-orange-500"></i>
            <span class="text-sm font-medium text-gray-700">{{ now()->format('M d, Y') }}</span>
        </div>

        <!-- Notifications -->
        <button class="relative p-2 hover:bg-gray-100 rounded-xl transition-colors">
            <i class="ri-notification-line text-xl text-gray-700"></i>
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>

        <!-- User Profile Section -->
        <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
            <div class="hidden md:block text-right">
                <p class="text-sm font-semibold text-gray-800">
                    {{ Auth::user()->name ?? 'Guest User' }}
                </p>
                <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'guest@example.com' }}</p>
            </div>
            
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold" 
                 style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #5cb85c 100%);">
                {{ strtoupper(substr(Auth::user()->name ?? 'G', 0, 1)) }}
            </div>
        </div>

        <!-- Logout Button -->
        @auth
        <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="button" id="logoutButton"
                    class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white font-medium px-4 py-2 rounded-xl transition-all shadow-md hover:shadow-lg">
                <i class="ri-logout-box-line"></i>
                <span class="hidden md:inline">Logout</span>
            </button>
        </form>
        @endauth
    </div>
</div>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Logout confirmation
    document.getElementById('logoutButton').addEventListener('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff6b35',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Yes, logout!',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logoutForm').submit();
            }
        });
    });
</script>
