<!-- assets/components/navbar/navbar.php -->
<?php
if (!isset($currentPage)) { $currentPage = ''; }
?>

<header class="fixed top-0 z-40 w-full border-b border-gray-100 bg-white/90 backdrop-blur-md transition-all duration-300">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
        
        <!-- LOGO AREA -->
        <div class="flex items-center gap-3 cursor-pointer" onclick="window.location.href='/index'">
            <div class="relative w-10 h-10 flex items-center justify-center">
                <!-- PERBAIKAN: Tambahkan '/' di depan assets -->
                <img alt="GDPARTSTUDIO Logo" 
                     class="object-contain w-full h-full" 
                     src="/assets/images/Logo2b.png"/> 
            </div>
            <h1 class="hidden md:block text-slate-900 text-sm md:text-base font-display font-medium tracking-[0.25em] uppercase ml-2">
                GDPARTSTUDIO
            </h1>
        </div>

        <!-- NAVIGATION LINKS -->
        <!-- PERBAIKAN: Tambahkan '/' di depan semua href -->
        <nav class="hidden md:flex flex-1 justify-center gap-10">
            <a class="text-sm font-medium transition-colors duration-300 
               <?= $currentPage == 'home' ? 'text-primary font-bold' : 'text-slate-500 hover:text-slate-900' ?>" 
               href="/index">Home</a> <!-- href="/index" -->
            
            <a class="text-sm font-medium transition-colors duration-300 
               <?= $currentPage == 'portfolio' ? 'text-primary font-bold' : 'text-slate-500 hover:text-slate-900' ?>" 
               href="/portfolio">Portfolio</a> <!-- href="/portfolio" -->
            
            <a class="text-sm font-medium transition-colors duration-300 
               <?= $currentPage == 'services' ? 'text-primary font-bold' : 'text-slate-500 hover:text-slate-900' ?>" 
               href="/services">Services</a> <!-- href="/services" -->
            
            <a class="text-sm font-medium transition-colors duration-300 
               <?= $currentPage == 'contact' ? 'text-primary font-bold' : 'text-slate-500 hover:text-slate-900' ?>" 
               href="/contact">Contact</a> <!-- href="/contact" -->
        </nav>

        <!-- CTA BUTTON & MOBILE MENU -->
        <div class="flex items-center gap-4">
            <button class="hidden sm:flex items-center justify-center rounded-full bg-slate-900 px-6 py-2.5 text-sm font-semibold text-white transition-all hover:bg-slate-800 shadow-sm hover:shadow-md">
                Get Quote
            </button>
            <button class="md:hidden text-slate-900 p-2 hover:bg-slate-100 rounded-full transition-colors">
                <span class="material-symbols-outlined font-light text-2xl">menu</span>
            </button>
        </div>

    </div>
</header>