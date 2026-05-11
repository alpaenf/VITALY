<template>
    <AppLayout>
        <Head title="Dashboard" />

        <!-- ═══════════════════════════════════════════════════════════ -->
        <!-- SAFETY DISCLAIMER MODAL (tampil sekali per browser)        -->
        <!-- ═══════════════════════════════════════════════════════════ -->
        <Transition name="modal-fade">
            <div v-if="showDisclaimer"
                id="safety-disclaimer-modal"
                class="fixed inset-0 z-[999] flex items-center justify-center p-4"
                style="background: rgba(0,0,0,0.6); backdrop-filter: blur(6px);">
                <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden">

                    <!-- Header bar -->
                    <div class="relative flex items-center gap-3 px-5 pt-5 pb-4">
                        <div class="w-11 h-11 rounded-2xl flex items-center justify-center flex-shrink-0"
                            style="background: linear-gradient(135deg, #FEF3C7, #FDE68A);">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="font-bold text-gray-800 text-sm leading-tight">Perhatian Penting</h2>
                            <p class="text-[11px] text-amber-600 font-semibold tracking-widest uppercase mt-0.5">Medical Disclaimer</p>
                        </div>
                        <!-- Tombol X -->
                        <button @click="agreeDisclaimer"
                            class="w-8 h-8 flex items-center justify-center rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition flex-shrink-0"
                            aria-label="Tutup">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Input Data Mandiri — always accessible -->
                    <button @click="goTo('/input-mandiri')"
                        class="flex items-center gap-4 px-4 py-3.5 rounded-xl hover:bg-[#ECFDF5] transition text-left group w-full">
                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-800">Input Data Mandiri</p>
                            <p class="text-xs text-gray-400 mt-0.5">Tambahkan data pengukuran baru</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-primary transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <!-- Divider -->
                    <div class="mx-4 my-1 border-t border-gray-100"></div>

                    <!-- Body -->
                    <div class="px-5 py-4 space-y-3">
                        <!-- Intro box -->
                        <div class="bg-amber-50 border border-amber-100 rounded-2xl px-4 py-3">
                            <p class="text-xs text-gray-700 leading-relaxed">
                                <strong class="text-gray-900">Vitaly Smart Intelligence</strong> adalah sistem pemantauan
                                kesehatan berbasis perangkat IoMT yang berfungsi sebagai
                                <strong class="text-gray-900">alat bantu monitoring awal</strong>,
                                bukan alat diagnosis medis final.
                            </p>
                        </div>

                        <!-- Point list — teks dibungkus span agar tidak patah -->
                        <ul class="space-y-2.5">
                            <li class="flex items-start gap-3">
                                <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 mt-0.5 text-[10px] font-black">1</span>
                                <span class="text-xs text-gray-600 leading-relaxed">
                                    Data dari smartwatch bersifat <strong class="text-gray-800">estimasi</strong> dan dapat memiliki margin error. Selalu validasi dengan alat medis terstandar.
                                </span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 mt-0.5 text-[10px] font-black">2</span>
                                <span class="text-xs text-gray-600 leading-relaxed">
                                    Analisis AI merupakan <strong class="text-gray-800">rekomendasi gaya hidup</strong>, bukan resep atau anjuran medis yang mengikat.
                                </span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-5 h-5 rounded-full bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 mt-0.5 text-[10px] font-black">!</span>
                                <span class="text-xs text-gray-600 leading-relaxed">
                                    Saat mengalami <strong class="text-gray-800">gejala darurat</strong>, segera hubungi fasilitas kesehatan terdekat. Jangan andalkan sistem ini sebagai respons pertama.
                                </span>
                            </li>
                        </ul>
                    </div>

                    <!-- Footer button -->
                    <div class="px-5 pb-5">
                        <button id="btn-disclaimer-agree"
                            @click="agreeDisclaimer"
                            class="w-full py-3 rounded-2xl font-bold text-sm text-white transition-all hover:opacity-90 active:scale-95 shadow-lg"
                            style="background: linear-gradient(135deg, #059669, #047857);">
                            ✓ &nbsp;Saya Mengerti &amp; Setuju
                        </button>
                        <p class="text-center text-[10px] text-gray-400 mt-2.5">Pernyataan ini hanya ditampilkan satu kali.</p>
                    </div>

                </div>
            </div>
        </Transition>

        <!-- Bluetooth Status Modal (themed) -->
        <Transition name="modal-fade">
            <div v-if="showBluetoothModal"
                class="fixed inset-0 z-[998] flex items-center justify-center p-4"
                style="background: rgba(0,0,0,0.6); backdrop-filter: blur(6px);">
                <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden">
                    <div class="flex items-center gap-3 px-5 pt-5 pb-4">
                        <div class="w-11 h-11 rounded-2xl flex items-center justify-center flex-shrink-0"
                            style="background: linear-gradient(135deg, #ECFDF5, #D1FAE5);">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M11 3v8l-2-2m2 2l2-2m-2 2v8m-3-4h6"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="font-bold text-gray-800 text-sm leading-tight">Bluetooth Diperlukan</h2>
                            <p class="text-[11px] text-primary font-semibold tracking-widest uppercase mt-0.5">Ambil Data Smartwatch</p>
                        </div>
                        <button @click="closeBluetoothModal"
                            class="w-8 h-8 flex items-center justify-center rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition flex-shrink-0"
                            aria-label="Tutup">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="px-5 pb-4">
                        <div class="bg-primary/5 border border-primary/10 rounded-2xl px-4 py-3">
                            <p class="text-xs text-gray-700 leading-relaxed">{{ bluetoothMessage }}</p>
                        </div>
                        <ul class="space-y-2.5 mt-4">
                            <li class="flex items-start gap-3">
                                <span class="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0 mt-0.5 text-[10px] font-black">1</span>
                                <span class="text-xs text-gray-600 leading-relaxed">Aktifkan Bluetooth di perangkat sebelum sinkronisasi.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0 mt-0.5 text-[10px] font-black">2</span>
                                <span class="text-xs text-gray-600 leading-relaxed">Pastikan smartwatch dalam mode pairing/discoverable.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0 mt-0.5 text-[10px] font-black">3</span>
                                <span class="text-xs text-gray-600 leading-relaxed">Gunakan Chrome/Edge dan akses via HTTPS.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="px-5 pb-5 flex items-center gap-2">
                        <button @click="closeBluetoothModal"
                            class="flex-1 py-2.5 rounded-2xl font-semibold text-xs text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                            Tutup
                        </button>
                        <button @click="retrySync"
                            class="flex-1 py-2.5 rounded-2xl font-bold text-xs text-white transition-all hover:opacity-90 active:scale-95 shadow-lg"
                            style="background: linear-gradient(135deg, #059669, #047857);">
                            Coba Lagi
                        </button>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- ═══════════════════════════════════════════════════════════ -->
        <!-- DEMO IoMT CONFIRMATION MODAL                               -->
        <!-- ═══════════════════════════════════════════════════════════ -->
        <Transition name="modal-fade">
            <div v-if="showDemoModal"
                class="fixed inset-0 z-[998] flex items-center justify-center p-4"
                style="background: rgba(0,0,0,0.6); backdrop-filter: blur(6px);">
                <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden">

                    <!-- Header -->
                    <div class="flex items-center gap-3 px-5 pt-5 pb-4">
                        <div class="w-11 h-11 rounded-2xl flex items-center justify-center flex-shrink-0 bg-amber-100">
                            <!-- Beaker / lab icon -->
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 3v8.5L5.5 17A2 2 0 007.31 20h9.38a2 2 0 001.81-2.87L15 11.5V3M9 3h6M9 3H7m8 0h2"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <h2 class="font-bold text-gray-800 text-sm leading-tight">Demo IoMT</h2>
                                <span class="text-[9px] font-black text-amber-700 bg-amber-100 px-1.5 py-0.5 rounded-full uppercase tracking-widest">SIMULASI</span>
                            </div>
                            <p class="text-[11px] text-amber-600 font-semibold tracking-widest uppercase mt-0.5">Proof of Concept</p>
                        </div>
                        <button @click="showDemoModal = false"
                            class="w-8 h-8 flex items-center justify-center rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition flex-shrink-0"
                            aria-label="Tutup">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="px-5 pb-4 space-y-3">
                        <div class="bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3">
                            <p class="text-xs text-gray-700 leading-relaxed">
                                Fitur ini akan <strong class="text-gray-900">mensimulasikan pengambilan data dari perangkat IoMT</strong>
                                sebagai demonstrasi alur kerja sistem. Data yang dihasilkan adalah nilai acak yang realistis secara klinis.
                            </p>
                        </div>
                        <ul class="space-y-2">
                            <li class="flex items-start gap-3">
                                <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0 mt-0.5 text-[10px] font-black">!</span>
                                <span class="text-xs text-gray-600 leading-relaxed">Data ini <strong class="text-gray-800">bukan data nyata</strong> dari perangkat fisik.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 mt-0.5 text-[10px] font-black">i</span>
                                <span class="text-xs text-gray-600 leading-relaxed">Rekaman akan diberi label <strong class="text-gray-800">"Demo IoMT"</strong> agar dapat dibedakan.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-0.5 text-[10px] font-black">✓</span>
                                <span class="text-xs text-gray-600 leading-relaxed">Alur validasi, analisis AI, dan penyimpanan data tetap berjalan nyata.</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Footer -->
                    <div class="px-5 pb-5 flex items-center gap-2">
                        <button @click="showDemoModal = false"
                            class="flex-1 py-2.5 rounded-2xl font-semibold text-xs text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                            Batal
                        </button>
                        <button id="btn-demo-confirm" @click="confirmDemo"
                            class="flex-1 py-2.5 rounded-2xl font-bold text-xs text-white transition-all hover:opacity-90 active:scale-95 shadow-lg bg-amber-700">
                            ▶&nbsp; Mulai Simulasi
                        </button>
                    </div>
                </div>
            </div>
        </Transition>


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
        <!-- IoMT Device Status Bar -->
        <div v-if="props.deviceId"
            class="flex items-center gap-2.5 bg-emerald-50 border border-emerald-200 rounded-xl px-3 py-2.5 mb-4 animate-fade-in-down">
            <span class="relative flex-shrink-0">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 block"></span>
                <span class="absolute inset-0 w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping opacity-60"></span>
            </span>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-emerald-800 leading-none">Smartwatch Tersandingkan</p>
                <p class="text-[10px] text-emerald-600 font-mono mt-0.5 truncate">{{ props.deviceId }}</p>
            </div>
            <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded-lg flex-shrink-0">IoMT Aktif</span>
        </div>
        <div v-else
            class="flex items-center gap-2.5 bg-amber-50 border border-amber-200 rounded-xl px-3 py-2.5 mb-4 animate-fade-in-down">
            <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            <p class="text-xs text-amber-700 flex-1">Belum ada smartwatch tersandingkan. Hubungi Health Facilitator untuk menautkan perangkat.</p>
        </div>

        <!-- Action Row (Always Visible) -->
        <div class="grid grid-cols-2 gap-3 mb-5">

            <!-- ── REAL Sync button (hanya tampil jika ada deviceId) ── -->
            <button v-if="props.deviceId" @click="doSync" :disabled="isSyncing"
                class="flex flex-col items-center justify-center p-3 rounded-2xl bg-gradient-to-br from-primary to-primary-dark text-white shadow-lg shadow-primary/25 hover:opacity-90 active:scale-95 transition disabled:opacity-60 text-center relative overflow-hidden">
                <!-- Badge device ID -->
                <span v-if="!isSyncing"
                    class="absolute top-1.5 right-1.5 flex items-center gap-0.5 bg-white/20 backdrop-blur-sm text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full leading-none">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse inline-block"></span>
                    {{ props.deviceId.slice(0, 8) }}
                </span>
                <svg v-if="!isSyncing" class="w-6 h-6 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <svg v-else class="w-6 h-6 mb-1.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <p class="font-bold text-xs">{{ isSyncing ? (syncStatus || 'Mengambil...') : 'Ambil Data Terbaru' }}</p>
                <p class="text-[9px] text-white/70 mt-0.5">{{ isSyncing ? '' : 'Smartwatch Terpasang ✓' }}</p>
            </button>

            <!-- ── DEMO IoMT button (tampil jika TIDAK ada deviceId) ── -->
            <button v-else @click="showDemoModal = true" :disabled="isSyncing"
                class="flex flex-col items-center justify-center p-3 rounded-2xl text-white shadow-lg hover:opacity-90 active:scale-95 transition disabled:opacity-60 text-center relative overflow-hidden bg-amber-700">
                <!-- DEMO badge -->
                <span class="absolute top-1.5 right-1.5 text-[8px] font-black bg-white/25 text-white px-1.5 py-0.5 rounded-full leading-none tracking-widest">DEMO</span>
                <!-- Beaker icon -->
                <svg v-if="!isSyncing" class="w-6 h-6 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 3v8.5L5.5 17A2 2 0 007.31 20h9.38a2 2 0 001.81-2.87L15 11.5V3M9 3h6M9 3H7m8 0h2"/>
                </svg>
                <svg v-else class="w-6 h-6 mb-1.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <p class="font-bold text-xs">{{ isSyncing ? (syncStatus || 'Mensimulasikan...') : 'Demo IoMT' }}</p>
                <p class="text-[9px] text-white/70 mt-0.5">{{ isSyncing ? '' : 'Simulasi Perangkat' }}</p>
            </button>

            <!-- Input Manual -->
            <Link href="/input-mandiri" class="flex flex-col items-center justify-center p-3 rounded-2xl bg-white border border-primary/20 text-gray-800 shadow-sm hover:bg-primary/5 active:scale-95 transition text-center">
                <svg class="w-6 h-6 mb-1.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <p class="font-bold text-xs">Input Manual</p>
                <p class="text-[9px] text-gray-400 mt-0.5">Isi form mandiri</p>
            </Link>
        </div>

        <!-- Data Quality Warning Banner (Always visible if there are warnings) -->
        <div v-if="syncWarnings.length > 0" class="mb-5 bg-red-50 border border-red-200 rounded-2xl p-4 animate-fade-in-down">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <p class="text-xs font-bold text-red-700">Data Tidak Valid — Kualitas Sensor Rendah</p>
            </div>
            <ul class="space-y-1">
                <li v-for="(w, i) in syncWarnings" :key="i" class="text-xs text-red-600 leading-relaxed">{{ w }}</li>
            </ul>
            <p class="text-[10px] text-red-400 mt-2 font-medium">Pastikan perangkat terpasang dengan benar di pergelangan tangan dan diam saat pengukuran.</p>
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

            <!-- No data — IoMT aware empty state -->
            <div v-if="!latestRecord" class="card-VITALY p-6 sm:p-8 animate-scale-in delay-150 lg:col-span-2">

                <!-- Hint -->
                <div class="text-center">
                    <p class="text-xs text-gray-400">Atau hubungi <strong>Health Facilitator</strong> untuk membantu proses input data kesehatanmu.</p>
                </div>
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
                    <!-- Input Ulang Data — selalu terlihat, bukan hanya saat belum ada data -->
                    <Link href="/input-mandiri" class="text-xs font-medium bg-primary/10 text-primary px-3 py-1.5 rounded-lg hover:bg-primary/20 transition inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Input Data Baru
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
import { computed, h, onMounted, ref } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Tooltip, Filler } from 'chart.js';
import { getBluetoothStatus, syncVitalData, validateVitalData } from '@/Services/BluetoothService';
import axios from 'axios';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Tooltip, Filler);

const isSyncing  = ref(false);
const syncStatus = ref('');
const showBluetoothModal = ref(false);
const bluetoothMessage = ref('');
const showDemoModal = ref(false);
const syncToken = ref(0);
const SYNC_TIMEOUT_MS = 15000;

// ── Safety Disclaimer ─────────────────────────────────────────────
const DISCLAIMER_KEY = 'vitaly_disclaimer_v1';
const showDisclaimer = ref(false);

onMounted(() => {
    if (!localStorage.getItem(DISCLAIMER_KEY)) {
        // Sedikit delay agar halaman selesai render dulu
        setTimeout(() => { showDisclaimer.value = true; }, 600);
    }
});

const agreeDisclaimer = () => {
    localStorage.setItem(DISCLAIMER_KEY, '1');
    showDisclaimer.value = false;
};

// \u2500\u2500 Sync Smartwatch \u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500
const syncWarnings = ref([]);

const withTimeout = (promise, ms) => new Promise((resolve, reject) => {
    const timer = setTimeout(() => reject(new Error('timeout')), ms);
    promise
        .then((value) => {
            clearTimeout(timer);
            resolve(value);
        })
        .catch((err) => {
            clearTimeout(timer);
            reject(err);
        });
});

const doSync = async () => {
    const token = ++syncToken.value;
    isSyncing.value   = true;
    syncWarnings.value = [];
    const btStatus = await getBluetoothStatus();
    if (!btStatus.canUse) {
        bluetoothMessage.value = btStatus.message;
        showBluetoothModal.value = true;
        isSyncing.value = false;
        syncStatus.value = '';
        return;
    }
    let rawData;
    try {
        rawData = await withTimeout(
            syncVitalData((msg) => {
                if (token === syncToken.value) syncStatus.value = msg;
            }),
            SYNC_TIMEOUT_MS
        );
    } catch (err) {
        if (token !== syncToken.value) return;
        bluetoothMessage.value = err && err.message === 'timeout'
            ? 'Sinkronisasi terlalu lama. Pastikan Bluetooth aktif, perangkat dalam mode pairing, lalu coba lagi.'
            : 'Sinkronisasi gagal. Periksa Bluetooth dan coba ulang.';
        showBluetoothModal.value = true;
        isSyncing.value = false;
        syncStatus.value = '';
        return;
    }

    if (token !== syncToken.value) return;

    // User menutup dialog pilih perangkat
    if (rawData === null) {
        syncStatus.value = '';
        isSyncing.value  = false;
        return;
    }

    if (rawData) {
        // ── Perangkat nyata terhubung tapi tidak ada data GATT ────────────
        if (rawData.isLimited) {
            bluetoothMessage.value =
                `Perangkat "${rawData.deviceName}" berhasil terhubung, namun tidak dapat membaca ` +
                `data vital secara otomatis. Perangkat ini kemungkinan menggunakan protokol proprietary ` +
                `(bukan GATT standar). Silakan input data secara manual.`;
            showBluetoothModal.value = true;
            isSyncing.value = false;
            syncStatus.value = '';
            return;
        }

        // ── STEP 1: Validasi data sebelum dikirim ke backend ─────────────
        const { isValid, warnings, cleanData } = validateVitalData(rawData);

        if (!isValid) {
            // Tampilkan peringatan data kotor ke user
            syncWarnings.value = warnings;
            syncStatus.value   = '⚠️ Data tidak konsisten, mohon periksa posisi perangkat.';
            isSyncing.value    = false;
            return; // Batalkan pengiriman — jangan kirim data kotor ke AI
        }

        // ── STEP 2: Pastikan ada minimal 1 metrik yang terisi ────────────
        const hasAnyData = ['heart_rate', 'systolic', 'oxygen_saturation', 'temperature']
            .some(k => cleanData[k] != null);

        if (!hasAnyData) {
            bluetoothMessage.value = 'Perangkat terhubung namun tidak ada data vital yang berhasil dibaca. Silakan input data secara manual.';
            showBluetoothModal.value = true;
            isSyncing.value = false;
            syncStatus.value = '';
            return;
        }

        // ── STEP 3: Hanya kirim data bersih ke backend ───────────────────
        await axios.post('/input-mandiri', { ...cleanData, source: 'iomt' });
        window.location.reload();
    }

    isSyncing.value    = false;
    syncStatus.value   = '';
};

const closeBluetoothModal = () => {
    showBluetoothModal.value = false;
};

const retrySync = () => {
    showBluetoothModal.value = false;
    doSync();
};

// ── Demo IoMT — simulasi eksplisit dengan label jelas ────────────
const confirmDemo = async () => {
    showDemoModal.value = false;
    isSyncing.value     = true;
    syncWarnings.value  = [];

    // Simulasikan proses penarikan data secara bertahap
    const steps = [
        { msg: 'Menginisialisasi antarmuka IoMT demo...', delay: 600 },
        { msg: 'Mensimulasikan sinyal Bluetooth BLE...', delay: 800 },
        { msg: '✓ Membaca detak jantung...', delay: 700 },
        { msg: '✓ Membaca tekanan darah...', delay: 700 },
        { msg: '✓ Membaca SpO2 & suhu...', delay: 600 },
        { msg: '✓ Data demo berhasil digenerate!', delay: 400 },
    ];
    for (const step of steps) {
        syncStatus.value = step.msg;
        await new Promise(r => setTimeout(r, step.delay));
    }

    // Generate data realistis secara klinis (sama seperti simulateVitalData)
    const demoData = {
        heart_rate        : Math.floor(65 + Math.random() * 35),       // 65–100
        systolic          : Math.floor(110 + Math.random() * 40),       // 110–150
        diastolic         : Math.floor(70  + Math.random() * 20),       // 70–90
        oxygen_saturation : Math.floor(96  + Math.random() * 4),        // 96–100
        temperature       : parseFloat((36.2 + Math.random() * 1.0).toFixed(1)), // 36.2–37.2
        deviceName        : 'VITALY Pulse v2.0 (Demo)',
        isReal            : false,
        isLimited         : false,
    };

    // Pastikan sistolik > diastolik
    if (demoData.systolic <= demoData.diastolic) {
        demoData.diastolic = demoData.systolic - Math.floor(20 + Math.random() * 15);
    }

    // Validasi tetap dijalankan
    const { isValid, warnings, cleanData } = validateVitalData(demoData);
    if (!isValid) {
        syncWarnings.value = warnings;
        syncStatus.value   = '⚠️ Data demo tidak lolos validasi. Coba lagi.';
        isSyncing.value    = false;
        return;
    }

    // Simpan ke backend dengan source: 'demo' — jelas terlabel di DB
    await axios.post('/input-mandiri', {
        ...cleanData,
        source: 'demo',
        notes : '[Demo IoMT] Data simulasi untuk demonstrasi alur kerja sistem.',
    });
    window.location.reload();
};

const props = defineProps({
    latestRecord:   Object,
    recentRecords:  Array,
    totalRecords:   Number,
    latestAnalysis: Object,
    healthScore:    Number,
    chartData:      Array,
    deviceId:       String,
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

<style scoped>
/* Safety Modal Animation */
.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.3s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}
.modal-fade-enter-active .bg-white,
.modal-fade-leave-active .bg-white {
    transition: transform 0.3s ease;
}
.modal-fade-enter-from .bg-white {
    transform: scale(0.92) translateY(10px);
}
.modal-fade-leave-to .bg-white {
    transform: scale(0.92);
}
</style>
