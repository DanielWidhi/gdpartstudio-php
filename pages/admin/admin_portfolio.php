<?php
session_start();
include '../../db.php';

// 1. Cek Keamanan Login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. Logika Hapus (Delete)
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    
    // Ambil path gambar lama
    $q = mysqli_query($conn, "SELECT image_url FROM projects WHERE id=$id");
    $data = mysqli_fetch_assoc($q);
    
    // Hapus file fisik jika ada
    if ($data && file_exists("../../" . $data['image_url'])) {
        unlink("../../" . $data['image_url']);
    }

    // Hapus dari database
    mysqli_query($conn, "DELETE FROM projects WHERE id=$id");
    
    // Refresh halaman
    header("Location: admin_portfolio.php");
    exit;
}

// 3. Logika Search & Filter Data
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$whereClause = "";
if($search) {
    $whereClause = "WHERE title LIKE '%$search%' OR client_name LIKE '%$search%' OR category_display LIKE '%$search%'";
}

// Query Data Utama
$query = "SELECT * FROM projects $whereClause ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$total_rows = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>GDPARTSTUDIO - Admin Dashboard</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
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
        .material-symbols-outlined { font-size: 20px; font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1; }
    </style>
</head>
<body class="bg-background-light text-[#0d121b] flex h-screen overflow-hidden">

<!-- SIDEBAR -->
<aside class="w-64 bg-white border-r border-[#cfd7e7] flex flex-col h-full shrink-0 z-20 hidden md:flex">
    <div class="p-6 flex items-center gap-3">
        <div class="w-8 h-8 rounded bg-primary flex items-center justify-center">
            <span class="material-symbols-outlined text-white">camera</span>
        </div>
        <h1 class="text-[#0d121b] text-base font-bold tracking-tight">GDPARTSTUDIO</h1>
    </div>
    <nav class="flex flex-col gap-1 px-3 mt-2 flex-1">
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-[#f3f4f6] group transition-colors" href="#">
            <span class="material-symbols-outlined text-[#4c669a] group-hover:text-[#0d121b]">dashboard</span>
            <span class="text-[#4c669a] text-sm font-medium group-hover:text-[#0d121b]">Dashboard</span>
        </a>
        <!-- Active Link Style -->
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary group transition-colors" href="admin_portfolio.php">
            <span class="material-symbols-outlined fill">inventory_2</span>
            <span class="text-primary text-sm font-bold">Portfolio</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-[#f3f4f6] group transition-colors" href="#">
            <span class="material-symbols-outlined text-[#4c669a] group-hover:text-[#0d121b]">handshake</span>
            <span class="text-[#4c669a] text-sm font-medium group-hover:text-[#0d121b]">Services</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-[#f3f4f6] group transition-colors" href="#">
            <span class="material-symbols-outlined text-[#4c669a] group-hover:text-[#0d121b]">settings</span>
            <span class="text-[#4c669a] text-sm font-medium group-hover:text-[#0d121b]">Settings</span>
        </a>
    </nav>
    <div class="p-3 mt-auto border-t border-[#cfd7e7]">
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-[#fee2e2] group transition-colors" href="../logout.php">
            <span class="material-symbols-outlined text-[#4c669a] group-hover:text-red-600">logout</span>
            <span class="text-[#4c669a] text-sm font-medium group-hover:text-red-600">Logout</span>
        </a>
    </div>
</aside>

<!-- MAIN CONTENT -->
<main class="flex-1 flex flex-col h-full overflow-hidden relative md:ml-0">
    
    <!-- TOP HEADER -->
    <header class="h-16 bg-white border-b border-[#cfd7e7] flex items-center justify-end px-8 shrink-0">
        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-semibold text-[#0d121b]"><?= $_SESSION['admin_name'] ?? 'Admin User' ?></p>
                <p class="text-xs text-[#4c669a]"><?= $_SESSION['admin_email'] ?? 'admin@gdpartstudio.com' ?></p>
            </div>
            <!-- User Avatar -->
            <div class="w-10 h-10 rounded-full bg-gray-200 bg-cover bg-center border border-gray-300" style="background-image: url('../../assets/images/user-placeholder.jpg');"></div>
        </div>
    </header>

    <!-- CONTENT BODY -->
    <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
        <div class="max-w-[1200px] mx-auto flex flex-col gap-6">
            
            <!-- Page Heading -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="text-[#0d121b] text-[32px] font-bold leading-tight tracking-tight">Portfolio Management</h2>
                    <p class="text-[#4c669a] text-sm font-normal mt-1">Manage, organize, and publish your latest wedding and event documentation.</p>
                </div>
            </div>

            <!-- Toolbar: Search & Add Button -->
            <div class="flex flex-col sm:flex-row gap-4 items-center justify-between bg-white p-4 rounded-xl border border-[#cfd7e7] shadow-sm">
                <!-- Search Form -->
                <form method="GET" class="w-full sm:w-96 relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-[#9ca3af] group-focus-within:text-primary transition-colors">search</span>
                    </div>
                    <input type="text" name="search" value="<?= $search ?>" class="block w-full pl-10 pr-3 py-2.5 border-none rounded-lg bg-[#f3f4f6] text-sm placeholder-[#9ca3af] focus:outline-none focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all" placeholder="Search by title, category, or date..."/>
                </form>
                
                <!-- Add Button -->
                <a href="create_post.php" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg transition-colors shadow-sm shadow-primary/30">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    <span class="text-sm font-bold tracking-wide">Add New Project</span>
                </a>
            </div>

            <!-- Data Table -->
            <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm overflow-hidden @container">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#f8fafc] border-b border-[#e2e8f0]">
                                <th class="px-6 py-4 text-xs font-semibold text-[#64748b] uppercase tracking-wider w-20">Thumbnail</th>
                                <th class="px-6 py-4 text-xs font-semibold text-[#64748b] uppercase tracking-wider min-w-[200px]">Title</th>
                                <th class="px-6 py-4 text-xs font-semibold text-[#64748b] uppercase tracking-wider">Category</th>
                                <th class="px-6 py-4 text-xs font-semibold text-[#64748b] uppercase tracking-wider">Date Created</th>
                                <th class="px-6 py-4 text-xs font-semibold text-[#64748b] uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-semibold text-[#64748b] uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e2e8f0]">
                            
                            <?php if($total_rows > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                    
                                    <!-- START ROW -->
                                    <tr class="group hover:bg-[#f8fafc] transition-colors">
                                        <td class="px-6 py-4 align-middle">
                                            <div class="h-12 w-16 bg-gray-100 rounded-md overflow-hidden bg-cover bg-center border border-gray-200" 
                                                 style="background-image: url('../../<?= $row['image_url'] ?>');">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 align-middle">
                                            <p class="text-[#0d121b] text-sm font-medium"><?= $row['title'] ?></p>
                                            <p class="text-[#64748b] text-xs">ID: #GD-2023-<?= str_pad($row['id'], 3, '0', STR_PAD_LEFT) ?></p>
                                        </td>
                                        <td class="px-6 py-4 align-middle">
                                            <?php
                                                // Warna dinamis berdasarkan kategori
                                                $cat = strtolower($row['filter_tag']);
                                                if($cat == 'weddings') { $badgeClass = 'bg-purple-50 text-purple-700 border-purple-100'; $dotClass = 'bg-purple-500'; }
                                                elseif($cat == 'religious') { $badgeClass = 'bg-orange-50 text-orange-700 border-orange-100'; $dotClass = 'bg-orange-500'; }
                                                elseif($cat == 'events') { $badgeClass = 'bg-blue-50 text-blue-700 border-blue-100'; $dotClass = 'bg-blue-500'; }
                                                else { $badgeClass = 'bg-gray-50 text-gray-700 border-gray-100'; $dotClass = 'bg-gray-500'; }
                                            ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium border <?= $badgeClass ?>">
                                                <span class="w-1.5 h-1.5 rounded-full <?= $dotClass ?>"></span>
                                                <?= $row['category_display'] ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 align-middle">
                                            <p class="text-[#0d121b] text-sm"><?= date('M d, Y', strtotime($row['created_at'])) ?></p>
                                            <p class="text-[#64748b] text-xs"><?= date('h:i A', strtotime($row['created_at'])) ?></p>
                                        </td>
                                        <td class="px-6 py-4 align-middle text-center">
                                            <?php 
                                                $status = $row['status'];
                                                $statusStyle = ($status == 'Published') 
                                                    ? 'bg-green-100 text-green-700 border-green-200' 
                                                    : 'bg-gray-100 text-gray-600 border-gray-200';
                                            ?>
                                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium border <?= $statusStyle ?>">
                                                <?= $status ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 align-middle text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="post_detail.php?id=<?= $row['id'] ?>" class="p-1.5 rounded-md text-[#64748b] hover:text-primary hover:bg-blue-50 transition-colors" title="View">
                                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                                </a>
                                                <a href="edit_post.php?id=<?= $row['id'] ?>" class="p-1.5 rounded-md text-[#64748b] hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit">
                                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                                </a>
                                                <a href="admin_portfolio.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus proyek ini?')" class="p-1.5 rounded-md text-[#64748b] hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- END ROW -->

                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-[#64748b]">
                                        Tidak ada data portfolio ditemukan. Silakan tambah data baru.
                                    </td>
                                </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="px-6 py-4 bg-white border-t border-[#e2e8f0] flex items-center justify-between">
                    <span class="text-sm text-[#64748b]">Showing 1 to <?= $total_rows ?> of <?= $total_rows ?> entries</span>
                    <div class="flex items-center gap-2">
                        <button class="p-2 rounded-lg border border-[#e2e8f0] text-[#64748b] hover:bg-[#f8fafc] hover:text-primary transition-colors disabled:opacity-50" disabled>
                            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                        </button>
                        <div class="flex items-center gap-1">
                            <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary text-white text-sm font-medium">1</button>
                            <!-- Placeholder pagination -->
                        </div>
                        <button class="p-2 rounded-lg border border-[#e2e8f0] text-[#64748b] hover:bg-[#f8fafc] hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

</body>
</html>