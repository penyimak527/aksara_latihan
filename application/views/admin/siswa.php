<style media="screen">
	.div_tagihan_pembayaran {
		display: none;
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
		margin-bottom: 16px;
	}

	.kv label {
		color: var(--muted);
		font-size: .82rem;
		margin-bottom: 6px;
	}

	.kv .value {
		font-weight: 600;
	}
</style>
<div class="card">
	<div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
		<h4 class="header-title">Data <?= $title; ?></h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-3">
				<div class="mb-3">
					<div class="input-group">
						<select class="form-control" id="cari_id_kelas" onclick="siswa()"></select>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="mb-3">
					<div class="input-group">
						<input type="text" class="form-control" id="cari" placeholder="Cari ..."
							aria-describedby="inputGroupPrepend" onkeyup="siswa()">
						<span class="input-group-text bg-primary text-white" id="inputGroupPrepend"><i
								class="ri-search-line"></i></span>
					</div>
				</div>
			</div>
		</div>
		<div id="data_siswa"></div>
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
						<label for="simpleinput" class="form-label">Jenjang</label>
						<select name="id_jenjang" class="form-control">
						</select>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Nis</label>
						<input type="text" name="nis" class="form-control" placeholder="Nis ...">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Nama Siswa</label>
						<input type="text" name="nama_siswa" class="form-control" placeholder="Nama Siswa ...">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Nama Wali</label>
						<input type="text" name="nama_wali" class="form-control" placeholder="Nama Wali ...">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Hp Wali</label>
						<input type="text" name="hp_wali" class="form-control" placeholder="Hp Wali ...">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Alamat</label>
						<input type="text" name="alamat" class="form-control" placeholder="Alamat ...">
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
					<input type="hidden" id="id_siswa" name="id_siswa" class="form-control">
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Jenjang</label>
						<select id="id_jenjang" name="id_jenjang" class="form-control" onchange="kelas();">
						</select>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Kelas</label>
						<select id="id_kelas" name="id_kelas" class="form-control">
						</select>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Nis</label>
						<input type="text" id="nis" name="nis" class="form-control">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Nama Siswa</label>
						<input type="text" id="nama_siswa" name="nama_siswa" class="form-control">
					</div>
					
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Password Siswa</label>
						<input type="password" id="password_siswa" name="password_siswa" class="form-control"
							placeholder="Kosongkan jika tidak ingin mengubah password">
						<small class="text-muted">Kosongkan jika password tidak ingin diubah.</small>
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Konfirmasi Password</label>
						<input type="password" id="konfirmasi_password_siswa" name="konfirmasi_password_siswa"
							class="form-control" placeholder="Ulangi password siswa">
					</div>

					<div class="mb-2">
						<label for="simpleinput" class="form-label">Nama Wali</label>
						<input type="text" id="nama_wali" name="nama_wali" class="form-control">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Hp Wali</label>
						<input type="text" id="hp_wali" name="hp_wali" class="form-control">
					</div>
					<div class="mb-2">
						<label for="simpleinput" class="form-label">Alamat</label>
						<input type="text" id="alamat" name="alamat" class="form-control">
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
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel">Detail <?= $title; ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs nav-bordered nav-justified">
					<li class="nav-item">
						<a href="#biodata-b2" data-bs-toggle="tab" aria-expanded="trues" class="nav-link active">
							Biodata
						</a>
					</li>
					<li class="nav-item">
						<a href="#administrasi-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
							Riwayat Administrasi
						</a>
					</li>
					<li class="nav-item">
						<a href="#kelas-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
							Riwayat Kelas
						</a>
					</li>
					<li class="nav-item">
						<a href="#paket-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
							Riwayat Paket
						</a>
					</li>
					<li class="nav-item">
						<a href="#pembayaran-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
							Riwayat Pembayaran
						</a>
					</li>
				</ul>
				<div class="tab-content pt-2">
					<div class="tab-pane active" id="biodata-b2">
						<div class="row mb-2">
							<div class="col-12 col-md-6 kv">
								<label>Nama siswa</label>
								<div class="value span_nama_siswa"></div>
							</div>
							<div class="col-12 col-md-6 kv">
								<label>Nis</label>
								<div class="value span_nis"></div>
							</div>
							<div class="col-12 col-md-6 kv">
								<label>Jenjang</label>
								<div class="value span_jenjang"></div>
							</div>
							<div class="col-12 col-md-6 kv">
								<label>Kelas Saat Ini</label>
								<div class="value span_kelas"></div>
							</div>
							<div class="col-12 col-md-6 kv">
								<label>Asal Sekolah</label>
								<div class="value span_sekolah_asal"></div>
							</div>
							<div class="col-12 col-md-6 kv">
								<label>Nama Wali</label>
								<div class="value span_nama_wali"></div>
							</div>
							<div class="col-12 col-md-6 kv">
								<label>Ho Wali</label>
								<div class="value span_hp_wali"></div>
							</div>
							<div class="col-12 col-md-6 kv">
								<label>Alamat</label>
								<div class="value span_alamat"></div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="administrasi-b2">
						<div id="data_administrasi"></div>
					</div>
					<div class="tab-pane" id="kelas-b2">
						<div id="data_kelas"></div>
					</div>
					<div class="tab-pane" id="paket-b2">
						<div id="data_paket"></div>
					</div>
					<div class="tab-pane" id="pembayaran-b2">
						<div id="data_pembayaran"></div>
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
		siswa();
		jenjang();
		result_kelas();
		$("#btn-update").click(function () {
			var form = $("#form-edit");
			var data = form.serialize();
			var password = $('#form-edit input[name=password_siswa]').val();
			var konfirmasi_password = $('#form-edit input[name=konfirmasi_password_siswa]').val();
			if (password != konfirmasi_password) {
				Swal.fire({
					icon: 'error',
					title: 'Gagal',
					text: 'Password Tidak sama',
				})
				$('#btn-simpan').prop('disabled', false);
				$('#btn-simpan').html('Simpan');
				return false;
			}
			$.ajax({
				url: '<?= base_url('admin/siswa/edit'); ?>',
				type: 'POST',
				data: data,
				dataType: 'JSON',
				success: function (data) {
					$("#edit").modal('hide');

					if (data.result == 'true') {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: 'Data berhasil diupdate',
						})
						siswa();
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
			paging($('#data_siswa .card-mapel'), jumlah);
		});
	})

	function siswa() {
		var search = $("#cari").val();
		var kelas = $("#cari_id_kelas").val();
		$.ajax({
			url: '<?= base_url('admin/siswa/siswa_result'); ?>',
			type: 'POST',
			data: {
				search: search,
				kelas: kelas
			},
			dataType: 'JSON',
			success: function (data) {
				$('#siswa').empty();

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
						let detail = btoa(unescape(encodeURIComponent(JSON.stringify(item))));
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
												<b>Kelas:</b> ${item.nama_jenjang} ${item.nama_kelas}
												<br>
												<b>Nama Wali:</b> ${item.nama_wali} , <b>Hp Wali:</b> ${item.hp_wali} , <b>Alamat:</b> ${item.alamat}
												</p>
										  </div>
										  <div class="keterangan-mapel-kanan">
											  <div class="d-flex justify-content-center gap-2">
												<button type="button" class="btn btn-outline-info w-50" onclick="detail('${detail}')">
													<i class="ri-eye-line"></i>
												</button>
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
				$('#data_siswa').html(table);
				let jumlah_awal = parseInt($('#dt-length-0').val());
				paging($('#data_siswa .card-mapel'), jumlah_awal);
			}
		});
	}
	function result_kelas(id_kelas = null) {
		$.ajax({
			url: '<?= base_url('admin/siswa/kelas_result'); ?>',
			type: 'GET',
			dataType: 'JSON',
			success: function (data) {
				var no = 1;
				var option = '<option value="">Pilih Kelas</option>';
				if (data.length > 0) {
					data.forEach(function (item) {
						option += `
						  <option value="${item.id}" data-jenjang="${item.id_jenjang}" data-namajenjang="${item.nama_jenjang}" data-kelas="${item.id_kelas}" data-namakelas="${item.nama_kelas}" ${item.id == id_kelas ? 'selected' : ''}>${item.nama_jenjang} ${item.nama_kelas}</option>
						`;
					});
				}

				if (id_kelas == null) {
					$('#cari_id_kelas').html(option);
				}
			}
		});
	}

	function detail(detail) {
		$('#detail').modal('show');
		var item = JSON.parse(decodeURIComponent(escape(atob(detail))));

		$('.span_nama_siswa').html(item.nama_siswa)
		$('.span_nis').html(item.nis)
		$('.span_jenjang').html(item.nama_jenjang)
		$('.span_kelas').html(item.nama_kelas)
		$('.span_sekolah_asal').html(item.asal_sekolah)
		$('.span_nama_wali').html(item.nama_wali)
		$('.span_hp_wali').html(item.hp_wali)
		$('.span_alamat').html(item.alamat)

		let id_siswa = item.id

		$.ajax({
			type: 'POST',
			url: `<?= base_url(); ?>admin/siswa/detail_siswa_result`,
			data: { id_siswa },
			dataType: 'json',
			success: function (data) {
				var no_administrasi = 1;
				var data_administrasi = '';
				if (data['administrasi'].length == 0) {
					data_administrasi += `<div class="card-mapel">
																	<div class="keterangan-mapel">
																		<div class="keterangan-mapel-kiri">
																			<h5 class="judul-mapel" style="margin:0; margin-top: 8px;">Tidak ada data</h5>
																		</div>
																	</div>
																</div>`;
				} else {
					data['administrasi'].forEach(function (item) {
						data_administrasi += `<div class="card-mapel">
																		<p class="keterangan-hari">
																			<span>Tanggal: ${item.tanggal} ${item.waktu}</span>
																		</p>
																		<div class="keterangan-mapel">
																			<div class="keterangan-mapel-kiri">
																				<h5 class="judul-mapel" style="margin:0; margin-top: 8px;">${no_administrasi++}. ${item.status_biaya}</h5>
																				<p style="margin: 0; padding: 0; font-size: 12px; margin-bottom: 8px;">
																				<b>Periode:</b> ${item.periode_tahun}
																				<br>
																				<b>Nominal:</b> Rp. ${NumberToMoney(item.nominal_bayar)} , <b>Metode:</b> ${item.metode_pembayaran}
																				</p>
																			</div>
																		</div>
																	</div>`;
					});
				}

				var no_kelas = 1;
				var data_kelas = '';
				if (data['kelas'].length == 0) {
					data_kelas += `<div class="card-mapel">
																	<div class="keterangan-mapel">
																		<div class="keterangan-mapel-kiri">
																			<h5 class="judul-mapel" style="margin:0; margin-top: 8px;">Tidak ada data</h5>
																		</div>
																	</div>
																</div>`;
				} else {
					data['kelas'].forEach(function (item) {
						data_kelas += `<div class="card-mapel">
																		<p class="keterangan-hari">
																			<span>Periode: ${item.periode_tahun}</span>
																		</p>
																		<div class="keterangan-mapel">
																			<div class="keterangan-mapel-kiri">
																				<h5 class="judul-mapel" style="margin:0; margin-top: 8px;">${no_kelas++}. ${item.nama_jenjang} - ${item.nama_kelas}</h5>
																			</div>
																		</div>
																	</div>`;
					});
				}

				var no_paket = 1;
				var data_paket = '';
				if (data['paket'].length == 0) {
					data_paket += `<div class="card-mapel">
																	<div class="keterangan-mapel">
																		<div class="keterangan-mapel-kiri">
																			<h5 class="judul-mapel" style="margin:0; margin-top: 8px;">Tidak ada data</h5>
																		</div>
																	</div>
																</div>`;
				} else {
					data['paket'].forEach(function (item) {
						data_paket += `<div class="card-mapel">
																		<p class="keterangan-hari">
																			<span>Mulai: ${item.tanggal_mulai} - Selesai: ${item.tanggal_selesai}</span>
																		</p>
																		<div class="keterangan-mapel">
																			<div class="keterangan-mapel-kiri">
																				<h5 class="judul-mapel" style="margin:0; margin-top: 8px;">${no_paket++}. ${item.nama_jenjang} - ${item.nama_kelas}</h5>
																				<p style="margin: 0; padding: 0; font-size: 12px; margin-bottom: 8px;">
																				<b>Paket:</b> ${item.nama_paket}
																				</p>
																			</div>
																		</div>
																	</div>`;
					});
				}

				var no_pembayaran = 1;
				var data_pembayaran = '';
				if (data['pembayaran'].length == 0) {
					data_pembayaran += `<div class="card-mapel">
																	<div class="keterangan-mapel">
																		<div class="keterangan-mapel-kiri">
																			<h5 class="judul-mapel" style="margin:0; margin-top: 8px;">Tidak ada data</h5>
																		</div>
																	</div>
																</div>`;
				} else {
					data['pembayaran'].forEach(function (item) {
						data_pembayaran += `<div class="card-mapel">
																		<p class="keterangan-hari">
																			<span>Status: ${item.status}</span>
																		</p>
																		<div class="keterangan-mapel">
																			<div class="keterangan-mapel-kiri">
																				<h5 class="judul-mapel" style="margin:0; margin-top: 8px;">${no_pembayaran++}. Periode: ${item.periode_tahun}-${item.periode_bulan}</h5>
																				<p style="margin: 0; padding: 0; font-size: 12px; margin-bottom: 8px;">
																				<b>Jumlah Meet:</b> ${item.jumlah_meet}, <b>Tagihan:</b> Rp. ${NumberToMoney(item.total_akhir)}<br>
																				<b>Bayar:</b> Rp. ${NumberToMoney(item.nominal_bayar)}, <b>Kembali:</b> Rp. ${NumberToMoney(item.kembali)}<br>
																				<b>Metode:</b> ${item.metode_pembayaran}
																				</p>
																			</div>
																		</div>
																	</div>`;
					});
				}

				$('#data_administrasi').html(data_administrasi)
				$('#data_kelas').html(data_kelas)
				$('#data_paket').html(data_paket)
				$('#data_pembayaran').html(data_pembayaran)
			}
		})

	}

	function edit(detail) {
		$('#edit').modal('show');
		var item = JSON.parse(atob(detail));
		$('#id_siswa').val(item.id);
		$('#nis').val(item.nis);
		$('#nama_siswa').val(item.nama_siswa);
		$('#nama_wali').val(item.nama_wali);
		$('#hp_wali').val(item.hp_wali);
		$('#alamat').val(item.alamat);

		$('#password_siswa').val('');
		$('#konfirmasi_password_siswa').val('');
		jenjang(item.id_jenjang, item.id_kelas);
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
					url: `<?= base_url(); ?>admin/siswa/hapus`,
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
							siswa();
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

	function jenjang(id_jenjang = null, id_kelas = null) {
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
					kelas(id_kelas)
				}
			}
		});
	}

	function kelas(id_kelas = null) {
		var id_jenjang = $('#edit select[name="id_jenjang"]').val();
		$.ajax({
			url: '<?= base_url('admin/administrasi/daftar_awal/kelas_result'); ?>',
			type: 'POST',
			data: {
				id_jenjang
			},
			dataType: 'JSON',
			success: function (data) {
				var no = 1;
				var option = '<option value="">Pilih Kelas</option>';
				if (data.length == 0) {

				} else {
					data.forEach(function (item) {

						option += `
							<option value="${item.id}" ${item.id == id_kelas ? 'selected' : ''}>${item.nama_kelas}</option>
						`;
					});
				}

				if (id_kelas == null) {
					$('#tambah select[name="id_kelas"]').html(option);
				} else {
					$('#edit select[name="id_kelas"]').html(option);
				}
			}
		});
	}
</script>