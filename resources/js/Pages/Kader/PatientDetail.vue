<template>
    <KaderLayout>
        <Head :title="patient.name" />

        <!-- Back link -->
        <Link href="/kader/pasien"
            class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-primary mb-5 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Pasien
        </Link>

        <!-- Patient Info Card -->
        <div class="bg-gradient-to-br from-primary to-primary-dark text-white rounded-2xl p-5 mb-6 shadow-lg shadow-primary/20">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center text-2xl font-bold">
                        {{ patient.name.charAt(0).toUpperCase() }}
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">{{ patient.name }}</h1>
                        <p class="text-white/70 text-sm font-mono">NIK: {{ patient.nik }}</p>
                        <p class="text-white/70 text-xs mt-0.5">
                            {{ patient.gender === 'male' ? 'Laki-laki' : patient.gender === 'female' ? 'Perempuan' : '-' }}
                            <span v-if="patient.date_of_birth"> · {{ formatAge(patient.date_of_birth) }}</span>
                            <span v-if="patient.phone"> · {{ patient.phone }}</span>
                        </p>
                    </div>
                </div>
                <Link :href="`/kader/pasien/${patient.id}/input`"
                    class="flex items-center gap-1.5 bg-white text-primary px-3 py-2 rounded-xl text-sm font-semibold hover:bg-gray-50 transition flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Input Data
                </Link>
            </div>
        </div>

        <!-- Flash -->
        <div v-if="$page.props.flash?.success"
            class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm mb-4">
            {{ $page.props.flash.success }}
        </div>

        <!-- Latest record summary -->
        <div v-if="latestRecord" class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            <div v-if="latestRecord.systolic" class="bg-white rounded-xl p-3 shadow-sm border border-gray-100 text-center">
                <p class="text-sm font-bold text-gray-800">{{ latestRecord.systolic }}/{{ latestRecord.diastolic }}</p>
                <p class="text-[10px] text-gray-400">Tekanan <span class="text-[9px]">mmHg</span></p>
            </div>
            <div v-if="latestRecord.heart_rate" class="bg-white rounded-xl p-3 shadow-sm border border-gray-100 text-center">
                <p class="text-sm font-bold text-gray-800">{{ latestRecord.heart_rate }}</p>
                <p class="text-[10px] text-gray-400">BPM</p>
            </div>
            <div v-if="latestRecord.blood_sugar" class="bg-white rounded-xl p-3 shadow-sm border border-gray-100 text-center">
                <p class="text-sm font-bold text-gray-800">{{ latestRecord.blood_sugar }}</p>
                <p class="text-[10px] text-gray-400">Gula <span class="text-[9px]">mg/dL</span></p>
            </div>
            <div v-if="latestRecord.weight" class="bg-white rounded-xl p-3 shadow-sm border border-gray-100 text-center">
                <p class="text-sm font-bold text-gray-800">{{ latestRecord.weight }} kg</p>
                <p class="text-[10px] text-gray-400">Berat Badan</p>
            </div>
        </div>

        <!-- Records table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Riwayat Data</h3>
                <span class="text-xs text-gray-400">{{ records.total }} catatan</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tekanan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">BPM</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Gula</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">BB/TB</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-if="records.data.length === 0">
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">Belum ada data</td>
                        </tr>
                        <tr v-for="r in records.data" :key="r.id" class="hover:bg-gray-50/70 transition-colors">
                            <td class="px-4 py-3 text-xs text-gray-500">{{ formatDate(r.recorded_at) }}</td>
                            <td class="px-4 py-3">
                                <span v-if="r.systolic" class="font-medium text-gray-800">{{ r.systolic }}/{{ r.diastolic }}</span>
                                <span v-else class="text-gray-300">—</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ r.heart_rate || '—' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ r.blood_sugar || '—' }}</td>
                            <td class="px-4 py-3 text-xs text-gray-500">
                                {{ r.weight && r.height ? `${r.weight}kg / ${r.height}cm` : '—' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div v-if="records.last_page > 1" class="px-4 py-3 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
                <span>{{ records.from }}–{{ records.to }} dari {{ records.total }}</span>
                <div class="flex gap-1">
                    <Link v-if="records.prev_page_url" :href="records.prev_page_url" class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-50 text-xs">← Prev</Link>
                    <Link v-if="records.next_page_url" :href="records.next_page_url" class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-50 text-xs">Next →</Link>
                </div>
            </div>
        </div>
    </KaderLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import KaderLayout from '@/Layouts/KaderLayout.vue';

const props = defineProps({
    patient: Object,
    records: Object,
    latestRecord: Object,
});

const formatDate = (d) => d ? new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-';

const formatAge = (dob) => {
    const diff = Date.now() - new Date(dob).getTime();
    const age = Math.floor(diff / (1000 * 60 * 60 * 24 * 365.25));
    return `${age} tahun`;
};
</script>
