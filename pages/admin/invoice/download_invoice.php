<?php
session_start();
require '../../db.php';
require '../../vendor/autoload.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

// 1. Cek Login & Validasi ID
if (!isset($_SESSION['admin_logged_in']) || !isset($_GET['id'])) {
    die("Akses ditolak.");
}

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM invoices WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) { die("Invoice tidak ditemukan."); }

// --- LOGIKA SMART CACHE PDF ---

// Path folder penyimpanan
$folderPath = "../../assets/invoice/"; 
$fileName   = "Invoice-" . str_replace(['/', '\\'], '-', $data['invoice_number']) . ".pdf"; // Sanitasi nama file
$fullPath   = $folderPath . $fileName;

// Cek 1: Apakah file sudah tercatat di DB dan Fisiknya ada?
if (!empty($data['file_pdf']) && file_exists($folderPath . $data['file_pdf'])) {
    // JIKA ADA: Langsung tampilkan file yang sudah disimpan
    $existingFile = $folderPath . $data['file_pdf'];
    
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $data['file_pdf'] . '"');
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
    
    @readfile($existingFile);
    exit; // Selesai, tidak perlu generate ulang
}

// --- JIKA BELUM ADA: GENERATE PDF BARU ---

// Decode Items
$items = json_decode($data['items_json'] ?? '[]', true);

// Format Helper
$date = date('d F Y', strtotime($data['invoice_date']));
$dueDate = ($data['due_date']) ? date('d F Y', strtotime($data['due_date'])) : '-';
function formatRp($angka){ return "Rp " . number_format($angka, 0, ',', '.'); }

// Logo Base64
$pathLogo = '../../assets/images/Logo2b.png'; 
$type = pathinfo($pathLogo, PATHINFO_EXTENSION);
$dataLogo = file_get_contents($pathLogo);
$base64Logo = 'data:image/' . $type . ';base64,' . base64_encode($dataLogo);

// HTML Template (Sama seperti sebelumnya)
$html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #1a1a1a; font-size: 14px; line-height: 1.5; }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }
        .invoice-title { font-size: 40px; color: #e2e8f0; text-align: right; font-family: serif; font-weight: bold; line-height: 1; }
        .invoice-num { font-size: 14px; font-weight: bold; text-align: right; margin-top: 5px; }
        .info-label { font-size: 10px; color: #888; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; margin-bottom: 5px; }
        .info-value { font-size: 14px; font-weight: bold; margin-bottom: 2px; }
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 10px; font-weight: bold; text-transform: uppercase; display: inline-block; }
        .bg-lunas { background-color: #dcfce7; color: #166534; }
        .bg-pending { background-color: #fef3c7; color: #b45309; }
        .bg-batal { background-color: #fee2e2; color: #991b1b; }
        .table-items { margin-top: 40px; margin-bottom: 20px; width: 100%; }
        .table-items th { text-align: left; font-size: 10px; text-transform: uppercase; color: #888; border-bottom: 2px solid #1a1a1a; padding: 10px 0; }
        .table-items td { padding: 15px 0; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .grand-total { font-size: 20px; font-weight: bold; color: #135bec; }
        .footer { margin-top: 50px; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>
    <table style="margin-bottom: 40px;">
        <tr>
            <td width="50%">
                <img src="' . $base64Logo . '" style="width: 40px; vertical-align: middle;">
                <span style="font-size: 20px; font-weight: bold; margin-left: 10px; vertical-align: middle;">GDPARTSTUDIO</span>
            </td>
            <td width="50%" style="text-align: right;">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-num">#' . $data['invoice_number'] . '</div>
            </td>
        </tr>
    </table>

    <table style="margin-bottom: 30px;">
        <tr>
            <td width="50%">
                <div class="info-label">DITAGIHKAN KEPADA:</div>
                <div class="info-value" style="font-size: 16px; font-family: serif;">' . $data['client_name'] . '</div>
                <div>' . $data['client_email'] . '</div>
                <div>' . ($data['client_phone'] ?? '-') . '</div>
            </td>
            <td width="25%" style="text-align: right;">
                <div class="info-label">TANGGAL TERBIT</div>
                <div class="info-value">' . $date . '</div>
                <br>
                <div class="info-label">STATUS</div>';
                
                $statusColor = 'bg-pending';
                if($data['status'] == 'Lunas') $statusColor = 'bg-lunas';
                if($data['status'] == 'Dibatalkan') $statusColor = 'bg-batal';
                
                $html .= '<span class="badge ' . $statusColor . '">' . $data['status'] . '</span>
            </td>
            <td width="25%" style="text-align: right;">
                <div class="info-label">JATUH TEMPO</div>
                <div class="info-value" style="color: #dc2626;">' . $dueDate . '</div>
            </td>
        </tr>
    </table>

    <table class="table-items">
        <thead>
            <tr>
                <th width="50%">DESKRIPSI LAYANAN</th>
                <th width="10%" class="text-center">QTY</th>
                <th width="20%" class="text-right">HARGA SATUAN</th>
                <th width="20%" class="text-right">TOTAL</th>
            </tr>
        </thead>
        <tbody>';
        
        foreach($items as $item) {
            $totalItem = $item['price'] * $item['qty'];
            $html .= '
            <tr>
                <td><b>' . $item['desc'] . '</b></td>
                <td class="text-center">' . $item['qty'] . '</td>
                <td class="text-right" style="color: #666;">' . formatRp($item['price']) . '</td>
                <td class="text-right" style="font-weight: bold;">' . formatRp($totalItem) . '</td>
            </tr>';
        }

$html .= '
        </tbody>
    </table>

    <table>
        <tr>
            <td width="50%"></td>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td class="text-right" style="padding: 5px 0;">Subtotal</td>
                        <td class="text-right" style="font-weight: bold;">' . formatRp($data['total_amount']) . '</td>
                    </tr>';
                    
                    if($data['discount_amount'] > 0) {
                        $html .= '<tr><td class="text-right">Diskon</td><td class="text-right" style="color: green;">- ' . formatRp($data['discount_amount']) . '</td></tr>';
                    }
                    if($data['tax_amount'] > 0) {
                        $html .= '<tr><td class="text-right">Pajak (' . $data['tax_percent'] . '%)</td><td class="text-right">' . formatRp($data['tax_amount']) . '</td></tr>';
                    }

            $html .= '
                    <tr><td colspan="2" style="border-top: 1px solid #eee; padding: 10px;"></td></tr>
                    <tr>
                        <td class="text-right" style="font-weight: bold;">TOTAL AKHIR</td>
                        <td class="text-right grand-total">' . formatRp($data['grand_total']) . '</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="footer">
        <div class="info-label">CATATAN / INSTRUKSI</div>
        <div style="font-size: 12px; color: #444; margin-top: 5px;">' . nl2br($data['notes'] ?: "-") . '</div>
        <div style="text-align: center; margin-top: 40px; font-size: 9px; color: #aaa; letter-spacing: 2px;">
            GDPARTSTUDIO &copy; ' . date('Y') . '
        </div>
    </div>
</body>
</html>';

// 4. GENERATE & SAVE PDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Simpan Output ke Server (Assets/Invoice)
$output = $dompdf->output();
file_put_contents($fullPath, $output);

// Update Database dengan nama file
mysqli_query($conn, "UPDATE invoices SET file_pdf = '$fileName' WHERE id = $id");

// 5. TAMPILKAN DI BROWSER (PREVIEW)
// Attachment => false artinya tidak langsung download, tapi buka di tab baru
$dompdf->stream($fileName, ["Attachment" => false]);
?>