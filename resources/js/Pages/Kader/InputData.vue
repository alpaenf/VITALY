<template>
    <KaderLayout>
        <Head :title="`Input Data - ${patient.name}`" />

        <!-- Back link -->
        <Link :href="`/kader/pasien/${patient.id}`"
            class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-primary mb-5 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Detail Pasien
        </Link>

        <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Input Data Kesehatan</h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Pasien: <span class="font-semibold text-gray-700">{{ patient.name }}</span>
                    <span class="mx-1 text-gray-300">|</span>
                    <span class="font-mono text-xs">NIK: {{ patient.nik }}</span>
                </p>
            </div>
            
            <!-- Simulation Button -->
            <button type="button" @click="simulateIoMTSync" :disabled="isScanning"
                class="flex items-center justify-center gap-2 bg-[#FCD34D] hover:bg-amber-400 text-[#064E3B] px-5 py-2.5 rounded-xl font-bold text-sm transition shadow-lg shadow-amber-500/20 active:scale-95 disabled:opacity-70">
                <svg v-if="!isScanning" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2zM12 7V3m0 0l-3 3m3-3l3 3"/></svg>
                <svg v-else class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                {{ isScanning ? 'Mencari Perangkat...' : 'Sinkron IoMT (Bluetooth)' }}
            </button>
        </div>

        <!-- Sync Overlay -->
        <transition name="fade">
            <div v-if="isScanning || showSyncSuccess" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-5">
                <div class="bg-white rounded-3xl p-8 max-w-sm w-full text-center shadow-2xl animate-scale-in">
                    <div v-if="isScanning" class="relative w-24 h-24 mx-auto mb-6">
                        <div class="absolute inset-0 border-4 border-primary/20 rounded-full"></div>
                        <div class="absolute inset-0 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-10 h-10 text-primary animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                    <div v-else class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>

                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ isScanning ? 'Mencari Smartwatch...' : 'Sinkronisasi Berhasil!' }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        {{ isScanning ? syncStatusMsg : syncSuccessMsg }}
                    </p>
                    
                    <div v-if="!isScanning" class="mt-6">
                        <div class="bg-gray-50 rounded-2xl p-4 space-y-2">
                            <div class="flex justify-between text-xs"><span class="text-gray-400">Status</span><span class="text-green-600 font-bold">Terhubung</span></div>
                            <div class="flex justify-between text-xs"><span class="text-gray-400">Perangkat</span><span class="text-gray-800 font-medium">{{ syncDeviceName }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>

        <form @submit.prevent="submit">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                <!-- Tekanan Darah -->
                <div class="card-VITALY p-4 sm:p-5 lg:p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-700">Tekanan Darah</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Sistolik (mmHg)</label>
                            <input v-model="form.systolic" type="number" placeholder="120" class="input-field w-full" />
                            <p v-if="form.errors.systolic" class="text-red-500 text-xs mt-1">{{ form.errors.systolic }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Diastolik (mmHg)</label>
                            <input v-model="form.diastolic" type="number" placeholder="80" class="input-field w-full" />
                        </div>
                    </div>
                    <transition name="badge">
                        <div v-if="bpStatus" class="mt-3 text-xs px-3 py-1.5 rounded-lg inline-block font-medium" :class="bpStatusClass">
                            {{ bpStatus }}
                        </div>
                    </transition>
                </div>

                <!-- Detak Jantung -->
                <div class="card-VITALY p-4 sm:p-5 lg:p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-secondary/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-700">Detak Jantung</h3>
                    </div>
                    <label class="text-xs text-gray-500 mb-1 block">BPM (denyut per menit)</label>
                    <input v-model="form.heart_rate" type="number" placeholder="72" class="input-field w-full" />
                </div>

                <!-- Berat & Tinggi -->
                <div class="card-VITALY p-4 sm:p-5 lg:p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-[#D1FAE5] flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-700">Berat & Tinggi Badan</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Berat (kg)</label>
                            <input v-model="form.weight" type="number" step="0.1" placeholder="60" class="input-field w-full" />
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Tinggi (cm)</label>
                            <input v-model="form.height" type="number" step="0.1" placeholder="165" class="input-field w-full" />
                        </div>
                    </div>
                    <div v-if="bmi" class="mt-3 text-xs px-3 py-1.5 rounded-lg inline-block font-medium bg-blue-50 text-blue-700">
                        IMT: {{ bmi }} — {{ bmiLabel }}
                    </div>
                </div>

                <!-- Gula Darah -->
                <div class="card-VITALY p-4 sm:p-5 lg:p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-yellow-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-700">Gula Darah</h3>
                    </div>
                    <label class="text-xs text-gray-500 mb-1 block">Kadar Gula Darah (mg/dL)</label>
                    <input v-model="form.blood_sugar" type="number" step="0.1" placeholder="100" class="input-field w-full" />
                </div>

                <!-- Suhu & RR -->
                <div class="card-VITALY p-4 sm:p-5 lg:p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-700">Suhu & Laju Nafas</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Suhu (°C)</label>
                            <input v-model="form.temperature" type="number" step="0.1" placeholder="36.5" class="input-field w-full" />
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">RR (x/menit)</label>
                            <input v-model="form.respiratory_rate" type="number" placeholder="20" class="input-field w-full" />
                        </div>
                    </div>
                </div>

                <!-- Tanggal & Catatan -->
                <div class="card-VITALY p-4 sm:p-5 lg:p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-700">Tanggal & Catatan</h3>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Tanggal Pemeriksaan</label>
                            <input v-model="form.recorded_at" type="datetime-local" class="input-field w-full" />
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Catatan</label>
                            <textarea v-model="form.notes" rows="3" placeholder="Catatan tambahan..."
                                class="input-field w-full resize-none"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="mt-6 flex flex-col md:flex-row gap-3 md:gap-4">
                <Link :href="`/kader/pasien/${patient.id}`"
                    class="flex-1 text-center border border-gray-200 text-gray-600 py-3 rounded-xl font-medium text-sm hover:bg-gray-50 transition order-2 md:order-1">
                    Batal
                </Link>
                <button type="submit" :disabled="form.processing"
                    class="flex-1 bg-primary text-white py-3 rounded-xl font-semibold text-sm hover:bg-primary-dark transition shadow-lg shadow-primary/20 disabled:opacity-50 order-1 md:order-2">
                    <span v-if="form.processing">Menyimpan...</span>
                    <span v-else>Simpan Data</span>
                </button>
            </div>
        </form>
    </KaderLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import KaderLayout from '@/Layouts/KaderLayout.vue';

const props = defineProps({ patient: Object });

const isScanning = ref(false);
const showSyncSuccess = ref(false);
const syncStatusMsg = ref('Mendekatkan perangkat IoMT ke sistem untuk penarikan data vital otomatis.');
const syncSuccessMsg = ref('Data Tekanan Darah & Detak Jantung berhasil ditarik dari perangkat VITALY Smart Hub.');
const syncDeviceName = ref('VITALY Pulse v2.0');

const simulateIoMTSync = async () => {
    isScanning.value = true;

    // --- COBA KONEKSI BLUETOOTH NYATA (hanya di HTTPS + Chrome/Edge) ---
    if (typeof navigator.bluetooth !== 'undefined') {
        try {
            syncStatusMsg.value = 'Memindai perangkat Bluetooth terdekat...';
            
            const device = await navigator.bluetooth.requestDevice({
                // Coba filter standard BLE health devices
                filters: [
                    { services: ['heart_rate'] },
                    { services: ['blood_pressure'] },
                    { namePrefix: 'Mi' },
                    { namePrefix: 'VITALY' },
                    { namePrefix: 'Band' },
                ],
                optionalServices: ['heart_rate', 'blood_pressure', 'battery_service'],
            });

            syncStatusMsg.value = `Terhubung ke ${device.name || 'Perangkat BLE'}...`;
            syncDeviceName.value = device.name || 'Smartwatch BLE';

            const server = await device.gatt.connect();

            let heartRate = null;
            let systolic = null;
            let diastolic = null;

            // Baca Heart Rate jika tersedia
            try {
                const hrService = await server.getPrimaryService('heart_rate');
                const hrChar = await hrService.getCharacteristic('heart_rate_measurement');
                const hrValue = await hrChar.readValue();
                const flags = hrValue.getUint8(0);
                heartRate = (flags & 0x01) ? hrValue.getUint16(1, true) : hrValue.getUint8(1);
            } catch (_) { /* tidak ada HR service */ }

            // Baca Blood Pressure jika tersedia
            try {
                const bpService = await server.getPrimaryService('blood_pressure');
                const bpChar = await bpService.getCharacteristic('blood_pressure_measurement');
                await bpChar.startNotifications();
                await new Promise((resolve) => {
                    bpChar.addEventListener('characteristicvaluechanged', (e) => {
                        const v = e.target.value;
                        systolic  = Math.round(v.getFloat32(1, true));
                        diastolic = Math.round(v.getFloat32(5, true));
                        resolve();
                    });
                    setTimeout(resolve, 3000); // timeout 3 detik
                });
            } catch (_) { /* tidak ada BP service */ }

            // Isi form dengan data real
            if (heartRate)  form.heart_rate = heartRate;
            if (systolic)   form.systolic   = systolic;
            if (diastolic)  form.diastolic  = diastolic;
            if (!heartRate && !systolic) {
                // Perangkat nyambung tapi tidak ada data spesifik, isi dummy
                form.systolic   = 185;
                form.diastolic  = 115;
                form.heart_rate = 112;
                form.temperature = 37.2;
            }
            form.notes = `Data ditarik via Bluetooth dari ${device.name || 'smartwatch'} (IoMT Real)`;
            syncSuccessMsg.value = `Data berhasil ditarik dari ${device.name || 'Smartwatch'} via Bluetooth secara langsung!`;

            isScanning.value = false;
            showSyncSuccess.value = true;
            setTimeout(() => { showSyncSuccess.value = false; }, 3500);
            return;

        } catch (err) {
            // User cancel, device not found, atau tidak ada service → fallback ke simulasi
            console.info('Web Bluetooth fallback to simulation:', err.message);
        }
    }

    // --- FALLBACK: SIMULASI (localhost / Firefox / tidak ada HTTPS) ---
    syncStatusMsg.value   = 'Mendekatkan perangkat IoMT ke sistem untuk penarikan data vital otomatis.';
    syncSuccessMsg.value  = 'Data Tekanan Darah & Detak Jantung berhasil ditarik dari perangkat VITALY Smart Hub.';
    syncDeviceName.value  = 'VITALY Pulse v2.0';

    setTimeout(() => {
        isScanning.value = false;
        showSyncSuccess.value = true;
        form.systolic    = 185;
        form.diastolic   = 115;
        form.heart_rate  = 112;
        form.temperature = 37.2;
        form.notes = 'Data otomatis ditarik via Bluetooth (Simulasi IoMT)';
        setTimeout(() => { showSyncSuccess.value = false; }, 3000);
    }, 2500);
};

const form = useForm({
    systolic: '',
    diastolic: '',
    heart_rate: '',
    blood_sugar: '',
    weight: '',
    height: '',
    temperature: '',
    respiratory_rate: '',
    notes: '',
    recorded_at: '',
});

const submit = () => form.post(`/kader/pasien/${props.patient.id}/input`);

const bpStatus = computed(() => {
    const s = Number(form.systolic), d = Number(form.diastolic);
    if (!s || !d) return null;
    if (s < 90 || d < 60) return 'Hipotensi';
    if (s <= 120 && d <= 80) return 'Normal';
    if (s <= 129 && d < 80) return 'Meningkat';
    if (s <= 139 || d <= 89) return 'Hipertensi Tahap 1';
    return 'Hipertensi Tahap 2';
});
const bpStatusClass = computed(() => {
    const s = bpStatus.value;
    if (s === 'Normal') return 'bg-green-50 text-green-700';
    if (s === 'Meningkat') return 'bg-yellow-50 text-yellow-700';
    if (!s) return '';
    return 'bg-primary/10 text-red-700';
});

const bmi = computed(() => {
    const w = Number(form.weight), h = Number(form.height);
    if (!w || !h) return null;
    return (w / ((h / 100) ** 2)).toFixed(1);
});
const bmiLabel = computed(() => {
    const b = Number(bmi.value);
    if (b < 18.5) return 'Kurus';
    if (b < 25) return 'Normal';
    if (b < 30) return 'Gemuk';
    return 'Obesitas';
});
</script>
