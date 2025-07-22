<script setup lang="ts">
import WeatherApp from '@/Components/WeatherApp.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion: string;
    phpVersion: string;
}>();
</script>

<template>
    <div
        class="flex h-screen items-center justify-center bg-gradient-to-br from-blue-400 via-indigo-400 to-purple-500 px-4"
    >
        <Head title="Welcome" />
        <WeatherApp />
    </div>
</template>
