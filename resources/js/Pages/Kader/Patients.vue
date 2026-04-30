<template>
    <KaderLayout>
        <Head title="Data Pasien" />

        <div class="flex flex-col gap-3 mb-5">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Data Pasien</h1>
                    <p class="text-sm text-gray-500">{{ patients.total }} pasien terdaftar</p>
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <a href="/kader/pasien/export-all"
                        class="flex flex-1 md:flex-none items-center justify-center gap-1.5 bg-green-500 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-green-600 transition shadow-sm shadow-green-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export Excel
                    </a>
                    <button @click="showAddModal = true"
                        class="flex flex-1 md:flex-none items-center justify-center gap-1.5 bg-primary text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition shadow-sm shadow-primary/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Pasien
                    </button>
                </div>
            </div>
        </div>

        <!-- Flash -->
        <div v-if="$page.props.flash?.success"
            class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm mb-4">
            {{ $page.props.flash.success }}
        </div>

        <!-- Search -->
        <div class="relative mb-4">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input v-model="search" @input="doSearch" type="text" placeholder="Cari nama atau NIK pasien..."
                class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/30" />
        </div>

        <!-- Table / Card List -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <!-- Mobile card list -->
            <div class="divide-y divide-gray-50 sm:hidden">
                <div v-if="patients.data.length === 0" class="px-4 py-8 text-center text-gray-400 text-sm">Tidak ada data pasien</div>
                <div v-for="patient in patients.data" :key="patient.id"
                    class="px-4 py-3.5 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ patient.name.charAt(0).toUpperCase() }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-800 text-sm truncate">{{ patient.name }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ patient.nik }}</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-gray-400">{{ patient.gender === 'male' ? 'Laki-laki' : patient.gender === 'female' ? 'Perempuan' : '-' }}</span>
                            <span class="text-xs bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded-md font-medium">{{ patient.health_records_count }} data</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5 flex-shrink-0">
                        <button @click="openEditModal(patient)"
                            class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-lg hover:bg-amber-200 transition text-center">Edit</button>
                        <Link :href="`/kader/pasien/${patient.id}/input`"
                            class="text-xs bg-primary text-white px-2 py-1 rounded-lg hover:bg-primary-dark transition text-center">+ Input</Link>
                        <Link :href="`/kader/pasien/${patient.id}`"
                            class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-lg hover:bg-gray-200 transition text-center">Detail</Link>
                    </div>
                </div>
            </div>

            <!-- Desktop table -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Pasien</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">NIK</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-if="patients.data.length === 0">
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400 text-sm">Tidak ada data pasien</td>
                        </tr>
                        <tr v-for="patient in patients.data" :key="patient.id"
                            class="hover:bg-gray-50/70 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                                        {{ patient.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ patient.name }}</p>
                                        <p class="text-gray-400 text-xs">{{ patient.gender === 'male' ? 'Laki-laki' : patient.gender === 'female' ? 'Perempuan' : '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ patient.nik }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded-lg font-medium">
                                    {{ patient.health_records_count }} data
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <button @click="openEditModal(patient)"
                                        class="text-xs bg-amber-100 text-amber-700 px-2.5 py-1 rounded-lg hover:bg-amber-200 transition">
                                        Edit
                                    </button>
                                    <Link :href="`/kader/pasien/${patient.id}/input`"
                                        class="text-xs bg-primary text-white px-2.5 py-1 rounded-lg hover:bg-primary-dark transition">
                                        + Input
                                    </Link>
                                    <Link :href="`/kader/pasien/${patient.id}`"
                                        class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg hover:bg-gray-200 transition">
                                        Detail
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="patients.last_page > 1" class="px-4 py-3 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-gray-500">
                <span>{{ patients.from }}–{{ patients.to }} dari {{ patients.total }}</span>
                <div class="flex gap-1">
                    <Link v-if="patients.prev_page_url" :href="patients.prev_page_url"
                        class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-50 text-xs">← Prev</Link>
                    <Link v-if="patients.next_page_url" :href="patients.next_page_url"
                        class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-50 text-xs">Next →</Link>
                </div>
            </div>
        </div>

        <!-- Add Patient Modal -->
        <Transition name="modal">
            <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showAddModal = false"></div>
                <div class="relative bg-white rounded-t-3xl sm:rounded-3xl shadow-2xl w-full max-w-md max-h-[90dvh] overflow-y-auto p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Tambah Pasien Baru</h3>
                        <button type="button" @click="fillDemoData"
                            class="flex items-center gap-1.5 text-[10px] font-bold bg-amber-100 hover:bg-amber-200 text-amber-700 px-3 py-1.5 rounded-lg transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Isi Data Demo
                        </button>
                    </div>

                    <form @submit.prevent="submitAdd" class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-700 mb-1 block">NIK (16 digit) <span class="text-red-500">*</span></label>
                            <input v-model="addForm.nik" type="text" maxlength="16" inputmode="numeric"
                                placeholder="3201xxxxxxxxxxxxxxx"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 tracking-widest"
                                :class="{ 'border-red-400': addForm.errors.nik }" />
                            <p v-if="addForm.errors.nik" class="text-red-500 text-xs mt-1">{{ addForm.errors.nik }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 mb-1 block">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input v-model="addForm.name" type="text"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                                :class="{ 'border-red-400': addForm.errors.name }" />
                            <p v-if="addForm.errors.name" class="text-red-500 text-xs mt-1">{{ addForm.errors.name }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 mb-1 block">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input v-model="addForm.date_of_birth" type="date"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-medium text-gray-700 mb-1 block">Jenis Kelamin</label>
                                <select v-model="addForm.gender"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
                                    <option value="">Pilih...</option>
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-700 mb-1 block">No. HP</label>
                                <input v-model="addForm.phone" type="tel"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30" />
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 mb-1 block">Alamat</label>
                            <textarea v-model="addForm.address" rows="1"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-none"></textarea>
                        </div>

                        <!-- IoMT Pairing Section -->
                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Konfigurasi IoMT</label>
                            <div class="flex items-center gap-3">
                                <div class="flex-1">
                                    <input v-model="addForm.device_id" type="text" placeholder="ID Perangkat (Optional)" readonly
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs bg-white font-mono" />
                                </div>
                                <button type="button" @click="simulatePairing('add')" :disabled="isPairing"
                                    class="bg-gray-900 text-white text-[10px] font-bold px-3 py-2 rounded-lg hover:bg-black transition flex items-center gap-1.5 disabled:opacity-50">
                                    <svg v-if="!isPairing" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                    <svg v-else class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    {{ addForm.device_id ? 'Ubah Device' : 'Tautkan' }}
                                </button>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-2">Hubungkan smartwatch pasien untuk otomatisasi data.</p>
                        </div>
                        <div class="flex flex-col-reverse md:flex-row gap-3 pt-2">
                            <button type="button" @click="showAddModal = false"
                                class="flex-1 border border-gray-200 text-gray-600 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-50 transition w-full">
                                Batal
                            </button>
                            <button type="submit" :disabled="addForm.processing"
                                class="flex-1 bg-primary text-white py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition disabled:opacity-50 w-full">
                                <span v-if="addForm.processing">Menyimpan...</span>
                                <span v-else>Simpan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>

        <!-- Edit Patient Modal -->
        <Transition name="modal">
            <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showEditModal = false"></div>
                <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-4 sm:p-6 lg:p-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Edit Data Pasien</h3>

                    <form @submit.prevent="submitEdit" class="space-y-4">
                        <div>
                            <label class="text-xs font-medium text-gray-700 mb-1 block">NIK</label>
                            <input :value="editForm.nik" type="text" disabled
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-500 tracking-widest" />
                            <p class="text-[11px] text-gray-400 mt-1">NIK tidak dapat diubah.</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 mb-1 block">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input v-model="editForm.name" type="text"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                                :class="{ 'border-red-400': editForm.errors.name }" />
                            <p v-if="editForm.errors.name" class="text-red-500 text-xs mt-1">{{ editForm.errors.name }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 mb-1 block">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input v-model="editForm.date_of_birth" type="date"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                                :class="{ 'border-red-400': editForm.errors.date_of_birth }" />
                            <p v-if="editForm.errors.date_of_birth" class="text-red-500 text-xs mt-1">{{ editForm.errors.date_of_birth }}</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-medium text-gray-700 mb-1 block">Jenis Kelamin</label>
                                <select v-model="editForm.gender"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
                                    <option value="">Pilih...</option>
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-700 mb-1 block">No. HP</label>
                                <input v-model="editForm.phone" type="tel"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30" />
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 mb-1 block">Alamat</label>
                            <textarea v-model="editForm.address" rows="2"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-none"></textarea>
                        </div>

                        <!-- IoMT Pairing Section -->
                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Konfigurasi IoMT</label>
                            <div class="flex items-center gap-3">
                                <div class="flex-1">
                                    <input v-model="editForm.device_id" type="text" placeholder="ID Perangkat" readonly
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs bg-white font-mono" />
                                </div>
                                <button type="button" @click="simulatePairing('edit')" :disabled="isPairing"
                                    class="bg-gray-900 text-white text-[10px] font-bold px-3 py-2 rounded-lg hover:bg-black transition flex items-center gap-1.5 disabled:opacity-50">
                                    <svg v-if="!isPairing" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                    <svg v-else class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    {{ editForm.device_id ? 'Ganti Perangkat' : 'Tautkan Perangkat' }}
                                </button>
                            </div>
                        </div>
                        <div class="flex flex-col-reverse md:flex-row gap-3 pt-2">
                            <button type="button" @click="showEditModal = false"
                                class="flex-1 border border-gray-200 text-gray-600 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-50 transition w-full">
                                Batal
                            </button>
                            <button type="submit" :disabled="editForm.processing"
                                class="flex-1 bg-primary text-white py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition disabled:opacity-50 w-full">
                                <span v-if="editForm.processing">Menyimpan...</span>
                                <span v-else>Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>

        <!-- Pairing Overlay -->
        <Transition name="fade">
            <div v-if="isPairing" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-5">
                <div class="bg-white rounded-3xl p-8 max-w-xs w-full text-center shadow-2xl">
                    <div class="relative w-20 h-20 mx-auto mb-6">
                        <div class="absolute inset-0 border-4 border-primary/20 rounded-full"></div>
                        <div class="absolute inset-0 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-8 h-8 text-primary animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Mencari Perangkat...</h3>
                    <p class="text-gray-500 text-xs leading-relaxed">Pastikan Bluetooth Smartwatch pasien aktif dan dalam jangkauan aplikasi VITALY.</p>
                </div>
            </div>
        </Transition>
    </KaderLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import KaderLayout from '@/Layouts/KaderLayout.vue';
import { pairDevice } from '@/Services/BluetoothService';

const props = defineProps({
    patients: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const showAddModal = ref(false);
const showEditModal = ref(false);
const editingPatientId = ref(null);
const isPairing = ref(false);

const simulatePairing = async (mode) => {
    isPairing.value = true;

    const setDeviceId = (id) => {
        if (mode === 'add') addForm.device_id = id;
        else                editForm.device_id = id;
    };

    const result = await pairDevice((msg) => {
        console.info('[VITALY BT Pairing]', msg);
    });

    if (result) {
        setDeviceId(result.deviceId);
    }

    isPairing.value = false;
};


const fillDemoData = () => {
    const randomSuffix = Math.floor(1000 + Math.random() * 9000);
    addForm.nik           = `32010155087${randomSuffix}0`.slice(0, 16);
    addForm.name          = 'Ahmad Fauzi';
    addForm.date_of_birth = '1968-03-22';
    addForm.gender        = 'male';
    addForm.phone         = `0856${Math.floor(10000000 + Math.random() * 90000000)}`;
    addForm.address       = 'Jl. Kenanga No. 12, Depok, Jawa Barat';
    addForm.device_id     = '';
};


const doSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get('/kader/pasien', { search: search.value }, { preserveState: true, replace: true });
    }, 400);
};

const addForm = useForm({
    nik: '',
    name: '',
    date_of_birth: '',
    gender: '',
    phone: '',
    address: '',
    device_id: '',
});

const editForm = useForm({
    nik: '',
    name: '',
    date_of_birth: '',
    gender: '',
    phone: '',
    address: '',
    device_id: '',
});

const normalizeDateInput = (dateValue) => {
    if (!dateValue) return '';
    return String(dateValue).slice(0, 10);
};

const submitAdd = () => {
    addForm.post('/kader/pasien', {
        onSuccess: () => {
            showAddModal.value = false;
            addForm.reset();
        },
    });
};

const openEditModal = (patient) => {
    editingPatientId.value = patient.id;
    editForm.reset();
    editForm.clearErrors();
    editForm.nik = patient.nik || '';
    editForm.name = patient.name || '';
    editForm.date_of_birth = normalizeDateInput(patient.date_of_birth);
    editForm.gender = patient.gender || '';
    editForm.phone = patient.phone || '';
    editForm.address = patient.address || '';
    editForm.device_id = patient.device_id || '';
    showEditModal.value = true;
};

const submitEdit = () => {
    if (!editingPatientId.value) return;

    editForm.put(`/kader/pasien/${editingPatientId.value}`, {
        preserveScroll: true,
        onSuccess: () => {
            showEditModal.value = false;
        },
    });
};
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
