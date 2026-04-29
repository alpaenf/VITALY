<template>
    <AppLayout>
        <Head title="Dashboard" />

        <!-- Greeting Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-primary text-white p-4 sm:p-6 mb-5 animate-fade-in-down shadow-xl shadow-primary/20">
            <!-- Modern subtle abstract decorations (White/Glassy glows instead of hard circles) -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 blur-3xl rounded-full -translate-y-1/2 translate-x-1/3"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-black/10 blur-2xl rounded-full translate-y-1/2 -translate-x-1/4"></div>
            
            <div class="relative flex items-center justify-between gap-4 z-10">
                <div class="flex-1">
                    <p class="text-white/80 text-xs sm:text-sm font-medium tracking-wide uppercase">{{ greeting }}</p>
                    <h1 class="text-2xl sm:text-3xl font-bold mt-1 tracking-tight text-white break-words">{{ patient?.name?.split(' ')[0] }}</h1>
                    <p class="text-white/70 text-[10px] sm:text-xs mt-1.5 font-medium">{{ currentDate }}</p>
                </div>
                <div class="text-right flex flex-col items-end shrink-0">
                    <div class="relative w-20 h-20 mb-1">
                        <!-- Redesigned professional ring -->
                        <svg class="w-20 h-20 -rotate-90 drop-shadow-md" viewBox="0 0 80 80">
                            <circle cx="40" cy="40" r="34" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="4"/>
                            <circle cx="40" cy="40" r="34" fill="none" class="stroke-white" stroke-width="5"
                                stroke-linecap="round"
                                :stroke-dasharray="`${(healthScore / 100) * 213.6} 213.6`"
                                style="transition: stroke-dasharray 1.5s ease-out;" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-2xl font-black leading-none text-white">{{ healthScore }}</span>
                        </div>
                    </div>
                    <p class="text-xs font-semibold text-white/90 uppercase tracking-wider">{{ healthScoreLabel }}</p>
                    <p class="text-[9px] text-white/60 mt-0.5 font-medium tracking-widest uppercase">Skor Kesehatan</p>
                </div>
            </div>
        </div>

        <!-- Quick Stats Row -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
            <div class="card-VITALY p-3.5 text-center hover-lift animate-fade-in-up delay-75"
                :class="latestRecord?.systolic ? '' : 'opacity-60'">
                <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-1.5">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <p class="text-sm font-bold text-gray-800">{{ latestRecord?.systolic ? `${latestRecord.systolic}/${latestRecord.diastolic}` : '—' }}</p>
                <p class="text-[10px] text-gray-400 mt-0.5">Tekanan</p>
            </div>
            <div class="card-VITALY p-3.5 text-center hover-lift animate-fade-in-up delay-100"
                :class="latestRecord?.heart_rate ? '' : 'opacity-60'">
                <div class="w-8 h-8 rounded-xl bg-secondary/20 flex items-center justify-center mx-auto mb-1.5">
                    <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
                <p class="text-sm font-bold text-gray-800">{{ latestRecord?.heart_rate ? `${latestRecord.heart_rate}` : '—' }}</p>
                <p class="text-[10px] text-gray-400 mt-0.5">BPM</p>
            </div>
            <div class="card-VITALY p-3.5 text-center hover-lift animate-fade-in-up delay-150">
                <div class="w-8 h-8 rounded-xl bg-[#D1FAE5] flex items-center justify-center mx-auto mb-1.5">
                    <svg class="w-4 h-4 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <p class="text-sm font-bold text-gray-800">{{ totalRecords }}</p>
                <p class="text-[10px] text-gray-400 mt-0.5">Total Data</p>
            </div>
        </div>

        <!-- Chart + Latest Record -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">
            <!-- Chart -->
            <div v-if="chartData.length > 1" class="card-VITALY p-4 sm:p-5 animate-fade-in-left delay-150">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                    <h3 class="font-semibold text-gray-700 text-sm">Tren Tekanan Darah</h3>
                    <div class="flex flex-wrap items-center gap-3 text-[10px] text-gray-400">
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-primary inline-block"></span>Sistolik</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-secondary inline-block"></span>Diastolik</span>
                    </div>
                </div>
                <div class="h-44">
                    <Line :data="lineChartData" :options="chartOptions" />
                </div>
            </div>

            <!-- Latest record metrics -->
            <div v-if="latestRecord" class="card-VITALY p-4 sm:p-5 animate-fade-in-right delay-150"
                :class="chartData.length <= 1 ? 'lg:col-span-2' : ''">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                    <h3 class="font-semibold text-gray-700 text-sm">Data Terkini</h3>
                    <span class="text-[10px] text-gray-400 bg-gray-100 px-2 py-1 rounded-full">{{ formatDate(latestRecord.recorded_at) }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <MetricCard v-if="latestRecord.systolic"
                        label="Tekanan Darah"
                        :value="`${latestRecord.systolic}/${latestRecord.diastolic} mmHg — ${bpLabel}`"
                        :status="bpStatus" icon="heart" />
                    <MetricCard v-if="latestRecord.heart_rate"
                        label="Detak Jantung"
                        :value="`${latestRecord.heart_rate} bpm — ${hrLabel}`"
                        :status="hrStatus" icon="pulse" />
                    <MetricCard v-if="latestRecord.blood_sugar"
                        label="Gula Darah"
                        :value="`${latestRecord.blood_sugar} mg/dL — ${sugarLabel}`"
                        :status="latestRecord.blood_sugar >= 126 ? 'warn' : 'info'" icon="drop" />
                    <MetricCard v-if="bmi"
                        label="IMT"
                        :value="`${bmi} — ${bmiLabel}`"
                        :status="bmiStatus" icon="weight" />
                    <MetricCard v-if="latestRecord.temperature"
                        label="Suhu Tubuh"
                        :value="`${latestRecord.temperature}°C — ${tempLabel}`"
                        :status="latestRecord.temperature > 37.5 ? 'warn' : 'info'" icon="temp" />
                    <MetricCard v-if="latestRecord.oxygen_saturation"
                        label="SpO2"
                        :value="`${latestRecord.oxygen_saturation}% — ${spo2Label}`"
                        :status="latestRecord.oxygen_saturation >= 95 ? 'good' : 'warn'" icon="lungs" />
                </div>
            </div>

            <!-- No data -->
            <div v-if="!latestRecord" class="card-VITALY p-6 sm:p-8 text-center animate-scale-in delay-150 lg:col-span-2">
                <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <h3 class="font-semibold text-gray-700 mb-1">Belum Ada Data</h3>
                <p class="text-sm text-gray-500 mb-4">Data kesehatan Anda belum tersedia. Minta kader untuk memasukkan data.</p>
                <Link href="/ai-analysis" class="inline-block bg-primary text-white text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-primary-dark transition">
                    Analisis AI Sekarang
                </Link>
            </div>
        </div>

        <!-- Bottom: AI + Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">
            <!-- AI Prompt Card -->
            <div class="bg-primary-dark rounded-2xl p-5 text-white hover-lift animate-fade-in-up delay-250 shadow-lg shadow-primary/20">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-base">Analisis AI</p>
                        <p class="text-white/70 text-xs mt-0.5 mb-3">Dapatkan interpretasi mendalam kondisi kesehatanmu dari AI</p>
                        <Link href="/ai-analysis"
                            class="inline-flex items-center gap-1.5 bg-white text-primary font-semibold text-xs px-4 py-2 rounded-xl hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Mulai Analisis
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Latest Analysis preview OR quick navigation -->
            <div v-if="latestAnalysis" class="card-VITALY p-5 hover-lift animate-fade-in-up delay-300 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-semibold text-gray-700 text-sm">Analisis Terakhir</h3>
                        <span class="text-[10px] text-gray-400">{{ formatDate(latestAnalysis.created_at) }}</span>
                    </div>
                    <p class="text-xs text-gray-500 leading-relaxed line-clamp-2 mb-4">{{ truncateAnalysis(latestAnalysis.result) }}</p>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-t border-gray-50 pt-3 mt-auto">
                    <Link href="/ai-analysis" class="text-primary text-xs font-semibold inline-flex items-center gap-1 hover:gap-2 transition-all">
                        Lihat selengkapnya
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </Link>
                    <Link href="/standar-normal" class="text-xs font-medium bg-[#FCD34D]/50 text-[#064E3B] px-3 py-1.5 rounded-lg hover:bg-[#FCD34D] transition inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Kalkulator IMT
                    </Link>
                </div>
            </div>
            <div v-else class="card-VITALY p-5 animate-fade-in-up delay-300">
                <h3 class="font-semibold text-gray-700 text-sm mb-3">Aksi Cepat</h3>
                <div class="space-y-2">
                    <Link href="/ai-analysis" class="flex items-center gap-3 p-2.5 rounded-xl bg-primary/10 hover:bg-[#FCD34D] transition">
                        <div class="w-8 h-8 bg-primary rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Analisis AI</span>
                    </Link>
                    <Link href="/history" class="flex items-center gap-3 p-2.5 rounded-xl bg-[#D1FAE5] hover:bg-[#e4cdce] transition">
                        <div class="w-8 h-8 bg-[#10B981] rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Lihat Riwayat</span>
                    </Link>
                    <Link href="/standar-normal" class="flex items-center gap-3 p-2.5 rounded-xl bg-blue-50 hover:bg-blue-100 transition">
                        <div class="w-8 h-8 bg-blue-500 rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Cek Standar Normal</span>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Target Mingguan (Gamifikasi) -->
        <div v-if="missions.length > 0" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 mb-5 animate-fade-in-up delay-400">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-700 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Misi Kesehatan Minggu Ini
                </h3>
                <span class="text-[10px] bg-amber-50 text-amber-700 px-2 py-1 rounded-lg font-bold">+10 Poin / Misi</span>
            </div>
            <div class="space-y-2">
                <label v-for="(mission, idx) in missions" :key="idx" class="flex items-start gap-3 p-3 rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer transition group">
                    <div class="relative flex items-start mt-0.5">
                        <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/30 transition-colors cursor-pointer" />
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-700 group-hover:text-primary transition-colors">{{ mission.title }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ mission.desc }}</p>
                    </div>
                </label>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed, h } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Tooltip, Filler } from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Tooltip, Filler);

const props = defineProps({
    latestRecord: Object,
    recentRecords: Array,
    totalRecords: Number,
    latestAnalysis: Object,
    healthScore: Number,
    chartData: Array,
});

const page = usePage();
const patient = computed(() => page.props.patient);

const greeting = computed(() => {
    const hour = new Date().getHours();
    if (hour < 12) return 'Selamat Pagi';
    if (hour < 15) return 'Selamat Siang';
    if (hour < 18) return 'Selamat Sore';
    return 'Selamat Malam';
});

const currentDate = computed(() =>
    new Date().toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })
);

const healthScoreLabel = computed(() => {
    const s = props.healthScore;
    if (s >= 85) return 'Sangat Baik';
    if (s >= 70) return 'Baik';
    if (s >= 50) return 'Perlu Perhatian';
    if (s === 0) return 'Belum Ada Data';
    return 'Perlu Periksa';
});

const bmi = computed(() => {
    if (props.latestRecord?.weight && props.latestRecord?.height) {
        const h = props.latestRecord.height / 100;
        return (props.latestRecord.weight / (h * h)).toFixed(1);
    }
    return null;
});

const bmiLabel = computed(() => {
    const b = parseFloat(bmi.value);
    if (!b) return '';
    if (b >= 30.0) return 'Obes II';
    if (b >= 25.0) return 'Obes I';
    if (b >= 23.0) return 'Beresiko menjadi obes';
    if (b >= 18.5) return 'Berat badan normal';
    return 'Berat badan kurang';
});

const bmiStatus = computed(() => {
    const b = parseFloat(bmi.value);
    if (!b) return 'info';
    if (b >= 30.0) return 'critical';
    if (b >= 25.0) return 'danger';
    if (b >= 23.0) return 'warn';
    if (b >= 18.5) return 'good';
    return 'warn';
});

const bpStatus = computed(() => {
    const s = props.latestRecord?.systolic, d = props.latestRecord?.diastolic;
    if (!s) return 'info';
    if (s >= 140 || d >= 90) return 'danger';
    if (s >= 130 || d >= 85) return 'warn';
    if (s >= 120 || d >= 80) return 'good';
    return 'good';
});

const bpLabel = computed(() => {
    const s = props.latestRecord?.systolic, d = props.latestRecord?.diastolic;
    if (!s) return '';
    if (s >= 180 || d >= 110) return 'Hipertensi Derajat 3';
    if (s >= 160 || d >= 100) return 'Hipertensi Derajat 2';
    if (s >= 140 && d < 90) return 'Hipertensi Sistolik';
    if (s >= 140 || d >= 90) return 'Hipertensi Derajat 1';
    if (s >= 130 || d >= 85) return 'Normal-tinggi';
    if (s >= 120 || d >= 80) return 'Normal';
    return 'Optimal';
});

const hrLabel = computed(() => {
    const hr = props.latestRecord?.heart_rate;
    if (!hr) return '';
    if (hr < 60) return 'Lambat';
    if (hr > 100) return 'Cepat';
    return 'Normal';
});

const sugarLabel = computed(() => {
    const bs = props.latestRecord?.blood_sugar;
    if (!bs) return '';
    if (bs >= 200) return 'Risiko Diabetes';
    if (bs >= 126) return 'Pradiabetes';
    if (bs < 70) return 'Terlalu Rendah';
    return 'Normal';
});

const tempLabel = computed(() => {
    const t = props.latestRecord?.temperature;
    if (!t) return '';
    if (t > 37.5) return 'Demam';
    if (t < 36.0) return 'Rendah';
    return 'Normal';
});

const spo2Label = computed(() => {
    const sp = props.latestRecord?.oxygen_saturation;
    if (!sp) return '';
    if (sp < 95) return 'Rendah (Hipoksia)';
    return 'Normal';
});

const hrStatus = computed(() => {
    const hr = props.latestRecord?.heart_rate;
    if (!hr) return 'info';
    if (hr < 60 || hr > 100) return 'warn';
    return 'good';

});

const missions = computed(() => {
    let list = [];
    const s = props.latestRecord?.systolic;
    const bs = props.latestRecord?.blood_sugar;
    const hr = props.latestRecord?.heart_rate;
    const b = parseFloat(bmi.value);

    list.push({ title: 'Jalan Kaki 15 Menit', desc: 'Lakukan aktivitas fisik ringan setiap pagi atau sore.' });
    list.push({ title: 'Minum Air Putih 2 Liter', desc: 'Jaga hidrasi tubuh dengan minum air minimal 8 gelas hari ini.' });

    if (s && s >= 130) list.push({ title: 'Kurangi Konsumsi Garam', desc: 'Hindari makanan bersodium tinggi (seperti ikan asin, camilan gurih).' });
    if (bs && bs >= 140) list.push({ title: 'Puasa Gula Tambahan', desc: 'Jangan mengonsumsi teh manis, kopi manis, atau boba hari ini.' });
    if (b && b >= 25) list.push({ title: 'Tidur Cukup 7-8 Jam', desc: 'Tidur yang cukup membantu mengontrol nafsu makan dan metabolisme.' });

    return list.slice(0, 3);
});

const lineChartData = computed(() => ({
    labels: props.chartData.map(d => d.date),
    datasets: [
        { label: 'Sistolik', data: props.chartData.map(d => d.systolic), borderColor: '#059669', backgroundColor: 'rgba(5,150,105,0.08)', fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#059669' },
        { label: 'Diastolik', data: props.chartData.map(d => d.diastolic), borderColor: '#F59E0B', backgroundColor: 'transparent', fill: false, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#F59E0B' },
    ],
}));

const chartOptions = {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
    scales: {
        x: { grid: { display: false }, ticks: { font: { size: 9 }, maxRotation: 0 } },
        y: { grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { font: { size: 9 }, precision: 0 } },
    },
};

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
};

const truncateAnalysis = (text) => {
    if (!text) return '';
    return text.replace(/[#*_]/g, '').substring(0, 180) + '...';
};

// MetricCard render component
const statusStyles = {
    good:   { bg: 'bg-[#FCD34D]',  dot: 'bg-[#10B981]',    text: 'text-[#064E3B]' },
    warn:   { bg: 'bg-secondary/15', dot: 'bg-secondary',    text: 'text-[#064E3B]' },
    danger: { bg: 'bg-primary/10',   dot: 'bg-primary',      text: 'text-primary-dark' },
    info:   { bg: 'bg-[#D1FAE5]',    dot: 'bg-[#10B981]',    text: 'text-[#10B981]' },
};

const MetricCard = {
    props: ['label', 'value', 'status'],
    setup(props) {
        return () => {
            const s = statusStyles[props.status] || statusStyles.info;
            return h('div', { class: `${s.bg} rounded-xl p-3` }, [
                h('div', { class: 'flex items-center gap-1.5 mb-1' }, [
                    h('span', { class: `w-1.5 h-1.5 rounded-full ${s.dot} flex-shrink-0` }),
                    h('p', { class: `text-[10px] font-medium ${s.text} truncate` }, props.label),
                ]),
                h('p', { class: 'text-sm font-bold text-gray-800 leading-tight' }, props.value),
            ]);
        };
    }
};
</script>
