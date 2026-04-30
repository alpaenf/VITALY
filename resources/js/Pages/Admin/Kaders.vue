<template>
    <AdminLayout>
        <Head title="Kelola Health Agent" />

        <div class="flex items-center justify-between gap-3 mb-5 animate-fade-in-down">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Kelola Health Agent</h1>
                <p class="text-sm text-gray-500">{{ kaders.total }} kader terdaftar</p>
            </div>
            <button @click="showAddModal = true"
                class="flex items-center gap-1.5 px-3 py-2 sm:px-4 text-xs sm:text-sm font-semibold bg-primary hover:bg-primary-dark text-white rounded-xl transition shadow-sm shadow-primary/20">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden xs:inline">Tambah Health Agent</span>
                <span class="xs:hidden">Tambah</span>
            </button>
        </div>

        <div v-if="$page.props.flash?.success"
            class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm mb-4">
            {{ $page.props.flash.success }}
        </div>
        <div v-if="$page.props.flash?.error"
            class="bg-red-100 border border-red-400 text-red-800 rounded-xl px-4 py-3 text-sm mb-4">
            {{ $page.props.flash.error }}
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in-up delay-75">

            <!-- Mobile card list -->
            <div class="divide-y divide-gray-50 sm:hidden">
                <div v-if="!kaders.data.length" class="px-4 py-8 text-center text-gray-400 text-sm">Belum ada kader</div>
                <div v-for="kader in kaders.data" :key="kader.id"
                    class="px-4 py-3.5 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ kader.name.charAt(0).toUpperCase() }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-800 text-sm truncate">{{ kader.name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ kader.email }}</p>
                        <p class="text-xs text-gray-300 mt-0.5">{{ formatDate(kader.created_at) }}</p>
                    </div>
                    <button @click="confirmDelete(kader.id, kader.name)"
                        class="text-xs text-red-400 hover:text-red-600 px-2 py-1 border border-red-100 hover:border-red-200 rounded-lg transition flex-shrink-0">
                        Hapus
                    </button>
                </div>
            </div>

            <!-- Desktop table -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Health Agent</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Bergabung</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="kader in kaders.data" :key="kader.id" class="hover:bg-gray-50/70 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                                        {{ kader.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <p class="font-medium text-gray-800">{{ kader.name }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ kader.email }}</td>
                            <td class="px-4 py-3 text-xs text-gray-500">{{ formatDate(kader.created_at) }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <button @click="confirmDelete(kader.id, kader.name)"
                                        class="text-xs text-red-600 hover:text-red-800 px-2 py-1 border border-red-300 hover:border-red-500 rounded-lg transition">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!kaders.data.length">
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada kader</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="kaders.last_page > 1" class="flex items-center justify-between px-4 py-3 border-t border-gray-100">
                <p class="text-xs text-gray-500">{{ kaders.from }}–{{ kaders.to }} dari {{ kaders.total }}</p>
                <div class="flex gap-2">
                    <Link v-if="kaders.prev_page_url" :href="kaders.prev_page_url"
                        class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">← Prev</Link>
                    <Link v-if="kaders.next_page_url" :href="kaders.next_page_url"
                        class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Next →</Link>
                </div>
            </div>
        </div>

        <!-- Add Kader Modal -->
        <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4" @click.self="closeAddModal">
            <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-xl">
                <h3 class="font-bold text-gray-800 mb-4">Tambah Health Agent Baru</h3>
                <form @submit.prevent="submitAdd" class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                        <input v-model="addForm.name" type="text" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30" placeholder="Nama kader" required />
                        <p v-if="addErrors.name" class="text-xs text-red-600 mt-1">{{ addErrors.name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Email Google</label>
                        <input v-model="addForm.email" type="email" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30" placeholder="email@gmail.com" required />
                        <p v-if="addErrors.email" class="text-xs text-red-600 mt-1">{{ addErrors.email }}</p>
                    </div>
                    <!-- Google login info -->
                    <div class="flex items-start gap-2 bg-blue-50 border border-blue-100 rounded-xl px-3 py-2.5">
                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-blue-600">Health Agent akan login menggunakan akun <strong>Google</strong> dengan email di atas. Pastikan email sudah benar.</p>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="closeAddModal"
                            class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" :disabled="addLoading"
                            class="flex-1 py-2.5 bg-primary hover:bg-primary-dark rounded-xl text-sm font-semibold text-white transition disabled:opacity-60">
                            {{ addLoading ? 'Menyimpan...' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirm Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4" @click.self="showDeleteModal = false">
            <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-xl">
                <h3 class="font-bold text-gray-800 mb-2">Hapus Health Agent?</h3>
                <p class="text-sm text-gray-500 mb-5">Akun kader <strong>{{ actionName }}</strong> akan dihapus permanen.</p>
                <div class="flex gap-3">
                    <button @click="showDeleteModal = false"
                        class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button @click="doDelete"
                        class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 rounded-xl text-sm font-medium text-white transition">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ kaders: Object });

const formatDate = (d) => d ? new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';

// Add kader
const showAddModal = ref(false);
const addLoading = ref(false);
const addErrors = ref({});
const addForm = ref({ name: '', email: '' });

const closeAddModal = () => {
    showAddModal.value = false;
    addForm.value = { name: '', email: '' };
    addErrors.value = {};
};

const submitAdd = () => {
    addLoading.value = true;
    router.post('/admin/kaders', addForm.value, {
        onSuccess: () => { closeAddModal(); addLoading.value = false; },
        onError: (errors) => { addErrors.value = errors; addLoading.value = false; },
    });
};

// Delete
const showDeleteModal = ref(false);
const actionId = ref(null);
const actionName = ref('');

const confirmDelete = (id, name) => { actionId.value = id; actionName.value = name; showDeleteModal.value = true; };
const doDelete = () => {
    router.delete(`/admin/kaders/${actionId.value}`, { onSuccess: () => { showDeleteModal.value = false; } });
};
</script>
