<style>
	.detail-siswa-page {
		--ls-blue: #2f6df6;
		--ls-orange: #f59e0b;
		--ls-green: #10b981;
		--ls-purple: #8b5cf6;
		--ls-cyan: #06b6d4;
		--ls-pink: #ec4899;
		--ls-soft-border: #e8eef6;
		--ls-text: #1f2937;
		--ls-muted: #7b8794;
	}

	.detail-topbar {
		background: transparent;
		border: 0;
		box-shadow: none;
		margin-bottom: 12px;
	}

	.detail-topbar .card-body {
		padding: 4px 0 8px 0;
	}

	.back-round {
		width: 34px;
		height: 34px;
		border-radius: 50%;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		border: 0;
		background: rgba(15, 23, 42, .04);
		color: #334155;
		font-size: 18px;
		text-decoration: none;
	}

	.student-name-title {
		font-size: 20px;
		font-weight: 800;
		line-height: 1.2;
		margin: 0;
		color: var(--ls-text);
	}

	.student-subtitle {
		font-size: 13px;
		color: var(--ls-muted);
		margin-top: 3px;
	}

	.summary-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
		gap: 14px;
		align-items: stretch;
	}

	.summary-card {
		border: 0;
		border-radius: 8px;
		padding: 16px 18px;
		min-height: 104px;
		color: #fff;
		display: flex;
		align-items: center;
		gap: 14px;
		box-shadow: 0 8px 20px rgba(15, 23, 42, .10);
		overflow: hidden;
		width: 100%;
	}

	.summary-icon {
		width: 44px;
		height: 44px;
		border-radius: 12px;
		background: rgba(255, 255, 255, .22);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 20px;
		font-weight: 700;
		flex: 0 0 44px;
	}

	.summary-content {
		min-width: 0;
		flex: 1 1 auto;
	}

	.summary-label {
		font-size: 13px;
		line-height: 1.25;
		font-weight: 700;
		opacity: .92;
		white-space: normal;
		overflow: visible;
		text-overflow: initial;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
	}

	.summary-value {
		font-size: 28px;
		font-weight: 900;
		line-height: 1;
		margin-top: 6px;
	}

	.summary-blue { background: linear-gradient(135deg, #2563eb, #3b82f6); }
	.summary-orange { background: linear-gradient(135deg, #f97316, #facc15); }
	.summary-green { background: linear-gradient(135deg, #10b981, #22c55e); }
	.summary-purple { background: linear-gradient(135deg, #7c3aed, #a78bfa); }
	.summary-cyan { background: linear-gradient(135deg, #0891b2, #22d3ee); }
	.summary-pink { background: linear-gradient(135deg, #db2777, #fb7185); }

	.panel-card {
		border: 1px solid var(--ls-soft-border);
		border-radius: 8px;
		box-shadow: 0 6px 18px rgba(15, 23, 42, .04);
	}

	.panel-card .card-body {
		padding: 16px;
	}

	.panel-title {
		font-size: 17px;
		font-weight: 800;
		margin-bottom: 14px;
		color: var(--ls-text);
	}

	.history-head {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 10px;
		flex-wrap: wrap;
		margin-bottom: 14px;
	}

	.history-head .panel-title {
		margin-bottom: 0;
	}

	.history-filter {
		min-width: 150px;
		max-width: 180px;
	}

	.history-filter .form-select {
		font-size: 12px;
		padding-top: 5px;
		padding-bottom: 5px;
	}

	.history-list {
		max-height: 640px;
		overflow-y: auto;
		padding-right: 4px;
	}

	.history-item {
		border: 1px solid transparent;
		background: #f5f7fb;
		border-radius: 12px;
		padding: 12px;
		margin-bottom: 10px;
		cursor: pointer;
		transition: .18s ease;
	}

	.history-item:hover {
		border-color: #38bdf8;
		background: #eff9ff;
	}

	.history-item.active {
		border-color: #38bdf8;
		background: #e8f7ff;
		box-shadow: inset 0 0 0 1px #38bdf8;
	}

	.history-title {
		font-size: 14px;
		font-weight: 800;
		color: var(--ls-text);
		margin: 0 0 4px;
	}

	.history-meta {
		font-size: 12px;
		color: var(--ls-muted);
		line-height: 1.35;
	}

	.history-type {
		display: inline-block;
		font-size: 11px;
		font-weight: 700;
		border-radius: 999px;
		padding: 2px 8px;
		margin: 4px 0;
		background: #fff7ed;
		color: #c2410c;
	}

	.history-score {
		font-size: 14px;
		font-weight: 800;
		color: #10b981;
		white-space: nowrap;
	}

	.session-head {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 12px;
		margin-bottom: 12px;
	}

	.session-title {
		font-size: 17px;
		font-weight: 800;
		margin: 0;
		color: var(--ls-text);
	}

	.session-mapel {
		font-size: 13px;
		color: var(--ls-muted);
		margin-top: 2px;
	}

	.session-score {
		font-size: 28px;
		font-weight: 900;
		line-height: 1;
		color: #10b981;
		text-align: right;
	}

	.session-score small {
		display: block;
		font-size: 12px;
		font-weight: 600;
		color: var(--ls-muted);
		margin-top: 6px;
	}

	.session-info {
		display: flex;
		flex-wrap: wrap;
		gap: 8px;
		margin-bottom: 12px;
	}

	.info-chip {
		font-size: 12px;
		border-radius: 999px;
		padding: 6px 10px;
		background: #f3f6fa;
		color: #475569;
	}

	.topic-box {
		border-radius: 12px;
		padding: 14px;
		margin-bottom: 14px;
	}

	.topic-box.success {
		background: #eafaf3;
	}

	.topic-box.warning {
		background: #fff8e6;
	}

	.topic-box-title {
		font-size: 16px;
		font-weight: 800;
		margin-bottom: 10px;
	}

	.topic-box.success .topic-box-title {
		color: #047857;
	}

	.topic-box.warning .topic-box-title {
		color: #92400e;
	}

	.topic-row {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 10px;
		padding: 6px 0;
		font-size: 13px;
	}

	.topic-name {
		color: #374151;
		font-weight: 600;
	}

	.topic-count {
		font-size: 12px;
		color: #64748b;
		white-space: nowrap;
	}

	.topic-pill {
		font-size: 12px;
		font-weight: 800;
		border-radius: 999px;
		padding: 4px 8px;
		min-width: 52px;
		text-align: center;
		white-space: nowrap;
	}

	.pill-good { background: #d1fae5; color: #059669; }
	.pill-mid { background: #fef3c7; color: #d97706; }
	.pill-low { background: #fee2e2; color: #dc2626; }

	.all-topic-row {
		display: grid;
		grid-template-columns: 1fr 120px 54px;
		align-items: center;
		gap: 12px;
		padding: 7px 0;
		font-size: 13px;
	}

	.all-topic-name {
		font-weight: 600;
		color: #374151;
	}

	.progress-mini {
		height: 7px;
		border-radius: 99px;
		background: #eef2f7;
		overflow: hidden;
	}

	.progress-mini span {
		display: block;
		height: 100%;
		border-radius: 99px;
		background: #10b981;
	}

	.progress-mini.mid span { background: #f59e0b; }
	.progress-mini.low span { background: #ef4444; }


	.empty-state {
		border: 1px dashed #d7dee8;
		border-radius: 12px;
		padding: 14px;
		color: #7b8794;
		font-size: 13px;
		background: #fafcff;
	}

	@media (max-width: 991px) {
		.history-list {
			max-height: none;
		}
	}

	@media (max-width: 575px) {
		.summary-grid {
			grid-template-columns: 1fr;
			gap: 10px;
		}

		.summary-card {
			min-height: 86px;
			padding: 12px 14px;
		}

		.summary-icon {
			width: 38px;
			height: 38px;
			flex-basis: 38px;
		}

		.summary-value {
			font-size: 24px;
		}

		.session-score {
			font-size: 24px;
		}

		.all-topic-row {
			grid-template-columns: 1fr 70px 46px;
			gap: 8px;
		}
	}
</style>

<div class="detail-siswa-page">
	<div class="card detail-topbar">
		<div class="card-body" id="header-siswa">
			<div class="d-flex align-items-center gap-2">
				<a href="<?= base_url('latihan_soal/analisa_kelas'); ?>" class="back-round" title="Kembali">←</a>
				<div>
					<h4 class="student-name-title">Memuat detail siswa...</h4>
					<div class="student-subtitle">Mohon tunggu sebentar</div>
				</div>
			</div>
		</div>
	</div>

	<div id="area-detail" style="display:none;">
		<div class="card panel-card mb-3">
			<div class="card-body">
				<div class="row g-2 align-items-center">
					<div class="col-md-7">
						<h4 class="panel-title mb-1">Ringkasan Nilai</h4>
						<div class="student-subtitle">Pilih mata pelajaran untuk melihat rata-rata mapel dan riwayat ujian terkait.</div>
					</div>
					<div class="col-md-5">
						<label class="form-label mb-1">Mata Pelajaran</label>
						<select class="form-select" id="filter-mapel-detail">
							<option value="">Memuat mata pelajaran...</option>
						</select>
					</div>
				</div>

				<div class="summary-grid mt-3" id="ringkasan-siswa"></div>
			</div>
		</div>

		<div class="row g-3">
			<div class="col-lg-4">
				<div class="card panel-card">
					<div class="card-body">
						<div class="history-head">
							<h4 class="panel-title">Riwayat Ujian</h4>
							<div class="history-filter">
								<select class="form-select form-select-sm" id="filter-jenis-riwayat">
									<option value="">Semua</option>
									<option value="Bimbel">Bimbel</option>
									<option value="Rumah">Rumah</option>
								</select>
							</div>
						</div>
						<div id="riwayat-pengerjaan" class="history-list"></div>

						<div class="d-flex flex-column flex-md-row justify-content-between align-items-center align-items-md-center flex-wrap gap-2 mt-2">
							<ul class="pagination pagination-sm pagination-boxed mb-0" id="pagination-riwayat"></ul>
							<div class="d-flex align-items-center gap-2">
								<label for="dt-length-riwayat" class="mb-0">Tampilkan</label>
								<select class="form-select form-select-sm" id="dt-length-riwayat">
									<option value="5" selected>5</option>
									<option value="10">10</option>
									<option value="25">25</option>
									<option value="50">50</option>
								</select>
								<span>entri</span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-8">
				<div class="card panel-card mb-3">
					<div class="card-body" id="detail-sesi">
						<div class="empty-state">Pilih salah satu riwayat untuk melihat analisa sesi.</div>
					</div>
				</div>

				<div class="card panel-card mb-3">
					<div class="card-body" id="analisa-materi-siswa">
						<div class="empty-state">Analisa materi belum tersedia.</div>
					</div>
				</div>

				<div class="card panel-card mb-3">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
							<div>
								<h4 class="panel-title mb-1">Preview Jawaban</h4>
								<div class="student-subtitle">Klik tombol untuk melihat detail soal dan jawaban dalam modal.</div>
							</div>
							<button type="button" class="btn btn-sm btn-outline-primary" id="btn-preview">Preview Jawaban</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-preview-jawaban" tabindex="-1" aria-labelledby="modal-preview-jawaban-label" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<div>
					<h5 class="modal-title" id="modal-preview-jawaban-label">Preview Jawaban</h5>
					<div class="student-subtitle mb-0" id="preview-modal-subtitle">Detail soal, jawaban siswa, kunci jawaban, status, dan nilai.</div>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-2" id="preview-jawaban-modal-body">
				<div class="empty-state">Belum ada preview jawaban.</div>
			</div>
			<div class="modal-footer justify-content-between">
	<div class="fw-bold text-muted">Total Nilai</div>
	<div class="fw-bold text-success fs-4" id="preview-total-nilai">-</div>
</div>
			<div class=" modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<script>
	const pageInfo = <?= json_encode($page); ?>;
	let currentDetail = null;
	let selectedMapelId = 0;
	let selectedJenisRiwayat = '';
	let jumlahRiwayatTampil = 5;

	$(document).ready(function () {
		detailSiswa(pageInfo.id_pengerjaan || 0);

		$('#btn-preview').on('click', function () {
			showPreviewModal();
		});

		$(document).on('change', '#filter-mapel-detail', function () {
			selectedMapelId = $(this).val() || 0;
			detailSiswa(0);
		});

		$(document).on('change', '#filter-jenis-riwayat', function () {
			selectedJenisRiwayat = $(this).val() || '';
			detailSiswa(0);
		});

		$(document).on('change', '#dt-length-riwayat', function () {
			jumlahRiwayatTampil = parseInt($(this).val());
			pagingRiwayat($('#riwayat-pengerjaan .history-item'), jumlahRiwayatTampil);
		});
	});

	function escapeHtml(text) {
		return $('<div/>').text(text ?? '').html();
	}

	function percentNumber(value) {
		let n = parseFloat(String(value ?? '0').replace('%', '').replace(',', '.'));
		return isNaN(n) ? 0 : n;
	}

	function colorByPercent(value) {
		let n = percentNumber(value);
		if (n >= 80) return 'good';
		if (n >= 60) return 'mid';
		return 'low';
	}

	function summaryColor(index) {
		const colors = ['summary-blue', 'summary-orange', 'summary-green', 'summary-purple', 'summary-cyan', 'summary-pink'];
		return colors[index % colors.length];
	}

	function shortMapelName(name) {
		name = String(name || '-');
		if (name.toLowerCase().indexOf('bahasa indonesia') !== -1) return 'B. Indonesia';
		if (name.toLowerCase().indexOf('bahasa inggris') !== -1) return 'B. Inggris';
		return name;
	}

	function detailSiswa(idPengerjaan) {
		$.ajax({
			url: '<?= base_url('latihan_soal/detail_siswa/detail_result'); ?>',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_siswa: pageInfo.id_siswa,
				tahun_ajaran: pageInfo.tahun_ajaran,
				id_kelas: pageInfo.id_kelas,
				id_pengerjaan: idPengerjaan || 0,
				id_mata_pelajaran: selectedMapelId || 0,
				jenis_pengerjaan: selectedJenisRiwayat || ''
			},
			success: function (res) {
				if (res.result != 'true') {
					$('#header-siswa').html(`<div class="alert alert-warning mb-0">${escapeHtml(res.message || 'Data tidak ditemukan.')}</div>`);
					$('#area-detail').hide();
					return;
				}

				currentDetail = res;
				selectedMapelId = res.filter && res.filter.id_mata_pelajaran ? res.filter.id_mata_pelajaran : selectedMapelId;

				$('#area-detail').show();
				renderHeader(res.siswa, res.ringkasan);
				renderMapelOptions(res.mapel_options || [], selectedMapelId);
				renderRingkasan(res.ringkasan);
				renderRiwayat(res.riwayat, res.id_pengerjaan_aktif);
				renderDetailSesi(res.detail_sesi);
				renderMateri(res.materi);
				renderPreview(res.preview);
			},
			error: function () {
				Swal.fire('Gagal', 'Terjadi kesalahan saat memuat detail siswa.', 'error');
			}
		});
	}

	function renderHeader(s, r) {
		let jumlah = r && r.jumlah_sesi ? r.jumlah_sesi : 0;
		$('#header-siswa').html(`
			<div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
				<div class="d-flex align-items-center gap-2">
					<a href="<?= base_url('latihan_soal/analisa_kelas'); ?>" class="back-round" title="Kembali"><i
                        class="ti ti-arrow-left"></i></a>
					<div>
						<h4 class="student-name-title">${escapeHtml(s.nama_siswa)}</h4>
						<div class="student-subtitle">${jumlah} hasil ujian · ${escapeHtml(s.kelas_filter || '-')} · ${escapeHtml(s.tahun_ajaran || '-')}</div>
					</div>
				</div>
			</div>
		`);
	}

	function renderMapelOptions(rows, aktif) {
		let html = '';

		if (!rows || rows.length == 0) {
			html = '<option value="">Belum ada mata pelajaran</option>';
			$('#filter-mapel-detail').html(html).prop('disabled', true);
			return;
		}

		rows.forEach(function (row) {
			let selected = String(row.id_mata_pelajaran) == String(aktif) ? 'selected' : '';
			html += `<option value="${escapeHtml(row.id_mata_pelajaran)}" ${selected}>${escapeHtml(row.nama_mata_pelajaran || '-')}</option>`;
		});

		$('#filter-mapel-detail').html(html).prop('disabled', false);
	}

	function renderRingkasan(r) {
		r = r || {};
		let html = '';
		let cards = [
			{
				label: 'Rata-rata Keseluruhan',
				value: r.rata || '-',
				icon: '◎'
			}
		];

		if (r.mapel_aktif) {
			cards.push({
				label: 'Rata-rata ' + shortMapelName(r.mapel_aktif.nama_mata_pelajaran),
				value: r.mapel_aktif.rata_format || '-',
				icon: '◎'
			});
		}

		cards.forEach(function (card, index) {
			html += `
				<div class="summary-card ${summaryColor(index)}">
					<div class="summary-icon">${card.icon}</div>
					<div class="summary-content">
						<div class="summary-label" title="${escapeHtml(card.label)}">${escapeHtml(card.label)}</div>
						<div class="summary-value">${escapeHtml(card.value)}</div>
					</div>
				</div>
			`;
		});

		$('#ringkasan-siswa').html(html);
	}

	function renderRiwayat(rows, aktif) {
		let html = '';

		$('#pagination-riwayat').html('');

		if (!rows || rows.length == 0) {
			html = '<div class="empty-state">Belum ada riwayat pengerjaan.</div>';
			$('#riwayat-pengerjaan').html(html);
			return;
		}

		rows.forEach(function (row) {
			html += cardRiwayat(row, aktif);
		});

		$('#riwayat-pengerjaan').html(html);

		let jumlah_awal = parseInt($('#dt-length-riwayat').val() || jumlahRiwayatTampil);
		pagingRiwayat($('#riwayat-pengerjaan .history-item'), jumlah_awal);
	}

	function cardRiwayat(row, aktif) {
		let activeClass = String(row.id) == String(aktif) ? 'active' : '';

		return `
			<div class="history-item ${activeClass}" onclick="detailSiswa('${row.id}')">
				<div class="d-flex justify-content-between gap-2">
					<div class="min-w-0">
						<h5 class="history-title">${escapeHtml(row.nama_sesi)}</h5>
						<div class="history-meta">${escapeHtml(row.nama_mata_pelajaran || '-')}</div>
						<div class="history-meta">Jenis Pengerjaan: ${escapeHtml(row.jenis_pengerjaan || '-')}</div>
						<div class="history-meta">${escapeHtml(row.tanggal || '-')}</div>
					</div>
					<div class="history-score">${escapeHtml(row.nilai_format || '-')}</div>
				</div>
			</div>
		`;
	}

	function pagingRiwayat($selector, jumlah_tampil = 5) {
		$('#pagination-riwayat').html('');

		if (!$selector || $selector.length == 0) {
			return;
		}

		window.tpRiwayat = new Pagination('#pagination-riwayat', {
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
function statusPengerjaanText(d) {
	if (!d) {
		return '-';
	}

	if (d.status_pengerjaan == 'Proses') {
		return 'Sedang mengerjakan';
	}

	if (parseInt(d.reset_jawaban || 0) > 0) {
		return 'Jawaban pernah dihapus karena keluar halaman';
	}

	if (d.status_pengerjaan == 'Waktu Habis') {
		return 'Selesai karena timer habis';
	}

	if (d.status_pengerjaan == 'Selesai') {
		return 'Selesai';
	}
	return d.status_pengerjaan || '-';
}

	function renderDetailSesi(d) {
		if (!d) {
			$('#detail-sesi').html('<div class="empty-state">Pilih salah satu riwayat untuk melihat detail sesi.</div>');
			return;
		}

		let benar = d.jumlah_benar || 0;
		let total = (parseInt(d.jumlah_benar || 0) + parseInt(d.jumlah_salah || 0) + parseInt(d.jumlah_kosong || 0));
		let benarText = total > 0 ? `${benar}/${total} benar` : `${benar} benar`;
let statusText = statusPengerjaanText(d);
		$('#detail-sesi').html(`
			<div class="session-head">
				<div>
					<h4 class="session-title">${escapeHtml(d.nama_sesi || '-')}</h4>
					<div class="session-mapel">${escapeHtml(d.nama_mata_pelajaran || '-')}</div>
				</div>
				<div class="session-score">
					${escapeHtml(d.nilai_format || '-')}
					<small>${escapeHtml(benarText)}</small>
				</div>
			</div>

			<div class="session-info">
				<span class="info-chip">Durasi: ${escapeHtml(d.durasi_format || '-')}</span>
				<span class="info-chip">Jenis Pengerjaan: ${escapeHtml(d.jenis_pengerjaan || '-')}</span>
				<span class="info-chip">Kategori: ${escapeHtml(d.nama_kategori_soal || '-')}</span>
				<span class="info-chip">Status Pengerjaan: ${escapeHtml(statusText)}</span>
			</div>
		`);
	}

	function topicList(rows, type) {
		if (!rows || rows.length == 0) {
			return '<div class="empty-state">Tidak ada data.</div>';
		}

		let html = '';
		rows.forEach(function (row) {
			let cls = colorByPercent(row.persen_format || row.persen || 0);
			html += `
				<div class="topic-row">
					<div class="topic-name">${escapeHtml(row.nama_materi || '-')}</div>
					<div class="d-flex align-items-center gap-2">
						<span class="topic-count">${escapeHtml(row.jumlah_benar || 0)}/${escapeHtml(row.total_soal || 0)}</span>
						<span class="topic-pill pill-${cls}">${escapeHtml(row.persen_format || '-')}</span>
					</div>
				</div>
			`;
		});
		return html;
	}

	function allTopicList(rows) {
		if (!rows || rows.length == 0) {
			return '<div class="empty-state">Tidak ada data semua topik.</div>';
		}

		let html = '';
		rows.forEach(function (row) {
			let persen = percentNumber(row.persen_format || row.persen || 0);
			let cls = colorByPercent(persen);
			html += `
				<div class="all-topic-row">
					<div class="all-topic-name">${escapeHtml(row.nama_materi || '-')}</div>
					<div class="progress-mini ${cls}"><span style="width:${Math.max(0, Math.min(100, persen))}%"></span></div>
					<div class="text-end fw-bold ${cls == 'good' ? 'text-success' : (cls == 'mid' ? 'text-warning' : 'text-danger')}">${escapeHtml(row.persen_format || '-')}</div>
				</div>
			`;
		});
		return html;
	}

	function renderMateri(data) {
		data = data || { kekuatan: [], kelemahan: [], semua: [] };
		$('#analisa-materi-siswa').html(`
			<div class="topic-box success">
				<div class="topic-box-title">↗ Kekuatan (Topik Dikuasai)</div>
				${topicList(data.kekuatan, 'success')}
			</div>

			<div class="topic-box warning">
				<div class="topic-box-title">↘ Kelemahan (Perlu Ditingkatkan)</div>
				${topicList(data.kelemahan, 'warning')}
			</div>

			<h4 class="panel-title">Semua Topik</h4>
			${allTopicList(data.semua)}
		`);
	}

	function statusJawabanInfo(status) {
		let textStatus = String(status || '-');
		let normalized = textStatus.toLowerCase();

		if (normalized.indexOf('sebagian') !== -1) {
			return {
				text: textStatus,
				className: 'sebagian'
			};
		}

		if (normalized.indexOf('benar') !== -1) {
			return {
				text: textStatus,
				className: 'benar'
			};
		}

		if (normalized.indexOf('salah') !== -1) {
			return {
				text: textStatus,
				className: 'salah'
			};
		}

		return {
			text: textStatus,
			className: 'salah'
		};
	}

	function parseNilaiPreview(value) {
		let text = String(value ?? '0').replace('%', '').trim();

		if (text.indexOf(',') !== -1) {
			text = text.replace(/\./g, '').replace(',', '.');
		} else {
			text = text.replace(/[^0-9.-]/g, '');
		}

		let n = parseFloat(text);
		return isNaN(n) ? 0 : n;
	}

	function formatNilaiPreview(value) {
		let n = parseFloat(value);
		if (isNaN(n)) {
			return '-';
		}

		return n.toLocaleString('id-ID', {
			maximumFractionDigits: 2
		});
	}

	function getTotalNilaiPreview(rows) {
		if (currentDetail && currentDetail.detail_sesi && currentDetail.detail_sesi.nilai_format) {
			return currentDetail.detail_sesi.nilai_format;
		}

		let total = 0;
		(rows || []).forEach(function (row) {
			total += parseNilaiPreview(row.nilai_format || row.nilai || 0);
		});

		return formatNilaiPreview(total);
	}

	function showPreviewModal() {
		if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
			let modal = new bootstrap.Modal(document.getElementById('modal-preview-jawaban'));
			modal.show();
		} else {
			$('#modal-preview-jawaban').modal('show');
		}
	}

	function renderPreview(rows) {
		let html = '';
		let totalNilai = getTotalNilaiPreview(rows);

		if (!rows || rows.length == 0) {
			html = '<div class="empty-state">Belum ada preview jawaban.</div>';
			totalNilai = '-';
			$('#btn-preview').prop('disabled', true);
		} else {
			$('#btn-preview').prop('disabled', false);

			rows.forEach(function (row, index) {
				let gambarUrl = row.gambar_soal || '';
				if (gambarUrl !== '' && !/^https?:\/\//i.test(gambarUrl)) {
					gambarUrl = '<?= base_url(); ?>' + gambarUrl;
				}

let gambar = gambarUrl ? `
	<div class="mb-2">
		<div class="text-muted fw-bold" style="font-size:11px;">Gambar Soal</div>
		<img src="${escapeHtml(gambarUrl)}" class="img-fluid rounded border" style="max-height:160px;">
	</div>
` : '';

let statusInfo = statusJawabanBadge(row.status_jawaban);
let nomor = row.nomor_soal || (index + 1);

html += `
	<div class="border rounded mb-2 bg-white">
		<div class="d-flex align-items-center justify-content-between gap-2 px-2 py-2 border-bottom bg-light">
			<div class="min-w-0">
				<div class="fw-bold small mb-0">Soal ${escapeHtml(nomor)}</div>
				<div class="text-muted" style="font-size:11px;">Materi: ${escapeHtml(row.nama_materi || '-')}</div>
			</div>
			<span class="${statusInfo.className}">${escapeHtml(statusInfo.text)}</span>
		</div>

		<div class="p-2">
			<div class="mb-2">
				<div class="text-muted fw-bold" style="font-size:11px;">Pertanyaan</div>
				<div class="small">${escapeHtml(row.pertanyaan || '-')}</div>
			</div>

			${gambar}

			<div class="table-responsive">
				<table class="table table-sm table-bordered align-middle mb-2" style="font-size:12px;">
					<tbody>
						<tr>
							<th class="bg-light py-1" style="width:120px;">Jawaban Siswa</th>
							<td class="py-1">${escapeHtml(row.jawaban_siswa_text || '-')}</td>
						</tr>
						<tr>
							<th class="bg-light py-1">Kunci Jawaban</th>
							<td class="py-1">${escapeHtml(row.jawaban_benar_text || '-')}</td>
						</tr>
						${row.pembahasan ? `
							<tr>
								<th class="bg-light py-1">Pembahasan</th>
								<td class="py-1">${escapeHtml(row.pembahasan)}</td>
							</tr>
						` : ''}
					</tbody>
				</table>
			</div>

			<div class="d-flex align-items-center justify-content-between gap-2 small">
				<div class="fw-bold text-success">Nilai: ${escapeHtml(row.nilai_format || '-')}</div>
			</div>
		</div>
	</div>
`;
			});
		}

		$('#preview-jawaban-modal-body').html(html);
		$('#preview-total-nilai').text(totalNilai || '-');

		let subtitle = 'Detail soal, jawaban siswa, kunci jawaban, status, dan nilai.';
		if (currentDetail && currentDetail.detail_sesi) {
			subtitle = `${currentDetail.detail_sesi.nama_sesi || '-'} · ${currentDetail.detail_sesi.nama_mata_pelajaran || '-'} · ${rows ? rows.length : 0} soal`;
		}
		$('#preview-modal-subtitle').text(subtitle);
	}
	function statusJawabanBadge(status) {
	let textStatus = String(status || '-');
	let normalized = textStatus.toLowerCase();

	if (normalized.indexOf('sebagian') !== -1) {
		return {
			text: textStatus,
			className: 'badge bg-warning text-dark'
		};
	}

	if (normalized.indexOf('benar') !== -1) {
		return {
			text: textStatus,
			className: 'badge bg-success'
		};
	}

	if (normalized.indexOf('salah') !== -1) {
		return {
			text: textStatus,
			className: 'badge bg-danger'
		};
	}

	return {
		text: textStatus,
		className: 'badge bg-secondary'
	};
}
</script>
