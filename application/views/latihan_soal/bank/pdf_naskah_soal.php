<?php
$naskah = isset($naskah) ? $naskah : [];
$soal = isset($soal) ? $soal : [];

function h_naskah($text)
{
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

function nomor_label_naskah($index)
{
    $index = (int) $index;
    $label = '';

    do {
        $label = chr(65 + ($index % 26)) . $label;
        $index = floor($index / 26) - 1;
    } while ($index >= 0);

    return $label;
}

function estimasi_tinggi_soal($row)
{
    /*
     * Estimasi ini dipakai hanya untuk membagi soal ke halaman dan kolom.
     * Angkanya dibuat tidak terlalu besar supaya ruang kosong PDF bisa terisi.
     */
    $nilai = 2;

    $pertanyaan = strip_tags((string) ($row['pertanyaan'] ?? ''));
    $nilai += max(1, ceil(strlen($pertanyaan) / 80));

    $jawaban = isset($row['jawaban']) && is_array($row['jawaban']) ? $row['jawaban'] : [];
    foreach ($jawaban as $jawab) {
        $teks = strip_tags((string) ($jawab['isi_jawaban'] ?? ''));
        $nilai += max(1, ceil(strlen($teks) / 90));
    }

    if (!empty($row['gambar_soal'])) {
        $nilai += 10;
    }

    return $nilai;
}

function bagi_soal_dua_kolom($soal)
{
    $halaman = [];
    $index = 0;
    $total_soal = count($soal);
    $page_index = 0;

    while ($index < $total_soal) {
        $items_halaman = [];
        $total_estimasi = 0;

        /*
         * Kapasitas halaman.
         * Halaman pertama lebih kecil karena ada KOP dan identitas.
         * Halaman berikutnya lebih besar karena tidak ada KOP.
         */
        $batas_halaman = ($page_index == 0) ? 78 : 105;

        while ($index < $total_soal) {
            $row = $soal[$index];
            $tinggi = estimasi_tinggi_soal($row);

            if (!empty($items_halaman) && ($total_estimasi + $tinggi) > $batas_halaman) {
                break;
            }

            $items_halaman[] = $row;
            $total_estimasi += $tinggi;
            $index++;
        }

        /*
         * Pembagian kolom dibuat seimbang.
         * Tidak isi kiri sampai penuh, karena itu bisa membuat semua soal berada di kiri.
         */
        $jumlah_item = count($items_halaman);
        $jumlah_kiri = (int) ceil($jumlah_item / 2);

        $kiri = array_slice($items_halaman, 0, $jumlah_kiri);
        $kanan = array_slice($items_halaman, $jumlah_kiri);

        /*
         * Jika kanan kosong tapi item lebih dari 1, paksa sebagian pindah kanan.
         */
        if ($jumlah_item > 1 && empty($kanan)) {
            $kanan[] = array_pop($kiri);
        }

        $halaman[] = [
            'kiri' => $kiri,
            'kanan' => $kanan
        ];

        $page_index++;
    }

    return $halaman;
}
function render_soal_pdf_naskah($row, $index)
{
    $nomor = $row['nomor_soal'] ?? ($index + 1);
    $tipe = $row['tipe_soal'] ?? '';
    ?>
    <div class="soal-item">
        <div class="soal-row">
            <div class="soal-number"><?= h_naskah($nomor); ?>.</div>
            <div class="soal-content">
                <div class="pertanyaan">
                    <?= $row['pertanyaan'] ?? '-'; ?>
                </div>

                <?php if (!empty($row['gambar_soal'])): ?>
                    <img src="<?= h_naskah($row['gambar_soal']); ?>" class="gambar-soal" alt="Gambar Soal">
                <?php endif; ?>

                <div class="opsi">
                    <?php if ($tipe === 'benar_salah'): ?>
                        <?php foreach (($row['jawaban'] ?? []) as $idx => $jawab): ?>
                            <div class="bs-row">
                                <div class="bs-question">
                                    <?= ($idx + 1); ?>. <?= h_naskah($jawab['isi_jawaban'] ?? '-'); ?>
                                </div>
                                <div class="bs-option">
                                    ( ) Benar &nbsp;&nbsp;&nbsp; ( ) Salah
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php foreach (($row['jawaban'] ?? []) as $idx => $jawab): ?>
                            <div class="opsi-row">
                                <div class="opsi-label">
                                    <?= h_naskah($jawab['label_jawaban'] ?? nomor_label_naskah($idx)); ?>.
                                </div>
                                <div class="opsi-text">
                                    <?= h_naskah($jawab['isi_jawaban'] ?? '-'); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= h_naskah($naskah['nama_naskah_soal'] ?? 'Naskah Soal'); ?></title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #222;
            margin: 0;
            background: #fff;
            font-size: 12px;
            line-height: 1.45;
        }

        .page {
            width: 100%;
            background: #fff;
        }

        .page-lanjutan {
            padding-top: 2mm;
        }

        .kop {
            border: 1.5px solid #111;
            padding: 10px 12px;
            margin-bottom: 12px;
            text-align: center;
        }

        .kop h1 {
            margin: 0;
            font-size: 18px;
            letter-spacing: .5px;
        }

        .kop p {
            margin: 3px 0 0;
            font-size: 11px;
        }

        .title {
            text-align: center;
            margin: 12px 0 12px;
        }

        .title h2 {
            margin: 0;
            font-size: 16px;
            text-decoration: underline;
        }

        .meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 12px;
        }

        .meta td {
            padding: 3px 4px;
            vertical-align: top;
        }

        .soal-columns {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .soal-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .soal-col.left {
            padding-right: 14px;
        }

        .soal-col.right {
            padding-left: 14px;
        }

        .soal-item {
            page-break-inside: avoid;
            margin-bottom: 13px;
        }

        .soal-row {
            display: table;
            width: 100%;
        }

        .soal-number {
            display: table-cell;
            width: 28px;
            font-weight: bold;
            vertical-align: top;
            font-size: 13px;
        }

        .soal-content {
            display: table-cell;
            vertical-align: top;
            font-size: 13px;
        }

        .pertanyaan {
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 7px;
        }

        .gambar-soal {
            max-width: 100%;
            max-height: 220px;
            display: block;
            margin: 8px 0;
            border: 1px solid #ddd;
            padding: 4px;
        }

        .opsi {
            margin-top: 6px;
            font-size: 13px;
        }

        .opsi-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
            line-height: 1.45;
        }

        .opsi-label {
            display: table-cell;
            width: 24px;
            font-weight: bold;
            vertical-align: top;
        }

        .opsi-text {
            display: table-cell;
            vertical-align: top;
        }

        .bs-row {
            margin-bottom: 7px;
            page-break-inside: avoid;
        }

        .bs-question {
            margin-bottom: 3px;
        }

        .bs-option {
            margin-left: 0;
            white-space: nowrap;
        }

        @page {
            size: A4;
            margin: 14mm;
        }
    </style>
</head>
<body>
    <?php if (empty($soal)): ?>
        <div class="page">
            <div class="kop">
                <h1>KOP BIMBEL AKSARA</h1>
                <p>Alamat: ....................................................................................................................</p>
                <p>Telepon: ........................................ &nbsp; Email: ....................................................</p>
            </div>

            <div class="title">
                <h2>NASKAH SOAL</h2>
            </div>

            <p>Belum ada soal aktif pada naskah ini.</p>
        </div>
    <?php else: ?>
        <?php
            $halaman_soal = bagi_soal_dua_kolom($soal);
            $total_halaman = count($halaman_soal);
            $nomor_render = 0;
        ?>

        <?php foreach ($halaman_soal as $page_index => $halaman): ?>
            <?php
                $style_page_break = ($page_index < $total_halaman - 1) ? 'page-break-after: always;' : '';
            ?>

            <div class="page <?= $page_index > 0 ? 'page-lanjutan' : ''; ?>" style="<?= $style_page_break; ?>">
                <?php if ($page_index == 0): ?>
                    <div class="kop">
                        <h1>KOP BIMBEL AKSARA</h1>
                        <p>Alamat: ....................................................................................................................</p>
                        <p>Telepon: ........................................ &nbsp; Email: ....................................................</p>
                    </div>
<!-- 
                    <div class="title">
                        <h2>NASKAH SOAL</h2>
                    </div> -->

                    <table class="meta">
                        <tr>
                            <td style="width: 120px;">Nama Naskah</td>
                            <td style="width: 8px;">:</td>
                            <td><?= h_naskah($naskah['nama_naskah_soal'] ?? '-'); ?></td>
                            <td style="width: 100px;">Jumlah Soal</td>
                            <td style="width: 8px;">:</td>
                            <td><?= (int) ($naskah['jumlah_soal'] ?? 0); ?> soal</td>
                        </tr>
                        <tr>
                            <td>Mata Pelajaran</td>
                            <td>:</td>
                            <td><?= h_naskah($naskah['nama_mata_pelajaran'] ?? '-'); ?></td>
                            <td>Kategori</td>
                            <td>:</td>
                            <td><?= h_naskah($naskah['nama_kategori_soal'] ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td>Nama Siswa</td>
                            <td>:</td>
                            <td>..............................................................</td>
                            <td>Kelas</td>
                            <td>:</td>
                            <td>................................</td>
                        </tr>
                    </table>
                <?php endif; ?>

                <div class="soal-columns">
                    <div class="soal-col left">
                        <?php foreach ($halaman['kiri'] as $row): ?>
                            <?php
                                render_soal_pdf_naskah($row, $nomor_render);
                                $nomor_render++;
                            ?>
                        <?php endforeach; ?>
                    </div>

                    <div class="soal-col right">
                        <?php foreach ($halaman['kanan'] as $row): ?>
                            <?php
                                render_soal_pdf_naskah($row, $nomor_render);
                                $nomor_render++;
                            ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>