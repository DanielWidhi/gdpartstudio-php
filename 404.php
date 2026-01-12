<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>404 Page Not Found - GDPARTSTUDIO</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#1e293b", 
                        "secondary": "#64748b",
                        "accent": "#0f172a", 
                        "background-light": "#ffffff",
                        "surface-light": "#f8fafc",
                        "surface-hover": "#f1f5f9",
                        "brand-gold": "#C5A059"
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"],
                        "serif": ["Playfair Display", "serif"],
                        "body": ["Noto Sans", "sans-serif"],
                    },
                },
            },
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        }
    </style>
    
    <!-- Base URL Helper (Agar CSS/Gambar tidak rusak jika 404 terjadi di sub-folder dalam) -->
    <!-- Sesuaikan URL ini dengan alamat lokal Anda, misal http://localhost/gdpartstudio/ -->
    <base href="/">
</head>
<body class="bg-background-light text-primary font-body antialiased selection:bg-slate-200 selection:text-slate-900 overflow-x-hidden">
<div class="flex min-h-screen flex-col">
    
    <!-- HEADER -->
    <header class="fixed top-0 z-40 w-full border-b border-gray-100 bg-white/90 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
            <div class="flex items-center gap-3 cursor-pointer" onclick="window.location.href='index.php'">
                <div class="flex size-10 items-center justify-center rounded-lg bg-slate-900 text-white shadow-md ring-1 ring-slate-900/5">
                    <span class="material-symbols-outlined text-[24px]">shutter_speed</span>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="font-display text-lg font-bold tracking-tight text-slate-900 uppercase">GDPARTSTUDIO</span>
                    <span class="text-[10px] tracking-widest text-slate-500 uppercase font-medium">Visual Storytellers</span>
                </div>
            </div>
            <nav class="hidden md:flex flex-1 justify-center gap-10">
                <a class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors" href="index.php">Home</a>
                <a class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors" href="portfolio.php">Portfolio</a>
                <a class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors" href="services.php">Services</a>
                <a class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors" href="contact.php">Contact</a>
            </nav>
            <div class="flex items-center gap-4">
                <button class="hidden sm:flex items-center justify-center rounded-full bg-slate-900 px-6 py-2.5 text-sm font-semibold text-white transition-all hover:bg-slate-800 shadow-sm">
                    Get Quote
                </button>
                <button class="md:hidden text-slate-900 p-2 hover:bg-slate-100 rounded-full transition-colors">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-grow flex items-center justify-center pt-24 pb-12">
        <div class="mx-auto max-w-4xl px-6 text-center">
            
            <!-- Icon Illustration -->
            <div class="relative mx-auto mb-12 w-32 h-32 flex items-center justify-center text-slate-200">
                <div class="absolute inset-0 bg-slate-50 rounded-full blur-2xl opacity-50"></div>
                <span class="material-symbols-outlined text-8xl relative z-10 text-slate-300">photo_camera</span>
                <div class="absolute -bottom-2 right-4 bg-white p-2 rounded-full shadow-lg border border-slate-100">
                    <span class="material-symbols-outlined text-red-400 font-bold">search_off</span>
                </div>
            </div>

            <!-- Text Content -->
            <div class="space-y-6">
                <h1 class="font-serif text-5xl sm:text-7xl font-bold tracking-tight text-slate-900 leading-[1.1]">
                    Lost Moments, <br/> <span class="text-transparent bg-clip-text bg-gradient-to-r from-slate-400 to-slate-600 italic font-normal">Still Unseen</span>
                </h1>
                <p class="mt-8 mx-auto max-w-lg text-lg text-slate-500 font-light leading-relaxed">
                    The frame you are looking for doesn't exist. It might have been moved or the memory has faded from our servers.
                </p>
                
                <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a class="inline-flex items-center justify-center rounded-full bg-slate-900 px-10 py-4 text-sm font-semibold text-white transition-all hover:bg-slate-800 shadow-md hover:shadow-xl hover:-translate-y-0.5" href="index.php">
                        Kembali ke Beranda
                    </a>
                </div>

                <!-- Footer Links -->
                <div class="mt-16 pt-12 border-t border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Or explore our galleries</p>
                    <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-4">
                        <a class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-1" href="portfolio.php">
                            <span class="material-symbols-outlined text-lg">favorite</span> Wedding
                        </a>
                        <a class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-1" href="portfolio.php">
                            <span class="material-symbols-outlined text-lg">auto_awesome</span> Religious
                        </a>
                        <a class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-1" href="services.php">
                            <span class="material-symbols-outlined text-lg">event_note</span> Corporate Events
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-100 py-12">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="flex items-center gap-2 text-slate-900">
                    <div class="flex size-8 items-center justify-center rounded-lg bg-slate-900 text-white">
                        <span class="material-symbols-outlined text-[18px]">shutter_speed</span>
                    </div>
                    <h2 class="text-lg font-bold tracking-tight font-display">GDPARTSTUDIO</h2>
                </div>
                <div class="flex gap-8">
                    <a class="text-xs font-medium text-slate-400 hover:text-slate-900 transition-colors" href="#">Instagram</a>
                    <a class="text-xs font-medium text-slate-400 hover:text-slate-900 transition-colors" href="#">Facebook</a>
                    <a class="text-xs font-medium text-slate-400 hover:text-slate-900 transition-colors" href="#">YouTube</a>
                </div>
                <p class="text-xs text-slate-400">© <?= date('Y') ?> GDPARTSTUDIO. Page Not Found (404).</p>
            </div>
        </div>
    </footer>
</div>
</body>
</html>