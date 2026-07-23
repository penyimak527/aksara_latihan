<style>
	.scroll-wrapper {

		width: 100%;
		overflow: auto;
		max-height: 400px;
	}

	.excel-container {
		display: flex;
		flex-direction: column;
		gap: 12px;
		margin-top: 20px;
		font-family: 'Segoe UI', sans-serif;
		font-size: 14px;
	}


	.excel-header,
	.excel-row {
		display: grid;
		grid-template-columns: 50px 1fr 300px 80px 130px;
		gap: 15px;
		padding: 10px;
		border: 1px solid #ced4da;
		border-radius: 6px;
		align-items: center;
	}

	.excel-header {
		border: 2px solid #009b4b;
		color: #009b4b;
		font-weight: 600;
	}

	.excel-row {
		border: 1px solid #6c757d;
		color: #343a40;
		margin-bottom: 10px;
	}

	.status-lulus {
		color: green;
		font-weight: 600;
	}

	.status-tidak {
		color: red;
		font-weight: 600;
	}

	@media screen and (max-width: 768px) {

		.excel-header,
		.excel-row {
			grid-template-columns: 1fr;
			padding: 10px;
		}

		.excel-header div::before,
		.excel-row div::before {
			content: attr(data-label) ": ";
			font-weight: 600;
			color: #6c757d;
		}

		.excel-header {
			display: none;
		}
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
						<input type="text" class="form-control" id="cari-data-laporan" placeholder="Cari Laporan"
							aria-describedby="inputGroupPrepend" onkeyup="data_laporan()">
						<span class="input-group-text bg-primary text-white" id="inputGroupPrepend"><i
								class="ri-search-line"></i></span>
					</div>
				</div>
			</div>
		</div>
		<div style="height: 500px; overflow-y: auto; scroll-behavior: smooth;" id="data-laporan">
		</div>
	</div>
</div>

<div class="modal fade" id="printLaporan" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel"></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form_laporan">
					<input type="hidden" id="path">
					<div class="row" id="filter-data" style="margin-bottom: 20px;">
						<div class="col-md-4">
							<div class="radio">
								<input type="radio" name="filter" id="filter_hari" value="tanggal" checked>
								<label for="filter_hari"> Hari </label>
							</div>
						</div>
						<div class="col-md-4">
							<div class="radio">
								<input type="radio" name="filter" id="filter_bulan" value="bulan">
								<label for="filter_bulan"> Bulan </label>
							</div>
						</div>
						<div class="col-md-4">
							<div class="radio">
								<input type="radio" name="filter" id="filter_tahun" value="tahun">
								<label for="filter_tahun"> Tahun </label>
							</div>
						</div>
					</div>
					<div id="form-hari" class="row mb-2">
						<div class="col-md-6">
							<div class="form-group">
								<label class="mb-1">Start</label>
								<input type="date" class="form-control" name="dari_tanggal">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="mb-1">End</label>
								<input type="date" class="form-control" name="sampai_tanggal">
							</div>
						</div>
					</div>

					<div id="form-bulan" class="row mb-2">
						<div class="col-md-6">
							<div class="form-group">
								<label class="mb-1">Bulan</label>
								<select class="form-control" data-width="100%" name="filter_bulan">
									<?php
									$bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
									$jlh_bln = count($bulan);
									$no = 0;
									for ($c = 0; $c < $jlh_bln; $c += 1) {
										$no++;
										$no_pas = sprintf("%02s", $no);
										?>
										<option value="<?php echo $no_pas; ?>">
											<?php echo $bulan[$c]; ?>
										</option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="mb-1">Tahun</label>
								<select class="form-control" data-width="100%" name="filter_tahun">
									<?php
									$now = date('Y');
									for ($a = 2025; $a <= $now; $a++) {
										?>
										<option value="<?php echo $a; ?>">
											<?php echo $a; ?>
										</option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
					</div>
					<div id="form-tahun" class="row mb-2">
						<div class="col-md-12">
							<div class="form-group">
								<label class="mb-1">Tahun</label>
								<select class="form-control" data-width="100%" name="single_filter_tahun">
									<?php
									$now = date('Y');
									for ($a = 2025; $a <= $now; $a++) {
										?>
										<option value="<?php echo $a; ?>">
											<?php echo $a; ?>
										</option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
					</div>
					<div id="form-tahun-ajaran" class="row mb-2">
						<div class="col-md-12">
							<div class="form-group">
								<label class="mb-1">Tahun Ajaran</label>
								 <select class="form-control" data-width="100%" name="single_filter_tahun_ajaran" >
                	<?php foreach ($tahun_ajaran_options as $tahun_ajaran) { ?>
                    <option value="<?php echo html_escape($tahun_ajaran); ?>">
                        <?php echo html_escape($tahun_ajaran); ?>
                    </option>
               	 <?php } ?>
         		   </select>
							</div>
						</div>
					</div>
					<div id="form-kelas-tahun-ajaran" class="row mb-2">
						<div class="col-md-12">
							<div class="form-group">
								<label class="mb-1">Kelas</label>
								<select name="id_kelas" id="id-kelas-perkembangan" class="form-control">
									<option value="">Pilih Kelas</option>
									<?php
									foreach ($kelas as $j): ?>
										?>
										<option value="<?php echo $j['id']; ?>">
											<?php echo $j['nama_kelas']; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
					<div id="form-siswa-perkembangan" class="row mb-2" style="display: none;">
						<div class="col-md-12">
							<div class="form-group">
								<label class="mb-1">Nama Siswa</label>
								<select name="id_siswa" id="id-siswa-perkembangan" class="form-control" disabled>
									<option value="">Pilih kelas terlebih dahulu</option>
								</select>
							</div>
						</div>
					</div>
					<div id="form-mapel" class="row mb-2">
						<div class="col-md-12">
							<div class="form-group">
								<label class="mb-1">Mapel</label>
								<select name="id_mapel" id="id-mapel-perkembangan" class="form-control">
									<option value="semua">Pilih Mapel</option>
									<?php
									foreach ($mapel_options as $mapel): ?>
										<option value="<?php echo $mapel['id']; ?>">
											<?php echo $mapel['nama_mata_pelajaran']; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
					<div id="form-spp-bulanan" class="row g-2 mb-4" style="display: none;">
						<div class="col-md-12">
							<div class="form-group">
								<label class="mb-1">Jenjang</label>
								<select type="date" name="id_jenjang" class="form-control">
									<option value="semua">Pilih Jenjang</option>
									<?php
									foreach ($jenjang as $j): ?>
										?>
										<option value="<?php echo $j['id']; ?>">
											<?php echo $j['nama_jenjang']; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-md-12" id="kelas">
							<div class="form-group">
								<label class="mb-1">Kelas</label>
								<select name="id_kelas" class="form-control">
									<option value="semua">Pilih Kelas</option>
									<?php
									foreach ($kelas as $j): ?>
										?>
										<option value="<?php echo $j['id']; ?>">
											<?php echo $j['nama_kelas']; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-md-12" id="paket">
							<div class="form-group">
								<label class="mb-1">Paket</label>
								<select type="date" name="id_paket" class="form-control">
									<option value="semua">Pilih Paket</option>
									<?php
									foreach ($paket as $j): ?>
										?>
										<option value="<?php echo $j['id']; ?>">
											<?php echo $j['nama_paket']; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-md-12" id="beasiswa">
							<div class="form-group">
								<label class="mb-1">Pilih Beasiswa</label>
								<select type="date" name="id_beasiswa" class="form-control">
									<option value="semua">Pilih Beasiswa</option>
									<?php
									foreach ($beasiswa as $j): ?>
										?>
										<option value="<?php echo $j['id']; ?>">
											<?php echo $j['nama_beasiswa']; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
					<div id="form-siswa" class="row g-2 mb-4" style="display: none;">
						<div class="col-md-12">
							<div class="form-group">
								<label class="mb-1">Siswa</label>
								<select type="date" name="id_siswa" class="form-control">
									<option value="semua">Pilih Siswa</option>
									<?php
									foreach ($siswa as $j): ?>
										?>
										<option value="<?php echo $j['id']; ?>">
											<?php echo $j['nama_siswa']; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
					<!-- <div id="form-pegawai" class="row g-2 mb-4" style="display: none;">
						<div class="col-md-12">
							<div class="form-group">
								<label class="mb-1">Pegawai</label>
								<select type="date" name="id_siswa" class="form-control">
									<option value="semua">Pilih Pegawai</option>
									<?php
									foreach ($pegawai as $j): ?>
										?>
										<option value="<?php echo $j['id']; ?>">
											<?php echo $j['nama_pegawai']; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div> -->



					<div class="modal-footer" style="margin-right:-22px;">
						<button type="button" class="btn btn-success waves-effect" name="print" value="excel"
							id="btn_print_laporan_excel"><i class="fa fa-file-excel me-1"></i>
							Excel</button>
						<button type="button" class="btn btn-info waves-effect" name="print" value="pdf"
							id="btn_print_laporan"><i class="fa fa-print me-1"></i>
							Print</button>
						<button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>

<script>
	$(document).ready(function () {
		data_laporan();
		$('#filter_hari').click(function () {
			$('#form-hari').show();
			$('#form-bulan').hide();
			$('#form-tahun').hide();
		});

		$('#filter_bulan').click(function () {
			$('#form-hari').hide();
			$('#form-bulan').show();
			$('#form-tahun').hide();
		});

		$('#filter_tahun').click(function () {
			$('#form-hari').hide();
			$('#form-bulan').hide();
			$('#form-tahun').show();
		});

		$('#btn_print_laporan').click(function () {
			var unindexed_array = $('#form_laporan').serializeArray();
			var indexed_array = {};

			$.map(unindexed_array, function (n, i) {
				indexed_array[n['name']] = n['value'];
			});
			indexed_array['print'] = 'pdf';

			let path = $('#path').val();
			console.log(path);
			$.ajax({
				url: '<?php echo base_url(); ?>' + path,
				data: JSON.stringify(indexed_array),
				contentType: "application/json",
				type: "POST",
				async: false,
				beforeSend: () => {
					$('#popup_load').show();
				},
				success: function (result) {
					let myWindow = window.open('', '_blank');
					myWindow.document.write(result);
				},
				complete: () => {
					$('#popup_load').fadeOut();
				}
			});
		});
		$('#btn_print_laporan_excel').click(function () {
			$('#btn_print_laporan_excel').html('Loading...');
			$('#btn_print_laporan_excel').prop('disabled', true);
			const formArr = $('#form_laporan').serializeArray();
			const payload = formArr.reduce((acc, it) => (acc[it.name] = it.value, acc), {});
			payload.print = 'excel';

			const path = $('#path').val();

			$.ajax({
				url: '<?php echo base_url(); ?>' + path,
				type: 'POST',
				data: JSON.stringify(payload),
				contentType: 'application/json',
				processData: false,
				xhrFields: { responseType: 'blob' },
				beforeSend: () => $('#popup_load').show(),
				success: function (blob, status, xhr) {
					const ct = xhr.getResponseHeader('Content-Type') || '';
					if (ct.indexOf('application/json') !== -1 || blob.type === 'application/json') {
						const reader = new FileReader();
						reader.onload = () => alert(reader.result || 'Terjadi kesalahan.');
						reader.readAsText(blob);
						return;
					}


					let filename = 'Laporan_SPP_Bulanan.xlsx';
					const dispo = xhr.getResponseHeader('Content-Disposition') || '';
					const match = dispo.match(/filename=\"?([^\";]+)\"?/i);
					if (match && match[1]) filename = match[1];


					const url = window.URL.createObjectURL(blob);
					const a = document.createElement('a');
					a.href = url;
					a.download = filename;
					document.body.appendChild(a);
					a.click();
					a.remove();
					window.URL.revokeObjectURL(url);

					$('#btn_print_laporan_excel').html('<i class="fa fa-file-excel me-1"></i> Excel');
					$('#btn_print_laporan_excel').prop('disabled', false);
				},
				error: function (xhr) {
					alert('Gagal mengunduh Excel: ' + (xhr.statusText || xhr.status));
				},
				complete: () => $('#popup_load').fadeOut()
			});
		});


		$(document).on('change', '#id-kelas-perkembangan', function () {
			var idKelas = $(this).val();
			var $siswa = $('#id-siswa-perkembangan');

			// Kosongkan pilihan lama setiap kali kelas berubah.
			$siswa.prop('disabled', true).html('<option value="">Memuat data siswa...</option>');

			if (!idKelas) {
				$siswa.html('<option value="">Pilih kelas terlebih dahulu</option>');
				return;
			}

			$.ajax({
				url: '<?= base_url('admin/laporan/siswa_by_kelas'); ?>',
				type: 'POST',
				dataType: 'JSON',
				data: { id_kelas: idKelas },
				success: function (response) {
					var options = '<option value="">Pilih Siswa</option>';
					var daftarSiswa = response.data || [];

					if (response.result === 'true' && daftarSiswa.length > 0) {
						daftarSiswa.forEach(function (item) {
							options += '<option value="' + item.id + '">' +
								$('<div>').text(item.nama_siswa || '-').html() +
							'</option>';
						});
					} else {
						options = '<option value="">Tidak ada siswa pada kelas ini</option>';
					}

					$siswa.html(options).prop('disabled', false).val('');
				},
				error: function () {
					$siswa
						.html('<option value="">Data siswa gagal dimuat</option>')
						.prop('disabled', true);
				}
			});
		});

	})

	function data_laporan() {
		var search = $("#cari-data-laporan").val();

		$.ajax({
			url: '<?= base_url('admin/laporan/laporan_result'); ?>',
			type: 'POST',
			data: {
				search
			},
			dataType: 'JSON',
			success: function (data) {

				var table = '';
				if (data.length == 0) {
					table += `
					<tr>
						<td colspan="7" style="text-align: center;">Tidak ada data</td>
					</tr>
				`;
				} else {
					data.forEach(function (item) {
						if (item.name == "Laporan") {
							return;
						}
						table += `
						<div class="panel"
					style="box-shadow: 0 1px 4px 0 rgba(0,0,0,.1); border: 0.2px solid #E3E3E3; border-radius: 5px; margin-bottom: 25px;">
					<div class="card-header border-bottom order-dashed d-flex align-items-center  ">
						<h3>${item.name}  </h3>
					</div>
					<div class="card-body">
						<button type="button" class="btn btn-primary" name="button" onclick="klik_laporan('${item.name}','${item.path}')"><i class="fa fa-bookmark me-1"></i> Buka
							Laporan</button>
						</div>
					</div>
						`;
					});
				}
				$('#data-laporan').html(table);
			}
		});
	}

	function klik_laporan(nama, path) {
		$("#printLaporan").modal('show');
		$('#myLargeModalLabel').html(nama);
		$('#path').val(path);

		let link = '<?php echo base_url(); ?>' + path;
		$('#form_laporan').attr('action', link);

		console.log(nama);
		if (nama == 'Laporan  SPP Bulanan') {
			$('#filter-data').hide();
			$('#form-hari').hide();
			$('#form-bulan').show();
			$('#form-tahun').hide();
			$('#form-spp-bulanan').show();
			$('#btn_print_laporan_excel').show();
			$('#form-siswa').hide();
			$('#beasiswa').hide();
			$('#paket').show();
			$('#kelas').show();
			$('#form-tahun-ajaran').hide();
			$('#form-kelas-tahun-ajaran').hide();
			$('#form-siswa-perkembangan').hide();
			$('#form-mapel').hide();
		} else if (nama == 'Laporan Beasiswa') {
			$('#filter-data').hide();
			$('#form-hari').hide();
			$('#form-bulan').show();
			$('#form-spp-bulanan').show();
			$('#beasiswa').hide();
			$('#paket').hide();
			$('#form-tahun').hide();
			$('#btn_print_laporan_excel').show();
			$('#form-siswa').hide();
			$('#kelas').hide();
			$('#form-tahun-ajaran').hide();
			$('#form-kelas-tahun-ajaran').hide();
			$('#form-siswa-perkembangan').hide();
			$('#form-mapel').hide();
		} else if (nama == 'Laporan Administrasi') {
			$('#filter-data').hide();
			$('#form-hari').hide();
			$('#form-bulan').show();
			$('#form-spp-bulanan').show();
			$('#beasiswa').hide();
			$('#paket').hide();
			$('#kelas').hide();
			$('#form-tahun').hide();
			$('#btn_print_laporan_excel').show();
			$('#form-siswa').hide();
			$('#form-tahun-ajaran').hide();
			$('#form-kelas-tahun-ajaran').hide();
			$('#form-siswa-perkembangan').hide();
			$('#form-mapel').hide();
		} else if (nama == 'Laporan Siswa') {
			$('#filter-data').hide();
			$('#form-hari').hide();
			$('#form-bulan').hide();
			$('#form-spp-bulanan').show();
			$('#beasiswa').hide();
			$('#paket').hide();
			$('#form-tahun').show();
			$('#btn_print_laporan_excel').show();
			$('#form-siswa').hide();
			$('#form-tahun-ajaran').hide();
			$('#form-kelas-tahun-ajaran').hide();
			$('#form-siswa-perkembangan').hide();
			$('#form-mapel').hide();
		} else if (nama == 'Laporan Riwayat Kelas') {
			$('#filter-data').hide();
			$('#form-hari').hide();
			$('#form-bulan').hide();
			$('#form-spp-bulanan').hide();
			$('#beasiswa').hide();
			$('#paket').hide();
			$('#form-tahun').show();
			$('#btn_print_laporan_excel').show();
			$('#form-siswa').show();
			$('#form-tahun-ajaran').hide();
			$('#form-kelas-tahun-ajaran').hide();
			$('#form-siswa-perkembangan').hide();
			$('#form-mapel').hide();
		} else if (nama == 'Laporan Aging Piutang') {
			$('#filter-data').hide();
			$('#form-hari').hide();
			$('#form-bulan').hide();
			$('#form-spp-bulanan').hide();
			$('#beasiswa').hide();
			$('#paket').hide();
			$('#form-tahun').hide();
			$('#btn_print_laporan_excel').show();
			$('#form-siswa').hide();
			$('#form-tahun-ajaran').hide();
			$('#form-kelas-tahun-ajaran').hide();
			$('#form-siswa-perkembangan').hide();
			$('#form-mapel').hide();
		} else if (nama == 'Laporan Pertemuan Tentor') {
			$('#filter-data').hide();
			$('#form-hari').hide();
			$('#form-pegawai').show();
			$('#form-bulan').show();
			$('#form-spp-bulanan').hide();
			$('#beasiswa').hide();
			$('#paket').hide();
			$('#form-tahun').hide();
			$('#btn_print_laporan_excel').show();
			$('#form-siswa').hide();
			$('#form-tahun-ajaran').hide();
			$('#form-kelas-tahun-ajaran').hide();
			$('#form-siswa-perkembangan').hide();
			$('#form-mapel').hide();
		}else if (nama == 'Laporan Perkembangan Belajar') {
			$('#filter-data').hide();
			$('#form-hari').hide();
			$('#form-pegawai').hide();
			$('#form-bulan').hide();
			$('#form-spp-bulanan').hide();
			$('#beasiswa').hide();
			$('#paket').hide();
			$('#form-tahun-ajaran').show();
			$('#form-kelas-tahun-ajaran').show();
			$('#form-siswa-perkembangan').show();
			$('#form-mapel').show();

			// Reset filter agar siswa dari kelas sebelumnya tidak tetap terpilih.
			$('#id-kelas-perkembangan').val('');
			$('#id-siswa-perkembangan').prop('disabled', true).html('<option value="">Pilih kelas terlebih dahulu</option>');
			$('#form-tahun').hide();
			$('#btn_print_laporan_excel').hide();
			$('#form-siswa').hide();
		} else {
			$('#form-laporan-presensi-siswa').hide();
			$('#form-jurnal-guru-kelas').hide();
			$('#form-jurnal-guru-tanggal').hide();
		}

		$('#laporan_presensi_siswa_filter').change(function () {
			var val = this.value;
			if (val == 1) {
				$("#laporan_presensi_siswa_filter_bulan").hide();
			} else {
				$("#laporan_presensi_siswa_filter_bulan").show();
			}
		});
	}

	function data_jurnal_pegawai() {
		const select = document.querySelector('select[name="id_pegawai"]');
		const selectedOption = select.options[select.selectedIndex];
		const namaPegawai = selectedOption.getAttribute('data-label');
		const jabatan = selectedOption.getAttribute('data-jabatan');
		$('#nama_pegawai').text(namaPegawai);
		$('#jabatan').text(jabatan);
		var filter = $('input[name="filter"]:checked').val();

		var id_pegawai = $('select[name="id_pegawai"]').val();
		var dari_tanggal = $('input[name="dari_tanggal"]').val();
		var sampai_tanggal = $('input[name="sampai_tanggal"]').val();
		var bulan = $('select[name="filter_bulan"]').val();
		var tahun = $('select[name="filter_tahun"]').val();
		var single_filter_tahun = $('select[name="single_filter_tahun"]').val();
		var dataPost = {
			dari_tanggal,
			sampai_tanggal,
			id_pegawai,
			filter,
			bulan,
			tahun,
			single_filter_tahun
		};

		$.ajax({
			url: '<?= base_url('admin/laporan/laporan_jurnal_pegawai'); ?>',
			type: 'POST',
			data: dataPost,
			dataType: 'JSON',
			success: function (data) {

				var no = 1;
				var table = '';
				if (data.length == 0) {

				} else {
					data.forEach(function (item) {

						table += `
				  <div class="excel-row">
					<div data-label="No">${no++}</div>
					<div data-label="Tanggal">${item.tanggal}</div>
					<div data-label="Kegiatan">${item.kegiatan}</div>
					<div data-label="Semester">${item.semester}</div>
					<div data-label="Periode">${item.periode}</div>
				</div>
				`;
					});
				}

				$('#data_jurnal_pegawai').html(table);

			}
		});
	}
	function data_jurnal_guru_kelas() {
		const select = document.querySelector('select[name="id_kelas"]');
		const selectedOption = select.options[select.selectedIndex];
		const kelas = selectedOption.getAttribute('data-kelas');
		$('#kelas').text(kelas);

		const selectTahun = document.querySelector('select[name="id_periode_jurnal"]');
		const selectedOptionTahun = selectTahun.options[selectTahun.selectedIndex];
		const jurnal_periode = selectedOptionTahun.getAttribute('data-tahun');

		$('#periode').text(jurnal_periode);

		var filter = $('input[name="filter"]:checked').val();

		var id_kelas = $('select[name="id_kelas"]').val();
		var semester = $('select[name="semester_jurnal"]').val();
		$('#semester').text(semester);

		var periode = $('select[name="id_periode_jurnal"]').val();

		var dari_tanggal = $('input[name="dari_tanggal"]').val();
		var sampai_tanggal = $('input[name="sampai_tanggal"]').val();
		var bulan = $('select[name="filter_bulan"]').val();
		var tahun = $('select[name="filter_tahun"]').val();
		var single_filter_tahun = $('select[name="single_filter_tahun"]').val();
		var dataPost = {
			dari_tanggal,
			sampai_tanggal,
			bulan,
			tahun,
			single_filter_tahun,
			filter,
			id_kelas,
			semester,
			periode
		};

		$.ajax({
			url: '<?= base_url('admin/laporan/laporan_jurnal_guru_kelas'); ?>',
			type: 'POST',
			data: dataPost,
			dataType: 'JSON',
			success: function (data) {

				var no = 1;
				var table = '';


				if (Object.keys(data).length === 0) {
					table = '<tr><td colspan="8" class="text-center">Tidak ada data</td></tr>';
				} else {
					Object.keys(data).forEach(function (namaGuru) {
						let mapelList = data[namaGuru];
						let totalBaris = 0;

						Object.keys(mapelList).forEach(mapel => {
							totalBaris += mapelList[mapel].length;
						});

						let guruRowspanAdded = false;

						Object.keys(mapelList).forEach(function (mapel) {
							let jadwalList = mapelList[mapel];

							jadwalList.forEach(function (item, index) {

								var selisih = hitungSelisihHariDMY(item.tanggal, item.tanggal_input);


								table += '<tr>';

								// Tambah kolom No & Nama Guru hanya sekali
								if (!guruRowspanAdded) {
									table += `<td rowspan="${totalBaris}">${no++}</td>`;
									table += `<td rowspan="${totalBaris}">${namaGuru}</td>`;
									guruRowspanAdded = true;
								}

								table += `<td>${mapel}</td>`;
								table += `<td>${item.nama_kelas}</td>`;
								table += `<td>${item.jam_mulai_pelajaran} - ${item.jam_selesai_pelajaran}</td>`;
								table += `<td>${item.kegiatan}</td>`;
								table += `<td>${item.tema}</td>`;
								table += `<td>${item.tanggal} ${selisih}</td>`;

								table += '</tr>';
							});
						});
					});
				}

				$('#data_jurnal_guru_kelas tbody').html(table);

			}
		});
	}


	function data_izin_pegawai() {

		var filter = $('input[name="filter"]:checked').val();
		var dari_tanggal = $('input[name="dari_tanggal"]').val();
		var sampai_tanggal = $('input[name="sampai_tanggal"]').val();
		var bulan = $('select[name="filter_bulan"]').val();
		var tahun = $('select[name="filter_tahun"]').val();
		var single_filter_tahun = $('select[name="single_filter_tahun"]').val();
		var dataPost = {
			dari_tanggal,
			sampai_tanggal,
			bulan,
			tahun,
			single_filter_tahun,
			filter,
		};

		$.ajax({
			url: '<?= base_url('admin/laporan/laporan_izin_pegawai'); ?>',
			type: 'POST',
			data: dataPost,
			dataType: 'JSON',
			success: function (data) {

				var no = 1;
				var table = '';
				if (Object.keys(data).length === 0) {
					table = '<tr><td colspan="8" class="text-center">Tidak ada data</td></tr>';
				} else {

					Object.keys(data).forEach(function (tanggal) {
						let jadwalList = data[tanggal];
						let totalBaris = jadwalList.length;
						let guruRowspanAdded = false;

						jadwalList.forEach(function (item, index) {
							table += '<tr>';

							if (!guruRowspanAdded) {
								table += `<td rowspan="${totalBaris}">${no++}</td>`;
								table += `<td rowspan="${totalBaris}">${tanggal}</td>`;
								guruRowspanAdded = true;
							}

							table += `<td>${item.nama_pegawai}</td>`;
							table += `<td>${item.keterangan}</td>`;
							table += `<td>${item.alasan_tidak_hadir}</td>`;
							table += '</tr>';
						});
					});
				}

				$('#data_izin_pegawai tbody').html(table);

			}
		});
	}
</script>