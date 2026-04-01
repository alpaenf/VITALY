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

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Input Data Kesehatan</h1>
            <p class="text-sm text-gray-500 mt-0.5">
                Pasien: <span class="font-semibold text-gray-700">{{ patient.name }}</span>
                <span class="mx-1 text-gray-300">|</span>
                <span class="font-mono text-xs">NIK: {{ patient.nik }}</span>
            </p>
        </div>

        <form @submit.prevent="submit">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                <!-- Tekanan Darah -->
                <div class="card-Healtiva p-4 sm:p-5 lg:p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
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
                <div class="card-Healtiva p-4 sm:p-5 lg:p-6">
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
                <div class="card-Healtiva p-4 sm:p-5 lg:p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-[#EFDBDC] flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#B74443]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="card-Healtiva p-4 sm:p-5 lg:p-6">
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
                <div class="card-Healtiva p-4 sm:p-5 lg:p-6">
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
                <div class="card-Healtiva p-4 sm:p-5 lg:p-6">
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
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import KaderLayout from '@/Layouts/KaderLayout.vue';

const props = defineProps({ patient: Object });

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
    return 'bg-red-50 text-red-700';
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
