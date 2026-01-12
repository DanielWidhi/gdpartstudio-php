<?php
include 'db.php'; // Koneksi database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Halaman Beranda GDPARTSTUDIO</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600&amp;family=Noto+Sans:wght@300;400&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "brand-dark": "#121212",
                        "brand-gray": "#9e9e9e",
                        "stone-900": "#1c1917",
                        "stone-500": "#78716c",
                        "stone-50": "#fafaf9",
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"],
                        "body": ["Noto Sans", "sans-serif"],
                    },
                    letterSpacing: {
                        "widest-plus": "0.2em",
                    }
                },
            },
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #aaa; }
        
        /* Transisi halus untuk logo dan warna */
        .navbar-transition { transition: all 0.3s ease-in-out; }
    </style>
</head>
<body class="bg-white text-brand-dark font-body overflow-x-hidden antialiased selection:bg-stone-200 selection:text-stone-900">
    <div class="relative flex min-h-screen w-full flex-col">
        
        <!-- HEADER / NAVBAR -->
        <!-- ID 'main-navbar' digunakan oleh Javascript -->
        <header id="main-navbar" class="fixed top-0 left-0 right-0 z-50 w-full py-6 transition-all duration-300 bg-gradient-to-b from-black/50 to-transparent">
            <div class="w-full max-w-[1800px] mx-auto px-6 md:px-12 flex items-center justify-between">
                
                <!-- LOGO AREA -->
                <div class="flex items-center gap-2 cursor-pointer z-50" onclick="window.location.href='index.php'">
                    <div class="relative w-12 h-12 md:w-16 md:h-16 flex items-center justify-center">
                        <!-- ID 'navbar-logo' untuk JS mengganti src gambar -->
                        <!-- Default: Logo1.png (untuk background gelap) -->
                        <img id="navbar-logo" 
                             alt="GDPARTSTUDIO Logo" 
                             class="object-contain w-full h-full drop-shadow-sm transition-all duration-300" 
                             src="assets/images/Logo1.png"/>
                    </div>
                    <!-- ID 'navbar-title' untuk JS mengganti warna teks -->
                    <h1 id="navbar-title" class="hidden md:block text-white drop-shadow-md text-sm md:text-base font-display font-medium tracking-[0.25em] uppercase ml-2 transition-colors duration-300">
                        GDPARTSTUDIO
                    </h1>
                </div>

                <!-- NAVIGATION LINKS -->
                <div class="hidden md:flex items-center">
                    <nav class="flex items-center gap-12" id="navbar-links">
                        <!-- Class 'nav-link' digunakan untuk selector JS -->
                        <a class="nav-link text-white/90 hover:text-white transition-all text-[10px] font-medium tracking-[0.25em] uppercase hover:tracking-[0.3em] duration-300" href="portfolio.php">Portfolio</a>
                        <a class="nav-link text-white/90 hover:text-white transition-all text-[10px] font-medium tracking-[0.25em] uppercase hover:tracking-[0.3em] duration-300" href="services.php">Services</a>
                        <a class="nav-link text-white/90 hover:text-white transition-all text-[10px] font-medium tracking-[0.25em] uppercase hover:tracking-[0.3em] duration-300" href="#">About</a>
                        <a class="nav-link text-white/90 hover:text-white transition-all text-[10px] font-medium tracking-[0.25em] uppercase hover:tracking-[0.3em] duration-300" href="contact.php">Contact</a>
                    </nav>
                </div>

                <!-- MOBILE MENU BUTTON -->
                <button id="mobile-menu-btn" class="md:hidden text-white drop-shadow-md p-2 hover:bg-white/10 rounded-full transition-colors">
                    <span class="material-symbols-outlined font-light text-2xl">menu</span>
                </button>
            </div>
        </header>

        <!-- HERO SECTION -->
        <section class="relative h-screen w-full overflow-hidden bg-stone-200">
            <div class="absolute inset-0 w-full h-full">
                <!-- Overlay gelap agar tulisan putih terbaca -->
                <div class="absolute inset-0 bg-black/20 z-10 pointer-events-none"></div>
                <!-- Background Image -->
                <div class="w-full h-full bg-cover bg-center animate-[pulse_15s_ease-in-out_infinite]" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBP66-4wr2ROM6I1aRWj8fe4HA0A0VY7BJkKA4FWqQbqeOuNX7rNWZGf-ZzisRVIFFr_J-meud2Z4Pj40sgCjUk0YyqhRXVckeo593EwnrFwABdBkcPBUZ7w9_9n3oIuyjmabFHSj3rDAz3a39MKF-gSvOYWhNxRshIMR0cEV5ZaiHr2iBwlEUR1chldYOFGrBZdXaD7F1yrRWaYEbVjWeXlbn3-LNpteF1U9DLC686osT7a_NL1WdFedgcDGyWnwCiIfrS2ESBSXc"); animation-direction: alternate; animation-duration: 20s;'></div>
            </div>
            
            <!-- Hero Arrows (Static UI) -->
            <button class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 z-20 group cursor-pointer hidden md:flex items-center justify-center p-4">
                <span class="material-symbols-outlined text-white/70 group-hover:text-white text-4xl font-thin transition-all duration-300 group-hover:-translate-x-1">west</span>
            </button>
            <button class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 z-20 group cursor-pointer hidden md:flex items-center justify-center p-4">
                <span class="material-symbols-outlined text-white/70 group-hover:text-white text-4xl font-thin transition-all duration-300 group-hover:translate-x-1">east</span>
            </button>
        </section>

        <!-- MAIN CONTENT (Portfolio Grid) -->
        <section class="bg-white relative z-20">
            <div class="w-full max-w-[1400px] mx-auto px-6 md:px-12 py-24 md:py-36 flex flex-col">
                
                <div class="flex flex-col md:flex-row justify-between items-end mb-20 md:mb-32 gap-8">
                    <div class="max-w-3xl">
                        <span class="block text-stone-400 text-[10px] tracking-[0.3em] uppercase mb-8 font-medium pl-1 border-l border-stone-300 h-3 flex items-center">Selected Works</span>
                        <h2 class="text-stone-900 font-display text-4xl md:text-6xl font-extralight leading-[1.15] tracking-tight">
                            Visualizing emotions through <br/> <span class="font-normal text-stone-600 italic font-serif">modern storytelling</span>.
                        </h2>
                    </div>
                    <div class="hidden md:block pb-2">
                        <a class="group flex items-center gap-3 text-stone-400 hover:text-stone-900 transition-colors py-2" href="portfolio.php">
                            <span class="text-[10px] uppercase tracking-[0.25em] font-semibold">View Portfolio</span>
                            <span class="material-symbols-outlined text-lg font-light group-hover:translate-x-2 transition-transform duration-300">arrow_right_alt</span>
                        </a>
                    </div>
                </div>

                <!-- PORTFOLIO GRID -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-y-20 gap-x-10 w-full">
                    <?php 
                    $query = mysqli_query($conn, "SELECT * FROM projects ORDER BY id ASC LIMIT 3");
                    $index = 0; 
                    while($row = mysqli_fetch_assoc($query)) {
                        $offsetClass = ($index % 3 == 1) ? 'md:mt-24' : '';
                        $displayCategory = $row['category_display'];
                    ?>
                        <a class="group block cursor-pointer <?= $offsetClass ?>" href="portfolio-detail.php?id=<?= $row['id'] ?>">
                            <div class="aspect-[4/5] overflow-hidden bg-stone-50 relative mb-8">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors z-10 duration-700"></div>
                                <div class="w-full h-full bg-cover bg-center transition-transform duration-[1.5s] ease-[cubic-bezier(0.25,1,0.5,1)] group-hover:scale-105" 
                                     style='background-image: url("<?= $row['image_url'] ?>");'>
                                </div>
                            </div>
                            <div class="flex flex-col items-center text-center">
                                <h3 class="text-stone-900 font-display text-xl md:text-2xl font-light"><?= $row['title'] ?></h3>
                                <p class="text-stone-400 text-[10px] mt-3 font-medium tracking-[0.2em] uppercase"><?= $displayCategory ?></p>
                            </div>
                        </a>
                    <?php $index++; } ?>
                </div>

            </div>
        </section>

        <!-- FOOTER -->
        <footer class="bg-stone-50 py-20 border-t border-stone-100">
            <div class="max-w-[1600px] mx-auto px-6 md:px-12 flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="text-center md:text-left">
                    <h4 class="text-stone-900 font-display font-bold tracking-[0.2em] text-xs uppercase mb-3">GDPARTSTUDIO</h4>
                    <p class="text-stone-400 text-[10px] tracking-wide">Copyright © <?= date('Y') ?>. All rights reserved.</p>
                </div>
                <div class="flex gap-10 md:gap-16">
                    <a class="text-stone-400 hover:text-stone-900 transition-colors text-[10px] uppercase tracking-[0.2em] font-medium" href="#">Instagram</a>
                    <a class="text-stone-400 hover:text-stone-900 transition-colors text-[10px] uppercase tracking-[0.2em] font-medium" href="#">Facebook</a>
                </div>
            </div>
        </footer>
    </div>

    <!-- JAVASCRIPT FOR NAVBAR SCROLL EFFECT -->
    <script>
        // Mengambil elemen-elemen yang akan dimanipulasi
        const navbar = document.getElementById('main-navbar');
        const logo = document.getElementById('navbar-logo');
        const title = document.getElementById('navbar-title');
        const navLinks = document.querySelectorAll('.nav-link');
        const mobileBtn = document.getElementById('mobile-menu-btn');

        // Path Gambar (Sesuaikan jika folder anda berbeda)
        const logoLight = 'assets/images/Logo1.png';  // Logo Putih (untuk background gelap)
        const logoDark = 'assets/images/Logo2b.png';  // Logo Berwarna/Hitam (untuk background terang)

        // Event Listener saat di-scroll
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                // --- STATE SCROLLED (Background Putih) ---
                
                // Ubah background navbar jadi putih & tambah shadow
                navbar.classList.add('bg-white/95', 'backdrop-blur-md', 'shadow-sm', 'py-4');
                navbar.classList.remove('bg-gradient-to-b', 'from-black/50', 'to-transparent', 'py-6');

                // Ganti Logo ke versi Gelap/Berwarna
                logo.src = logoDark;

                // Ganti Warna Teks Judul jadi Hitam
                title.classList.remove('text-white');
                title.classList.add('text-stone-900');

                // Ganti Warna Tombol Mobile
                mobileBtn.classList.remove('text-white');
                mobileBtn.classList.add('text-stone-900');

                // Ganti Warna Link Navigasi jadi Abu-abu gelap
                navLinks.forEach(link => {
                    link.classList.remove('text-white/90', 'hover:text-white');
                    link.classList.add('text-stone-500', 'hover:text-stone-900');
                });

            } else {
                // --- STATE TOP (Background Transparan/Gelap) ---
                
                // Kembalikan background transparan & gradient
                navbar.classList.remove('bg-white/95', 'backdrop-blur-md', 'shadow-sm', 'py-4');
                navbar.classList.add('bg-gradient-to-b', 'from-black/50', 'to-transparent', 'py-6');

                // Ganti Logo ke versi Putih
                logo.src = logoLight;

                // Kembalikan Warna Teks Judul jadi Putih
                title.classList.add('text-white');
                title.classList.remove('text-stone-900');

                // Kembalikan Warna Tombol Mobile
                mobileBtn.classList.add('text-white');
                mobileBtn.classList.remove('text-stone-900');

                // Kembalikan Warna Link Navigasi jadi Putih
                navLinks.forEach(link => {
                    link.classList.add('text-white/90', 'hover:text-white');
                    link.classList.remove('text-stone-500', 'hover:text-stone-900');
                });
            }
        });
    </script>

</body>
</html>