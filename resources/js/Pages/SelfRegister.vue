<template>
    <div class="min-h-screen bg-gradient-to-br from-primary to-primary-dark flex items-start sm:items-center justify-center p-4 py-6 font-poppins">
        <Head title="Daftar Mandiri - VITALY" />

        <!-- Background decoration -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 blur-3xl rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-black/10 blur-2xl rounded-full translate-y-1/2 -translate-x-1/4"></div>
        </div>

        <div class="relative w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-5">
                <img src="/images/logo.png" alt="VITALY" class="h-10 w-auto mx-auto brightness-0 invert" />
                <p class="text-white/70 text-sm mt-2">Daftar Mandiri · Sistem Monitor Kesehatan</p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-5 sm:p-7">

                <!-- Step indicator -->
                <div class="flex items-center gap-2 mb-5">
                    <div v-for="s in 3" :key="s"
                        :class="['flex-1 h-1.5 rounded-full transition-all duration-500',
                            s <= step ? 'bg-primary' : 'bg-gray-100']">
                    </div>
                </div>

                <!-- STEP 1: Verifikasi Google -->
                <div v-if="step === 1">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-primary/10 rounded-2xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-800">Langkah 1 dari 3</h2>
                            <p class="text-xs text-gray-500">Verifikasi Identitas Google</p>
                        </div>
                    </div>

                    <!-- Already verified -->
                    <div v-if="googleEmail" class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-emerald-800">Google Terverifikasi ✓</p>
                                <p class="text-xs text-emerald-600">{{ googleName }} · {{ googleEmail }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- NIK already registered warning -->
                    <div v-if="$page.props.errors?.nik"
                        class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
                        {{ $page.props.errors.nik }}
                        <Link href="/masuk" class="underline font-semibold ml-1">Masuk di sini</Link>
                    </div>

                    <div v-if="!googleEmail">
                        <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                            Untuk mendaftar, kamu perlu memverifikasi identitas terlebih dahulu menggunakan akun Google kamu.
                        </p>
                        <a href="/auth/google/register"
                            class="w-full flex items-center justify-center gap-3 border border-gray-200 text-gray-700 text-sm font-semibold py-3 rounded-2xl hover:bg-gray-50 hover:border-primary/30 transition shadow-sm">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Lanjutkan dengan Google
                        </a>
                    </div>
                    <div v-else>
                        <button @click="step = 2"
                            class="w-full bg-primary text-white font-semibold py-3 rounded-2xl text-sm hover:bg-primary-dark transition shadow-lg shadow-primary/30">
                            Lanjut Isi Data Diri →
                        </button>
                    </div>

                    <Link href="/masuk"
                        class="mt-4 w-full flex items-center justify-center gap-1.5 text-xs text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Sudah punya akun? Masuk di sini
                    </Link>
                </div>

                <!-- STEP 2: Form Data Diri -->
                <div v-if="step === 2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-primary/10 rounded-2xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-800">Langkah 2 dari 3</h2>
                            <p class="text-xs text-gray-500">Data Diri Kamu</p>
                        </div>
                    </div>

                    <form @submit.prevent="submitRegister" class="space-y-3">
                        <!-- NIK -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">NIK <span class="text-red-500">*</span></label>
                            <input v-model="form.nik" type="text" inputmode="numeric" maxlength="16"
                                placeholder="16 digit NIK sesuai KTP"
                                class="w-full px-3 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition tracking-widest"
                                :class="form.errors.nik ? 'border-red-400 bg-red-50' : 'border-gray-200'" />
                            <p v-if="form.errors.nik" class="text-red-500 text-xs mt-1">{{ form.errors.nik }}</p>
                        </div>

                        <!-- Nama -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input v-model="form.name" type="text"
                                placeholder="Sesuai KTP"
                                class="w-full px-3 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                                :class="form.errors.name ? 'border-red-400 bg-red-50' : 'border-gray-200'" />
                            <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                        </div>

                        <!-- Tanggal Lahir + Gender -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Tgl Lahir <span class="text-red-500">*</span></label>
                                <input v-model="form.date_of_birth" type="date"
                                    class="w-full px-3 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                                    :class="form.errors.date_of_birth ? 'border-red-400 bg-red-50' : 'border-gray-200'" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Jenis Kelamin</label>
                                <select v-model="form.gender"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white">
                                    <option value="">Pilih</option>
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <!-- No HP -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">No. HP / WhatsApp</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-mono select-none">+62</span>
                                <input v-model="phoneRaw" @blur="normalizePhone" type="tel"
                                    placeholder="8xxxxxxxxxx"
                                    class="w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition font-mono" />
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Boleh format 08xx, 628xx, atau +628xx — akan dikonversi otomatis.</p>
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat Lengkap</label>
                            <textarea v-model="form.address" rows="2"
                                placeholder="Jl. ..."
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition resize-none"></textarea>
                        </div>

                        <div class="flex gap-2 pt-1">
                            <button type="button" @click="step = 1"
                                class="flex-1 border border-gray-200 text-gray-600 font-semibold py-2.5 rounded-xl text-sm hover:bg-gray-50 transition">
                                ← Kembali
                            </button>
                            <button type="submit" :disabled="form.processing"
                                class="flex-[2] bg-primary text-white font-semibold py-2.5 rounded-xl text-sm hover:bg-primary-dark transition shadow-lg shadow-primary/30 disabled:opacity-50">
                                <span v-if="form.processing">Menyimpan...</span>
                                <span v-else>Simpan & Lanjut →</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- STEP 3: Pairing Smartwatch -->
                <div v-if="step === 3">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-emerald-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-800">Akun Berhasil Dibuat! 🎉</h2>
                            <p class="text-xs text-gray-500">Langkah 3 dari 3 — Pairing Smartwatch (Opsional)</p>
                        </div>
                    </div>

                    <!-- Opsional badge -->
                    <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-4 flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-blue-700 leading-relaxed">
                            <span class="font-semibold">Opsional tapi direkomendasikan!</span>
                            Tautkan smartwatch kamu sekarang agar data vital (detak jantung, tekanan darah) bisa dipantau secara real-time oleh VITALY.
                        </p>
                    </div>

                    <!-- Pairing button -->
                    <div v-if="!devicePaired">
                        <button @click="doPairing"
                            :disabled="isPairing"
                            class="w-full flex items-center justify-center gap-3 bg-gradient-to-r from-primary to-primary-dark text-white font-semibold py-3 rounded-2xl text-sm hover:opacity-90 transition shadow-lg shadow-primary/30 disabled:opacity-60 mb-3">
                            <svg v-if="!isPairing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <svg v-else class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span>{{ isPairing ? btStatus || 'Mencari perangkat...' : 'Hubungkan Smartwatch (Mi Band 8)' }}</span>
                        </button>
                        <button @click="goToDashboard"
                            class="w-full text-center text-xs text-gray-400 hover:text-gray-600 transition py-1">
                            Lewati, lanjut ke Dashboard →
                        </button>
                    </div>

                    <!-- Paired success -->
                    <div v-else>
                        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex items-center gap-3 mb-4">
                            <div class="w-9 h-9 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-emerald-800">Smartwatch Terhubung! ✓</p>
                                <p class="text-xs text-emerald-600 mt-0.5 font-mono">{{ pairedDeviceId }}</p>
                            </div>
                        </div>
                        <button @click="goToDashboard"
                            class="w-full bg-primary text-white font-semibold py-3 rounded-2xl text-sm hover:bg-primary-dark transition shadow-lg shadow-primary/30">
                            Masuk ke Dashboard →
                        </button>
                    </div>
                </div>
            </div>

            <p class="text-center text-white/50 text-xs mt-6">
                VITALY &copy; {{ new Date().getFullYear() }} — Sistem Monitor Kesehatan
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { pairDevice } from '@/Services/BluetoothService';

const props = defineProps({
    googleName:  { type: String, default: null },
    googleEmail: { type: String, default: null },
    patientId:   { type: Number, default: null },
});

const step          = ref(props.googleEmail ? 2 : 1);
const isPairing     = ref(false);
const devicePaired  = ref(false);
const pairedDeviceId = ref('');
const btStatus      = ref('');
const phoneRaw      = ref('');

// Normalize phone: 08xx / +628xx / 628xx → 628xx
const normalizePhone = () => {
    let val = phoneRaw.value.trim().replace(/\s+/g, '');
    if (!val) { form.phone = ''; return; }
    // Strip leading +
    if (val.startsWith('+')) val = val.slice(1);
    // 0812... → 62812...
    if (val.startsWith('0')) val = '62' + val.slice(1);
    // Already 62... → keep
    phoneRaw.value = val;
    form.phone = val;
};

const form = useForm({
    nik:           '',
    name:          props.googleName || '',
    date_of_birth: '',
    gender:        '',
    phone:         '',
    address:       '',
});

const submitRegister = () => {
    form.post('/daftar', {
        onSuccess: () => {
            step.value = 3;
        },
    });
};

// ── Pairing Smartwatch ──────────────────────────────────────────────
const doPairing = async () => {
    isPairing.value = true;
    const result = await pairDevice((msg) => { btStatus.value = msg; });
    if (result) {
        pairedDeviceId.value = result.deviceId;
        devicePaired.value   = true;
        // Simpan device ID ke patient record
        router.post('/daftar/device', { device_id: result.deviceId }, { preserveState: true });
    }
    isPairing.value = false;
    btStatus.value  = '';
};

const goToDashboard = () => router.visit('/masuk');
</script>
