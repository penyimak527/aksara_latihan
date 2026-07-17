<!doctype html>
<html lang="id">

<head>
	<meta charset="utf-8">
	<title>Laporan Pertemuan Tentor</title>
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

		tfoot.totals {
			display: table-header-group;
		}

		thead {
			display: table-row-group;
		}
	</style>
</head>

<body>

	<h1>REKAP TENTOR "AKSARA COURSE" BULAN <?= strtoupper($bulan) ?> <?= $tahun?></h1>

	<table>
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Tentor</th>
				<th>Kelas</th>
				<th>Siswa</th>
				<th>Paket</th>
				<th>Jumlah</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!empty($absen)): ?>
				<?php
				$no = 1;


				foreach ($absen as $namaPegawai => $rows):
					?>
					<?php
					$rowspan = count($rows);
					$first = true;
					?>

					<?php foreach ($rows as $row): ?>
						<tr>
							<?php if ($first): ?>
								<td rowspan="<?= $rowspan ?>" width="5%" style="text-align:center;"><?= $no++ ?></td>
								<td rowspan="<?= $rowspan ?>">
									<?= htmlspecialchars($namaPegawai, ENT_QUOTES, 'UTF-8') ?>
								</td>
								<?php $first = false; ?>
							<?php endif; ?>
							<td><?= $row['nama_kelas'] ?></td>
							<td><?= $row['nama_siswa'] ?></td>
							<td><?= $row['nama_jenjang'] ?></td>
							<td><?= $row['kehadiran'] ?></td>
						</tr>
					<?php endforeach; ?>

				<?php endforeach; ?>


			<?php else: ?>
				<tr>
					<td colspan="6" class="align-c">Tidak ada data.</td>
				</tr>
			<?php endif; ?>
		</tbody>


	</table>
	<br />
	<script>
		window.print();
	</script>
</body>

</html>
