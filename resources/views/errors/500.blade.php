<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Kendala Sistem | Racer Robotic</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 antialiased text-slate-800">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-xl border border-slate-200/80 p-8 text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-amber-500 via-rose-500 to-indigo-600"></div>

        <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-inner">
            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-slate-900 mb-2">Terjadi Kendala pada Sistem</h1>
        <p class="text-slate-600 text-sm mb-6 leading-relaxed">
            Maaf, sistem mendeteksi kendala teknis internal saat memproses permintaan Anda. Demi keamanan data dan privasi sistem, detail teknis mentah tidak ditampilkan ke publik.
        </p>

        <!-- Incident ID Box -->
        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 mb-6 text-left">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                Kode Referensi Insiden (Incident ID):
            </div>
            <div class="flex items-center justify-between bg-white px-3 py-2 rounded-lg border border-slate-300 font-mono text-sm text-indigo-700 font-bold">
                <span>{{ $incidentId ?? 'ERR-' . date('Ymd') . '-GEN500' }}</span>
                <span class="text-xs font-sans font-normal text-slate-400">Tercatat di server</span>
            </div>
            <p class="text-xs text-slate-500 mt-2">
                Laporkan kode insiden di atas kepada tim administrator untuk investigasi cepat melalui log terisolasi.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 shadow-md transition-all duration-150">
                Kembali ke Beranda
            </a>
            <button onclick="window.location.reload();" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 transition-all duration-150">
                Muat Ulang Halaman
            </button>
        </div>
    </div>
</body>
</html>
