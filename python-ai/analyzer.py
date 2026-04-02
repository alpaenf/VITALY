from typing import Optional


class HealthAnalyzer:
    def __init__(self, user_data: dict, records: list):
        self.user = user_data
        self.records = records

    # â”€â”€â”€ Blood Pressure â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    def classify_bp(self, systolic: int, diastolic: int) -> tuple[str, str]:
        """Returns (label, severity) based on AHA 2017 guidelines."""
        if systolic < 120 and diastolic < 80:
            return "Normal", "normal"
        elif systolic < 130 and diastolic < 80:
            return "Elevated (Prehipertensi)", "warning"
        elif (130 <= systolic < 140) or (80 <= diastolic < 90):
            return "Hipertensi Tahap 1", "danger"
        elif systolic >= 140 or diastolic >= 90:
            return "Hipertensi Tahap 2", "danger"
        elif systolic > 180 or diastolic > 120:
            return "Krisis Hipertensi", "critical"
        return "Tidak Diketahui", "unknown"

    # â”€â”€â”€ Heart Rate â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    def classify_hr(self, hr: int) -> tuple[str, str]:
        if hr < 60:
            return "Bradikardia (detak terlalu lambat)", "warning"
        elif 60 <= hr <= 100:
            return "Normal", "normal"
        elif 100 < hr <= 120:
            return "Takikardia Ringan", "warning"
        else:
            return "Takikardia Berat", "danger"

    # â”€â”€â”€ Blood Sugar â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    def classify_sugar(self, sugar: float) -> tuple[str, str]:
        """Fasting blood glucose classification."""
        if sugar < 70:
            return "Hipoglikemia (gula darah terlalu rendah)", "danger"
        elif 70 <= sugar < 100:
            return "Normal (puasa)", "normal"
        elif 100 <= sugar < 126:
            return "Pradiabetes", "warning"
        else:
            return "Potensi Diabetes (konsultasi dokter)", "danger"

    # â”€â”€â”€ IMT â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    def calculate_bmi(self, weight: float, height: float) -> float:
        return round(weight / (height / 100) ** 2, 1)

    def classify_bmi(self, bmi: float) -> tuple[str, str]:
        """WHO Asia-Pacific cutoffs."""
        if bmi < 18.5:
            return "Kurus (Underweight)", "warning"
        elif 18.5 <= bmi < 23.0:
            return "Normal", "normal"
        elif 23.0 <= bmi < 27.5:
            return "Overweight", "warning"
        else:
            return "Obesitas", "danger"

    # â”€â”€â”€ Temperature â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    def classify_temp(self, temp: float) -> tuple[str, str]:
        if temp < 35.0:
            return "Hipotermia", "danger"
        elif 35.0 <= temp < 36.0:
            return "Suhu rendah (sedikit di bawah normal)", "warning"
        elif 36.0 <= temp <= 37.5:
            return "Normal", "normal"
        elif 37.5 < temp <= 38.5:
            return "Demam Ringan", "warning"
        elif 38.5 < temp <= 40.0:
            return "Demam Tinggi", "danger"
        else:
            return "Demam Sangat Tinggi (segera ke dokter)", "critical"

    # â”€â”€â”€ SpO2 â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    def classify_spo2(self, spo2: int) -> tuple[str, str]:
        if spo2 >= 95:
            return "Normal", "normal"
        elif 90 <= spo2 < 95:
            return "Rendah (Hipoksia Ringan)", "warning"
        else:
            return "Sangat Rendah (segera ke dokter)", "critical"

    # â”€â”€â”€ Trend Analysis â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    def analyze_trend(self, values: list) -> str:
        clean = [v for v in values if v is not None]
        if len(clean) < 2:
            return "data tidak cukup untuk analisis tren"
        diffs = [clean[i + 1] - clean[i] for i in range(len(clean) - 1)]
        avg_diff = sum(diffs) / len(diffs)
        if avg_diff > 2:
            return "cenderung **meningkat**"
        elif avg_diff < -2:
            return "cenderung **menurun**"
        else:
            return "cenderung **stabil**"

    # â”€â”€â”€ Main Analyze â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    def analyze(self) -> str:
        name = self.user.get("name", "Pasien")
        gender = "Laki-laki" if self.user.get("gender") == "male" else "Perempuan"
        age = self.user.get("age")
        age_str = f"{age} tahun" if age else "tidak diketahui"
        n = len(self.records)
        latest = self.records[0] if self.records else {}

        sections = []

        # â”€â”€ Header â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        sections.append(
            f"# Laporan Analisis Kesehatan\n\n"
            f"**Nama:** {name} | **Jenis Kelamin:** {gender} | **Usia:** {age_str}\n\n"
            f"*Berdasarkan {n} pengukuran terakhir*"
        )

        # â”€â”€ Ringkasan Kondisi â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        status_list = []

        sys = latest.get("systolic")
        dia = latest.get("diastolic")
        if sys and dia:
            label, sev = self.classify_bp(sys, dia)
            if sev != "normal":
                status_list.append(f"Tekanan darah: **{label}** ({sys}/{dia} mmHg)")

        hr = latest.get("heart_rate")
        if hr:
            label, sev = self.classify_hr(hr)
            if sev != "normal":
                status_list.append(f"Detak jantung: **{label}** ({hr} bpm)")

        sugar = latest.get("blood_sugar")
        if sugar:
            label, sev = self.classify_sugar(sugar)
            if sev != "normal":
                status_list.append(f"Gula darah: **{label}** ({sugar} mg/dL)")

        w = latest.get("weight")
        h = latest.get("height")
        if w and h:
            bmi = self.calculate_bmi(w, h)
            label, sev = self.classify_bmi(bmi)
            if sev != "normal":
                status_list.append(f"IMT: **{label}** ({bmi})")

        temp = latest.get("temperature")
        if temp:
            label, sev = self.classify_temp(temp)
            if sev != "normal":
                status_list.append(f"Suhu tubuh: **{label}** ({temp}°C)")

        spo2 = latest.get("oxygen_saturation")
        if spo2:
            label, sev = self.classify_spo2(spo2)
            if sev != "normal":
                status_list.append(f"SpO2: **{label}** ({spo2}%)")

        if status_list:
            summary = "## Ringkasan Kondisi Kesehatan\n\nTerdapat beberapa parameter yang perlu diperhatikan:\n\n"
            summary += "\n".join(f"- {s}" for s in status_list)
        else:
            summary = "## Ringkasan Kondisi Kesehatan\n\nBerdasarkan data terbaru, semua parameter kesehatan Anda berada dalam **batas normal**. Pertahankan pola hidup sehat yang sudah Anda jalani."

        sections.append(summary)

        # â”€â”€ Analisis Per Parameter â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        params_section = "## Analisis Per Parameter\n"

        if sys and dia:
            label, sev = self.classify_bp(sys, dia)
            bp_trend = ""
            if n >= 2:
                systolics = [r.get("systolic") for r in self.records]
                trend = self.analyze_trend(systolics)
                bp_trend = f" Tren tekanan sistolik {trend}."
            params_section += f"\n### Tekanan Darah\n- Nilai terbaru: **{sys}/{dia} mmHg**\n- Klasifikasi: **{label}**{bp_trend}\n"
            if label == "Normal":
                params_section += "- Tekanan darah Anda ideal. Jaga asupan garam dan tetap aktif bergerak.\n"
            elif "Tahap 1" in label:
                params_section += "- Kurangi konsumsi garam (<2.3 g/hari), perbanyak sayur dan buah, olahraga 30 menit/hari.\n"
            elif "Tahap 2" in label:
                params_section += "- Disarankan segera konsultasi ke dokter untuk evaluasi lebih lanjut dan rencana pengobatan.\n"
            elif "Krisis" in label:
                params_section += "- **SEGERA ke UGD** atau hubungi layanan darurat medis.\n"

        if hr:
            label, sev = self.classify_hr(hr)
            hr_trend = ""
            if n >= 2:
                hrs = [r.get("heart_rate") for r in self.records]
                trend = self.analyze_trend(hrs)
                hr_trend = f" Tren detak jantung {trend}."
            params_section += f"\n### Detak Jantung\n- Nilai terbaru: **{hr} bpm**\n- Klasifikasi: **{label}**{hr_trend}\n"
            if sev == "normal":
                params_section += "- Detak jantung Anda normal dan sehat.\n"
            elif "Bradikardia" in label:
                params_section += "- Bisa normal pada atlet. Jika disertai pusing atau sesak napas, segera ke dokter.\n"
            else:
                params_section += "- Hindari kafein berlebih dan kelola stres. Jika berlanjut, konsultasi ke dokter.\n"

        if sugar:
            label, sev = self.classify_sugar(sugar)
            sugar_trend = ""
            if n >= 2:
                sugars = [r.get("blood_sugar") for r in self.records]
                trend = self.analyze_trend(sugars)
                sugar_trend = f" Tren gula darah {trend}."
            params_section += f"\n### Gula Darah\n- Nilai terbaru: **{sugar} mg/dL**\n- Klasifikasi: **{label}**{sugar_trend}\n"
            if sev == "normal":
                params_section += "- Gula darah Anda normal. Jaga pola makan dengan membatasi gula dan karbohidrat sederhana.\n"
            elif "Pradiabetes" in label:
                params_section += "- Kurangi konsumsi gula dan makanan olahan. Olahraga rutin dapat membantu menurunkan risiko diabetes.\n"
            elif "Diabetes" in label:
                params_section += "- Sangat disarankan untuk konsultasi ke dokter dan lakukan pemeriksaan HbA1c.\n"
            elif "Hipoglikemia" in label:
                params_section += "- Segera konsumsi glukosa cepat (gula/madu). Jika sering terjadi, konsultasi ke dokter.\n"

        if w and h:
            bmi = self.calculate_bmi(w, h)
            label, sev = self.classify_bmi(bmi)
            bmi_trend = ""
            if n >= 2:
                weights = [r.get("weight") for r in self.records]
                trend = self.analyze_trend(weights)
                bmi_trend = f" Berat badan {trend}."
            params_section += f"\n### Indeks Massa Tubuh (IMT)\n- Berat: **{w} kg** | Tinggi: **{h} cm** | IMT: **{bmi}**\n- Klasifikasi: **{label}**{bmi_trend}\n"
            if sev == "normal":
                params_section += "- IMT Anda ideal. Pertahankan dengan pola makan seimbang dan olahraga rutin.\n"
            elif "Kurus" in label:
                params_section += "- Tingkatkan asupan kalori berkualitas (protein, lemak sehat). Hindari makanan olahan berlebihan.\n"
            elif "Overweight" in label:
                params_section += "- Target turun 0.5â€“1 kg/minggu melalui kombinasi diet dan olahraga aerobik.\n"
            elif "Obesitas" in label:
                params_section += "- Konsultasi ke ahli gizi dan dokter untuk program penurunan berat badan yang aman.\n"

        if temp:
            label, sev = self.classify_temp(temp)
            params_section += f"\n### Suhu Tubuh\n- Nilai terbaru: **{temp}°C**\n- Klasifikasi: **{label}**\n"
            if sev == "normal":
                params_section += "- Suhu tubuh Anda normal.\n"
            elif "Demam Ringan" in label:
                params_section += "- Perbanyak minum air, istirahat cukup. Monitor suhu secara berkala.\n"
            elif "Demam Tinggi" in label:
                params_section += "- Gunakan kompres dingin, minum parasetamol jika perlu. Jika tidak turun dalam 48 jam, ke dokter.\n"
            elif "critical" == sev:
                params_section += "- **Segera ke fasilitas kesehatan terdekat.**\n"

        if spo2:
            label, sev = self.classify_spo2(spo2)
            params_section += f"\n### Saturasi Oksigen (SpO2)\n- Nilai terbaru: **{spo2}%**\n- Klasifikasi: **{label}**\n"
            if sev == "normal":
                params_section += "- Saturasi oksigen Anda baik.\n"
            elif sev == "warning":
                params_section += "- Latihan pernapasan diafragma dapat membantu. Jika disertai sesak, segera ke dokter.\n"
            else:
                params_section += "- **Segera ke IGD**, saturasi oksigen sangat rendah.\n"

        sections.append(params_section)

        # â”€â”€ Tren Kesehatan â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        if n >= 2:
            trend_section = "## Tren Kesehatan\n\n"
            trend_lines = []

            if any(r.get("systolic") for r in self.records):
                systolics = [r.get("systolic") for r in self.records]
                trend_lines.append(f"- Tekanan darah sistolik {self.analyze_trend(systolics)} selama {n} pengukuran")

            if any(r.get("heart_rate") for r in self.records):
                hrs = [r.get("heart_rate") for r in self.records]
                trend_lines.append(f"- Detak jantung {self.analyze_trend(hrs)}")

            if any(r.get("blood_sugar") for r in self.records):
                sugars = [r.get("blood_sugar") for r in self.records]
                trend_lines.append(f"- Gula darah {self.analyze_trend(sugars)}")

            if any(r.get("weight") for r in self.records):
                weights = [r.get("weight") for r in self.records]
                trend_lines.append(f"- Berat badan {self.analyze_trend(weights)}")

            if trend_lines:
                trend_section += "\n".join(trend_lines)
            else:
                trend_section += "Data tren belum cukup untuk dianalisis secara menyeluruh."

            sections.append(trend_section)

        # â”€â”€ Rekomendasi â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        recs = ["## Rekomendasi Gaya Hidup\n"]

        # Universal recommendations
        recs.append("**Umum:**")
        recs.append("- Tidur 7â€“9 jam per malam secara konsisten")
        recs.append("- Minum air putih minimal 8 gelas (2 liter) per hari")
        recs.append("- Kelola stres dengan meditasi, yoga, atau hobi positif")
        recs.append("- Hindari rokok dan batasi konsumsi alkohol")

        # Specific to values
        specific = []
        if sys and dia and (sys >= 130 or dia >= 80):
            specific.append("- Kurangi garam, hindari makanan olahan dan fast food")
            specific.append("- Olahraga aerobik 30 menit minimal 5 hari/minggu (jalan cepat, bersepeda, renang)")
        if sugar and sugar >= 100:
            specific.append("- Konsumsi makanan indeks glikemik rendah (sayur, biji-bijian utuh)")
            specific.append("- Hindari minuman manis dan jus buah kemasan")
        if w and h:
            bmi = self.calculate_bmi(w, h)
            if bmi >= 23:
                specific.append("- Terapkan piring seimbang: Â½ sayur/buah, Â¼ protein, Â¼ karbohidrat kompleks")
            elif bmi < 18.5:
                specific.append("- Konsumsi 3 kali makan utama dan 2 kali snack bergizi per hari")

        if specific:
            recs.append("\n**Spesifik untuk kondisi Anda:**")
            recs.extend(specific)

        sections.append("\n".join(recs))

        # â”€â”€ Kapan ke Dokter â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        urgent_lines = []
        routine_lines = []

        if sys and dia:
            if sys > 180 or dia > 120:
                urgent_lines.append("- Tekanan darah sangat tinggi (>180/120 mmHg) â€” **segera ke IGD**")
            elif sys >= 140 or dia >= 90:
                routine_lines.append("- Tekanan darah hipertensi tahap 2 â€” **konsultasi dalam 1â€“2 minggu**")

        if hr and (hr < 40 or hr > 150):
            urgent_lines.append("- Detak jantung ekstrem â€” **segera ke IGD**")

        if sugar and sugar < 60:
            urgent_lines.append("- Gula darah sangat rendah â€” **segera tangani dan ke dokter**")
        elif sugar and sugar >= 200:
            urgent_lines.append("- Gula darah sangat tinggi â€” **segera ke dokter**")

        if temp and temp > 40:
            urgent_lines.append("- Demam sangat tinggi (>40°C) â€” **segera ke IGD**")

        if spo2 and spo2 < 90:
            urgent_lines.append("- SpO2 sangat rendah â€” **segera ke IGD**")

        doctor_section = "## Kapan Harus ke Dokter\n\n"
        if urgent_lines:
            doctor_section += "**SEGERA (Hari Ini):**\n" + "\n".join(urgent_lines) + "\n\n"
        if routine_lines:
            doctor_section += "**Dalam Waktu Dekat:**\n" + "\n".join(routine_lines) + "\n\n"
        if not urgent_lines and not routine_lines:
            doctor_section += (
                "Berdasarkan data saat ini, tidak ada kondisi darurat yang memerlukan penanganan segera. "
                "Lakukan **pemeriksaan kesehatan rutin** minimal 1 tahun sekali dan monitor kesehatan Anda secara berkala melalui aplikasi ini.\n"
            )

        doctor_section += "\n---\n*Analisis ini bersifat informatif dan tidak menggantikan diagnosis atau saran medis dari dokter.*"
        sections.append(doctor_section)

        return "\n\n".join(sections)
