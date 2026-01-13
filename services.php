<?php
include 'db.php'; // Koneksi database (untuk konsistensi sistem)
?>

<!DOCTYPE html>
<html class="scroll-smooth" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>GDPARTSTUDIO - Layanan & Harga</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&amp;family=Inter:wght@300;400;500;600&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#3b82f6", 
                        "primary-hover": "#2563eb",
                        "light-bg": "#f8fafc",
                        "card-bg": "#ffffff",
                        "card-border": "#e2e8f0",
                        "text-main": "#0f172a",
                        "text-muted": "#64748b",
                        "accent-soft": "#f0f9ff",
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"],
                        "body": ["Inter", "sans-serif"],
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                        'hover': '0 10px 40px -5px rgba(59, 130, 246, 0.15)',
                        'card': '0 2px 8px rgba(0,0,0,0.04)',
                    },
                    backgroundImage: {
                        'grid-pattern': "linear-gradient(to right, #f1f5f9 1px, transparent 1px), linear-gradient(to bottom, #f1f5f9 1px, transparent 1px)",
                    }
                },
            },
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            .text-balance { text-wrap: balance; }
        }
    </style>
</head>
<body class="bg-light-bg text-text-main font-body antialiased selection:bg-primary/20 selection:text-primary">

<!-- NAVBAR -->
    <?php 
        $currentPage = 'services'; 
        include 'assets/components/navbar/navbar.php'; 
    ?>

<main class="flex flex-col min-h-screen">
    
    <!-- HERO SECTION -->
    <header class="relative w-full py-24 lg:py-32 flex items-center justify-center overflow-hidden bg-white">
        <div class="absolute inset-0 bg-grid-pattern [background-size:24px_24px] [mask-image:linear-gradient(to_bottom,white,transparent)]"></div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>
        
        <div class="relative z-20 text-center max-w-4xl px-4 flex flex-col gap-8 items-center">
            <div class="inline-flex items-center gap-2.5 px-5 py-2 rounded-full border border-primary/10 bg-accent-soft shadow-sm mb-2 backdrop-blur-sm">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-primary"></span>
                </span>
                <span class="text-xs font-bold text-primary uppercase tracking-widest">Open Booking 2024-2025</span>
            </div>
            
            <h2 class="text-text-main text-5xl md:text-7xl font-display font-bold leading-[1.1] tracking-tight text-balance">
                Abadikan <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-blue-400">Momen Abadi</span> Anda
            </h2>
            
            <p class="text-text-muted text-lg md:text-xl max-w-2xl font-light leading-relaxed text-balance">
                Layanan fotografi dan videografi profesional dengan sentuhan minimalis modern. Kami merangkai cerita visual yang elegan untuk setiap momen berharga Anda.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 mt-8 w-full justify-center">
                <a class="flex items-center justify-center rounded-full h-14 px-8 bg-text-main text-white text-base font-semibold hover:bg-primary hover:shadow-xl hover:shadow-primary/20 transition-all duration-300 hover:-translate-y-1" href="#weddings">
                    Lihat Paket
                </a>
                <a class="flex items-center justify-center rounded-full h-14 px-8 bg-white text-text-main border border-gray-200 text-base font-semibold hover:bg-gray-50 transition-all hover:border-gray-300 shadow-sm hover:shadow-md group" href="#contact">
                    Hubungi Kami
                    <span class="material-symbols-outlined ml-2 text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
            </div>
        </div>
    </header>

    <!-- STICKY SUB-NAV -->
    <div class="sticky top-[73px] z-40 bg-white/80 backdrop-blur-lg border-b border-gray-100 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-start md:justify-center overflow-x-auto no-scrollbar gap-2 py-4 px-2">
                <a class="px-6 py-2.5 rounded-full bg-accent-soft text-primary border border-primary/10 font-semibold whitespace-nowrap transition-colors shadow-sm ring-2 ring-transparent focus:ring-primary/20" href="#weddings">Wedding</a>
                <a class="px-6 py-2.5 rounded-full bg-transparent hover:bg-gray-50 text-text-muted hover:text-text-main font-medium whitespace-nowrap transition-colors" href="#religious">Upacara Keagamaan</a>
                <a class="px-6 py-2.5 rounded-full bg-transparent hover:bg-gray-50 text-text-muted hover:text-text-main font-medium whitespace-nowrap transition-colors" href="#events">Dokumentasi Event</a>
            </div>
        </div>
    </div>

    <div class="w-full max-w-7xl mx-auto px-4 md:px-8 py-20 space-y-32">
        
        <!-- WEDDING SECTION -->
        <section class="scroll-mt-32" id="weddings">
            <div class="text-center mb-16 space-y-4 relative">
                <span class="text-primary font-bold tracking-widest uppercase text-xs bg-accent-soft px-3 py-1 rounded-md">Untuk Hari Spesial Anda</span>
                <h3 class="text-text-main text-4xl font-display font-bold mt-4">Paket Wedding</h3>
                <p class="text-text-muted max-w-2xl mx-auto text-lg">Pilih cakupan yang sempurna untuk pernikahan Anda. Transparan, tanpa biaya tersembunyi.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Silver -->
                <div class="group relative flex flex-col p-8 rounded-3xl bg-white border border-gray-200 hover:border-primary/40 transition-all duration-300 shadow-card hover:shadow-hover h-full">
                    <div class="mb-6">
                        <h4 class="text-text-main text-2xl font-display font-bold mb-2">Silver</h4>
                        <p class="text-text-muted text-sm leading-relaxed">Cakupan esensial untuk akad nikah atau resepsi intim.</p>
                    </div>
                    <div class="mb-8 pb-8 border-b border-gray-100">
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-bold text-text-main tracking-tight">IDR 3.000k</span>
                            <span class="text-text-muted text-sm font-medium">/event</span>
                        </div>
                    </div>
                    <ul class="flex flex-col gap-4 mb-8 flex-1">
                        <li class="flex gap-3 text-text-muted text-sm items-center"><div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-50 text-green-600 flex items-center justify-center"><span class="material-symbols-outlined text-sm font-bold">check</span></div><span>4 Jam Liputan</span></li>
                        <li class="flex gap-3 text-text-muted text-sm items-center"><div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-50 text-green-600 flex items-center justify-center"><span class="material-symbols-outlined text-sm font-bold">check</span></div><span>1 Fotografer Profesional</span></li>
                        <li class="flex gap-3 text-text-muted text-sm items-center"><div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-50 text-green-600 flex items-center justify-center"><span class="material-symbols-outlined text-sm font-bold">check</span></div><span>50 Foto Edit High-Res</span></li>
                        <li class="flex gap-3 text-text-muted text-sm items-center"><div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-50 text-green-600 flex items-center justify-center"><span class="material-symbols-outlined text-sm font-bold">check</span></div><span>Akses Galeri Online</span></li>
                    </ul>
                    <button class="w-full py-4 rounded-xl bg-gray-50 hover:bg-white text-text-main font-semibold transition-all border border-gray-200 hover:border-primary hover:text-primary hover:shadow-lg hover:shadow-primary/10 mt-auto">Pilih Silver</button>
                </div>

                <!-- Gold (Highlighted) -->
                <div class="group relative flex flex-col p-8 rounded-3xl bg-white border-2 border-primary shadow-xl shadow-primary/10 transform lg:-translate-y-4 z-10 h-full">
                    <div class="absolute top-0 right-0 p-6">
                        <span class="bg-gradient-to-r from-primary to-blue-500 text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-md shadow-blue-500/20">Paling Populer</span>
                    </div>
                    <div class="mb-6">
                        <h4 class="text-text-main text-2xl font-display font-bold mb-2">Gold</h4>
                        <p class="text-text-muted text-sm leading-relaxed">Pilihan standar untuk pernikahan lengkap, foto &amp; video.</p>
                    </div>
                    <div class="mb-8 pb-8 border-b border-gray-100">
                        <div class="flex items-baseline gap-1">
                            <span class="text-5xl font-bold text-text-main tracking-tight">IDR 5.500k</span>
                            <span class="text-text-muted text-sm font-medium">/event</span>
                        </div>
                    </div>
                    <ul class="flex flex-col gap-4 mb-8 flex-1">
                        <li class="flex gap-3 text-text-main text-sm font-medium items-center"><div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center shadow-sm shadow-primary/30"><span class="material-symbols-outlined text-sm font-bold">check</span></div><span>8 Jam Liputan</span></li>
                        <li class="flex gap-3 text-text-main text-sm font-medium items-center"><div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center shadow-sm shadow-primary/30"><span class="material-symbols-outlined text-sm font-bold">check</span></div><span>1 Fotografer + 1 Videografer</span></li>
                        <li class="flex gap-3 text-text-main text-sm font-medium items-center"><div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center shadow-sm shadow-primary/30"><span class="material-symbols-outlined text-sm font-bold">check</span></div><span>100 Foto Edit</span></li>
                        <li class="flex gap-3 text-text-main text-sm font-medium items-center"><div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center shadow-sm shadow-primary/30"><span class="material-symbols-outlined text-sm font-bold">check</span></div><span>1 Menit Cinematic Highlight</span></li>
                        <li class="flex gap-3 text-text-main text-sm font-medium items-center"><div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center shadow-sm shadow-primary/30"><span class="material-symbols-outlined text-sm font-bold">check</span></div><span>Flashdisk Output Exclusive</span></li>
                    </ul>
                    <button class="w-full py-4 rounded-xl bg-primary hover:bg-primary-hover text-white font-bold transition-all shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-0.5 mt-auto">Pilih Gold</button>
                </div>

                <!-- Platinum -->
                <div class="group relative flex flex-col p-8 rounded-3xl bg-white border border-gray-200 hover:border-primary/40 transition-all duration-300 shadow-card hover:shadow-hover h-full">
                    <div class="mb-6">
                        <h4 class="text-text-main text-2xl font-display font-bold mb-2">Platinum</h4>
                        <p class="text-text-muted text-sm leading-relaxed">Pengalaman sinematik seharian penuh dengan drone.</p>
                    </div>
                    <div class="mb-8 pb-8 border-b border-gray-100">
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-bold text-text-main tracking-tight">IDR 8.000k</span>
                            <span class="text-text-muted text-sm font-medium">/event</span>
                        </div>
                    </div>
                    <ul class="flex flex-col gap-4 mb-8 flex-1">
                        <li class="flex gap-3 text-text-muted text-sm items-center"><div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-50 text-green-600 flex items-center justify-center"><span class="material-symbols-outlined text-sm font-bold">check</span></div><span>Seharian Penuh (12 Jam)</span></li>
                        <li class="flex gap-3 text-text-muted text-sm items-center"><div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-50 text-green-600 flex items-center justify-center"><span class="material-symbols-outlined text-sm font-bold">check</span></div><span>2 Fotografer + 2 Videografer</span></li>
                        <li class="flex gap-3 text-text-muted text-sm items-center"><div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-50 text-green-600 flex items-center justify-center"><span class="material-symbols-outlined text-sm font-bold">check</span></div><span>Foto Edit Unlimited</span></li>
                        <li class="flex gap-3 text-text-muted text-sm items-center"><div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-50 text-green-600 flex items-center justify-center"><span class="material-symbols-outlined text-sm font-bold">check</span></div><span>Full Movie + Teaser</span></li>
                        <li class="flex gap-3 text-text-muted text-sm items-center"><div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-50 text-green-600 flex items-center justify-center"><span class="material-symbols-outlined text-sm font-bold">check</span></div><span>Termasuk Liputan Drone</span></li>
                    </ul>
                    <button class="w-full py-4 rounded-xl bg-gray-50 hover:bg-white text-text-main font-semibold transition-all border border-gray-200 hover:border-primary hover:text-primary hover:shadow-lg hover:shadow-primary/10 mt-auto">Pilih Platinum</button>
                </div>
            </div>
        </section>

        <!-- IMAGE GALLERY BREAK -->
        <section class="py-12 relative">
            <div class="absolute inset-0 bg-gray-50 skew-y-3 -z-10 transform origin-top-left scale-110"></div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 h-[320px]">
                <div class="rounded-2xl overflow-hidden relative group cursor-pointer shadow-md h-full">
                    <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBiIwjCFoi0AEOFRoavAfeFJtlUNLHw9F1bRufed16VqJ6uN0ZjM5jWxuSi_JVfLQuu90IOnEfsJnyChaAA4hBVbWOoZcW41GGsk99Wkas837CrkA8J2m8ZOz1SP8Kpa-n1C3cHVGvloxOanjdBjEd7kBfqx_uPSZajeb2MBBFmX65LgvQOZj-1jfGu2Ok0Ju8G_lF9ZMNTn7HczIywEEcENLOTuSbVOugXP--UcAm_Xk5Zr9QhSo0zNnXRBQIMyxDU9VMhWaJBkEI"/>
                </div>
                <div class="rounded-2xl overflow-hidden relative group cursor-pointer shadow-md h-full md:-translate-y-8">
                    <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDzEzi3AMfLSezPEkUVqlNOdCttvqej0MuCw1TROvXGcPV4fcrg9LZNF8ruAUy9XJqSt2Pq1Pxgmtsji6QaJ1ilq1MglyvEkAyhV6WY5btOuBWectBMmnZPcG1GOniCaeM_TH3gLjgK4yjVX3mBFTv6M8vUcvAsJaDd3thJp9EJz4Afa-y1-sNtDF6h2A8clks9wbP1dHVmPq4LA0A88x7LtLt_dfKeBLhY9KUf7oUI8Pddcf6Evg-3WSkAWKW2O0CynJP5u6w7TFw"/>
                </div>
                <div class="rounded-2xl overflow-hidden relative group cursor-pointer shadow-md h-full">
                    <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBVQFElFjHgScvsg2KLOXZyJGSHI6rDT96wtIXHX1ab8TMccPNE_YuAxLQEx66PO4uTHLdvS1qFOfV74iMhkxNu5xeuW_mro8yYyPdc1qWjcq9WtwLF4ecl_KyISB0GjSE-4smEWv90zorXwY1y7qwn_-1YP1Ntc_GUWUQJh1nZOGq1ag0DPToR8Tp55_vQE0-Jg649ARJq1oyc_19KrloTyZFIiO1a8F8TyEfph-pFhByt-_Ay51HKwzMcMdQ0fydrlWIpJGFWigU"/>
                </div>
                <div class="rounded-2xl overflow-hidden relative group cursor-pointer shadow-md h-full md:-translate-y-8">
                    <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDDsptxhDf00R5KkcsS89vJQFJMMAfbaXdN8L9VTeMP_nQ-sYyovG8eEFznUVGSJapaXLuuus_-y83NDuYlxc1aoJL-kjTKvBD7UbEfmczPHInQCVQO8YOhWRqUo2pDu_SgtXsZISXjOLMa_Opmi9_92_KzboWFyxTN1l1ETK6EVfTwLaMimPkBIRscHgTZgfvSHiZqChmENRQnI_HcHKlQ6fW3nH6Dsfgjnl2q8K3-2IwWrW-LSDGDq8mD8nraQ2rirWOb1PCs4HM"/>
                </div>
            </div>
            <div class="flex justify-center mt-12">
                <a class="inline-flex items-center gap-3 text-sm font-bold text-text-main hover:text-primary transition-colors group px-8 py-4 rounded-full bg-white shadow-lg shadow-gray-200/50 border border-gray-100 hover:shadow-xl hover:-translate-y-1 duration-300" href="portfolio.php">
                    Lihat Portofolio Lengkap
                    <span class="material-symbols-outlined text-xl group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
            </div>
        </section>

        <!-- RELIGIOUS SECTION -->
        <section class="scroll-mt-32" id="religious">
            <div class="text-center mb-16 space-y-4">
                <span class="text-primary font-bold tracking-widest uppercase text-xs bg-accent-soft px-3 py-1 rounded-md">Momen Sakral</span>
                <h3 class="text-text-main text-3xl md:text-4xl font-display font-bold mt-4">Upacara Keagamaan</h3>
                <p class="text-text-muted max-w-xl mx-auto text-base">Dokumentasi yang hormat dan tidak mengganggu untuk tradisi dan upacara paling suci Anda.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Card 1 -->
                <div class="flex flex-col p-8 rounded-3xl bg-white border border-gray-200 hover:border-primary/30 transition-all duration-300 shadow-card hover:shadow-hover">
                    <div class="flex items-start gap-6 mb-6">
                        <div class="p-4 rounded-2xl bg-blue-50 text-primary shadow-sm">
                            <span class="material-symbols-outlined text-3xl">church</span>
                        </div>
                        <div>
                            <h4 class="text-text-main text-xl font-bold">Akad / Pemberkatan</h4>
                            <div class="mt-1 flex items-baseline gap-1">
                                <span class="text-sm text-text-muted">Mulai dari</span>
                                <span class="text-primary font-bold text-lg">IDR 2.500k</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-text-muted text-sm mb-8 leading-relaxed">Fokus pada prosesi seremonial utama dengan detail yang emosional.</p>
                    <div class="space-y-4 mb-8 flex-1 border-t border-gray-100 pt-6">
                        <div class="flex gap-3 text-text-muted text-sm items-center"><span class="material-symbols-outlined text-primary text-xl">check_small</span>3 Jam Liputan</div>
                        <div class="flex gap-3 text-text-muted text-sm items-center"><span class="material-symbols-outlined text-primary text-xl">check_small</span>1 Fotografer</div>
                        <div class="flex gap-3 text-text-muted text-sm items-center"><span class="material-symbols-outlined text-primary text-xl">check_small</span>Semua file raw disertakan</div>
                    </div>
                    <button class="w-full py-3.5 rounded-xl bg-gray-50 hover:bg-white text-text-main font-semibold transition-all border border-gray-200 hover:border-primary hover:text-primary hover:shadow-md mt-auto">Pesan Sekarang</button>
                </div>
                <!-- Card 2 -->
                <div class="flex flex-col p-8 rounded-3xl bg-white border border-gray-200 hover:border-primary/30 transition-all duration-300 shadow-card hover:shadow-hover">
                    <div class="flex items-start gap-6 mb-6">
                        <div class="p-4 rounded-2xl bg-blue-50 text-primary shadow-sm">
                            <span class="material-symbols-outlined text-3xl">diversity_3</span>
                        </div>
                        <div>
                            <h4 class="text-text-main text-xl font-bold">Resepsi &amp; Upacara</h4>
                            <div class="mt-1 flex items-baseline gap-1">
                                <span class="text-sm text-text-muted">Mulai dari</span>
                                <span class="text-primary font-bold text-lg">IDR 4.000k</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-text-muted text-sm mb-8 leading-relaxed">Cakupan komprehensif termasuk persiapan, ritual adat, dan resepsi.</p>
                    <div class="space-y-4 mb-8 flex-1 border-t border-gray-100 pt-6">
                        <div class="flex gap-3 text-text-muted text-sm items-center"><span class="material-symbols-outlined text-primary text-xl">check_small</span>6 Jam Liputan</div>
                        <div class="flex gap-3 text-text-muted text-sm items-center"><span class="material-symbols-outlined text-primary text-xl">check_small</span>Foto + Video Highlight</div>
                        <div class="flex gap-3 text-text-muted text-sm items-center"><span class="material-symbols-outlined text-primary text-xl">check_small</span>80 Foto Edit</div>
                    </div>
                    <button class="w-full py-3.5 rounded-xl bg-gray-50 hover:bg-white text-text-main font-semibold transition-all border border-gray-200 hover:border-primary hover:text-primary hover:shadow-md mt-auto">Pesan Sekarang</button>
                </div>
            </div>
        </section>

        <!-- EVENTS SECTION -->
        <section class="scroll-mt-32" id="events">
            <div class="text-center mb-16 space-y-4">
                <span class="text-primary font-bold tracking-widest uppercase text-xs bg-accent-soft px-3 py-1 rounded-md">Korporat &amp; Kasual</span>
                <h3 class="text-text-main text-4xl font-display font-bold mt-4">Dokumentasi Event</h3>
                <p class="text-text-muted max-w-2xl mx-auto text-lg">Tarif fleksibel untuk ulang tahun, gathering, workshop, dan lainnya.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Hourly -->
                <div class="group relative flex flex-col p-8 rounded-3xl bg-white border border-gray-200 hover:border-primary/30 transition-all duration-300 shadow-card hover:shadow-hover">
                    <div class="w-14 h-14 mb-6 rounded-2xl bg-blue-50 flex items-center justify-center text-primary group-hover:scale-110 transition-transform shadow-sm">
                        <span class="material-symbols-outlined text-3xl">schedule</span>
                    </div>
                    <h4 class="text-text-main text-xl font-bold mb-3">Tarif Per Jam</h4>
                    <p class="text-text-muted text-sm mb-6 flex-1 leading-relaxed">Fleksibel untuk jenis acara apapun dengan minimal booking 2 jam.</p>
                    <div class="mb-6 pt-6 border-t border-gray-100">
                        <span class="text-3xl font-bold text-text-main">IDR 750k</span>
                        <span class="text-text-muted text-sm font-medium">/ jam</span>
                    </div>
                    <button class="w-full h-12 rounded-xl bg-white border border-gray-200 text-text-main font-semibold transition-all hover:bg-gray-50 hover:border-gray-300 hover:shadow-sm">Pesan Jam</button>
                </div>
                <!-- Full Day -->
                <div class="group relative flex flex-col p-8 rounded-3xl bg-white border border-gray-200 hover:border-primary/30 transition-all duration-300 shadow-card hover:shadow-hover ring-1 ring-primary/5">
                    <div class="w-14 h-14 mb-6 rounded-2xl bg-primary text-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md shadow-primary/20">
                        <span class="material-symbols-outlined text-3xl">calendar_month</span>
                    </div>
                    <h4 class="text-text-main text-xl font-bold mb-3">Full Day Event</h4>
                    <p class="text-text-muted text-sm mb-6 flex-1 leading-relaxed">Cakupan lengkap untuk seminar atau konferensi seharian (8 jam).</p>
                    <div class="mb-6 pt-6 border-t border-gray-100">
                        <span class="text-3xl font-bold text-text-main">IDR 5.000k</span>
                        <span class="text-text-muted text-sm font-medium">/ hari</span>
                    </div>
                    <button class="w-full h-12 rounded-xl bg-primary hover:bg-primary-hover text-white font-semibold transition-all shadow-lg shadow-primary/20">Pesan Hari</button>
                </div>
                <!-- Video Add-on -->
                <div class="group relative flex flex-col p-8 rounded-3xl bg-white border border-gray-200 hover:border-primary/30 transition-all duration-300 shadow-card hover:shadow-hover">
                    <div class="w-14 h-14 mb-6 rounded-2xl bg-blue-50 flex items-center justify-center text-primary group-hover:scale-110 transition-transform shadow-sm">
                        <span class="material-symbols-outlined text-3xl">videocam</span>
                    </div>
                    <h4 class="text-text-main text-xl font-bold mb-3">Video Highlight</h4>
                    <p class="text-text-muted text-sm mb-6 flex-1 leading-relaxed">Tambahan video highlight sinematik berdurasi 2-3 menit untuk event.</p>
                    <div class="mb-6 pt-6 border-t border-gray-100">
                        <span class="text-primary font-bold text-lg">+ IDR 1.500k</span>
                        <span class="text-text-muted text-sm font-medium">/ event</span>
                    </div>
                    <button class="w-full h-12 rounded-xl bg-white border border-gray-200 text-text-main font-semibold transition-all hover:bg-gray-50 hover:border-gray-300 hover:shadow-sm">Tambah Video</button>
                </div>
            </div>

            <!-- CTA Banner -->
            <div class="mt-20 p-8 md:p-12 rounded-[2.5rem] bg-text-main text-white shadow-2xl flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-text-main to-gray-900 z-0"></div>
                <div class="absolute top-0 right-0 w-96 h-96 bg-primary/20 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2 group-hover:bg-primary/30 transition-colors duration-700"></div>
                <div class="relative z-10 text-center md:text-left">
                    <h4 class="text-white text-3xl md:text-4xl font-bold mb-4 font-display">Butuh Penawaran Khusus?</h4>
                    <p class="text-gray-400 text-base md:text-lg max-w-lg font-light">Untuk kebutuhan korporat skala besar atau request spesifik lainnya, hubungi kami untuk penawaran yang disesuaikan.</p>
                </div>
                <div class="flex gap-4 w-full md:w-auto relative z-10">
                    <button class="flex-1 md:flex-none h-14 px-10 rounded-full bg-white text-text-main text-base font-bold hover:bg-gray-50 transition-all shadow-xl hover:shadow-2xl hover:-translate-y-0.5 whitespace-nowrap">Minta Penawaran</button>
                </div>
            </div>
        </section>

        <!-- FAQ SECTION -->
        <section class="max-w-3xl mx-auto pt-10">
            <h3 class="text-text-main text-2xl font-display font-bold text-center mb-12">Pertanyaan yang Sering Diajukan</h3>
            <div class="space-y-4">
                <details class="group bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <summary class="flex justify-between items-center p-6 cursor-pointer list-none bg-white">
                        <span class="text-text-main font-semibold">Apakah ada biaya perjalanan?</span>
                        <span class="transition-transform duration-300 group-open:rotate-180 p-2 rounded-full bg-gray-50 text-text-muted group-hover:text-primary"><span class="material-symbols-outlined">expand_more</span></span>
                    </summary>
                    <div class="px-6 pb-6 text-text-muted text-sm leading-relaxed border-t border-gray-50 pt-4">Untuk acara di area Jakarta, transportasi sudah termasuk. Untuk luar kota, kami mengenakan biaya perjalanan standar yang mencakup transportasi dan akomodasi sesuai lokasi.</div>
                </details>
                <details class="group bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <summary class="flex justify-between items-center p-6 cursor-pointer list-none bg-white">
                        <span class="text-text-main font-semibold">Berapa lama proses editing?</span>
                        <span class="transition-transform duration-300 group-open:rotate-180 p-2 rounded-full bg-gray-50 text-text-muted group-hover:text-primary"><span class="material-symbols-outlined">expand_more</span></span>
                    </summary>
                    <div class="px-6 pb-6 text-text-muted text-sm leading-relaxed border-t border-gray-50 pt-4">Untuk foto, Anda akan menerima preview dalam 3 hari. Galeri full edit memakan waktu sekitar 2-3 minggu. Editing video biasanya memakan waktu 4-6 minggu tergantung kompleksitas proyek.</div>
                </details>
                <details class="group bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <summary class="flex justify-between items-center p-6 cursor-pointer list-none bg-white">
                        <span class="text-text-main font-semibold">Bagaimana sistem pembayarannya?</span>
                        <span class="transition-transform duration-300 group-open:rotate-180 p-2 rounded-full bg-gray-50 text-text-muted group-hover:text-primary"><span class="material-symbols-outlined">expand_more</span></span>
                    </summary>
                    <div class="px-6 pb-6 text-text-muted text-sm leading-relaxed border-t border-gray-50 pt-4">Kami memerlukan uang muka (DP) 30% untuk mengamankan tanggal Anda. Pelunasan dilakukan 3 hari sebelum acara atau pada hari H.</div>
                </details>
            </div>
        </section>

    </div>

    <!-- FOOTER -->
    <footer class="border-t border-gray-200 bg-white pt-20 pb-10 mt-auto" id="contact">
        <div class="max-w-7xl mx-auto px-6 md:px-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-2 space-y-6">
                    <div class="flex items-center gap-2 text-text-main mb-4">
                        <div class="h-8 w-auto flex items-center">
                            <img alt="GDPARTSTUDIO" class="h-full w-auto object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCLOKwkAfjZ8Z_kcGXiGPmRDltxUfadPwseHEPjzXO_eH6UGtYKBSpOs_BQUddl1gqf6RH1IFp206isB9uwQNUA_Sgbr0WG5AHcgILrAoexSlLGxcM8x71jt3cp0_Xi4GLEBQOFGkV0XXD7ifwv5azRGMVXYCZ37QvqFWPQC4WbxmzDYD9JH7IPTycx36cgHQMQIVcgA1E-kwAXRXETthAed4a2mbTgUgvx5oWPqjfro2lp2VfOTqG2k_rRZi08OVJY6_CPFz9U0ec"/>
                        </div>
                        <h2 class="sr-only">GDPARTSTUDIO</h2>
                    </div>
                    <p class="text-text-muted text-sm leading-relaxed max-w-sm">Menangkap momen terindah dalam hidup dengan keunggulan sinematik. Berbasis di Jakarta, tersedia untuk pernikahan destinasi di seluruh dunia.</p>
                </div>
                <div>
                    <h4 class="text-text-main font-bold mb-6">Layanan</h4>
                    <ul class="flex flex-col gap-3 text-sm text-text-muted">
                        <li><a class="hover:text-primary transition-colors" href="#weddings">Pernikahan</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#religious">Upacara Keagamaan</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#events">Dokumentasi Event</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Komersial</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-text-main font-bold mb-6">Kontak</h4>
                    <ul class="flex flex-col gap-4 text-sm text-text-muted">
                        <li class="flex items-center gap-3"><span class="material-symbols-outlined text-[18px] text-primary">mail</span>hello@gdpartstudio.com</li>
                        <li class="flex items-center gap-3"><span class="material-symbols-outlined text-[18px] text-primary">call</span>+62 812 3456 7890</li>
                        <li class="flex items-start gap-3"><span class="material-symbols-outlined text-[18px] text-primary">location_on</span>Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-text-muted text-xs">© <?= date('Y') ?> GDPARTSTUDIO. All rights reserved.</p>
                <div class="flex gap-6">
                    <a class="text-text-muted hover:text-primary transition-colors" href="#"><span class="sr-only">Instagram</span><svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path clip-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.468 2.527c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" fill-rule="evenodd"></path></svg></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- WHATSAPP FLOATING BUTTON -->
    <a class="fixed bottom-8 right-8 z-50 flex items-center justify-center w-14 h-14 bg-[#25D366] hover:bg-[#20b858] rounded-full shadow-lg shadow-green-900/10 transition-transform hover:scale-110" href="https://wa.me/6281234567890" rel="noopener noreferrer" target="_blank">
        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM16.64 16.64C16.14 17.14 15.35 17.38 14.53 17.11C12.33 16.39 10.33 15.11 8.78 13.56C7.23 12.01 5.95 10.01 5.23 7.81C4.96 6.99 5.2 6.2 5.7 5.7C6.01 5.39 6.47 5.28 6.87 5.42L8.29 5.9C8.75 6.06 9 6.55 8.87 7.02L8.43 8.65C8.32 9.05 8.46 9.47 8.78 9.79L10.97 11.98C11.29 12.3 11.71 12.44 12.11 12.33L13.74 11.89C14.21 11.76 14.7 12.01 14.86 12.47L15.34 13.89C15.48 14.29 15.37 14.75 15.06 15.06L16.64 16.64Z" fill-rule="evenodd"></path>
        </svg>
    </a>

</main>
</body>
</html>