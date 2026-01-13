<?php
include 'db.php';

$project = null;

// 1. Cek Parameter URL (Slug atau ID)
if (isset($_GET['slug'])) {
    $slug = mysqli_real_escape_string($conn, $_GET['slug']);
    // Filter: Hanya ambil jika status = 'Published'
    $query = mysqli_query($conn, "SELECT * FROM projects WHERE slug = '$slug' AND status = 'Published'");
    $project = mysqli_fetch_assoc($query);

} elseif (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Filter: Hanya ambil jika status = 'Published'
    $query = mysqli_query($conn, "SELECT * FROM projects WHERE id = $id AND status = 'Published'");
    $project = mysqli_fetch_assoc($query);
}

// 2. LOGIKA 404 (PENTING)
// Jika $project kosong (artinya ID salah ATAU statusnya Draft/Archived)
if (!$project) {
    // Set Header HTTP 404 agar mesin pencari tahu halaman ini tidak ada
    http_response_code(404);
    
    // Panggil tampilan 404.php yang sudah kita buat
    include '404.php'; 
    
    // Hentikan script agar konten detail di bawah tidak dimuat
    exit;
}

// --- JIKA LOLOS (Data Ada & Published), LANJUT KE BAWAH ---

$id = $project['id']; // Ambil ID untuk query gallery

// 3. Query Gallery
$gallery_query = mysqli_query($conn, "SELECT * FROM project_gallery WHERE project_id = $id LIMIT 3");
$gallery_images = [];
while($img = mysqli_fetch_assoc($gallery_query)){
    $gallery_images[] = $img['image_url'];
}

// 4. Query Navigasi (Hanya Prev/Next yang Published)
$prev_query = mysqli_query($conn, "SELECT id, title, slug, category_display FROM projects WHERE id < $id AND status = 'Published' ORDER BY id DESC LIMIT 1");
$prev_project = mysqli_fetch_assoc($prev_query);

$next_query = mysqli_query($conn, "SELECT id, title, slug, category_display FROM projects WHERE id > $id AND status = 'Published' ORDER BY id ASC LIMIT 1");
$next_project = mysqli_fetch_assoc($next_query);
?>

<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $project['title'] ?> - GDPARTSTUDIO</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
</head>
<body class="bg-background-light text-primary font-body antialiased selection:bg-slate-200 selection:text-slate-900 overflow-x-hidden">
<div class="flex min-h-screen flex-col">
    
    <!-- HEADER -->
     <?php 
        $currentPage = 'portfolio'; // Tetap aktifkan menu portfolio saat detail dibuka
        include 'assets/components/navbar/navbar.php'; 
    ?>

    <main class="flex-grow">
        <!-- TITLE & BREADCRUMB SECTION -->
        <section class="pt-32 pb-10 sm:pt-40 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-sm text-slate-500 mb-8">
                    <a class="hover:text-slate-900 transition-colors" href="portfolio.php">Portfolio</a>
                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                    <span class="text-slate-900 font-medium capitalize"><?= $project['filter_tag'] ?></span>
                </div>
                
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-8">
                    <div class="max-w-2xl">
                        <span class="inline-block py-1 px-3 rounded-full bg-slate-100 text-xs font-semibold text-slate-600 uppercase tracking-wider mb-4">
                            <?= $project['category_display'] ?>
                        </span>
                        <h1 class="font-display text-4xl sm:text-5xl md:text-6xl font-bold text-slate-900 tracking-tight leading-tight">
                            <?= $project['title'] ?>: <br/>
                            <span class="text-slate-500 font-light italic"><?= $project['client_name'] ?></span>
                        </h1>
                    </div>
                    <div class="flex gap-12 lg:pb-2">
                        <div>
                            <span class="block text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1">Date</span>
                            <span class="text-sm font-medium text-slate-900"><?= $project['event_date'] ?></span>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1">Location</span>
                            <span class="text-sm font-medium text-slate-900"><?= $project['location'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- HERO IMAGE -->
        <section class="bg-white pb-16">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="relative overflow-hidden rounded-3xl aspect-[16/9] sm:aspect-[21/9] shadow-lg">
                    <img alt="<?= $project['title'] ?>" class="absolute inset-0 h-full w-full object-cover" src="/<?= $project['image_url'] ?>"/>
                </div>
            </div>
        </section>

        <!-- CONTENT & SIDEBAR -->
        <section class="py-12 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20">
                    
                    <!-- Sidebar -->
                    <div class="lg:col-span-4 space-y-8">
                        <div class="p-8 rounded-2xl bg-surface-light border border-slate-100">
                            <h3 class="font-display text-lg font-bold text-slate-900 mb-6">Project Info</h3>
                            <div class="space-y-6">
                                <div>
                                    <span class="block text-xs uppercase tracking-wider text-slate-400 font-semibold">Client</span>
                                    <span class="block text-base text-slate-700 mt-1"><?= $project['client_name'] ?></span>
                                </div>
                                <div>
                                    <span class="block text-xs uppercase tracking-wider text-slate-400 font-semibold">Services</span>
                                    <span class="block text-base text-slate-700 mt-1"><?= $project['services'] ?></span>
                                </div>
                                <div>
                                    <span class="block text-xs uppercase tracking-wider text-slate-400 font-semibold">Venue</span>
                                    <span class="block text-base text-slate-700 mt-1"><?= $project['venue'] ?></span>
                                </div>
                                <div class="pt-6 border-t border-slate-200">
                                    <span class="block text-xs uppercase tracking-wider text-slate-400 font-semibold mb-3">Share</span>
                                    <div class="flex gap-3">
                                        <a class="flex items-center justify-center size-8 rounded-full bg-white text-slate-900 shadow-sm border border-slate-200 hover:bg-slate-50 transition-colors" href="#"><span class="text-xs font-bold">IG</span></a>
                                        <a class="flex items-center justify-center size-8 rounded-full bg-white text-slate-900 shadow-sm border border-slate-200 hover:bg-slate-50 transition-colors" href="#"><span class="text-xs font-bold">FB</span></a>
                                        <a class="flex items-center justify-center size-8 rounded-full bg-white text-slate-900 shadow-sm border border-slate-200 hover:bg-slate-50 transition-colors" href="#"><span class="text-xs font-bold">LI</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Text -->
                    <div class="lg:col-span-8">
                        <h2 class="font-display text-2xl font-bold text-slate-900 mb-6">The Concept</h2>
                        
                        <!-- Menampilkan deskripsi panjang (nl2br agar spasi paragraf terbaca) -->
                        <div class="text-lg text-slate-600 font-light leading-relaxed mb-8">
                            <?= nl2br($project['concept_text']) ?>
                        </div>

                        <?php if($project['testimonial_quote']): ?>
                        <blockquote class="border-l-4 border-slate-900 pl-6 my-10 italic text-slate-700 text-xl font-display">
                            "<?= $project['testimonial_quote'] ?>"
                        </blockquote>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- GALLERY GRID (Dynamic) -->
        <?php if(!empty($gallery_images)): ?>
        <section class="py-12 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    
                    <!-- Foto Besar Kiri (Gambar Pertama di Galeri) -->
                    <?php if(isset($gallery_images[0])): ?>
                    <div class="aspect-[4/5] rounded-2xl overflow-hidden bg-slate-100">
                        <img alt="Gallery Image 1" class="h-full w-full object-cover hover:scale-105 transition-transform duration-700" src="/<?= $gallery_images[0] ?>"/>
                    </div>
                    <?php endif; ?>

                    <!-- Foto Stack Kanan -->
                    <div class="grid grid-cols-1 gap-8">
                        <?php if(isset($gallery_images[1])): ?>
                        <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-slate-100">
                            <img alt="Gallery Image 2" class="h-full w-full object-cover hover:scale-105 transition-transform duration-700" src="/<?= $gallery_images[1] ?>"/>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($gallery_images[2])): ?>
                        <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-slate-100">
                            <img alt="Gallery Image 3" class="h-full w-full object-cover hover:scale-105 transition-transform duration-700" src="/<?= $gallery_images[2] ?>"/>
                        </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- VIDEO SECTION -->
        <?php if($project['video_thumbnail_url']): ?>
        <section class="py-16 bg-surface-light border-y border-slate-100">
            <div class="mx-auto max-w-5xl px-6 lg:px-8 text-center">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4 block">Cinematic Highlights</span>
                <h2 class="font-display text-3xl font-bold text-slate-900 mb-10">Relive the Day</h2>
                <div class="relative aspect-video overflow-hidden rounded-3xl bg-white shadow-xl ring-1 ring-slate-200/50 group cursor-pointer">
                    <div class="absolute inset-0 bg-cover bg-center opacity-95 transition-transform duration-700 group-hover:scale-105" style="background-image: url('<?= $project['video_thumbnail_url'] ?>');"></div>
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="flex size-20 items-center justify-center rounded-full bg-white/90 backdrop-blur-sm text-slate-900 shadow-2xl transition-all duration-300 group-hover:scale-110 group-hover:bg-white">
                            <span class="material-symbols-outlined text-4xl fill-current ml-1">play_arrow</span>
                        </div>
                    </div>
                </div>
                <p class="mt-6 text-sm text-slate-500">Duration: 03:45 • 4K Resolution</p>
            </div>
        </section>
        <?php endif; ?>

        <!-- TESTIMONIAL BOTTOM -->
        <?php if($project['testimonial_quote']): ?>
        <section class="py-24 bg-white">
            <div class="mx-auto max-w-4xl px-6 lg:px-8 text-center">
                <span class="material-symbols-outlined text-6xl text-slate-200 mb-6">format_quote</span>
                <h3 class="font-display text-2xl md:text-3xl font-medium text-slate-900 leading-normal mb-8">
                    "<?= $project['testimonial_quote'] ?>"
                </h3>
                <div class="flex items-center justify-center gap-4">
                    <div class="size-12 rounded-full bg-slate-100 overflow-hidden flex items-center justify-center">
                         <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400 font-bold text-lg">
                            <?= substr($project['testimonial_author'], 0, 1) ?>
                        </div>
                    </div>
                    <div class="text-left">
                        <div class="font-bold text-slate-900"><?= $project['testimonial_author'] ?></div>
                        <div class="text-xs text-slate-500 uppercase tracking-wide"><?= $project['testimonial_role'] ?></div>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- NAVIGATION FOOTER -->
        <section class="border-t border-slate-100 bg-slate-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    
                    <!-- PREVIOUS PROJECT -->
                    <?php if($prev_project): ?>
                    <a class="group relative flex flex-col justify-center py-16 px-6 md:px-12 border-b md:border-b-0 md:border-r border-slate-100 hover:bg-white transition-colors" 
                       href="portfolio-detail.php?id=<?= $prev_project['id'] ?>">
                        <span class="absolute top-8 left-8 text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2 group-hover:text-slate-600 transition-colors">
                            <span class="material-symbols-outlined text-base transition-transform group-hover:-translate-x-1">arrow_back</span>
                            Previous Project
                        </span>
                        <div class="mt-6">
                            <h4 class="font-display text-2xl font-bold text-slate-900 group-hover:text-slate-700"><?= $prev_project['title'] ?></h4>
                            <p class="text-slate-500 mt-2"><?= $prev_project['category_display'] ?></p>
                        </div>
                    </a>
                    <?php else: ?>
                    <!-- Empty div to keep grid layout if no previous -->
                    <div class="hidden md:block border-r border-slate-100"></div>
                    <?php endif; ?>

                    <!-- NEXT PROJECT -->
                    <?php if($next_project): ?>
                    <a class="group relative flex flex-col justify-center items-end text-right py-16 px-6 md:px-12 hover:bg-white transition-colors" 
                       href="portfolio-detail.php?id=<?= $next_project['id'] ?>">
                        <span class="absolute top-8 right-8 text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2 group-hover:text-slate-600 transition-colors">
                            Next Project
                            <span class="material-symbols-outlined text-base transition-transform group-hover:translate-x-1">arrow_forward</span>
                        </span>
                        <div class="mt-6">
                            <h4 class="font-display text-2xl font-bold text-slate-900 group-hover:text-slate-700"><?= $next_project['title'] ?></h4>
                            <p class="text-slate-500 mt-2"><?= $next_project['category_display'] ?></p>
                        </div>
                    </a>
                    <?php endif; ?>

                </div>
            </div>
        </section>
    </main>

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
                <p class="text-xs text-slate-400">© 2023 GDPARTSTUDIO. All rights reserved.</p>
                <div class="flex gap-4">
                    <a class="text-xs text-slate-400 hover:text-slate-600" href="#">Privacy Policy</a>
                    <a class="text-xs text-slate-400 hover:text-slate-600" href="#">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
</div>

</body>
</html>