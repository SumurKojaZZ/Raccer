<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | Racer Robotic</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 antialiased text-slate-800">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-xl border border-slate-200/80 p-8 text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-amber-400 via-orange-500 to-rose-600"></div>

        <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-inner">
            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m7-7V7a5 5 0 00-10 0v3m-2 4h14a2 2 0 012 2v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4a2 2 0 012-2z" />
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-slate-900 mb-2">Akses Ditolak (403 Forbidden)</h1>
        <p class="text-slate-600 text-sm mb-6 leading-relaxed">
            Anda tidak memiliki wewenang atau hak akses untuk melihat halaman ini. Percobaan akses tidak sah dicatat demi keamanan sistem.
        </p>

        <!-- Incident ID Box -->
        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 mb-6 text-left">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                Kode Referensi Keamanan:
            </div>
            <div class="flex items-center justify-between bg-white px-3 py-2 rounded-lg border border-slate-300 font-mono text-sm text-amber-700 font-bold">
                <span>{{ $incidentId ?? 'ERR-' . date('Ymd') . '-SEC403' }}</span>
                <span class="text-xs font-sans font-normal text-slate-400">Security Audit Log</span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 shadow-md transition-all duration-150">
                Kembali ke Beranda
            </a>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 transition-all duration-150">
                Masuk Akun Lain
            </a>
        </div>
    </div>
</body>
</html>
