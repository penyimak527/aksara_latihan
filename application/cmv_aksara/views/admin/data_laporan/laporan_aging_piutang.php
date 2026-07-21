<!doctype html>
<html lang="id">

<head>
	<meta charset="utf-8">
	<title>Laporan Aging Piutang - <?= html_escape($periode ?? '') ?></title>
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

	<h1>LAPORAN AGING PIUTANG</h1>

	<table>
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Siswa</th>
				<th>Kelas</th>
				<th>Total Piutang</th>
				<th>0 - 30</th>
				<th>31 - 60</th>
				<th>61 - 90</th>
				<th>90+</th>
			</tr>
		</thead>
		<tbody>
			<?php

			$total_piutang_all = 0;
			$total_0_30 = 0;
			$total_31_60 = 0;
			$total_61_90 = 0;
			$total_90_plus = 0;
			if (!empty($aging)):
				$no = 1;
				foreach ($aging as $row):

					?>
					<tr>
						<td width="5%" style="text-align: center;"><?= $no++ ?></td>
						<td><?= $row['nama_siswa'] ?> </td>
						<td><?= $row['nama_kelas'] ?> 		<?= $row['nama_jenjang'] ?> </td>
						<td><?= number_format((int) $row['total_piutang'], 0, ',', '.') ?> </td>
						<td><?= number_format((int) $row['d_0_30'], 0, ',', '.') ?> </td>
						<td><?= number_format((int) $row['d_31_60'], 0, ',', '.') ?> </td>
						<td><?= number_format((int) $row['d_61_90'], 0, ',', '.') ?> </td>
						<td><?= number_format((int) $row['d_90_plus'], 0, ',', '.') ?> </td>
					</tr>
					<?php
					$total_piutang_all += $row['total_piutang'];
					$total_0_30 += $row['d_0_30'];
					$total_31_60 += $row['d_31_60'];
					$total_61_90 += $row['d_61_90'];
					$total_90_plus += $row['d_90_plus'];
				endforeach; ?>

			<?php else: ?>
				<tr>
					<td colspan="11" class="align-c">Tidak ada data.</td>
				</tr>
			<?php endif; ?>
		</tbody>
		<tfoot class="totals">
			<tr>
				<td colspan="3" style="text-align: right;"><b>Total</b></td>
				<td><b><?= number_format((int) $total_piutang_all, 0, ',', '.') ?> </b></td>
				<td><b><?= number_format((int) $total_0_30, 0, ',', '.') ?> </b></td>
				<td><b><?= number_format((int) $total_31_60, 0, ',', '.') ?> </b></td>
				<td><b><?= number_format((int) $total_61_90, 0, ',', '.') ?> </b></td>
				<td><b><?= number_format((int) $total_90_plus, 0, ',', '.') ?> </b></td>
			</tr>
		</tfoot>
	</table>
	<script>
		window.print();
	</script>
</body>

</html>
