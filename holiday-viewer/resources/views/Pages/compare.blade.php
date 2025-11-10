<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compare Countries - HolidaySys</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    @include('Components.sidebar')

    <div class="flex-1 ml-64">
        @include('Components.header', ['title' => 'Compare Countries'])

        <div class="p-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Compare Countries</h2>
                <p class="text-gray-600">Compare holiday schedules between different countries</p>
            </div>

            <!-- Comparison Form -->
            <div class="bg-white rounded-2xl p-6 shadow-lg mb-8 border border-gray-100">
                <form method="GET" action="{{ route('compare') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="ri-map-pin-line mr-1"></i>Country 1
                            </label>
                            <select name="country1" class="w-full border-2 border-gray-200 rounded-xl p-3 focus:border-[#ff6b35] transition-all">
                                @foreach($countries as $code => $name)
                                    <option value="{{ $code }}" {{ ($country1 ?? 'PH') === $code ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="ri-map-pin-line mr-1"></i>Country 2
                            </label>
                            <select name="country2" class="w-full border-2 border-gray-200 rounded-xl p-3 focus:border-[#f7931e] transition-all">
                                @foreach($countries as $code => $name)
                                    <option value="{{ $code }}" {{ ($country2 ?? 'US') === $code ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="ri-calendar-line mr-1"></i>Year
                            </label>
                            <input type="number" name="year" value="{{ $year ?? now()->year }}" min="2000" max="2100"
                                   class="w-full border-2 border-gray-200 rounded-xl p-3 focus:border-[#5cb85c] transition-all">
                        </div>

                        <div class="flex items-end">
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-[#ff6b35] via-[#f7931e] to-[#5cb85c] text-white font-semibold px-6 py-3 rounded-xl hover:shadow-lg transition-all">
                                <i class="ri-git-compare-line mr-2"></i>Compare
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @if(isset($holidays1) && isset($holidays2))
                @php
                    $holidays1 = is_array($holidays1) ? $holidays1 : [];
                    $holidays2 = is_array($holidays2) ? $holidays2 : [];
                @endphp
                
                <!-- Comparison Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="rounded-2xl p-6 text-white shadow-xl" style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #5cb85c 100%);">
                        <h3 class="text-sm font-medium opacity-90 mb-2">{{ $countries[$country1] ?? $country1 }}</h3>
                        <p class="text-4xl font-bold">{{ count($holidays1) }}</p>
                        <p class="text-sm opacity-80 mt-2">Total Holidays</p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-[#ff6b35]/20 flex items-center justify-center">
                        <div class="text-center">
                            <i class="ri-arrow-left-right-line text-5xl text-[#ff6b35] mb-2"></i>
                            <p class="text-sm font-medium text-gray-600">Comparison</p>
                        </div>
                    </div>

                    <div class="rounded-2xl p-6 text-white shadow-xl" style="background: linear-gradient(135deg, #f7931e 0%, #5cb85c 100%);">
                        <h3 class="text-sm font-medium opacity-90 mb-2">{{ $countries[$country2] ?? $country2 }}</h3>
                        <p class="text-4xl font-bold">{{ count($holidays2) }}</p>
                        <p class="text-sm opacity-80 mt-2">Total Holidays</p>
                    </div>
                </div>

                <!-- Side by Side Comparison -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Country 1 -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                        <div class="text-white px-6 py-4 rounded-t-2xl" style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #5cb85c 100%);">
                            <h3 class="text-xl font-bold flex items-center">
                                <i class="ri-flag-line mr-2"></i>
                                {{ $countries[$country1] }} ({{ $country1 }})
                            </h3>
                        </div>
                        <div class="p-6 max-h-[600px] overflow-y-auto">
                            @if(empty($holidays1))
                                <p class="text-gray-500 text-center py-8">No holidays found</p>
                            @else
                                <div class="space-y-3">
                                    @foreach($holidays1 as $holiday)
                                        @php 
                                            $date = \Carbon\Carbon::parse($holiday['date']); 
                                            $name = $holiday['name'] ?? $holiday['localName'] ?? 'Holiday';
                                        @endphp
                                        <div class="flex items-center p-4 rounded-xl border border-gray-200 hover:border-[#ff6b35]/50 transition-all">
                                            <div class="w-14 h-14 rounded-xl flex flex-col items-center justify-center text-white mr-4 flex-shrink-0" style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #5cb85c 100%);">
                                                <span class="text-xs font-medium">{{ $date->format('M') }}</span>
                                                <span class="text-lg font-bold">{{ $date->format('d') }}</span>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-800 text-sm">{{ $name }}</h4>
                                                <p class="text-xs text-gray-500 mt-1">{{ $date->format('l, F d') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Country 2 -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                        <div class="text-white px-6 py-4 rounded-t-2xl" style="background: linear-gradient(135deg, #f7931e 0%, #5cb85c 100%);">
                            <h3 class="text-xl font-bold flex items-center">
                                <i class="ri-flag-line mr-2"></i>
                                {{ $countries[$country2] }} ({{ $country2 }})
                            </h3>
                        </div>
                        <div class="p-6 max-h-[600px] overflow-y-auto">
                            @if(empty($holidays2))
                                <p class="text-gray-500 text-center py-8">No holidays found</p>
                            @else
                                <div class="space-y-3">
                                    @foreach($holidays2 as $holiday)
                                        @php 
                                            $date = \Carbon\Carbon::parse($holiday['date']); 
                                            $name = $holiday['name'] ?? $holiday['localName'] ?? 'Holiday';
                                        @endphp
                                        <div class="flex items-center p-4 rounded-xl border border-gray-200 hover:border-[#f7931e]/50 transition-all">
                                            <div class="w-14 h-14 rounded-xl flex flex-col items-center justify-center text-white mr-4 flex-shrink-0" style="background: linear-gradient(135deg, #f7931e 0%, #5cb85c 100%);">
                                                <span class="text-xs font-medium">{{ $date->format('M') }}</span>
                                                <span class="text-lg font-bold">{{ $date->format('d') }}</span>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-800 text-sm">{{ $name }}</h4>
                                                <p class="text-xs text-gray-500 mt-1">{{ $date->format('l, F d') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>