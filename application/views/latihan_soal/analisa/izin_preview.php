<div class="card">
	<div class="card-header border-bottom border-dashed">
		<h4 class="header-title mb-0">Izin Preview Jawaban</h4>
	</div>
	<div class="card-body">
		<div class="row g-2 mb-3">
			<div class="col-md-3">
				<div class="input-group">
					<input type="text" id="search" class="form-control" placeholder="Cari siswa / sesi ...">
					<span class="input-group-text bg-primary text-white" id="inputGroupPrepend"><i
							class="ri-search-line"></i></span>
				</div>
			</div>
			<div class="col-md-2">
				<select id="tahun_ajaran" class="form-control">
					<option value="Semua">Pilih Tahun Ajaran</option>
					<?php foreach (($dropdown['tahun'] ?? []) as $row): ?>
						<option value="<?= $row['tahun_ajaran']; ?>"><?= $row['tahun_ajaran']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-2">
				<select id="id_kelas" class="form-control">
					<option value="Semua">Pilih Kelas</option>
					<?php foreach (($dropdown['kelas'] ?? []) as $row): ?>
						<option value="<?= $row['id']; ?>"><?= $row['nama_kelas']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-3">
				<select id="id_sesi_soal" class="form-control">
					<option value="Semua">Pilih Sesi</option>
					<?php foreach (($dropdown['sesi'] ?? []) as $row): ?>
						<option value="<?= $row['id']; ?>"><?= $row['nama_sesi']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-2">
				<select id="preview_diizinkan" class="form-control">
					<option value="Semua">Pilih Status Izin</option>
					<option value="0">Belum Diizinkan</option>
					<option value="1">Diizinkan</option>
				</select>
			</div>
		</div>

		<div id="data-izin-preview"></div>
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

<script>
	$(document).ready(function () {
		izinPreview();
		$('#search, #tahun_ajaran, #id_kelas, #id_sesi_soal, #preview_diizinkan').on('keyup change', function () {
			izinPreview();
		});

		$('#dt-length-0').on('change', function () {
			const jumlah = parseInt($(this).val());
			paging($('#data-izin-preview .card-mapel'), jumlah);
		});
	});

	function escapeHtml(text) {
		return $('<div/>').text(text ?? '').html();
	}

	function izinPreview() {
		$.ajax({
			url: '<?= base_url('latihan_soal/izin_preview/izin_preview_result'); ?>',
			type: 'POST',
			dataType: 'JSON',
			data: {
				search: $('#search').val(),
				tahun_ajaran: $('#tahun_ajaran').val(),
				id_kelas: $('#id_kelas').val(),
				id_sesi_soal: $('#id_sesi_soal').val(),
				preview_diizinkan: $('#preview_diizinkan').val()
			},
			success: function (res) {
				if (res.result != 'true') {
					$('#data-izin-preview').html(`<div class="alert alert-warning mb-0">${escapeHtml(res.message || 'Data tidak bisa dimuat.')}</div>`);
					$('#pagination').html('');
					return;
				}
				renderIzin(res.data);
			},
			error: function () {
				Swal.fire('Gagal', 'Terjadi kesalahan saat memuat izin preview.', 'error');
			}
		});
	}
	function renderIzin(rows) {
		let html = '';
		if (!rows || rows.length == 0) {
			html = '<div class="alert alert-info mb-0">Belum ada data pengerjaan siswa.</div>';
		} else {
			rows.forEach(function (row, i) {
				const key = row.row_key || (row.id_sesi_soal + '-' + row.id_siswa);
				let checked0 = row.preview_diizinkan == '1' ? '' : 'selected';
				let checked1 = row.preview_diizinkan == '1' ? 'selected' : '';
				let badge = row.preview_diizinkan == '1'
					? '<span class="badge bg-success">Diizinkan</span>'
					: '<span class="badge bg-warning text-dark">Belum Diizinkan</span>';
				html += `<div class="card-mapel">
					<div class="keterangan-mapel">
						<div class="keterangan-mapel-kiri">
							<h5 class="judul-mapel" style="margin:0; margin-top:8px;">${i + 1}. ${escapeHtml(row.nama_siswa)}</h5>
							<b>Sesi</b> : ${escapeHtml(row.nama_sesi || '-')}<br>
							<b>Status</b> : ${badge}</p>
						</div>
						<div class="keterangan-mapel-kanan" style="min-width:220px;">
							<label class="form-label">Status Izin Preview</label>
							<select class="form-control mb-2" id="preview-${key}">
								<option value="0" ${checked0}>Belum Diizinkan</option>
								<option value="1" ${checked1}>Diizinkan</option>
							</select>
							<button type="button" class="btn btn-primary w-100" onclick="simpanIzin('${row.id_sesi_soal}', '${row.id_siswa}', '${key}')">Simpan</button>
						</div>
					</div>
				</div>`;
			});
		}
		$('#data-izin-preview').html(html);
		let jumlah_awal = parseInt($('#dt-length-0').val());
		paging($('#data-izin-preview .card-mapel'), jumlah_awal);
	}

	function paging($selector, jumlah_tampil = 10) {

		window.tp = new Pagination('#pagination', {
			itemsCount: $selector.length,
			pageSize: parseInt(jumlah_tampil),
			onPageChange: function (paging) {
				let start = paging.pageSize * (paging.currentPage - 1);
				let end = start + paging.pageSize;
				let $rows = $selector;

				$rows.hide(); for (let i = start; i < end; i++) {
					$rows.eq(i).show();
				}
			}
		});
	}
	function simpanIzin(idSesi, idSiswa, key) {
		$.ajax({
			url: '<?= base_url('latihan_soal/izin_preview/simpan'); ?>',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_sesi_soal: idSesi,
				id_siswa: idSiswa,
				preview_diizinkan: $(`#preview-${key}`).val()
			},
			success: function (res) {
				if (res.result == 'true') {
					Swal.fire('Berhasil', res.message || 'Izin preview berhasil disimpan.', 'success');
					izinPreview();
				} else {
					Swal.fire('Gagal', res.message || 'Izin preview gagal disimpan.', 'error');
				}
			},
			error: function () {
				Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan izin preview.', 'error');
			}
		});
	}

</script>