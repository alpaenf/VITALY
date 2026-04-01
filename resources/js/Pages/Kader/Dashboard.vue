<template>
    <KaderLayout>
        <Head title="Dashboard Kader" />

        <!-- Header -->
        <div class="mb-5">
            <h1 class="text-xl font-bold text-gray-800">Dashboard Kader</h1>
            <p class="text-sm text-gray-500 mt-0.5">Selamat datang, <span class="font-medium text-gray-700">{{ user?.name }}</span></p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded-2xl p-4 sm:p-6 lg:p-8 shadow-sm border border-gray-100">
                <div class="w-9 h-9 bg-primary/10 rounded-xl flex items-center justify-center mb-2.5">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-800">{{ stats.totalPatients }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Total Pasien</p>
            </div>
            <div class="bg-white rounded-2xl p-4 sm:p-6 lg:p-8 shadow-sm border border-gray-100">
                <div class="w-9 h-9 bg-secondary/10 rounded-xl flex items-center justify-center mb-2.5">
                    <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-800">{{ stats.totalRecords }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Total Data Rekam</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <Link href="/kader/pasien"
                class="bg-primary text-white rounded-2xl p-4 sm:p-6 flex items-center gap-3 hover:bg-primary-dark transition shadow-lg shadow-primary/20">
                <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold truncate">Kelola Pasien</p>
                    <p class="text-xs text-white/70 truncate">Cari & tambah pasien</p>
                </div>
            </Link>
            <Link href="/kader/pasien"
                class="bg-white border border-gray-100 rounded-2xl p-4 sm:p-6 flex items-center gap-3 hover:bg-gray-50 transition shadow-sm">
                <div class="w-9 h-9 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">Input Data</p>
                    <p class="text-xs text-gray-400 truncate">Data kesehatan</p>
                </div>
            </Link>
        </div>

        <!-- Recent Records -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 py-3.5 border-b border-gray-50 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800 text-sm">Data Terbaru</h3>
                <Link href="/kader/pasien" class="text-xs text-primary hover:underline">Lihat semua</Link>
            </div>
            <div v-if="recentRecords.length === 0" class="text-center py-8 text-gray-400 text-sm">
                Belum ada data yang diinput
            </div>
            <div v-else class="divide-y divide-gray-50">
                <div v-for="record in recentRecords" :key="record.id"
                    class="flex items-center justify-between px-4 py-3 gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ record.patient?.name }}</p>
                        <p class="text-xs text-gray-400">{{ formatDate(record.recorded_at) }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p v-if="record.systolic" class="text-xs font-semibold text-primary">
                            {{ record.systolic }}/{{ record.diastolic }}
                        </p>
                        <Link :href="`/kader/pasien/${record.patient?.id}`"
                            class="text-xs text-primary hover:underline">Detail →</Link>
                    </div>
                </div>
            </div>
        </div>
    </KaderLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import KaderLayout from '@/Layouts/KaderLayout.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);

const props = defineProps({
    stats: Object,
    recentRecords: Array,
    recentPatients: Array,
});

const formatDate = (d) => d ? new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
</script>