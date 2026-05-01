<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * OllamaService
 *
 * Memanggil Ollama API secara langsung dari PHP tanpa perantara Python.
 * Endpoint Ollama: http://localhost:11434/api/chat
 */
class OllamaService
{
    protected string $ollamaUrl;
    protected string $model;
    protected int    $timeout;
    protected int    $numCtx;

    public function __construct()
    {
        $this->ollamaUrl = rtrim(env('OLLAMA_URL', 'http://localhost:11434'), '/');
        $this->model     = env('OLLAMA_MODEL', 'qwen2.5:3b');
        $this->timeout   = (int) env('OLLAMA_TIMEOUT', 45);
        $this->numCtx    = (int) env('OLLAMA_NUM_CTX', 2048);
    }

    // -----------------------------------------------------------------------
    // Public API
    // -----------------------------------------------------------------------

    /**
     * Analisis data kesehatan pasien menggunakan Ollama.
     */
    public function analyzeHealth(array $userData, array $records): string
    {
        $prompt = $this->buildAnalysisPrompt($userData, $records);

        $result = $this->callOllama(
            messages: [['role' => 'user', 'content' => $prompt]],
            systemPrompt: $this->analysisSystemPrompt(),
            timeout: 60,
        );

        return $result ?? $this->generateFallbackAnalysis($userData, $records);
    }

    /**
     * Chat multi-turn dengan Ollama.
     *
     * @param  array  $history  Array of ['role' => 'user'|'model', 'text' => '...']
     */
    public function chat(array $history, string $systemPrompt): string
    {
        $messages = collect($history)->map(fn($m) => [
            'role'    => $m['role'] === 'model' ? 'assistant' : 'user',
            'content' => $m['text'],
        ])->all();

        return $this->callOllama($messages, $systemPrompt)
            ?? $this->chatFallback($history);
    }

    // -----------------------------------------------------------------------
    // Ollama HTTP
    // -----------------------------------------------------------------------

    private function callOllama(array $messages, string $systemPrompt, ?int $timeout = null): ?string
    {
        $payload = [
            'model'    => $this->model,
            'messages' => array_merge(
                [['role' => 'system', 'content' => $systemPrompt]],
                $messages
            ),
            'stream'  => false,
            'options' => [
                'temperature' => 0.4,
                'num_ctx'     => $this->numCtx,
            ],
        ];

        try {
            $response = Http::timeout($timeout ?? $this->timeout)
                ->post("{$this->ollamaUrl}/api/chat", $payload);

            if ($response->successful()) {
                return $response->json('message.content') ?: null;
            }

            Log::warning('Ollama API non-2xx: ' . $response->status() . ' ' . $response->body());
        } catch (\Exception $e) {
            Log::warning('Ollama unreachable: ' . $e->getMessage());
        }

        return null;
    }

    // -----------------------------------------------------------------------
    // System prompts
    // -----------------------------------------------------------------------

    private function analysisSystemPrompt(): string
    {
        return <<<'PROMPT'
Kamu adalah analis kesehatan berbasis data VITALY.

Aturan:
- Gunakan Bahasa Indonesia yang jelas dan profesional.
- Jangan mendiagnosis penyakit secara pasti.
- Jika ada indikasi serius, anjurkan konsultasi dokter secara wajar.
- Ikuti format yang diminta pengguna.
- JANGAN PERNAH menyebutkan bahwa kamu adalah AI, LLM, atau menyebutkan model tertentu seperti Ollama, Qwen, dll.
PROMPT;
    }

    // -----------------------------------------------------------------------
    // Prompt builder untuk /analyze
    // -----------------------------------------------------------------------

    private function buildAnalysisPrompt(array $userData, array $records): string
    {
        $gender = ($userData['gender'] ?? 'male') === 'male' ? 'Laki-laki' : 'Perempuan';
        $age    = isset($userData['age']) ? "{$userData['age']} tahun" : 'tidak diketahui';

        $lines = [
            'Buat laporan analisis kesehatan dalam Bahasa Indonesia.',
            'Format wajib:',
            '1. Ringkasan Kesehatan',
            '2. Analisis Detail Parameter',
            '3. Rekomendasi Gaya Hidup',
            '4. Kesimpulan',
            '',
            "Data pasien: Nama {$userData['name']}, Jenis kelamin {$gender}, Usia {$age}.",
            '',
            'Data pengukuran:',
        ];

        foreach ($records as $i => $r) {
            $parts = [];
            if (!empty($r['systolic']) && !empty($r['diastolic']))
                $parts[] = "Tekanan darah {$r['systolic']}/{$r['diastolic']} mmHg";
            if (!empty($r['heart_rate']))
                $parts[] = "Detak jantung {$r['heart_rate']} bpm";
            if (!empty($r['blood_sugar']))
                $parts[] = "Gula darah {$r['blood_sugar']} mg/dL";
            if (!empty($r['weight']) && !empty($r['height'])) {
                $bmi     = round($r['weight'] / (($r['height'] / 100) ** 2), 1);
                $parts[] = "BB {$r['weight']} kg, TB {$r['height']} cm (IMT {$bmi})";
            }
            if (!empty($r['temperature']))
                $parts[] = "Suhu {$r['temperature']}°C";
            if (!empty($r['oxygen_saturation']))
                $parts[] = "SpO2 {$r['oxygen_saturation']}%";

            $date    = $r['recorded_at'] ?? 'tanggal tidak diketahui';
            $lines[] = 'Pengukuran ' . ($i + 1) . " ({$date}): " . (count($parts) ? implode('; ', $parts) : 'data tidak tersedia');
        }

        return implode("\n", $lines);
    }

    // -----------------------------------------------------------------------
    // Fallback rule-based — ANALISIS
    // -----------------------------------------------------------------------

    public function generateFallbackAnalysis(?array $userData, ?array $records): string
    {
        if (!$records || count($records) === 0) {
            return 'Belum ada data kesehatan yang bisa dianalisis. Silakan input data terlebih dahulu melalui menu Input Data.';
        }

        $name          = explode(' ', $userData['name'] ?? 'kamu')[0];
        $gender        = $userData['gender'] ?? 'male';
        $age           = isset($userData['age']) && is_numeric($userData['age']) ? (int) $userData['age'] : null;
        $latest        = $records[0];
        $prev          = $records[1] ?? null;
        $fragments     = [];
        $recs          = [];
        $riskScore     = 0;
        $combinedRisks = [];

        // 1. TEKANAN DARAH
        if (!empty($latest['systolic']) && !empty($latest['diastolic'])) {
            $sys   = $latest['systolic'];
            $dia   = $latest['diastolic'];
            $trend = '';
            if ($prev && !empty($prev['systolic'])) {
                $diff  = $sys - $prev['systolic'];
                $trend = $diff > 5 ? " (naik {$diff} poin)" : ($diff < -5 ? ' (turun ' . abs($diff) . ' poin, membaik)' : ' (stabil)');
            }
            if ($sys >= 180 || $dia >= 110) { $cat = 'Hipertensi Derajat 3'; $riskScore += 4; $combinedRisks[] = 'hipertensi'; }
            elseif ($sys >= 160 || $dia >= 100) { $cat = 'Hipertensi Derajat 2'; $riskScore += 3; $combinedRisks[] = 'hipertensi'; }
            elseif ($sys >= 140 && $dia < 90)   { $cat = 'Hipertensi Sistolik'; $riskScore += 2; $combinedRisks[] = 'hipertensi'; }
            elseif ($sys >= 140 || $dia >= 90)  { $cat = 'Hipertensi Derajat 1'; $riskScore += 2; $combinedRisks[] = 'hipertensi'; }
            elseif ($sys >= 130 || $dia >= 85)  { $cat = 'Normal-tinggi'; $riskScore += 1; }
            elseif ($sys >= 120 || $dia >= 80)  { $cat = 'Normal'; }
            else                                 { $cat = 'Optimal'; }

            if (str_contains($cat, 'Hipertensi')) {
                $fragments[] = "- **Tekanan Darah ({$sys}/{$dia} mmHg){$trend}:** {$cat}. Berisiko tinggi.";
                $recs[]      = 'Segera konsultasi ke dokter. Kurangi garam (<5g/hari) dan hindari stres.';
            } elseif ($cat === 'Normal-tinggi') {
                $fragments[] = "- **Tekanan Darah ({$sys}/{$dia} mmHg){$trend}:** {$cat}. Perlu diwaspadai.";
                $recs[]      = 'Kurangi garam, batasi kafein, olahraga ringan 30 menit/hari.';
            } else {
                $fragments[] = "- **Tekanan Darah ({$sys}/{$dia} mmHg){$trend}:** {$cat}.";
                $recs[]      = 'Pertahankan pola hidup sehat rendah garam dan aktif bergerak.';
            }
        }

        // 2. GULA DARAH
        if (!empty($latest['blood_sugar'])) {
            $bs    = $latest['blood_sugar'];
            $trend = '';
            if ($prev && !empty($prev['blood_sugar'])) {
                $diff  = $bs - $prev['blood_sugar'];
                $trend = $diff > 10 ? " (naik {$diff} mg/dL)" : ($diff < -10 ? ' (turun ' . abs($diff) . ' mg/dL, membaik)' : ' (stabil)');
            }
            if ($bs < 100)      { $fragments[] = "- **Gula Darah ({$bs} mg/dL){$trend}:** Normal."; $recs[] = 'Batasi gula tambahan dan minuman manis.'; }
            elseif ($bs < 126)  { $fragments[] = "- **Gula Darah ({$bs} mg/dL){$trend}:** Pradiabetes. Sinyal awal yang harus diwaspadai."; $recs[] = 'Kurangi karbohidrat sederhana, perbanyak serat, cek darah ulang 3 bulan.'; $riskScore += 2; $combinedRisks[] = 'pradiabetes'; }
            else                { $fragments[] = "- **Gula Darah ({$bs} mg/dL){$trend}:** Kategori Diabetes. Butuh penanganan medis."; $recs[] = 'Konsultasi dokter untuk penanganan diabetes. Mulai konsumsi makanan indeks glikemik rendah.'; $riskScore += 4; $combinedRisks[] = 'diabetes'; }
        }

        // 3. DETAK JANTUNG
        if (!empty($latest['heart_rate'])) {
            $hr = $latest['heart_rate'];
            if ($hr >= 60 && $hr <= 100) { $fragments[] = "- **Detak Jantung ({$hr} bpm):** Normal dan stabil."; }
            elseif ($hr < 60)  { $note = ($age && $age < 40 && $gender === 'male') ? ' Normal untuk atlet aktif.' : ' Perlu dipantau, bisa indikasi bradikardia.'; $fragments[] = "- **Detak Jantung ({$hr} bpm):** Di bawah normal.{$note}"; if (!($age && $age < 40 && $gender === 'male')) { $riskScore += 1; } }
            else               { $fragments[] = "- **Detak Jantung ({$hr} bpm):** Di atas normal (Takikardia)."; $recs[] = 'Cukupi cairan 2L/hari, kurangi kafein, lakukan relaksasi pernapasan.'; $riskScore += 2; }
        }

        // 4. IMT
        if (!empty($latest['weight']) && !empty($latest['height'])) {
            $bmi = round($latest['weight'] / pow($latest['height'] / 100, 2), 1);
            if ($bmi < 18.5)      { $fragments[] = "- **IMT ({$bmi}):** Underweight."; $recs[] = 'Tingkatkan asupan protein dan kalori berkualitas.'; $riskScore += 1; }
            elseif ($bmi <= 22.9) { $fragments[] = "- **IMT ({$bmi}):** Berat badan normal."; }
            elseif ($bmi <= 24.9) { $fragments[] = "- **IMT ({$bmi}):** Overweight. Perlu kontrol kalori."; $recs[] = 'Kurangi porsi makan berlebih, mulai olahraga rutin.'; $riskScore += 1; $combinedRisks[] = 'overweight'; }
            elseif ($bmi <= 29.9) { $fragments[] = "- **IMT ({$bmi}):** Obes I. Meningkatkan risiko jantung dan diabetes."; $recs[] = 'Defisit kalori sedang + perbanyak protein dan serat.'; $riskScore += 2; $combinedRisks[] = 'obesitas'; }
            else                  { $fragments[] = "- **IMT ({$bmi}):** Obes II. Risiko komplikasi sangat tinggi."; $recs[] = 'Konsultasi dokter/ahli gizi untuk program penurunan berat badan.'; $riskScore += 3; $combinedRisks[] = 'obesitas'; }
        }

        // 5. SUHU
        if (!empty($latest['temperature'])) {
            $temp = $latest['temperature'];
            if ($temp < 36.0)      $fragments[] = "- **Suhu ({$temp}°C):** Sedikit di bawah normal.";
            elseif ($temp <= 37.5) $fragments[] = "- **Suhu ({$temp}°C):** Normal.";
            elseif ($temp <= 38.4) { $fragments[] = "- **Suhu ({$temp}°C):** Demam ringan."; $recs[] = 'Istirahat, banyak minum, kompres hangat, paracetamol jika perlu.'; $riskScore += 1; }
            else                   { $fragments[] = "- **Suhu ({$temp}°C):** Demam tinggi! Segera tangani."; $recs[] = 'Segera ke fasilitas kesehatan jika tidak membaik dalam 2 hari.'; $riskScore += 2; }
        }

        // 6. SpO2
        if (!empty($latest['oxygen_saturation'])) {
            $spo2 = $latest['oxygen_saturation'];
            if ($spo2 >= 95)      $fragments[] = "- **SpO2 ({$spo2}%):** Normal dan aman.";
            elseif ($spo2 >= 90)  { $fragments[] = "- **SpO2 ({$spo2}%):** Hipoksia ringan. Perlu diperhatikan."; $recs[] = 'Latihan pernapasan dalam. Jika sesak, segera ke dokter.'; $riskScore += 2; }
            else                  { $fragments[] = "- **SpO2 ({$spo2}%):** Sangat rendah — darurat!"; $recs[] = 'Segera cari bantuan medis. SpO2 <90% butuh oksigen segera.'; $riskScore += 5; }
        }

        // Risiko kombinasi
        $comboNotes = [];
        if (in_array('hipertensi', $combinedRisks) && (in_array('diabetes', $combinedRisks) || in_array('pradiabetes', $combinedRisks))) { $comboNotes[] = '**Perhatian:** Kombinasi hipertensi + gula darah tinggi meningkatkan risiko jantung dan ginjal. Evaluasi menyeluruh sangat disarankan.'; $riskScore += 2; }
        if (in_array('obesitas', $combinedRisks) && in_array('hipertensi', $combinedRisks))   { $comboNotes[] = '**Perhatian:** Obesitas + hipertensi = faktor risiko utama stroke. Turun 5-10% BB sudah signifikan.'; $riskScore += 2; }
        if (in_array('obesitas', $combinedRisks) && (in_array('diabetes', $combinedRisks) || in_array('pradiabetes', $combinedRisks))) { $comboNotes[] = '**Perhatian:** Obesitas + gula darah tinggi = sindrom metabolik. Perubahan gaya hidup agresif sangat krusial.'; $riskScore += 2; }

        if ($riskScore === 0)       $riskLabel = 'Sangat Baik';
        elseif ($riskScore <= 2)    $riskLabel = 'Baik (ada beberapa hal yang perlu dijaga)';
        elseif ($riskScore <= 5)    $riskLabel = 'Perlu Perhatian';
        elseif ($riskScore <= 9)    $riskLabel = 'Berisiko Tinggi';
        else                        $riskLabel = 'Kritis - Segera Tanggap';

        $personalNote = '';
        if ($age && $age >= 40 && in_array('hipertensi', $combinedRisks))
            $personalNote = "\n\nUsia 40+ dengan hipertensi: risiko stroke dan serangan jantung meningkat 2-3x. EKG + profil lipid tahunan direkomendasikan.";
        elseif ($gender === 'female' && $age && $age >= 45 && !empty($latest['systolic']))
            $personalNote = "\n\nWanita perimenopause (45+) berisiko lebih tinggi pada kardiovaskular. Pantau tekanan darah secara rutin.";

        $analisaTeks     = count($fragments) > 0 ? implode("\n", $fragments) : '- Data belum cukup lengkap untuk dianalisis rinci.';
        $rekomendasiTeks = count($recs) > 0
            ? implode("\n", array_map(fn ($i, $r) => ($i + 1) . '. ' . $r, array_keys($recs), $recs))
            : 'Pertahankan pola hidup sehat: makan bergizi, tidur 7-8 jam, aktif bergerak.';
        $comboTeks  = count($comboNotes) > 0 ? "\n\n" . implode("\n\n", $comboNotes) : '';
        $kesimpulan = $riskScore >= 4
            ? "🚨 **PERINGATAN (KRITIS):** Kondisi {$name} saat ini berisiko tinggi. Segera periksakan ke dokter atau IGD terdekat!"
            : "Secara keseluruhan kondisi {$name} terpantau dalam batas yang dapat dikelola. Pertahankan gaya hidup sehat dan pantau secara berkala.";

        return "**1. Ringkasan Kesehatan**\nBerdasarkan data terakhir {$name}, sistem menghasilkan **Skor Risiko: {$riskLabel}**.{$personalNote}\n\n**2. Analisis Detail Parameter**\n{$analisaTeks}{$comboTeks}\n\n**3. Rekomendasi Gaya Hidup**\n{$rekomendasiTeks}\n\n**4. Kesimpulan**\n{$kesimpulan}\n\n---\n*Analisis ini dihasilkan oleh VITALY Health AI. Tidak menggantikan diagnosis dokter.*";
    }

    // -----------------------------------------------------------------------
    // Fallback rule-based — CHAT (singkat, untuk AiChatController)
    // -----------------------------------------------------------------------

    private function chatFallback(array $history): string
    {
        // Kembalikan string kosong agar AiChatController pakai ruleBasedReply-nya sendiri
        return '';
    }
}
