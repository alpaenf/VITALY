<template>
    <div class="min-h-screen bg-gradient-to-br from-primary to-primary-dark flex font-poppins">

        <!-- LEFT PANEL (desktop only) -->
        <div class="hidden lg:flex flex-col justify-between w-1/2 p-12 relative overflow-hidden transition-transform duration-1000 ease-in-out z-10"
             :class="mode === 'register' ? 'translate-x-full' : 'translate-x-0'">
            <!-- Decorative circles -->
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-0 w-56 h-56 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute top-1/2 left-1/3 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>

            <div class="relative z-10 animate-fade-in-left">
                <div class="flex items-center mb-10">
                    <img src="/images/logo.png" alt="Healtiva" class="h-[120px] w-auto brightness-0 invert" />
                </div>

                <h2 class="text-3xl font-bold text-white leading-tight mb-3">Monitor Kesehatan<br><span class="text-white/70">Lebih Cerdas</span></h2>
                <p class="text-white/60 text-sm leading-relaxed">Pantau kondisi kesehatan Anda setiap hari dengan analisis AI yang akurat dan mudah dipahami.</p>

                <div class="mt-8 space-y-3">
                    <div v-for="(ft, i) in features" :key="i" class="flex items-center gap-3 animate-fade-in-left" :style="`animation-delay:${(i+1)*100}ms`">
                        <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-white/80 text-sm">{{ ft }}</span>
                    </div>
                </div>
            </div>

            <p class="relative z-10 text-white/40 text-xs">© 2026 Medix · Sistem Monitor Kesehatan</p>
        </div>

        <!-- RIGHT PANEL (auth card) -->
        <div class="flex-1 lg:flex-none lg:w-1/2 flex flex-col items-center justify-start lg:justify-center px-4 lg:px-16 pt-0 lg:pt-8 pb-8 transition-transform duration-1000 ease-in-out z-20 relative"
             :class="mode === 'register' ? 'lg:-translate-x-full' : 'lg:translate-x-0'">
             
            <!-- Mobile Background Decorations -->
            <div class="lg:hidden absolute inset-0 overflow-hidden pointer-events-none z-0">
                <!-- Large overlay circles -->
                <div class="absolute -top-10 -right-10 w-64 h-64 bg-white/5 rounded-full blur-2xl animate-pulse-soft"></div>
                <!-- SVG Plus Icon -->
                <svg class="absolute top-1/4 left-5 w-10 h-10 text-white/10 rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <!-- SVG Circle outline -->
                <svg class="absolute bottom-32 -right-5 w-24 h-24 text-white/10 animate-spin" style="animation-duration: 15s;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke-width="1.5" stroke-dasharray="4 4"></circle>
                </svg>
                <!-- Small dots -->
                <div class="absolute bottom-1/4 left-10 w-3 h-3 bg-white/20 rounded-full"></div>
                <div class="absolute top-1/2 right-12 w-2 h-2 bg-white/30 rounded-full"></div>
            </div>

            <!-- Mobile Logo -->
            <div class="lg:hidden flex flex-col items-center -mb-6 mt-4 relative z-10 animate-fade-in-down">
                <img src="/images/logo.png" alt="Healtiva" class="h-[120px] w-auto brightness-0 invert opacity-90" />
            </div>

            <!-- Card -->
            <div class="w-full max-w-[340px] sm:max-w-[380px] bg-white rounded-2xl shadow-2xl p-5 lg:p-6 animate-scale-in overflow-hidden relative z-10 flex flex-col">
                <Transition name="form-swap" mode="out-in">
                    <div :key="$page.component">
                        <slot />
                    </div>
                </Transition>
                
                <!-- Back to Home Link -->
                <div class="mt-5 pt-4 border-t border-gray-100 flex justify-center w-full">
                    <Link href="/" class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-primary transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Beranda
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    mode: {
        type: String,
        default: 'login'
    }
});

const features = [
    'Pantau tekanan darah, gula darah & BMI',
    'Analisis kondisi kesehatan berbasis AI',
    'Riwayat data lengkap & terstruktur',
];
</script>
