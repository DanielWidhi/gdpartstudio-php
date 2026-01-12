<?php
include 'db.php'; // Koneksi database (untuk konsistensi)

// Logika Sederhana untuk Form Handling (Opsional)
$message_sent = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Di sini nanti Anda bisa menambahkan logika kirim email atau simpan ke database
    // Contoh: mail($to, $subject, $message, $headers);
    $message_sent = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Kontak - GDPARTSTUDIO</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              "primary": "#3b82f6", 
              "primary-dark": "#1e3a8a",
              "surface": "#ffffff", 
              "background": "#fbfcfe", 
            },
            fontFamily: {
              "display": ["Plus Jakarta Sans", "sans-serif"],
              "body": ["Noto Sans", "sans-serif"],
            },
            borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "2xl": "1rem", "3xl": "1.5rem", "full": "9999px"},
            boxShadow: {
              'soft': '0 10px 40px -10px rgba(0, 0, 0, 0.05)',
              'card': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
            }
          },
        },
      }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Noto Sans', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-background text-slate-800 overflow-x-hidden antialiased flex flex-col min-h-screen">

<!-- NAVBAR -->
<div class="w-full bg-surface/90 backdrop-blur-md sticky top-0 z-50 shadow-sm transition-all duration-300 border-b border-slate-100">
    <div class="max-w-[1280px] mx-auto px-6 md:px-12">
        <header class="flex items-center justify-between whitespace-nowrap py-5">
            <!-- Logo -->
            <div class="flex items-center gap-3 cursor-pointer" onclick="window.location.href='index.php'">
                <div class="size-9 text-primary">
                    <svg class="w-full h-full" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" d="M24 0.757355L47.2426 24L24 47.2426L0.757355 24L24 0.757355ZM21 35.7574V12.2426L9.24264 24L21 35.7574Z" fill="currentColor" fill-rule="evenodd"></path>
                    </svg>
                </div>
                <h2 class="text-slate-900 text-xl font-extrabold tracking-tight">GDPARTSTUDIO</h2>
            </div>

            <!-- Menu Desktop -->
            <div class="hidden md:flex flex-1 justify-end gap-10 items-center">
                <nav class="flex items-center gap-8">
                    <a class="text-slate-600 hover:text-primary text-sm font-semibold transition-colors" href="index.php">Beranda</a>
                    <a class="text-slate-600 hover:text-primary text-sm font-semibold transition-colors" href="portfolio.php">Portofolio</a>
                    <a class="text-slate-600 hover:text-primary text-sm font-semibold transition-colors" href="services.php">Layanan</a>
                    <!-- Active State for Contact -->
                    <a class="text-primary text-sm font-bold" href="contact.php">Kontak</a>
                </nav>
                <button class="rounded-full h-11 px-7 bg-slate-900 hover:bg-primary transition-colors text-white text-sm font-bold shadow-lg shadow-slate-900/10">
                    Pesan Sekarang
                </button>
            </div>

            <!-- Mobile Menu Button -->
            <button class="md:hidden text-slate-900 p-2">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </header>
    </div>
</div>

<main class="flex-grow">
    <div class="max-w-[1280px] mx-auto px-6 md:px-12 py-16 md:py-24">
        
        <?php if($message_sent): ?>
        <!-- Success Alert (Muncul jika form dikirim) -->
        <div class="mb-10 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span>Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.</span>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-20 items-start">
            
            <!-- LEFT COLUMN: INFO -->
            <div class="lg:col-span-5 flex flex-col gap-10">
                <div class="flex flex-col gap-6">
                    <span class="inline-flex items-center gap-2 py-1.5 px-3 w-fit rounded-md bg-blue-50 text-primary text-[10px] font-bold tracking-widest uppercase">
                        <span class="size-2 rounded-full bg-primary"></span>
                        Hubungi Kami
                    </span>
                    <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-[1.15]">
                        Ceritakan <br/>
                        <span class="text-primary">Momen Anda</span>
                    </h1>
                    <p class="text-slate-500 text-lg leading-relaxed font-light">
                        Kami siap mendengarkan cerita dan ide acara Anda. Jangan ragu untuk menghubungi kami untuk konsultasi gratis.
                    </p>
                </div>

                <!-- Contact Details -->
                <div class="flex flex-col gap-8 mt-4">
                    <!-- Address -->
                    <div class="flex gap-5 group items-start">
                        <div class="size-12 rounded-full bg-white border border-slate-100 flex items-center justify-center text-primary shrink-0 shadow-sm">
                            <span class="material-symbols-outlined text-[24px]">location_on</span>
                        </div>
                        <div class="pt-1">
                            <h3 class="text-slate-900 font-bold text-base mb-1">Studio Kami</h3>
                            <p class="text-slate-500 text-sm leading-relaxed">Jl. Fotografi No. 12, Kebayoran Baru<br/>Jakarta Selatan, Indonesia 12160</p>
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="flex gap-5 group items-start">
                        <div class="size-12 rounded-full bg-white border border-slate-100 flex items-center justify-center text-primary shrink-0 shadow-sm">
                            <span class="material-symbols-outlined text-[24px]">mail</span>
                        </div>
                        <div class="pt-1">
                            <h3 class="text-slate-900 font-bold text-base mb-1">Email</h3>
                            <a class="text-slate-500 text-sm hover:text-primary transition-colors" href="mailto:hello@gdpartstudio.com">hello@gdpartstudio.com</a>
                        </div>
                    </div>
                    
                    <!-- Phone -->
                    <div class="flex gap-5 group items-start">
                        <div class="size-12 rounded-full bg-white border border-slate-100 flex items-center justify-center text-primary shrink-0 shadow-sm">
                            <span class="material-symbols-outlined text-[24px]">call</span>
                        </div>
                        <div class="pt-1">
                            <h3 class="text-slate-900 font-bold text-base mb-1">Telepon</h3>
                            <a class="text-slate-500 text-sm hover:text-primary transition-colors" href="https://wa.me/628123456789">+62 812-3456-7890</a>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="pt-6 border-t border-slate-100">
                    <h4 class="text-xs font-bold text-slate-400 mb-4 uppercase tracking-wider">Media Sosial</h4>
                    <div class="flex gap-3">
                        <a class="size-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 hover:border-primary hover:text-primary transition-all duration-300 bg-white" href="#">
                            <!-- Instagram Icon -->
                            <svg fill="none" height="18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="18"><rect height="20" rx="5" ry="5" width="20" x="2" y="2"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line></svg>
                        </a>
                        <a class="size-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 hover:border-primary hover:text-primary transition-all duration-300 bg-white" href="#">
                            <!-- Facebook Icon -->
                            <svg fill="none" height="18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="18"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                        </a>
                        <a class="size-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 hover:border-primary hover:text-primary transition-all duration-300 bg-white" href="#">
                            <!-- Video/Youtube Icon -->
                            <svg fill="none" height="18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="18"><path d="M23 7l-7 5 7 5V7z"></path><rect height="14" rx="2" ry="2" width="15" x="1" y="5"></rect></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: FORM -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-[2rem] p-8 md:p-10 shadow-soft border border-slate-100 relative overflow-hidden">
                    <div class="relative z-10">
                        <h2 class="text-2xl font-bold text-slate-900 mb-2">Kirim Pesan</h2>
                        <p class="text-slate-500 mb-8 font-light text-sm">Lengkapi formulir di bawah ini, kami akan merespons secepatnya.</p>
                        
                        <form class="flex flex-col gap-5" action="" method="POST">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <label class="flex flex-col gap-2">
                                    <span class="text-slate-700 text-sm font-semibold ml-1">Nama Lengkap</span>
                                    <input required class="w-full rounded-lg border-slate-200 bg-slate-50/50 px-4 h-12 text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm" placeholder="Nama Anda" name="name" type="text"/>
                                </label>
                                <label class="flex flex-col gap-2">
                                    <span class="text-slate-700 text-sm font-semibold ml-1">Email</span>
                                    <input required class="w-full rounded-lg border-slate-200 bg-slate-50/50 px-4 h-12 text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm" placeholder="Alamat Email" name="email" type="email"/>
                                </label>
                            </div>
                            
                            <label class="flex flex-col gap-2">
                                <span class="text-slate-700 text-sm font-semibold ml-1">Subjek</span>
                                <input required class="w-full rounded-lg border-slate-200 bg-slate-50/50 px-4 h-12 text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm" placeholder="Misal: Tanya Jasa Wedding" name="subject" type="text"/>
                            </label>
                            
                            <label class="flex flex-col gap-2">
                                <span class="text-slate-700 text-sm font-semibold ml-1">Pesan</span>
                                <textarea required class="w-full rounded-lg border-slate-200 bg-slate-50/50 p-4 h-40 text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary resize-none transition-all text-sm" placeholder="Tuliskan pesan Anda di sini..." name="message"></textarea>
                            </label>
                            
                            <button class="mt-4 w-full h-14 bg-[#0f172a] hover:bg-primary text-white font-bold rounded-lg transition-all duration-300 flex items-center justify-center gap-2 group shadow-lg shadow-slate-900/10 hover:shadow-blue-500/20" type="submit">
                                <span>Kirim Pesan</span>
                                <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- MAP SECTION -->
    <div class="w-full px-6 md:px-12 pb-16 md:pb-24">
        <div class="max-w-[1280px] mx-auto">
            <div class="w-full h-[350px] md:h-[400px] rounded-[2.5rem] overflow-hidden relative group shadow-sm border border-slate-200">
                <!-- Map Background Image -->
                <div class="w-full h-full bg-cover bg-center grayscale group-hover:grayscale-0 transition-all duration-700 opacity-80 group-hover:opacity-100" 
                     style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuC-tCMUFl9rZe7pUMOGYadT9lM-HaZWdaluLSB-YtLorRaKy0p8C13oxEklUlsytlJHovDoZdMs1X62qzfxNSFISs_zEJkfhQp_3SLZwGWB-fD2nUXP3UH3lPf07U5xH4M_6e9Ih4XdijI5rvowYAQSxeGRuP3rGOcBT8ngk6fDwsgCZv9C_v0UkBMBtnbjowbtYYMTroXPIydJTOLLwfHlPn3fgtnK56S8P1tY50dCy0F2K8WSyTqD0hrZ-gETZshK0rJcLYVF5ZY');">
                </div>
                
                <!-- Floating Info Card -->
                <div class="absolute bottom-6 left-6 md:bottom-8 md:left-8 bg-white/95 backdrop-blur-md p-5 pr-8 rounded-2xl shadow-lg shadow-slate-900/5 flex items-center gap-5 border border-white/50">
                    <div class="size-12 bg-[#0f172a] text-white rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-[24px]">map</span>
                    </div>
                    <div>
                        <h5 class="font-bold text-slate-900 text-base">Lokasi Studio</h5>
                        <p class="text-xs text-slate-500 mb-1">Jakarta Selatan, Indonesia</p>
                        <a class="text-xs font-bold text-primary hover:text-blue-700 flex items-center gap-1 group/link transition-colors" href="https://maps.google.com" target="_blank">
                            Buka di Google Maps
                            <span class="material-symbols-outlined text-[14px] group-hover/link:translate-x-0.5 transition-transform">open_in_new</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- FOOTER -->
<footer class="bg-white border-t border-slate-100 py-12">
    <div class="max-w-[1280px] mx-auto px-6 md:px-12">
        <div class="flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex items-center gap-3">
                <div class="size-6 text-slate-900">
                    <svg class="w-full h-full" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" d="M24 0.757355L47.2426 24L24 47.2426L0.757355 24L24 0.757355ZM21 35.7574V12.2426L9.24264 24L21 35.7574Z" fill="currentColor" fill-rule="evenodd"></path>
                    </svg>
                </div>
                <span class="text-slate-900 font-bold text-lg">GDPARTSTUDIO</span>
            </div>
            
            <div class="flex gap-8 text-slate-500 text-sm font-medium">
                <a class="hover:text-primary transition-colors" href="#">Kebijakan Privasi</a>
                <a class="hover:text-primary transition-colors" href="#">Syarat &amp; Ketentuan</a>
                <a class="hover:text-primary transition-colors" href="#">Instagram</a>
            </div>
            
            <p class="text-slate-400 text-sm">
                © <?= date('Y') ?> GDPARTSTUDIO. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
</footer>

</body>
</html>