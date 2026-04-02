from fastapi import FastAPI
from pydantic import BaseModel
from typing import List, Optional
from analyzer import HealthAnalyzer
import os
import requests
import uvicorn

try:
    import google.generativeai as genai
    _GEMINI_OK = True
except ImportError:
    _GEMINI_OK = False

app = FastAPI(title="HEALTIVA Health AI Service", version="2.0.0")


# --- Models -------------------------------------------------------------------

class Record(BaseModel):
    systolic: Optional[int] = None
    diastolic: Optional[int] = None
    heart_rate: Optional[int] = None
    blood_sugar: Optional[float] = None
    weight: Optional[float] = None
    height: Optional[float] = None
    temperature: Optional[float] = None
    oxygen_saturation: Optional[int] = None
    notes: Optional[str] = None
    recorded_at: Optional[str] = None


class UserData(BaseModel):
    name: str
    gender: Optional[str] = "male"
    age: Optional[int] = None


class AnalyzeRequest(BaseModel):
    user: UserData
    records: List[Record]


class ChatMessage(BaseModel):
    role: str  # "user" or "model"
    text: str


class ChatRequest(BaseModel):
    messages: List[ChatMessage]
    user: Optional[UserData] = None
    latest_record: Optional[Record] = None
    youtube_api_key: Optional[str] = None


# --- Analyze endpoint ----------------------------------------------------------

@app.post("/analyze")
def analyze(request: AnalyzeRequest):
    analyzer = HealthAnalyzer(
        request.user.model_dump(),
        [r.model_dump() for r in request.records],
    )
    result = analyzer.analyze()
    return {"analysis": result}


# --- YouTube helper ------------------------------------------------------------

def search_youtube(api_key: str, query: str, max_results: int = 3) -> list:
    """Search YouTube and return [{youtubeId, title, channel}]."""
    if not api_key:
        return []
    try:
        resp = requests.get(
            "https://www.googleapis.com/youtube/v3/search",
            params={
                "part": "snippet",
                "q": query,
                "type": "video",
                "maxResults": max_results,
                "relevanceLanguage": "id",
                "regionCode": "ID",
                "safeSearch": "strict",
                "key": api_key,
            },
            timeout=8,
        )
        if not resp.ok:
            return []
        videos = []
        for item in resp.json().get("items", []):
            vid_id = item.get("id", {}).get("videoId")
            snip = item.get("snippet", {})
            if vid_id:
                videos.append({
                    "youtubeId": vid_id,
                    "title": snip.get("title", ""),
                    "channel": snip.get("channelTitle", ""),
                })
        return videos
    except Exception:
        return []


def detect_youtube_query(text: str) -> Optional[str]:
    """Map message to YouTube search query."""
    t = text.lower()
    if any(w in t for w in ["hipertensi", "tekanan darah", "tensi", "sistolik", "diastolik"]):
        return "hipertensi tekanan darah pencegahan kemenkes"
    if any(w in t for w in ["diabetes", "gula darah", "glukosa", "pradiabetes"]):
        return "diabetes mellitus gula darah perkeni indonesia"
    if any(w in t for w in ["jantung", "kardio", "koroner", "angina"]):
        return "penyakit jantung koroner pencegahan perki indonesia"
    if any(w in t for w in ["imt", "bmi", "berat badan", "obesitas", "overweight", "kurus", "kegemukan", "diet"]):
        return "imt indeks massa tubuh gizi seimbang kemenkes"
    if any(w in t for w in ["kolesterol", "ldl", "hdl", "trigliserida"]):
        return "kolesterol tinggi pencegahan makanan sehat"
    if any(w in t for w in ["stroke", "serangan otak"]):
        return "pencegahan stroke indonesia kemenkes"
    if any(w in t for w in ["ginjal", "cuci darah", "kreatinin"]):
        return "penyakit ginjal kronis pencegahan indonesia"
    if any(w in t for w in ["olahraga", "latihan", "gym", "lari", "aerobik"]):
        return "olahraga sehat untuk jantung pemula indonesia"
    if any(w in t for w in ["tidur", "insomnia", "susah tidur"]):
        return "tips tidur berkualitas kesehatan indonesia"
    if any(w in t for w in ["stres", "stress", "kecemasan", "anxiety", "mental", "burnout"]):
        return "kelola stres kesehatan mental indonesia"
    if any(w in t for w in ["spo2", "saturasi", "oksigen", "oximeter", "sesak"]):
        return "saturasi oksigen spo2 pernapasan kesehatan"
    if any(w in t for w in ["demam", "suhu", "panas", "meriang"]):
        return "demam cara menurunkan penanganan kemenkes"
    if any(w in t for w in ["asam urat", "gout"]):
        return "asam urat gout pencegahan makanan indonesia"
    if any(w in t for w in ["maag", "lambung", "gerd", "refluks", "asam lambung"]):
        return "asam lambung maag gerd cara mengatasi"
    if any(w in t for w in ["anemia", "kurang darah", "hemoglobin"]):
        return "anemia kurang darah pencegahan makanan kemenkes"
    if any(w in t for w in ["vitamin", "suplemen", "nutrisi", "gizi", "makan sehat"]):
        return "vitamin suplemen gizi seimbang indonesia kemenkes"
    if any(w in t for w in ["rokok", "merokok", "nikotin"]):
        return "bahaya rokok dan cara berhenti merokok kemenkes"
    if any(w in t for w in ["video", "tonton", "rekomendasi video", "lihat video", "cari video", "edukasi"]):
        return "edukasi kesehatan hidup sehat indonesia kemenkes"
    return None


# --- System prompt -------------------------------------------------------------

_HEALTH_SYSTEM = """Kamu adalah HEALTIVA, asisten kesehatan pribadi yang cerdas, empatik, dan menyenangkan.

Karakter:
- Seperti teman dekat yang kebetulan paham kesehatan — santai, hangat, tidak menggurui
- Gunakan Bahasa Indonesia sehari-hari yang natural, sesekali gaul
- Boleh bercanda, basa-basi, dan ngobrol santai
- Sesekali pakai emoji agar ekspresif
- Ingat konteks percakapan

Cara menjawab:
- Pertanyaan kesehatan: langsung ke inti, gunakan data spesifik pengguna jika ada, saran personal
- Basa-basi / obrolan santai: ikuti secara natural seperti teman
- Pertanyaan umum: jawab ramah dan helpful
- Panjang jawaban sesuai konteks: ringan singkat, serius detail

Topik yang dikuasai: tekanan darah, hipertensi, jantung (PERKI/AHA), gula darah & diabetes (PERKENI 2021),
IMT & gizi (WHO Asia-Pacific), kolesterol, stroke, ginjal, olahraga, tidur, stres, gaya hidup sehat.

Batasan:
- Jangan mendiagnosis penyakit secara pasti
- Untuk gejala serius, anjurkan dokter secara natural
- Jangan akhiri setiap jawaban dengan disclaimer panjang
- Jangan mulai dengan Halo!, Tentu!, Baik! terus-menerus"""


# --- Rule-based fallback --------------------------------------------------------

def rule_based_reply(last_msg: str, record: Optional[Record], user: Optional[UserData]) -> str:
    name = user.name.split()[0] if user else "kamu"
    t = last_msg.lower()

    def d_bp():
        if record and record.systolic and record.diastolic:
            return f" *(data kamu: {record.systolic}/{record.diastolic} mmHg)*"
        return ""
    def d_sugar():
        if record and record.blood_sugar:
            return f" *(data kamu: {record.blood_sugar} mg/dL)*"
        return ""
    def d_bmi():
        if record and record.weight and record.height:
            bmi = round(record.weight / (record.height / 100) ** 2, 1)
            return f" *(IMT kamu: {bmi})*"
        return ""
    def d_hr():
        if record and record.heart_rate:
            return f" *(detak kamu: {record.heart_rate} bpm)*"
        return ""
    def d_spo2():
        if record and record.oxygen_saturation:
            return f" *(SpO2 kamu: {record.oxygen_saturation}%)*"
        return ""
    def d_temp():
        if record and record.temperature:
            return f" *(suhu kamu: {record.temperature}C)*"
        return ""

    if any(w in t for w in ["tekanan darah", "hipertensi", "sistolik", "diastolik", "tensi", "blood pressure"]):
        return (
            f"Tekanan darah normal itu **di bawah 120/80 mmHg**{d_bp()}.\n\n"
            "Klasifikasi Kemenkes RI:\n"
            "- **Normal:** <120/<80\n"
            "- **Elevated:** 120-129/<80\n"
            "- **Hipertensi Tahap 1:** 130-139/80-89\n"
            "- **Hipertensi Tahap 2:** >=140/>=90\n"
            "- **Krisis:** >180/>120\n\n"
            "Cara menurunkan: kurangi garam (<5g/hari), olahraga aerobik 30 menit 5x/minggu, "
            "berhenti rokok, kelola stres, tidur cukup. Konsisten >=140/90? Periksa ke dokter ya."
        )

    if any(w in t for w in ["gula darah", "diabetes", "glukosa", "pradiabetes", "blood sugar", "hiperglikemia"]):
        return (
            f"Gula darah puasa normal: **70-99 mg/dL**{d_sugar()}.\n\n"
            "Klasifikasi PERKENI 2021:\n"
            "- **Normal:** 70-99 mg/dL\n"
            "- **Pradiabetes:** 100-125 mg/dL\n"
            "- **Diabetes:** >=126 mg/dL (2x pemeriksaan)\n\n"
            "Menjaga gula stabil: batasi karbohidrat sederhana, perbanyak serat, olahraga teratur, "
            "hindari stres. HbA1c dianjurkan tiap 3 bulan untuk yang berisiko."
        )

    if any(w in t for w in ["imt", "bmi", "berat badan", "obesitas", "overweight", "kurus", "kegemukan", "diet", "tubuh ideal"]):
        return (
            f"IMT = berat (kg) / tinggi (m){d_bmi()}.\n\n"
            "Standar WHO Asia-Pacific:\n"
            "- **<18.5** Kurus\n"
            "- **18.5-22.9** Normal\n"
            "- **23.0-27.4** Overweight\n"
            "- **>=27.5** Obesitas\n\n"
            "IMT bukan satu-satunya indikator — lingkar pinggang juga penting (pria <90cm, wanita <80cm). "
            "Target ideal: makan bergizi seimbang + aktivitas fisik 150 menit/minggu + tidur 7-9 jam."
        )

    if any(w in t for w in ["detak", "bpm", "nadi", "denyut", "heart rate", "aritmia", "berdebar"]):
        return (
            f"Detak jantung normal saat istirahat: **60-100 bpm**{d_hr()}.\n\n"
            "- **<60 bpm** Bradikardia (normal pada atlet)\n"
            "- **60-100 bpm** Normal\n"
            "- **>100 bpm** Takikardia\n"
            "- **>150 bpm** saat istirahat — segera periksa\n\n"
            "Dipengaruhi: kafein, stres, dehidrasi, obat-obatan. "
            "Jantung berdebar + pusing/sesak/nyeri dada? Langsung ke dokter."
        )

    if any(w in t for w in ["spo2", "oksigen", "saturasi", "oximeter", "sesak napas"]):
        return (
            f"Saturasi oksigen normal: **>=95%**{d_spo2()}.\n\n"
            "- **95-100%** Normal\n"
            "- **90-94%** Hipoksia ringan\n"
            "- **<90%** Segera ke fasilitas kesehatan!\n\n"
            "Tips ukur yang benar: jari hangat, tidak bergerak, tidak ada cat kuku. "
            "SpO2 rendah persisten bisa tanda masalah paru atau jantung."
        )

    if any(w in t for w in ["suhu", "demam", "panas", "meriang", "fever"]):
        return (
            f"Suhu tubuh normal: **36.1-37.5 derajat C**{d_temp()}.\n\n"
            "- **>37.5C** Demam ringan\n"
            "- **>38.5C** Demam tinggi\n"
            "- **>40C** Segera ke dokter/IGD!\n\n"
            "Penanganan: istirahat, banyak minum air, kompres hangat, parasetamol sesuai dosis. "
            "Demam >3 hari atau disertai kejang/ruam? Jangan tunda periksa."
        )

    if any(w in t for w in ["kolesterol", "ldl", "hdl", "trigliserida", "lemak darah"]):
        return (
            "Nilai kolesterol ideal:\n\n"
            "- **Total kolesterol:** <200 mg/dL\n"
            "- **LDL:** <130 mg/dL (target <100 untuk risiko tinggi)\n"
            "- **HDL:** >40 (pria), >50 (wanita) mg/dL\n"
            "- **Trigliserida:** <150 mg/dL\n\n"
            "Cara menurunkan: kurangi lemak jenuh (jeroan, gorengan, santan), perbanyak serat, "
            "olahraga rutin, berhenti merokok. Cek profil lipid setahun sekali setelah usia 30."
        )

    if any(w in t for w in ["stroke", "serangan otak", "bicara pelo", "wajah mencong"]):
        return (
            "Stroke = medical emergency — setiap menit berarti!\n\n"
            "**Kenali gejala FAST:**\n"
            "- **F**ace — wajah mencong sebelah?\n"
            "- **A**rm — tangan/kaki lemah mendadak?\n"
            "- **S**peech — bicara pelo atau tidak nyambung?\n"
            "- **T**ime — langsung hubungi 119 atau ke IGD!\n\n"
            "Faktor risiko utama: hipertensi, diabetes, kolesterol tinggi, merokok, obesitas. "
            "Kontrol faktor risiko ini adalah pencegahan terbaik."
        )

    if any(w in t for w in ["ginjal", "cuci darah", "kreatinin", "batu ginjal"]):
        return (
            "Tanda-tanda gangguan ginjal yang perlu diwaspadai:\n\n"
            "- Kencing berbusa atau berdarah\n"
            "- Bengkak kaki/wajah\n"
            "- Lelah berlebihan + mual tanpa sebab jelas\n"
            "- Tekanan darah susah dikontrol\n\n"
            "Menjaga ginjal sehat: minum air 2-3 liter/hari, kontrol tensi & gula darah, "
            "hindari NSAID (ibuprofen) berlebihan, jaga berat badan. "
            "Cek kreatinin & eGFR setahun sekali setelah usia 40."
        )

    if any(w in t for w in ["asam urat", "gout", "uric acid", "sendi nyeri"]):
        return (
            "Asam urat normal: pria 3.5-7.2 mg/dL, wanita 2.6-6.0 mg/dL.\n\n"
            "**Hindari:** jeroan, seafood (udang, kepiting, sarden), daging merah berlebihan, "
            "alkohol, minuman manis tinggi fruktosa.\n\n"
            "**Boleh dikonsumsi:** sayuran hijau, buah-buahan, susu rendah lemak, telur, tahu-tempe. "
            "Minum air putih banyak membantu ekskresi asam urat."
        )

    if any(w in t for w in ["maag", "lambung", "gerd", "refluks", "asam lambung", "heartburn"]):
        return (
            "Tips mengatasi asam lambung / GERD:\n\n"
            "- Makan porsi kecil tapi sering\n"
            "- Hindari pemicu: kopi, coklat, pedas, asam, gorengan\n"
            "- Jangan langsung tiduran setelah makan (tunggu 2-3 jam)\n"
            "- Tinggikan kepala saat tidur 15-30 cm\n"
            "- Kelola stres\n\n"
            "Antasida bisa meredakan sementara. Kalau kambuh >2x/minggu, sebaiknya periksa ke dokter."
        )

    if any(w in t for w in ["olahraga", "latihan", "gym", "lari", "aerobik", "exercise"]):
        return (
            "Rekomendasi aktivitas fisik WHO:\n\n"
            "- **Aerobik sedang:** 150-300 menit/minggu (jalan cepat, bersepeda)\n"
            "- **Aerobik intensitas tinggi:** 75-150 menit/minggu (lari, renang)\n"
            "- **Latihan kekuatan:** 2x seminggu\n\n"
            "Untuk pemula: mulai dari 20-30 menit jalan kaki/hari, tingkatkan perlahan. "
            "Punya masalah jantung atau hipertensi? Konsultasikan ke dokter dulu sebelum olahraga berat.\n\n"
            "Ingat: olahraga apa pun lebih baik daripada tidak sama sekali!"
        )

    if any(w in t for w in ["tidur", "insomnia", "susah tidur", "sleep", "ngantuk"]):
        return (
            "Durasi tidur ideal: **7-9 jam/malam** (dewasa).\n\n"
            "**Tips tidur berkualitas:**\n"
            "- Jam tidur & bangun konsisten setiap hari\n"
            "- Hindari layar (HP/laptop) 1 jam sebelum tidur\n"
            "- Kamar gelap, sejuk, tenang\n"
            "- Hindari kafein setelah jam 2 siang\n"
            "- Olahraga sore (tidak terlalu malam)\n"
            "- Relaksasi: napas dalam atau meditasi singkat\n\n"
            "Kurang tidur kronis meningkatkan risiko hipertensi, diabetes, obesitas, dan depresi."
        )

    if any(w in t for w in ["stres", "stress", "kecemasan", "anxiety", "mental", "burnout", "depresi"]):
        return (
            "Stres berlarut-larut bisa pengaruhi tensi, gula darah, dan imun tubuh.\n\n"
            "**Cara mengelola:**\n"
            "- Pernapasan 4-7-8: tarik 4 detik, tahan 7, hembuskan 8\n"
            "- Olahraga teratur (lepas endorfin alami)\n"
            "- Journaling — tulis apa yang dirasakan\n"
            "- Cerita ke orang terpercaya\n"
            "- Batasi konsumsi berita negatif\n\n"
            "Kalau sudah mengganggu aktivitas harian, jangan ragu ke psikolog ya. "
            "Into The Light Indonesia: 119 ext 8."
        )

    if any(w in t for w in ["vitamin", "suplemen", "nutrisi", "gizi", "makan sehat"]):
        return (
            "Yang sering kekurangan di Indonesia:\n\n"
            "- **Vitamin D:** sinar matahari pagi 10-15 menit, ikan, susu\n"
            "- **Zat besi:** daging merah, hati, bayam, kacang\n"
            "- **Kalsium:** susu, tahu, brokoli\n"
            "- **Omega-3:** ikan laut (salmon, makarel, sarden)\n\n"
            "Prinsip utama (Isi Piringku): setengah piring sayur+buah, seperempat karbohidrat, "
            "seperempat protein. Suplemen hanya pelengkap, bukan pengganti makan bergizi."
        )

    # Basa-basi
    greetings = ["halo", "hai", "hi", "hey", "hei", "pagi", "siang", "sore", "malam", "selamat"]
    thanks = ["makasih", "thanks", "terima kasih", "thx", "tq", "trims"]
    how_are = ["apa kabar", "gimana kabar", "how are", "baik-baik", "sehat"]
    chitchat = ["haha", "wkwk", "lol", "lucu", "gila", "anjir", "parah", "wah", "wow"]

    if any(w in t for w in greetings):
        return f"Hei {name}! Ada yang bisa aku bantu hari ini? Mau tanya soal kesehatan atau ngobrol santai juga boleh!"
    if any(w in t for w in thanks):
        return f"Sama-sama {name}! Kalau ada yang mau ditanyain lagi, aku selalu di sini."
    if any(w in t for w in how_are):
        return f"Baik dong, siap bantu {name}! Kamu sendiri gimana, sehat?"
    if any(w in t for w in chitchat):
        return "Haha iya! Btw ada yang mau ditanyain soal kesehatan?"

    return (
        f"Hei {name}! Aku HEALTIVA, asisten kesehatan kamu. "
        "Bisa bantu soal tekanan darah, gula darah, IMT, jantung, olahraga, tidur, dan masih banyak lagi. "
        "Mau tanya apa nih?"
    )


# --- Chat endpoint --------------------------------------------------------------

@app.post("/chat")
def chat(request: ChatRequest):
    history = request.messages
    last_msg = history[-1].text if history else ""
    youtube_key = request.youtube_api_key or os.getenv("YOUTUBE_API_KEY", "")

    # Build context
    ctx = ""
    if request.latest_record:
        r = request.latest_record
        parts = []
        if r.systolic and r.diastolic:
            parts.append(f"Tekanan darah: {r.systolic}/{r.diastolic} mmHg")
        if r.heart_rate:
            parts.append(f"Detak jantung: {r.heart_rate} bpm")
        if r.blood_sugar:
            parts.append(f"Gula darah: {r.blood_sugar} mg/dL")
        if r.weight and r.height:
            bmi = round(r.weight / (r.height / 100) ** 2, 1)
            parts.append(f"IMT: {bmi} (BB {r.weight}kg, TB {r.height}cm)")
        if r.temperature:
            parts.append(f"Suhu: {r.temperature}C")
        if r.oxygen_saturation:
            parts.append(f"SpO2: {r.oxygen_saturation}%")
        if parts:
            ctx = "\n\nData kesehatan terakhir:\n- " + "\n- ".join(parts)

    if request.user:
        ctx = (f"Nama: {request.user.name}, "
               f"Umur: {request.user.age or '?'} tahun, "
               f"Kelamin: {request.user.gender or '?'}.") + ctx

    # Try Gemini
    reply_text = None
    api_key = os.getenv("GEMINI_API_KEY", "")
    if _GEMINI_OK and api_key:
        try:
            genai.configure(api_key=api_key)
            model_name = os.getenv("GEMINI_MODEL", "gemini-2.0-flash-lite")
            model = genai.GenerativeModel(
                model_name=model_name,
                system_instruction=_HEALTH_SYSTEM + ("\n\nKonteks pengguna:\n" + ctx if ctx else ""),
            )
            chat_session = model.start_chat(history=[
                {"role": m.role, "parts": [m.text]} for m in history[:-1]
            ])
            reply_text = chat_session.send_message(last_msg).text
        except Exception:
            pass

    # Rule-based fallback
    if not reply_text:
        reply_text = rule_based_reply(last_msg, request.latest_record, request.user)

    # YouTube recommendations – cek dari pesan user dulu, lalu dari reply jika tidak ketemu
    videos = []
    yt_query = detect_youtube_query(last_msg) or detect_youtube_query(reply_text)
    if yt_query and youtube_key:
        videos = search_youtube(youtube_key, yt_query, max_results=3)

    return {"reply": reply_text, "videos": videos}


@app.get("/health")
def health_check():
    return {"status": "ok", "service": "HEALTIVA Health AI v2"}


if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=8001, reload=False)
