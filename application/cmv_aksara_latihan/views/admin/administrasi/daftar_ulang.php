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
							aria-describedby="inputGroupPrepend" onkeyup="daftar_ulang()">
						<span class="input-group-text bg-primary text-white" id="inputGroupPrepend"><i
								class="ri-search-line"></i></span>
					</div>
				</div>
			</div>
		</div>
		<div id="data_daftar_ulang"></div>
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
					<ul class="nav nav-tabs nav-bordered nav-justified">
						<li class="nav-item">
							<a href="#siswa-b2" data-bs-toggle="tab" aria-expanded="false"
								class="nav-link active">Siswa</a>
						</li>
						<li class="nav-item">
							<a href="#administrasi-b2" data-bs-toggle="tab" aria-expanded="true"
								class="nav-link">Administrasi</a>
						</li>
					</ul>
					<div class="tab-content pt-2">
						<div class="tab-pane active" id="siswa-b2">
							<div class="alert alert-primary alert-dismissible fade show" role="alert">
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  Pilih siswa terlebih dahulu lalu pilih kelas berikutnya dari siswa yang terpilih ...
              </div>
							<div class="mb-2">
								<label for="simpleinput" class="form-label">Siswa</label>
								<input type="text" class="form-control siswa_input" name="nama_siswa" placeholder="Siswa ...">
			    			<input type="hidden" name="id_siswa">
							</div>
							<div class="mb-2">
								<label for="simpleinput" class="form-label">Kelas Saat Ini</label>
								<input readonly type="text" name="nama_kelas_saat_ini" class="form-control"
									placeholder="Kelas Saat Ini ...">
							</div>
							<div class="mb-2">
								<label for="simpleinput" class="form-label">Kelas Berikutnya</label>
								<select name="id_kelas_berikutnya" class="form-control" disabled>
								</select>
							</div>
						</div>
						<div class="tab-pane" id="administrasi-b2">
							<div class="mb-2">
								<label for="simpleinput" class="form-label">Periode Tahun</label>
								<select name="periode_tahun" class="form-control">
									<?php
									$now = date('Y');
									$periode_tahun_selected = '';
									for ($a = 2025; $a <= $now; $a++) {
										if ($a == date('Y')) {
											$periode_tahun_selected = 'selected';
										}
										echo '<option value="' . $a . '" ' . $periode_tahun_selected . '>' . $a . '</option>';
									}
									?>
								</select>
							</div>
							<div class="mb-2">
								<label for="simpleinput" class="form-label">Biaya Daftar Ulang</label>
								<div class="input-group">
									<span class="input-group-text">Rp</span>
									<input readonly type="text" name="biaya_daftar_ulang" class="form-control"
										value="50,000" placeholder="Biaya Daftar Ulang ...">
								</div>
							</div>
							<div class="mb-2">
								<label for="simpleinput" class="form-label">Metode Pembayaran</label>
								<select name="metode_pembayaran" class="form-control">
									<option value="Cash">Cash</option>
									<option value="Transfer">Transfer</option>
									<option value="Qris">Qris</option>
								</select>
							</div>
							<div class="mb-2">
								<label for="simpleinput" class="form-label">Nominal Bayar</label>
								<div class="input-group">
									<span class="input-group-text">Rp</span>
									<input type="text" name="nominal_bayar" class="form-control"
										onkeyup="FormatCurrency(this); hitung_jumlah_bayar('tambah');"
										placeholder="Nominal Bayar ...">
								</div>
							</div>
							<div class="mb-2">
								<label for="simpleinput" class="form-label">Kembali</label>
								<div class="input-group">
									<span class="input-group-text">Rp</span>
									<input readonly type="text" name="kembali" class="form-control"
										placeholder="Kembali ...">
								</div>
							</div>
							<div class="mb-2">
								<label for="simpleinput" class="form-label">Keterangan</label>
								<input type="text" name="keterangan" class="form-control" placeholder="Keterangan ...">
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
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel">Edit <?= $title; ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-edit">
					<input type="hidden" id="id_daftar_ulang" name="id_daftar_ulang" class="form-control">
					<input type="hidden" id="id_siswa" name="id_siswa" class="form-control">
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Siswa</label>
						<input readonly type="text" id="nama_siswa" name="nama_siswa" class="form-control">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Periode Tahun</label>
						<select name="periode_tahun" class="form-control">
							<?php
							$now = date('Y');
							$periode_tahun_selected = '';
							for ($a = 2025; $a <= $now; $a++) {
								if ($a == date('Y')) {
									$periode_tahun_selected = 'selected';
								}
								echo '<option value="' . $a . '" ' . $periode_tahun_selected . '>' . $a . '</option>';
							}
							?>
						</select>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Biaya Daftar Ulang</label>
						<div class="input-group">
							<span class="input-group-text">Rp</span>
							<input readonly type="text" name="biaya_daftar_ulang" class="form-control" value="50,000">
						</div>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Metode Pembayaran</label>
						<select name="metode_pembayaran" class="form-control">
						</select>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Nominal Bayar</label>
						<div class="input-group">
							<span class="input-group-text">Rp</span>
							<input type="text" id="nominal_bayar" name="nominal_bayar" class="form-control"
								onkeyup="FormatCurrency(this); hitung_jumlah_bayar('edit');">
						</div>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Kembali</label>
						<div class="input-group">
							<span class="input-group-text">Rp</span>
							<input readonly type="text" id="kembali" name="kembali" class="form-control">
						</div>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Keterangan</label>
						<input type="text" id="keterangan" name="keterangan" class="form-control">
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
		daftar_ulang();
		siswa();

		$("#btn-simpan").click(function () {
			var form = $("#form-tambah");
			var data = form.serialize();
			$.ajax({
				url: '<?= base_url('admin/administrasi/daftar_ulang/tambah'); ?>',
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
						daftar_ulang();
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
				url: '<?= base_url('admin/administrasi/daftar_ulang/edit'); ?>',
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
						daftar_ulang();
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
			paging($('#data_daftar_ulang .card-mapel'), jumlah);
		});
	})

	function daftar_ulang() {
		var search = $("#cari").val();
		$.ajax({
			url: '<?= base_url('admin/administrasi/daftar_ulang/daftar_ulang_result'); ?>',
			type: 'POST',
			data: {
				search
			},
			dataType: 'JSON',
			success: function (data) {
				$('#daftar_ulang').empty();

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
						table += `<div class="card-mapel">
									<p class="keterangan-hari">
										  <span>Tanggal:  ${item.tanggal} ${item.waktu}</span>
									  </p>
									  <div class="keterangan-mapel">
										  <div class="keterangan-mapel-kiri">
											  <h5 class="judul-mapel" style="margin:0; margin-top: 8px;">${no++}. ${item.nama_siswa} - ${item.alamat}</h5>
											<p style="margin: 0; padding: 0; font-size: 12px; margin-bottom: 8px;">
											<b>Metode:</b> ${(item.metode_pembayaran)}
											<br>
											<b>Bayar:</b> Rp. ${NumberToMoney(item.nominal_bayar)} , <b>Kembali:</b> Rp. ${NumberToMoney(item.kembali)}</p>
										  </div>
										  <div class="keterangan-mapel-kanan">
											<div class="d-flex justify-content-center gap-2">
												<button type="button" class="btn btn-outline-warning w-50" onclick="edit('${detail}')">
													<i class="ri-edit-line"></i>
												</button>
												<button type="button" class="btn btn-outline-danger w-50" onclick="hapus('${item.id}', '${item.id_siswa}')">
													<i class="ri-delete-bin-line"></i>
												</button>
												</div>
										  </div>
									  </div>

								  </div>
								  `;


					});
				}
				$('#data_daftar_ulang').html(table);
				let jumlah_ulang = parseInt($('#dt-length-0').val());
				paging($('#data_daftar_ulang .card-mapel'), jumlah_ulang);
			}
		});
	}

	function edit(detail) {
		$('#edit').modal('show');
		var item = JSON.parse(atob(detail));
		$('#id_daftar_ulang').val(item.id);
		$('#id_siswa').val(item.id_siswa);
		$('#nama_siswa').val(item.nama_siswa);
		$('#periode_tahun').val(item.periode_tahun);
		$('#biaya_daftar_ulang').val(NumberToMoney(item.biaya_daftar_ulang));
		$('#metode_pembayaran').val(item.metode_pembayaran);
		$('#nominal_bayar').val(NumberToMoney(item.nominal_bayar));
		$('#kembali').val(NumberToMoney(item.kembali));
		$('#keterangan').val(item.keterangan);
		$('#form-edit select[name="periode_tahun"]').html(
			`<?php
			$now = date('Y');
			$periode_tahun_selected = '';
			for ($a = 2025; $a <= $now; $a++) {
				?>
				<option value="<?php echo $a; ?>" ${item.periode_tahun == <?php echo $a; ?> ? 'selected' : ''}><?php echo $a; ?></option>
			<?php
			}
			?>`
		)
		$('#form-edit select[name="metode_pembayaran"]').html(
			`
							<option value="Cash" ${item.semester == 'Cash' ? 'selected' : ''}>Cash</option>
							<option value="Transfer" ${item.semester == 'Transfer' ? 'selected' : ''}>Transfer</option>
			`
		);
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

	function hapus(id, id_siswa) {
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
					url: `<?= base_url(); ?>admin/administrasi/daftar_ulang/hapus`,
					data: {
						id: id,
						id_siswa: id_siswa
					},
					dataType: 'json',
					success: function (data) {
						if (data.result == 'true') {
							Swal.fire({
								icon: 'success',
								title: 'Berhasil',
								text: 'Data berhasil dihapus',
							})
							daftar_ulang();
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

	function siswa(id_siswa = null) {
		$.ajax({
			url: '<?= base_url('admin/administrasi/daftar_ulang/siswa_result'); ?>',
			type: 'POST',
			dataType: 'JSON',
			success: function (data) {
				var no = 1;
				var option = '<option value="">Pilih Siswa</option>';
				if (data.length > 0) {
					data.forEach(function (item) {
						option += `
			  <option value="${item.id}" ${item.id == id_siswa ? 'selected' : ''}>${item.nama_siswa} | ${item.alamat}</option>
			`;
					});
				}

				if (id_siswa == null) {
					$('#form-tambah select[name="id_siswa"]').html(option);
				} else {
					$('#form-edit select[name="id_siswa"]').html(option);
				}
			}
		});
	}

	function siswa(id_siswa = null) {
	  return $.ajax({
	    url: '<?= base_url('admin/administrasi/daftar_ulang/siswa_result'); ?>',
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
						pilih_siswa();
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
						pilih_siswa();
					}).on('focus', function () {
						$(this).autocomplete('search', '');
					});

					let ambil_data_siswa = Object.keys(map_id_siswa).find(k => map_id_siswa[k] == id_siswa);
	        if (ambil_data_siswa) {
						$('#edit input[name="nama_siswa"]').val(ambil_data_siswa);
						$('#edit input[name="id_siswa"]').val(id_siswa);
						pilih_siswa();
	        }
				}
	    }
	  });
	}

	function pilih_siswa() {
		var id_siswa = $('#tambah input[name="id_siswa"]').val();

		$('#tambah select[name="id_kelas_berikutnya"]').attr('disabled', false)
		$.ajax({
			url: '<?= base_url('admin/administrasi/daftar_ulang/siswa_row'); ?>',
			data: { id_siswa },
			type: 'POST',
			dataType: 'JSON',
			success: function (data) {
				$('#tambah input[name="nama_kelas_saat_ini"]').val(data.siswa.nama_jenjang + ' - ' + data.siswa.nama_kelas);

				var option = '<option value="">Pilih Kelas Berikutnya</option>';
				data.kelas_berikutnya.forEach(function (item) {
					option += `
						<option value="${item.id}">${item.nama_jenjang} - ${item.nama_kelas}</option>
					`;
				});

				$('#tambah select[name="id_kelas_berikutnya"]').html(option);
			}
		});
	}

	function hitung_jumlah_bayar(form) {
		let nominal_bayar = $(`#form-${form} input[name="nominal_bayar"]`).val();
		nominal_bayar = nominal_bayar.split(',').join('');
		if (nominal_bayar == null || nominal_bayar == '') {
			nominal_bayar = '0';
		}

		let biaya_daftar_ulang = $(`#form-${form} input[name="biaya_daftar_ulang"]`).val();
		biaya_daftar_ulang = biaya_daftar_ulang.split(',').join('');
		if (biaya_daftar_ulang == null || biaya_daftar_ulang == '') {
			biaya_daftar_ulang = '0';
		}

		let hitung = parseFloat(nominal_bayar) - parseFloat(biaya_daftar_ulang)
		$(`#form-${form} input[name="kembali"]`).val(NumberToMoney(hitung));
	}
</script>
