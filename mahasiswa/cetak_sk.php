<?php
require_once "../middleware/auth_middleware.php";
require_once "../config/database.php";
require_once "../dompdf/autoload.inc.php";

use Dompdf\Dompdf;

checkAuth();
checkRole(['mahasiswa']);

$user_id = $_SESSION['user_id'];

$q = mysqli_query($conn, "
SELECT 
    m.npm,
    m.nama,
    m.tgl_mulai_kuliah,
    m.tgl_ujian,
    m.ipk,
    m.predikat,
    m.peringkat,
    p.nama_prodi,
    y.nina,
    y.nomor_sk,
    pe.nama_periode
FROM mahasiswa m
JOIN yudisium y ON m.npm = y.npm
JOIN prodi p ON m.prodi_id = p.id
JOIN periode pe ON y.periode_id = pe.id
WHERE m.user_id = $user_id
AND y.status_final_baa = 1
ORDER BY y.id DESC
LIMIT 1
");

$data = mysqli_fetch_assoc($q);

if (!$data) {
    die("Data tidak ditemukan atau belum disetujui BAA.");
}

$html = "
<h2 style='text-align:center'>SURAT KETERANGAN YUDISIUM</h2>
<hr>

<p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>

<table cellpadding='5'>
<tr><td><b>Nama</b></td><td>: {$data['nama']}</td></tr>
<tr><td><b>NPM</b></td><td>: {$data['npm']}</td></tr>
<tr><td><b>Program Studi</b></td><td>: {$data['nama_prodi']}</td></tr>
<tr><td><b>Periode Yudisium</b></td><td>: {$data['nama_periode']}</td></tr>
<tr><td><b>Nomor SK Yudisium</b></td><td>: {$data['nomor_sk']}</td></tr>
<tr><td><b>NINA</b></td><td>: {$data['nina']}</td></tr>
<tr><td><b>Tanggal Mulai Kuliah</b></td><td>: {$data['tgl_mulai_kuliah']}</td></tr>
<tr><td><b>Tanggal Ujian</b></td><td>: {$data['tgl_ujian']}</td></tr>
<tr><td><b>IPK</b></td><td>: {$data['ipk']}</td></tr>
<tr><td><b>Predikat</b></td><td>: {$data['predikat']}</td></tr>
<tr><td><b>Peringkat</b></td><td>: {$data['peringkat']}</td></tr>
</table>

<br><br>
<p>
Dengan ini dinyatakan telah menyelesaikan seluruh proses akademik dan dinyatakan layak mengikuti yudisium.
</p>

<br><br><br>
<table width='100%'>
<tr>
<td>
Mengetahui,<br>
Kaprodi<br><br><br>
( __________________ )
</td>

<td style='text-align:right'>
Mahasiswa<br><br><br>
( {$data['nama']} )
</td>
</tr>
</table>
";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("SK_Yudisium_{$data['npm']}.pdf", ["Attachment" => false]);
exit;
