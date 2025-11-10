<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Holidays - HolidaySys</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    @include('Components.sidebar')

    <div class="flex-1 ml-64">
        @include('Components.header', ['title' => 'Public Holidays'])

        <div class="p-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Public Holidays Explorer</h2>
                <p class="text-gray-600">Browse holidays for {{ $supportedCountries[$country] ?? $country }} in {{ $year }}</p>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-2xl p-6 shadow-lg mb-8 border border-gray-100">
                <form method="GET" action="{{ route('holidays') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="ri-global-line mr-1"></i>Select Country
                            </label>
                            <select name="country" 
                                    class="w-full border-2 border-gray-200 rounded-xl p-3 focus:border-[#ff6b35] focus:ring focus:ring-[#ff6b35]/20 transition-all">
                                @foreach($supportedCountries as $code => $name)
                                    <option value="{{ $code }}" {{ $country === $code ? 'selected' : '' }}>
                                        {{ $name }} ({{ $code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="ri-calendar-line mr-1"></i>Select Year
                            </label>
                            <input type="number" 
                                   name="year" 
                                   value="{{ $year }}" 
                                   min="2000" 
                                   max="2100"
                                   class="w-full border-2 border-gray-200 rounded-xl p-3 focus:border-[#ff6b35] focus:ring focus:ring-[#ff6b35]/20 transition-all">
                        </div>

                        <div class="flex items-end">
                            <button type="submit" 
                                    class="w-full text-white font-semibold px-6 py-3 rounded-xl hover:shadow-lg transition-all flex items-center justify-center" 
                                    style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #5cb85c 100%);">
                                <i class="ri-search-line mr-2"></i>
                                Load Holidays
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Results Section -->
            @if(isset($error))
                <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-xl flex items-center">
                    <i class="ri-error-warning-line text-3xl text-red-500 mr-4"></i>
                    <div>
                        <h3 class="font-semibold text-red-800">Error Loading Data</h3>
                        <p class="text-red-600">{{ $error }}</p>
                    </div>
                </div>
            @elseif(empty($holidays))
                <div class="bg-white rounded-2xl p-12 shadow-lg text-center border border-gray-100">
                    <i class="ri-calendar-close-line text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No Holidays Found</h3>
                    <p class="text-gray-500">No holidays available for {{ $supportedCountries[$country] ?? $country }} in {{ $year }}</p>
                </div>
            @else
                <!-- Stats Summary -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="rounded-xl p-4 text-white" style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #5cb85c 100%);">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm opacity-90">Total Holidays</p>
                                <p class="text-3xl font-bold">{{ count($holidays) }}</p>
                            </div>
                            <i class="ri-calendar-check-line text-4xl opacity-30"></i>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl p-4 shadow-md border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Country</p>
                                <p class="text-lg font-bold text-gray-800">{{ $supportedCountries[$country] ?? $country }}</p>
                            </div>
                            <i class="ri-map-pin-line text-3xl text-[#ff6b35]"></i>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 shadow-md border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Year</p>
                                <p class="text-lg font-bold text-gray-800">{{ $year }}</p>
                            </div>
                            <i class="ri-time-line text-3xl text-[#f7931e]"></i>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 shadow-md border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Data Source</p>
                                <p class="text-lg font-bold text-gray-800">Nager API</p>
                            </div>
                            <i class="ri-database-2-line text-3xl text-[#5cb85c]"></i>
                        </div>
                    </div>
                </div>

                <!-- Holidays Table -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #5cb85c 100%);" class="text-white">
                                    <th class="px-6 py-4 text-left font-semibold">#</th>
                                    <th class="px-6 py-4 text-left font-semibold">Date</th>
                                    <th class="px-6 py-4 text-left font-semibold">Holiday Name</th>
                                    <th class="px-6 py-4 text-left font-semibold">Local Name</th>
                                    <th class="px-6 py-4 text-left font-semibold">Type</th>
                                    <th class="px-6 py-4 text-left font-semibold">Day</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($holidays as $index => $holiday)
                                    @php
                                        $date = \Carbon\Carbon::parse($holiday['date']);
                                        $isWeekend = in_array($date->dayOfWeek, [0, 6]);
                                        $name = $holiday['name'] ?? 'Unnamed Holiday';
                                        $localName = $holiday['localName'] ?? $name;
                                        $types = $holiday['types'] ?? ['Public'];
                                        $type = is_array($types) ? $types[0] : 'Public';
                                    @endphp
                                    <tr class="border-b border-gray-100 hover:bg-[#ff6b35]/10 transition-colors">
                                        <td class="px-6 py-4 text-gray-600 font-medium">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 rounded-lg flex flex-col items-center justify-center text-white mr-3" style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #5cb85c 100%);">
                                                    <span class="text-xs font-medium">{{ $date->format('M') }}</span>
                                                    <span class="text-lg font-bold">{{ $date->format('d') }}</span>
                                                </div>
                                                <span class="font-medium text-gray-800">{{ $date->format('F d, Y') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-semibold text-gray-800">{{ $name }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-600">{{ $localName }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                {{ $type === 'Public' ? 'bg-green-100 text-green-700' : ($type === 'Bank' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                                                {{ $type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-700 flex items-center">
                                                @if($isWeekend)
                                                    <i class="ri-emotion-happy-line text-[#ff6b35] mr-1"></i>
                                                @endif
                                                {{ $date->format('l') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>