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

                <!-- Sync buttons -->
                <div class="sm:ml-auto flex flex-col sm:flex-row gap-2 mt-2 sm:mt-0">
                    <!-- Notify for Xiaomi (PRIMARY) -->
                    <button @click="doSyncNotify" :disabled="isSyncing"
                        class="flex items-center justify-center gap-2 bg-white text-primary text-xs font-bold px-4 py-3 sm:py-2 rounded-xl transition disabled:opacity-60 hover:bg-white/90 shadow-lg">
                        <svg v-if="!isSyncing" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                        </svg>
                        <svg v-else class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>{{ isSyncing && syncMode === 'notify' ? 'Syncing...' : 'Sync Mi Band 8' }}</span>
                    </button>

                    <!-- Generic BLE (SECONDARY) -->
                    <button @click="doSync" :disabled="isSyncing"
                        class="flex items-center justify-center gap-2 bg-white/20 hover:bg-white/30 text-white text-xs font-semibold px-4 py-3 sm:py-2 rounded-xl transition disabled:opacity-60">
                        <svg v-if="!(isSyncing && syncMode === 'ble')" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                        </svg>
                        <svg v-else class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>{{ (isSyncing && syncMode === 'ble') ? 'Scanning...' : 'BLE Generic' }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Setup Guide for Notify -->
        <div v-if="showGuide" class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-4 animate-fade-in-down">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">📱</span>
                    <div>
                        <p class="text-sm font-bold text-blue-800 mb-2">Setup Notify for Xiaomi (Wajib 1x)</p>
                        <ol class="text-xs text-blue-700 space-y-1 list-decimal list-inside">
                            <li>Install <strong>"Notify for Xiaomi & Mi Fitness"</strong> dari Play Store</li>
                            <li>Buka Notify → Connect ke Mi Band 8 kamu</li>
                            <li>Masuk ke <strong>Settings → Heart Rate</strong></li>
                            <li>Aktifkan <strong>"Broadcast as GATT Sensor / HR Broadcast"</strong></li>
                            <li>Buka VITALY di Chrome/Edge → Klik <strong>"Sync Mi Band 8"</strong></li>
                        </ol>
                    </div>
                </div>
                <button @click="showGuide = false" class="text-blue-400 hover:text-blue-600 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Tech Log Terminal (tampil saat sync) -->
        <div v-if="techLogs.length > 0 || isSyncing" class="bg-gray-950 rounded-2xl p-4 mb-4 font-mono text-xs overflow-hidden animate-fade-in-down">
            <div class="flex items-center gap-2 mb-3 pb-2 border-b border-gray-800">
                <div class="flex gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                    <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                    <span class="w-3 h-3 rounded-full bg-green-500"></span>
                </div>
                <span class="text-gray-400 text-[10px] tracking-wider">VITALY IoMT — BLE Data Acquisition Log</span>
                <span v-if="isSyncing" class="ml-auto flex items-center gap-1 text-green-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                    LIVE
                </span>
                <span v-else class="ml-auto text-gray-600 text-[10px]">DONE</span>
            </div>
            <div ref="logContainer" class="space-y-1 max-h-40 overflow-y-auto">
                <p v-for="(log, i) in techLogs" :key="i"
                    class="text-green-400 leading-relaxed"
                    :class="log.includes('✗') || log.includes('⚠️') ? 'text-yellow-400' : log.includes('✅') ? 'text-emerald-400' : 'text-green-400'">
                    <span class="text-gray-600">{{ String(i).padStart(2,'0') }} &gt; </span>{{ log }}
                </p>
                <span v-if="isSyncing" class="inline-block w-2 h-3 bg-green-400 animate-pulse"></span>
            </div>
        </div>

        <!-- Sync success banner -->
        <div v-if="syncSuccess" class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 mb-4 text-sm text-emerald-700 animate-fade-in-down">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            <div>
                <span class="font-semibold">Data berhasil disinkronkan!</span>
                <span class="text-emerald-600 ml-1 text-xs">{{ syncDeviceName }}</span>
                <span v-if="!syncIsReal" class="ml-2 text-[10px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">Mode Simulasi</span>
                <span v-else class="ml-2 text-[10px] bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">📡 Real Device</span>
            </div>
        </div>

        <!-- IoMT tip -->
        <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-5 text-xs text-blue-700">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>
                <strong>Tips:</strong> Klik <strong>"Sync Mi Band 8"</strong> untuk ambil data otomatis dari Mi Band via Notify for Xiaomi.
                Belum setup?
                <button @click="showGuide = true" class="text-blue-600 underline font-semibold">Lihat panduan</button>.
            </span>
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
import { ref, nextTick } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { syncFromNotify, syncVitalData } from '@/Services/BluetoothService';

const isSyncing    = ref(false);
const syncMode     = ref('');       // 'notify' | 'ble'
const syncSuccess  = ref(false);
const syncDeviceName = ref('');
const syncIsReal   = ref(false);
const showGuide    = ref(false);
const techLogs     = ref([]);
const logContainer = ref(null);

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

// Append log + auto-scroll ke bawah
const addLog = async (msg) => {
    techLogs.value.push(msg);
    await nextTick();
    if (logContainer.value) {
        logContainer.value.scrollTop = logContainer.value.scrollHeight;
    }
};

const applyData = (data) => {
    if (!data) return;
    if (data.heart_rate)        { form.heart_rate        = data.heart_rate;        autoFilled.value.heart_rate        = true; }
    if (data.systolic)          { form.systolic          = data.systolic;          autoFilled.value.systolic          = true; }
    if (data.diastolic)         { form.diastolic         = data.diastolic;         autoFilled.value.diastolic         = true; }
    if (data.oxygen_saturation) { form.oxygen_saturation = data.oxygen_saturation; autoFilled.value.oxygen_saturation = true; }
    form.source      = 'iomt';
    syncSuccess.value   = true;
    syncDeviceName.value = data.deviceName || 'IoMT Device';
    syncIsReal.value     = !!data.isReal;
};

// ── Sync via Notify for Xiaomi (Mode Utama) ──
const doSyncNotify = async () => {
    isSyncing.value  = true;
    syncMode.value   = 'notify';
    syncSuccess.value = false;
    techLogs.value   = [];

    try {
        const data = await syncFromNotify(addLog);
        applyData(data);
    } catch (err) {
        await addLog(`[VITALY] ✗ Fatal: ${err.message}`);
    } finally {
        isSyncing.value = false;
        syncMode.value  = '';
    }
};

// ── Sync via Generic BLE (Fallback) ──
const doSync = async () => {
    isSyncing.value  = true;
    syncMode.value   = 'ble';
    syncSuccess.value = false;
    techLogs.value   = [];

    try {
        const data = await syncVitalData((msg) => addLog(msg));
        applyData(data);
    } catch (err) {
        await addLog(`[VITALY] ✗ Fatal: ${err.message}`);
    } finally {
        isSyncing.value = false;
        syncMode.value  = '';
    }
};

const submit = () => {
    form.post('/input-mandiri', {
        onSuccess: () => form.reset(),
    });
};
</script>
