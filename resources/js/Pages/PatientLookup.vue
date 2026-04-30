<template>
    <div class="min-h-screen bg-gradient-to-br from-primary to-primary-dark flex items-start sm:items-center justify-center p-4 py-6 sm:py-4 font-poppins">
        <Head title="Masuk - Cek Data Kesehatan" />

        <!-- Background decoration -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 blur-3xl rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-black/10 blur-2xl rounded-full translate-y-1/2 -translate-x-1/4"></div>
        </div>

        <div class="relative w-full max-w-sm">
            <!-- Logo -->
            <div class="text-center mb-6 sm:mb-8">
                <img src="/images/logo.png" alt="VITALY" class="h-10 w-auto mx-auto brightness-0 invert" />
                <p class="text-white/70 text-sm mt-2">Sistem Monitor Kesehatan VITALY</p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-5 sm:p-7">
                <h2 class="text-lg font-bold text-gray-800 mb-0.5">Akses Data Kesehatan</h2>
                <p class="text-xs text-gray-500 mb-5">Masukkan NIK (16 digit) yang kamu daftarkan</p>

                <!-- Google welcome banner -->
                <div v-if="googleName" class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-green-100">
                            <svg class="h-4 w-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold leading-5">Login Google berhasil</p>
                            <p class="mt-0.5 text-sm leading-5 text-green-700">
                                Halo, <span class="font-semibold break-words">{{ googleName }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Error -->
                <div v-if="form.errors.nik"
                    class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
                    {{ form.errors.nik }}
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">NIK (16 digit)</label>
                        <input
                            v-model="form.nik"
                            type="text"
                            inputmode="numeric"
                            maxlength="16"
                            placeholder="3201xxxxxxxxxxxxxxx"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition tracking-widest"
                            :class="{ 'border-red-400': form.errors.nik }"
                            autocomplete="off"
                        />
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing || form.nik.length !== 16"
                        class="w-full bg-primary text-white font-semibold py-2.5 rounded-xl text-sm hover:bg-primary-dark transition shadow-lg shadow-primary/30 disabled:opacity-50 disabled:cursor-not-allowed mt-2">
                        <span v-if="form.processing">Memeriksa...</span>
                        <span v-else>Lihat Data Saya</span>
                    </button>
                </form>

                <!-- divider -->
                <div class="flex items-center gap-3 my-5">
                    <div class="flex-1 h-px bg-gray-100"></div>
                    <span class="text-xs text-gray-400">atau</span>
                    <div class="flex-1 h-px bg-gray-100"></div>
                </div>

                <!-- Daftar Mandiri -->
                <a href="/auth/google/register"
                    class="w-full flex items-center justify-center gap-2.5 bg-primary text-white text-sm font-semibold py-2.5 rounded-xl hover:bg-primary-dark transition shadow-lg shadow-primary/30 mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Belum terdaftar? Daftar Mandiri
                </a>

                <Link href="/login"
                    class="w-full flex items-center justify-center gap-2 border border-gray-200 text-gray-600 text-sm font-medium py-2.5 rounded-xl hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Login sebagai Health Agent / Admin
                </Link>
            </div>

            <p class="text-center text-white/50 text-xs mt-6">
                VITALY &copy; {{ new Date().getFullYear() }} — Sistem Monitor Kesehatan
            </p>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    googleName: { type: String, default: null },
});

const form = useForm({
    nik: '',
});

const submit = () => form.post('/masuk');
</script>
