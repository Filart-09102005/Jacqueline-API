<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class HolidaySystemController extends Controller
{
    protected $nagerApiBase = 'https://date.nager.at/api/v3';
    protected $openHolidaysApiBase = 'https://openholidaysapi.org';

    // Dashboard
    public function dashboard()
    {
        $countries = $this->getSupportedCountries();
        $types = ['Public', 'Bank', 'School', 'Observance'];
        $currentYear = now()->year;
        
        // Get user's country or default to Philippines
        $userCountry = 'PH';
        
        // Get holidays for user's country (CACHED)
        $holidays = $this->getHolidaysCached($userCountry, $currentYear);
        
        // Filter only upcoming holidays
        $upcomingHolidays = array_filter($holidays, function($holiday) {
            $date = $holiday['date'] ?? null;
            return $date ? Carbon::parse($date)->isFuture() || Carbon::parse($date)->isToday() : false;
        });
        
        // Sort by date
        usort($upcomingHolidays, function($a, $b) {
            return Carbon::parse($a['date'])->timestamp <=> Carbon::parse($b['date'])->timestamp;
        });
        
        // Limit to next 10 upcoming holidays
        $upcomingHolidays = array_slice($upcomingHolidays, 0, 10);
        
        // Get cached total holidays count (calculated in background)
        $totalHolidaysCount = Cache::remember('total_holidays_count_' . $currentYear, 3600, function() use ($countries, $currentYear) {
            $total = 0;
            foreach ($countries as $code => $name) {
                $countryHolidays = $this->getHolidaysCached($code, $currentYear);
                $total += count($countryHolidays);
            }
            return $total;
        });
        
        return view('Pages.dashboard', compact(
            'countries', 
            'types', 
            'holidays',
            'upcomingHolidays',
            'userCountry',
            'totalHolidaysCount'
        ));
    }

    // Holidays page
    public function holidays(Request $request)
    {
        $country = $request->query('country', 'PH');
        $year = $request->query('year', now()->year);
        $supportedCountries = $this->getSupportedCountries();

        try {
            $holidays = $this->getHolidaysCached($country, $year);
        } catch (\Exception $e) {
            $holidays = [];
            $error = $e->getMessage();
            return view('Pages.holidays', compact('holidays', 'supportedCountries', 'country', 'year', 'error'));
        }

        return view('Pages.holidays', compact('holidays', 'supportedCountries', 'country', 'year'));
    }

    // Countries page
    public function countries()
    {
        $countries = $this->getSupportedCountries();
        return view('Pages.countries', compact('countries'));
    }

    // Comparison page
    public function compare(Request $request)
    {
        $countries = $this->getSupportedCountries();
        $country1 = $request->query('country1', 'PH');
        $country2 = $request->query('country2', 'US');
        $year = $request->query('year', now()->year);

        $holidays1 = $this->getHolidaysCached($country1, $year);
        $holidays2 = $this->getHolidaysCached($country2, $year);

        return view('Pages.compare', compact('countries', 'country1', 'country2', 'year', 'holidays1', 'holidays2'));
    }

    // Statistics page (OPTIMIZED)
    public function statistics()
    {
        $countries = $this->getSupportedCountries();
        $currentYear = now()->year;
        
        // Cache the entire statistics calculation for 1 hour
        $statsData = Cache::remember('statistics_data_' . $currentYear, 3600, function() use ($countries, $currentYear) {
            $totalHolidays = 0;
            $upcomingHolidays = 0;
            $allCountriesData = [];
            
            foreach ($countries as $code => $name) {
                $countryHolidays = $this->getHolidaysCached($code, $currentYear);
                
                $holidayCount = count($countryHolidays);
                $totalHolidays += $holidayCount;
                
                // Count upcoming holidays for this country
                $upcomingCount = count(array_filter($countryHolidays, function($h) {
                    $date = $h['date'] ?? null;
                    return $date ? Carbon::parse($date)->isFuture() : false;
                }));
                $upcomingHolidays += $upcomingCount;
                
                // Store for ranking
                $allCountriesData[] = [
                    'code' => $code,
                    'name' => $name,
                    'count' => $holidayCount
                ];
            }
            
            // Sort countries by holiday count (descending)
            usort($allCountriesData, fn($a, $b) => $b['count'] <=> $a['count']);
            
            return [
                'totalHolidays' => $totalHolidays,
                'upcomingHolidays' => $upcomingHolidays,
                'allCountriesData' => $allCountriesData
            ];
        });
        
        // Get top 10 countries
        $topCountries = array_slice($statsData['allCountriesData'], 0, 10);
        
        // Get Philippines holidays for the detailed list
        $holidays = $this->getHolidaysCached('PH', $currentYear);
        
        // Calculate unique types
        $allTypes = ['Public', 'Bank', 'School', 'Observance'];
        
        // Build stats array
        $stats = [
            'total_holidays' => $statsData['totalHolidays'],
            'total_countries' => count($countries),
            'total_types' => count($allTypes),
            'upcoming' => $statsData['upcomingHolidays'],
        ];
        
        // Monthly data for Philippines
        $monthlyData = array_fill(0, 12, 0);
        foreach ($holidays as $holiday) {
            $date = $holiday['date'] ?? null;
            if ($date) {
                $month = Carbon::parse($date)->month - 1;
                $monthlyData[$month]++;
            }
        }
        
        // Types calculation for Philippines
        $typesLabels = ['Public', 'Bank', 'School', 'Observance'];
        $typesValues = array_fill(0, 4, 0);
        foreach ($holidays as $holiday) {
            $type = $holiday['type'] ?? 'Public';
            $index = array_search($type, $typesLabels);
            if ($index !== false) $typesValues[$index]++;
        }
        
        return view('Pages.statistics', compact(
            'stats', 
            'topCountries', 
            'holidays',
            'monthlyData',
            'typesLabels',
            'typesValues'
        ));
    }

    // CACHED VERSION - Fetch holidays with caching
    protected function getHolidaysCached($countryCode, $year)
    {
        // Cache for 24 hours (86400 seconds)
        $cacheKey = "holidays_{$countryCode}_{$year}";
        
        return Cache::remember($cacheKey, 86400, function() use ($countryCode, $year) {
            return $this->getHolidays($countryCode, $year);
        });
    }

    // Fetch holidays helper - Uses both APIs for better coverage
    protected function getHolidays($countryCode, $year)
    {
        // Try Nager API first (faster and simpler)
        try {
            $response = Http::timeout(10)->get("{$this->nagerApiBase}/PublicHolidays/{$year}/{$countryCode}");
            
            if ($response->successful()) {
                $data = $response->json();
                if (is_array($data) && !empty($data)) {
                    // Transform Nager data to consistent format
                    return array_map(function($holiday) {
                        return [
                            'date' => $holiday['date'] ?? null,
                            'name' => $holiday['localName'] ?? $holiday['name'] ?? 'Holiday',
                            'type' => $this->mapNagerType($holiday['types'] ?? [])
                        ];
                    }, $data);
                }
            }
        } catch (\Exception $e) {
            // Fall through to OpenHolidays API
        }

        // Fallback to OpenHolidays API
        try {
            $response = Http::timeout(10)->get("{$this->openHolidaysApiBase}/PublicHolidays", [
                'countryIsoCode' => $countryCode,
                'languageIsoCode' => 'EN',
                'validFrom' => "{$year}-01-01",
                'validTo' => "{$year}-12-31"
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (is_array($data) && !empty($data)) {
                    // Transform OpenHolidays data to consistent format
                    return array_map(function($holiday) {
                        return [
                            'date' => $holiday['startDate'] ?? null,
                            'name' => $holiday['name'][0]['text'] ?? 'Holiday',
                            'type' => $this->mapOpenHolidaysType($holiday)
                        ];
                    }, $data);
                }
            }
        } catch (\Exception $e) {
            // Return empty array if both APIs fail
        }

        return [];
    }

    // Map Nager API types to our standard types
    protected function mapNagerType($types)
    {
        if (empty($types)) return 'Public';
        
        $type = $types[0] ?? 'Public';
        $typeMap = [
            'Public' => 'Public',
            'Bank' => 'Bank',
            'School' => 'School',
            'Authorities' => 'Observance',
            'Optional' => 'Observance',
            'Observance' => 'Observance'
        ];
        
        return $typeMap[$type] ?? 'Public';
    }

    // Map OpenHolidays API data to our standard types
    protected function mapOpenHolidaysType($holiday)
    {
        // OpenHolidays uses different categorization
        $nationwide = $holiday['nationwide'] ?? true;
        $type = $holiday['type'] ?? 'Public';
        
        if (!$nationwide) return 'Observance';
        if (stripos($type, 'bank') !== false) return 'Bank';
        if (stripos($type, 'school') !== false) return 'School';
        
        return 'Public';
    }

    // Supported countries (countries available in both APIs)
    protected function getSupportedCountries()
    {
        return [
            'PH' => 'Philippines',
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'JP' => 'Japan',
            'DE' => 'Germany',
            'AU' => 'Australia',
            'CA' => 'Canada',
            'FR' => 'France',
            'IT' => 'Italy',
            'ES' => 'Spain',
            'IN' => 'India',
            'BR' => 'Brazil',
            'MX' => 'Mexico',
            'NL' => 'Netherlands',
            'BE' => 'Belgium',
            'CH' => 'Switzerland',
            'AT' => 'Austria',
            'SE' => 'Sweden',
            'NO' => 'Norway',
            'DK' => 'Denmark',
            'FI' => 'Finland',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'GR' => 'Greece',
            'IE' => 'Ireland',
            'NZ' => 'New Zealand',
            'SG' => 'Singapore',
            'MY' => 'Malaysia',
            'TH' => 'Thailand',
            'KR' => 'South Korea'
        ];
    }
}