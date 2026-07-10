backup lama :
<div class="card mb-3">
	<div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
		<h4 class="header-title mb-0">← Detail Siswa</h4>
		<a href="<?= base_url('latihan_soal/analisa_kelas'); ?>" class="btn btn-sm btn-outline-secondary">Kembali</a>
	</div>
	<div class="card-body" id="header-siswa">
		<div class="alert alert-info mb-0">Memuat detail siswa...</div>
	</div>
</div>

<div id="area-detail" style="display:none;">
	<div class="card mb-3">
		<div class="card-header border-bottom border-dashed"><h4 class="header-title mb-0">Ringkasan Nilai Siswa</h4></div>
		<div class="card-body" id="ringkasan-siswa"></div>
	</div>

	<div class="card mb-3">
		<div class="card-header border-bottom border-dashed"><h4 class="header-title mb-0">Riwayat Pengerjaan</h4></div>
		<div class="card-body" id="riwayat-pengerjaan"></div>
	</div>

	<div class="card mb-3">
		<div class="card-header border-bottom border-dashed"><h4 class="header-title mb-0">Detail Sesi yang Dipilih</h4></div>
		<div class="card-body" id="detail-sesi"></div>
	</div>

	<div class="card mb-3">
		<div class="card-header border-bottom border-dashed"><h4 class="header-title mb-0">Analisa Materi Siswa</h4></div>
		<div class="card-body" id="analisa-materi-siswa"></div>
	</div>

	<div class="card mb-3">
		<div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
			<h4 class="header-title mb-0">Preview Jawaban</h4>
			<button type="button" class="btn btn-sm btn-outline-primary" id="btn-preview">Tampilkan Preview Jawaban</button>
		</div>
		<div class="card-body" id="preview-jawaban" style="display:none;"></div>
	</div>
</div>

<script>
	const pageInfo = <?= json_encode($page); ?>;
	let currentDetail = null;

	$(document).ready(function () {
		detailSiswa(pageInfo.id_pengerjaan || 0);

		$('#btn-preview').on('click', function () {
			$('#preview-jawaban').toggle();
			$(this).text($('#preview-jawaban').is(':visible') ? 'Sembunyikan Preview Jawaban' : 'Tampilkan Preview Jawaban');
		});
	});

	function escapeHtml(text) {
		return $('<div/>').text(text ?? '').html();
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
				id_pengerjaan: idPengerjaan || 0
			},
			success: function (res) {
				if (res.result != 'true') {
					$('#header-siswa').html(`<div class="alert alert-warning mb-0">${escapeHtml(res.message || 'Data tidak ditemukan.')}</div>`);
					$('#area-detail').hide();
					return;
				}
				currentDetail = res;
				$('#area-detail').show();
				renderHeader(res.siswa);
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

	function renderHeader(s) {
		$('#header-siswa').html(`
			<p class="mb-1"><b>Nama Siswa</b> : ${escapeHtml(s.nama_siswa)}</p>
			<p class="mb-1"><b>NIS</b> : ${escapeHtml(s.nis)}</p>
			<p class="mb-1"><b>Kelas</b> : ${escapeHtml(s.kelas_filter)}</p>
			<p class="mb-0"><b>Tahun Ajaran</b> : ${escapeHtml(s.tahun_ajaran)}</p>
		`);
	}

	function renderRingkasan(r) {
		let mapel = '';
		if (r.mapel && r.mapel.length > 0) {
			r.mapel.forEach(function (m) {
				mapel += `<p class="mb-1"><b>Rata-rata ${escapeHtml(m.nama_mata_pelajaran)}</b> : ${m.rata_format}</p>`;
			});
		} else {
			mapel = '<p class="text-muted mb-1">Belum ada rata-rata per mata pelajaran.</p>';
		}

		$('#ringkasan-siswa').html(`
			<div class="row">
				<div class="col-md-6">
					<p class="mb-1"><b>Jumlah Sesi Dikerjakan</b> : ${r.jumlah_sesi} sesi</p>
					<p class="mb-1"><b>Rata-rata Keseluruhan</b> : ${r.rata}</p>
					<p class="mb-1"><b>Nilai Tertinggi</b> : ${r.tertinggi}</p>
					<p class="mb-1"><b>Nilai Terendah</b> : ${r.terendah}</p>
					<br>
					<p class="mb-1"><b>Rata-rata Bimbel</b> : ${r.bimbel}</p>
					<p class="mb-1"><b>Rata-rata Rumah</b> : ${r.rumah}</p>
				</div>
				<div class="col-md-6">${mapel}</div>
			</div>
		`);
	}

	function renderRiwayat(rows, aktif) {
		let html = '';
		if (!rows || rows.length == 0) {
			html = '<div class="text-muted">Belum ada riwayat pengerjaan.</div>';
		} else {
			rows.forEach(function (row, i) {
				let activeClass = row.id == aktif ? 'border-primary' : '';
				html += `<div class="card-mapel ${activeClass}">
					<div class="keterangan-mapel">
						<div class="keterangan-mapel-kiri">
							<h5 class="judul-mapel" style="margin:0; margin-top:8px;">${i + 1}. ${escapeHtml(row.nama_sesi)}</h5>
							<p class="mb-1"><b>Mata Pelajaran</b> : ${escapeHtml(row.nama_mata_pelajaran)}</p>
							<p class="mb-1"><b>Jenis Pengerjaan</b> : ${escapeHtml(row.jenis_pengerjaan)}</p>
							<p class="mb-1"><b>Tanggal</b> : ${row.tanggal}</p>
							<p class="mb-1"><b>Nilai</b> : ${row.nilai_format}</p>
							<p class="mb-2"><b>Status</b> : ${escapeHtml(row.status_pengerjaan)}</p>
						</div>
						<div class="keterangan-mapel-kanan">
							<button type="button" class="btn btn-outline-primary" onclick="detailSiswa('${row.id}')">Lihat Detail</button>
						</div>
					</div>
				</div>`;
			});
		}
		$('#riwayat-pengerjaan').html(html);
	}

	function renderDetailSesi(d) {
		if (!d) {
			$('#detail-sesi').html('<div class="text-muted">Pilih salah satu riwayat untuk melihat detail sesi.</div>');
			return;
		}
		$('#detail-sesi').html(`
			<div class="row">
				<div class="col-md-6">
					<p class="mb-1"><b>Nama Sesi</b> : ${escapeHtml(d.nama_sesi)}</p>
					<p class="mb-1"><b>Mata Pelajaran</b> : ${escapeHtml(d.nama_mata_pelajaran)}</p>
					<p class="mb-1"><b>Jenis Pengerjaan</b> : ${escapeHtml(d.jenis_pengerjaan)}</p>
					<p class="mb-1"><b>Kategori Soal</b> : ${escapeHtml(d.nama_kategori_soal)}</p>
					<br>
					<p class="mb-1"><b>Nilai</b> : ${d.nilai_format}</p>
					<p class="mb-1"><b>Benar</b> : ${d.jumlah_benar}</p>
					<p class="mb-1"><b>Salah</b> : ${d.jumlah_salah}</p>
					<p class="mb-1"><b>Kosong</b> : ${d.jumlah_kosong}</p>
				</div>
				<div class="col-md-6">
					<p class="mb-1"><b>Durasi</b> : ${d.durasi_format}</p>
					<p class="mb-1"><b>Waktu Mulai</b> : ${d.waktu_mulai_format}</p>
					<p class="mb-1"><b>Waktu Selesai</b> : ${d.waktu_selesai_format}</p>
					<p class="mb-1"><b>Status</b> : ${escapeHtml(d.status_pengerjaan)}</p>
				</div>
			</div>
		`);
	}

	function renderMateri(data) {
		function listMateri(rows, withCount) {
			if (!rows || rows.length == 0) return '<div class="text-muted mb-2">Tidak ada data.</div>';
			let html = '';
			rows.forEach(function (row) {
				let count = withCount ? ` : ${row.jumlah_benar}/${row.total_soal} benar - ${row.persen_format}` : ` : ${row.persen_format}`;
				html += `<div class="border-bottom py-1">- ${escapeHtml(row.nama_materi)}${count}</div>`;
			});
			return html;
		}

		$('#analisa-materi-siswa').html(`
			<h5>Kekuatan Materi:</h5>
			${listMateri(data.kekuatan, true)}
			<hr>
			<h5>Kelemahan Materi:</h5>
			${listMateri(data.kelemahan, true)}
			<hr>
			<h5>Semua Materi:</h5>
			${listMateri(data.semua, false)}
		`);
	}

	function renderPreview(rows) {
		let html = '';
		if (!rows || rows.length == 0) {
			html = '<div class="text-muted">Belum ada preview jawaban.</div>';
		} else {
			rows.forEach(function (row) {
				// let gambar = row.gambar_soal ? `<div class="mb-2"><img src="<= base_url(); ?>${row.gambar_soal}" class="img-fluid rounded border" style="max-height:240px;"></div>` : '';
				let gambarUrl = row.gambar_soal || '';

if (gambarUrl !== '' && !/^https?:\/\//i.test(gambarUrl)) {
	gambarUrl = '<?= base_url(); ?>' + gambarUrl;
}

let gambar = gambarUrl ? `<div class="mb-2"><img src="${gambarUrl}" class="img-fluid rounded border" style="max-height:240px;"></div>` : '';
				html += `<div class="card-mapel">
					<h5 class="judul-mapel" style="margin:0; margin-top:8px;">Soal ${row.nomor_soal}</h5>
					<p class="mb-1"><b>Materi:</b><br>${escapeHtml(row.nama_materi)}</p>
					<p class="mb-1"><b>Pertanyaan:</b><br>${escapeHtml(row.pertanyaan)}</p>
					${gambar}
					<p class="mb-1"><b>Jawaban Siswa:</b><br>${escapeHtml(row.jawaban_siswa_text)}</p>
					<p class="mb-1"><b>Kunci Jawaban:</b><br>${escapeHtml(row.jawaban_benar_text)}</p>
					<p class="mb-1"><b>Status:</b><br>${escapeHtml(row.status_jawaban)}</p>
					<p class="mb-1"><b>Nilai:</b><br>${row.nilai_format}</p>
					${row.pembahasan ? `<p class="mb-1"><b>Pembahasan:</b><br>${escapeHtml(row.pembahasan)}</p>` : ''}
				</div>`;
			});
		}
		$('#preview-jawaban').html(html).hide();
		$('#btn-preview').text('Tampilkan Preview Jawaban');
	}
</script>
