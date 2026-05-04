<template>
    <AppLayout>
        <Head title="Input Data Mandiri" />

        <!-- Header -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary via-primary to-primary-dark text-white p-5 mb-5 animate-fade-in-down">
            <div class="absolute -top-6 -right-6 w-28 h-28 bg-white/10 rounded-full"></div>
            <div class="absolute -bottom-8 -left-4 w-20 h-20 bg-white/5 rounded-full"></div>
            <div class="relative flex flex-col sm:flex-row sm:items-center gap-4">
                <!-- Title Section -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold leading-tight">Input Data Mandiri</h1>
                        <p class="text-white/70 text-xs mt-0.5">Isi data kesehatanmu sendiri</p>
                    </div>
                </div>
                
                <!-- Sync button -->
                <button @click="doSync" :disabled="isSyncing"
                    class="sm:ml-auto w-full sm:w-auto flex items-center justify-center gap-2 bg-white/20 hover:bg-white/30 text-white text-xs font-semibold px-4 py-3 sm:py-2 rounded-xl transition disabled:opacity-60 flex-shrink-0 mt-2 sm:mt-0">
                    <svg v-if="!isSyncing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <svg v-else class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>{{ isSyncing ? (syncStatus || 'Mencari...') : 'Sync Smartwatch' }}</span>
                </button>
            </div>
        </div>

        <!-- Sync success banner -->
        <div v-if="syncSuccess" class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 mb-4 text-sm text-emerald-700 animate-fade-in-down">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            Data berhasil disinkronkan dari smartwatch! Silakan periksa dan simpan.
        </div>

        <!-- IoMT tip -->
        <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-5 text-xs text-blue-700">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span><strong>Tips:</strong> Klik "Sync Smartwatch" untuk mengisi data detak jantung & tekanan darah secara otomatis dari Mi Band 8. Data lain seperti berat badan dan gula darah tetap diisi manual.</span>
        </div>

        <!-- Form -->
        <form @submit.prevent="submit" class="space-y-4">

            <!-- Tekanan Darah -->
            <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 bg-primary/10 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </span>
                    Tekanan Darah
                    <span v-if="autoFilled.systolic" class="ml-auto text-[10px] bg-primary/10 text-primary px-2 py-0.5 rounded-full font-medium">📡 IoMT</span>
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Sistolik (mmHg)</label>
                        <input v-model="form.systolic" @input="clearAuto('systolic')" type="number" min="60" max="250" placeholder="120"
                            class="w-full px-3 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                            :class="[form.errors.systolic ? 'border-red-400' : 'border-gray-200', autoFilled.systolic ? 'bg-primary/5 border-primary/30' : '']" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Diastolik (mmHg)</label>
                        <input v-model="form.diastolic" @input="clearAuto('diastolic')" type="number" min="40" max="150" placeholder="80"
                            class="w-full px-3 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                            :class="[form.errors.diastolic ? 'border-red-400' : 'border-gray-200', autoFilled.diastolic ? 'bg-primary/5 border-primary/30' : '']" />
                    </div>
                </div>
            </div>

            <!-- Detak Jantung & SpO2 -->
            <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 bg-secondary/20 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                    </span>
                    Detak Jantung & SpO2
                    <span v-if="autoFilled.heart_rate" class="ml-auto text-[10px] bg-primary/10 text-primary px-2 py-0.5 rounded-full font-medium">📡 IoMT</span>
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Detak Jantung (bpm)</label>
                        <input v-model="form.heart_rate" @input="clearAuto('heart_rate')" type="number" min="30" max="220" placeholder="75"
                            class="w-full px-3 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                            :class="[autoFilled.heart_rate ? 'bg-primary/5 border-primary/30' : 'border-gray-200']" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">SpO2 (%)</label>
                        <input v-model="form.oxygen_saturation" @input="clearAuto('oxygen_saturation')" type="number" min="70" max="100" placeholder="98"
                            class="w-full px-3 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                            :class="[autoFilled.oxygen_saturation ? 'bg-primary/5 border-primary/30' : 'border-gray-200']" />
                    </div>
                </div>
            </div>

            <!-- Data Manual -->
            <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </span>
                    Data Manual
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Gula Darah (mg/dL)</label>
                        <input v-model="form.blood_sugar" type="number" min="30" max="600" placeholder="90"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Suhu Tubuh (°C)</label>
                        <input v-model="form.temperature" type="number" step="0.1" min="34" max="42" placeholder="36.5"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Berat Badan (kg)</label>
                        <input v-model="form.weight" type="number" step="0.1" min="1" max="300" placeholder="60"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Tinggi Badan (cm)</label>
                        <input v-model="form.height" type="number" min="50" max="250" placeholder="165"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition" />
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" :disabled="form.processing"
                class="w-full bg-primary text-white font-bold py-3.5 rounded-2xl text-sm hover:bg-primary-dark transition shadow-lg shadow-primary/30 disabled:opacity-50">
                <span v-if="form.processing">Menyimpan...</span>
                <span v-else>Simpan Data Kesehatan</span>
            </button>
        </form>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { syncVitalData } from '@/Services/BluetoothService';

const isSyncing   = ref(false);
const syncStatus  = ref('');
const syncSuccess = ref(false);

const autoFilled = ref({
    systolic: false, diastolic: false,
    heart_rate: false, oxygen_saturation: false,
});

const form = useForm({
    systolic:          '',
    diastolic:         '',
    heart_rate:        '',
    oxygen_saturation: '',
    blood_sugar:       '',
    temperature:       '',
    weight:            '',
    height:            '',
    source:            'manual',
});

const clearAuto = (field) => { autoFilled.value[field] = false; };

const doSync = async () => {
    isSyncing.value  = true;
    syncSuccess.value = false;
    const data = await syncVitalData((msg) => { syncStatus.value = msg; });
    if (data) {
        if (data.systolic)          { form.systolic          = data.systolic;          autoFilled.value.systolic          = true; }
        if (data.diastolic)         { form.diastolic         = data.diastolic;         autoFilled.value.diastolic         = true; }
        if (data.heart_rate)        { form.heart_rate        = data.heart_rate;        autoFilled.value.heart_rate        = true; }
        if (data.oxygen_saturation) { form.oxygen_saturation = data.oxygen_saturation; autoFilled.value.oxygen_saturation = true; }
        form.source   = 'iomt';
        syncSuccess.value = true;
    }
    isSyncing.value = false;
    syncStatus.value = '';
};

const submit = () => {
    form.post('/input-mandiri', {
        onSuccess: () => form.reset(),
    });
};
</script>
