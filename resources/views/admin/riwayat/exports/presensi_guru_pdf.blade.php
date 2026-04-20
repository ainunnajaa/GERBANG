<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Presensi Guru</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
        }

        h1 {
            margin: 0 0 6px;
            font-size: 18px;
        }

        .meta {
            margin: 0 0 4px;
            font-size: 11px;
            color: #374151;
        }

        .month-title {
            margin: 14px 0 8px;
            text-align: center;
            font-size: 14px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 6px 7px;
            text-align: left;
            vertical-align: top;
            word-break: break-word;
        }

        th {
            background: #f3f4f6;
            font-weight: 700;
        }

        .text-center {
            text-align: center;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <h1>Riwayat Presensi Guru</h1>
    <p class="meta">Nama Guru: {{ $guru->name }}</p>
    <p class="meta">Kelas: {{ $guru->kelas ?? '-' }}</p>
    <p class="meta">Periode: {{ $selectedPeriod->name }} ({{ $selectedPeriod->start_date->format('d M Y') }} - {{ $selectedPeriod->end_date->format('d M Y') }})</p>

    @forelse($sections as $section)
        <p class="month-title">{{ $section['label'] }}</p>
        <p class="meta">Rentang: {{ $section['start']->format('d M Y') }} - {{ $section['end']->format('d M Y') }}</p>

        <table>
            <thead>
                <tr>
                    <th style="width: 13%;">Tanggal</th>
                    <th style="width: 12%;">Jam Masuk</th>
                    <th style="width: 12%;">Jam Pulang</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 12%;">Jam Izin</th>
                    <th style="width: 41%;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($section['rows'] as $row)
                    @php
                        $item = $row['presensi'];
                        $izin = $row['izin'];
                    @endphp
                    <tr>
                        <td>{{ $row['date']->format('Y-m-d') }}</td>
                        <td>{{ optional($item)->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '-' }}</td>
                        <td>{{ optional($item)->jam_pulang ? \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') : '-' }}</td>
                        <td>{{ $row['status'] }}</td>
                        <td>
                            @if($izin)
                                {{ \Carbon\Carbon::parse($izin->created_at)->format('H:i') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $izin->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data pada bulan ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($isAllMonths && ! $loop->last)
            <div class="page-break"></div>
        @endif
    @empty
        <p class="meta">Tidak ada data presensi untuk ditampilkan.</p>
    @endforelse
</body>
</html>
