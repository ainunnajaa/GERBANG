<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Presensi Bulanan</title>
    <style>
        @page {
            size: 330mm 210mm;
            margin: 10mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111827;
            margin: 0;
        }

        .page {
            width: fit-content;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 10px;
        }

        .title {
            font-size: 14px;
            font-weight: 700;
            margin: 0 0 4px;
        }

        .subtitle {
            margin: 0;
            font-size: 10px;
            color: #374151;
        }

        table {
            width: auto;
            border-collapse: collapse;
            table-layout: fixed;
        }

        col.col-name {
            width: 430px;
        }

        col.col-class {
            width: 62px;
        }

        col.col-day {
            width: 16px;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 2px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background: #f3f4f6;
            font-weight: 700;
        }

        th.col-name,
        td.col-name {
            text-align: left;
            white-space: nowrap;
            word-break: normal;
        }

        th.col-class,
        td.col-class {
            text-align: left;
        }

        th.col-day,
        td.col-day {
            width: 16px;
            min-width: 16px;
            max-width: 16px;
            padding: 2px 1px;
            font-size: 9px;
            font-family: DejaVu Sans Mono, monospace;
            text-align: center;
        }

        .status-h { background: #dcfce7; color: #166534; }
        .status-t { background: #fef9c3; color: #854d0e; }
        .status-i { background: #dbeafe; color: #1e3a8a; }
        .status-a { background: #fee2e2; color: #991b1b; }
        .status-empty { background: #f3f4f6; color: #374151; }

        .sunday {
            color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <p class="title">Rekap Presensi Bulanan Semua Guru</p>
            <p class="subtitle">
                Periode: {{ $selectedPeriod->name }} ({{ $selectedPeriod->start_date->format('d M Y') }} - {{ $selectedPeriod->end_date->format('d M Y') }})
                | Bulan: {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
            </p>
        </div>

        <table>
        <colgroup>
            <col class="col-name">
            <col class="col-class">
            @foreach($days as $day)
                <col class="col-day">
            @endforeach
        </colgroup>
        <thead>
            <tr>
                <th class="col-name">Nama Guru</th>
                <th class="col-class">Kelas</th>
                @foreach($days as $day)
                    @php
                        $isSunday = \Carbon\Carbon::create($year, $month, $day)->isSunday();
                    @endphp
                    <th class="col-day {{ $isSunday ? 'sunday' : '' }}">{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($gurus as $guru)
                <tr>
                    <td class="col-name">{{ $guru->name }}</td>
                    <td class="col-class">{{ $guru->kelas ?? '-' }}</td>
                    @foreach($days as $day)
                        @php
                            $status = $matrix[$guru->id][$day] ?? '-';
                            $statusClass = match ($status) {
                                'H' => 'status-h',
                                'T' => 'status-t',
                                'I' => 'status-i',
                                'A' => 'status-a',
                                default => 'status-empty',
                            };
                        @endphp
                        <td class="col-day {{ $statusClass }}">{{ $status }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</body>
</html>
