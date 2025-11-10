<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics - HolidaySys</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    @include('Components.sidebar')

    <div class="flex-1 ml-64">
        @include('Components.header', ['title' => 'Statistics & Analytics'])

        <div class="p-8 space-y-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Holiday Statistics</h2>
                <p class="text-gray-600">Comprehensive analytics across all supported countries</p>
            </div>

            <!-- Top Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="rounded-2xl p-6 text-white shadow-xl" style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #5cb85c 100%);">
                    <i class="ri-calendar-event-line text-4xl mb-3 opacity-80"></i>
                    <h3 class="text-sm font-medium opacity-90 mb-1">Total Holidays (All Countries)</h3>
                    <p class="text-4xl font-bold">{{ $stats['total_holidays'] ?? 0 }}</p>
                    <p class="text-xs mt-1 opacity-75">in {{ now()->year }}</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                    <i class="ri-global-line text-4xl mb-3" style="color:#ff6b35;"></i>
                    <h3 class="text-sm font-medium text-gray-600 mb-1">Countries Covered</h3>
                    <p class="text-4xl font-bold text-gray-800">{{ $stats['total_countries'] ?? 0 }}</p>
                    <p class="text-xs mt-1 text-gray-500">active countries</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                    <i class="ri-calendar-check-line text-4xl mb-3" style="color:#5cb85c;"></i>
                    <h3 class="text-sm font-medium text-gray-600 mb-1">Upcoming (All Countries)</h3>
                    <p class="text-4xl font-bold text-gray-800">{{ $stats['upcoming'] ?? 0 }}</p>
                    <p class="text-xs mt-1 text-gray-500">remaining this year</p>
                </div>
            </div>

            <!-- Top Countries Table -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="ri-trophy-line mr-2" style="color:#5cb85c;"></i>
                    Top Countries by Holidays ({{ now()->year }})
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Rank</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Country</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Holidays</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topCountries ?? [] as $index => $country)
                                <tr class="border-b hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        @if($index === 0)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 text-yellow-700 font-bold">
                                                <i class="ri-trophy-fill"></i>
                                            </span>
                                        @elseif($index === 1)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-700 font-bold">
                                                <i class="ri-medal-line"></i>
                                            </span>
                                        @elseif($index === 2)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-orange-100 text-orange-700 font-bold">
                                                <i class="ri-medal-line"></i>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-700 font-bold">
                                                {{ $index + 1 }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="p-2 bg-[#ff6b35]/10 rounded-lg mr-3">
                                                <i class="ri-flag-line text-[#ff6b35]"></i>
                                            </div>
                                            <div>
                                                <span class="font-semibold text-gray-800">{{ $country['name'] }}</span>
                                                <span class="text-xs text-gray-500 ml-2">({{ $country['code'] }})</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-2xl font-bold text-[#f7931e]">{{ $country['count'] }}</span>
                                        <span class="text-sm text-gray-500 ml-1">holidays</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('holidays', ['country' => $country['code']]) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-[#5cb85c]/10 text-[#5cb85c] rounded-lg hover:bg-[#5cb85c]/20 transition-colors">
                                            <i class="ri-arrow-right-line mr-1"></i>
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        No data available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Philippines Holidays List (Optional - can be changed to any country) -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="ri-calendar-line mr-2" style="color:#ff6b35;"></i>
                    Philippines Holidays ({{ now()->year }})
                </h3>
                
                @if(!empty($holidays) && count($holidays) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($holidays as $holiday)
                            @php
                                $date = isset($holiday->date) ? \Carbon\Carbon::parse($holiday->date) : null;
                                $name = $holiday->name ?? $holiday->localName ?? 'Holiday';
                                $types = $holiday->types ?? ['Public'];
                                $type = is_array($types) ? $types[0] : 'Public';
                            @endphp

                            @if($date)
                                <div class="flex items-center p-4 rounded-xl border border-gray-200 hover:bg-gray-50 transition-all">
                                    <div class="w-14 h-14 rounded-xl flex flex-col items-center justify-center text-white mr-4 flex-shrink-0" 
                                         style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #5cb85c 100%);">
                                        <span class="text-xs font-medium">{{ $date->format('M') }}</span>
                                        <span class="text-lg font-bold">{{ $date->format('d') }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-800 text-sm">{{ $name }}</h4>
                                        <p class="text-xs text-gray-500 mt-1">{{ $date->format('l, F d') }}</p>
                                        <span class="inline-block mt-1 text-xs font-medium px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">
                                            {{ $type }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="ri-calendar-close-line text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No holiday data available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>