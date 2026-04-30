/**
 * ╔══════════════════════════════════════════════════════════════════╗
 * ║        VITALY — BluetoothService.js  (Production-Ready)         ║
 * ║  Handles BLE device connection, pairing & data sync             ║
 * ║  Supports: Mi Band 8, Generic Heart Rate GATT devices           ║
 * ║  Fallback: Simulation mode for HTTP / Firefox / no hardware     ║
 * ╚══════════════════════════════════════════════════════════════════╝
 *
 * ⚠️  SYARAT AGAR BLUETOOTH ASLI BEKERJA:
 *   1. Aplikasi harus berjalan via HTTPS (bukan HTTP biasa)
 *   2. Browser: Google Chrome / Microsoft Edge (bukan Firefox/Safari)
 *   3. Perangkat: HP/laptop yang punya hardware Bluetooth
 *   4. Smartwatch dalam mode pairing / discoverable
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
const MI_BAND = {
    SERVICE_MAIN       : '0000fee0-0000-1000-8000-00805f9b34fb',
    SERVICE_AUTH       : '0000fee1-0000-1000-8000-00805f9b34fb',
    CHAR_AUTH          : '00000009-0000-3512-2118-0009af100700',
    CHAR_HR_CONTROL    : '00002a39-0000-1000-8000-00805f9b34fb',
    CHAR_HR_MEASUREMENT: '00002a37-0000-1000-8000-00805f9b34fb', // standard
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
        onStatusChange('Membuka pemindai Bluetooth...');

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
// SYNC DATA — Tarik data vital dari perangkat BLE
// ─────────────────────────────────────────────────────────────────
/**
 * Coba baca data vital dari smartwatch via BLE.
 * Otomatis fallback ke simulasi jika kondisi tidak memenuhi syarat.
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
        onStatusChange('Memindai perangkat BLE terdekat...');

        const device = await navigator.bluetooth.requestDevice({
            acceptAllDevices: true,
            optionalServices: [
                GATT.HEART_RATE_SERVICE,
                GATT.BLOOD_PRESSURE_SERVICE,
                GATT.BATTERY_SERVICE,
                MI_BAND.SERVICE_MAIN,
            ],
        });

        onStatusChange(`Menghubungkan ke ${device.name || 'perangkat'}...`);
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

        // ── Jika tidak ada data sama sekali → fallback simulasi ───────────
        if (!result.heart_rate && !result.systolic) {
            onStatusChange('Perangkat terhubung namun data terbatas (mode simulasi)...');
            return {
                ...(await simulateVitalData(() => {})),
                deviceName: result.deviceName,
                isReal    : false,
            };
        }

        onStatusChange(`✓ Selesai! Data vital berhasil dibaca.`);
        return result;

    } catch (err) {
        if (err.name === 'NotFoundError') {
            onStatusChange('Tidak ada perangkat dipilih. Menggunakan mode simulasi...');
        } else {
            onStatusChange(`Koneksi gagal (${err.message}). Menggunakan mode simulasi...`);
        }
        return simulateVitalData(onStatusChange);
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
            onStatusChange('✓ Perangkat demo ditemukan: VITALY Pulse v2.0');
            resolve({ deviceId, deviceName: 'VITALY Pulse v2.0 (Demo)', bluetoothDevice: null });
        }, 2000);
    });
};

const simulateVitalData = (onStatusChange) => {
    return new Promise((resolve) => {
        onStatusChange('Mensimulasikan penarikan data dari Mi Band...');
        setTimeout(() => {
            const heartRate        = Math.floor(65 + Math.random() * 50);
            const systolic         = Math.floor(110 + Math.random() * 80);
            const diastolic        = Math.floor(70  + Math.random() * 30);
            const temp             = (36.0 + Math.random() * 1.5).toFixed(1);
            const battery          = Math.floor(40 + Math.random() * 60);
            const oxygenSaturation = Math.floor(95 + Math.random() * 5); // 95–100%

            onStatusChange('✓ Data berhasil ditarik (mode simulasi)');
            resolve({
                deviceName       : 'VITALY Pulse v2.0 (Demo)',
                heart_rate       : heartRate,
                systolic         : systolic,
                diastolic        : diastolic,
                oxygen_saturation: oxygenSaturation,
                temperature      : parseFloat(temp),
                battery          : battery,
                isReal           : false,
            });
        }, 2500);
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
