<?php
session_start();
include '../../db.php';
include_once 'log_helper.php'; // Pastikan file ini ada di folder yang sama

// 1. Cek Login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. Proses Form Submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $client = mysqli_real_escape_string($conn, $_POST['client']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $status = $_POST['status'];
    
    // Auto Generate Slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

    // Mapping Kategori
    $category_input = $_POST['category_preset'];
    $category_display = "Event Documentation";
    $filter_tag = "events";

    switch ($category_input) {
        case 'wedding': $category_display = "Wedding Photography"; $filter_tag = "weddings"; break;
        case 'ceremony': $category_display = "Religious Ceremony"; $filter_tag = "religious"; break;
        case 'event': $category_display = "Corporate Event"; $filter_tag = "events"; break;
    }

    // Logic Upload Gambar
    $target_dir = "../../assets/uploads/";
    
    // Buat folder uploads jika belum ada
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

    // Folder spesifik project (Opsional: jika ingin dikelompokkan per project)
    $project_folder = $target_dir . $slug . "/";
    if (!file_exists($project_folder)) { mkdir($project_folder, 0777, true); }

    if (!empty($_FILES["thumbnail"]["name"])) {
        $filename = time() . "_" . basename($_FILES["thumbnail"]["name"]);
        $target_file = $project_folder . $filename;
        $db_image_path = "assets/uploads/" . $slug . "/" . $filename; // Path untuk database

        if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
            
            // --- DEFINISI VARIABLE $sql DISINI ---
            $sql = "INSERT INTO projects (title, slug, client_name, category_display, filter_tag, event_date, location, description, image_url, status) 
                    VALUES ('$title', '$slug', '$client', '$category_display', '$filter_tag', '$date', '$location', '$description', '$db_image_path', '$status')";
            
            // --- EKSEKUSI QUERY ---
            if (mysqli_query($conn, $sql)) {
                
                // --- CATAT LOG (Jika Sukses) ---
                if (function_exists('writeLog')) {
                    writeLog($conn, $_SESSION['admin_id'], 'Create', $title, 'Menambah portfolio baru');
                }
                
                echo "<script>alert('Postingan Berhasil Dibuat!'); window.location='admin_portfolio.php';</script>";
            } else {
                echo "<script>alert('Error Database: " . mysqli_error($conn) . "');</script>";
            }

        } else {
            echo "<script>alert('Gagal mengupload gambar ke server.');</script>";
        }
    } else {
        echo "<script>alert('Harap pilih gambar thumbnail!');</script>";
    }
}
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Buat Postingan Baru - GDPARTSTUDIO</title>
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
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; font-size: 20px; }
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
        
        <!-- TOP HEADER -->
        <?php 
            $pageTitle = "Portfolio > Buat Postingan"; 
            include '../../assets/components/admin/header.php'; 
        ?>

        <!-- CONTENT SCROLL AREA -->
        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <div class="max-w-[1200px] mx-auto flex flex-col gap-6">
                
                <!-- START FORM -->
                <form action="" method="POST" enctype="multipart/form-data">
                    
                    <!-- Page Header & Actions -->
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <a class="text-[#4c669a] hover:text-primary text-sm font-medium flex items-center gap-1 transition-colors" href="admin_portfolio.php">
                                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                                    Back to Portfolio
                                </a>
                            </div>
                            <h2 class="text-[#0d121b] text-[32px] font-bold leading-tight tracking-tight">Buat Postingan Baru</h2>
                            <p class="text-[#4c669a] text-sm font-normal mt-1">Isi formulir di bawah ini untuk menambahkan proyek baru ke portofolio Anda.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="admin_portfolio.php" class="px-5 py-2.5 rounded-lg border border-[#cfd7e7] bg-white text-[#4c669a] font-semibold text-sm hover:bg-[#f3f4f6] hover:text-[#0d121b] transition-colors shadow-sm">
                                Batal
                            </a>
                            <button type="submit" class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary hover:bg-primary-hover text-white font-bold text-sm shadow-sm shadow-primary/30 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">save</span>
                                Simpan & Publish
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <!-- LEFT COLUMN -->
                        <div class="lg:col-span-2 flex flex-col gap-6">
                            
                            <!-- Project Info Card -->
                            <div class="bg-white p-6 rounded-xl border border-[#cfd7e7] shadow-sm">
                                <h3 class="text-base font-semibold text-[#0d121b] mb-4">Informasi Proyek</h3>
                                <div class="space-y-5">
                                    <div>
                                        <label class="block text-sm font-medium text-[#0d121b] mb-1.5">Judul Proyek</label>
                                        <input class="block w-full rounded-lg border-[#cfd7e7] bg-[#f8fafc] text-[#0d121b] placeholder-[#94a3b8] focus:border-primary focus:ring-primary focus:bg-white transition-all sm:text-sm p-2.5 shadow-sm" name="title" placeholder="Contoh: Rina & Dika Wedding" required type="text"/>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-[#0d121b] mb-1.5">Deskripsi</label>
                                        <div class="border border-[#cfd7e7] rounded-lg overflow-hidden bg-white focus-within:ring-1 focus-within:ring-primary focus-within:border-primary transition-all shadow-sm">
                                            <!-- Fake Toolbar -->
                                            <div class="flex items-center gap-1 p-2 bg-[#f8fafc] border-b border-[#e2e8f0]">
                                                <button type="button" class="p-1.5 rounded hover:bg-white text-[#64748b]"><span class="material-symbols-outlined text-[18px]">format_bold</span></button>
                                                <button type="button" class="p-1.5 rounded hover:bg-white text-[#64748b]"><span class="material-symbols-outlined text-[18px]">format_italic</span></button>
                                                <div class="w-px h-4 bg-[#cbd5e1] mx-1"></div>
                                                <button type="button" class="p-1.5 rounded hover:bg-white text-[#64748b]"><span class="material-symbols-outlined text-[18px]">link</span></button>
                                            </div>
                                            <textarea class="block w-full border-none p-4 text-[#0d121b] placeholder-[#94a3b8] focus:ring-0 sm:text-sm resize-none" name="description" placeholder="Tulis deskripsi detail tentang proyek ini..." rows="8"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Tambahan Card -->
                            <div class="bg-white p-6 rounded-xl border border-[#cfd7e7] shadow-sm">
                                <h3 class="text-base font-semibold text-[#0d121b] mb-4">Detail Tambahan</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-medium text-[#0d121b] mb-1.5">Nama Klien</label>
                                        <input class="block w-full rounded-lg border-[#cfd7e7] bg-[#f8fafc] text-[#0d121b] placeholder-[#94a3b8] focus:border-primary focus:ring-primary focus:bg-white transition-all sm:text-sm p-2.5 shadow-sm" name="client" placeholder="Nama Klien" type="text" required/>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-[#0d121b] mb-1.5">Lokasi</label>
                                        <input class="block w-full rounded-lg border-[#cfd7e7] bg-[#f8fafc] text-[#0d121b] placeholder-[#94a3b8] focus:border-primary focus:ring-primary focus:bg-white transition-all sm:text-sm p-2.5 shadow-sm" name="location" placeholder="Contoh: Bali, Indonesia" type="text" required/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN -->
                        <div class="lg:col-span-1 flex flex-col gap-6">
                            
                            <!-- Pengaturan Post Card -->
                            <div class="bg-white p-6 rounded-xl border border-[#cfd7e7] shadow-sm">
                                <h3 class="text-base font-semibold text-[#0d121b] mb-4">Pengaturan Post</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-[#0d121b] mb-1.5">Status</label>
                                        <select class="block w-full rounded-lg border-[#cfd7e7] bg-[#f8fafc] text-[#0d121b] focus:border-primary focus:ring-primary focus:bg-white sm:text-sm p-2.5 shadow-sm" name="status">
                                            <option value="Published">Published</option>
                                            <option value="Draft">Draft</option>
                                            <option value="Archived">Archived</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-[#0d121b] mb-1.5">Kategori</label>
                                        <select class="block w-full rounded-lg border-[#cfd7e7] bg-[#f8fafc] text-[#0d121b] focus:border-primary focus:ring-primary focus:bg-white sm:text-sm p-2.5 shadow-sm" name="category_preset">
                                            <option value="wedding">Wedding</option>
                                            <option value="ceremony">Ceremony</option>
                                            <option value="event">Corporate Event</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-[#0d121b] mb-1.5">Tanggal Acara</label>
                                        <input class="block w-full rounded-lg border-[#cfd7e7] bg-[#f8fafc] text-[#0d121b] focus:border-primary focus:ring-primary focus:bg-white sm:text-sm p-2.5 shadow-sm" name="date" type="date" required/>
                                    </div>
                                </div>
                            </div>

                            <!-- Media Card -->
                            <div class="bg-white p-6 rounded-xl border border-[#cfd7e7] shadow-sm">
                                <h3 class="text-base font-semibold text-[#0d121b] mb-4">Media</h3>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-[#0d121b] mb-1.5">Gambar Utama (Thumbnail)</label>
                                    <label for="thumbnail-upload" class="border-2 border-dashed border-[#cfd7e7] rounded-xl p-6 flex flex-col items-center justify-center text-center bg-[#f8fafc] hover:bg-[#f1f5f9] hover:border-primary/50 transition-all cursor-pointer group h-48 relative">
                                        <div class="w-12 h-12 rounded-full bg-white border border-[#e2e8f0] flex items-center justify-center mb-3 shadow-sm group-hover:scale-110 transition-transform">
                                            <span class="material-symbols-outlined text-primary text-[24px]">cloud_upload</span>
                                        </div>
                                        <p class="text-sm font-medium text-[#0d121b]">Klik untuk upload</p>
                                        <p class="text-[10px] text-[#94a3b8] mt-2 uppercase">PNG, JPG, WEBP (Max 5MB)</p>
                                        <input id="thumbnail-upload" name="thumbnail" accept="image/*" class="hidden" type="file" onchange="previewFile()"/>
                                        <p id="file-name" class="absolute bottom-2 text-xs text-primary font-medium truncate w-3/4"></p>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
                <!-- END FORM -->

            </div>
        </div>
    </main>

    <script>
        function previewFile() {
            const input = document.getElementById('thumbnail-upload');
            const fileNameDisplay = document.getElementById('file-name');
            if (input.files && input.files.length > 0) {
                fileNameDisplay.textContent = "Selected: " + input.files[0].name;
            } else {
                fileNameDisplay.textContent = "";
            }
        }
    </script>
</body>
</html>