<style>
	/* Layout analisa kelas dibuat mendekati referensi, tanpa mengubah konsep card utama */
	.analysis-panel-body {
		min-height: 360px;
	}

	.ranking-chart {
		padding: 8px 4px 12px;
		border-bottom: 1px dashed #e5e7eb;
		margin-bottom: 12px;
	}

	.ranking-bar-row {
		display: grid;
		grid-template-columns: 120px 1fr 58px;
		gap: 10px;
		align-items: center;
		margin-bottom: 10px;
	}

	.ranking-bar-name {
		font-size: 12px;
		font-weight: 700;
		color: #64748b;
		line-height: 1.2;
		word-break: break-word;
	}

	.ranking-track {
		height: 18px;
		background: #f1f5f9;
		border-radius: 999px;
		overflow: hidden;
	}

	.ranking-fill {
		height: 100%;
		width: var(--w, 0%);
		background: var(--c, #22c55e);
		border-radius: 999px;
		transition: width .2s ease;
	}

	.ranking-bar-score {
		font-size: 12px;
		font-weight: 800;
		color: #334155;
		text-align: right;
	}

	.ranking-list-row {
		display: grid;
		grid-template-columns: 34px 1fr auto auto;
		gap: 10px;
		align-items: center;
		padding: 10px 12px;
		border-radius: 12px;
		background: #f8fbff;
		margin-bottom: 8px;
	}

	.ranking-medal {
		width: 30px;
		height: 30px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		background: #eef6ff;
		font-weight: 900;
		color: #64748b;
	}

	.ranking-list-name {
		font-weight: 800;
		color: #334155;
		line-height: 1.2;
	}

	.ranking-list-score {
		font-weight: 900;
		font-size: 18px;
		color: #0ea5e9;
		white-space: nowrap;
	}

	.ranking-list-count {
		font-size: 12px;
		font-weight: 700;
		color: #94a3b8;
		white-space: nowrap;
	}


	.topic-body-title {
		font-weight: 800;
		color: #334155;
		margin-bottom: 12px;
	}

	.topic-row {
		display: grid;
		grid-template-columns: minmax(140px, 1fr) minmax(120px, 210px) 58px;
		gap: 12px;
		align-items: center;
		padding: 9px 0;
	}

	.topic-name {
		font-weight: 700;
		color: #475569;
		line-height: 1.25;
	}

	.topic-track {
		height: 10px;
		border-radius: 999px;
		background: #f1f5f9;
		overflow: hidden;
	}

	.topic-fill {
		height: 100%;
		width: var(--w, 0%);
		border-radius: 999px;
		background: var(--c, #22c55e);
		transition: width .2s ease;
	}

	.topic-percent {
		font-weight: 900;
		text-align: right;
		color: #475569;
	}


	/* Ringkasan kelas tetap di dalam card utama, isi dibuat lebih rapi seperti bagan kecil */
	.summary-header-soft {
		display: flex;
		flex-wrap: wrap;
		gap: 10px;
		align-items: center;
		justify-content: space-between;
		padding: 12px 14px;
		border: 1px solid #edf2f7;
		border-radius: 14px;
		background: #f8fbff;
		margin-bottom: 14px;
	}

	.summary-header-title {
		font-size: 14px;
		font-weight: 500;
		color: #334155;
		margin: 0;
	}

	.summary-header-subtitle {
		font-size: 13px;
		font-weight: 400;
		color: #64748b;
		margin: 2px 0 0;
	}

	.summary-grid-soft {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(155px, 1fr));
		gap: 12px;
		margin-bottom: 14px;
	}

	.summary-mini-card {
		border: 1px solid #edf2f7;
		border-radius: 14px;
		background: #ffffff;
		padding: 13px 14px;
		min-height: 82px;
	}

	.summary-mini-label {
		font-size: 12px;
		font-weight: 400;
		color: #64748b;
		line-height: 1.25;
		margin-bottom: 7px;
	}

	.summary-mini-value {
		font-size: 20px;
		font-weight: 500;
		color: #1e293b;
		line-height: 1.15;
	}

	.summary-chart-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
		gap: 12px;
	}

	.summary-chart-card {
		border: 1px solid #edf2f7;
		border-radius: 14px;
		padding: 14px;
		background: #ffffff;
	}

	.summary-chart-title {
		font-size: 13px;
		font-weight: 500;
		color: #334155;
		margin-bottom: 12px;
	}

	.summary-progress-row {
		display: grid;
		grid-template-columns: 110px 1fr 52px;
		gap: 10px;
		align-items: center;
		margin-bottom: 10px;
	}

	.summary-progress-label {
		font-size: 12px;
		font-weight: 400;
		color: #64748b;
	}

	.summary-progress-track {
		height: 9px;
		border-radius: 999px;
		background: #f1f5f9;
		overflow: hidden;
	}

	.summary-progress-fill {
		height: 100%;
		width: var(--w, 0%);
		background: var(--c, #0ea5e9);
		border-radius: 999px;
	}

	.summary-progress-value {
		font-size: 12px;
		font-weight: 500;
		color: #475569;
		text-align: right;
	}

	/* Daftar siswa dibuat lebih rapi dan tidak menimpa panel atas */
	.card-daftar-siswa {
		clear: both;
		margin-top: 0;
	}

	.table-siswa-analisa {
		font-size: 13px;
	}

	.table-siswa-analisa th {
		font-weight: 500 !important;
		white-space: nowrap;
		vertical-align: middle;
	}

	.table-siswa-analisa td {
		font-weight: 400 !important;
		vertical-align: middle;
	}

	.table-siswa-analisa .col-no {
		width: 50px;
	}

	.table-siswa-analisa .col-nama {
		min-width: 210px;
	}

	.table-siswa-analisa .col-nilai {
		width: 95px;
	}

	.table-siswa-analisa .col-sesi {
		width: 95px;
	}

	.sesi-tooltip-wrap {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 5px;
		white-space: nowrap;
	}

	.sesi-tooltip-icon {
		width: 18px;
		height: 18px;
		border-radius: 50%;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 11px;
		font-weight: 900;
		line-height: 1;
		cursor: help;
		border: 1px solid transparent;
	}

	.sesi-tooltip-icon.warning {
		background: #fff7ed;
		border-color: #fdba74;
		color: #ea580c;
	}

	.sesi-tooltip-icon.info {
		background: #eff6ff;
		border-color: #93c5fd;
		color: #2563eb;
	}

	.table-siswa-analisa .col-terakhir {
		min-width: 110px;
	}

	.table-siswa-analisa .col-aksi {
		width: 90px;
	}

	@media (max-width: 575.98px) {
		.ranking-bar-row {
			grid-template-columns: 90px 1fr 48px;
		}

		.ranking-list-row {
			grid-template-columns: 30px 1fr auto;
		}

		.ranking-list-count {
			display: none;
		}

		.topic-row {
			grid-template-columns: 1fr;
			gap: 6px;
		}

		.topic-percent {
			text-align: left;
		}

		.summary-progress-row {
			grid-template-columns: 1fr;
			gap: 6px;
		}

		.summary-progress-value {
			text-align: left;
		}
	}

	.tooltip-inner {
		max-width: 260px;
		text-align: left;
		white-space: normal;
		font-size: 11px;
		line-height: 1.35;
		padding: 6px 8px;
	}
</style>

<div class="card mb-3">
	<div class="card-header border-bottom border-dashed">
		<h4 class="header-title mb-0">Analisa Kelas</h4>
	</div>
	<div class="card-body">
		<h5 class="mb-3">Filter Utama</h5>
		<div class="row g-2 align-items-end">
			<div class="col-md-5">
				<label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
				<select id="tahun_ajaran" class="form-control">
					<option value="">Pilih Tahun Ajaran</option>
					<?php foreach (($dropdown['tahun'] ?? []) as $row): ?>
						<option value="<?= $row['tahun_ajaran']; ?>"><?= $row['tahun_ajaran']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-5">
				<label class="form-label">Kelas <span class="text-danger">*</span></label>
				<select id="id_kelas" class="form-control">
					<option value="">Pilih Kelas</option>
					<?php foreach (($dropdown['kelas'] ?? []) as $row): ?>
						<option value="<?= $row['id']; ?>"><?= $row['nama_jenjang'] ?> 	<?= $row['nama_kelas']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-2 text-end">
				<button type="button" class="btn btn-primary w-100" id="btn-tampilkan"><i class="ri-search-line"></i>
					Tampilkan</button>
			</div>
		</div>
		<div class="text-muted small mt-2">Filter utama hanya untuk menentukan tahun ajaran dan kelas. Ringkasan kelas
			tidak mengikuti filter mata pelajaran, jenis pengerjaan, atau kategori.</div>
	</div>
</div>

<div id="alert-awal" class="alert alert-info">Pilih tahun ajaran dan kelas, lalu klik tombol <b>Tampilkan</b> untuk
	melihat analisa kelas.</div>
<div id="area-analisa" style="display:none;">
	<div class="card mb-3">
		<div class="card-header border-bottom border-dashed">
			<h4 class="header-title mb-0">Ringkasan Kelas</h4>
		</div>
		<div class="card-body" id="ringkasan-kelas"></div>
	</div>

	<div class="card mb-3">
		<div class="card-header border-bottom border-dashed">
			<h4 class="header-title mb-0">Filter Analisis</h4>
			<div class="text-muted small mt-1">Filter ini hanya berlaku untuk Peringkat Siswa, Analisis Topik, dan
				Daftar Siswa.</div>
		</div>
		<div class="card-body">
			<div class="row g-2 align-items-end">
				<div class="col-md-4">
					<label class="form-label">Mata Pelajaran</label>
					<select id="id_mata_pelajaran" class="form-control">
						<option value="Semua">Semua Mata Pelajaran</option>
						<?php foreach (($dropdown['mapel'] ?? []) as $row): ?>
							<option value="<?= $row['id']; ?>"><?= $row['nama_mata_pelajaran']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-4">
					<label class="form-label">Jenis Pengerjaan</label>
					<select id="jenis_pengerjaan" class="form-control">
						<option value="Bimbel" selected>Bimbel</option>
						<option value="Rumah">Rumah</option>
						<option value="Semua">Semua</option>
					</select>
				</div>
				<div class="col-md-4">
					<label class="form-label">Kategori Soal</label>
					<select id="id_kategori_soal" class="form-control">
						<option value="Semua">Semua Kategori</option>
						<?php foreach (($dropdown['kategori'] ?? []) as $row): ?>
							<option value="<?= $row['id']; ?>"><?= $row['nama_kategori_soal']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-12 text-end mt-3">
					<button type="button" class="btn btn-outline-primary" id="btn-filter-analisis"><i
							class="ri-filter-3-line"></i> Terapkan Filter Analisis</button>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mb-3 align-items-stretch">
		<div class="col-md-6">
			<div class="card h-100">
				<div class="card-header border-bottom border-dashed">
					<h4 class="header-title mb-0"><i class="ri-trophy-line text-warning me-1"></i> Peringkat Siswa</h4>
				</div>
				<div class="card-body analysis-panel-body" id="peringkat-siswa"></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card h-100">
				<div class="card-header border-bottom border-dashed">
					<h4 class="header-title mb-0"><i class="ri-book-open-line text-info me-1"></i> Analisis Topik</h4>
					<div class="text-muted small mt-1">Mengikuti Filter Analisis di atas. Tidak ada filter tambahan di
						dalam Analisis Topik.</div>
				</div>
				<div class="card-body analysis-panel-body" id="analisa-materi"></div>
			</div>
		</div>
	</div>

	<div class="card card-daftar-siswa">
		<div
			class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between flex-wrap gap-2">
			<h4 class="header-title mb-0">Daftar Siswa</h4>
			<div style="width:260px;">
				<input type="text" class="form-control form-control-sm" id="search_siswa" placeholder="Cari siswa ...">
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-striped align-middle mb-0 table-siswa-analisa">
					<thead class="table-light">
						<tr>
							<th class="col-no text-center">No</th>
							<th class="col-nama">Nama Siswa</th>
							<th class="col-nilai text-end">Rata-rata</th>
							<th class="col-nilai text-end">Bimbel</th>
							<th class="col-nilai text-end">Rumah</th>
							<th class="col-sesi text-center">Sesi</th>
							<th class="col-terakhir">Terakhir</th>
							<th class="col-aksi text-center">Aksi</th>
						</tr>
					</thead>
					<tbody id="daftar-siswa"></tbody>
				</table>
			</div>

			<div
				class="d-flex flex-column flex-md-row justify-content-between align-items-center align-items-md-center flex-wrap gap-2 mt-2">
				<ul class="pagination pagination-sm pagination-boxed mb-0" id="pagination"></ul>
				<div class="d-flex align-items-center gap-2">
					<label for="dt-length-0" class="mb-0">Tampilkan</label>
					<select class="form-select form-select-sm" id="dt-length-0">
						<option value="10" selected>10</option>
						<option value="25">25</option>
						<option value="50">50</option>
						<option value="100">100</option>
					</select>
					<span>entri</span>
				</div>
			</div>
		</div>
	</div>
</div>
<form id="form-detail-siswa" action="<?= base_url('latihan_soal/detail_siswa'); ?>" method="POST" style="display:none;">
	<input type="hidden" name="id_siswa" id="detail_id_siswa">
	<input type="hidden" name="tahun_ajaran" id="detail_tahun_ajaran">
	<input type="hidden" name="id_kelas" id="detail_id_kelas">
</form>
<script>
	let materiMapelCache = [];

	$(document).ready(function () {
		$('#btn-tampilkan').on('click', function () {
			analisaKelas();
		});

		$('#btn-filter-analisis').on('click', function () {
			analisaKelas();
		});

		$('#search_siswa').on('keyup', function () {
			analisaKelas();
		});


		$(document).on('change', '#dt-length-0', function () {
			applyPagingSiswa();
		});
	});

	function escapeHtml(text) {
		return $('<div/>').text(text ?? '').html();
	}

	function tooltipHtml(text) {
		return escapeHtml(text || 'Semua sesi sudah dikerjakan').replace(/\n/g, '<br>');
	}

	function adaSesiBelumDikerjakan(row) {
		let tooltip = String(row.tooltip_sesi || '').toLowerCase();
		return tooltip.indexOf('belum dikerjakan') !== -1;
	}

	function iconTooltipSesi(row) {
		let belumLengkap = adaSesiBelumDikerjakan(row);
		let iconClass = belumLengkap ? 'warning' : 'info';
		let iconText = belumLengkap ? '!' : 'i';
		let title = tooltipHtml(row.tooltip_sesi || 'Semua sesi sudah dikerjakan');

		return `<span class="sesi-tooltip-icon ${iconClass}"
				data-bs-toggle="tooltip"
				data-bs-html="true"
				data-bs-placement="top"
				title="${title}">${iconText}</span>`;
	}

	function kolomSesi(row) {
		return `<span class="sesi-tooltip-wrap">
			<span class="badge bg-light text-dark border">${escapeHtml(row.sesi || '0/0')}</span>
			${iconTooltipSesi(row)}
		</span>`;
	}

	function initTooltips() {
		if (typeof bootstrap === 'undefined' || typeof bootstrap.Tooltip === 'undefined') {
			return;
		}

		document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
			let old = bootstrap.Tooltip.getInstance(el);
			if (old) {
				old.dispose();
			}
			new bootstrap.Tooltip(el);
		});
	}

	function badgeStatus(status) {
		let color = 'secondary';
		if (status == 'Lengkap') color = 'success';
		else if (status == 'Belum Lengkap') color = 'warning';
		else if (status == 'Belum Mengerjakan') color = 'danger';
		else if (status == 'Sedang Mengerjakan') color = 'info';
		return `<span class="badge bg-${color}">${escapeHtml(status)}</span>`;
	}



	function percentNumber(value) {
		value = String(value ?? '').replace('%', '').replace(',', '.').trim();
		let number = parseFloat(value);
		return isNaN(number) ? 0 : number;
	}

	function percentColor(value) {
		value = parseFloat(value) || 0;
		if (value >= 80) return '#22c55e';
		if (value >= 70) return '#f59e0b';
		if (value >= 50) return '#f97316';
		return '#ef4444';
	}

	function rankingColor(index) {
		let colors = ['#10b981', '#f59e0b', '#ef4444', '#3b82f6', '#8b5cf6', '#06b6d4', '#64748b'];
		return colors[index % colors.length];
	}

	function filterData() {
		return $.param({
			tahun_ajaran: $('#tahun_ajaran').val() || '',
			id_kelas: $('#id_kelas').val() || '',
			id_mata_pelajaran: $('#id_mata_pelajaran').val() || 'Semua',
			jenis_pengerjaan: $('#jenis_pengerjaan').val() || 'Bimbel',
			id_kategori_soal: $('#id_kategori_soal').val() || 'Semua',
			search_siswa: $('#search_siswa').val() || ''
		});
	}

	function analisaKelas() {
		$.ajax({
			url: '<?= base_url('latihan_soal/analisa_kelas/analisa_result'); ?>',
			type: 'POST',
			data: filterData(),
			dataType: 'JSON',
			success: function (res) {
				if (res.result != 'true') {
					$('#area-analisa').hide();
					$('#alert-awal').removeClass('alert-info').addClass('alert-warning').html(res.message || 'Data belum bisa ditampilkan.').show();
					return;
				}

				$('#alert-awal').hide();
				$('#area-analisa').show();
				renderRingkasan(res.ringkasan);
				renderPeringkat(res.peringkat);
				renderMateri(res.materi);
				renderSiswa(res.siswa, res.filter);
			},
			error: function () {
				Swal.fire('Gagal', 'Terjadi kesalahan saat memuat analisa kelas.', 'error');
			}
		});
	}

	function renderRingkasan(r) {
		let jumlahSiswa = parseInt(r.jumlah_siswa || 0);
		let sudah = parseInt(r.sudah_mengerjakan || 0);
		let belum = parseInt(r.belum_mengerjakan || 0);
		let totalPengerjaan = sudah + belum;
		let persenSudah = totalPengerjaan > 0 ? Math.round((sudah / totalPengerjaan) * 100) : 0;
		let persenBelum = totalPengerjaan > 0 ? Math.round((belum / totalPengerjaan) * 100) : 0;

		let bimbel = percentNumber(r.rata_rata_bimbel || 0);
		let rumah = percentNumber(r.rata_rata_rumah || 0);

		$('#ringkasan-kelas').html(`
			<div class="summary-header-soft">
				<div>
					<p class="summary-header-title">${escapeHtml(r.kelas || '-')}</p>
					<p class="summary-header-subtitle">Tahun Ajaran ${escapeHtml(r.tahun_ajaran || '-')}</p>
				</div>
				<div class="text-muted small">Ringkasan hasil pengerjaan siswa</div>
			</div>

			<div class="summary-grid-soft">
				<div class="summary-mini-card">
					<div class="summary-mini-label">Jumlah Siswa</div>
					<div class="summary-mini-value">${jumlahSiswa}</div>
				</div>
				<div class="summary-mini-card">
					<div class="summary-mini-label">Jumlah Sesi</div>
					<div class="summary-mini-value">${escapeHtml(r.jumlah_sesi || 0)}</div>
				</div>
				<div class="summary-mini-card">
					<div class="summary-mini-label">Rata-rata Kelas</div>
					<div class="summary-mini-value">${escapeHtml(r.rata_rata_kelas || '-')}</div>
				</div>
				<div class="summary-mini-card">
					<div class="summary-mini-label">Nilai Tertinggi</div>
					<div class="summary-mini-value">${escapeHtml(r.nilai_tertinggi || '-')}</div>
				</div>
				<div class="summary-mini-card">
					<div class="summary-mini-label">Nilai Terendah</div>
					<div class="summary-mini-value">${escapeHtml(r.nilai_terendah || '-')}</div>
				</div>
			</div>

			<div class="summary-chart-grid">
				<div class="summary-chart-card">
					<div class="summary-chart-title">Progress Pengerjaan</div>
					<div class="summary-progress-row">
						<div class="summary-progress-label">Sudah Mengerjakan</div>
						<div class="summary-progress-track"><div class="summary-progress-fill" style="--w:${persenSudah}%; --c:#22c55e;"></div></div>
						<div class="summary-progress-value">${sudah}</div>
					</div>
					<div class="summary-progress-row mb-0">
						<div class="summary-progress-label">Belum Mengerjakan</div>
						<div class="summary-progress-track"><div class="summary-progress-fill" style="--w:${persenBelum}%; --c:#f97316;"></div></div>
						<div class="summary-progress-value">${belum}</div>
					</div>
				</div>

				<div class="summary-chart-card">
					<div class="summary-chart-title">Perbandingan Rata-rata</div>
					<div class="summary-progress-row">
						<div class="summary-progress-label">Bimbel</div>
						<div class="summary-progress-track"><div class="summary-progress-fill" style="--w:${Math.max(0, Math.min(100, bimbel))}%; --c:#0ea5e9;"></div></div>
						<div class="summary-progress-value">${escapeHtml(r.rata_rata_bimbel || '-')}</div>
					</div>
					<div class="summary-progress-row mb-0">
						<div class="summary-progress-label">Rumah</div>
						<div class="summary-progress-track"><div class="summary-progress-fill" style="--w:${Math.max(0, Math.min(100, rumah))}%; --c:#8b5cf6;"></div></div>
						<div class="summary-progress-value">${escapeHtml(r.rata_rata_rumah || '-')}</div>
					</div>
				</div>
			</div>
		`);
	}

	function renderPeringkat(rows) {
		let html = '';
		if (!rows || rows.length == 0) {
			html = '<div class="text-muted">Belum ada data peringkat.</div>';
			$('#peringkat-siswa').html(html);
			return;
		}

		let chartRows = rows.slice(0, 5);
		html += '<div class="ranking-chart">';
		chartRows.forEach(function (row, i) {
			let nilai = percentNumber(row.rata_format || row.rata || 0);
			let width = Math.max(0, Math.min(100, nilai));
			html += `<div class="ranking-bar-row">
				<div class="ranking-bar-name">${escapeHtml(row.nama_siswa)}</div>
				<div class="ranking-track">
					<div class="ranking-fill" style="--w:${width}%; --c:${rankingColor(i)};"></div>
				</div>
				<div class="ranking-bar-score">${escapeHtml(row.rata_format || '-')}</div>
			</div>`;
		});
		html += '</div>';

		rows.slice(0, 5).forEach(function (row, i) {
			let icon = i == 0 ? '♛' : (i + 1);
			let sesiInfo = `${row.sesi_dikerjakan || 0}/${row.sesi_target || 0} sesi`;
			html += `<div class="ranking-list-row">
				<div class="ranking-medal">${icon}</div>
				<div class="ranking-list-name">${escapeHtml(row.nama_siswa)}</div>
				<div class="ranking-list-score">${escapeHtml(row.rata_format || '-')}</div>
				<div class="ranking-list-count">${escapeHtml(sesiInfo)}</div>
			</div>`;
		});

		$('#peringkat-siswa').html(html);
	}

	function renderMateri(resMateri) {
		let groups = [];

		if (resMateri && Array.isArray(resMateri.mapel)) {
			groups = resMateri.mapel;
		} else if (Array.isArray(resMateri)) {
			groups = [{
				id_mata_pelajaran: 'semua',
				nama_mata_pelajaran: 'Semua Mata Pelajaran',
				materi: resMateri
			}];
		}

		if (!groups || groups.length == 0) {
			$('#analisa-materi').html('<div class="text-muted">Belum ada data analisis topik sesuai filter.</div>');
			return;
		}

		let html = '';
		groups.forEach(function (group) {
			let rows = group.materi || [];
			if (!rows || rows.length == 0) {
				return;
			}

			html += `<div class="topic-body-title mt-2">${escapeHtml(group.nama_mata_pelajaran || 'Mata Pelajaran')}</div>`;
			rows.forEach(function (row) {
				let nilai = percentNumber(row.persen_format || row.persen || 0);
				let width = Math.max(0, Math.min(100, nilai));
				html += `<div class="topic-row">
					<div class="topic-name">${escapeHtml(row.nama_materi || '-')}</div>
					<div class="topic-track">
						<div class="topic-fill" style="--w:${width}%; --c:${percentColor(width)};"></div>
					</div>
					<div class="topic-percent">${escapeHtml(row.persen_format || '-')}</div>
				</div>`;
			});
		});

		if (html === '') {
			html = '<div class="text-muted">Belum ada data topik sesuai filter.</div>';
		}

		$('#analisa-materi').html(html);
	}

	function renderSiswa(rows, filter) {
		let html = '';
		if (!rows || rows.length == 0) {
			html = `<tr><td colspan="8" class="text-center text-muted">Belum ada data siswa.</td></tr>`;
		} else {
			rows.forEach(function (row, i) {
				html += `<tr class="data-siswa-row">
					<td class="text-center">${i + 1}</td>
					<td>${escapeHtml(row.nama_siswa)}</td>
					<td class="text-end">${escapeHtml(row.rata_rata)}</td>
					<td class="text-end">${escapeHtml(row.bimbel)}</td>
					<td class="text-end">${escapeHtml(row.rumah)}</td>
					<td class="text-center">${kolomSesi(row)}</td>
					<td>${escapeHtml(row.terakhir)}</td>
					<td class="text-center">
					<button type="button" class="btn btn-sm btn-outline-primary"
						onclick="detail_siswa('${row.id_siswa}', '${filter.tahun_ajaran}', '${filter.id_kelas}')">
						Detail
					</button>
					</td>
				</tr>`;
			});
		}
		$('#daftar-siswa').html(html);
		applyPagingSiswa();
		initTooltips();
	}

	function applyPagingSiswa() {
		let jumlah_tampil = parseInt($('#dt-length-0').val()) || 10;
		let $rows = $('#daftar-siswa tr.data-siswa-row');

		if ($rows.length == 0) {
			$('#pagination').empty();
			return;
		}

		paging($rows, jumlah_tampil);
	}

	function detail_siswa(id_siswa, tahun_ajaran, id_kelas) {
		$('#detail_id_siswa').val(id_siswa);
		$('#detail_tahun_ajaran').val(tahun_ajaran);
		$('#detail_id_kelas').val(id_kelas);

		$('#form-detail-siswa').trigger('submit');
	}

	function paging($selector, jumlah_tampil = 10) {

		window.tp = new Pagination('#pagination', {
			itemsCount: $selector.length,
			pageSize: parseInt(jumlah_tampil),
			onPageChange: function (paging) {
				let start = paging.pageSize * (paging.currentPage - 1);
				let end = start + paging.pageSize;
				let $rows = $selector;

				$rows.hide();
				for (let i = start; i < end; i++) {
					$rows.eq(i).show();
				}
			}
		});
	}
</script>