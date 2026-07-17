<div class="card">
	<div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
		<h4 class="header-title">Data <?= $title; ?></h4>
		<button type="button" class="btn btn-sm btn-outline-primary" onclick="tambah()"><i
				class="ri-add-line"></i>Tambah</button>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-3">
				<div class="mb-3">
					<div class="input-group">
						<input type="text" class="form-control" id="cari" placeholder="Cari ..."
							aria-describedby="inputGroupPrepend" onkeyup="beasiswa()">
						<span class="input-group-text bg-primary text-white" id="inputGroupPrepend"><i
								class="ri-search-line"></i></span>
					</div>
				</div>
			</div>
		</div>
		<div id="data_beasiswa"></div>
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
				<h4 class="modal-title" id="myLargeModalLabel">Tambah Beasiswa </h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-tambah">
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Siswa</label>
						<input type="text" class="form-control siswa_input" name="nama_siswa" placeholder="Siswa ...">
	    			<input type="hidden" name="id_siswa">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Beasiswa</label>
						<select name="id_beasiswa" class="form-control">
						</select>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Berlaku Mulai</label>
						<input type="text" id="flatpicker" name="berlaku_mulai" class="form-control"
							placeholder="Berlaku Mulai ...">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Berlaku Sampai</label>
						<input type="text" id="flatpicker" name="berlaku_sampai" class="form-control"
							placeholder="Berlaku Sampai ...">
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
				<h4 class="modal-title" id="myLargeModalLabel">Edit Beasiswa </h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-edit">
					<input type="hidden" name="id" id="id_siswa_beasiswa">
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Siswa</label>
						<input type="text" class="form-control siswa_input" name="nama_siswa" placeholder="Siswa ...">
	    			<input type="hidden" name="id_siswa">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Beasiswa</label>
						<select name="id_beasiswa" class="form-control">
						</select>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Berlaku Mulai</label>
						<input type="text" id="berlaku_mulai" name="berlaku_mulai" class="form-control"
							placeholder="Berlaku Mulai ...">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Berlaku Sampai</label>
						<input type="text" id="berlaku_sampai" name="berlaku_sampai" class="form-control"
							placeholder="Berlaku Sampai ...">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-primary" id="btn-update">Simpan</button>
			</div>
		</div>
	</div>
</div>
<script>
	let fpMulai;
	let fpSampai;

	$(document).ready(function () {
		beasiswa();
		siswa_result();
		beasiswa_result();

		fpMulai = flatpickr("#form-edit #berlaku_mulai", {
			dateFormat: "d-m-Y",
			altInput: true,
			altFormat: "d F Y",
			locale: "id"
		});

		fpSampai = flatpickr("#form-edit #berlaku_sampai", {
			dateFormat: "d-m-Y",
			altInput: true,
			altFormat: "d F Y",
			locale: "id"
		});

		$("#btn-simpan").click(function () {
			var form = $("#form-tambah");
			var data = form.serialize();
			$.ajax({
				url: '<?= base_url('admin/beasiswa/tambah'); ?>',
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
						beasiswa();
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
				url: '<?= base_url('admin/beasiswa/edit'); ?>',
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
						beasiswa();
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
			paging($('#data_beasiswa .card-mapel'), jumlah);
		});
	})

	function beasiswa() {
		var search = $("#cari").val();
		$.ajax({
			url: '<?= base_url('admin/beasiswa/beasiswa_result'); ?>',
			type: 'POST',
			data: {
				search
			},
			dataType: 'JSON',
			success: function (data) {
				$('#beasiswa').empty();

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
											  <h5 class="judul-mapel" style="margin:0; margin-top: 8px;">${no++}. ${item.nama_siswa}</h5>
												<p style="margin: 0; padding: 0; font-size: 12px; margin-bottom: 8px;">
												<b>Beasiswa:</b> ${item.nama_beasiswa}
												<br>
												<b>Tanggal Berlaku:</b> ${item.berlaku_mulai} - ${item.berlaku_sampai}
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
				$('#data_beasiswa').html(table);
				let jumlah_awal = parseInt($('#dt-length-0').val());
				paging($('#data_beasiswa .card-mapel'), jumlah_awal);
			}
		});
	}

	function tambah() {
		$('#tambah').modal('show');
		fpMulai.clear();
		fpSampai.clear();
	}

	function edit(detail) {
		$('#edit').modal('show');
		$('#myLargeModalLabel').text('Edit Beasiswa');
		var item = JSON.parse(atob(detail));
		$('#id_siswa_beasiswa').val(item.id);

		fpMulai.setDate(item.berlaku_mulai || null, true, "d-m-Y");
		fpSampai.setDate(item.berlaku_sampai || null, true, "d-m-Y");

		siswa_result(item.id_siswa);
		beasiswa_result(item.id_beasiswa);
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
					url: `<?= base_url(); ?>admin/beasiswa/hapus`,
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
							beasiswa();
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

	function siswa_result(id_siswa = null) {
		// $.ajax({
		// 	url: '<?= base_url('admin/siswa/siswa_result'); ?>',
		// 	type: 'POST',
		// 	dataType: 'JSON',
		// 	success: function (data) {
		// 		var no = 1;
		// 		var option = '<option value="">Pilih Siswa</option>';
		// 		if (data.length > 0) {
		// 			data.forEach(function (item) {
		// 				var selected = item.id == id_siswa ? 'selected' : '';
		// 				option += `
		// 				  <option value="${item.id}" ${selected}>${item.nama_siswa}</option>
		// 				`;
		// 			});
		// 		}
		//
		// 		if (id_siswa != null) {
		// 			$('#form-edit select[name="id_siswa"]').html(option);
		// 		} else {
		// 			$('#form-tambah select[name="id_siswa"]').html(option);
		//
		// 		}
		// 	}
		// });

		return $.ajax({
	    url: '<?= base_url('admin/siswa/siswa_result'); ?>',
	    type: "POST",
	    dataType: "json",
	    success: function (data) {
	      let siswa = [];
	      let map_id_siswa = {};

	      if (Array.isArray(data) && data.length > 0) {
	        for (const item of data) {
	          let text = item.nama_siswa.toString()+' | '+item.alamat.toString();
	          siswa.push(text);
	          map_id_siswa[text] = item.id;
	        }
	      }

				if (id_siswa == null) {
					$('#tambah input[name="nama_siswa"]').autocomplete({
						source: [siswa],
						minLength: 0
					}).on('selected.xdsoft', function (e, datum) {
						let text = (datum && datum.value) ? datum.value : $(this).val();
						let id = map_id_siswa[text] || null;

						$('#tambah input[name="id_siswa"]').val(id);
						pilih_siswa(id_paket);
					}).on('focus', function () {
						$(this).autocomplete('search', '');
					});
				}else {
					$('#edit input[name="nama_siswa"]').autocomplete({
						source: [siswa],
						minLength: 0
					}).on('selected.xdsoft', function (e, datum) {
						let text = (datum && datum.value) ? datum.value : $(this).val();
						let id = map_id_siswa[text] || null;

						$('#edit input[name="id_siswa"]').val(id);
						pilih_siswa(id_paket);
					}).on('focus', function () {
						$(this).autocomplete('search', '');
					});

					let ambil_data_siswa = Object.keys(map_id_siswa).find(k => map_id_siswa[k] == id_siswa);
	        if (ambil_data_siswa) {
						$('#edit input[name="nama_siswa"]').val(ambil_data_siswa);
						$('#edit input[name="id_siswa"]').val(id_siswa);
						pilih_siswa(id_paket);
	        }
				}
	    }
	  });
	}

	function beasiswa_result(id_beasiswa = null) {
		$.ajax({
			url: '<?= base_url('admin/master/beasiswa/beasiswa_result'); ?>',
			type: 'POST',
			dataType: 'JSON',
			success: function (data) {
				var no = 1;
				var option = '<option value="">Pilih Beasiswa</option>';
				if (data.length > 0) {
					data.forEach(function (item) {
						var selected = item.id == id_beasiswa ? 'selected' : '';
						option += `
						  <option value="${item.id}" ${selected}>${item.nama_beasiswa}</option>
			`;
					});
				}

				if (id_beasiswa != null) {
					$('#form-edit select[name="id_beasiswa"]').html(option);
				} else {
					$('#form-tambah select[name="id_beasiswa"]').html(option);
				}
			}
		});
	}
</script>
