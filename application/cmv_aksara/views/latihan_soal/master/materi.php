<div class="card">
	<div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
		<div>
			<h4 class="header-title mb-1">Master Materi</h4>
			<small class="text-muted">Materi wajib terhubung ke mata pelajaran dan dipakai untuk analisa kemampuan
				siswa.</small>
		</div>
		<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambah"><i
				class="ri-add-line"></i> Tambah</button>
	</div>
	<div class="card-body">
		<div class="row g-2 mb-3">
			<div class="col-md-5">
				<div class="input-group">
					<input type="text" class="form-control" id="cari" placeholder="Cari materi ..." onkeyup="materi()">
					<span class="input-group-text bg-primary text-white" id="inputGroupPrepend"><i
							class="ri-search-line"></i></span>
				</div>
			</div>
			<div class="col-md-4">
				<select class="form-control" id="filter_mapel" onchange="materi()">
					<option value="Semua">Pilih Mata Pelajaran</option>
					<?php foreach (($mapel ?? []) as $mp): ?>
						<option value="<?= $mp['id']; ?>">
							<?= htmlspecialchars($mp['nama_mata_pelajaran']); ?>	<?= $mp['status_aktif'] == '0' ? ' (Tidak Aktif)' : ''; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-3">
				<select class="form-control" id="filter_status" onchange="materi()">
					<option value="Semua">Pilih Status</option>
					<option value="1">Status: Aktif</option>
					<option value="0">Status: Tidak Aktif</option>
				</select>
			</div>
		</div>

		<div class="d-flex justify-content-between align-items-center mb-2">
			<h5 class="mb-0">Data Materi</h5>
			<small class="text-muted" id="total_data">0 data</small>
		</div>
		<div id="data_materi"></div>
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

<div class="modal fade" id="tambah" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Tambah Materi</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-tambah">
					<div class="mb-2">
						<label class="form-label">Mata Pelajaran</label>
						<select name="id_mata_pelajaran" class="form-control">
							<option value="">Pilih Mata Pelajaran</option>
							<?php foreach (($mapel ?? []) as $mp): ?>
								<?php if ($mp['status_aktif'] == '1'): ?>
									<option value="<?= $mp['id']; ?>"><?= htmlspecialchars($mp['nama_mata_pelajaran']); ?>
									</option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="mb-2">
						<label class="form-label">Nama Materi</label>
						<input type="text" name="nama_materi" class="form-control"
							placeholder="Masukkan nama materi ...">
					</div>
					<div class="mb-2">
						<label class="form-label">Keterangan</label>
						<textarea name="keterangan" class="form-control" rows="3"
							placeholder="Masukkan keterangan ..."></textarea>
					</div>
					<div class="mb-2">
						<label class="form-label">Status</label>
						<select name="status_aktif" class="form-control">
							<option value="1">Aktif</option>
							<option value="0">Tidak Aktif</option>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-primary" id="btn-simpan">Simpan</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="edit" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Edit Materi</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-edit">
					<input type="hidden" name="id_materi" id="id_materi">
					<div class="mb-2">
						<label class="form-label">Mata Pelajaran</label>
						<select name="id_mata_pelajaran" id="id_mata_pelajaran" class="form-control">
							<option value="">Pilih Mata Pelajaran</option>
							<?php foreach (($mapel ?? []) as $mp): ?>
								<option value="<?= $mp['id']; ?>">
									<?= htmlspecialchars($mp['nama_mata_pelajaran']); ?>	<?= $mp['status_aktif'] == '0' ? ' (Tidak Aktif)' : ''; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="mb-2">
						<label class="form-label">Nama Materi</label>
						<input type="text" name="nama_materi" id="nama_materi" class="form-control">
					</div>
					<div class="mb-2">
						<label class="form-label">Keterangan</label>
						<textarea name="keterangan" id="keterangan" class="form-control" rows="3"></textarea>
					</div>
					<div class="mb-2">
						<label class="form-label">Status</label>
						<select name="status_aktif" id="status_aktif" class="form-control">
							<option value="1">Aktif</option>
							<option value="0">Tidak Aktif</option>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-primary" id="btn-update">Update</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		materi();

		$('#btn-simpan').click(function () {
			$.ajax({
				url: '<?= base_url('latihan_soal/master/materi/tambah'); ?>',
				type: 'POST',
				dataType: 'json',
				data: $('#form-tambah').serialize(),
				success: function (data) {
					if (data.result == 'true') {
						$('#tambah').modal('hide');
						Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message || 'Data berhasil disimpan' });
						$('#form-tambah')[0].reset();
						materi();
					} else {
						Swal.fire({ icon: 'warning', title: 'Gagal', text: data.message || 'Data gagal disimpan' });
					}
				}
			});
		});

		$('#btn-update').click(function () {
			$.ajax({
				url: '<?= base_url('latihan_soal/master/materi/edit'); ?>',
				type: 'POST',
				dataType: 'json',
				data: $('#form-edit').serialize(),
				success: function (data) {
					if (data.result == 'true') {
						$('#edit').modal('hide');
						Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message || 'Data berhasil diupdate' });
						materi();
					} else {
						Swal.fire({ icon: 'warning', title: 'Gagal', text: data.message || 'Data gagal diupdate' });
					}
				}
			});
		});

		$('#dt-length-0').on('change', function () {
			const jumlah = parseInt($(this).val());
			paging($('#data_materi .card-mapel'), jumlah);
		});
	});

	function escapeHtml(text) {
		return String(text ?? '').replace(/[&<>'"]/g, function (m) { return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' })[m]; });
	}

	function badgeStatus(status) {
		return String(status) == '1' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
	}

	function materi() {
		var search = $('#cari').val();
		var id_mata_pelajaran = $('#filter_mapel').val();
		var status = $('#filter_status').val();

		$.ajax({
			url: '<?= base_url('latihan_soal/master/materi/materi_result'); ?>',
			type: 'POST',
			dataType: 'json',
			data: {
				search,
				id_mata_pelajaran,
				status
			},
			success: function (res) {
				let data = Array.isArray(res) ? res : (res.data || []);
				let response_status = Array.isArray(res) ? true : res.status;

				$('#total_data').text(data.length + ' data');

				var no = 1;
				var table = '';
				if (!response_status || data.length == 0) {
					table += `
						<div class="card-mapel">
							<div class="keterangan-mapel">
								<div class="keterangan-mapel-kiri">
									<h5 class="judul-mapel" style="margin:0; margin-top: 8px;">Tidak ada data</h5>
								</div>
							</div>
						</div>
					`;
				} else {
					data.forEach(function (item) {
						let detail = btoa(unescape(encodeURIComponent(JSON.stringify(item))));
						let isAktif = String(item.status_aktif) == '1';

						let status_tujuan = isAktif ? '0' : '1';
						let warna_status = isAktif ? 'danger' : 'success';
						let icon_status = isAktif ? 'ri-forbid-line' : 'ri-check-line';

						table += `<div class="card-mapel">
							<div class="keterangan-mapel">
								<div class="keterangan-mapel-kiri">
									<h5 class="judul-mapel">${no++}. ${escapeHtml(item.nama_materi)}</h5>
									<p class="keterangan-jam-mapel" style="color:#343a40; margin-bottom:4px;">Mata Pelajaran: ${escapeHtml(item.nama_mata_pelajaran || '-')}</p>
									<p class="keterangan-jam-mapel" style="color:#343a40; margin-bottom:4px;">Status: ${badgeStatus(item.status_aktif)}</p>
								</div>
								<div class="keterangan-mapel-kanan">
									<div class="d-flex justify-content-center gap-2 ">
										<button type="button" class="btn btn-outline-warning w-50" onclick="edit('${detail}')"><i class="ri-edit-line"></i></button>
										<button type="button"  class="btn btn-outline-${warna_status} w-50"  onclick="ubah_status('${item.id}', '${status_tujuan}')"><i class="${icon_status}"></i></button>
									</div>
								</div>
							</div>
						</div>`;
					});
				}

				$('#data_materi').html(table);
				let jumlah_awal = parseInt($('#dt-length-0').val());
				paging($('#data_materi .card-mapel'), jumlah_awal);
			}
		});
	}


	function edit(detail) {
		let item = JSON.parse(decodeURIComponent(escape(atob(detail))));
		$('#id_materi').val(item.id);
		$('#id_mata_pelajaran').val(item.id_mata_pelajaran);
		$('#nama_materi').val(item.nama_materi);
		$('#keterangan').val(item.keterangan);
		$('#status_aktif').val(String(item.status_aktif));
		$('#edit').modal('show');
	}

	function ubah_status(id, status) {
		Swal.fire({ icon: 'question', title: 'Konfirmasi', text: 'Yakin ingin mengubah status data ini?', showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Batal' }).then(function (result) {
			if (!result.isConfirmed) return;
			$.ajax({
				url: '<?= base_url('latihan_soal/master/materi/ubah_status'); ?>',
				type: 'POST',
				dataType: 'json',
				data: { id: id, status_aktif: status },
				success: function (data) {
					if (data.result == 'true') {
						Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message || 'Status berhasil diubah' });
						materi();
					} else {
						Swal.fire({ icon: 'warning', title: 'Gagal', text: data.message || 'Status gagal diubah' });
					}
				}
			});
		});
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