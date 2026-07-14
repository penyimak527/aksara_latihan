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
							aria-describedby="inputGroupPrepend" onkeyup="paket_harga()">
						<span class="input-group-text bg-primary text-white" id="inputGroupPrepend"><i
								class="ri-search-line"></i></span>
					</div>
				</div>
			</div>
		</div>
		<div id="data_paket_harga"></div>
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
						<label for="simpleinput" class="form-label">Paket</label>
            <select name="id_paket" class="form-control">
            </select>
					</div>
          <div class="mb-2">
						<label for="simpleinput" class="form-label">Jenjang</label>
            <select name="id_jenjang" class="form-control">
            </select>
					</div>
          <div class="mb-2">
						<label for="simpleinput" class="form-label">Harga Pertemuan</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="text" name="harga_pertemuan" class="form-control" onkeyup="FormatCurrency(this);" placeholder="Harga Pertemuan ...">
            </div>
					</div>
          <div class="mb-2">
						<label for="simpleinput" class="form-label">Iuran Kas</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="text" name="iuran_kas" class="form-control" onkeyup="FormatCurrency(this);" placeholder="Iuran Kas ...">
            </div>
					</div>
          <div class="mb-2">
						<label for="simpleinput" class="form-label">Target Meet Bulanan</label>
            <input type="text" name="target_meet_bulanan" class="form-control" onkeyup="FormatCurrency(this);" placeholder="Target Meet Bulanan ...">
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
          <input type="hidden" id="id_paket_harga" name="id_paket_harga" class="form-control">
          <div class="mb-2">
						<label for="simpleinput" class="form-label">Paket</label>
            <select id="id_paket" name="id_paket" class="form-control">
            </select>
					</div>
          <div class="mb-2">
						<label for="simpleinput" class="form-label">Jenjang</label>
            <select id="id_jenjang" name="id_jenjang" class="form-control">
            </select>
					</div>
          <div class="mb-2">
						<label for="simpleinput" class="form-label">Harga Pertemuan</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="text" id="harga_pertemuan" name="harga_pertemuan" class="form-control" onkeyup="FormatCurrency(this);" placeholder="Harga Pertemuan ...">
            </div>
					</div>
          <div class="mb-2">
						<label for="simpleinput" class="form-label">Iuran Kas</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="text" id="iuran_kas" name="iuran_kas" class="form-control" onkeyup="FormatCurrency(this);" placeholder="Iuran Kas ...">
            </div>
					</div>
          <div class="mb-2">
						<label for="simpleinput" class="form-label">Target Meet Bulanan</label>
            <input type="text" id="target_meet_bulanan" name="target_meet_bulanan" class="form-control" onkeyup="FormatCurrency(this);" placeholder="Target Meet Bulanan ...">
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
		paket_harga();
    paket();
    jenjang();

		$("#btn-simpan").click(function () {
			var form = $("#form-tambah");
			var data = form.serialize();
			$.ajax({
				url: '<?= base_url('admin/master/paket_harga/tambah'); ?>',
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
						paket_harga();
						$("#form-tambah")[0].reset();
					}else if (data.result == 'false') {
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
				url: '<?= base_url('admin/master/paket_harga/edit'); ?>',
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
						paket_harga();
					}else if (data.result == 'false') {
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
			paging($('#data_paket_harga .card-mapel'), jumlah);
		});
	})

	function paket_harga() {
		var search = $("#cari").val();
		$.ajax({
			url: '<?= base_url('admin/master/paket_harga/paket_harga_result'); ?>',
			type: 'POST',
			data: {
				search
			},
			dataType: 'JSON',
			success: function (data) {
				$('#paket_harga').empty();

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
          									<h5 class="judul-mapel" style="margin:0; margin-top: 8px;">${no++}. ${item.nama_paket} | ${item.nama_jenjang}</h5>
                            <p style="margin: 0; padding: 0; font-size: 12px; margin-bottom: 8px;"><b>Harga Pertemuan:</b> Rp. ${NumberToMoney(item.harga_pertemuan)} , <b>Iuran Kas:</b> Rp. ${NumberToMoney(item.iuran_kas)}</p>
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
				$('#data_paket_harga').html(table);
				let jumlah_awal = parseInt($('#dt-length-0').val());
				paging($('#data_paket_harga .card-mapel'), jumlah_awal);
			}
		});
	}

	function edit(detail) {
		$('#edit').modal('show');
		var item = JSON.parse(atob(detail));
		$('#id_paket_harga').val(item.id);
		$('#harga_pertemuan').val(NumberToMoney(item.harga_pertemuan));
    $('#iuran_kas').val(NumberToMoney(item.iuran_kas));
    $('#target_meet_bulanan').val(NumberToMoney(item.target_meet_bulanan));
    paket(item.id_paket);
    jenjang(item.id_jenjang);
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
					url: `<?= base_url(); ?>admin/master/paket_harga/hapus`,
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
							paket_harga();
						}else if (data.result == 'false') {
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

  function paket(id_paket = null) {
		$.ajax({
			url: '<?= base_url('admin/master/paket/paket_result'); ?>',
			type: 'POST',
			dataType: 'JSON',
			success: function (data) {
				var no = 1;
				var option = '<option value="">Pilih Paket</option>';
				if (data.length > 0) {
          data.forEach(function (item) {
            option += `
              <option value="${item.id}" ${item.id == id_paket ? 'selected' : ''}>${item.nama_paket}</option>
            `;
          });
				}

				if (id_paket == null) {
					$('#form-tambah select[name="id_paket"]').html(option);
				} else {
					$('#form-edit select[name="id_paket"]').html(option);
				}
			}
		});
	}

  function jenjang(id_jenjang = null) {
		$.ajax({
			url: '<?= base_url('admin/master/jenjang/jenjang_result'); ?>',
			type: 'POST',
			dataType: 'JSON',
			success: function (data) {
				var no = 1;
				var option = '<option value="">Pilih Jenjang</option>';
				if (data.length > 0) {
          data.forEach(function (item) {
            option += `
              <option value="${item.id}" ${item.id == id_jenjang ? 'selected' : ''}>${item.nama_jenjang}</option>
            `;
          });
				}

				if (id_jenjang == null) {
					$('#form-tambah select[name="id_jenjang"]').html(option);
				} else {
					$('#form-edit select[name="id_jenjang"]').html(option);
				}
			}
		});
	}
</script>
