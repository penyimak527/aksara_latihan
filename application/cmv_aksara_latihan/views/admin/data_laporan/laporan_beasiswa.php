<!doctype html>
<html lang="id">

<head>
	<meta charset="utf-8">
	<title>Laporan Beasiswa - <?= html_escape($periode ?? '') ?></title>
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
	</style>
</head>

<body>

	<h1>LAPORAN BEASISWA</h1>
	<!-- <p class="meta">Periode: <strong> <= html_escape($periode ?? '-') ?></strong> -->
	<p class="meta">Periode: <strong> <?= html_escape($bulan ?? '-') ?> <?= html_escape($periode ?? '-') ?></strong>
	</p>

	<table>
		<thead>
			<tr>
				<th>No</th>
				<th>Nama</th>
				<th>Kelas</th>
				<th>Potongan</th>
				<th>Pertemuan</th>
				<th>Total Potongan</th>
			</tr>
		</thead>
		<tbody>
			<?php

			if (!empty($beasiswa)):
				$no = 1;
				$total_beasiswa = 0;
				foreach ($beasiswa as $row):
					$total_potongan = $row['nilai'];
					if ($row['tipe'] == 'Persen') {
						$potongan = $row['nilai'] * $row['harga_pertemuan'] / 100 ;
						$total_potongan = ($row['nilai'] * $row['pertemuan']) * $row['harga_pertemuan'] / 100 ;
					}

					if ($row['tipe'] == 'Nominal') {
						$potongan = $row['nilai'] ;
						$total_potongan = $row['nilai'] * $row['pertemuan'];
					}

					if ($row['tipe'] == 'Harga Khusus') {
						$potongan = $row['harga_pertemuan'] - $row['nilai'] ;
						$total_potongan = $row['harga_pertemuan'] - ($row['nilai'] * $row['pertemuan']);
					}

					?>
					<tr>
						<td width="5%" style="text-align: center;"><?= $no++ ?></td>
						<td><?= $row['nama_siswa'] ?></td>
						<td><?= $row['nama_kelas'] ?> 		<?= $row['nama_jenjang'] ?> </td>
						<td> Rp. <?= number_format((int) $potongan, 0, ',', '.')?></td>
						<td><?= $row['pertemuan'] ?? 0 ?></td>
						<td> Rp. <?= number_format((int) $total_potongan, 0, ',', '.') ?></td>
					</tr>
					<?php
					$total_beasiswa += $total_potongan;
				endforeach; ?>
				<tr>
					<td colspan="5" style="text-align: right; font-weight: bold;">Total Potongan :
					</td>
					<td>
						Rp. <?= number_format((int) $total_beasiswa, 0, ',', '.') ?>
					</td>
				</tr>
			<?php else: ?>
				<tr>
					<td colspan="11" class="align-c">Tidak ada data.</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
	<script>
		window.print();
	</script>
</body>

</html>
