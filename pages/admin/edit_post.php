<?php
session_start();
include '../../db.php';
include_once 'log_helper.php'; // Pastikan helper log ada

// 1. Cek Login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. Ambil ID dari URL
if (!isset($_GET['id'])) {
    header("Location: admin_portfolio.php");
    exit;
}
$id = intval($_GET['id']);

// 3. Ambil Data Lama (READ)
// Kita menggunakan variabel $q_data, BUKAN $sql agar tidak bentrok
$q_data = mysqli_query($conn, "SELECT * FROM projects WHERE id = $id");
$data = mysqli_fetch_assoc($q_data);

// Jika Data Tidak Ditemukan
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='admin_portfolio.php';</script>";
    exit;
}

// 4. Proses Update Data (Hanya jalan jika tombol Simpan ditekan)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $client = mysqli_real_escape_string($conn, $_POST['client']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $status = $_POST['status'];
    
    // Auto Generate Slug Baru
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

    // Mapping Kategori
    $category_input = $_POST['category_preset'];
    $category_display = $data['category_display']; // Default ambil lama
    $filter_tag = $data['filter_tag']; // Default ambil lama

    if($category_input) {
        switch ($category_input) {
            case 'wedding': $category_display = "Wedding Photography"; $filter_tag = "weddings"; break;
            case 'ceremony': $category_display = "Religious Ceremony"; $filter_tag = "religious"; break;
            case 'event': $category_display = "Corporate Event"; $filter_tag = "events"; break;
        }
    }

    // Logic Update Gambar
    $image_query = "";
    if (!empty($_FILES["thumbnail"]["name"])) {
        // Gunakan slug untuk folder
        $upload_root = "../../assets/uploads/";
        $project_folder = $upload_root . $slug . "/";

        // Buat folder jika belum ada
        if (!file_exists($project_folder)) { mkdir($project_folder, 0777, true); }

        $filename = time() . "_" . basename($_FILES["thumbnail"]["name"]);
        $target_file = $project_folder . $filename;
        
        if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
            // Hapus gambar lama fisik
            if (!empty($data['image_url']) && file_exists("../../" . $data['image_url'])) {
                unlink("../../" . $data['image_url']);
            }
            $db_path = "assets/uploads/" . $slug . "/" . $filename;
            $image_query = ", image_url='$db_path'";
        }
    }

    // Definisi Variabel $sql (Query Update)
    $sql = "UPDATE projects SET 
            title='$title', slug='$slug', client_name='$client', 
            category_display='$category_display', filter_tag='$filter_tag', 
            event_date='$date', location='$location', description='$description', 
            status='$status' $image_query 
            WHERE id=$id";

    // Eksekusi Query
    if (mysqli_query($conn, $sql)) {
        
        // --- CATAT LOG ---
        if (function_exists('writeLog')) {
            writeLog($conn, $_SESSION['admin_id'], 'Update', $title, 'Memperbarui data portfolio');
        }

        echo "<script>alert('Perubahan Berhasil Disimpan!'); window.location='admin_portfolio.php';</script>";
    } else {
        echo "<script>alert('Gagal Update: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Edit Postingan - GDPARTSTUDIO</title>
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
            $pageTitle = "Portfolio > Edit Postingan"; 
            include '../../assets/components/admin/header.php'; 
        ?>

        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <div class="max-w-[1200px] mx-auto flex flex-col gap-6">
                
                <!-- Form Edit -->
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div>
                            <div class="flex items-center gap-2 text-sm text-[#4c669a] mb-1">
                                <a href="admin_portfolio.php" class="hover:text-primary flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">arrow_back</span> Portfolio
                                </a>
                                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                                <span class="font-medium text-[#0d121b]">Edit Postingan</span>
                            </div>
                            <h2 class="text-[#0d121b] text-[32px] font-bold leading-tight">Edit Postingan</h2>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="admin_portfolio.php" class="px-4 py-2.5 rounded-lg border border-[#cfd7e7] bg-white text-[#4c669a] text-sm font-medium hover:bg-[#f3f4f6] transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="flex items-center gap-2 bg-primary hover:bg-primary-hover text-white px-6 py-2.5 rounded-lg transition-colors shadow-sm font-bold text-sm">
                                <span class="material-symbols-outlined text-[20px]">save</span>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        
                        <!-- LEFT COLUMN -->
                        <div class="lg:col-span-2 space-y-6">
                            
                            <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm overflow-hidden">
                                <div class="border-b border-[#cfd7e7] px-6 py-4 bg-[#f8fafc]">
                                    <h3 class="text-[#0d121b] text-base font-bold">Informasi Proyek</h3>
                                </div>
                                <div class="p-6 space-y-6">
                                    <div>
                                        <label class="block text-sm font-medium text-[#0d121b] mb-1.5">Judul Proyek</label>
                                        <input name="title" type="text" class="block w-full rounded-lg border-[#cfd7e7] bg-[#f8f9fc] text-sm px-4 py-2.5 focus:border-primary focus:ring-primary" value="<?= $data['title'] ?>" required/>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-[#4c669a] mb-1.5">ID Postingan</label>
                                            <input class="block w-full rounded-lg border-[#e2e8f0] bg-gray-50 text-[#64748b] text-sm px-4 py-2.5 cursor-not-allowed" readonly type="text" value="#GD-2023-<?= str_pad($data['id'], 3, '0', STR_PAD_LEFT) ?>"/>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-[#0d121b] mb-1.5">Slug URL (Auto)</label>
                                            <input class="block w-full rounded-lg border-[#cfd7e7] bg-[#f8f9fc] text-sm px-4 py-2.5 cursor-not-allowed" readonly type="text" value="<?= $data['slug'] ?>"/>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-[#0d121b] mb-1.5">Deskripsi</label>
                                        <textarea name="description" rows="6" class="block w-full rounded-lg border-[#cfd7e7] bg-[#f8f9fc] text-sm px-4 py-2.5 focus:border-primary focus:ring-primary"><?= $data['description'] ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm overflow-hidden">
                                <div class="border-b border-[#cfd7e7] px-6 py-4 bg-[#f8fafc]">
                                    <h3 class="text-[#0d121b] text-base font-bold">Detail Tambahan</h3>
                                </div>
                                <div class="p-6 space-y-4">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-[#0d121b] mb-1.5">Nama Klien</label>
                                            <input name="client" type="text" class="block w-full rounded-lg border-[#cfd7e7] bg-[#f8f9fc] text-sm px-4 py-2.5" value="<?= $data['client_name'] ?>" required/>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-[#0d121b] mb-1.5">Lokasi</label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-[#94a3b8] material-symbols-outlined text-[18px]">location_on</span>
                                                <input name="location" type="text" class="block w-full pl-9 rounded-lg border-[#cfd7e7] bg-[#f8f9fc] text-sm px-4 py-2.5" value="<?= $data['location'] ?>" required/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN -->
                        <div class="lg:col-span-1 space-y-6">
                            <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm overflow-hidden">
                                <div class="border-b border-[#cfd7e7] px-6 py-4 bg-[#f8fafc]">
                                    <h3 class="text-[#0d121b] text-base font-bold">Status Publikasi</h3>
                                </div>
                                <div class="p-6 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-[#0d121b] mb-1.5">Status</label>
                                        <select name="status" class="block w-full rounded-lg border-[#cfd7e7] bg-[#f8f9fc] text-sm px-4 py-2.5 focus:border-primary focus:ring-primary">
                                            <option value="Published" <?= $data['status'] == 'Published' ? 'selected' : '' ?>>Published</option>
                                            <option value="Draft" <?= $data['status'] == 'Draft' ? 'selected' : '' ?>>Draft</option>
                                            <option value="Archived" <?= $data['status'] == 'Archived' ? 'selected' : '' ?>>Archived</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-[#0d121b] mb-1.5">Kategori</label>
                                        <select name="category_preset" class="block w-full rounded-lg border-[#cfd7e7] bg-[#f8f9fc] text-sm px-4 py-2.5 focus:border-primary focus:ring-primary">
                                            <option value="wedding" <?= $data['filter_tag'] == 'weddings' ? 'selected' : '' ?>>Wedding</option>
                                            <option value="ceremony" <?= $data['filter_tag'] == 'religious' ? 'selected' : '' ?>>Ceremony</option>
                                            <option value="event" <?= $data['filter_tag'] == 'events' ? 'selected' : '' ?>>Corporate Event</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-[#0d121b] mb-1.5">Tanggal Acara</label>
                                        <input name="date" type="date" class="block w-full rounded-lg border-[#cfd7e7] bg-[#f8f9fc] text-sm px-4 py-2.5" value="<?= date('Y-m-d', strtotime($data['event_date'])) ?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm overflow-hidden">
                                <div class="border-b border-[#cfd7e7] px-6 py-4 bg-[#f8fafc]">
                                    <h3 class="text-[#0d121b] text-base font-bold">Media</h3>
                                </div>
                                <div class="p-6">
                                    <div class="w-full aspect-video rounded-lg bg-gray-100 border border-[#e2e8f0] overflow-hidden relative group mb-4">
                                        <img src="../../<?= $data['image_url'] ?>" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <span class="text-white text-sm font-medium">Ganti Gambar di Bawah</span>
                                        </div>
                                    </div>
                                    <label class="block text-sm font-medium text-[#0d121b] mb-1.5">Ganti Gambar Baru</label>
                                    <input type="file" name="thumbnail" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-hover"/>
                                </div>
                            </div>

                            <div class="bg-red-50 border border-red-100 rounded-xl shadow-sm overflow-hidden">
                                <div class="p-6">
                                    <h3 class="text-red-800 text-sm font-bold mb-2">Hapus Postingan</h3>
                                    <p class="text-red-600/80 text-xs mb-4">Tindakan ini tidak dapat dibatalkan.</p>
                                    <a href="admin_portfolio.php?delete_id=<?= $data['id'] ?>" onclick="return confirm('Yakin ingin menghapus permanen?')" class="flex items-center justify-center gap-2 bg-white border border-red-200 text-red-600 hover:bg-red-600 hover:text-white px-4 py-2.5 rounded-lg transition-colors font-medium text-sm">
                                        <span class="material-symbols-outlined text-[20px]">delete</span> Hapus Postingan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>