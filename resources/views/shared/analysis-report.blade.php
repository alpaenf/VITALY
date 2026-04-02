<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Analisis Kesehatan</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f8fafc;
            color: #111827;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 860px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        }
        .head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            border-bottom: 2px solid #B92521;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .brand {
            font-size: 22px;
            font-weight: 800;
            color: #B92521;
            letter-spacing: 0.4px;
        }
        .meta {
            font-size: 13px;
            color: #4b5563;
        }
        .identity {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .identity td {
            font-size: 13px;
            padding: 4px 6px;
            vertical-align: top;
        }
        .label {
            color: #6b7280;
            width: 120px;
            font-weight: 600;
        }
        .value {
            color: #111827;
            font-weight: 500;
        }
        .result {
            border-top: 1px solid #e5e7eb;
            margin-top: 8px;
            padding-top: 14px;
            line-height: 1.7;
            white-space: pre-wrap;
            font-size: 14px;
        }
        .actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn {
            border: 0;
            border-radius: 8px;
            padding: 10px 14px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-print {
            background: #B92521;
            color: #fff;
        }
        .btn-close {
            background: #f3f4f6;
            color: #374151;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .container {
                box-shadow: none;
                border: 0;
                border-radius: 0;
                padding: 0;
            }
            .actions {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="head">
            <div>
                <div class="brand">HEALTIVA</div>
                <div class="meta">Laporan Analisis Kesehatan</div>
            </div>
            <div class="meta">
                Dicetak: {{ optional($analysis->created_at)->format('d M Y H:i') }}
            </div>
        </div>

        <table class="identity">
            <tr>
                <td class="label">Nama</td>
                <td class="value">{{ $patient->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="value">
                    @if(($patient->gender ?? null) === 'male')
                        Laki-laki
                    @elseif(($patient->gender ?? null) === 'female')
                        Perempuan
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Tanggal Analisis</td>
                <td class="value">{{ optional($analysis->created_at)->format('d M Y H:i') }}</td>
            </tr>
        </table>

        <div class="result">{{ $analysis->result }}</div>

        <div class="actions">
            <button type="button" class="btn btn-print" onclick="window.print()">Cetak / Simpan PDF</button>
            <button type="button" class="btn btn-close" onclick="window.close()">Tutup</button>
        </div>
    </div>
</body>
</html>
