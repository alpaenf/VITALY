<template>
    <Transition name="modal-fade">
        <div v-if="show" class="fixed inset-0 z-[999] flex items-end sm:items-center justify-center p-4"
            @click.self="$emit('cancel')">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="$emit('cancel')"></div>
            <Transition name="modal-slide">
                <div v-if="show" class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden z-10">
                    <div class="h-1 w-full" :class="accentClass"></div>
                    <div class="px-6 py-5">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0" :class="iconBgClass">
                                <slot name="icon">
                                    <svg class="w-6 h-6" :class="iconColorClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </slot>
                            </div>
                            <div>
                                <p class="text-base font-bold text-gray-900">{{ title }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ subtitle }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed mb-5">{{ message }}</p>
                        <div class="flex gap-3">
                            <button @click="$emit('cancel')"
                                class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 active:scale-95 transition-all duration-150">
                                {{ cancelLabel }}
                            </button>
                            <button @click="$emit('confirm')"
                                class="flex-1 px-4 py-2.5 rounded-xl text-white text-sm font-semibold shadow-md active:scale-95 transition-all duration-150 hover:opacity-90"
                                :class="confirmBtnClass">
                                {{ confirmLabel }}
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
    </Transition>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    show:         { type: Boolean, default: false },
    title:        { type: String, default: 'Konfirmasi' },
    subtitle:     { type: String, default: 'Tindakan ini tidak dapat dibatalkan' },
    message:      { type: String, default: 'Apakah Anda yakin?' },
    confirmLabel: { type: String, default: 'Ya, Lanjutkan' },
    cancelLabel:  { type: String, default: 'Batal' },
    variant:      { type: String, default: 'danger' }, // 'danger' | 'warning'
});
defineEmits(['confirm', 'cancel']);

const accentClass   = computed(() => props.variant === 'danger' ? 'bg-gradient-to-r from-primary to-red-400' : 'bg-gradient-to-r from-amber-400 to-yellow-500');
const iconBgClass   = computed(() => props.variant === 'danger' ? 'bg-red-50' : 'bg-amber-50');
const iconColorClass= computed(() => props.variant === 'danger' ? 'text-primary' : 'text-amber-500');
const confirmBtnClass=computed(() => props.variant === 'danger' ? 'bg-gradient-to-r from-primary to-red-500 shadow-primary/30' : 'bg-gradient-to-r from-amber-400 to-yellow-500 shadow-amber-200');
</script>

<style scoped>
.modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.25s ease; }
.modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; }
.modal-slide-enter-active { transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
.modal-slide-leave-active { transition: all 0.2s ease; }
.modal-slide-enter-from { opacity: 0; transform: translateY(24px) scale(0.97); }
.modal-slide-leave-to { opacity: 0; transform: translateY(12px); }
</style>
