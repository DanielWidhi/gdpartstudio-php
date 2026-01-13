<?php
session_start();
include '../../db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// Generate Invoice Number
$query_inv = mysqli_query($conn, "SELECT id FROM invoices ORDER BY id DESC LIMIT 1");
$last_inv = mysqli_fetch_assoc($query_inv);
$next_id = ($last_inv) ? $last_inv['id'] + 1 : 1;
$invoice_number = "INV-" . date("ymd") . str_pad($next_id, 2, '0', STR_PAD_LEFT);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_name = mysqli_real_escape_string($conn, $_POST['client_name']);
    $client_email = mysqli_real_escape_string($conn, $_POST['client_email']);
    $client_phone = mysqli_real_escape_string($conn, $_POST['client_phone']); // Tambahan No Telp
    $inv_date = $_POST['inv_date'];
    
    $subtotal = $_POST['input_subtotal'];
    $discount_amount = $_POST['input_discount'];
    $tax_percent = $_POST['input_tax_percent'];
    $tax_amount = $_POST['input_tax_amount'];
    $grand_total = $_POST['input_grand_total'];
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

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

    // Query Insert dengan client_phone
    $query = "INSERT INTO invoices (invoice_number, client_name, client_email, client_phone, invoice_date, total_amount, discount_amount, tax_percent, tax_amount, grand_total, status, notes, items_json) 
              VALUES ('$invoice_number', '$client_name', '$client_email', '$client_phone', '$inv_date', '$subtotal', '$discount_amount', '$tax_percent', '$tax_amount', '$grand_total', 'Pending', '$notes', '$items_json')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Nota Berhasil Dibuat!'); window.location='manage_invoices.php';</script>";
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
    <title>Buat Nota Baru - GDPARTSTUDIO</title>
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
                    <span class="material-symbols-outlined">arrow_back</span><span class="font-medium">Kembali</span>
                </a>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <form method="POST" action="" class="max-w-[1200px] mx-auto flex flex-col gap-8 pb-12">
                
                <div>
                    <h2 class="text-[#0d121b] text-2xl font-bold tracking-tight">Buat Nota Baru</h2>
                    <p class="text-[#4c669a] text-sm mt-1">Lengkapi informasi di bawah ini.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Klien Info -->
                    <div class="bg-white border border-[#cfd7e7] rounded-xl p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-6">
                            <span class="material-symbols-outlined text-primary">person</span>
                            <h3 class="font-bold text-[#0d121b]">Informasi Klien</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-[#64748b] uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                                <input name="client_name" class="w-full px-3 py-2 border border-[#cfd7e7] rounded-lg focus:ring-primary outline-none" required/>
                            </div>
                            <!-- Tambahan No Telepon -->
                            <div>
                                <label class="block text-xs font-semibold text-[#64748b] uppercase tracking-wider mb-1.5">Nomor Telepon</label>
                                <input name="client_phone" class="w-full px-3 py-2 border border-[#cfd7e7] rounded-lg focus:ring-primary outline-none" type="text" placeholder="08..."/>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#64748b] uppercase tracking-wider mb-1.5">Email Klien</label>
                                <input name="client_email" class="w-full px-3 py-2 border border-[#cfd7e7] rounded-lg focus:ring-primary outline-none" type="email"/>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Info -->
                    <div class="bg-white border border-[#cfd7e7] rounded-xl p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-6">
                            <span class="material-symbols-outlined text-primary">description</span>
                            <h3 class="font-bold text-[#0d121b]">Detail Invoice</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-[#64748b] uppercase tracking-wider mb-1.5">Nomor Invoice</label>
                                <input class="w-full px-3 py-2 border border-[#cfd7e7] rounded-lg bg-[#f3f4f6] font-mono" readonly value="<?= $invoice_number ?>"/>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#64748b] uppercase tracking-wider mb-1.5">Tanggal Terbit</label>
                                <input name="inv_date" class="w-full px-3 py-2 border border-[#cfd7e7] rounded-lg focus:ring-primary outline-none" type="date" value="<?= date('Y-m-d') ?>"/>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Items -->
                <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-[#cfd7e7] flex justify-between">
                        <h3 class="font-bold text-[#0d121b] flex items-center gap-2"><span class="material-symbols-outlined text-primary">list_alt</span> Daftar Layanan</h3>
                        <button type="button" onclick="addRow()" class="text-primary font-bold text-sm flex gap-1"><span class="material-symbols-outlined">add_circle</span> Tambah Item</button>
                    </div>
                    <table class="w-full text-left" id="itemsTable">
                        <thead class="bg-[#f8fafc] border-b border-[#e2e8f0]">
                            <tr>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase w-1/2">Deskripsi</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase w-32">Harga</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase w-20 text-center">Qty</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase w-32 text-right">Total</th>
                                <th class="w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e2e8f0]">
                            <tr>
                                <td class="px-6 py-4"><input class="w-full border-none p-0 text-sm bg-transparent" name="item_desc[]" placeholder="Nama layanan" required/></td>
                                <td class="px-6 py-4"><input class="w-full border-none p-0 text-sm bg-transparent price-input" name="item_price[]" type="number" placeholder="0" oninput="calcTotal()"/></td>
                                <td class="px-6 py-4"><input class="w-full border-none p-0 text-sm bg-transparent text-center qty-input" name="item_qty[]" type="number" value="1" oninput="calcTotal()"/></td>
                                <td class="px-6 py-4 text-right"><span class="font-bold row-total">Rp 0</span></td>
                                <td class="px-6 py-4 text-right"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    <div class="lg:col-span-7">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Catatan</label>
                        <textarea name="notes" class="w-full border-gray-300 rounded-lg p-3" rows="3"></textarea>
                    </div>

                    <!-- Ringkasan Biaya -->
                    <div class="lg:col-span-5 bg-white border border-[#cfd7e7] rounded-xl p-6 shadow-sm flex flex-col gap-4">
                        <h3 class="font-bold text-[#0d121b] mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">account_balance_wallet</span> Ringkasan Biaya
                        </h3>
                        
                        <div class="flex justify-between text-gray-500"><span>Subtotal</span><span class="font-bold" id="displaySubtotal">Rp 0</span></div>

                        <!-- Area Tombol Aksi -->
                        <div class="flex gap-2 text-xs">
                            <button type="button" id="btn-add-discount" onclick="toggleSection('discount-row', true)" class="text-primary hover:underline font-bold flex items-center gap-1">+ Diskon</button>
                            <button type="button" id="btn-add-tax" onclick="toggleSection('tax-row', true)" class="text-primary hover:underline font-bold flex items-center gap-1">+ Pajak</button>
                        </div>

                        <div class="flex flex-col gap-2 border-y border-gray-100 py-4">
                            
                            <!-- Input Diskon (Hidden by default) -->
                            <div id="discount-row" class="hidden flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-500">Diskon (Rp)</span>
                                    <input type="number" id="discountInput" class="w-24 border-gray-300 rounded p-1 text-right text-xs" value="0" oninput="calcTotal()">
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-red-600 font-medium" id="displayDiscount">- Rp 0</span>
                                    <button type="button" onclick="toggleSection('discount-row', false)" class="text-red-400 hover:text-red-600"><span class="material-symbols-outlined text-[16px]">close</span></button>
                                </div>
                            </div>

                            <!-- Input Pajak (Hidden by default) -->
                            <div id="tax-row" class="hidden flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-500">Pajak (%)</span>
                                    <input type="number" id="taxInput" class="w-16 border-gray-300 rounded p-1 text-center text-xs" value="0" oninput="calcTotal()">
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-500 font-medium" id="displayTax">Rp 0</span>
                                    <button type="button" onclick="toggleSection('tax-row', false)" class="text-red-400 hover:text-red-600"><span class="material-symbols-outlined text-[16px]">close</span></button>
                                </div>
                            </div>

                        </div>

                        <div class="flex justify-between text-lg font-bold text-primary"><span>Total Akhir</span><span id="displayGrandTotal">Rp 0</span></div>
                        
                        <!-- Hidden Inputs -->
                        <input type="hidden" name="input_subtotal" id="inputSubtotal">
                        <input type="hidden" name="input_discount" id="inputDiscount" value="0">
                        <input type="hidden" name="input_tax_percent" id="inputTaxPercent" value="0">
                        <input type="hidden" name="input_tax_amount" id="inputTaxAmount" value="0">
                        <input type="hidden" name="input_grand_total" id="inputGrandTotal">
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="manage_invoices.php" class="px-6 py-2.5 rounded-lg border bg-white text-gray-500">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary text-white font-bold shadow-md">Simpan Nota</button>
                </div>

            </form>
        </div>
    </main>

    <script>
        function formatRupiah(num) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num); }

        function toggleSection(id, show) {
            const el = document.getElementById(id);
            if(show) {
                el.classList.remove('hidden');
                el.classList.add('flex');
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
                // Reset value saat di-hide
                if(id === 'discount-row') document.getElementById('discountInput').value = 0;
                if(id === 'tax-row') document.getElementById('taxInput').value = 0;
                calcTotal(); // Recalculate
            }
            // Toggle tombol Add
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

            // Ambil value (jika elemen hidden, tetap ambil value yg sudah di-reset jadi 0 oleh toggleSection)
            const discount = parseFloat(document.getElementById("discountInput").value) || 0;
            const taxPercent = parseFloat(document.getElementById("taxInput").value) || 0;

            const taxable = subtotal - discount;
            const taxAmount = taxable * (taxPercent / 100);
            const grandTotal = taxable + taxAmount;

            document.getElementById("displaySubtotal").innerText = formatRupiah(subtotal);
            document.getElementById("displayDiscount").innerText = "- " + formatRupiah(discount);
            document.getElementById("displayTax").innerText = formatRupiah(taxAmount);
            document.getElementById("displayGrandTotal").innerText = formatRupiah(grandTotal);

            // Update Hidden Inputs
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
    </script>

</body>
</html>