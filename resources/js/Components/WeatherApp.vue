<template>
    <div
        class="glass-bg mx-auto w-full max-w-3xl rounded-3xl p-1 shadow-xl sm:my-4"
    >
        <div class="grid grid-cols-1 gap-0 md:grid-cols-3">
            <!-- Left Side -->
            <div
                class="glass-inner col-span-1 flex min-h-[300px] flex-col items-center justify-center rounded-t-3xl p-4 sm:min-h-[350px] sm:p-6 md:rounded-l-3xl md:rounded-r-none"
            >
                <input
                    v-model="city"
                    @keyup.enter="getWeather"
                    type="text"
                    placeholder="Enter city name..."
                    class="mb-4 w-full rounded-full border-none bg-white/30 px-4 py-2 text-center text-base placeholder-gray-500 shadow-inner backdrop-blur-md focus:outline-none focus:ring-2 focus:ring-blue-400 sm:mb-6 sm:px-5 sm:text-lg"
                />
                <div
                    v-if="loading"
                    class="mt-6 flex flex-col items-center sm:mt-8"
                >
                    <span class="text-base text-gray-600 sm:text-lg"
                        >Loading...</span
                    >
                </div>
                <div v-else-if="weatherData" class="flex flex-col items-center">
                    <span class="mb-2 text-5xl sm:text-6xl">{{
                        weatherIcon
                    }}</span>
                    <span
                        class="text-2xl font-bold text-white drop-shadow-lg sm:text-3xl"
                    >
                        {{ Math.round(weatherData.main.temp) }}°C
                    </span>
                    <span
                        class="mt-2 text-sm capitalize text-white/80 sm:text-base"
                    >
                        {{ weatherData.weather[0].main }}
                    </span>
                </div>
                <div v-else class="mt-6 flex flex-col items-center sm:mt-8">
                    <span class="text-5xl text-gray-400 sm:text-6xl">☁️</span>
                    <span class="mt-2 text-base text-gray-400 sm:text-lg"
                        >No data yet</span
                    >
                </div>
            </div>
            <!-- Right Side -->
            <div
                class="glass-inner col-span-1 flex min-h-[300px] flex-col justify-center rounded-b-3xl p-5 sm:min-h-[350px] sm:p-8 md:col-span-2 md:rounded-b-none md:rounded-r-3xl"
            >
                <h2
                    class="mb-3 text-center text-xl font-semibold text-white drop-shadow-lg sm:mb-4 sm:text-2xl"
                >
                    Weather Application
                </h2>
                <div
                    v-if="error"
                    class="mb-3 text-center text-base font-medium text-red-300 sm:mb-4 sm:text-lg"
                >
                    {{ error }}
                </div>
                <div v-if="weatherData" class="space-y-2">
                    <h3
                        class="text-lg font-bold text-white drop-shadow sm:text-xl"
                    >
                        {{ weatherData.name }}, {{ weatherData.sys.country }}
                    </h3>
                    <p class="text-base text-white/90 sm:text-lg">
                        Weather:
                        <span class="capitalize">{{
                            weatherData.weather[0].description
                        }}</span>
                    </p>
                    <p class="text-base text-white/90 sm:text-lg">
                        Humidity: {{ weatherData.main.humidity }}%
                    </p>
                    <p class="text-base text-white/90 sm:text-lg">
                        Wind Speed: {{ weatherData.wind.speed }} m/s
                    </p>
                    <p class="mt-3 text-xs text-white/60 sm:mt-4 sm:text-sm">
                        Last updated:
                        {{ new Date(weatherData.dt * 1000).toLocaleString() }}
                    </p>
                </div>
                <div
                    v-else-if="loading"
                    class="text-center text-base text-white/80 sm:text-lg"
                >
                    Loading weather data...
                </div>
                <div
                    v-else
                    class="text-center text-base text-white/70 sm:text-lg"
                >
                    Please search for a city to view the weather.
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import { computed, ref } from 'vue';

const city = ref('');
const weatherData = ref(null);
const loading = ref(false);
const error = ref(null);

// Simple icon mapping based on weather main
const weatherIcon = computed(() => {
    if (!weatherData.value) return '☁️';
    const main = weatherData.value.weather[0].main.toLowerCase();
    if (main.includes('cloud')) return '☁️';
    if (main.includes('rain')) return '🌧️';
    if (main.includes('drizzle')) return '🌦️';
    if (main.includes('thunder')) return '⛈️';
    if (main.includes('snow')) return '❄️';
    if (main.includes('clear')) return '☀️';
    if (main.includes('mist') || main.includes('fog') || main.includes('haze'))
        return '🌫️';
    return '🌡️';
});

const getWeather = async () => {
    if (!city.value) {
        error.value = 'Please enter a city name.';
        weatherData.value = null;
        return;
    }

    loading.value = true;
    error.value = null;
    weatherData.value = null;

    try {
        const response = await axios.get('/api/weather', {
            params: { city: city.value },
        });
        weatherData.value = response.data;
    } catch (err) {
        if (err.response && err.response.status === 404) {
            error.value = 'City not found. Please try again.';
        } else {
            error.value =
                'An error occurred while fetching weather data. Please try again later.';
        }
        console.error(err);
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
.glass-bg {
    background: rgba(255, 255, 255, 0.15);
    border-radius: 2rem;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1.5px solid rgba(255, 255, 255, 0.25);
}
.glass-inner {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}
</style>
