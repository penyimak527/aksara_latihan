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
							aria-describedby="inputGroupPrepend" onkeyup="pendaftaran_paket()">
						<span class="input-group-text bg-primary text-white" id="inputGroupPrepend"><i
								class="ri-search-line"></i></span>
					</div>
				</div>
			</div>
		</div>
		<div id="data_pendaftaran_paket"></div>
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
						<label for="simpleinput" class="form-label">Jenis Administrasi</label>
						<select name="jenis_administrasi" class="form-control">
							<option value="Reguler">Reguler</option>
							<option value="Private">Private</option>
						</select>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Siswa</label>
						<input type="text" class="form-control siswa_input" name="nama_siswa" placeholder="Siswa ...">
	    			<input type="hidden" name="id_siswa">
					</div>
					<input type="hidden" name="id_jenjang">
					<input type="hidden" name="id_kelas">
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Paket</label>
						<select name="id_paket_harga" class="form-control" onchange="pilih_paket();" disabled>
						</select>
					</div>
					<input type="hidden" name="id_paket">
					<input type="hidden" name="harga_paket_awal">
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Tanggal Mulai</label>
						<input type="text" name="tanggal_mulai" class="form-control" id="flatpicker"
							placeholder="Tanggal Mulai ..." />
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Tanggal Selesai</label>
						<input type="text" name="tanggal_selesai" id="flatpicker" class="form-control"
							placeholder="Tanggal Selesai ..." />
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Harga Paket</label>
						<div class="input-group">
							<span class="input-group-text">Rp</span>
							<input readonly type="text" name="harga_paket" class="form-control"
								placeholder="Harga Paket ...">
						</div>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Diskon</label>
						<div class="input-group">
							<span class="input-group-text">Rp</span>
							<input type="text" name="diskon" class="form-control"
								onkeyup="FormatCurrency(this); hitung_diskon();" value="0">
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
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel">Edit <?= $title; ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-edit">
					<input type="hidden" id="id_pendaftaran_paket" name="id_pendaftaran_paket">
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Jenis Administrasi</label>
						<select name="jenis_administrasi" class="form-control">
							<option value="Reguler">Reguler</option>
							<option value="Private">Private</option>
						</select>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Siswa</label>
						<input type="text" class="form-control siswa_input" name="nama_siswa" placeholder="Siswa ...">
	    			<input type="hidden" name="id_siswa">
					</div>
					<input type="hidden" name="id_jenjang">
					<input type="hidden" name="id_kelas">
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Paket</label>
						<select name="id_paket_harga" class="form-control" onchange="pilih_paket();" disabled>
						</select>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Tanggal Mulai</label>
						<input type="text" name="tanggal_mulai" class="form-control" placeholder="Tanggal Mulai ..." />
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Tanggal Selesai</label>
						<input type="text" name="tanggal_selesai" class="form-control" placeholder="Tanggal Selesai ..." />
					</div>
					<input type="hidden" name="id_paket">
					<input type="hidden" name="harga_paket_awal">
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Harga Paket</label>
						<div class="input-group">
							<span class="input-group-text">Rp</span>
							<input readonly type="text" name="harga_paket" class="form-control" placeholder="Harga Paket ...">
						</div>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Diskon</label>
						<div class="input-group">
							<span class="input-group-text">Rp</span>
							<input type="text" name="diskon" class="form-control" onkeyup="FormatCurrency(this); hitung_diskon();" value="0">
						</div>
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
		pendaftaran_paket();
		siswa();

		$("#btn-simpan").click(function () {
			var form = $("#form-tambah");
			var data = form.serialize();
			$.ajax({
				url: '<?= base_url('admin/pendaftaran_paket/tambah'); ?>',
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
						pendaftaran_paket();
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
				url: '<?= base_url('admin/pendaftaran_paket/edit'); ?>',
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
						pendaftaran_paket();
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
			paging($('#data_pendaftaran_paket .card-mapel'), jumlah);
		});
	})

	function pendaftaran_paket() {
		var search = $("#cari").val();
		$.ajax({
			url: '<?= base_url('admin/pendaftaran_paket/pendaftaran_paket_result'); ?>',
			type: 'POST',
			data: {
				search
			},
			dataType: 'JSON',
			success: function (data) {
				$('#pendaftaran_paket').empty();

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
												<b>Nama Paket:</b> ${item.nama_paket} - ${item.nama_jenjang} , <b>Diskon:</b> Rp. ${NumberToMoney(item.diskon)}<br>
												<b>Tanggal Mulai & Selesai:</b> ${item.tanggal_mulai} - ${item.tanggal_selesai}
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
				$('#data_pendaftaran_paket').html(table);
				let jumlah_awal = parseInt($('#dt-length-0').val());
				paging($('#data_pendaftaran_paket .card-mapel'), jumlah_awal);
			}
		});
	}

	function edit(detail) {
		$('#edit').modal('show');
		var item = JSON.parse(atob(detail));

		$('#id_pendaftaran_paket').val(item.id);

		$('select[name="jenis_administrasi"]').html(`
			<option value="Reguler" ${item.jenis_administrasi == 'Reguler' ? 'selected' : ''}>Reguler</option>
			<option value="Private" ${item.jenis_administrasi == 'Private' ? 'selected' : ''}>Private</option>
		`);
		flatpickr("#form-edit input[name='tanggal_mulai']", {
			dateFormat: "d-m-Y",
			altInput: true,
			altFormat: "d F Y",
			locale: "id",
			defaultDate: item.tanggal_mulai
		});
		flatpickr("#form-edit input[name='tanggal_selesai']", {
			dateFormat: "d-m-Y",
			altInput: true,
			altFormat: "d F Y",
			locale: "id",
			defaultDate: item.tanggal_selesai
		});
		$('#form-edit input[name="diskon"]').val(NumberToMoney(item.diskon));

		siswa(item.id_siswa, item.id_paket)
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
					url: `<?= base_url(); ?>admin/pendaftaran_paket/hapus`,
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
							pendaftaran_paket();
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

	function siswa(id_siswa = null, id_paket = null) {
	  return $.ajax({
	    url: '<?= base_url('admin/pendaftaran_paket/siswa_result'); ?>',
			data: {id_siswa},
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

	function pilih_siswa(id_paket = null) {
		var edit_siswa = $('#edit input[name="id_siswa"]').val();
		var id_siswa = $('#tambah input[name="id_siswa"]').val() ? $('#tambah input[name="id_siswa"]').val() : edit_siswa;

		$('#tambah select[name="id_paket_harga"]').attr('disabled', false)
		$('#edit select[name="id_paket_harga"]').attr('disabled', false)
		$.ajax({
			url: '<?= base_url('admin/pendaftaran_paket/siswa_row'); ?>',
			data: { id_siswa },
			type: 'POST',
			dataType: 'JSON',
			success: function (data) {
				var option = '<option value="">Pilih Paket</option>';
				data.paket_harga.forEach(function (item) {
					option += `
						<option value="${item.id}" data-idpaket="${item.id_paket}" data-harga="${item.harga_pertemuan}" ${item.id_paket == id_paket ? 'selected' : ''}>${item.nama_paket}</option>
					`;
				});

				if (id_paket == null) {
					$('#tambah input[name="id_jenjang"]').val(data.siswa.id_jenjang);
					$('#tambah input[name="id_kelas"]').val(data.siswa.id_kelas);
					$('#tambah select[name="id_paket_harga"]').html(option);
				} else {
					$('#edit input[name="id_jenjang"]').val(data.siswa.id_jenjang);
					$('#edit input[name="id_kelas"]').val(data.siswa.id_kelas);
					$('#edit select[name="id_paket_harga"]').html(option);
					pilih_paket();
				}
			}
		});
	}

	function pilih_paket() {
		let id_paket = $('#tambah select[name="id_paket_harga"]').find(':selected').data('idpaket');
		let harga = $('#tambah select[name="id_paket_harga"]').find(':selected').data('harga');
		if (id_paket == undefined) {
			id_paket = $('#edit select[name="id_paket_harga"]').find(':selected').data('idpaket');
			harga = $('#edit select[name="id_paket_harga"]').find(':selected').data('harga');
			$('#edit input[name="id_paket"]').val(id_paket);
			$('#edit input[name="harga_paket_awal"]').val(harga);
			$('#edit input[name="harga_paket"]').val(NumberToMoney(harga));
		} else {
			$('#tambah input[name="id_paket"]').val(id_paket);
			$('#tambah input[name="harga_paket_awal"]').val(harga);
			$('#tambah input[name="harga_paket"]').val(NumberToMoney(harga));
		}
	}

	function hitung_diskon() {
		let harga_paket_awal = $(`#form-tambah input[name="harga_paket_awal"]`).val();
		harga_paket_awal = harga_paket_awal.split(',').join('');
		if (harga_paket_awal == null || harga_paket_awal == '') {
			harga_paket_awal = '0';
		}

		let diskon = $(`#form-tambah input[name="diskon"]`).val();
		diskon = diskon.split(',').join('');
		if (diskon == null || diskon == '') {
			diskon = '0';
		}

		let hitung = parseFloat(harga_paket_awal) - parseFloat(diskon)
		$(`#form-tambah input[name="harga_paket"]`).val(NumberToMoney(hitung));
	}
</script>
