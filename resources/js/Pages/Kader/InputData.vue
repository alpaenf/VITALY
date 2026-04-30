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
            
            <!-- IoMT Sync Button -->
            <div class="flex flex-col items-end gap-1">
                <button type="button" @click="doSync" :disabled="isScanning"
                    class="flex items-center justify-center gap-2 bg-[#FCD34D] hover:bg-amber-400 text-[#064E3B] px-5 py-2.5 rounded-xl font-bold text-sm transition shadow-lg shadow-amber-500/20 active:scale-95 disabled:opacity-70">
                    <svg v-if="!isScanning" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                    <svg v-else class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    {{ isScanning ? 'Menghubungkan...' : 'Hubungkan Smartwatch' }}
                </button>
                <!-- Live status message -->
                <p v-if="btStatus" class="text-[10px] text-gray-400 max-w-[220px] text-right leading-tight">{{ btStatus }}</p>
            </div>
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
                        <span v-if="autoFilled.systolic || autoFilled.diastolic" class="ml-2 text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Auto Sync</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Sistolik (mmHg)</label>
                            <input v-model="form.systolic" @input="clearAuto('systolic')" type="number" placeholder="120" :class="['input-field w-full', inputAutoClass('systolic')]" />
                            <p v-if="form.errors.systolic" class="text-red-500 text-xs mt-1">{{ form.errors.systolic }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Diastolik (mmHg)</label>
                            <input v-model="form.diastolic" @input="clearAuto('diastolic')" type="number" placeholder="80" :class="['input-field w-full', inputAutoClass('diastolic')]" />
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
                        <span v-if="autoFilled.heart_rate" class="ml-2 text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Auto Sync</span>
                    </div>
                    <label class="text-xs text-gray-500 mb-1 block">BPM (denyut per menit)</label>
                    <input v-model="form.heart_rate" @input="clearAuto('heart_rate')" type="number" placeholder="72" :class="['input-field w-full', inputAutoClass('heart_rate')]" />
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
                        <span v-if="autoFilled.temperature" class="ml-2 text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Auto Sync</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Suhu (°C)</label>
                            <input v-model="form.temperature" @input="clearAuto('temperature')" type="number" step="0.1" placeholder="36.5" :class="['input-field w-full', inputAutoClass('temperature')]" />
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

            <!-- Konfirmasi: hanya muncul kalau data diisi MANUAL (bukan sync) -->
            <transition name="fade">
                <div v-if="!isSynced" class="mt-6 bg-amber-50 border border-amber-100 rounded-xl p-4">
                    <label class="flex items-start gap-2.5 cursor-pointer">
                        <input v-model="manualConfirmed" type="checkbox" class="mt-0.5 accent-amber-500" />
                        <div>
                            <span class="text-xs font-medium text-amber-800">Konfirmasi Data Manual</span>
                            <p class="text-[11px] text-amber-600 mt-0.5 leading-relaxed">Data di atas diukur menggunakan alat yang sesuai dan hasilnya akurat.</p>
                        </div>
                    </label>
                    <p v-if="manualError" class="text-red-500 text-xs mt-2 ml-5">{{ manualError }}</p>
                </div>

                <!-- Jika data dari smartwatch: tampilkan badge verifikasi + tips -->
                <div v-else class="mt-6 space-y-2">
                    <!-- Badge verified -->
                    <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-emerald-800">Data Terverifikasi Otomatis</p>
                            <p class="text-[11px] text-emerald-600 mt-0.5">Data ditarik langsung dari sensor smartwatch — tidak perlu konfirmasi manual.</p>
                        </div>
                    </div>
                    <!-- Tips untuk hasil maksimal -->
                    <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-[11px] text-blue-600 leading-relaxed">
                            <span class="font-semibold">Tips hasil maksimal:</span>
                            Lengkapi juga data <span class="font-medium">Gula Darah</span>, <span class="font-medium">Berat & Tinggi Badan</span>, dan <span class="font-medium">Laju Nafas</span> secara manual agar analisis AI lebih akurat dan menyeluruh.
                        </p>
                    </div>
                </div>
            </transition>

            <!-- Submit -->
            <div class="mt-4 flex flex-col md:flex-row gap-3 md:gap-4">
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
import { syncVitalData } from '@/Services/BluetoothService';

const props = defineProps({ patient: Object });

const isScanning      = ref(false);
const showSyncSuccess = ref(false);
const btStatus        = ref('');
const syncSuccessMsg  = ref('');
const syncDeviceName  = ref('');
const isSynced        = ref(false);  // true kalau data dari smartwatch
const manualConfirmed = ref(false);
const manualError     = ref('');
const autoFilled      = ref({ systolic: false, diastolic: false, heart_rate: false, temperature: false });

const inputAutoClass = (field) =>
    autoFilled.value[field] ? 'ring-2 ring-emerald-300 bg-emerald-50/30' : '';

const markAuto  = (fields) => fields.forEach(f => (autoFilled.value[f] = true));


// ── Submit form ──────────────────────────────────────────────
const submit = () => {
    // Kalau data dari smartwatch → skip konfirmasi
    if (!isSynced.value && !manualConfirmed.value) {
        manualError.value = 'Konfirmasi pengukuran manual wajib diisi.';
        return;
    }
    manualError.value = '';
    form.post(`/kader/pasien/${props.patient.id}/input`);
};

// ── Sync dari Smartwatch via BluetoothService ────────────────
const doSync = async () => {
    isScanning.value      = true;
    btStatus.value        = '';
    showSyncSuccess.value = false;

    const data = await syncVitalData((msg) => {
        btStatus.value = msg; // update status live
    });

    // Isi form dengan hasil data
    if (data.heart_rate)  { form.heart_rate  = data.heart_rate;  }
    if (data.systolic)    { form.systolic    = data.systolic;    }
    if (data.diastolic)   { form.diastolic   = data.diastolic;   }
    if (data.temperature) { form.temperature = data.temperature; }

    markAuto(['heart_rate', 'systolic', 'diastolic', 'temperature']);
    form.notes = data.isReal
        ? `Data ditarik via Bluetooth dari ${data.deviceName} (IoMT Real)`
        : `Data otomatis ditarik via Bluetooth (${data.deviceName})`;

    syncDeviceName.value = data.deviceName;
    syncSuccessMsg.value = data.isReal
        ? `✓ Data berhasil ditarik langsung dari ${data.deviceName}!`
        : `✓ Data berhasil diisi (mode demo — ${data.deviceName})`;

    isSynced.value        = true;  // tandai bahwa data dari smartwatch
    isScanning.value      = false;
    showSyncSuccess.value = true;
    setTimeout(() => { showSyncSuccess.value = false; btStatus.value = ''; }, 4000);
};

// Reset isSynced kalau user edit manual setelah sync
const clearAuto = (field) => {
    autoFilled.value[field] = false;
    // Kalau ada field yang diedit manual, anggap tidak lagi full-synced
    const anyAutoLeft = Object.values(autoFilled.value).some(v => v);
    if (!anyAutoLeft) isSynced.value = false;
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
