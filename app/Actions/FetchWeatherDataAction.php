<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FetchWeatherDataAction
{
    use AsAction;

    public function handle(string $city)
    {
        $apiKey = config('services.openweathermap.key');
        $baseUrl = config('services.openweathermap.base_url');

        $response = Http::get($baseUrl, [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric',
        ]);

        if ($response->failed()) {
            if ($response->status() === 404) {
                throw new \Exception('City not found', 404);
            }
            throw new \Exception('Failed to fetch weather data', $response->status());
        }

        return $response->json();
    }

    // Method ini akan dipanggil ketika action digunakan sebagai controller
    public function asController(Request $request)
    {
        $city = $request->query('city');

        if (!$city) {
            return response()->json(['error' => 'City parameter is required'], 400);
        }

        try {
            $weatherData = $this->handle($city);
            return response()->json($weatherData);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
