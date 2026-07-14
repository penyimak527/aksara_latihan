<!doctype html>
<html lang="id">

<head>
	<meta charset="utf-8">
	<title>Laporan SPP Bulanan - <?= html_escape($periode ?? '') ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		@page {
			size: A4 landscape;
		}

		:root {
			--red: #C0504D;
			/* header TERBAYAR */
			--pink: #F8CBAD;
			/* header SISA & STATUS */
			--gray: #EFEFEF;
			/* header umum */
			--text: #222;
			--green: #006100;
			--danger: #9C0006;
			--row-alt: #fafafa;
			--yellow: #FFF2CC;
			/* highlight TK */
			--border: #999;
		}

		* {
			box-sizing: border-box
		}

		body {
			font: 14px/1.5 "Segoe UI", Tahoma, Arial, sans-serif;
			color: var(--text);
			margin: 24px
		}

		h1 {
			font-size: 20px;
			margin: 0 0 12px;
			text-align: center
		}

		.meta {
			margin: 0 0 18px;
			text-align: center;
			color: #555
		}

		table {
			width: 100%;
			border-collapse: collapse
		}

		th,
		td {
			padding: 8px 10px;
			border: 1px solid var(--border)
		}

		th {
			font-weight: 700;
			text-align: center
		}

		/* header colors */
		thead th {
			background: var(--gray)
		}

		thead th.col-terbayar {
			background: var(--red);
			color: #fff
		}

		thead th.col-sisa,
		thead th.col-status {
			background: var(--pink)
		}

		thead th.col-kas,
		thead th.col-total-kas {
			background: var(--gray)
		}

		/* body */
		tbody tr:nth-child(even) {
			background: var(--row-alt)
		}

		.align-c {
			text-align: center
		}

		.align-r {
			text-align: right
		}

		.status-lunas {
			color: var(--green);
			font-weight: 600
		}

		.status-belum {
			color: var(--danger);
			font-weight: 600
		}

		/* optional: highlight baris jenjang TK (bisa dimatikan) */
		.hl-tk td {
			background: var(--yellow)
		}

		/* responsive */
		@media (max-width: 920px) {
			body {
				margin: 12px
			}

			th,
			td {
				padding: 6px 8px;
				font-size: 13px
			}
		}

		table tfoot {
			display: table-header-group;
		}

		table thead {
			display: table-row-group;
		}

		table tfoot td {
			font-weight: 700;
			background: #f5f7ff;
		}
	</style>
</head>

<body>

	<h1>LAPORAN SPP BULANAN</h1>
	<p class="meta">Periode: <strong><?= html_escape($bulan ?? '-') ?> <?= html_escape($periode ?? '-') ?></strong>
	</p>

	<table>
		<thead>
			<tr>
				<th style="width:25%">NAMA</th>
				<th style="width:8%">KLS</th>
				<th style="width:8%">KODE</th>
				<th style="width:9%">PERTEMUAN</th>
				<th style="width:12%">HARGA /PERTEMUAN (Rp)</th>
				<th style="width:14%">TOTAL YG HARUS DIBAYAR (Rp)</th>
				<th class="col-terbayar" style="width:10%">TERBAYAR (Rp)</th>
				<th class="col-sisa" style="width:12%">SISA TAGIHAN (Rp)</th>
				<th class="col-status" style="width:10%">STATUS</th>
				<th class="col-kas" style="width:9%">KAS (Rp)</th>
				<th class="col-total-kas" style="width:12%">TOTAL KAS (Rp)</th>
			</tr>
		</thead>
		<tbody>

			<?php
			// helper rupiah
			$rupiah = function ($n) {
				return 'Rp ' . number_format((int) $n, 0, ',', '.');
			};

			$total_akhir = 0;
			$total_harus = 0;
			$total_terbayar = 0;
			$total_sisa = 0;
			$total_kas = 0;
			$total_total_kas = 0;
			$nilai_beasiswa = 0;
			if (!empty($pembayaran)):
				foreach ($pembayaran as $row):

					$nama = $row['nama_siswa'] ?? '';
					$kelas = $row['nama_kelas'] ?? '';
					$kode = $row['nama_jenjang'];

					$pertemuan = (int) ($row['pertemuan'] ?? 0);
					$hargaPert = (int) ($row['harga_pertemuan'] ?? 0);
					$totalHarus = (int) ($row['total_akhir'] ?? 0);
					$nilai_beasiswa = (int) ($row['nilai_beasiswa'] ?? 0);

					$terbayar = 0;
					if ($row['status'] == 'Lunas') {
						$terbayar = $row['total_akhir'];
					}
					
					$sisa = max(0, $totalHarus - $terbayar);

					if ($pertemuan == 0) {
						$status = '-';
					} else {
						$status = ($row['status'] === 'Lunas') ? 'Lunas' : 'Tidak Lunas';
						if ($row['status'] === 'Belum') {
							// $sisa = (int) $row['total_harga_pertemuan'] - $nilai_beasiswa;
							$sisa = (int) $row['total_akhir'];
						} else {
							$sisa = 0;
						}
					}

					$kas = (int) ($row['kas'] ?? 0);
					$totalKas = (int) ($row['total_kas'] ?? 0);

					// highlight TK (opsional)
					$is_tk = stripos($kelas, 'TK') !== false;
					$tr_class = $is_tk ? 'hl-tk' : '';

					$status_class = (strtolower($status) === 'lunas') ? 'status-lunas' : 'status-belum';
					?>
					<tr class="<?= $tr_class ?>">
						<td><?= html_escape($nama) ?></td>
						<td class="align-c"><?= html_escape($kelas) ?></td>
						<td class="align-c"><?= html_escape($kode) ?></td>
						<td class="align-c"><?= $pertemuan ?></td>
						<td class="align-r"><?= $rupiah($hargaPert) ?></td>
						<td class="align-r"><?= $rupiah($totalHarus) ?></td>
						<td class="align-r"><?= $rupiah($terbayar) ?></td>
						<td class="align-r"><?= $rupiah($sisa) ?></td>
						<td class="align-c <?= $status_class ?>"><?= html_escape($status) ?></td>
						<td class="align-r"><?= $rupiah($kas) ?></td>
						<td class="align-r"><?= $rupiah($totalKas) ?></td>
					</tr>
					<?php
					// if ($status == 'Lunas') {
					// 	$sisa = ($row['status'] === 'Belum')
					// 			? (int) $row['total_harga_pertemuan']
					// 			: 0;
					// 		// $sisa = (int) $row['total_harga_pertemuan'];
					// 	}
					$total_akhir += $hargaPert;
					$total_harus += $totalHarus;
					$total_terbayar += $terbayar;
					$total_sisa += $sisa;
					$total_kas += $kas;
					$total_total_kas += $totalKas;
				endforeach; ?>

				<?php
			else: ?>
				<tr>
					<td colspan="11" class="align-c">Tidak ada data.</td>
				</tr>
			<?php endif; ?>
		</tbody>
		<tfoot class="totals">
			<tr>
				<td colspan="5" class="align-r"><?= $rupiah($total_akhir) ?></td>
				<td class="align-r"><?= $rupiah($total_harus) ?></td>
				<td class="align-r"><?= $rupiah($total_terbayar) ?></td>
				<td class="align-r"><?= $rupiah($total_sisa) ?></td>
				<td class="align-c">-</td>
				<td class="align-r"><?= $rupiah($total_kas) ?></td>
				<td class="align-r"><?= $rupiah($total_total_kas) ?></td>
			</tr>
		</tfoot>
	</table>
	<script>
		window.print();
	</script>
</body>

</html>
