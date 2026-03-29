@php
    $tabProfile = $schoolProfile ?? \App\Models\SchoolProfile::first();
    $faviconUrl = !empty($tabProfile?->school_logo_path)
        ? asset('storage/' . $tabProfile->school_logo_path)
        : asset('favicon.ico');
@endphp

<link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
<link rel="shortcut icon" href="{{ $faviconUrl }}">
<link rel="apple-touch-icon" href="{{ $faviconUrl }}">
