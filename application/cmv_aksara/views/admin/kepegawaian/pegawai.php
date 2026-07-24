<div class="card">
	<div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
		<h4 class="header-title">Data <?= $title; ?></h4>
		<button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#tambah"><i
				class="ri-add-line"></i>Tambah</button>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-3">
				<div class="mb-3">
					<div class="input-group">
						<input type="text" class="form-control" id="cari" placeholder="Cari ..."
							aria-describedby="inputGroupPrepend" onkeyup="pegawai()">
						<span class="input-group-text bg-primary text-white" id="inputGroupPrepend"><i
								class="ri-search-line"></i></span>
					</div>
				</div>
			</div>
		</div>
		<div id="data_pegawai"></div>
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
						<label for="simpleinput" class="form-label">Nama Pegawai</label>
						<input type="text" name="nama_pegawai" class="form-control" placeholder="Nama Pegawai ...">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Jabatan</label>
						<select name="id_jabatan" class="form-control">
						</select>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Pendidikan SMA</label>
						<input type="text" name="pendidikan_sma" class="form-control" placeholder="Pendidikan SMA ...">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Pendidikan Kuliah</label>
						<input type="text" name="pendidikan_kuliah" class="form-control" placeholder="Pendidikan Kuliah ...">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Jenis Kelamin</label>
						<select name="jenis_kelamin" class="form-control">
							<option value="Laki - Laki">Laki - Laki</option>
							<option value="Perempuan">Perempuan</option>
						</select>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Tempat Lahir</label>
						<input type="text" name="tempat_lahir" class="form-control" placeholder="Tempat Lahir ...">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Tanggal Lahir</label>
						<input type="text" id="flatpicker" name="tanggal_lahir" class="form-control"
							placeholder="Tanggal Lahir ...">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">No Telepon</label>
						<input type="text" name="no_telepon" class="form-control" placeholder="No Telepon ...">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Alamat</label>
						<textarea type="text" name="alamat" class="form-control" placeholder="Alamat ..."></textarea>
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
					<input type="hidden" id="id_pegawai" name="id_pegawai" class="form-control">
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Nama Pegawai</label>
						<input type="text" id="nama_pegawai" name="nama_pegawai" class="form-control">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Jabatan</label>
						<select id="id_jabatan" name="id_jabatan" class="form-control">
						</select>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Pendidikan SMA</label>
						<input type="text" name="pendidikan_sma" class="form-control" placeholder="Pendidikan SMA ...">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Pendidikan Kuliah</label>
						<input type="text" name="pendidikan_kuliah" class="form-control" placeholder="Pendidikan Kuliah ...">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Jenis Kelamin</label>
						<select id="jenis_kelamin " name="jenis_kelamin" class="form-control">
						</select>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Tempat Lahir</label>
						<input id="tempat_lahir" type="text" name="tempat_lahir" class="form-control">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Tanggal Lahir</label>
						<input id="tanggal_lahir" type="text" id="flatpicker" name="tanggal_lahir" class="form-control">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">No Telepon</label>
						<input id="no_telepon" type="text" name="no_telepon" class="form-control">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Alamat</label>
						<textarea type="text" name="alamat" class="form-control" placeholder="Alamat ..."></textarea>
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
		pegawai();
		jabatan();

		$("#btn-simpan").click(function () {
			var form = $("#form-tambah");
			var data = form.serialize();
			$.ajax({
				url: '<?= base_url('admin/kepegawaian/pegawai/tambah'); ?>',
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
						pegawai();
						$("#form-tambah")[0].reset();
					} else if (data.result == 'false') {
						Swal.fire({
							icon: 'danger',
							title: 'Gagal',
							text: 'Data gagal disimpan',
						})
						$("#form-tambah")[0].reset();
					}
				}
			})
		})

		$("#btn-update").click(function () {
			var form = $("#form-edit");
			var data = form.serialize();
			$.ajax({
				url: '<?= base_url('admin/kepegawaian/pegawai/edit'); ?>',
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
						pegawai();
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
			paging($('#data_pegawai .card-mapel'), jumlah);
		});
	})

	function pegawai() {
		var search = $("#cari").val();
		$.ajax({
			url: '<?= base_url('admin/kepegawaian/pegawai/pegawai_result'); ?>',
			type: 'POST',
			data: {
				search
			},
			dataType: 'JSON',
			success: function (data) {
				$('#pegawai').empty();

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
										  <span>Status: <span class="badge bg-${status_aktif == 'Aktif' ? 'success' : 'danger'}">${status_aktif}</span> </span>
									  </p>
									  <div class="keterangan-mapel">
										  <div class="keterangan-mapel-kiri">
											  <h5 class="judul-mapel" style="margin:0; margin-top: 8px;">${no++}. ${item.nama_pegawai}</h5>
							<p style="margin: 0; padding: 0; font-size: 12px; margin-bottom: 8px;">
							<b>Jabatan:</b> ${item.nama_jabatan}
							<br>
							<b>Pendidikan SMA:</b> ${item.pendidikan_sma ?? '-'} , <b>Pendidikan Kuliah:</b> ${item.pendidikan_kuliah ?? '-'} , <b>Jenis Kelamin:</b> ${item.jenis_kelamin} , <b>Tempat Lahir:</b> ${item.tempat_lahir} , <b>Tanggal Lahir:</b> ${item.tanggal_lahir}, <b>No Telepon:</b> ${item.no_telepon} , <b>Alamat:</b> ${item.alamat ?? '-'}
							</p>
										  </div>
										  <div class="keterangan-mapel-kanan">
											  <div class="d-flex justify-content-center gap-2">
												<button type="button" class="btn btn-outline-warning w-50" onclick="edit('${detail}')">
													<i class="ri-edit-line"></i>
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
				$('#data_pegawai').html(table);
				let jumlah_awal = parseInt($('#dt-length-0').val());
				paging($('#data_pegawai .card-mapel'), jumlah_awal);
			}
		});
	}

	function edit(detail) {
		$('#edit').modal('show');
		var item = JSON.parse(atob(detail));
		$('#id_pegawai').val(item.id);
		$('#nama_pegawai').val(item.nama_pegawai);
		$('#form-edit #tempat_lahir').val(item.tempat_lahir);
		$('#form-edit #tanggal_lahir').val(item.tanggal_lahir);
		$('#form-edit #no_telepon').val(item.no_telepon);
		$('#form-edit select[name="jenis_kelamin"]').html(
			`
							<option value="Laki - Laki" ${item.jenis_kelamin == 'Laki - Laki' ? 'selected' : ''}>Laki - Laki</option>
							<option value="Perempuan" ${item.jenis_kelamin == 'Perempuan' ? 'selected' : ''}>Perempuan</option>
			`
		);
		$('#form-edit input[name="pendidikan_sma"]').val(item.pendidikan_sma);
		$('#form-edit input[name="pendidikan_kuliah"]').val(item.pendidikan_kuliah);
		$('#form-edit textarea[name="alamat"]').val(item.alamat);
		jabatan(item.id_jabatan);
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
					url: `<?= base_url(); ?>admin/kepegawaian/pegawai/hapus`,
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
							pegawai();
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

	function jabatan(id_jabatan = null) {
		$.ajax({
			url: '<?= base_url('admin/kepegawaian/jabatan/jabatan_result'); ?>',
			type: 'POST',
			dataType: 'JSON',
			success: function (data) {
				var no = 1;
				var option = '<option value="">Pilih Jabatan</option>';
				if (data.length > 0) {
					data.forEach(function (item) {
						option += `
			  <option value="${item.id}" ${item.id == id_jabatan ? 'selected' : ''}>${item.nama_jabatan}</option>
			`;
					});
				}

				if (id_jabatan == null) {
					$('#form-tambah select[name="id_jabatan"]').html(option);
				} else {
					$('#form-edit select[name="id_jabatan"]').html(option);
				}
			}
		});
	}
</script>