<?php
session_start();
include '../../db.php';

// 1. Cek Login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. Query Data Log
$query = "SELECT log.*, adm.name as admin_name, adm.email as admin_email 
          FROM activity_logs log 
          LEFT JOIN admins adm ON log.admin_id = adm.id 
          ORDER BY log.created_at DESC";

$result = mysqli_query($conn, $query);
$total_rows = mysqli_num_rows($result); // <-- Variabel yang tadinya hilang

// Helper Warna
function getBadgeColor($action) {
    switch ($action) {
        case 'Create': return 'bg-green-50 text-green-700 border-green-100 dot-green';
        case 'Delete': return 'bg-red-50 text-red-700 border-red-100 dot-red';
        case 'Update': return 'bg-blue-50 text-blue-700 border-blue-100 dot-blue';
        case 'Login Failed': return 'bg-red-100 text-red-800 border-red-200 dot-red'; 
        default: return 'bg-orange-50 text-orange-700 border-orange-100 dot-orange';
    }
}

// Helper Icon Device
function getDeviceIcon($device) {
    if (strpos($device, 'Windows') !== false) return 'laptop_windows';
    if (strpos($device, 'macOS') !== false) return 'laptop_mac';
    if (strpos($device, 'Android') !== false || strpos($device, 'iPhone') !== false) return 'smartphone';
    return 'devices';
}
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Activity Log - GDPARTSTUDIO</title>
    
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
        .material-symbols-outlined { font-size: 20px; font-variation-settings: 'FILL' 0, 'wght' 400; }
        .dot-green { background-color: #22c55e; }
        .dot-red { background-color: #ef4444; }
        .dot-blue { background-color: #3b82f6; }
        .dot-orange { background-color: #f97316; }
    </style>
</head>
<body class="bg-background-light text-[#0d121b] flex h-screen overflow-hidden">

    <?php 
        $currentPage = 'settings'; 
        include '../../assets/components/admin/sidebar.php'; 
    ?>

    <?php include '../../assets/components/admin/mobile_header.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-hidden relative md:ml-0 mt-14 md:mt-0">
        
        <header class="h-16 bg-white border-b border-[#cfd7e7] flex items-center justify-between px-8 shrink-0">
            <div class="flex items-center gap-2 text-sm">
                <span class="text-[#4c669a]">Settings</span>
                <span class="material-symbols-outlined text-[16px] text-[#9ca3af]">chevron_right</span>
                <span class="font-semibold text-[#0d121b]">Activity Log</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-[#0d121b]"><?= $_SESSION['admin_name'] ?></p>
                    <p class="text-xs text-[#4c669a]"><?= $_SESSION['admin_email'] ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-gray-200 bg-cover bg-center border border-gray-300" style="background-image: url('../../assets/images/user-placeholder.jpg');"></div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <div class="max-w-[1400px] mx-auto flex flex-col gap-6">
                
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <h2 class="text-[#0d121b] text-[32px] font-bold leading-tight tracking-tight">System Activity Log</h2>
                        <p class="text-[#4c669a] text-sm font-normal mt-1">Audit security logs including device and browser details.</p>
                    </div>
                </div>

                <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-[#f8fafc] border-b border-[#e2e8f0]">
                                    <th class="px-6 py-4 text-xs font-semibold text-[#64748b] uppercase tracking-wider">Time</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-[#64748b] uppercase tracking-wider">Admin</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-[#64748b] uppercase tracking-wider">Action</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-[#64748b] uppercase tracking-wider">Detail</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-[#64748b] uppercase tracking-wider">Perangkat</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-[#64748b] uppercase tracking-wider">IP Address</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#e2e8f0]">
                                
                                <?php if($total_rows > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr class="hover:bg-[#f8fafc] transition-colors">
                                    <td class="px-6 py-4 align-middle">
                                        <p class="text-[#0d121b] text-sm font-medium"><?= date('M d, Y', strtotime($row['created_at'])) ?></p>
                                        <p class="text-[#64748b] text-xs"><?= date('H:i:s', strtotime($row['created_at'])) ?></p>
                                    </td>

                                    <td class="px-6 py-4 align-middle">
                                        <div class="flex items-center gap-3">
                                            <?php if ($row['admin_name']): ?>
                                                <div class="w-8 h-8 rounded-full bg-gray-200 border border-gray-300 flex items-center justify-center text-xs font-bold text-gray-500">
                                                    <?= strtoupper(substr($row['admin_name'], 0, 2)) ?>
                                                </div>
                                                <span class="text-[#0d121b] text-sm font-medium"><?= $row['admin_name'] ?></span>
                                            <?php else: ?>
                                                <div class="w-8 h-8 rounded-full bg-red-100 border border-red-200 flex items-center justify-center text-xs font-bold text-red-500">?</div>
                                                <div>
                                                    <span class="text-red-600 text-sm font-bold">Unknown</span>
                                                    <p class="text-[10px] text-gray-400">Guest</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 align-middle">
                                        <?php 
                                            $badge = getBadgeColor($row['action_type']);
                                            if($row['action_type'] == 'Login Failed') $badge = 'bg-red-100 text-red-800 border-red-200 dot-red';
                                        ?>
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-xs font-medium border <?= $badge ?>">
                                            <span class="w-1.5 h-1.5 rounded-full <?= explode(' ', $badge)[3] ?? 'bg-gray-500' ?>"></span>
                                            <?= $row['action_type'] ?>
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 align-middle">
                                        <p class="text-[#0d121b] text-sm font-medium truncate max-w-[200px]"><?= $row['target_name'] ?></p>
                                        <p class="text-[#64748b] text-xs font-mono"><?= $row['detail'] ?></p>
                                    </td>

                                    <td class="px-6 py-4 align-middle">
                                        <div class="flex flex-col gap-1">
                                            <div class="flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-[16px] text-[#4c669a]"><?= getDeviceIcon($row['device']) ?></span>
                                                <span class="text-[#0d121b] text-xs font-medium"><?= $row['device'] ?></span>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-[16px] text-[#4c669a]">browser_updated</span>
                                                <span class="text-[#64748b] text-xs"><?= $row['browser'] ?></span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 align-middle">
                                        <span class="text-[#64748b] text-sm font-mono"><?= $row['ip_address'] ?></span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada aktivitas tercatat.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-6 py-4 bg-white border-t border-[#e2e8f0] flex items-center justify-between">
                        <span class="text-sm text-[#64748b]">Showing <?= $total_rows ?> entries</span>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>
</html>