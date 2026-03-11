@php($p = $profile ?? null)
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Pesan Kontak Baru</title>
</head>
<body>
    <h2>Pesan Kontak Baru dari {{ $data['name'] }}</h2>

    <p><strong>Nama:</strong> {{ $data['name'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    @if(!empty($data['phone']))
        <p><strong>Telepon:</strong> {{ $data['phone'] }}</p>
    @endif

    <p><strong>Pesan:</strong></p>
    <p style="white-space: pre-line;">{{ $data['message'] }}</p>

    @if($p)
        <hr>
        <p>Dikirim dari halaman profil sekolah: {{ $p->school_name ?? config('app.name') }}</p>
    @endif
</body>
</html>
