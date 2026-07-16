<style>
	.content-presensi {
		display: flex;
		justify-content: space-between;
		column-gap: 12px;
	}

	.content-presensi-item {
		flex: 1;
	}

	.presensi-siswa__content {
		display: grid;
		grid-template-columns: repeat(4, 1fr);
		grid-gap: 10px;
		margin-bottom: 1rem;
	}

	.presensi-siswa__item {
		min-height: 120px;
		font-size: 14px;
		border: 1px solid #bbb;
		border-radius: 5px;
		padding: 1rem 1rem;
		display: flex;
		align-items: center;
		cursor: pointer;
		display: flex;
		justify-content: space-between;
	}

	.presensi-siswa__item-content {
		flex: 10;
		column-gap: 14px;
		display: flex;
	}

	.btn-option {
		display: flex;
		align-items: center;
		flex: 1;
		padding: 4px 10px;
		align-items: center;
	}

	.presensi-siswa__checkbox {
		visibility: hidden;
	}

	.presensi-siswa__item.active.hadir {
		background: #14945A;
		color: white;
		border: 1px solid #14945A;
	}

	.presensi-siswa__item.active.ijin {
		background: #ffff4c;
		color: black;
		border: 1px solid #ffff4c;
	}

	.presensi-siswa__item.active.tidak_hadir {
		background: #dc143c;
		color: white;
		border: 1px solid #dc143c;
	}

	.presensi-siswa__item.active .presensi-siswa__number {
		border: 1px solid white;
	}

	.presensi-siswa__item.active.ijin .presensi-siswa__number {
		border: 1px solid black;
	}

	.presensi-siswa__item.not-active {
		background: #06311E;
		color: #06311E;
		border-color: #06311E;
	}

	.presensi-siswa__item.not-active .presensi-siswa__number {
		border-color: #06311E;
	}

	.presensi-siswa__content-name {
		width: 80%;
	}

	.presensi-siswa__number {
		font-size: 18px;
		border: 1px solid #bbb;
		border-radius: 50%;
		height: 50px;
		width: 50px;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.presensi-siswa__name {
		font-size: 16px;
		font-weight: bold;
	}

	.presensi-siswa__status {
		display: inline-block;
		text-transform: uppercase;
	}

	@media only screen and (max-width: 600px) {
		.presensi-siswa__content {
			display: block;
		}
	}

	.checkbox-besar {
		transform: scale(1.3);
		transform-origin: top left;
		margin-right: 8px;
	}

	.modal-xlg {
		max-width: 1200px;
	}

	:root {
		--primary: #12B5C9;
		/* teal */
		--primary-dark: #0E7C8B;
		/* teal dark */
		--bg: #f6f8fb;
		/* page background */
		--card: #ffffff;
		/* card background */
		--muted: #6b7a8a;
		/* muted text */
		--border: #eef0f4;
		/* soft border */
		--radius: 20px;
	}

	.kv {
		font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial;
		color: #111827;
	}

	.kv label {
		color: var(--muted);
		font-size: .82rem;
		margin-bottom: 4px;
	}

	.kv .value {
		font-weight: 600;
	}
</style>
<div class="card">
	<div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
		<h4 class="header-title">Data <?= $title; ?></h4>
		<!-- <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#tambah"><i
				class="ri-add-line"></i>Tambah</button> -->
		<button type="button" class="btn btn-sm btn-outline-primary" onclick="tampiltambah_modal()"><i
				class="ri-add-line"></i>Tambah</button>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-3">
				<div class="mb-3">
					<div class="input-group">
						<select class="form-control" id="filter_id_kelas"></select>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="mb-3">
					<div class="input-group">
						<span class="input-group-text bg-primary text-white" id="inputGroupPrepend"><i
								class="fas fa-calendar-alt"></i></span>
						<input type="text" class="form-control" id="filter_tanggal" placeholder="Tanggal ..." />
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="mb-3">
					<button type="button" class="btn btn-sm btn-primary" onclick="jurnal_siswa_pengganti()">
						<i class="ri-search-line"></i></button>
				</div>
			</div>
		</div>
		<div id="data_jurnal_siswa_pengganti"></div>
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

<div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xlg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel">Tambah <?= $title; ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-tambah">
					<div class="row">
						<div class="col-md-12">
							<div class="mb-2">
								<label for="simpleinput" class="form-label">Tanggal</label>
								<input type="text" id="flatpicker-jurnal" name="tanggal" class="form-control"
									value="<?php echo date('d-m-Y') ?>" placeholder="Tanggal ...">
							</div>
							<div class="alert alert-primary alert-dismissible fade show" role="alert">
								<button type="button" class="btn-close" data-bs-dismiss="alert"
									aria-label="Close"></button>
								Pilih kelas terlebih dahulu untuk memunculkan data siswa ...
							</div>
							<div class="mb-2">
								<label for="simpleinput" class="form-label">Kelas</label>
								<select name="id_kelas" class="form-control" onchange="pilih_kelas('tambah');">
								</select>
							</div>
							<input type="hidden" name="id_jenjang">
							<input type="hidden" name="nama_jenjang">
							<input type="hidden" name="nama_kelas">
							<div class="div_data_siswa mb-2" style="display:none;">
								<label for="simpleinput" class="form-label">Siswa</label>
								<div class="presensi-siswa__content tambah"></div>
							</div>
						</div>
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

<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xlg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel">Edit <?= $title; ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-edit">
					<input type="hidden" id="id_jurnal_siswa_pengganti" name="id_jurnal_siswa_pengganti"
						class="form-control">
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Tanggal</label>
						<input type="text" name="tanggal" class="form-control">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Nama Pegawai</label>
						<input type="text" name="nama_pegawai" class="form-control" readonly>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Kelas</label>
						<input type="text" name="nama_kelas" class="form-control" readonly>
					</div>
					<input type="hidden" name="id_pegawai">
					<input type="hidden" name="id_jenjang">
					<input type="hidden" name="nama_jenjang">
					<input type="hidden" name="id_kelas">
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Siswa</label>
						<div class="presensi-siswa__content edit"></div>
					</div>
				</form>
			</div>
			<div class=" modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-primary" id="btn-update">Simpan</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xlg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel">Detail <?= $title; ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-detail">
					<input type="hidden" name="id_pegawai">
					<input type="hidden" name="tanggal">
					<input type="hidden" name="id_jenjang">
					<input type="hidden" name="id_kelas">
				</form>
				<div class="row">
					<div class="col-12 col-md-4 kv">
						<label>Tanggal</label>
						<div class="value detail_span_tanggal">01-10-2025</div>
					</div>
					<div class="col-12 col-md-4 kv">
						<label>Tentor</label>
						<div class="value detail_span_pegawai">Owner</div>
					</div>
					<div class="col-12 col-md-4 kv">
						<label>Kelas</label>
						<div class="value detail_span_kelas">SD Kelas 1</div>
					</div>
				</div>
				<br>
				<div class="col-12 col-md-12">
					<div class="table-responsive">
						<table class="table mb-0 detail_tabel_presensi tbody">
							<thead class="table-light">
								<tr>
									<th>#</th>
									<th>Siswa</th>
									<th>Presensi</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th scope="row">1</th>
									<td>Firman Syah</td>
									<td>Hadir</td>
								</tr>
								<tr>
									<th scope="row">2</th>
									<td>Riki Dwi Kurniawan</td>
									<td>Hadir</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class=" modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		jurnal_siswa_pengganti();
		kelas();

		flatpickr("#flatpicker-jurnal", {
			dateFormat: "d-m-Y",
			altInput: true,
			altFormat: "d F Y",
			locale: "id",
			minDate: "today",
			maxDate: "today"
		});

		$("#btn-simpan").click(function () {
			$('#btn-simpan').attr('disabled', 'disabled').html('Loading...');
			var form = $("#form-tambah");
			var data = form.serialize();
			$.ajax({
				url: '<?= base_url('admin/jurnal/jurnal_siswa_pengganti/tambah'); ?>',
				type: 'POST',
				data: data,
				success: function (data) {
					$("#tambah").modal('hide');

					if (data.result == 'true') {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: 'Data berhasil disimpan',
						})
						jurnal_siswa_pengganti();
						$("#form-tambah")[0].reset();
						$('.presensi-siswa__content.tambah').html('');
						$('.div_data_siswa').attr('style', 'display:none')
					} else if (data.result == 'false') {
						Swal.fire({
							icon: 'danger',
							title: 'Gagal',
							text: 'Data gagal disimpan',
						})
						$("#form-tambah")[0].reset();
						$('.presensi-siswa__content.tambah').html('');
						$('.div_data_siswa').attr('style', 'display:none')
					}
					$('#btn-simpan').removeAttr('disabled').html('Simpan');
				}
			})
		})

		$("#btn-update").click(function () {
			$('#btn-update').attr('disabled', 'disabled').html('Loading...');
			var form = $("#form-edit");
			var data = form.serialize();
			$.ajax({
				url: '<?= base_url('admin/jurnal/jurnal_siswa_pengganti/edit'); ?>',
				type: 'POST',
				data: data,
				success: function (data) {
					$("#edit").modal('hide');

					if (data.result == 'true') {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: 'Data berhasil diupdate',
						})
						jurnal_siswa_pengganti();
					} else if (data.result == 'false') {
						Swal.fire({
							icon: 'danger',
							title: 'Gagal',
							text: 'Data gagal diupdate',
						})
					}
					$('#btn-update').removeAttr('disabled').html('Simpan');
				}
			})
		})

		$('#dt-length-0').on('change', function () {
			const jumlah = parseInt($(this).val());
			paging($('#data_jurnal_siswa_pengganti .card-mapel'), jumlah);
		});
	})
	function tampiltambah_modal() {
		$.ajax({
			url: '<?= base_url('admin/jurnal/jurnal_siswa_pengganti/cek_session'); ?>',
			type: 'POST',
			dataType: 'JSON',
			success: function (data) {
				const status_log = data.status;
				if (status_log == '' || status_log == null || status_log == 'logout') {
					window.location.href = '<?= base_url('/') ?>';
				} else {
					$('#tambah').modal('show');
				}
			}
		})
	}
	function jurnal_siswa_pengganti() {
		var id_kelas = $("#filter_id_kelas").val();
		var tanggal = $("#filter_tanggal").val();

		$.ajax({
			url: '<?= base_url('admin/jurnal/jurnal_siswa_pengganti/jurnal_siswa_pengganti_result'); ?>',
			type: 'POST',
			data: {
				id_kelas,
				tanggal
			},
			dataType: 'JSON',
			success: function (data) {
				$('#jurnal_siswa_pengganti').empty();

				var no = 1;
				var table = '';
				if (data.length == 0) {
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
						let detail = btoa(JSON.stringify(item));
						let status_aktif = 'Tidak Aktif'
						if (item.status_aktif == '1') {
							status_aktif = 'Aktif'
						}
						table += `<div class="card-mapel">
										<p class="keterangan-hari">
											<span>Status: ${item.nama_pegawai} </span>
										</p>
									  <div class="keterangan-mapel">
										  <div class="keterangan-mapel-kiri">
											  <h5 class="judul-mapel" style="margin:0; margin-top: 8px;">${no++}. ${item.nama_jenjang}  ${item.nama_kelas}</h5>
												<p style="margin: 0; padding: 0; font-size: 12px; margin-bottom: 8px;"><b>Tanggal:</b> ${item.tanggal} - ${item.waktu}</p>
										  </div>
										  <div class="keterangan-mapel-kanan">
											  <div class="d-flex justify-content-center gap-2">
													 <button type="button" class="btn btn-outline-warning w-50" onclick="edit('${detail}')">
														<i class="ri-edit-line"></i>
													</button>
													<button type="button" class="btn btn-outline-info w-50" onclick="detail('${detail}')">
														<i class="ri-eye-line"></i>
													</button>
													<button type="button" class="btn btn-outline-danger w-50" onclick="hapus('${item.id}')">
														<i class="ri-delete-bin-line"></i>
													</button>
											  </div>
										  </div>
									  </div>
								  </div>
								  `;
					});
				}
				$('#data_jurnal_siswa_pengganti').html(table);
				let jumlah_awal = parseInt($('#dt-length-0').val());
				paging($('#data_jurnal_siswa_pengganti .card-mapel'), jumlah_awal);
			}
		});
	}

	function detail(detail) {
		$('#detail').modal('show');
		var item = JSON.parse(atob(detail));

		$('#form-detail input[name="tanggal"]').val(item.tanggal);
		$('#form-detail input[name="id_pegawai"]').val(item.id_pegawai);
		$('#form-detail input[name="id_jenjang"]').val(item.id_jenjang);
		$('#form-detail input[name="id_kelas"]').val(item.id_kelas);

		$('.detail_span_tanggal').html(item.tanggal + ' ' + item.waktu)
		$('.detail_span_pegawai').html(item.nama_pegawai)
		$('.detail_span_kelas').html(item.nama_jenjang + ' ' + item.nama_kelas)

		pilih_kelas('detail')
	}

	function edit(detail) {
		$('#edit').modal('show');
		var item = JSON.parse(atob(detail));
		$('#id_jurnal_siswa_pengganti').val(item.id);
		flatpickr("#form-edit input[name='tanggal']", {
			dateFormat: "d-m-Y",
			altInput: true,
			altFormat: "d F Y",
			locale: "id",
			defaultDate: item.tanggal
		});
		$('#form-edit input[name="nama_pegawai"]').val(item.nama_pegawai);
		$('#form-edit input[name="id_kelas"]').val(item.id_kelas);
		$('#form-edit input[name="id_jenjang"]').val(item.id_jenjang);
		$('#form-edit input[name="nama_kelas"]').val(item.nama_kelas);
		$('#form-edit input[name="nama_jenjang"]').val(item.nama_jenjang);

		pilih_kelas('edit');
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

	function hapus(id) {
		Swal.fire({
			title: 'Hapus Data',
			text: 'Anda yakin ingin menghapus data ini?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya',
			cancelButtonText: 'Tidak'
		}).then((result) => {
			if (result.value) {
				$.ajax({
					type: 'POST',
					url: `<?= base_url(); ?>admin/jurnal/jurnal_siswa_pengganti/hapus`,
					data: {
						id: id
					},
					dataType: 'json',
					success: function (data) {
						if (data.result == 'true') {
							Swal.fire({
								icon: 'success',
								title: 'Berhasil',
								text: 'Data berhasil dihapus',
							})
							jurnal_siswa_pengganti();
						} else if (data.result == 'false') {
							Swal.fire({
								icon: 'danger',
								title: 'Gagal',
								text: 'Data gagal dihapus',
							})
						}

					}
				})
			}
		})
	}

	function kelas(id_kelas = null) {
		$.ajax({
			url: '<?= base_url('admin/master/kelas/kelas_result'); ?>',
			type: 'POST',
			dataType: 'JSON',
			success: function (data) {
				var no = 1;
				var option = '<option value="">Pilih Kelas</option>';
				if (data.length > 0) {
					data.forEach(function (item) {
						option += `
					<option value="${item.id}" data-jenjang="${item.id_jenjang}" data-namajenjang="${item.nama_jenjang}" data-namakelas="${item.nama_kelas}" ${item.id == id_kelas ? 'selected' : ''}>${item.nama_jenjang} ${item.nama_kelas}</option>
				  `;
					});
				}

				$('#filter_id_kelas').html(option);

				if (id_kelas == null) {
					$('#form-tambah select[name="id_kelas"]').html(option);
				} else {
					$('#form-edit select[name="id_kelas"]').html(option);
					pilih_kelas('edit')
				}
			}
		});
	}

	function pilih_kelas(status_form) {
		let tanggal = ''
		let id_jenjang = ''
		let nama_jenjang = ''
		let id_kelas = ''
		let nama_kelas = ''

		$('.div_data_siswa').show()

		if (status_form == 'detail' || status_form == 'edit') {
			if (status_form == 'detail') {
				tanggal = $('#form-detail input[name="tanggal"]').val();
				id_pegawai = $('#form-detail input[name="id_pegawai"]').val();
				id_jenjang = $('#form-detail input[name="id_jenjang"]').val();
				id_kelas = $('#form-detail input[name="id_kelas"]').val();
			} else if (status_form == 'edit') {
				tanggal = $('#form-edit input[name="tanggal"]').val();
				id_pegawai = $('#form-edit input[name="id_pegawai"]').val();
				id_jenjang = $('#form-edit input[name="id_jenjang"]').val();
				id_kelas = $('#form-edit input[name="id_kelas"]').val();
			}

			$.ajax({
				url: '<?= base_url('admin/jurnal/jurnal_siswa_pengganti/detail_siswa_result'); ?>',
				data: { tanggal, id_jenjang, id_kelas, id_pegawai },
				method: 'POST',
				dataType: 'json',
				success: function (res) {
					let no = 0
					let row = ''
					if (res.length > 0) {
						let key = 0
						for (const item of res) {

							if (status_form == 'detail') {
								let status_presensi = item.status_presensi
								let class_presensi = ''

								if (status_presensi == 'Tidak Hadir') {
									class_presensi = 'active tidak_hadir';
								} else if (status_presensi == 'Hadir') {
									class_presensi = 'active hadir';
								}

								let checked_siswa = ''
								if (status_presensi == 'Hadir') {
									checked_siswa = 'checked'
								}

								row += `<tr>
													<th scope="row">${++no}</th>
													<td>${item.nama_siswa}</td>
													<td>${item.status_presensi}</td>
												</tr>`;

							} else if (status_form == 'edit') {
								let checked_siswa = ''
								if (item.status_presensi == 'Hadir') {
									checked_siswa = 'checked'
								}
								var presensi = item.status_presensi == 'Tidak Hadir' ? '' : 'active hadir'
								row += `<div class="presensi-siswa__item ${presensi}" id="presensi-siswa-item-${item.id_siswa}" onclick="pilih_siswa(${item.id_siswa})">
													<div class="presensi-siswa__item-content">
													  <span class="presensi-siswa__number">${++no}</span>
													  <div class="presensi-siswa__content-name">
														<span class="presensi-sis wa__name">${item.nama_siswa}</span>
														<div>
														  <span class="presensi-siswa__status" id="status-hadir-siswa-${item.id_siswa}">${item.status_presensi}</span>
														  <input type="checkbox" id="input-status-presensi-siswa-${item.id_siswa}" style="display: inline;" value="Hadir" class="presensi-siswa__checkbox" ${checked_siswa}>
														  <input type="hidden" id="val-presensi-siswa-${item.id_siswa}" name="status_presensi_siswa[]" value="${item.status_presensi}">
														  <input type="hidden" name="id_siswa[]" id="id-siswa-${item.id_siswa}" value="${item.id_siswa}">
														  <input type="hidden" name="nama_siswa[]" value="${item.nama_siswa}">
														  <input type="hidden" name="nis[]" value="${item.nis}">
														</div>
													  </div>
													</div>
											  </div>`;
							}
						}
					} else {
						row = `<div class="alert alert-primary alert-dismissible fade show" role="alert">
					  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					  Data siswa belum ada ...
					</div>`;
					}

					if (status_form == 'detail') {
						$(`.detail_tabel_presensi tbody`).html(row);
					} else if (status_form == 'edit') {
						$(`.presensi-siswa__content.edit`).html(row);
					}
				}
			})
		} else {
			if (status_form == 'tambah') {
				tanggal = $('#form-tambah input[name="tanggal"]').val();
				id_jenjang = $('#form-tambah select[name="id_kelas"]').find(':selected').data('jenjang');
				nama_jenjang = $('#form-tambah select[name="id_kelas"]').find(':selected').data('namajenjang');
				id_kelas = $('#form-tambah select[name="id_kelas"]').val();
				nama_kelas = $('#form-tambah select[name="id_kelas"]').find(':selected').data('namakelas');

				$('#form-tambah input[name="id_jenjang"]').val(id_jenjang);
				$('#form-tambah input[name="nama_jenjang"]').val(nama_jenjang);
				$('#form-tambah input[name="id_kelas"]').val(id_kelas);
				$('#form-tambah input[name="nama_kelas"]').val(nama_kelas);
			}

			$.ajax({
				url: '<?= base_url('admin/jurnal/jurnal_siswa_pengganti/siswa_result'); ?>',
				data: { tanggal, id_jenjang, id_kelas },
				method: 'POST',
				dataType: 'json',
				success: function (res) {
					let no = 0
					let row = ''
					if (res.length > 0) {
						let key = 0
						for (const item of res) {

							if (status_form == 'tambah') {
								row += `<div class="presensi-siswa__item" id="presensi-siswa-item-${item.id_siswa}" onclick="pilih_siswa(${item.id_siswa})">
													<div class="presensi-siswa__item-content">
													  <span class="presensi-siswa__number">${++no}</span>
													  <div class="presensi-siswa__content-name">
														<span class="presensi-sis wa__name">${item.nama_siswa}</span>
														<div>
														  <span class="presensi-siswa__status" id="status-hadir-siswa-${item.id_siswa}">Tidak Hadir</span>
														  <input type="checkbox" id="input-status-presensi-siswa-${item.id_siswa}" style="display: inline;" value="Hadir" class="presensi-siswa__checkbox">
														  <input type="hidden" id="val-presensi-siswa-${item.id_siswa}" name="status_presensi_siswa[]" value="Tidak Hadir">
														  <input type="hidden" name="id_siswa[]" id="id-siswa-${item.id_siswa}" value="${item.id_siswa}">
														  <input type="hidden" name="nama_siswa[]" value="${item.nama_siswa}">
														  <input type="hidden" name="nis[]" value="${item.nis}">
														</div>
													  </div>
													</div>
												 </div>`;
							}
						}
					} else {
						row = `<div class="alert alert-primary alert-dismissible fade show" role="alert">
					  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					  Data siswa belum ada ...
					</div>`;
					}

					if (status_form == 'tambah') {
						$(`.presensi-siswa__content.tambah`).html(row);
					}
				}
			})
		}
	}

	function pilih_siswa(id_siswa) {
		let value_id_siswa = $(`#input-status-presensi-siswa-${id_siswa}`).is(':checked')

		if (!value_id_siswa) {
			$(`#input-status-presensi-siswa-${id_siswa}`).prop('checked', true)
			$(`#presensi-siswa-item-${id_siswa}`).addClass(`active hadir`)
			$(`#status-hadir-siswa-${id_siswa}`).html('Hadir')
			$(`#val-presensi-siswa-${id_siswa}`).val('Hadir')
		} else {
			$(`#input-status-presensi-siswa-${id_siswa}`).prop('checked', false)
			$(`#presensi-siswa-item-${id_siswa}`).removeClass(`active hadir`)
			$(`#status-hadir-siswa-${id_siswa}`).html('Tidak Hadir')
			$(`#val-presensi-siswa-${id_siswa}`).val('Tidak Hadir')
			$(`.btn-simpan`).fadeOut()
		}
	}
</script>