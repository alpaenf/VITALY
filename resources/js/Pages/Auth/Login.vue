<template>
    <!-- ① Role selection -->
    <template v-if="!selectedRole">
        <h2 class="text-lg font-bold text-gray-800 mb-0.5">Selamat Datang</h2>
        <p class="text-xs text-gray-500 mb-5">Silakan pilih akses Anda</p>

        <div class="space-y-3">
            <!-- Pasien card -->
            <button
                @click="selectedRole = 'tamu'"
                class="w-full flex items-center gap-4 p-4 rounded-2xl border-2 border-gray-100 hover:border-primary/40 hover:bg-primary/5 text-left transition-all group"
            >
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary flex-shrink-0 group-hover:bg-primary/20 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800">Saya Pasien / Tamu</p>
                    <p class="text-xs text-gray-500 mt-0.5">Lihat data kesehatan saya</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-primary transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <!-- Kader card -->
            <button
                @click="selectedRole = 'kader'"
                class="w-full flex items-center gap-4 p-4 rounded-2xl border-2 border-gray-100 hover:border-primary/40 hover:bg-primary/5 text-left transition-all group"
            >
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary flex-shrink-0 group-hover:bg-primary/20 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800">Saya Kader / Admin</p>
                    <p class="text-xs text-gray-500 mt-0.5">Akses portal manajemen</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-primary transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </template>

    <!-- ② Tamu / Pasien panel -->
    <template v-else-if="selectedRole === 'tamu'">
        <button @click="selectedRole = null" class="flex items-center gap-1.5 text-xs text-gray-500 hover:text-gray-700 mb-4 -mt-1 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </button>

        <h2 class="text-lg font-bold text-gray-800 mb-0.5">Akses Data Pasien</h2>
        <p class="text-xs text-gray-500 mb-5">Login dengan Google, lalu masukkan NIK Anda</p>

        <a href="/auth/google/tamu"
            class="flex items-center justify-center gap-3 w-full border border-gray-200 rounded-xl py-3
                   text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Masuk dengan Google
        </a>

        <div class="mt-4 bg-blue-50 rounded-xl px-4 py-3 text-xs text-blue-700 leading-relaxed">
            Setelah login, Anda akan diarahkan untuk memasukkan NIK guna mengakses data kesehatan Anda.
        </div>
    </template>

    <!-- ③ Kader / Admin panel -->
    <template v-else-if="selectedRole === 'kader'">
        <button @click="selectedRole = null" class="flex items-center gap-1.5 text-xs text-gray-500 hover:text-gray-700 mb-4 -mt-1 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </button>

        <h2 class="text-lg font-bold text-gray-800 mb-0.5">Login Kader / Admin</h2>
        <p class="text-xs text-gray-500 mb-4">Masuk ke portal manajemen HEALTIVA</p>

        <form @submit.prevent="submit" class="space-y-3">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                <input
                    v-model="form.email"
                    type="email"
                    placeholder="email@example.com"
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                    :class="{ 'border-red-400': form.errors.email }"
                    autocomplete="email"
                />
                <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <input
                        v-model="form.password"
                        :type="showPassword ? 'text' : 'password'"
                        placeholder="********"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition pr-10"
                        :class="{ 'border-red-400': form.errors.password }"
                        autocomplete="current-password"
                    />
                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input v-model="form.remember" type="checkbox" id="remember" class="rounded accent-primary">
                <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 rounded-xl
                       transition-all duration-200 disabled:opacity-70 shadow-md shadow-primary/30"
            >
                <span v-if="form.processing" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Masuk...
                </span>
                <span v-else>Masuk</span>
            </button>
        </form>

        <div class="flex items-center gap-3 my-4">
            <div class="flex-1 h-px bg-gray-200"></div>
            <span class="text-xs text-gray-400 font-medium">atau</span>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>

        <a href="/auth/google"
            class="flex items-center justify-center gap-3 w-full border border-gray-200 rounded-xl py-2.5
                   text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Masuk dengan Google
        </a>
    </template>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';

defineOptions({
    layout: (h, page) => h(AuthLayout, { mode: 'login' }, { default: () => page }),
});

const page = usePage();
const selectedRole = ref(null);
const showPassword = ref(false);

// If redirected back with errors (e.g. failed Google kader login), jump straight to kader panel
onMounted(() => {
    if (Object.keys(page.props.errors || {}).length > 0) {
        selectedRole.value = 'kader';
    }
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>
