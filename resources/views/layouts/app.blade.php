<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Peminjaman Barang') - UKK RPL 2026</title>
    
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50 flex flex-col md:flex-row">

    @auth
    <!-- SIDEBAR NAVIGATION -->
    <aside class="w-full md:w-64 bg-slate-900 text-slate-300 flex-shrink-0 flex flex-col justify-between shadow-xl z-20">
        <div>
            <!-- Brand Logo -->
            <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-emerald-400 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-emerald-500/20">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-white text-base leading-tight tracking-tight">PinjamSarpras</h1>
                        <span class="text-xs text-emerald-400 font-medium tracking-wide">UKK RPL 2026</span>
                    </div>
                </div>
            </div>

            <!-- Profile Overview Badge -->
            <div class="px-6 py-4 border-b border-slate-800/80 bg-slate-950/40">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-full bg-slate-700 flex items-center justify-center text-white text-sm font-semibold border border-slate-600">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="truncate">
                        <div class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                            @if(Auth::user()->isAdmin()) bg-amber-500/20 text-amber-300 border border-amber-500/30
                            @elseif(Auth::user()->isPetugas()) bg-blue-500/20 text-blue-300 border border-blue-500/30
                            @else bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 @endif">
                            {{ Auth::user()->role }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="px-4 py-4 space-y-1.5 text-sm font-medium">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('dashboard') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('alat.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('alat.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-toolbox w-5 text-center"></i>
                    <span>Katalog Alat</span>
                </a>

                <a href="{{ route('peminjaman.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('peminjaman.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-handshake w-5 text-center"></i>
                    <span>{{ Auth::user()->role === 'peminjam' ? 'Riwayat Pinjam' : 'Data Peminjaman' }}</span>
                </a>

                @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
                <div class="pt-3 pb-1 px-3 text-[11px] font-bold text-slate-300 uppercase tracking-wider">
                    Kelola Master & Laporan
                </div>

                <a href="{{ route('kategori.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('kategori.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-tags w-5 text-center"></i>
                    <span>Kategori Alat</span>
                </a>

                <a href="{{ route('laporan.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('laporan.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-file-lines w-5 text-center"></i>
                    <span>Laporan & Rekap</span>
                </a>
                @endif

                @if(Auth::user()->isAdmin())
                <div class="pt-3 pb-1 px-3 text-[11px] font-bold text-slate-300 uppercase tracking-wider">
                    Administrasi Sistem
                </div>

                <a href="{{ route('user.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('user.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-users-gear w-5 text-center"></i>
                    <span>Manajemen User</span>
                </a>

                <a href="{{ route('log.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('log.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i>
                    <span>Log Aktivitas</span>
                </a>
                @endif
            </nav>
        </div>

        <!-- Logout Bottom -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/20">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 px-3 py-2 text-sm font-medium text-rose-400 hover:bg-rose-500/10 rounded-lg transition-all">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
        </div>
    </aside>
    @endauth

    <!-- MAIN CONTENT WRAPPER -->
    <main class="flex-1 flex flex-col min-w-0 overflow-y-auto custom-scrollbar">
        @auth
        <!-- Top Navbar Header -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-10 px-6 py-4 flex items-center justify-between shadow-xs">
            <div>
                <h2 class="text-xl font-bold text-slate-900">@yield('page_title', 'Dashboard')</h2>
                <p class="text-xs text-slate-500">@yield('page_subtitle', 'Sistem Peminjaman & Inventaris Sarana Sekolah')</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-xs text-slate-500 font-medium bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200">
                    <i class="fa-regular fa-calendar text-brand-600 mr-1.5"></i> {{ date('d F Y') }}
                </span>
            </div>
        </header>
        @endauth

        <!-- Flash Messages -->
        <div class="p-6 pb-0 max-w-7xl w-full mx-auto">
            @if(session('success'))
            <div class="mb-4 flex items-center justify-between p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-xs animate-fade-in">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 flex items-center justify-between p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl shadow-xs animate-fade-in">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 text-lg"></i>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-4 p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl shadow-xs">
                <div class="flex items-center space-x-2 font-bold mb-2 text-sm">
                    <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
                    <span>Terdapat beberapa kesalahan input:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <!-- Main Content Injection -->
        <div class="p-6 max-w-7xl w-full mx-auto flex-1">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="mt-auto px-6 py-4 border-t border-slate-200 text-center text-xs text-slate-400 bg-white">
            &copy; 2026 UKK RPL Paket 1 - Aplikasi Peminjaman Alat & Sarana Sekolah
        </footer>
    </main>

    @stack('scripts')
</body>
</html>
