<div class="card">
	<div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
		<h4 class="header-title">Data <?= $title; ?></h4>
		<button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#tambah"><i
				class="ri-add-line"></i>Tambah</button>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-2">
				<div class="mb-3">
					<select id="filter_id_kelas" name="id_kelas" class="form-control">
					</select>
				</div>
			</div>
			<div class="col-md-2">
				<div class="mb-3">
					<select id="filter_id_pegawai" name="id_pegawai" class="form-control">
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="mb-3">
					<button type="button" class="btn btn-sm btn-primary" onclick="kelas_setting()">
						<i class="ri-search-line"></i></button>
				</div>
			</div>
		</div>
		<div id="data_kelas_setting"></div>
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
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel">Tambah <?= $title; ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-tambah">
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Kelas</label>
						<select name="id_kelas" class="form-control">
						</select>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Pegawai</label>
						<select name="id_pegawai" class="form-control">
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

<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel">Edit <?= $title; ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-edit">
					<input type="hidden" id="id_kelas_setting" name="id_kelas_setting" class="form-control">
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Kelas</label>
						<select name="id_kelas" id="id_kelas" class="form-control">
						</select>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Pegawai</label>
						<select name="id_pegawai" id="id_pegawai" class="form-control">
						</select>
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

<script>
	$(document).ready(function () {
		kelas_setting();
		kelas();
		pegawai();

		$("#btn-simpan").click(function () {
			var form = $("#form-tambah");
			var data = form.serialize();
			$.ajax({
				url: '<?= base_url('admin/manajemen_kelas/kelas_setting/tambah'); ?>',
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
						kelas_setting();
						$("#form-tambah")[0].reset();
					} else if (data.result == 'false') {
						Swal.fire({
							icon: 'danger',
							title: 'Gagal',
							text: 'Data gagal disimpan',
						})
						$("#form-tambah")[0].reset();
					} else if (data.result == 'double') {
						Swal.fire({
							icon: 'danger',
							title: 'Gagal',
							text: 'Data dengan format yang sama sudah ada',
						})
					}
				}
			})
		})

		$("#btn-update").click(function () {
			var form = $("#form-edit");
			var data = form.serialize();
			$.ajax({
				url: '<?= base_url('admin/manajemen_kelas/kelas_setting/edit'); ?>',
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
						kelas_setting();
					} else if (data.result == 'false') {
						Swal.fire({
							icon: 'danger',
							title: 'Gagal',
							text: 'Data gagal diupdate',
						})
					}
				}
			})
		})

		$('#dt-length-0').on('change', function () {
			const jumlah = parseInt($(this).val());
			paging($('#data_kelas_setting .card-mapel'), jumlah);
		});
	})

	function kelas_setting() {
		var id_kelas = $("#filter_id_kelas").val();
		var id_pegawai = $("#filter_id_pegawai").val();

		$.ajax({
			url: '<?= base_url('admin/manajemen_kelas/kelas_setting/kelas_setting_result'); ?>',
			type: 'POST',
			data: {id_kelas, id_pegawai},
			dataType: 'JSON',
			success: function (data) {
				$('#kelas_setting').empty();

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
									  <div class="keterangan-mapel">
										  <div class="keterangan-mapel-kiri">
											  <h5 class="judul-mapel" style="margin:0; margin-top: 8px;">${no++}. ${item.nama_jenjang} ${item.nama_kelas} - ${item.nama_pegawai}</h5>
										  </div>
										  <div class="keterangan-mapel-kanan">
											  <div class="d-flex justify-content-center gap-2">
												<button type="button" class="btn btn-outline-warning w-100" onclick="edit('${detail}')">
													<i class="ri-edit-line"></i>
												</button>
												<button type="button" class="btn btn-outline-danger w-100" onclick="hapus('${item.id}')">
													<i class="ri-delete-bin-line"></i>
												</button>
											  </div>
										  </div>
									  </div>
								  </div>
								  `;
					});
				}
				$('#data_kelas_setting').html(table);
				let jumlah_awal = parseInt($('#dt-length-0').val());
				paging($('#data_kelas_setting .card-mapel'), jumlah_awal);
			}
		});
	}

	function edit(detail) {
		$('#edit').modal('show');
		var item = JSON.parse(atob(detail));
		$('#id_kelas_setting').val(item.id);
		kelas(item.id_kelas);
		pegawai(item.id_pegawai);
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
					url: `<?= base_url(); ?>admin/manajemen_kelas/kelas_setting/hapus`,
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
							kelas_setting();
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
						  <option value="${item.id}" ${item.id == id_kelas ? 'selected' : ''}>${item.nama_jenjang} - ${item.nama_kelas}</option>
						`;
					});
				}

				if (id_kelas == null) {
					$('#form-tambah select[name="id_kelas"]').html(option);
				} else {
					$('#form-edit select[name="id_kelas"]').html(option);
				}
				$('#filter_id_kelas').html(option);
			}
		});
	}

	function pegawai(id_pegawai = null) {
		$.ajax({
			url: '<?= base_url('admin/kepegawaian/pegawai/pegawai_result'); ?>',
			type: 'POST',
			dataType: 'JSON',
			success: function (data) {
				var no = 1;
				var option = '<option value="">Pilih Pegawai</option>';
				if (data.length > 0) {
					data.forEach(function (item) {
						option += `
						  <option value="${item.id}" ${item.id == id_pegawai ? 'selected' : ''}>${item.nama_pegawai}</option>
						`;
					});
				}

				if (id_pegawai == null) {
					$('#form-tambah select[name="id_pegawai"]').html(option);
				} else {
					$('#form-edit select[name="id_pegawai"]').html(option);
				}
				$('#filter_id_pegawai').html(option);
			}
		});
	}
</script>
