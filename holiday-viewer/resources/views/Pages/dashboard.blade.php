<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - HolidaySys</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    @include('Components.sidebar')

    <div class="flex-1 ml-64 transition-all duration-300">
        @include('Components.header', ['title' => 'Dashboard'])

        <div class="p-8 space-y-8">
            <!-- Welcome Section with Gradient -->
            <div class="rounded-3xl p-8 text-white shadow-xl" 
                 style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #5cb85c 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">Welcome to HolidaySys</h2>
                        <p class="text-white/90 text-lg">
                            Your comprehensive holiday management system powered by multiple APIs
                        </p>
                    </div>
                    <div class="hidden lg:block">
                        <i class="ri-calendar-event-line text-8xl opacity-20"></i>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Supported Countries -->
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-[#ff6b35]/20 rounded-xl">
                            <i class="ri-global-line text-3xl" style="color:#ff6b35;"></i>
                        </div>
                        <span class="text-xs font-semibold text-green-600 bg-green-100 px-3 py-1 rounded-full">Active</span>
                    </div>
                    <h3 class="text-gray-600 text-sm font-medium mb-1">Supported Countries</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ count($countries) }}</p>
                    <div class="mt-3 flex items-center text-sm text-gray-500">
                        <i class="ri-arrow-up-line text-green-500 mr-1"></i>
                        <span>All regions available</span>
                    </div>
                </div>

                <!-- Total Holidays (All Countries) -->
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-[#ff6b35]/20 rounded-xl">
                            <i class="ri-calendar-check-line text-3xl" style="color:#ff6b35;"></i>
                        </div>
                        <span class="text-xs font-semibold text-[#ff6b35] bg-[#ff6b35]/20 px-3 py-1 rounded-full">{{ now()->year }}</span>
                    </div>
                    <h3 class="text-gray-600 text-sm font-medium mb-1">Total Holidays</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalHolidaysCount ?? 0 }}</p>
                    <div class="mt-3 flex items-center text-sm text-gray-500">
                        <i class="ri-earth-line mr-1"></i>
                        <span>Across all countries</span>
                    </div>
                </div>

                <!-- Upcoming Holidays (User's Country) -->
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-[#ff6b35]/20 rounded-xl">
                            <i class="ri-calendar-event-line text-3xl" style="color:#ff6b35;"></i>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse mr-2"></div>
                            <span class="text-xs font-semibold text-green-600">Upcoming</span>
                        </div>
                    </div>
                    <h3 class="text-gray-600 text-sm font-medium mb-1">Your Holidays</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ count($upcomingHolidays ?? []) }}</p>
                    <div class="mt-3 flex items-center text-sm text-gray-500">
                        <i class="ri-map-pin-line mr-1"></i>
                        <span>{{ $countries[$userCountry] ?? 'Philippines' }}</span>
                    </div>
                </div>
            </div>

            <!-- Upcoming Holidays Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Upcoming Holidays -->
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <i class="ri-calendar-event-fill text-[#ff6b35] mr-2"></i>
                            Upcoming Holidays
                            <span class="ml-2 text-sm font-normal text-gray-500">({{ $countries[$userCountry] ?? 'Philippines' }})</span>
                        </h3>
                        <a href="{{ route('holidays', ['country' => $userCountry]) }}" 
                           class="text-sm text-[#ff6b35] hover:text-[#f7931e] font-medium flex items-center">
                            View All <i class="ri-arrow-right-line ml-1"></i>
                        </a>
                    </div>

                    @if(!empty($upcomingHolidays))
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @foreach($upcomingHolidays as $holiday)
                                @php
                                    $date = isset($holiday['date']) ? \Carbon\Carbon::parse($holiday['date']) : null;
                                    $isToday = $date ? $date->isToday() : false;
                                    $daysUntil = $date ? $date->diffInDays(now()) : 0;
                                @endphp

                                @if($date)
                                <div class="flex items-center p-4 rounded-xl hover:bg-gray-50 transition-all border border-gray-100
                                    {{ $isToday ? 'bg-[#ff6b35]/10 border-[#ff6b35]/50' : '' }}">
                                    <div class="flex-shrink-0 w-16 h-16 rounded-xl flex flex-col items-center justify-center text-white mr-4" 
                                         style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #5cb85c 100%);">
                                        <span class="text-xs font-medium">{{ $date->format('M') }}</span>
                                        <span class="text-2xl font-bold">{{ $date->format('d') }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-800">{{ $holiday['name'] ?? 'Holiday' }}</h4>
                                        <p class="text-sm text-gray-500 flex items-center mt-1">
                                            <i class="ri-calendar-line mr-1"></i>
                                            {{ $date->format('l, F d, Y') }}
                                            @if($isToday)
                                                <span class="ml-2 text-xs font-semibold text-[#ff6b35] bg-[#ff6b35]/20 px-2 py-0.5 rounded-full">Today</span>
                                            @elseif($daysUntil <= 7)
                                                <span class="ml-2 text-xs font-semibold text-green-600 bg-green-100 px-2 py-0.5 rounded-full">
                                                    In {{ $daysUntil }} {{ $daysUntil == 1 ? 'day' : 'days' }}
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-medium px-3 py-1 rounded-full bg-gray-100 text-gray-700">
                                            {{ $holiday['type'] ?? 'Public' }}
                                        </span>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="ri-calendar-line text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No upcoming holidays found</p>
                            <p class="text-sm text-gray-400 mt-2">All holidays for this year have passed</p>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="ri-flashlight-fill text-[#ff6b35] mr-2"></i>
                        Quick Actions
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('holidays') }}" 
                           class="flex items-center p-4 rounded-xl text-white hover:shadow-lg transition-all group" 
                           style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #5cb85c 100%);">
                            <div class="p-2 bg-white/20 rounded-lg mr-3">
                                <i class="ri-search-line text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold">Browse Holidays</h4>
                                <p class="text-xs text-white/80">Explore all countries</p>
                            </div>
                            <i class="ri-arrow-right-line group-hover:translate-x-1 transition-transform"></i>
                        </a>

                        <a href="{{ route('countries') }}" 
                           class="flex items-center p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition-all group border border-gray-200">
                            <div class="p-2 bg-[#ff6b35]/20 rounded-lg mr-3">
                                <i class="ri-earth-line text-xl" style="color:#ff6b35;"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800">View Countries</h4>
                                <p class="text-xs text-gray-500">All supported regions</p>
                            </div>
                            <i class="ri-arrow-right-line text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                        </a>

                        <a href="{{ route('statistics') }}" 
                           class="flex items-center p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition-all group border border-gray-200">
                            <div class="p-2 bg-[#ff6b35]/20 rounded-lg mr-3">
                                <i class="ri-bar-chart-box-line text-xl" style="color:#ff6b35;"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800">Statistics</h4>
                                <p class="text-xs text-gray-500">View analytics</p>
                            </div>
                            <i class="ri-arrow-right-line text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                        </a>

                        <a href="{{ route('compare') }}" 
                           class="flex items-center p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition-all group border border-gray-200">
                            <div class="p-2 bg-[#ff6b35]/20 rounded-lg mr-3">
                                <i class="ri-git-compare-line text-xl" style="color:#ff6b35;"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800">Compare</h4>
                                <p class="text-xs text-gray-500">Compare countries</p>
                            </div>
                            <i class="ri-arrow-right-line text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>