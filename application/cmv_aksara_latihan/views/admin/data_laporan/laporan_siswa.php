<!doctype html>
<html lang="id">

<head>
	<meta charset="utf-8">
	<title>Laporan Siswa - <?= html_escape($periode ?? '') ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
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

	<h1>LAPORAN SISWA</h1>
	<p class="meta">Periode: <strong> <?= html_escape($periode ?? '-') ?></strong>
	</p>

	<table>
		<thead>
			<tr>
				<th>No</th>
				<th>Nama</th>
				<th>NIS</th>
				<th>Kelas</th>
				<th>Jenjang</th>
				<th>Paket</th>
				<th>Alamat</th>
				<th>No Hp Wali</th>
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
						<td><?= $row['nama_siswa'] ?></td>
						<td><?= $row['nis'] ?></td>
						<td><?= $row['nama_kelas'] ?> </td>
						<td><?= $row['nama_jenjang'] ?> </td>
						<td><?= $row['nama_paket'] ?> </td>
						<td><?= $row['alamat'] ?> </td>
						<td><?= $row['hp_wali'] ?> </td>
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