<!doctype html>
<html lang="id">

<head>
	<meta charset="utf-8">
	<title>Laporan Administrasi - <?= html_escape($periode ?? '') ?></title>
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

	<h1>LAPORAN ADMINISTRASI</h1>
	<p class="meta">Periode: <strong> <?= html_escape($bulan ?? '-') ?> <?= html_escape($periode ?? '-') ?></strong>
	</p>

	<table>
		<thead>
			<tr>
				<th>No</th>
				<th>Nama</th>
				<th>Kelas</th>
				<th>Daftar Awal</th>
				<th>Daftar Ulang</th>
			</tr>
		</thead>
		<tbody>

			<?php $total_daftar_awal = 0;
			$total_daftar_ulang = 0;
			if (!empty($administrasi)):
				$no = 1;

				foreach ($administrasi as $row): ?>
					<tr>
						<td style="text-align:center;"><?= $no++ ?></td>
						<td><?= $row['nama_siswa'] ?></td>
						<td><?= $row['nama_kelas'] ?> 		<?= $row['nama_jenjang'] ?></td>
						<td>Rp. <?= number_format((int) $row['daftar_awal'], 0, ',', '.') ?>
							<?php if ($row['daftar_awal'] == 0): ?>
							<?php else: ?>
								<br /> Metode Pembayaran : <?= $row['metode_awal'] ?: '-' ?><br />
								Tanggal : <?= $row['tanggal_awal_all'] ?>
							<?php endif; ?>
						</td>
						<td>Rp. <?= number_format((int) $row['daftar_ulang'], 0, ',', '.') ?>
							<?php if ($row['daftar_ulang'] == 0): ?>
							<?php else: ?>
								<br />Metode Pembayaran : <?= $row['metode_ulang'] ?: '-' ?><br />
								Tanggal : <?= $row['tanggal_ulang_all'] ?>
							<?php endif; ?>
						</td>
					</tr>
					<?php
					$total_daftar_awal += $row['daftar_awal'];
					$total_daftar_ulang += $row['daftar_ulang'];
				endforeach; ?>

			<?php else: ?>
				<tr>
					<td colspan="8" class="align-c">Tidak ada data.</td>
				</tr>
			<?php endif; ?>
		</tbody>
		<tfoot class="totals">
			<tr>
				<td colspan="4" style="text-align: right;"><b>Total Daftar
						Awal:</b> Rp. <?= number_format($total_daftar_awal, 0, ',', '.') ?></td>
				<td><b>Total Daftar Ulang:</b> Rp. <?= number_format($total_daftar_ulang, 0, ',', '.') ?></td>
			</tr>
		</tfoot>
	</table>
	<script>
		window.print();
	</script>
</body>

</html>