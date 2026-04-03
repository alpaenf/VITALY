<template>
    <Transition name="modal-fade">
        <div v-if="show"
            class="fixed inset-0 z-[999] flex items-end sm:items-center justify-center p-4"
            @click.self="$emit('cancel')">

            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="$emit('cancel')"></div>

            <!-- Modal Card -->
            <Transition name="modal-slide">
                <div v-if="show"
                    class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden z-10">

                    <!-- Top accent bar -->
                    <div class="h-1 w-full bg-gradient-to-r from-primary to-red-400"></div>

                    <div class="px-6 py-5">
                        <!-- Icon -->
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-base font-bold text-gray-900">Keluar dari Aplikasi</p>
                                <p class="text-xs text-gray-400 mt-0.5">Sesi Anda akan diakhiri</p>
                            </div>
                        </div>

                        <!-- Message -->
                        <p class="text-sm text-gray-600 leading-relaxed mb-5">
                            {{ message }}
                        </p>

                        <!-- Buttons -->
                        <div class="flex gap-3">
                            <button @click="$emit('cancel')"
                                class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 active:scale-95 transition-all duration-150">
                                Batal
                            </button>
                            <button @click="$emit('confirm')"
                                class="flex-1 px-4 py-2.5 rounded-xl bg-gradient-to-r from-primary to-red-500 text-white text-sm font-semibold shadow-md shadow-primary/30 hover:opacity-90 active:scale-95 transition-all duration-150">
                                Ya, Keluar
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
    </Transition>
</template>

<script setup>
defineProps({
    show: { type: Boolean, default: false },
    message: { type: String, default: 'Apakah Anda yakin ingin keluar dari aplikasi?' },
});
defineEmits(['confirm', 'cancel']);
</script>

<style scoped>
.modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.25s ease; }
.modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; }

.modal-slide-enter-active { transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
.modal-slide-leave-active { transition: all 0.2s ease; }
.modal-slide-enter-from { opacity: 0; transform: translateY(24px) scale(0.97); }
.modal-slide-leave-to { opacity: 0; transform: translateY(12px) scale(0.98); }
</style>
