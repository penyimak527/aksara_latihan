<?php
$naskah = isset($naskah) ? $naskah : [];
$soal = isset($soal) ? $soal : [];

function h_naskah($text)
{
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}
function potong_teks_naskah($text, $limit = 42)
{
    $text = trim((string) $text);

    if ($text === '') {
        return '-';
    }

    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        return (mb_strlen($text, 'UTF-8') > $limit)
            ? mb_substr($text, 0, $limit, 'UTF-8') . '...'
            : $text;
    }

    return (strlen($text) > $limit)
        ? substr($text, 0, $limit) . '...'
        : $text;
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
     * Nilai dibuat per blok soal supaya satu soal tidak dipotong. Dompdf
     * tidak bisa memberi tinggi asli sebelum render, jadi estimasi dibuat
     * lebih aman untuk lebar kolom PDF.
     */
    $nilai = 2;
    $tipe = $row['tipe_soal'] ?? '';

    $pertanyaan = trim(strip_tags((string) ($row['pertanyaan'] ?? '')));
    $nilai += max(1, ceil(strlen($pertanyaan) / 48));

    $jawaban = isset($row['jawaban']) && is_array($row['jawaban']) ? $row['jawaban'] : [];
    foreach ($jawaban as $jawab) {
        $teks = trim(strip_tags((string) ($jawab['isi_jawaban'] ?? '')));

        if ($tipe === 'benar_salah') {
            /*
             * Benar/salah biasanya tampil minimal 2 baris:
             * 1 baris pernyataan + 1 baris pilihan Benar/Salah.
             */
            $nilai += 2 + max(0, ceil(strlen($teks) / 55) - 1);
        } else {
            $nilai += max(1, ceil(strlen($teks) / 55));
        }
    }

    if (!empty($row['gambar_soal'])) {
        $nilai += 8;
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
        /*
         * Kapasitas dihitung untuk setiap kolom, bukan untuk satu halaman.
         * Halaman pertama lebih kecil karena terdapat KOP dan identitas.
         * Nilai ini mempertahankan total kapasitas lama:
         * halaman pertama 39 + 39, halaman lanjutan 52 + 52.
         */
        $batas_kolom = ($page_index == 0) ? 43 : 56;

        $kiri = [];
        $kanan = [];

        /*
         * Urutan pengisian:
         * 1. Isi kolom kiri sampai tidak cukup untuk soal berikutnya.
         * 2. Lanjutkan soal berikutnya ke kolom kanan.
         * 3. Jika kolom kanan penuh, lanjutkan ke halaman berikutnya.
         */
        foreach (['kiri', 'kanan'] as $nama_kolom) {
            $total_estimasi_kolom = 0;

            while ($index < $total_soal) {
                $row = $soal[$index];
                $tinggi = max(1, estimasi_tinggi_soal($row));

                /*
                 * Jangan memotong satu soal. Jika sisa kolom tidak cukup,
                 * soal utuh dipindahkan ke kolom atau halaman berikutnya.
                 */
                if (
                    $total_estimasi_kolom > 0 &&
                    ($total_estimasi_kolom + $tinggi) > $batas_kolom
                ) {
                    break;
                }

                /*
                 * Jika satu soal lebih tinggi daripada kapasitas kolom,
                 * tetap tempatkan soal tersebut sendirian agar proses tidak
                 * berhenti berulang pada soal yang sama.
                 */
                if ($nama_kolom === 'kiri') {
                    $kiri[] = $row;
                } else {
                    $kanan[] = $row;
                }

                $total_estimasi_kolom += $tinggi;
                $index++;

                if ($total_estimasi_kolom >= $batas_kolom) {
                    break;
                }
            }
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
            position: relative;
            width: 100%;
            height: 198mm;
            clear: both;
        }

        .page-lanjutan .soal-columns {
            height: 260mm;
        }

        .soal-col {
            position: absolute;
            top: 0;
            width: 47.8%;
            padding: 0;
            overflow: visible;
        }

        .soal-col.left {
            left: 0;
        }

        .soal-col.right {
            left: 52.2%;
        }

        .soal-item {
            page-break-inside: avoid;
            margin-bottom: 13px;
            position: relative;
            padding-left: 24px;
        }

        .soal-row {
            display: block;
            width: 100%;
        }

        .soal-number {
            position: absolute;
            left: 0;
            top: 0;
            width: 22px;
            font-weight: bold;
            font-size: 13px;
            line-height: 1.5;
        }

        .soal-content {
            display: block;
            width: 100%;
            font-size: 13px;
        }

        .pertanyaan {
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 7px;
            word-wrap: break-word;
        }

        .gambar-soal {
            max-width: 85px;
            max-height: 85px;
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
            position: relative;
            padding-left: 24px;
            margin-bottom: 5px;
            line-height: 1.45;
            page-break-inside: avoid;
        }

        .opsi-label {
            position: absolute;
            left: 0;
            top: 0;
            width: 20px;
            font-weight: bold;
        }

        .opsi-text {
            display: block;
            width: 100%;
            word-wrap: break-word;
        }

        .bs-row {
            margin-bottom: 7px;
            page-break-inside: avoid;
        }

        .bs-question {
            margin-bottom: 3px;
            word-wrap: break-word;
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
                <p>Alamat:
                    ....................................................................................................................
                </p>
                <p>Telepon: ........................................ &nbsp; Email:
                    ....................................................</p>
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
                        <p>Alamat:
                            ....................................................................................................................
                        </p>
                        <p>Telepon: ........................................ &nbsp; Email:
                            ....................................................</p>
                    </div>
                    <!-- 
                    <div class="title">
                        <h2>NASKAH SOAL</h2>
                    </div> -->

                    <table class="meta">
                        <tr>
                            <td style="width: 120px;">Nama Naskah</td>
                            <td style="width: 8px;">:</td>
                            <!-- <td style="width: ;"><?= h_naskah($naskah['nama_naskah_soal'] ?? '-'); ?></td> -->
                            <td
                                style="width: 260px; max-width: 260px; white-space: normal; word-wrap: break-word; overflow-wrap: break-word; line-height: 1.35;">
                                <?= h_naskah($naskah['nama_naskah_soal'] ?? '-'); ?></td>
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