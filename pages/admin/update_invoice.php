<?php
session_start();
include '../../db.php';
include_once 'log_helper.php'; // Include Helper Log

// 1. Cek Login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. Cek ID
if (!isset($_GET['id'])) {
    header("Location: manage_invoices.php");
    exit;
}

$id = intval($_GET['id']);

// --- PERBAIKAN 1: Ganti nama variabel agar tidak bentrok ---
$q_select = mysqli_query($conn, "SELECT * FROM invoices WHERE id = $id");
$data = mysqli_fetch_assoc($q_select);

if (!$data) { 
    echo "<script>alert('Data tidak ditemukan'); window.location='manage_invoices.php';</script>"; 
    exit; 
}

// Decode Items
$items_json = isset($data['items_json']) ? $data['items_json'] : '[]';
$items = json_decode($items_json, true);
if (!is_array($items) || empty($items)) { 
    $items = [['desc' => 'Layanan Utama', 'price' => $data['total_amount'], 'qty' => 1]]; 
}

// --- PROSES UPDATE DATA ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_name = mysqli_real_escape_string($conn, $_POST['client_name']);
    $client_email = mysqli_real_escape_string($conn, $_POST['client_email']);
    $client_phone = mysqli_real_escape_string($conn, $_POST['client_phone']);
    $inv_date = $_POST['inv_date'];
    $status = $_POST['status'];
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    // Data Kalkulasi
    $subtotal = $_POST['input_subtotal'];
    $discount_amount = $_POST['input_discount'];
    $tax_percent = $_POST['input_tax_percent'];
    $tax_amount = $_POST['input_tax_amount'];
    $grand_total = $_POST['input_grand_total'];

    // JSON Items
    $items_array = [];
    $descs = $_POST['item_desc'];
    $prices = $_POST['item_price'];
    $qtys = $_POST['item_qty'];

    for ($i = 0; $i < count($descs); $i++) {
        if (!empty($descs[$i])) {
            $items_array[] = ['desc' => $descs[$i], 'price' => $prices[$i], 'qty' => $qtys[$i]];
        }
    }
    $items_json = json_encode($items_array);

    // Hapus File PDF Lama agar digenerate ulang
    if (!empty($data['file_pdf'])) {
        $oldFilePath = "../../assets/invoice/" . $data['file_pdf'];
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }
    }

    // --- PERBAIKAN 2: Gunakan variabel $sql_update ---
    $sql_update = "UPDATE invoices SET 
              client_name='$client_name', 
              client_email='$client_email', 
              client_phone='$client_phone',
              invoice_date='$inv_date', 
              total_amount='$subtotal', 
              discount_amount='$discount_amount', 
              tax_percent='$tax_percent', 
              tax_amount='$tax_amount', 
              grand_total='$grand_total',
              status='$status', 
              notes='$notes', 
              items_json='$items_json',
              file_pdf = NULL 
              WHERE id=$id";
    
    // Eksekusi
    if (mysqli_query($conn, $sql_update)) {
        
        // Catat Log
        if (function_exists('writeLog')) {
            $admin_id = $_SESSION['admin_id'];
            $inv_num = $data['invoice_number'];
            writeLog($conn, $admin_id, 'Update', $inv_num, "Status diubah menjadi: $status");
        }

        echo "<script>alert('Update Berhasil!'); window.location='manage_invoices.php';</script>";
    } else {
        echo "<script>alert('Gagal Update: " . mysqli_error($conn) . "');</script>";
    }
}

// Cek apakah diskon/pajak aktif di data lama (untuk tampilan form)
$showDiscount = $data['discount_amount'] > 0;
$showTax = $data['tax_percent'] > 0;
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Update Invoice - GDPARTSTUDIO</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: "#135bec", "primary-hover": "#0f4bc4", "background-light": "#f8f9fc" },
                    fontFamily: { display: ["Inter", "sans-serif"] },
                },
            },
        }
    </script>
    <style> body { font-family: 'Inter', sans-serif; } .material-symbols-outlined { font-size: 20px; } </style>
</head>
<body class="bg-background-light text-[#0d121b] flex h-screen overflow-hidden text-sm">

    <?php $currentPage = 'invoices'; include '../../assets/components/admin/sidebar.php'; ?>
    <?php include '../../assets/components/admin/mobile_header.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-hidden relative md:ml-0 mt-14 md:mt-0">
        
        <header class="h-16 bg-white border-b border-[#cfd7e7] flex items-center justify-between px-8 shrink-0">
            <div class="flex items-center gap-4">
                <a href="manage_invoices.php" class="flex items-center gap-2 text-[#64748b] hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span> <span class="font-medium">Kembali</span>
                </a>
                <h2 class="text-[#0d121b] text-lg font-bold">Update Nota <?= $data['invoice_number'] ?></h2>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="download_invoice.php?id=<?= $id ?>" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-bold hover:bg-gray-50 text-gray-700 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">download</span> PDF
                </a>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <form method="POST" action="" class="max-w-[1000px] mx-auto flex flex-col gap-6 pb-12">
                
                <!-- Status Card -->
                <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm p-6 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-semibold text-[#64748b] uppercase tracking-wider mb-1 block">Status Saat Ini</span>
                        <?php
                            $statusClass = '';
                            if($data['status'] == 'Lunas') $statusClass = 'bg-green-100 text-green-700 border-green-200';
                            elseif($data['status'] == 'Pending') $statusClass = 'bg-amber-100 text-amber-700 border-amber-200';
                            else $statusClass = 'bg-red-100 text-red-700 border-red-200';
                        ?>
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium border <?= $statusClass ?>"><?= $data['status'] ?></span>
                    </div>
                    <div class="w-64">
                        <label class="block text-sm font-semibold text-[#0d121b] mb-1">Ubah Status</label>
                        <select name="status" class="w-full border-[#cfd7e7] rounded-lg text-sm focus:ring-primary focus:border-primary">
                            <option value="Lunas" <?= $data['status']=='Lunas'?'selected':'' ?>>Lunas</option>
                            <option value="Pending" <?= $data['status']=='Pending'?'selected':'' ?>>Pending</option>
                            <option value="Dibatalkan" <?= $data['status']=='Dibatalkan'?'selected':'' ?>>Dibatalkan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white border border-[#cfd7e7] rounded-xl p-6 shadow-sm">
                        <h3 class="font-bold text-[#0d121b] mb-4">Informasi Klien</h3>
                        <div class="space-y-4">
                            <div><label class="text-xs font-bold text-gray-500">Nama</label><input name="client_name" class="w-full rounded-lg border-gray-300" value="<?= $data['client_name'] ?>"></div>
                            <div><label class="text-xs font-bold text-gray-500">Nomor Telepon</label><input name="client_phone" class="w-full rounded-lg border-gray-300" value="<?= $data['client_phone'] ?>"></div>
                            <div><label class="text-xs font-bold text-gray-500">Email</label><input name="client_email" class="w-full rounded-lg border-gray-300" value="<?= $data['client_email'] ?>"></div>
                        </div>
                    </div>
                    <div class="bg-white border border-[#cfd7e7] rounded-xl p-6 shadow-sm">
                        <h3 class="font-bold text-[#0d121b] mb-4">Detail Nota</h3>
                        <div class="space-y-4">
                            <div><label class="text-xs font-bold text-gray-500">No. Invoice</label><input class="w-full rounded-lg bg-gray-100 border-gray-300" readonly value="<?= $data['invoice_number'] ?>"></div>
                            <div><label class="text-xs font-bold text-gray-500">Tanggal</label><input name="inv_date" type="date" class="w-full rounded-lg border-gray-300" value="<?= $data['invoice_date'] ?>"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-[#cfd7e7] flex justify-between items-center">
                        <h3 class="font-bold text-[#0d121b]">Daftar Layanan</h3>
                        <button type="button" onclick="addRow()" class="text-primary font-bold text-sm flex gap-1"><span class="material-symbols-outlined">add_circle</span> Tambah Item</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left" id="itemsTable">
                            <thead class="bg-[#f8fafc] border-b border-[#e2e8f0]">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 w-1/2">Deskripsi</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 w-32">Harga</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 w-20 text-center">Qty</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 w-32 text-right">Total</th>
                                    <th class="w-10"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#e2e8f0]">
                                <?php foreach($items as $item): ?>
                                <tr>
                                    <td class="px-6 py-4"><input class="w-full border-none p-0 bg-transparent" name="item_desc[]" value="<?= $item['desc'] ?>"></td>
                                    <td class="px-6 py-4"><input class="w-full border-none p-0 bg-transparent price-input" name="item_price[]" type="number" value="<?= $item['price'] ?>" oninput="calcTotal()"></td>
                                    <td class="px-6 py-4"><input class="w-full border-none p-0 bg-transparent text-center qty-input" name="item_qty[]" type="number" value="<?= $item['qty'] ?>" oninput="calcTotal()"></td>
                                    <td class="px-6 py-4 text-right"><span class="font-bold row-total">Rp 0</span></td>
                                    <td class="px-6 py-4 text-right"><button type="button" onclick="this.closest('tr').remove(); calcTotal()" class="text-red-400"><span class="material-symbols-outlined">delete</span></button></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    <div class="lg:col-span-7">
                        <label class="text-xs font-bold text-gray-500 uppercase">Catatan</label>
                        <textarea name="notes" class="w-full mt-2 border-gray-300 rounded-lg p-3" rows="3"><?= $data['notes'] ?></textarea>
                    </div>
                    <div class="lg:col-span-5 bg-white border border-[#cfd7e7] rounded-xl p-6 shadow-sm flex flex-col gap-4">
                        <div class="flex justify-between"><span>Subtotal</span><span class="font-bold" id="displaySubtotal">Rp 0</span></div>
                        
                        <div class="flex gap-2 text-xs">
                            <button type="button" id="btn-add-discount" onclick="toggleSection('discount-row', true)" class="text-primary hover:underline font-bold flex items-center gap-1" style="<?= $showDiscount ? 'display:none' : '' ?>">+ Diskon</button>
                            <button type="button" id="btn-add-tax" onclick="toggleSection('tax-row', true)" class="text-primary hover:underline font-bold flex items-center gap-1" style="<?= $showTax ? 'display:none' : '' ?>">+ Pajak</button>
                        </div>

                        <div class="flex flex-col gap-2 border-y border-gray-100 py-4">
                            <div id="discount-row" class="<?= $showDiscount ? 'flex' : 'hidden' ?> justify-between items-center">
                                <div class="flex items-center gap-2"><span class="text-gray-500">Diskon</span><input type="number" id="discountInput" class="w-24 border-gray-300 rounded p-1 text-right text-xs" value="<?= $data['discount_amount'] ?>" oninput="calcTotal()"></div>
                                <div class="flex items-center gap-2"><span class="text-red-600 font-medium" id="displayDiscount">- Rp 0</span><button type="button" onclick="toggleSection('discount-row', false)" class="text-red-400"><span class="material-symbols-outlined text-[16px]">close</span></button></div>
                            </div>
                            <div id="tax-row" class="<?= $showTax ? 'flex' : 'hidden' ?> justify-between items-center">
                                <div class="flex items-center gap-2"><span class="text-gray-500">Pajak</span><input type="number" id="taxInput" class="w-12 border-gray-300 rounded p-1 text-center text-xs" value="<?= $data['tax_percent'] ?>" oninput="calcTotal()"><span>%</span></div>
                                <div class="flex items-center gap-2"><span class="text-gray-500 font-medium" id="displayTax">Rp 0</span><button type="button" onclick="toggleSection('tax-row', false)" class="text-red-400"><span class="material-symbols-outlined text-[16px]">close</span></button></div>
                            </div>
                        </div>

                        <div class="flex justify-between text-lg font-bold text-primary"><span>Total Akhir</span><span id="displayGrandTotal">Rp 0</span></div>
                        
                        <input type="hidden" name="input_subtotal" id="inputSubtotal">
                        <input type="hidden" name="input_discount" id="inputDiscount">
                        <input type="hidden" name="input_tax_percent" id="inputTaxPercent">
                        <input type="hidden" name="input_tax_amount" id="inputTaxAmount">
                        <input type="hidden" name="input_grand_total" id="inputGrandTotal">
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="manage_invoices.php" class="px-6 py-2.5 rounded-lg border bg-white text-gray-500">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary text-white font-bold shadow-md">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        function formatRupiah(num) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num); }

        function toggleSection(id, show) {
            const el = document.getElementById(id);
            if(show) {
                el.classList.remove('hidden'); el.classList.add('flex');
            } else {
                el.classList.add('hidden'); el.classList.remove('flex');
                if(id === 'discount-row') document.getElementById('discountInput').value = 0;
                if(id === 'tax-row') document.getElementById('taxInput').value = 0;
                calcTotal();
            }
            if(id === 'discount-row') document.getElementById('btn-add-discount').style.display = show ? 'none' : 'flex';
            if(id === 'tax-row') document.getElementById('btn-add-tax').style.display = show ? 'none' : 'flex';
        }

        function calcTotal() {
            let subtotal = 0;
            document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
                const price = parseFloat(row.querySelector(".price-input").value) || 0;
                const qty = parseFloat(row.querySelector(".qty-input").value) || 0;
                const total = price * qty;
                row.querySelector(".row-total").innerText = formatRupiah(total);
                subtotal += total;
            });

            const discount = parseFloat(document.getElementById("discountInput").value) || 0;
            const taxPercent = parseFloat(document.getElementById("taxInput").value) || 0;
            const taxable = subtotal - discount;
            const taxAmount = taxable * (taxPercent / 100);
            const grandTotal = taxable + taxAmount;

            document.getElementById("displaySubtotal").innerText = formatRupiah(subtotal);
            document.getElementById("displayDiscount").innerText = "- " + formatRupiah(discount);
            document.getElementById("displayTax").innerText = formatRupiah(taxAmount);
            document.getElementById("displayGrandTotal").innerText = formatRupiah(grandTotal);

            document.getElementById("inputSubtotal").value = subtotal;
            document.getElementById("inputDiscount").value = discount;
            document.getElementById("inputTaxPercent").value = taxPercent;
            document.getElementById("inputTaxAmount").value = taxAmount;
            document.getElementById("inputGrandTotal").value = grandTotal;
        }

        function addRow() {
            const tbody = document.querySelector("#itemsTable tbody");
            const tr = document.createElement("tr");
            tr.innerHTML = `<td class="px-6 py-4"><input class="w-full border-none p-0 bg-transparent" name="item_desc[]" placeholder="Item baru"></td><td class="px-6 py-4"><input class="w-full border-none p-0 bg-transparent price-input" name="item_price[]" type="number" placeholder="0" oninput="calcTotal()"></td><td class="px-6 py-4"><input class="w-full border-none p-0 bg-transparent text-center qty-input" name="item_qty[]" type="number" value="1" oninput="calcTotal()"></td><td class="px-6 py-4 text-right"><span class="font-bold row-total">Rp 0</span></td><td class="px-6 py-4 text-right"><button type="button" onclick="this.closest('tr').remove(); calcTotal()" class="text-red-400"><span class="material-symbols-outlined">delete</span></button></td>`;
            tbody.appendChild(tr);
        }

        document.addEventListener("DOMContentLoaded", calcTotal);
    </script>

</body>
</html>