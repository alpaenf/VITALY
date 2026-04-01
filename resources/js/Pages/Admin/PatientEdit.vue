<template>
    <AdminLayout>
        <Head :title="`Edit Pasien - ${patient.name}`" />

        <div class="max-w-3xl">
            <div class="mb-5">
                <h1 class="text-2xl font-bold text-gray-800">Edit Data Pasien</h1>
                <p class="text-sm text-gray-500">Perbarui data pasien lalu simpan perubahan.</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-6">
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="text-xs font-medium text-gray-700 mb-1 block">NIK</label>
                        <input :value="patient.nik" type="text" disabled
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-500 tracking-widest" />
                        <p class="text-[11px] text-gray-400 mt-1">NIK tidak dapat diubah.</p>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-700 mb-1 block">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input v-model="form.name" type="text"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                            :class="{ 'border-red-400': form.errors.name }" />
                        <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-700 mb-1 block">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input v-model="form.date_of_birth" type="date"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                            :class="{ 'border-red-400': form.errors.date_of_birth }" />
                        <p v-if="form.errors.date_of_birth" class="text-red-500 text-xs mt-1">{{ form.errors.date_of_birth }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-medium text-gray-700 mb-1 block">Jenis Kelamin</label>
                            <select v-model="form.gender"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
                                <option value="">Pilih...</option>
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 mb-1 block">No. HP</label>
                            <input v-model="form.phone" type="tel"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30" />
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-700 mb-1 block">Alamat</label>
                        <textarea v-model="form.address" rows="3"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-none"></textarea>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row gap-3 pt-2">
                        <Link :href="`/admin/patients/${patient.id}`"
                            class="sm:flex-1 inline-flex justify-center items-center border border-gray-200 text-gray-600 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                            Batal
                        </Link>
                        <button type="submit" :disabled="form.processing"
                            class="sm:flex-1 bg-primary text-white py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition disabled:opacity-50">
                            <span v-if="form.processing">Menyimpan...</span>
                            <span v-else>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    patient: Object,
});

const normalizeDateInput = (dateValue) => {
    if (!dateValue) return '';
    return String(dateValue).slice(0, 10);
};

const form = useForm({
    name: props.patient?.name || '',
    date_of_birth: normalizeDateInput(props.patient?.date_of_birth),
    gender: props.patient?.gender || '',
    phone: props.patient?.phone || '',
    address: props.patient?.address || '',
});

const submit = () => {
    form.put(`/admin/patients/${props.patient.id}`);
};
</script>
