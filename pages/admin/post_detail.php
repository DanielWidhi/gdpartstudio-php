<?php
session_start();
include '../../db.php';

// Cek Login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// Cek ID
if (!isset($_GET['id'])) {
    header("Location: admin_portfolio.php");
    exit;
}
$id = intval($_GET['id']);

$query = mysqli_query($conn, "SELECT * FROM projects WHERE id = $id");
$project = mysqli_fetch_assoc($query);

if (!$project) {
    echo "Postingan tidak ditemukan!";
    exit;
}

$gallery_query = mysqli_query($conn, "SELECT * FROM project_gallery WHERE project_id = $id");
$gallery_items = [];
while ($row = mysqli_fetch_assoc($gallery_query)) {
    $gallery_items[] = $row;
}

function formatDate($dateStr) {
    return date('M d, Y', strtotime($dateStr));
}
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Post Details - GDPARTSTUDIO</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "primary-hover": "#0f4bc4",
                        "background-light": "#f8f9fc",
                        "background-dark": "#101622",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-size: 20px; font-variation-settings: 'FILL' 0; }
        .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1; }
    </style>
</head>
<body class="bg-background-light text-[#0d121b] flex h-screen overflow-hidden">

    <!-- 1. INCLUDE SIDEBAR -->
    <?php 
        $currentPage = 'portfolio'; 
        include '../../assets/components/admin/sidebar.php'; 
    ?>

    <!-- 2. INCLUDE MOBILE HEADER -->
    <?php include '../../assets/components/admin/mobile_header.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative md:ml-0 mt-14 md:mt-0">
        
        <?php 
            $pageTitle = "Portfolio > Detail"; 
            include '../../assets/components/admin/header.php'; 
        ?>

        <div class="flex-1 overflow-y-auto bg-background-light p-8">
            <div class="max-w-[1200px] mx-auto flex flex-col gap-6">
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-2 text-sm text-[#64748b]">
                        <a class="hover:text-primary transition-colors flex items-center gap-1" href="admin_portfolio.php">
                            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Portfolio
                        </a>
                        <span>/</span>
                        <span class="text-[#0d121b] font-medium">Post Details</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="admin_portfolio.php?delete_id=<?= $project['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')" class="flex items-center gap-2 px-4 py-2 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300 font-medium text-sm transition-all bg-white">
                            <span class="material-symbols-outlined text-[18px]">delete</span> Hapus Postingan
                        </a>
                        <a href="edit_post.php?id=<?= $project['id'] ?>" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-primary hover:bg-primary-hover text-white font-medium text-sm shadow-md transition-all">
                            <span class="material-symbols-outlined text-[18px]">edit_document</span> Edit Postingan
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 flex flex-col gap-6">
                        <div class="bg-white p-3 rounded-xl border border-[#cfd7e7] shadow-sm">
                            <div class="w-full aspect-video rounded-lg overflow-hidden bg-gray-100 relative group border border-gray-100">
                                <img alt="Featured" class="w-full h-full object-cover" src="../../<?= $project['image_url'] ?>"/>
                                <div class="absolute bottom-3 right-3 bg-black/60 text-white text-xs px-2.5 py-1 rounded-md backdrop-blur-sm flex items-center gap-1.5 font-medium">
                                    <span class="material-symbols-outlined text-[14px]">image</span> Featured Image
                                </div>
                            </div>
                            <div class="grid grid-cols-4 gap-3 mt-3">
                                <?php if (count($gallery_items) > 0): ?>
                                    <?php foreach($gallery_items as $item): ?>
                                        <div class="aspect-square rounded-lg overflow-hidden border border-gray-200 relative">
                                            <img src="../../<?= $item['image_url'] ?>" class="w-full h-full object-cover">
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="aspect-square rounded-lg border-2 border-dashed border-[#cfd7e7] flex flex-col items-center justify-center text-[#64748b] bg-[#f8fafc]">
                                        <span class="material-symbols-outlined">image_not_supported</span>
                                        <span class="text-xs font-medium mt-1">No Gallery</span>
                                    </div>
                                <?php endif; ?>
                                <a href="edit_post.php?id=<?= $project['id'] ?>" class="aspect-square rounded-lg border-2 border-dashed border-[#cfd7e7] flex flex-col items-center justify-center text-[#64748b] bg-[#f8fafc] hover:bg-[#f1f5f9] transition-colors cursor-pointer">
                                    <span class="material-symbols-outlined">add</span>
                                    <span class="text-xs font-medium mt-1">Add Media</span>
                                </a>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-xl border border-[#cfd7e7] shadow-sm">
                            <div class="flex items-center justify-between mb-4 pb-4 border-b border-[#f1f5f9]">
                                <h3 class="text-lg font-bold text-[#0d121b]">Description</h3>
                            </div>
                            <div class="space-y-4 text-[#4c669a] text-sm leading-relaxed">
                                <?= nl2br($project['description']) ?>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-6">
                        <div class="bg-white rounded-xl border border-[#cfd7e7] shadow-sm overflow-hidden">
                            <div class="p-6 pb-0">
                                <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider block mb-2">Project Title</span>
                                <h1 class="text-xl font-bold text-[#0d121b] leading-tight"><?= $project['title'] ?></h1>
                                <p class="text-xs text-[#64748b] mt-2">ID: #GD-2023-<?= str_pad($project['id'], 3, '0', STR_PAD_LEFT) ?></p>
                            </div>
                            <div class="p-6 space-y-5">
                                <div class="pt-5 border-t border-[#f1f5f9]">
                                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider block mb-2">Category</span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium bg-purple-50 text-purple-700 border border-purple-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                        <?= $project['category_display'] ?>
                                    </span>
                                </div>
                                <div class="pt-5 border-t border-[#f1f5f9]">
                                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider block mb-2">Status</span>
                                    <?php 
                                        $statusClass = $project['status'] == 'Published' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-gray-100 text-gray-700 border-gray-200';
                                        $icon = $project['status'] == 'Published' ? 'check_circle' : 'edit_document';
                                    ?>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium border <?= $statusClass ?>">
                                        <span class="material-symbols-outlined text-[16px]"><?= $icon ?></span>
                                        <?= $project['status'] ?>
                                    </span>
                                </div>
                                <div class="pt-5 border-t border-[#f1f5f9]">
                                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider block mb-2">Publication Info</span>
                                    <div class="flex items-start gap-3 mb-3">
                                        <span class="material-symbols-outlined text-[#94a3b8] text-[20px] mt-0.5">calendar_today</span>
                                        <div>
                                            <p class="text-sm font-medium text-[#0d121b]"><?= formatDate($project['created_at']) ?></p>
                                            <p class="text-xs text-[#64748b]">Created at</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span class="material-symbols-outlined text-[#94a3b8] text-[20px] mt-0.5">event</span>
                                        <div>
                                            <p class="text-sm font-medium text-[#0d121b]"><?= formatDate($project['event_date']) ?></p>
                                            <p class="text-xs text-[#64748b]">Event Date</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-xl border border-[#cfd7e7] shadow-sm">
                            <h3 class="text-xs font-bold text-[#64748b] uppercase tracking-wider mb-4">Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-2.5 py-1 rounded bg-[#f1f5f9] text-[#475569] text-xs font-medium border border-[#e2e8f0]">#<?= $project['filter_tag'] ?></span>
                                <span class="px-2.5 py-1 rounded bg-[#f1f5f9] text-[#475569] text-xs font-medium border border-[#e2e8f0]">#<?= strtolower(str_replace(' ', '', $project['client_name'])) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>