<div class="w-64 h-screen bg-white border-r border-gray-200 flex flex-col fixed shadow-xl">
    <!-- Logo Section -->
    <div class="flex items-center gap-3 px-6 py-6 border-b border-gray-200" 
         style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #5cb85c 100%);">
        <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
            <i class="ri-calendar-event-fill text-white text-2xl"></i>
        </div>
        <div>
            <h1 class="text-xl font-bold text-white">HolidaySys</h1>
            <p class="text-xs text-white/80">Holiday Management</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="mt-6 px-4 pb-24 flex-1 overflow-y-auto">
        <ul class="flex flex-col gap-2">
            @php
                $links = [
                    ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'ri-dashboard-line'],
                    ['name' => 'Holidays', 'route' => 'holidays', 'icon' => 'ri-calendar-2-line'],
                    ['name' => 'Countries', 'route' => 'countries', 'icon' => 'ri-global-line'],
                    ['name' => 'Statistics', 'route' => 'statistics', 'icon' => 'ri-bar-chart-box-line'],
                    ['name' => 'Compare', 'route' => 'compare', 'icon' => 'ri-git-compare-line'],
                ];
            @endphp

            @foreach ($links as $link)
                <li>
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group
                       {{ request()->routeIs($link['route']) 
                           ? 'text-white shadow-lg shadow-orange-200' 
                           : 'text-gray-700 hover:bg-gray-50 hover:shadow-md' }}"
                       style="{{ request()->routeIs($link['route']) ? 'background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #5cb85c 100%);' : '' }}">
                        <div class="flex items-center gap-3">
                            <i class="{{ $link['icon'] }} text-xl
                                {{ request()->routeIs($link['route']) ? 'text-white' : 'text-gray-500 group-hover:text-orange-500' }}">
                            </i>
                            <span class="font-medium">{{ $link['name'] }}</span>
                        </div>
                        @if (request()->routeIs($link['route']))
                            <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                        @else
                            <i class="ri-arrow-right-s-line text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>

        <!-- Divider -->
        <div class="my-6 border-t border-gray-200"></div>

        <!-- Additional Menu Section -->
        <div class="px-2">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">System</p>
            <ul class="flex flex-col gap-2">
                <li>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 transition-all">
                        <i class="ri-settings-3-line text-xl text-gray-500"></i>
                        <span class="font-medium">Settings</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 transition-all">
                        <i class="ri-question-line text-xl text-gray-500"></i>
                        <span class="font-medium">Help & Support</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Footer -->
    <div class="p-4 border-t border-gray-200 bg-gray-50">
        <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-200">
            <div class="p-2 bg-green-100 rounded-lg">
                <i class="ri-shield-check-line text-green-600"></i>
            </div>
            <div class="flex-1">
                <p class="text-xs font-semibold text-gray-800">API Status</p>
                <p class="text-xs text-green-600 flex items-center">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1 animate-pulse"></span>
                    Connected
                </p>
            </div>
        </div>
    </div>
</div>
