<!doctype html>
<html lang="id">

<head>
	<meta charset="utf-8">
	<title>Laporan Siswa - <?= html_escape($periode ?? '') ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		@page {
			size: A4 portrait;
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
			text-align: start;
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

		.d-flex {
			display: flex
		}

		.flex-column {
			flex-direction: column;
			margin-bottom: 10px;
		}

		.gap-2 {
			gap: 5px;
		}

		.flex-custom-basis {
			flex-basis: 100px;
		}
	</style>
</head>

<body>

	<h1>LAPORAN RIWAYAT KELAS SISWA</h1>

	<div class="d-flex flex-column gap-2">
		<span class="d-flex gap-2">
			<span class="flex-custom-basis">Periode</span>: <strong> <?= html_escape($periode ?? '-') ?></strong>
		</span>
		<span class="d-flex gap-2">
			<span class="flex-custom-basis">Nama Siswa</span>: <strong>
				<?= html_escape($siswa[0]['nama_siswa'] ?? '-') ?></strong>
		</span>
		<span class="d-flex gap-2">
			<span class="flex-custom-basis">NISN</span>: <strong>
				<?= html_escape($siswa[0]['nis'] ?? '-') ?></strong>
		</span>
	</div>
	<table>
		<thead>
			<tr>
				<th>No</th>
				<th>Kelas</th>
				<th>Jenjang</th>
				<th>Tanggal Belajar</th>
			</tr>
		</thead>
		<tbody>
			<?php

			if (!empty($siswa)):
				$no = 1;
				foreach ($siswa as $row):

					?>
					<tr>
						<td width="5%" style="text-align: center;"><?= $no++ ?></td>
						<td><?= $row['nama_kelas'] ?> </td>
						<td><?= $row['nama_jenjang'] ?> </td>
						<td><?= $row['tanggal'] ?> </td>
					</tr>
				<?php endforeach; else: ?>
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
