@php
	$downloadData = $downloadSettings ?? null;
	$defaultBerita = 'https://tkaba54semarang.my.id/download/berita';
	$defaultGerbang = 'https://tkaba54semarang.my.id/download/gerbang';
	$beritaLink = $downloadData->link_berita ?? $defaultBerita;
	$gerbangLink = $downloadData->link_gerbang ?? $defaultGerbang;
	$installGuideLink = $downloadData->install_guide_link ?? null;
	$downloadPrimary = $tema->header_bg_color ?? '#0000F4';
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Pusat Unduhan Aplikasi - TK Pembina ABA 54</title>

	@include('partials.favicon')

	<script>
		(function() {
			try {
				var root = document.documentElement;
				var saved = localStorage.getItem('theme') || 'system';
				var isDark = saved === 'dark' || (saved !== 'light' && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
				if (isDark) { root.classList.add('dark'); } else { root.classList.remove('dark'); }
			} catch (e) {}
		})();
	</script>

	<style>
		:root { --download-primary: {{ $downloadPrimary }}; }
		.download-primary { background-color: var(--download-primary); }
		.download-primary:hover { filter: brightness(0.9); }
		.download-primary-text { color: var(--download-primary); }
	</style>

	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 dark:text-gray-100 antialiased min-h-screen flex flex-col">
	@include('publik.tampilan.footer_navbar', ['slotPosition' => 'header'])

	<!-- HEADER / HERO SECTION -->
	<header class="download-primary pt-16 pb-24 px-6 relative overflow-hidden">
		<!-- Dekorasi Background -->
		<div class="absolute top-[-50px] right-[-50px] w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
		<div class="absolute bottom-[-50px] left-[-50px] w-48 h-48 bg-blue-300 opacity-20 rounded-full blur-2xl"></div>
        
		<div class="max-w-4xl mx-auto text-center relative z-10">
			<div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white shadow-lg mb-6">
				<!-- Ikon Download -->
				<svg class="w-8 h-8 download-primary-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
				</svg>
			</div>
			<h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">Pusat Unduhan Aplikasi</h1>
			<p class="text-blue-100 text-sm md:text-base max-w-2xl mx-auto leading-relaxed">
				Dapatkan kemudahan akses informasi dan administrasi sekolah langsung dari *smartphone* Anda. Silakan unduh aplikasi resmi TK Pembina ABA 54 Semarang di bawah ini.
			</p>
		</div>
	</header>

	<!-- KONTEN APLIKASI (Cards) -->
	<!-- Margin negatif agar kotak aplikasinya menumpuk di atas background biru -->
	<main class="flex-1 max-w-5xl mx-auto w-full px-6 -mt-12 relative z-20 pb-20">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
			<!-- ============================================== -->
			<!-- CARD 1: APLIKASI BERITA                        -->
			<!-- ============================================== -->
			<div class="bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.08)] overflow-hidden border border-gray-100 flex flex-col hover:-translate-y-1 transition-transform duration-300">
				<div class="p-8 flex-1">
					<div class="flex items-center gap-4 mb-6">
						<div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center shrink-0 border border-blue-100">
							<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path></svg>
						</div>
						<div>
							<span class="text-[10px] font-bold tracking-wider text-blue-500 uppercase">Untuk Wali Murid & Umum</span>
							<h2 class="text-xl font-bold text-gray-900 mt-0.5">Aplikasi Berita Sekolah</h2>
						</div>
					</div>
                    
					<p class="text-sm text-gray-500 leading-relaxed mb-6">
						Aplikasi portal berita resmi untuk memantau kegiatan siswa, pengumuman penting, galeri foto, dan berbagai informasi terkini seputar sekolah. Tetap terhubung dengan dunia pendidikan buah hati Anda.
					</p>
					 <div class="bg-white p-3 rounded-2xl shadow-md border border-gray-100 mb-6 block mx-auto w-fit">
    <!-- Ganti parameter 'data=' dengan link asli aplikasi berita Uwa -->
    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ rawurlencode($beritaLink) }}" alt="QR Code Berita" class="w-32 h-32 md:w-40 md:h-40">
</div>

					<div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 flex items-center justify-between gap-4">
						<div class="flex-1">
                          
                         
							<p class="text-xs font-semibold text-gray-900 mb-1">Cara Unduh:</p>
							<p class="text-[11px] text-gray-500 leading-relaxed">
								Scan QR code di samping menggunakan kamera HP Anda, atau klik tombol unduh di bawah untuk men-download file APK.
							</p>
						</div>
						<!-- QR Code Generator Otomatis (Ganti data= link aplikasi Uwa nanti) -->
                       
					</div>
				</div>
                
				<div class="p-6 bg-gray-50 border-t border-gray-100">
					<a href="{{ $beritaLink }}" class="w-full download-primary text-white font-bold py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 transition-colors shadow-lg shadow-blue-500/30">
						<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
						Download APK Berita
					</a>
				</div>
			</div>

			<!-- ============================================== -->
			<!-- CARD 2: APLIKASI GERBANG (PRESENSI)            -->
			<!-- ============================================== -->
			<div class="bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.08)] overflow-hidden border border-gray-100 flex flex-col hover:-translate-y-1 transition-transform duration-300">
				<div class="p-8 flex-1">
					<div class="flex items-center gap-4 mb-6">
						<div class="w-14 h-14 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center shrink-0 border border-amber-100">
							<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
						</div>
						<div>
							<span class="text-[10px] font-bold tracking-wider text-amber-500 uppercase">Khusus Guru & Staf</span>
							<h2 class="text-xl font-bold text-gray-900 mt-0.5">Aplikasi GERBANG</h2>
						</div>
					</div>
                    
					<p class="text-sm text-gray-500 leading-relaxed mb-6">
						Aplikasi sistem informasi terpadu khusus untuk Guru dan Tenaga Kependidikan. Gunakan aplikasi ini untuk mencatat presensi kehadiran harian (Scan QR/GPS), mengajukan izin, dan melihat riwayat absensi.
					</p>
					<div class="bg-white p-3 rounded-2xl shadow-md border border-gray-100 mb-6 block mx-auto w-fit">
    <!-- Ganti parameter 'data=' dengan link asli aplikasi berita Uwa -->
    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ rawurlencode($gerbangLink) }}" alt="QR Code Gerbang" class="w-32 h-32 md:w-40 md:h-40">
</div>

					<div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 flex items-center justify-between gap-4">
						<div class="flex-1">
							<p class="text-xs font-semibold text-gray-900 mb-1">Cara Unduh:</p>
							<p class="text-[11px] text-gray-500 leading-relaxed">
								Scan QR code di samping menggunakan kamera HP Anda, atau klik tombol unduh di bawah untuk men-download file APK.
							</p>
						</div>
						<!-- QR Code Generator Otomatis (Ganti data= link aplikasi Uwa nanti) -->
                      
					</div>
				</div>
                
				<div class="p-6 bg-gray-50 border-t border-gray-100">
					<a href="{{ $gerbangLink }}" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 transition-colors shadow-lg shadow-gray-900/30">
						<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
						Download APK Gerbang
					</a>
				</div>
			</div>

		</div>
        
		<!-- Informasi Tambahan / Bantuan -->
		<div class="mt-12 bg-blue-50/50 border border-blue-100 rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
			<div class="flex items-center gap-4">
				<div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm shrink-0 text-blue-500">
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
				</div>
				<div>
					<h4 class="text-sm font-bold text-gray-900">Kendala saat menginstal?</h4>
					<p class="text-xs text-gray-500 mt-0.5">Pastikan fitur "Install from Unknown Sources" diaktifkan di HP Anda.</p>
				</div>
			</div>
			<a href="{{ $installGuideLink ?: '#' }}" class="text-xs font-bold download-primary-text bg-white px-4 py-2 rounded-lg border border-blue-200 shadow-sm hover:bg-gray-50 transition-colors whitespace-nowrap">
				Lihat Panduan Install
			</a>
		</div>
	</main>

	@include('publik.tampilan.footer_navbar', ['slotPosition' => 'footer'])

	

</body>
</html>
