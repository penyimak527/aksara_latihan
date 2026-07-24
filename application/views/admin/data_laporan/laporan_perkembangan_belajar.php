<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perkembangan Belajar</title>
    <style>
        @page { size: A4 portrait; margin: 18mm 15mm; }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            line-height: 1.4;
        }
        .kop-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .kop-logo { width: 28%; text-align: center; vertical-align: middle; }
        .kop-logo img { width: 145px; height: auto; display: inline-block; }
        .kop-title { width: 72%; text-align: center; vertical-align: middle; padding-left: 8px; }
        .kop-title .judul { font-size: 14px; font-weight: bold; line-height: 1.25; }
        .kop-title .periode { margin-top: 4px; font-size: 10px; font-weight: bold; }
        .kop-title .tanggal { margin-top: 2px; font-size: 9px; }
        .kop-line { margin: 7px 0 12px; border-top: 2px solid #000; border-bottom: 1px solid #000; height: 3px; }
        .section { margin-bottom: 14px; page-break-inside: auto; }
        .section-title {
            margin: 0 0 5px;
            padding-bottom: 4px;
            border-bottom: 1px solid #000;
            font-size: 10px;
            font-weight: bold;
            page-break-after: avoid;
        }
        .identity { width: 100%; border-collapse: collapse; }
        .identity td { padding: 2px 0; vertical-align: top; }
        .identity .label { width: 125px; }
        .identity .colon { width: 12px; }
        table.report { width: 100%; border-collapse: collapse; }
        table.report thead { display: table-header-group; }
        table.report tr { page-break-inside: avoid; }
        table.report th, table.report td { border: 1px solid #000; padding: 5px 6px; vertical-align: top; }
        table.report th { text-align: center; font-weight: bold; }
        .center { text-align: center; }
        .indicator { width: 62%; }
        .chart-block, .chart-wrap { page-break-inside: avoid; }
        .chart-svg { display: block; width: 100%; height: auto; }
        .approval { width: 100%; margin-top: 18px; page-break-inside: avoid; }
        .approval td { width: 50%; text-align: center; vertical-align: top; }
        .approval-title { margin-top: 14px; font-size: 10px; font-weight: bold; }
        .signature-space { height: 54px; }
        .signature-line { display: inline-block; min-width: 165px; padding-top: 2px; border-top: 1px solid #000; }
        .empty { text-align: center; padding: 12px; }
        .empty-report {
            margin-top: 24px;
            padding: 18px 12px;
            border: 1px solid #000;
            text-align: center;
            font-size: 11px;
            font-weight: normal;
        }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
<?php
    $nama_siswa = $siswa['nama_siswa'] ?? ($siswa['nama'] ?? '-');
    $nis = $siswa['nis'] ?? ($siswa['nisn'] ?? '-');
    $kelas = $ringkasan['kelas'] ?? ($siswa['nama_kelas'] ?? '-');
    $mapel = $ringkasan['mata_pelajaran'] ?? 'Semua Mata Pelajaran';

    $format_nilai = function ($nilai) {
        $nilai = (float) $nilai;
        return ((float) ((int) $nilai) === $nilai)
            ? (string) ((int) $nilai)
            : number_format($nilai, 2, ',', '.');
    };
?>

<table class="kop-table">
    <tr>
        <td class="kop-logo">
            <img src="<?php echo base_url('assets/aksara_edited.png'); ?>" alt="Logo Aksara">
        </td>
        <td class="kop-title">
            <div class="judul">LAPORAN PERKEMBANGAN BELAJAR SISWA</div>
            <div class="periode">
                Semester <?php echo htmlspecialchars($semester, ENT_QUOTES, 'UTF-8'); ?>
                Tahun Ajaran <?php echo htmlspecialchars($tahun_ajaran, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <div class="tanggal">
                Tanggal Cetak: <?php echo htmlspecialchars($tanggal_cetak, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        </td>
    </tr>
</table>

<div class="kop-line"></div>

<?php if (empty($data_tersedia)): ?>
    <div class="empty-report">
        <?php echo htmlspecialchars($pesan_kosong ?? 'Tidak ada data.', ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php else: ?>

<div class="section">
    <div class="section-title">IDENTITAS SISWA</div>
    <table class="identity">
        <tr><td class="label">Nama Siswa</td><td class="colon">:</td><td><?php echo htmlspecialchars($nama_siswa, ENT_QUOTES, 'UTF-8'); ?></td></tr>
        <tr><td class="label">NIS</td><td class="colon">:</td><td><?php echo htmlspecialchars($nis, ENT_QUOTES, 'UTF-8'); ?></td></tr>
        <tr><td class="label">Kelas</td><td class="colon">:</td><td><?php echo htmlspecialchars($kelas, ENT_QUOTES, 'UTF-8'); ?></td></tr>
        <tr><td class="label">Semester</td><td class="colon">:</td><td><?php echo htmlspecialchars($semester, ENT_QUOTES, 'UTF-8'); ?></td></tr>
        <tr><td class="label">Tahun Ajaran</td><td class="colon">:</td><td><?php echo htmlspecialchars($tahun_ajaran, ENT_QUOTES, 'UTF-8'); ?></td></tr>
        <tr><td class="label">Mata Pelajaran</td><td class="colon">:</td><td><?php echo htmlspecialchars($mapel, ENT_QUOTES, 'UTF-8'); ?></td></tr>
    </table>
</div>

<div class="section">
    <div class="section-title">RINGKASAN PERKEMBANGAN</div>
    <table class="report">
        <thead><tr><th style="width:35px">No</th><th>Komponen</th><th style="width:180px">Hasil</th></tr></thead>
        <tbody>
            <tr><td class="center">1</td><td>Jumlah Sesi Dikerjakan</td><td><?php echo (int) ($ringkasan['jumlah_sesi'] ?? 0); ?> sesi</td></tr>
            <tr><td class="center">2</td><td>Rata-rata Hasil Belajar</td><td><?php echo $format_nilai($ringkasan['rata_rata'] ?? 0); ?>%</td></tr>
            <tr><td class="center">3</td><td>Nilai Tertinggi</td><td><?php echo $format_nilai($ringkasan['nilai_tertinggi'] ?? 0); ?>%</td></tr>
            <tr><td class="center">4</td><td>Nilai Terendah</td><td><?php echo $format_nilai($ringkasan['nilai_terendah'] ?? 0); ?>%</td></tr>
            <tr><td class="center">5</td><td>Status Perkembangan</td><td><?php echo htmlspecialchars($ringkasan['status_perkembangan'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td></tr>
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">PERKEMBANGAN PER MATA PELAJARAN</div>
    <table class="report">
        <thead><tr><th style="width:35px">No</th><th>Mata Pelajaran</th><th style="width:85px">Jumlah Sesi</th><th style="width:80px">Rata-rata</th><th style="width:120px">Capaian</th></tr></thead>
        <tbody>
        <?php if (empty($perkembangan_mapel)): ?>
            <tr><td colspan="5" class="empty">Tidak ada data mata pelajaran.</td></tr>
        <?php else: ?>
            <?php foreach ($perkembangan_mapel as $index => $row): ?>
                <tr>
                    <td class="center"><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($row['nama_mata_pelajaran'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="center"><?php echo (int) ($row['jumlah_sesi'] ?? 0); ?></td>
                    <td class="center"><?php echo $format_nilai($row['rata_rata'] ?? 0); ?>%</td>
                    <td><?php echo htmlspecialchars($row['capaian'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">INDIKATOR CAPAIAN MATA PELAJARAN</div>
    <table class="report indicator">
        <thead><tr><th>Rentang Nilai</th><th>Capaian</th></tr></thead>
        <tbody>
            <tr><td class="center">80% - 100%</td><td>Dikuasai</td></tr>
            <tr><td class="center">60% - 79%</td><td>Cukup</td></tr>
            <tr><td class="center">0% - 59%</td><td>Perlu Ditingkatkan</td></tr>
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">PERKEMBANGAN MATERI</div>
    <table class="report">
        <thead><tr><th style="width:35px">No</th><th style="width:150px">Mata Pelajaran</th><th>Materi</th><th style="width:70px">Hasil</th><th style="width:120px">Capaian</th></tr></thead>
        <tbody>
        <?php if (empty($perkembangan_materi)): ?>
            <tr><td colspan="5" class="empty">Tidak ada data materi.</td></tr>
        <?php else: ?>
            <?php foreach ($perkembangan_materi as $index => $row): ?>
                <tr>
                    <td class="center"><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($row['nama_mata_pelajaran'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_materi'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="center"><?php echo $format_nilai($row['hasil'] ?? 0); ?>%</td>
                    <td><?php echo htmlspecialchars($row['capaian'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">INDIKATOR CAPAIAN MATERI</div>
    <table class="report indicator">
        <thead><tr><th>Rentang Nilai</th><th>Capaian</th></tr></thead>
        <tbody>
            <tr><td class="center">80% - 100%</td><td>Dikuasai</td></tr>
            <tr><td class="center">60% - 79%</td><td>Cukup</td></tr>
            <tr><td class="center">0% - 59%</td><td>Perlu Ditingkatkan</td></tr>
        </tbody>
    </table>
</div>

<div class="section chart-block">
    <div class="section-title">GRAFIK PERKEMBANGAN NILAI</div>

    <?php if (empty($grafik_bulan)): ?>
        <div class="empty">Tidak ada data grafik.</div>
    <?php else: ?>
        <?php
            $width = 720;
            $height = 245;
            $left = 42;
            $right = 15;
            $top = 14;
            $bottom = 42;
            $plot_width = $width - $left - $right;
            $plot_height = $height - $top - $bottom;
            $jumlah = count($grafik_bulan);
            $step_x = $jumlah > 1 ? $plot_width / ($jumlah - 1) : 0;
            $points = [];
        ?>
        <div class="chart-wrap">
            <svg class="chart-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 <?php echo $width; ?> <?php echo $height; ?>" aria-label="Grafik Perkembangan Nilai">
                <rect x="0" y="0" width="<?php echo $width; ?>" height="<?php echo $height; ?>" fill="#fff"></rect>

                <?php for ($nilai_y = 0; $nilai_y <= 100; $nilai_y += 20): ?>
                    <?php $y = $top + $plot_height - (($nilai_y / 100) * $plot_height); ?>
                    <text x="<?php echo $left - 10; ?>" y="<?php echo $y + 3; ?>" font-size="9" text-anchor="end" fill="#000"><?php echo $nilai_y; ?></text>
                <?php endfor; ?>

                <line x1="<?php echo $left; ?>" y1="<?php echo $top; ?>" x2="<?php echo $left; ?>" y2="<?php echo $top + $plot_height; ?>" stroke="#000" stroke-width="1"></line>
                <line x1="<?php echo $left; ?>" y1="<?php echo $top + $plot_height; ?>" x2="<?php echo $width - $right; ?>" y2="<?php echo $top + $plot_height; ?>" stroke="#000" stroke-width="1"></line>

                <?php foreach ($grafik_bulan as $index => $item): ?>
                    <?php
                        $x = $jumlah > 1 ? $left + ($index * $step_x) : $left + ($plot_width / 2);
                        $nilai = max(0, min(100, (float) ($item['nilai'] ?? 0)));
                        $y = $top + $plot_height - (($nilai / 100) * $plot_height);
                        $points[] = round($x, 2) . ',' . round($y, 2);
                    ?>
                <?php endforeach; ?>

                <?php if (count($points) > 1): ?>
                    <polyline
                        points="<?php echo implode(' ', $points); ?>"
                        fill="none"
                        stroke="#000"
                        stroke-width="1.4"
                    ></polyline>
                <?php else: ?>
                    <?php
                        $item = $grafik_bulan[0];
                        $nilai = max(0, min(100, (float) ($item['nilai'] ?? 0)));
                        $y = $top + $plot_height - (($nilai / 100) * $plot_height);

                        /*
                         * Garis satu titik dibuat dinamis mengikuti lebar area chart.
                         * Padding menjaga garis agar tidak menyentuh sumbu dan batas kanan.
                         */
                        $padding_garis = max(28, round($plot_width * 0.05));
                        $x_awal = $left + $padding_garis;
                        $x_akhir = ($width - $right) - $padding_garis;
                    ?>
                    <line
                        x1="<?php echo round($x_awal, 2); ?>"
                        y1="<?php echo round($y, 2); ?>"
                        x2="<?php echo round($x_akhir, 2); ?>"
                        y2="<?php echo round($y, 2); ?>"
                        stroke="#000"
                        stroke-width="1.4"
                    ></line>
                <?php endif; ?>

                <?php foreach ($grafik_bulan as $index => $item): ?>
                    <?php
                        $x = $jumlah > 1 ? $left + ($index * $step_x) : $left + ($plot_width / 2);
                        $nilai = max(0, min(100, (float) ($item['nilai'] ?? 0)));
                        $y = $top + $plot_height - (($nilai / 100) * $plot_height);
                        $label = (string) ($item['label'] ?? '-');
                        $bagian_label = explode(' ', $label);
                        $label_bulan = $bagian_label[0] ?? $label;
                    ?>
                    <circle cx="<?php echo round($x, 2); ?>" cy="<?php echo round($y, 2); ?>" r="3.2" fill="#000"></circle>
                    <text x="<?php echo round($x, 2); ?>" y="<?php echo $top + $plot_height + 20; ?>" font-size="8" text-anchor="middle" fill="#000"><?php echo htmlspecialchars($label_bulan, ENT_QUOTES, 'UTF-8'); ?></text>
                <?php endforeach; ?>
            </svg>
        </div>
    <?php endif; ?>
</div>

<div class="approval-title">PENGESAHAN</div>
<table class="approval">
    <tr><td>Tentor Aksara</td><td>Orang Tua / Wali</td></tr>
    <tr><td class="signature-space"></td><td class="signature-space"></td></tr>
    <tr>
        <td><span class="signature-line">( Nama Terang )</span></td>
        <td><span class="signature-line">( Nama Terang )</span></td>
    </tr>
</table>

<?php endif; ?>

<script>
        window.print();
</script>
</body>
</html>