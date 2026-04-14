<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    weekly: Array,
    monthly: Array
});
</script>

<template>
    <Head title="Pilihan Spesial" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Pilihan Spesial
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                <!-- Weekly Specials -->
                <div>
                    <h3 class="text-2xl font-bold text-indigo-700 mb-4 border-l-4 border-indigo-700 pl-4">Pilihan Setiap Minggu</h3>
                    <div v-if="weekly.length === 0" class="bg-white p-6 rounded-lg shadow text-gray-500">
                        Belum ada promo mingguan.
                    </div>
                    <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div v-for="menu in weekly" :key="menu.id" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                            <img :src="menu.image ? '/storage/' + menu.image : '/placeholder-menu.jpg'" class="h-48 w-full object-cover">
                            <div class="p-4">
                                <h4 class="font-bold text-lg">{{ menu.name }}</h4>
                                <p class="text-indigo-600 font-bold">Rp {{ Number(menu.price).toLocaleString('id-ID') }}</p>
                                <p class="text-gray-600 text-sm mt-2 line-clamp-2">{{ menu.description }}</p>
                                <Link :href="route('orders.create')" class="mt-4 block text-center bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 transition">
                                    Pesan Sekarang
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Specials -->
                <div>
                    <h3 class="text-2xl font-bold text-pink-600 mb-4 border-l-4 border-pink-600 pl-4">Pilihan Setiap Bulan</h3>
                    <div v-if="monthly.length === 0" class="bg-white p-6 rounded-lg shadow text-gray-500">
                        Belum ada promo bulanan.
                    </div>
                    <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div v-for="menu in monthly" :key="menu.id" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                            <img :src="menu.image ? '/storage/' + menu.image : '/placeholder-menu.jpg'" class="h-48 w-full object-cover">
                            <div class="p-4">
                                <h4 class="font-bold text-lg">{{ menu.name }}</h4>
                                <p class="text-pink-600 font-bold">Rp {{ Number(menu.price).toLocaleString('id-ID') }}</p>
                                <p class="text-gray-600 text-sm mt-2 line-clamp-2">{{ menu.description }}</p>
                                <Link :href="route('orders.create')" class="mt-4 block text-center bg-pink-600 text-white py-2 rounded hover:bg-pink-700 transition">
                                    Pesan Sekarang
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
