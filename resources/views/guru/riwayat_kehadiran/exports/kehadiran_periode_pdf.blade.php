<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Presensi Satu Periode</title>
    <style>
        @page {
            size: 330mm 210mm;
            margin: 10mm;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111827;
        }

        .section {
            width: fit-content;
            margin: 0 auto 8mm;
            page-break-after: always;
        }

        .section:last-child {
            page-break-after: auto;
        }

        .title {
            margin: 0 0 4px;
            font-size: 13px;
            font-weight: 700;
        }

        .month-title {
            margin: 0 0 6px;
            text-align: center;
            font-size: 15px;
            font-weight: 700;
        }

        .subtitle {
            margin: 0 0 8px;
            color: #374151;
            font-size: 10px;
        }

        table {
            width: auto;
            border-collapse: collapse;
            table-layout: fixed;
        }

        col.col-name { width: 260px; }
        col.col-day { width: 18px; }

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
        }

        th.col-day,
        td.col-day {
            width: 18px;
            min-width: 18px;
            max-width: 18px;
            font-size: 9px;
            padding: 2px 1px;
        }

        .status-h { background: #dcfce7; color: #166534; }
        .status-t { background: #fef9c3; color: #854d0e; }
        .status-i { background: #dbeafe; color: #1e3a8a; }
        .status-a { background: #fee2e2; color: #991b1b; }
        .status-empty { background: #f3f4f6; color: #374151; }

        .sunday { color: #dc2626; }
    </style>
</head>
<body>
    @foreach($sections as $section)
        <div class="section">
            <p class="title">Rekap Presensi Bulanan Saya</p>
            <p class="month-title">{{ $section['monthLabel'] }}</p>
            <p class="subtitle">
                Nama: {{ $user->name }}
                | Periode: {{ $selectedPeriod->name }} ({{ $selectedPeriod->start_date->format('d M Y') }} - {{ $selectedPeriod->end_date->format('d M Y') }})
            </p>

            <table>
                <colgroup>
                    <col class="col-name">
                    @foreach($section['days'] as $day)
                        <col class="col-day">
                    @endforeach
                </colgroup>
                <thead>
                    <tr>
                        <th class="col-name">Nama</th>
                        @foreach($section['days'] as $day)
                            @php
                                $isSunday = \Carbon\Carbon::create($section['year'], $section['month'], $day)->isSunday();
                            @endphp
                            <th class="col-day {{ $isSunday ? 'sunday' : '' }}">{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="col-name">{{ $user->name }}</td>
                        @foreach($section['days'] as $day)
                            @php
                                $status = $section['matrix'][$day] ?? '-';
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
                </tbody>
            </table>
        </div>
    @endforeach
</body>
</html>
