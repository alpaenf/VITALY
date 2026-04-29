<template>
    <KaderLayout>
        <Head title="Smart Health Agent Dashboard" />

        <!-- Header -->
        <div class="mb-5">
            <h1 class="text-xl font-bold text-gray-800">Smart Health Agent Dashboard</h1>
            <p class="text-sm text-gray-500 mt-0.5">Selamat datang, <span class="font-medium text-gray-700">{{ user?.name }}</span></p>
        </div>


        <!-- Stats -->
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="bg-white rounded-xl p-4 sm:p-5 shadow-sm border border-gray-100 flex flex-col justify-center text-center">
                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ stats.totalPatients }}</p>
                <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">Total Pasien</p>
            </div>
            <div class="bg-white rounded-xl p-4 sm:p-5 shadow-sm border border-gray-100 flex flex-col justify-center text-center">
                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ stats.totalRecords }}</p>
                <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">Data Rekam</p>
            </div>
            <!-- Quantitative Analysis Stats replaced by Risk Summary Card below -->
        </div>

        <!-- Risk Summary Card (Detailed) -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100 hover-lift mb-6">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1.5 h-4 sm:h-5 rounded-full bg-primary"></div>
                <h3 class="font-semibold text-gray-700 text-xs sm:text-sm">Ringkasan Risiko Pasien</h3>
                <span class="ml-auto text-[10px] sm:text-xs text-gray-400">dari {{ stats.analyzedPatients || 0 }} pasien tercatat</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <!-- Hipertensi -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] sm:text-xs font-medium text-gray-600">Hipertensi</span>
                        <span class="text-[11px] sm:text-xs font-bold text-primary">{{ stats.countHighBp || 0 }}/{{ stats.analyzedPatients || 0 }}</span>
                    </div>
                    <div class="w-full h-1.5 sm:h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-primary to-primary-dark transition-all duration-700"
                            :style="`width:${stats.pctHighBp || 0}%`"></div>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] sm:text-[11px] text-gray-400">{{ stats.pctHighBp || 0 }}% dari total pasien</p>
                        <button v-if="stats.countHighBp > 0"
                            @click="openPanel = openPanel === 'bp' ? null : 'bp'"
                            class="text-[10px] sm:text-[11px] text-primary hover:underline flex items-center gap-1 transition">
                            {{ openPanel === 'bp' ? 'Sembunyikan' : 'Lihat daftar' }}
                            <svg class="w-3 h-3 transition-transform" :class="openPanel === 'bp' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                    <Transition name="slide-fade">
                        <div v-if="openPanel === 'bp'" class="rounded-xl border border-red-100 bg-red-50/50 overflow-hidden mt-2">
                            <div v-for="p in stats.patientsHighBp" :key="p.id"
                                class="flex items-center justify-between px-3 py-2 border-b border-red-100/50 last:border-0 hover:bg-red-50 transition">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-[9px] sm:text-[10px] flex-shrink-0">
                                        {{ p.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <Link :href="`/kader/pasien/${p.id}`" class="text-[11px] sm:text-xs font-medium text-gray-700 hover:text-primary hover:underline truncate">
                                        {{ p.name }}
                                    </Link>
                                </div>
                                <span class="text-[9px] sm:text-[10px] font-mono font-semibold text-primary bg-primary/10 px-1.5 py-0.5 rounded-full flex-shrink-0 ml-2">
                                    {{ p.systolic }}/{{ p.diastolic }}
                                </span>
                            </div>
                        </div>
                    </Transition>
                </div>

                <!-- Gula Darah Tinggi -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] sm:text-xs font-medium text-gray-600">Gula Darah Tinggi</span>
                        <span class="text-[11px] sm:text-xs font-bold text-primary">{{ stats.countHighSugar || 0 }}/{{ stats.analyzedPatients || 0 }}</span>
                    </div>
                    <div class="w-full h-1.5 sm:h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-primary to-primary-dark transition-all duration-700"
                            :style="`width:${stats.pctHighSugar || 0}%`"></div>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] sm:text-[11px] text-gray-400">{{ stats.pctHighSugar || 0 }}% dari total pasien</p>
                        <button v-if="stats.countHighSugar > 0"
                            @click="openPanel = openPanel === 'sugar' ? null : 'sugar'"
                            class="text-[10px] sm:text-[11px] text-primary hover:underline flex items-center gap-1 transition">
                            {{ openPanel === 'sugar' ? 'Sembunyikan' : 'Lihat daftar' }}
                            <svg class="w-3 h-3 transition-transform" :class="openPanel === 'sugar' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                    <Transition name="slide-fade">
                        <div v-if="openPanel === 'sugar'" class="rounded-xl border border-red-100 bg-red-50/50 overflow-hidden mt-2">
                            <div v-for="p in stats.patientsHighSugar" :key="p.id"
                                class="flex items-center justify-between px-3 py-2 border-b border-red-100/50 last:border-0 hover:bg-red-50 transition">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-[9px] sm:text-[10px] flex-shrink-0">
                                        {{ p.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <Link :href="`/kader/pasien/${p.id}`" class="text-[11px] sm:text-xs font-medium text-gray-700 hover:text-primary hover:underline truncate">
                                        {{ p.name }}
                                    </Link>
                                </div>
                                <span class="text-[9px] sm:text-[10px] font-mono font-semibold text-primary bg-primary/10 px-1.5 py-0.5 rounded-full flex-shrink-0 ml-2">
                                    {{ p.blood_sugar }}
                                </span>
                            </div>
                        </div>
                    </Transition>
                </div>

                <!-- Obesitas -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] sm:text-xs font-medium text-gray-600">Obesitas (IMT ≥25)</span>
                        <span class="text-[11px] sm:text-xs font-bold text-primary">{{ stats.countObesity || 0 }}/{{ stats.analyzedPatients || 0 }}</span>
                    </div>
                    <div class="w-full h-1.5 sm:h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-primary to-primary-dark transition-all duration-700"
                            :style="`width:${stats.pctObesity || 0}%`"></div>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] sm:text-[11px] text-gray-400">{{ stats.pctObesity || 0 }}% dari total pasien</p>
                        <button v-if="stats.countObesity > 0"
                            @click="openPanel = openPanel === 'obesity' ? null : 'obesity'"
                            class="text-[10px] sm:text-[11px] text-primary hover:underline flex items-center gap-1 transition">
                            {{ openPanel === 'obesity' ? 'Sembunyikan' : 'Lihat daftar' }}
                            <svg class="w-3 h-3 transition-transform" :class="openPanel === 'obesity' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                    <Transition name="slide-fade">
                        <div v-if="openPanel === 'obesity'" class="rounded-xl border border-red-100 bg-red-50/50 overflow-hidden mt-2">
                            <div v-for="p in stats.patientsObesity" :key="p.id"
                                class="flex items-center justify-between px-3 py-2 border-b border-red-100/50 last:border-0 hover:bg-red-50 transition">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-[9px] sm:text-[10px] flex-shrink-0">
                                        {{ p.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <Link :href="`/kader/pasien/${p.id}`" class="text-[11px] sm:text-xs font-medium text-gray-700 hover:text-primary hover:underline truncate">
                                        {{ p.name }}
                                    </Link>
                                </div>
                                <span class="text-[9px] sm:text-[10px] font-mono font-semibold text-primary bg-primary/10 px-1.5 py-0.5 rounded-full flex-shrink-0 ml-2">
                                    IMT {{ p.imt }}
                                </span>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
            <Link href="/kader/pasien"
                class="bg-primary hover:bg-primary-dark text-white rounded-xl p-4 sm:p-5 flex items-center gap-3 transition shadow-md">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold truncate leading-tight">Kelola Pasien</p>
                    <p class="text-[11px] text-white/80 truncate mt-0.5">Cari & tambah pasien baru</p>
                </div>
            </Link>
            <Link href="/kader/pasien"
                class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5 flex items-center gap-3 hover:bg-gray-50 transition shadow-sm">
                <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate leading-tight">Input Data Rekam Baru</p>
                    <p class="text-[11px] text-gray-500 truncate mt-0.5">Masukkan data kesehatan pasien</p>
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
import { computed, ref } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import KaderLayout from '@/Layouts/KaderLayout.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const openPanel = ref(null);

const props = defineProps({
    stats: Object,
    recentRecords: Array,
    recentPatients: Array,
});

const formatDate = (d) => d ? new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
</script>

<style scoped>
.slide-fade-enter-active { transition: all 0.3s ease-out; }
.slide-fade-leave-active { transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1); }
.slide-fade-enter-from, .slide-fade-leave-to { opacity: 0; transform: translateY(-8px); }
</style>