<?php
session_start();
include '../../db.php';
include 'log_helper.php';

// 1. Cek Login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. Proses Simpan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']); // Kolom baru di logic (opsional di DB)
    $serial_number = mysqli_real_escape_string($conn, $_POST['serial_number']);
    
    $status = $_POST['status'];
    $location_rack = mysqli_real_escape_string($conn, $_POST['location_rack']);
    $condition = $_POST['condition'];
    
    $purchase_date = $_POST['purchase_date'];
    $last_service = $_POST['last_service'];
    $next_service = $_POST['next_service'];
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    // Logic Upload Foto
    $db_image_path = NULL;
    if (!empty($_FILES["equipment_photo"]["name"])) {
        $target_dir = "../../assets/uploads/equipment/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $filename = time() . "_" . basename($_FILES["equipment_photo"]["name"]);
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($_FILES["equipment_photo"]["tmp_name"], $target_file)) {
            $db_image_path = "assets/uploads/equipment/" . $filename;
        }
    }

    // Query Insert (Sesuaikan dengan struktur tabel Anda, kolom 'brand' dan 'purchase_date' jika belum ada bisa diabaikan atau ditambahkan)
    // Asumsi tabel 'equipments' sudah ada kolom-kolomnya. Jika belum, sesuaikan query ini.
    $sql = "INSERT INTO equipments (name, category, serial_number, status, rack_location, condition_status, last_service_date, next_service_date, image_url) 
            VALUES ('$name', '$category', '$serial_number', '$status', '$location_rack', '$condition', '$last_service', '$next_service', '$db_image_path')";
    
    if (mysqli_query($conn, $sql)) {
        if (function_exists('writeLog')) {
            writeLog($conn, $_SESSION['admin_id'], 'Create', $name, 'Menambahkan alat baru ke inventaris');
        }
        echo "<script>alert('Alat Berhasil Ditambahkan!'); window.location='equipment.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Tambah Alat Baru - GDPARTSTUDIO</title>
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
                    colors: { primary: "#135bec", "primary-hover": "#0f4bc4", "background-light": "#f8f9fc", "background-dark": "#101622" },
                    fontFamily: { display: ["Inter", "sans-serif"] },
                },
            },
        }
    </script>
    <style> body { font-family: 'Inter', sans-serif; } .material-symbols-outlined { font-size: 20px; } </style>
</head>
<body class="bg-background-light text-[#0d121b] flex h-screen overflow-hidden">

    <?php $currentPage = 'equipment'; include '../../assets/components/admin/sidebar.php'; ?>
    <?php include '../../assets/components/admin/mobile_header.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-hidden relative md:ml-0 mt-14 md:mt-0">
        
        <header class="h-16 bg-white border-b border-[#cfd7e7] flex items-center justify-between px-8 shrink-0">
            <div class="flex items-center gap-2 text-sm text-[#4c669a]">
                <a href="equipment.php" class="hover:text-primary">Equipment</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="font-semibold text-[#0d121b]">Tambah Alat</span>
            </div>
            <!-- User Area (Bisa pakai include header.php jika mau) -->
             <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-[#0d121b]"><?= $_SESSION['admin_name'] ?? 'Admin' ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-gray-200 bg-cover bg-center border border-gray-300" style="background-image: url('../../assets/images/user-placeholder.jpg');"></div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <div class="max-w-4xl mx-auto flex flex-col gap-6">
                
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-[#0d121b] text-2xl font-bold tracking-tight">Tambah Alat Baru</h2>
                        <p class="text-[#4c669a] text-sm">Lengkapi formulir di bawah untuk mendaftarkan aset baru.</p>
                    </div>
                    <a href="equipment.php" class="text-sm font-medium text-[#4c669a] hover:text-primary flex items-center gap-1">
                        <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali
                    </a>
                </div>

                <form method="POST" action="" enctype="multipart/form-data" class="flex flex-col gap-6 pb-12">
                    
                    <!-- INFORMASI UTAMA -->
                    <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-[#e2e8f0] bg-gray-50/50">
                            <h3 class="text-sm font-bold text-[#0d121b] flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-lg">info</span> Informasi Utama
                            </h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-[#64748b] uppercase tracking-wider mb-2">Nama Alat</label>
                                <input name="name" class="w-full px-4 py-2.5 bg-white border border-[#cfd7e7] rounded-lg text-sm focus:ring-primary focus:border-primary" placeholder="Contoh: Sony Alpha A7S III" type="text" required/>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#64748b] uppercase tracking-wider mb-2">Kategori</label>
                                <select name="category" class="w-full px-4 py-2.5 bg-white border border-[#cfd7e7] rounded-lg text-sm focus:ring-primary focus:border-primary">
                                    <option value="Kamera">Kamera</option>
                                    <option value="Lensa">Lensa</option>
                                    <option value="Drone">Drone</option>
                                    <option value="Lighting">Lighting</option>
                                    <option value="Audio">Audio</option>
                                    <option value="Aksesoris">Aksesoris</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#64748b] uppercase tracking-wider mb-2">Brand / Merk</label>
                                <input name="brand" class="w-full px-4 py-2.5 bg-white border border-[#cfd7e7] rounded-lg text-sm focus:ring-primary focus:border-primary" placeholder="Contoh: Sony, DJI" type="text"/>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-[#64748b] uppercase tracking-wider mb-2">Serial Number (SN)</label>
                                <input name="serial_number" class="w-full px-4 py-2.5 bg-white border border-[#cfd7e7] rounded-lg text-sm font-mono focus:ring-primary focus:border-primary" placeholder="SN-XXXX-XXXX" type="text"/>
                            </div>
                            
                            <!-- Upload Foto -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-[#64748b] uppercase tracking-wider mb-2">Foto Alat</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-[#cfd7e7] border-dashed rounded-lg hover:border-primary transition-all group relative cursor-pointer" onclick="document.getElementById('file-upload').click()">
                                    <div class="space-y-1 text-center">
                                        <span class="material-symbols-outlined text-gray-400 text-4xl mb-2 group-hover:text-primary transition-colors">add_a_photo</span>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <span class="font-medium text-primary hover:text-primary-hover">Unggah foto</span>
                                            <input id="file-upload" name="equipment_photo" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)"/>
                                        </div>
                                        <p class="text-xs text-[#64748b]" id="file-name-preview">PNG, JPG, JPEG hingga 5MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STATUS & KONDISI -->
                    <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-[#e2e8f0] bg-gray-50/50">
                            <h3 class="text-sm font-bold text-[#0d121b] flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-lg">fact_check</span> Status & Kondisi
                            </h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-[#64748b] uppercase tracking-wider mb-2">Status Saat Ini</label>
                                <select name="status" class="w-full px-4 py-2.5 bg-white border border-[#cfd7e7] rounded-lg text-sm focus:ring-primary focus:border-primary">
                                    <option value="Di Studio">Di Studio (Tersedia)</option>
                                    <option value="Di Lapangan">Di Lapangan (Dipinjam)</option>
                                    <option value="Maintenance">Maintenance (Servis)</option>
                                    <option value="Rusak">Rusak</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#64748b] uppercase tracking-wider mb-2">Lokasi Penyimpanan</label>
                                <input name="location_rack" class="w-full px-4 py-2.5 bg-white border border-[#cfd7e7] rounded-lg text-sm focus:ring-primary focus:border-primary" placeholder="Contoh: Rak A1" type="text"/>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-[#64748b] uppercase tracking-wider mb-2">Kondisi Fisik</label>
                                <div class="flex flex-wrap gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="condition" value="Excellent" checked class="text-primary focus:ring-primary h-4 w-4 border-gray-300"/>
                                        <span class="text-sm text-[#0d121b]">Excellent</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="condition" value="Good" class="text-primary focus:ring-primary h-4 w-4 border-gray-300"/>
                                        <span class="text-sm text-[#0d121b]">Good</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="condition" value="Fair" class="text-primary focus:ring-primary h-4 w-4 border-gray-300"/>
                                        <span class="text-sm text-[#0d121b]">Fair (Butuh Perhatian)</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="condition" value="Poor" class="text-primary focus:ring-primary h-4 w-4 border-gray-300"/>
                                        <span class="text-sm text-[#0d121b]">Poor (Rusak Ringan)</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PERAWATAN -->
                    <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-[#e2e8f0] bg-gray-50/50">
                            <h3 class="text-sm font-bold text-[#0d121b] flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-lg">build_circle</span> Perawatan & Pembelian
                            </h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-[#64748b] uppercase tracking-wider mb-2">Tanggal Pembelian</label>
                                <input name="purchase_date" class="w-full px-4 py-2.5 bg-white border border-[#cfd7e7] rounded-lg text-sm focus:ring-primary focus:border-primary" type="date"/>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#64748b] uppercase tracking-wider mb-2">Terakhir Servis</label>
                                <input name="last_service" class="w-full px-4 py-2.5 bg-white border border-[#cfd7e7] rounded-lg text-sm focus:ring-primary focus:border-primary" type="date"/>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#64748b] uppercase tracking-wider mb-2">Jadwal Servis Mendatang</label>
                                <input name="next_service" class="w-full px-4 py-2.5 bg-white border border-[#cfd7e7] rounded-lg text-sm focus:ring-primary focus:border-primary" type="date"/>
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-xs font-bold text-[#64748b] uppercase tracking-wider mb-2">Catatan Tambahan</label>
                                <textarea name="notes" class="w-full px-4 py-2.5 bg-white border border-[#cfd7e7] rounded-lg text-sm focus:ring-primary focus:border-primary" rows="3" placeholder="Info tambahan..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- BUTTONS -->
                    <div class="flex items-center justify-end gap-3 mt-4">
                        <a href="equipment.php" class="px-6 py-2.5 border border-[#cfd7e7] text-[#4c669a] font-semibold rounded-lg text-sm hover:bg-gray-50 transition-all">Batal</a>
                        <button type="submit" class="px-8 py-2.5 bg-primary hover:bg-primary-hover text-white font-semibold rounded-lg text-sm shadow-md transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">save</span> Simpan Alat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                document.getElementById('file-name-preview').innerText = "File terpilih: " + input.files[0].name;
            }
        }
    </script>
</body>
</html>