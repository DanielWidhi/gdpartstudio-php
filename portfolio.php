<?php
include 'db.php'; 

// 1. Ambil Data Projects (Hanya yang Published)
$projects_query = mysqli_query($conn, "SELECT * FROM projects WHERE status = 'Published' ORDER BY id DESC"); // Saya ubah ke DESC agar project terbaru muncul duluan

$projects = [];
while ($row = mysqli_fetch_assoc($projects_query)) {
    $projects[] = $row;
}

// 2. Ambil Data Videos
$videos_query = mysqli_query($conn, "SELECT * FROM videos ORDER BY id ASC");
?>

<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Portfolio - GDPARTSTUDIO</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#1e293b", 
                        "secondary": "#64748b",
                        "accent": "#0f172a", 
                        "background-light": "#ffffff",
                        "surface-light": "#f8fafc",
                        "surface-hover": "#f1f5f9"
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"],
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
        /* Helper class untuk filter javascript */
        .hidden-item { display: none !important; }
        .active-filter { background-color: #ffffff !important; color: #0f172a !important; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); }
    </style>
</head>
<body class="bg-background-light text-primary font-body antialiased selection:bg-slate-200 selection:text-slate-900 overflow-x-hidden">
<div class="flex min-h-screen flex-col">
    
    <!-- HEADER -->
    <header class="fixed top-0 z-40 w-full border-b border-gray-100 bg-white/90 backdrop-blur-md transition-all duration-300">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
            <!-- Logo -->
            <div class="flex items-center gap-3 cursor-pointer" onclick="window.location.href='index'">
                <div class="flex size-10 items-center justify-center rounded-lg bg-slate-900 text-white shadow-md ring-1 ring-slate-900/5">
                    <span class="material-symbols-outlined text-[24px]">shutter_speed</span>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="font-display text-lg font-bold tracking-tight text-slate-900 uppercase">GDPARTSTUDIO</span>
                    <span class="text-[10px] tracking-widest text-slate-500 uppercase font-medium">Visual Storytellers</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="hidden md:flex flex-1 justify-center gap-10">
                <a class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors" href="index">Home</a>
                <a class="text-sm font-medium text-slate-900 border-b-2 border-slate-900 pb-0.5" href="portfolio">Portfolio</a>
                <a class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors" href="services">Services</a>
                <a class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors" href="contact">Contact</a>
            </nav>

            <!-- CTA Button -->
            <div class="flex items-center gap-4">
                <button class="hidden sm:flex items-center justify-center rounded-full bg-slate-900 px-6 py-2.5 text-sm font-semibold text-white transition-all hover:bg-slate-800 shadow-sm hover:shadow-md">Get Quote</button>
                <button class="md:hidden text-slate-900 p-2 hover:bg-slate-100 rounded-full transition-colors">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        <!-- HERO SECTION -->
        <section class="relative pt-32 pb-16 sm:pt-44 sm:pb-24 bg-white overflow-hidden">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-slate-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-slate-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
            <div class="mx-auto max-w-7xl px-6 lg:px-8 relative">
                <div class="flex flex-col items-center text-center">
                    <div class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-600 shadow-sm mb-8">
                        <span class="flex h-2 w-2 rounded-full bg-emerald-500 mr-2"></span>
                        Available for 2024 Bookings
                    </div>
                    <h1 class="font-display text-5xl font-bold tracking-tight text-slate-900 sm:text-7xl md:text-8xl max-w-5xl leading-[1.1]">
                        Timeless Moments, <br/> <span class="text-transparent bg-clip-text bg-gradient-to-r from-slate-400 to-slate-600 font-light italic">Artfully Curated</span>
                    </h1>
                    <p class="mt-8 max-w-2xl text-lg text-slate-600 font-light leading-relaxed">
                        GDPARTSTUDIO specializes in minimalist documentation for weddings, religious ceremonies, and exclusive events. We craft visual legacies with elegance and clarity.
                    </p>
                </div>
            </div>
        </section>

        <!-- FILTER BUTTONS -->
        <section class="sticky top-[72px] z-30 bg-white/80 backdrop-blur-xl border-b border-slate-100 transition-all duration-300" id="gallery">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="flex items-center justify-center overflow-x-auto no-scrollbar py-5">
                    <div class="flex items-center p-1 bg-slate-100/50 rounded-full border border-slate-200/50">
                        <button onclick="filterProjects('all')" class="filter-btn active-filter px-6 py-2 rounded-full bg-transparent text-slate-600 hover:text-slate-900 text-sm font-medium transition-all">All Works</button>
                        <button onclick="filterProjects('weddings')" class="filter-btn px-6 py-2 rounded-full bg-transparent text-slate-600 hover:text-slate-900 text-sm font-medium transition-all">Weddings</button>
                        <button onclick="filterProjects('religious')" class="filter-btn px-6 py-2 rounded-full bg-transparent text-slate-600 hover:text-slate-900 text-sm font-medium transition-all whitespace-nowrap">Religious</button>
                        <button onclick="filterProjects('events')" class="filter-btn px-6 py-2 rounded-full bg-transparent text-slate-600 hover:text-slate-900 text-sm font-medium transition-all">Events</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- PORTFOLIO GRID -->
        <section class="py-16 sm:py-24 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12" id="projects-container">
                    
                    <?php foreach ($projects as $project): ?>
                    <!-- Item Project -->
                    <!-- PERBAIKAN: Menggunakan href project/slug -->
                    <a class="group block cursor-pointer gallery-item project-card" 
                       href="project/<?= $project['slug'] ?>"
                       data-category="<?= $project['filter_tag'] ?>">
                        
                        <div class="relative overflow-hidden rounded-2xl bg-slate-100 aspect-[4/5] isolate shadow-sm ring-1 ring-slate-900/5">
                            <!-- Gambar Project -->
                            <img alt="<?= $project['title'] ?>" class="h-full w-full object-cover transition-transform duration-700 ease-out" src="<?= $project['image_url'] ?>"/>
                            
                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"></div>
                            <!-- Hover Icon Arrow -->
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-white text-slate-900 shadow-lg transform scale-90 transition-transform duration-300 group-hover:scale-100">
                                    <span class="material-symbols-outlined">arrow_outward</span>
                                </span>
                            </div>
                        </div>

                        <!-- Text Info -->
                        <div class="mt-5 flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 group-hover:text-slate-700 transition-colors font-display"><?= $project['title'] ?></h3>
                                <p class="text-sm text-slate-500 mt-1"><?= $project['category_display'] ?></p>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>

                </div>

                <!-- Load More Button -->
                <div class="mt-20 flex justify-center">
                    <button class="group flex items-center gap-3 px-8 py-3 rounded-full bg-slate-50 border border-slate-200 text-sm font-semibold text-slate-800 transition-all hover:bg-white hover:border-slate-300 hover:shadow-md">
                        Load More Projects
                        <span class="material-symbols-outlined text-[18px] transition-transform group-hover:translate-y-0.5">expand_more</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- VIDEOGRAPHY SECTION -->
        <section class="bg-surface-light py-24 border-t border-slate-100">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mb-14 flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div>
                        <span class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-2 block">Videography</span>
                        <h2 class="font-display text-4xl font-bold tracking-tight text-slate-900">Cinematic Stories</h2>
                        <p class="mt-4 text-slate-500 max-w-md text-lg font-light">Relive the emotions through our carefully crafted cinematic films.</p>
                    </div>
                    <a class="group flex items-center gap-2 text-sm font-semibold text-slate-900 bg-white px-5 py-2.5 rounded-full shadow-sm hover:shadow-md transition-all border border-slate-200" href="#">
                        View All Videos
                        <span class="material-symbols-outlined text-[18px] transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <?php while($video = mysqli_fetch_assoc($videos_query)): ?>
                    <div class="group cursor-pointer">
                        <div class="relative aspect-video overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/50">
                            <div class="absolute inset-0 bg-cover bg-center opacity-95 transition-transform duration-700 group-hover:scale-105" style="background-image: url('<?= $video['thumbnail_url'] ?>');"></div>
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-colors"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="flex size-14 items-center justify-center rounded-full bg-white/90 backdrop-blur-sm text-slate-900 shadow-xl transition-all duration-300 group-hover:scale-110 group-hover:bg-white">
                                    <span class="material-symbols-outlined text-3xl fill-current ml-1">play_arrow</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5">
                            <h3 class="text-lg font-bold text-slate-900"><?= $video['title'] ?></h3>
                            <p class="text-sm text-slate-500 mt-1"><?= $video['category'] ?></p>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-100 py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start gap-12">
                <div class="max-w-xs">
                    <div class="flex items-center gap-2 text-slate-900 mb-6">
                        <div class="flex size-8 items-center justify-center rounded-lg bg-slate-900 text-white">
                            <span class="material-symbols-outlined text-[18px]">shutter_speed</span>
                        </div>
                        <h2 class="text-lg font-bold tracking-tight font-display">GDPARTSTUDIO</h2>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed">Capturing moments with a modern, minimalist approach. Based in Jakarta, available worldwide.</p>
                </div>
                <div class="grid grid-cols-2 gap-16">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 mb-4 uppercase tracking-wider">Portfolio</h3>
                        <ul class="space-y-3">
                            <li><a class="text-sm text-slate-500 hover:text-slate-900 transition-colors" href="#">Weddings</a></li>
                            <li><a class="text-sm text-slate-500 hover:text-slate-900 transition-colors" href="#">Religious</a></li>
                            <li><a class="text-sm text-slate-500 hover:text-slate-900 transition-colors" href="#">Events</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 mb-4 uppercase tracking-wider">Social</h3>
                        <ul class="space-y-3">
                            <li><a class="text-sm text-slate-500 hover:text-slate-900 transition-colors" href="#">Instagram</a></li>
                            <li><a class="text-sm text-slate-500 hover:text-slate-900 transition-colors" href="#">Facebook</a></li>
                            <li><a class="text-sm text-slate-500 hover:text-slate-900 transition-colors" href="#">YouTube</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mt-16 pt-8 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-xs text-slate-400">© <?= date('Y') ?> GDPARTSTUDIO. All rights reserved.</p>
                <div class="flex gap-4">
                    <a class="text-xs text-slate-400 hover:text-slate-600" href="#">Privacy Policy</a>
                    <a class="text-xs text-slate-400 hover:text-slate-600" href="#">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

</div>

<!-- Filter Script -->
<script>
    function filterProjects(category) {
        const items = document.querySelectorAll('.project-card');
        const buttons = document.querySelectorAll('.filter-btn');

        // Update style tombol aktif
        buttons.forEach(btn => {
            btn.classList.remove('active-filter', 'bg-white', 'shadow-sm', 'ring-1', 'ring-black/5');
            btn.classList.add('bg-transparent', 'hover:bg-white/50');
            
            if(btn.textContent.toLowerCase().includes(category) || (category === 'all' && btn.textContent.includes('All'))) {
                btn.classList.add('active-filter', 'bg-white', 'shadow-sm', 'ring-1', 'ring-black/5');
                btn.classList.remove('bg-transparent', 'hover:bg-white/50');
            }
        });

        // Tampilkan/Sembunyikan Item
        items.forEach(item => {
            const itemCategory = item.getAttribute('data-category');
            if (category === 'all' || itemCategory === category) {
                item.classList.remove('hidden-item');
            } else {
                item.classList.add('hidden-item');
            }
        });
    }
</script>

</body>
</html>