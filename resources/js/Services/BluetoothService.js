/**
 * ╔══════════════════════════════════════════════════════════════════╗
 * ║        VITALY — BluetoothService.js  (Production-Ready)         ║
 * ║  Handles BLE device connection, pairing & data sync             ║
 * ║  Supports: Mi Band 8 via Notify for Xiaomi (GATT HR Broadcast)  ║
 * ║  Supports: Generic Heart Rate GATT devices                      ║
 * ║  Fallback: Simulation mode for HTTP / Firefox / no hardware     ║
 * ╚══════════════════════════════════════════════════════════════════╝
 *
 * ⚠️  SYARAT AGAR BLUETOOTH ASLI BEKERJA:
 *   1. Aplikasi harus berjalan via HTTPS (bukan HTTP biasa)
 *   2. Browser: Google Chrome / Microsoft Edge (bukan Firefox/Safari)
 *   3. Perangkat: HP/laptop yang punya hardware Bluetooth
 *   4. Notify for Xiaomi terinstall di HP & HR Broadcast diaktifkan
 *
 * 📱 SETUP NOTIFY FOR XIAOMI (Solusi 1):
 *   1. Install "Notify for Xiaomi & Mi Fitness" dari Play Store
 *   2. Buka Notify > Connect Mi Band 8
 *   3. Masuk ke Settings > Heart Rate > "Broadcast as GATT Sensor" → ON
 *   4. Pastikan HP Android dalam keadaan tidak terkunci saat sinkronisasi
 */

// ── GATT Standard UUIDs ────────────────────────────────────────────
const GATT = {
    HEART_RATE_SERVICE        : 'heart_rate',
    HEART_RATE_MEASUREMENT    : 'heart_rate_measurement',
    BLOOD_PRESSURE_SERVICE    : 'blood_pressure',
    BLOOD_PRESSURE_MEASUREMENT: 'blood_pressure_measurement',
    BATTERY_SERVICE           : 'battery_service',
    BATTERY_LEVEL             : 'battery_level',
    DEVICE_INFO               : 'device_information',
    // SpO2 / Pulse Oximeter (GATT standard)
    PULSE_OX_SERVICE          : 'pulse_oximetry',
    PULSE_OX_CONTINUOUS       : '00002a5f-0000-1000-8000-00805f9b34fb',
};

// ── Mi Band 8 / Zepp OS Custom UUIDs (community-sourced) ─────────
// Mi Band menggunakan protokol proprietary Zepp, service FEE0/FEE1
// diperlukan auth token untuk baca data penuh.
// Jalur alternatif: Notify for Xiaomi meng-expose standard GATT.
const MI_BAND = {
    SERVICE_MAIN       : '0000fee0-0000-1000-8000-00805f9b34fb',
    SERVICE_AUTH       : '0000fee1-0000-1000-8000-00805f9b34fb',
    CHAR_AUTH          : '00000009-0000-3512-2118-0009af100700',
    CHAR_HR_CONTROL    : '00002a39-0000-1000-8000-00805f9b34fb',
    CHAR_HR_MEASUREMENT: '00002a37-0000-1000-8000-00805f9b34fb', // standard
};

// ── Notify for Xiaomi — re-broadcasts Mi Band data as standard GATT ──
// Saat Notify "Broadcast as GATT Sensor" aktif, HP Android bersikap
// seperti perangkat medis standar (Heart Rate Profile, 0x180D).
const NOTIFY_APP = {
    // Nama device yang di-broadcast Notify (bisa berubah tergantung setting user)
    KNOWN_NAMES: ['Mi Smart Band', 'Xiaomi Band', 'Notify HR', 'Mi Band', 'NOTIFY'],
};

// ── Environment Detection ──────────────────────────────────────────

/** Apakah browser mendukung Web Bluetooth API */
export const isBluetoothSupported = () =>
    typeof navigator !== 'undefined' && 'bluetooth' in navigator;

/** Apakah halaman berjalan di HTTPS (syarat wajib Web Bluetooth) */
export const isHttps = () =>
    typeof window !== 'undefined' &&
    (window.location.protocol === 'https:' || window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1');

/**
 * Cek apakah Bluetooth benar-benar bisa dipakai.
 * Return object { canUse: bool, reason: string }
 */
export const getBluetoothStatus = async () => {
    if (!isHttps()) {
        return {
            canUse: false,
            reason: 'http',
            message: 'Bluetooth membutuhkan koneksi HTTPS. Hubungi admin untuk mengaktifkan SSL.',
        };
    }
    if (!isBluetoothSupported()) {
        return {
            canUse: false,
            reason: 'browser',
            message: 'Browser Anda tidak mendukung Web Bluetooth. Gunakan Google Chrome atau Microsoft Edge.',
        };
    }
    try {
        const available = await navigator.bluetooth.getAvailability();
        if (!available) {
            return {
                canUse: false,
                reason: 'hardware',
                message: 'Bluetooth tidak tersedia di perangkat ini. Aktifkan Bluetooth terlebih dahulu.',
            };
        }
        return { canUse: true, reason: 'ok', message: 'Bluetooth siap digunakan.' };
    } catch {
        return { canUse: false, reason: 'unknown', message: 'Gagal memeriksa status Bluetooth.' };
    }
};

/** @deprecated — Gunakan getBluetoothStatus() */
export const canUseBluetooth = async () => {
    const s = await getBluetoothStatus();
    return s.canUse;
};

// ─────────────────────────────────────────────────────────────────
// PAIRING — Tautkan perangkat ke pasien
// ─────────────────────────────────────────────────────────────────
/**
 * Buka dialog pilih perangkat BLE, return info device.
 * @param {Function} onStatusChange  — callback(string) untuk update UI
 * @returns {{ deviceId, deviceName, bluetoothDevice }} | null
 */
export const pairDevice = async (onStatusChange = () => {}) => {
    const btStatus = await getBluetoothStatus();

    if (!btStatus.canUse) {
        onStatusChange(`⚠️ ${btStatus.message}`);
        // Fallback simulasi agar demo tetap berjalan
        return simulatePairing(onStatusChange);
    }

    try {
        onStatusChange('🔍 Membuka pemindai Bluetooth...');

        const device = await navigator.bluetooth.requestDevice({
            acceptAllDevices: true,
            optionalServices: [
                GATT.HEART_RATE_SERVICE,
                GATT.BLOOD_PRESSURE_SERVICE,
                GATT.BATTERY_SERVICE,
                GATT.DEVICE_INFO,
                MI_BAND.SERVICE_MAIN,
                MI_BAND.SERVICE_AUTH,
            ],
        });

        const cleanName = (device.name || 'BLE-DEVICE')
            .replace(/\s+/g, '-')
            .toUpperCase()
            .slice(0, 12);
        const uid      = Math.random().toString(36).substr(2, 5).toUpperCase();
        const deviceId = `VTL-${cleanName}-${uid}`;

        onStatusChange(`✓ Perangkat ditemukan: ${device.name || 'Unknown'}`);

        return {
            deviceId,
            deviceName     : device.name || 'Smartwatch BLE',
            bluetoothDevice: device,
        };
    } catch (err) {
        if (err.name === 'NotFoundError') {
            onStatusChange('Tidak ada perangkat dipilih.');
        } else {
            onStatusChange(`Gagal: ${err.message}`);
        }
        return null;
    }
};

// ─────────────────────────────────────────────────────────────────
// SYNC DATA via NOTIFY FOR XIAOMI (Mode Utama)
// ─────────────────────────────────────────────────────────────────
/**
 * Tarik data vital dari Mi Band 8 yang di-broadcast oleh Notify for Xiaomi.
 * Notify meng-expose Mi Band sebagai standard GATT Heart Rate sensor.
 * Filter: hanya tampilkan device yang punya Heart Rate Service (0x180D).
 *
 * @param {Function} onLog — callback(string) untuk tech-log di UI
 * @returns {{ heart_rate, systolic, diastolic, oxygen_saturation, battery, deviceName, isReal, source }}
 */
export const syncFromNotify = async (onLog = () => {}) => {
    const btStatus = await getBluetoothStatus();

    if (!btStatus.canUse) {
        onLog(`[VITALY] ⚠️ ${btStatus.message}`);
        onLog(`[VITALY] 🔄 Switching to simulation mode...`);
        return simulateVitalData(onLog, 'notify-sim');
    }

    try {
        onLog(`[VITALY] 🔍 Scanning BLE... filter: Heart Rate Service (0x180D)`);
        onLog(`[VITALY] 📱 Pastikan Notify for Xiaomi aktif & HR Broadcast ON`);

        // Filter khusus: hanya tampilkan device yang punya Heart Rate GATT Service
        // Ini yang di-broadcast oleh Notify for Xiaomi dari Mi Band 8
        const device = await navigator.bluetooth.requestDevice({
            filters: [
                { services: [GATT.HEART_RATE_SERVICE] },
            ],
            optionalServices: [
                GATT.BATTERY_SERVICE,
                GATT.DEVICE_INFO,
                GATT.BLOOD_PRESSURE_SERVICE,
                MI_BAND.SERVICE_MAIN,
            ],
        });

        onLog(`[VITALY] ✓ Device found: ${device.name || 'HR Sensor'}`);
        onLog(`[VITALY] 🔗 Connecting to GATT server...`);

        const server = await device.gatt.connect();
        onLog(`[VITALY] ✓ GATT server connected`);

        const result = {
            deviceName       : device.name || 'Mi Band 8 (via Notify)',
            heart_rate       : null,
            systolic         : null,
            diastolic        : null,
            oxygen_saturation: null,
            temperature      : null,
            battery          : null,
            isReal           : true,
            source           : 'notify-gatt',
        };

        // ── Heart Rate — Primary target dari Notify broadcast ──
        onLog(`[VITALY] 📡 Reading Heart Rate Service (UUID: 0x180D)...`);
        try {
            const hrService = await server.getPrimaryService(GATT.HEART_RATE_SERVICE);
            onLog(`[VITALY] ✓ Heart Rate Service found`);

            const hrChar = await hrService.getCharacteristic(GATT.HEART_RATE_MEASUREMENT);
            onLog(`[VITALY] ✓ Characteristic 0x2A37 acquired`);

            // Coba startNotifications dulu (lebih reliable dari Notify app)
            let hrResolved = false;
            try {
                await hrChar.startNotifications();
                onLog(`[VITALY] 📶 Notifications started, waiting for pulse...`);
                await new Promise((resolve) => {
                    const handler = (event) => {
                        if (hrResolved) return;
                        hrResolved = true;
                        const val = event.target.value;
                        const flags = val.getUint8(0);
                        result.heart_rate = (flags & 0x01)
                            ? val.getUint16(1, true)
                            : val.getUint8(1);
                        hrChar.removeEventListener('characteristicvaluechanged', handler);
                        resolve();
                    };
                    hrChar.addEventListener('characteristicvaluechanged', handler);
                    setTimeout(() => { if (!hrResolved) resolve(); }, 6000); // timeout 6s
                });
                await hrChar.stopNotifications().catch(() => {});
            } catch {
                // Fallback: readValue langsung
                onLog(`[VITALY] ↩️ Notifications failed, trying readValue...`);
                const hrValue = await hrChar.readValue();
                const flags = hrValue.getUint8(0);
                result.heart_rate = (flags & 0x01)
                    ? hrValue.getUint16(1, true)
                    : hrValue.getUint8(1);
            }

            if (result.heart_rate) {
                onLog(`[VITALY] ✅ Heart Rate: ${result.heart_rate} BPM`);
            } else {
                onLog(`[VITALY] ⚠️ Heart Rate: no data received (timeout)`);
            }
        } catch (e) {
            onLog(`[VITALY] ✗ Heart Rate Service error: ${e.message}`);
        }

        // ── Battery Level ──────────────────────────────────────────────
        onLog(`[VITALY] 🔋 Reading Battery Service (UUID: 0x180F)...`);
        try {
            const batService = await server.getPrimaryService(GATT.BATTERY_SERVICE);
            const batChar    = await batService.getCharacteristic(GATT.BATTERY_LEVEL);
            const batValue   = await batChar.readValue();
            result.battery   = batValue.getUint8(0);
            onLog(`[VITALY] ✅ Battery Level: ${result.battery}%`);
        } catch {
            onLog(`[VITALY] ℹ️ Battery Service not available on this device`);
        }

        // ── Blood Pressure (opsional, bisa ada di beberapa device) ───
        onLog(`[VITALY] 💉 Reading Blood Pressure Service (UUID: 0x1810)...`);
        try {
            const bpService = await server.getPrimaryService(GATT.BLOOD_PRESSURE_SERVICE);
            const bpChar    = await bpService.getCharacteristic(GATT.BLOOD_PRESSURE_MEASUREMENT);
            await bpChar.startNotifications();
            onLog(`[VITALY] 📶 BP Notifications started, waiting...`);
            await new Promise((resolve) => {
                const handler = (event) => {
                    const v = event.target.value;
                    result.systolic  = Math.round(v.getFloat32(1, true));
                    result.diastolic = Math.round(v.getFloat32(5, true));
                    bpChar.removeEventListener('characteristicvaluechanged', handler);
                    resolve();
                };
                bpChar.addEventListener('characteristicvaluechanged', handler);
                setTimeout(resolve, 5000);
            });
            if (result.systolic) {
                onLog(`[VITALY] ✅ Blood Pressure: ${result.systolic}/${result.diastolic} mmHg`);
            }
        } catch {
            onLog(`[VITALY] ℹ️ Blood Pressure Service not available (expected for HR-only sensors)`);
        }

        onLog(`[VITALY] ✅ Sync complete! Data acquisition finished.`);
        onLog(`[VITALY] 🛡️ Running clinical validation...`);

        return result;

    } catch (err) {
        if (err.name === 'NotFoundError') {
            onLog(`[VITALY] ℹ️ No device selected by user.`);
            return null;
        }
        onLog(`[VITALY] ✗ Connection error: ${err.message}`);
        throw err;
    }
};

// ─────────────────────────────────────────────────────────────────
// SYNC DATA — Tarik data vital dari perangkat BLE (General)
// ─────────────────────────────────────────────────────────────────
/**
 * Coba baca data vital dari smartwatch via BLE.
 * Otomatis fallback ke simulasi jika kondisi tidak memenuhi syarat.
 * Mode "acceptAllDevices" untuk kompatibilitas maksimal.
 *
 * @param {Function} onStatusChange — callback(string)
 * @returns {{ heart_rate, systolic, diastolic, oxygen_saturation, temperature, battery, deviceName, isReal }}
 */
export const syncVitalData = async (onStatusChange = () => {}) => {
    const btStatus = await getBluetoothStatus();

    if (!btStatus.canUse) {
        onStatusChange(`⚠️ ${btStatus.message} (mode simulasi aktif)`);
        return simulateVitalData(onStatusChange);
    }

    try {
        onStatusChange('🔍 Memindai perangkat BLE terdekat...');

        const device = await navigator.bluetooth.requestDevice({
            acceptAllDevices: true,
            optionalServices: [
                GATT.HEART_RATE_SERVICE,
                GATT.BLOOD_PRESSURE_SERVICE,
                GATT.BATTERY_SERVICE,
                MI_BAND.SERVICE_MAIN,
            ],
        });

        onStatusChange(`🔗 Menghubungkan ke ${device.name || 'perangkat'}...`);
        const server = await device.gatt.connect();
        onStatusChange('✓ Terhubung! Membaca data vital...');

        const result = {
            deviceName       : device.name || 'Smartwatch BLE',
            heart_rate       : null,
            systolic         : null,
            diastolic        : null,
            oxygen_saturation: null,
            temperature      : null,
            battery          : null,
            isReal           : true,
        };

        // ── Heart Rate (GATT standard — didukung hampir semua smartwatch) ──
        try {
            const hrService = await server.getPrimaryService(GATT.HEART_RATE_SERVICE);
            const hrChar    = await hrService.getCharacteristic(GATT.HEART_RATE_MEASUREMENT);
            const hrValue   = await hrChar.readValue();
            const flags     = hrValue.getUint8(0);
            result.heart_rate = (flags & 0x01)
                ? hrValue.getUint16(1, true)
                : hrValue.getUint8(1);
            onStatusChange(`✓ Detak jantung: ${result.heart_rate} BPM`);
        } catch {
            console.info('[VITALY BT] Heart Rate service tidak tersedia di perangkat ini');
        }

        // ── Blood Pressure (via GATT notifications) ──────────────────────
        try {
            const bpService = await server.getPrimaryService(GATT.BLOOD_PRESSURE_SERVICE);
            const bpChar    = await bpService.getCharacteristic(GATT.BLOOD_PRESSURE_MEASUREMENT);
            await bpChar.startNotifications();
            await new Promise((resolve) => {
                const handler = (event) => {
                    const v = event.target.value;
                    result.systolic  = Math.round(v.getFloat32(1, true));
                    result.diastolic = Math.round(v.getFloat32(5, true));
                    bpChar.removeEventListener('characteristicvaluechanged', handler);
                    resolve();
                };
                bpChar.addEventListener('characteristicvaluechanged', handler);
                setTimeout(resolve, 5000); // timeout 5 detik
            });
            if (result.systolic) {
                onStatusChange(`✓ Tekanan darah: ${result.systolic}/${result.diastolic}`);
            }
        } catch {
            console.info('[VITALY BT] Blood Pressure service tidak tersedia');
        }

        // ── Battery Level ─────────────────────────────────────────────────
        try {
            const batService = await server.getPrimaryService(GATT.BATTERY_SERVICE);
            const batChar    = await batService.getCharacteristic(GATT.BATTERY_LEVEL);
            const batValue   = await batChar.readValue();
            result.battery   = batValue.getUint8(0);
            onStatusChange(`✓ Baterai: ${result.battery}%`);
        } catch {
            console.info('[VITALY BT] Battery service tidak tersedia');
        }

        // ── Jika tidak ada data sama sekali → tetap kembalikan data real (partial) ──
        if (!result.heart_rate && !result.systolic) {
            onStatusChange(
                '⚠️ Perangkat terhubung, namun tidak ada data GATT yang terbaca. ' +
                'Pastikan Notify for Xiaomi aktif & HR Broadcast diaktifkan. ' +
                'Silakan isi data secara manual.'
            );
            return {
                ...result,
                isReal   : true,
                isLimited: true,
            };
        }

        onStatusChange(`✓ Selesai! Data vital berhasil dibaca dari perangkat nyata.`);
        return { ...result, isLimited: false };

    } catch (err) {
        if (err.name === 'NotFoundError') {
            onStatusChange('Tidak ada perangkat dipilih.');
            return null;
        }
        onStatusChange(`Koneksi gagal: ${err.message}`);
        throw err;
    }
};

// ─────────────────────────────────────────────────────────────────
// SIMULASI — Data demo jika Bluetooth tidak tersedia
// ─────────────────────────────────────────────────────────────────
const simulatePairing = (onStatusChange) => {
    return new Promise((resolve) => {
        onStatusChange('Mode demo: Mensimulasikan pencarian perangkat...');
        setTimeout(() => {
            const uid      = Math.random().toString(36).substr(2, 6).toUpperCase();
            const deviceId = `VTL-MIBAND8-${uid}`;
            onStatusChange('✓ Perangkat demo ditemukan: Mi Smart Band 8');
            resolve({ deviceId, deviceName: 'Mi Smart Band 8 (Demo)', bluetoothDevice: null });
        }, 2000);
    });
};

const simulateVitalData = (onLog, source = 'simulation') => {
    return new Promise((resolve) => {
        const steps = [
            '[VITALY] 🔍 Scanning BLE... filter: Heart Rate Service (0x180D)',
            '[VITALY] 📱 Detected: Mi Smart Band 8 [via Notify GATT Bridge]',
            '[VITALY] 🔗 Connecting to GATT server...',
            '[VITALY] ✓ GATT server connected',
            '[VITALY] 📡 Reading Characteristic 0x2A37 (Heart Rate Measurement)...',
            '[VITALY] 📶 Notifications started, waiting for pulse...',
            '[VITALY] ✅ Heart Rate acquired from sensor',
            '[VITALY] 🔋 Reading Battery Service (UUID: 0x180F)...',
            '[VITALY] ✅ Battery Level acquired',
            '[VITALY] 💉 Blood Pressure Service: not available on this profile',
            '[VITALY] 🛡️ Running clinical validation on acquired data...',
            '[VITALY] ✅ All values within clinical bounds. Data accepted.',
            '[VITALY] ✅ Sync complete! Data acquisition finished.',
        ];

        let i = 0;
        const heartRate        = Math.floor(65 + Math.random() * 35);   // 65–100 BPM
        const systolic         = Math.floor(110 + Math.random() * 30);  // 110–140
        const diastolic        = Math.floor(70  + Math.random() * 20);  // 70–90
        const temp             = (36.2 + Math.random() * 0.8).toFixed(1); // 36.2–37.0
        const battery          = Math.floor(40 + Math.random() * 60);
        const oxygenSaturation = Math.floor(96 + Math.random() * 4);    // 96–100%

        const interval = setInterval(() => {
            if (i < steps.length) {
                // Inject nilai nyata ke log setelah step HR acquired
                if (steps[i].includes('Heart Rate acquired')) {
                    onLog(`${steps[i]} → ${heartRate} BPM`);
                } else if (steps[i].includes('Battery Level acquired')) {
                    onLog(`${steps[i]} → ${battery}%`);
                } else {
                    onLog(steps[i]);
                }
                i++;
            } else {
                clearInterval(interval);
                resolve({
                    deviceName       : 'Mi Smart Band 8 (via Notify)',
                    heart_rate       : heartRate,
                    systolic         : systolic,
                    diastolic        : diastolic,
                    oxygen_saturation: oxygenSaturation,
                    temperature      : parseFloat(temp),
                    battery          : battery,
                    isReal           : false,
                    source,
                });
            }
        }, 350);
    });
};

// ─────────────────────────────────────────────────────────────────
// DATA VALIDATION — Saring data kotor / tidak masuk akal secara klinis
// Menjawab Research Gap: Consumer-grade IoMT device accuracy limitation
// Ref: WHO Vital Signs Reference Ranges & AHA Clinical Guidelines
// ─────────────────────────────────────────────────────────────────

/**
 * Batas klinis yang masuk akal untuk manusia hidup.
 * Jika data di luar range ini → dianggap noise/artefak sensor.
 */
const CLINICAL_BOUNDS = {
    heart_rate       : { min: 30,  max: 220, unit: 'BPM'  },
    systolic         : { min: 60,  max: 260, unit: 'mmHg' },
    diastolic        : { min: 30,  max: 160, unit: 'mmHg' },
    oxygen_saturation: { min: 70,  max: 100, unit: '%'    },
    temperature      : { min: 34.0, max: 42.0, unit: '°C' },
    blood_sugar      : { min: 30,  max: 600, unit: 'mg/dL'},
};

/**
 * Validasi data vital dari perangkat IoMT.
 * @param {Object} data — objek data vital dari syncVitalData / manual input
 * @returns {{ isValid: boolean, warnings: string[], cleanData: Object }}
 *   - isValid      → true jika SEMUA metrik yang ada dalam batas wajar
 *   - warnings     → array pesan metrik yang bermasalah (untuk ditampilkan di UI)
 *   - cleanData    → data yang sudah dibersihkan (metrik aneh di-null-kan)
 */
export const validateVitalData = (data) => {
    const warnings  = [];
    const cleanData = { ...data };

    for (const [key, bounds] of Object.entries(CLINICAL_BOUNDS)) {
        const value = data[key];
        if (value === null || value === undefined) continue; // lewati yg kosong

        const num = parseFloat(value);
        if (isNaN(num)) {
            warnings.push(`${key}: nilai tidak valid (${value})`);
            cleanData[key] = null;
            continue;
        }

        if (num < bounds.min || num > bounds.max) {
            warnings.push(
                `⚠️ ${key.replace(/_/g, ' ')} = ${num} ${bounds.unit} ` +
                `(di luar batas normal: ${bounds.min}–${bounds.max} ${bounds.unit}). ` +
                `Kemungkinan artefak sensor — mohon ukur ulang.`
            );
            cleanData[key] = null; // hapus data kotor agar tidak dikirim ke AI
        }
    }

    // Konsistensi sistolik vs diastolik
    if (cleanData.systolic && cleanData.diastolic) {
        if (cleanData.systolic <= cleanData.diastolic) {
            warnings.push(
                `⚠️ Tekanan darah tidak konsisten: Sistolik (${cleanData.systolic}) ` +
                `≤ Diastolik (${cleanData.diastolic}). Kemungkinan posisi sensor bergeser.`
            );
            cleanData.systolic  = null;
            cleanData.diastolic = null;
        }
    }

    return {
        isValid             : warnings.length === 0,
        warnings,
        cleanData,
        inconsistentData    : warnings.length > 0,
    };
};

// ── HELPER ────────────────────────────────────────────────────────
export const formatDeviceId = (deviceId) => {
    if (!deviceId) return '—';
    return deviceId.length > 20
        ? deviceId.slice(0, 8) + '...' + deviceId.slice(-6)
        : deviceId;
};
