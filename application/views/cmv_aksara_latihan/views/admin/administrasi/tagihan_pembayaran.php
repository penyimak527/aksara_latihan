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
	}

	.kv label {
		color: var(--muted);
		font-size: .82rem;
		margin-bottom: 4px;
	}

	.kv .value {
		font-weight: 600;
	}

	.total-amount {
		font-size: 1rem;
		font-weight: 800;
		color: #0c3b43;
	}
</style>
<div class="row">
	<div class="col-md-2">
		<div class="mb-3">
			<select id="filter_periode_tahun" name="periode_tahun" class="form-control">
				<option value="">Pilih Tahun</option>
				<?php
				$now = date('Y');
				$periode_tahun_selected = '';
				for ($a = 2025; $a <= $now; $a++) {
					echo '<option value="' . $a . '">' . $a . '</option>';
				}
				?>
			</select>
		</div>
	</div>
	<div class="col-md-2">
		<div class="mb-3">
			<select id="filter_periode_bulan" name="periode_bulan" class="form-control">
				<option value="">Pilih Bulan</option>
				<?php
				$bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
				$jlh_bln = count($bulan);
				$no = 0;
				for ($c = 0; $c < $jlh_bln; $c += 1) {
					$no++;
					$no_pas = sprintf("%02s", $no);
					echo '<option value="' . $no_pas . '"> ' . $bulan[$c] . '</option>';
				}
				?>
			</select>
		</div>
	</div>
	<div class="col-md-2">
		<div class="mb-3">
			<select id="filter_kelas" name="periode_tahun" class="form-control">
			</select>
		</div>
	</div>
	<div class="col-md-2">
		<div class="mb-3">
			<select id="filter_jenis_administrasi" name="periode_tahun" class="form-control">
				<option value="">Pilih Jenis</option>
				<option value="Reguler">Reguler</option>
				<option value="Private">Private</option>
			</select>
		</div>
	</div>
	<div class="col-md-3">
		<div class="mb-3">
			<button type="button" class="btn btn-sm btn-primary" onclick="tagihan_pembayaran_result()">
				<i class="ri-search-line"></i></button>
		</div>
	</div>
</div>
<div class="card">
	<div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
		<h4 class="header-title">Data <?= $title; ?></h4>
	</div>
	<div class="card-body">
		<div class="alert alert-primary alert-dismissible fade show" role="alert">
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			Pilih tahun dan bulan terlebih dahulu ...
		</div>
		<div class="div_tagihan_pembayaran">
			<div class="row">
				<div class="col-md-3">
					<div class="mb-3">
						<div class="input-group">
							<input type="text" class="form-control" id="filter_cari" placeholder="Cari ..."
								aria-describedby="inputGroupPrepend" onkeyup="tagihan_pembayaran_result()">
							<span class="input-group-text bg-primary text-white" id="inputGroupPrepend"><i
									class="fas fa-search"></i></span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="mb-3">
						<div class="input-group">
							<select id="filter_status_pembayaran" class="form-control">
								<option value="">Pilih Status Pembayaran</option>
								<option value="Perlu Ditagih">Perlu Ditagih</option>
								<option value="Sudah Mengirim Tagihan">Belum</option>
								<option value="Perlu Dicek Pembayaran nya">Sudah Bayar</option>
								<option value="Sudah Lunas">Lunas</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div id="data_tagihan_pembayaran"></div>
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
</div>

<div class="modal fade" id="tagih" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel">Tagih <?= $title; ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-tagih">
					<ul class="nav nav-tabs nav-bordered nav-justified">
						<li class="nav-item">
							<a href="#tagihan-b2" data-bs-toggle="tab" aria-expanded="false"
								class="nav-link active">Tagihan</a>
						</li>
						<li class="nav-item">
							<a href="#jurnal-b2" data-bs-toggle="tab" aria-expanded="true" class="nav-link">Jurnal</a>
						</li>
					</ul>
					<div class="tab-content pt-2">
						<div class="tab-pane active" id="tagihan-b2">
							<div class="row">
								<input type="hidden" name="id_pendaftaran_paket">
								<input type="hidden" name="id_siswa">
								<input type="hidden" name="id_beasiswa">
								<input type="hidden" name="periode_bulan">
								<input type="hidden" name="periode_tahun">
								<input type="hidden" name="hp_wali">
								<div class="col-md-12">
									<div class="mb-2">
										<label for="simpleinput" class="form-label">Nama Siswa</label>
										<input readonly type="text" name="nama_siswa" class="form-control">
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-2">
										<label for="simpleinput" class="form-label">Kelas</label>
										<input readonly type="text" name="nama_kelas" class="form-control">
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-2">
										<label for="simpleinput" class="form-label">Paket</label>
										<input readonly type="text" name="nama_paket" class="form-control">
									</div>
								</div>
								<div class="col-md-12">
									<div class="mb-2">
										<label for="simpleinput" class="form-label">Pertemuan</label>
										<input type="text" name="pertemuan" class="form-control"
											onkeyup="FormatCurrency(this); hitung_pertemuan();">
									</div>
								</div>
								<input readonly type="hidden" name="tipe_beasiswa" class="form-control">
								<input readonly type="hidden" name="nilai_beasiswa" class="form-control">
								<input readonly type="hidden" name="harga_pertemuan" class="form-control">
								<input readonly type="hidden" name="total_harga_pertemuan" class="form-control">
								<input readonly type="hidden" name="kas" class="form-control">
								<input readonly type="hidden" name="total_kas" class="form-control">
								<input readonly type="hidden" name="diskon" class="form-control">
								<input readonly type="hidden" name="total_akhir" class="form-control">
								<input readonly type="hidden" name="hitung_nilai_beasiswa" class="form-control">
								<input readonly type="hidden" id="nilai_edit" name="nilai" class="form-control">
								<div class="col-12 col-md-12">
									<div class="total-card mt-2">
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="label">Harga Pertemuan</span>
											<span class="fw-semibold edit_span_harga_pertemuan"></span>
										</div>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="label">Jumlah Pertemuan</span>
											<span class="fw-semibold edit_span_jumlah_pertemuan"></span>
										</div>
										<hr class="my-2">
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="label">Sub Total Harga Pertemuan</span>
											<span class="fw-semibold edit_span_sub_total_pertemuan"></span>
										</div>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="label">Diskon</span>
											<span class="fw-semibold edit_span_diskon"></span>
										</div>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="label">Beasiswa</span>
											<span class="fw-semibold edit_span_beasiswa"></span>
										</div>
										<hr class="my-2">
										<div class="d-flex justify-content-between align-items-center">
											<span class="label fw-semibold">Total</span>
											<span class="total-amount edit_span_total_akhir"></span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="jurnal-b2">
							<div id="form_tagih_data_jurnal_siswa"></div>
						</div>
					</div>
				</form>
			</div>
			<div class=" modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success" id="btn-kirim-tagihan"><i class="ri-whatsapp-line"></i>
					Kirim Tagihan</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="bukti-pembayaran" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel">Bukti <?= $title; ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-bukti-pembayaran">
					<div class="row">
						<input type="hidden" name="id_pembayaran">
						<input type="hidden" name="id_pendaftaran_paket">
						<input type="hidden" name="periode_bulan">
						<input type="hidden" name="periode_tahun">
						<input type="hidden" name="total_harga_pertemuan">
						<input type="hidden" name="src_gambar">
						<div class="col-md-12">
							<div class="mb-2">
								<label for="simpleinput" class="form-label">Tanggal & Waktu Bayar</label>
								<input readonly type="text" name="tanggal_waktu_bayar" class="form-control">
							</div>
							<div class="mb-2 text-center">
								<img id="bukti_pembayaran" alt="image" class="img-fluid img-thumbnail" width="400" />
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class=" modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-danger" id="btn-batalkan-pembayaran">Batalkan</button>
				<button type="button" class="btn btn-primary" id="btn-konfirmasi-pembayaran">Konfirmasi
					Pembayaran</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="proses-pembayaran" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel">Proses <?= $title; ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-proses-pembayaran">
					<div class="row">
						<input type="hidden" name="id_pembayaran">
						<input type="hidden" name="id_pendaftaran_paket">
						<input type="hidden" name="periode_bulan">
						<input type="hidden" name="periode_tahun">
						<div class="col-md-12">
							<div class="mb-2">
								<label for="simpleinput" class="form-label">Total Tagihan</label>
								<div class="input-group">
									<span class="input-group-text">Rp</span>
									<input readonly type="text" name="total_harga_pertemuan" class="form-control">
								</div>
							</div>
							<div class="mb-2">
								<label for="simpleinput" class="form-label">Metode Pembayaran</label>
								<select name="metode_pembayaran" class="form-control"
									onchange="pilih_metode_pembayaran();">
									<option value="Cash">Cash</option>
									<option value="Transfer">Transfer</option>
									<option value="Qris">Qris</option>
								</select>
							</div>
							<div class="div_form_bukti_pembayaran" style="display: none;">
								<div class="mb-2">
									<label for="simpleinput" class="form-label">Bukti Pembayaran</label>
									<input type="file" name="bukti_pembayaran" class="form-control">
								</div>
							</div>
							<div class="mb-2">
								<label for="simpleinput" class="form-label">Nominal Bayar</label>
								<div class="input-group">
									<span class="input-group-text">Rp</span>
									<input type="text" name="nominal_bayar" class="form-control"
										onkeyup="FormatCurrency(this); hitung_jumlah_bayar();">
								</div>
							</div>
							<div class="mb-2">
								<label for="simpleinput" class="form-label">Kembali</label>
								<div class="input-group">
									<span class="input-group-text">Rp</span>
									<input readonly type="text" name="kembali" value="0" class="form-control">
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class=" modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-primary" id="btn-proses-pembayaran">Proses</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="edit-pembayaran" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel">Edit <?= $title; ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-edit-pembayaran">
					<div class="tab-content pt-2">
						<div class="tab-pane active" id="tagihan-b2">
							<div class="row">
								<input type="hidden" id="id_pembayaran" name="id_pembayaran">
								<div class="col-md-12">
									<div class="mb-2">
										<label for="simpleinput" class="form-label">Nama Siswa</label>
										<input readonly type="text" name="nama_siswa" class="form-control">
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-2">
										<label for="simpleinput" class="form-label">Kelas</label>
										<input readonly type="text" name="nama_kelas" class="form-control">
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-2">
										<label for="simpleinput" class="form-label">Paket</label>
										<input readonly type="text" name="nama_paket" class="form-control">
									</div>
								</div>
								<div class="col-md-12">
									<div class="mb-2">
										<label for="simpleinput" class="form-label">Pertemuan</label>
										<input type="text" name="pertemuan" class="form-control"
											onkeyup="FormatCurrency(this); hitung_pertemuan_edit();">
									</div>
								</div>
								<input readonly type="hidden" name="tipe_beasiswa" class="form-control">
								<input readonly type="hidden" name="nilai_beasiswa" class="form-control">
								<input readonly type="hidden" name="harga_pertemuan" class="form-control">
								<input readonly type="hidden" name="total_harga_pertemuan" class="form-control">
								<input readonly type="hidden" name="kas" class="form-control">
								<input readonly type="hidden" name="total_kas" class="form-control">
								<input readonly type="hidden" name="diskon" class="form-control">
								<input readonly type="hidden" name="total_akhir" class="form-control">
								<input readonly type="hidden" name="hitung_nilai_beasiswa" class="form-control">
								<div class="col-12 col-md-12">
									<div class="total-card mt-2">
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="label">Harga Pertemuan</span>
											<span class="fw-semibold edit_span_harga_pertemuan_edit"></span>
										</div>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="label">Jumlah Pertemuan</span>
											<span class="fw-semibold edit_span_jumlah_pertemuan_edit"></span>
										</div>
										<hr class="my-2">
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="label">Sub Total Harga Pertemuan</span>
											<span class="fw-semibold edit_span_sub_total_pertemuan_edit"></span>
										</div>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="label">Diskon</span>
											<span class="fw-semibold edit_span_diskon_edit"></span>
										</div>
										<div class="d-flex justify-content-between align-items-center mb-2">
											<span class="label">Beasiswa</span>
											<span class="fw-semibold edit_span_beasiswa_edit"></span>
										</div>
										<hr class="my-2">
										<div class="d-flex justify-content-between align-items-center">
											<span class="label fw-semibold">Total</span>
											<span class="total-amount edit_span_total_akhir_edit"></span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- <div class="tab-pane" id="jurnal-b2">
							<div id="form_tagih_data_jurnal_siswa"></div>
						</div> -->
					</div>
				</form>
			</div>
			<div class=" modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-primary" id="btn-edit-pembayaran">
					Update Tagihan</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="detail-pembayaran" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel">Detail <?= $title; ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row mb-2">
					<div class="col-12 col-md-4 kv">
						<label>Siswa</label>
						<div class="value span_siswa"></div>
					</div>
					<div class="col-12 col-md-4 kv">
						<label>Paket</label>
						<div class="value span_paket"></div>
					</div>
					<div class="col-12 col-md-4 kv">
						<label>Pembayaran</label>
						<div class="value span_pembayaran"></div>
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-md-12">
						<p><a class="btn btn-secondary btn-sm waves-effect waves-light" data-bs-toggle="collapse"
								href="#bukti_pembayaran" aria-expanded="false" aria-controls="bukti_pembayaran"><i
									class="ri-wallet-3-line"></i></a></p>
						<div class="collapse" id="bukti_pembayaran">
							<div class="card border border-secondary">
								<div class="card-body text-secondary span_bukti_pembayaran text-center">
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-12">
						<div class="total-card mt-2">
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="label">Harga Pertemuan</span>
								<span class="fw-semibold span_harga_pertemuan"></span>
							</div>
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="label">Jumlah Pertemuan</span>
								<span class="fw-semibold span_jumlah_pertemuan"></span>
							</div>
							<hr class="my-2">
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="label">Sub Total Harga Pertemuan</span>
								<span class="fw-semibold span_sub_total_pertemuan"></span>
							</div>
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="label">Diskon</span>
								<span class="fw-semibold span_diskon"></span>
							</div>
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="label">Beasiswa</span>
								<span class="fw-semibold span_beasiswa"></span>
							</div>
							<hr class="my-2">
							<div class="d-flex justify-content-between align-items-center">
								<span class="label fw-semibold">Total</span>
								<span class="total-amount span_total_akhir"></span>
							</div>
						</div>
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
		kelas();

		$("#btn-kirim-tagihan").click(function () {
			$('#btn-kirim-tagihan').prop('disabled', true).html('Loading...');

			let id_pendaftaran_paket = $('#form-tagih input[name="id_pendaftaran_paket"]').val();
			let id_siswa = $('#form-tagih input[name="id_siswa"]').val();
			let nama_siswa = $('#form-tagih input[name="nama_siswa"]').val();
			let jenjang_kelas = $('#form-tagih input[name="nama_kelas"]').val();
			let hp_wali = $('#form-tagih input[name="hp_wali"]').val();

			let toNumber = (s) => {
				if (!s) return 0;
				let onlyDigits = String(s).replace(/[^\d]/g, '');
				return Number(onlyDigits || 0);
			};

			let total_harga_pertemuan = toNumber($('.edit_span_total_akhir').text().split('.').join(''));


			let beasiswa = toNumber($('#form-tagih input[name="nilai_beasiswa"]').val());

			let total_bayar = total_harga_pertemuan;

			let rupiah = (n) => new Intl.NumberFormat('id-ID').format(n);

			let date = new Date();
			let tahun = $('#filter_periode_tahun').val();
			let bulan = $('#filter_periode_bulan').val();
			let month = month_to_abjad_month(bulan);

			let toBase64Url = s => btoa(s).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
			let token = toBase64Url(id_siswa);
			let link_tagihan = `<?= base_url('tagihan_online/c/') ?>` + token;
			let no_telepon = hp_wali;

			// let barisBeasiswa = beasiswa > 0 ? `%0ABeasiswa%3A%20Rp.%20${rupiah(beasiswa)}` : '';

			let textRaw =
				`Kepada Bapak / Ibu Wali Murid,\n\n` +
				`Izin mengingatkan, untuk tagihan bimbel di Aksara Course\n` +
				`Nama Siswa: ${nama_siswa}\n` +
				`Kelas: ${jenjang_kelas}\n` +
				`Bulan: ${month} ${tahun}\n` +
				`Total Biaya: Rp. ${rupiah(total_bayar)}\n\n` +
				`Best Regards,\nAksara Course\nCek Nota Tagihan:\n${link_tagihan}`;

			let link = `https://api.whatsapp.com/send?phone=${no_telepon}&text=${encodeURIComponent(textRaw)}`;

			let form = $("#form-tagih");
			let data = form.serialize();

			setTimeout(function () {
				$.ajax({
					url: '<?= base_url('admin/administrasi/tagihan_pembayaran/generate_tagihan'); ?>',
					type: 'POST',
					data: data,
					dataType: 'json',
					success: function (data) {
						$("#tagih").modal('hide');
						if (data.result == 'true') {
							window.open(link);

							Swal.fire({
								title: "Berhasil Kirim",
								icon: "success",
								confirmButtonText: "Ok"
							}).then((result) => {
								if (result.isConfirmed) {
									tagihan_pembayaran_result();
								}
							})
						} else {
							Swal.fire({ icon: 'error', title: 'Gagal', text: 'Data gagal disimpan' });
						}
						$('#btn-kirim-tagihan').prop('disabled', false).html(`<i class="ri-whatsapp-line"></i>
					Kirim Tagihan`);
					}
				});
			}, 1500)
		});

		$("#btn-batalkan-pembayaran").click(function () {
			$('#btn-batalkan-pembayaran').prop('disabled', true).html(`Loading...`);
			var form = $("#form-bukti-pembayaran");
			var data = form.serialize();
			$.ajax({
				url: '<?= base_url('admin/administrasi/tagihan_pembayaran/batalkan_pembayaran'); ?>',
				type: 'POST',
				data: data,
				success: function (data) {
					$("#bukti-pembayaran").modal('hide');

					if (data.result == 'true') {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: 'Berhasil batalkan pembayaran',
						})
						tagihan_pembayaran_result();
					} else if (data.result == 'false') {
						Swal.fire({
							icon: 'danger',
							title: 'Gagal',
							text: 'Gagal batalkan pembayaran',
						})
					}
					$('#btn-batalkan-pembayaran').prop('disabled', false).html(`Batalkan`);
				}
			})
		})

		$("#btn-proses-pembayaran").click(function () {
			$('#btn-proses-pembayaran').prop('disabled', true).html(`Loading...`);
			let maxMB = 2;
			let form = $("#form-proses-pembayaran")[0];

			let metode_pembayaran = $(`#form-proses-pembayaran select[name="metode_pembayaran"]`).val();
			if (metode_pembayaran != 'Cash') {
				let fileInput = $(form).find('input[type="file"][name="bukti_pembayaran"]')[0];
				let file = fileInput ? fileInput.files[0] : null;

				let validasi = validasi_bukti_pembayaran(file, maxMB);
				if (!validasi.ok) {
					alert(validasi.msg);
					return;
				}
			}

			let formData = new FormData(form);

			$.ajax({
				url: '<?= base_url('admin/administrasi/tagihan_pembayaran/proses_pembayaran'); ?>',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				dataType: 'json',
				success: function (data) {
					$("#proses-pembayaran").modal('hide');

					if (data.result == 'true') {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: 'Data berhasil disimpan',
						})
						tagihan_pembayaran_result();
						$("#form-proses-pembayaran")[0].reset();
					} else if (data.result == 'false') {
						Swal.fire({
							icon: 'danger',
							title: 'Gagal',
							text: 'Data gagal disimpan',
						})
						$("#form-proses-pembayaran")[0].reset();
					}
					$('#btn-proses-pembayaran').prop('disabled', false).html(`Simpan`);
				}
			})
		})

		$("#btn-konfirmasi-pembayaran").click(function () {
			$('#btn-konfirmasi-pembayaran').prop('disabled', true).html(`Loading... `);
			var form = $("#form-bukti-pembayaran");
			var data = form.serialize();
			$.ajax({
				url: '<?= base_url('admin/administrasi/tagihan_pembayaran/konfirmasi_pembayaran'); ?>',
				type: 'POST',
				data: data,
				success: function (data) {
					$("#bukti-pembayaran").modal('hide');

					if (data.result == 'true') {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: 'Berhasil konfirmasi pembayaran',
						})
						tagihan_pembayaran_result();
					} else if (data.result == 'false') {
						Swal.fire({
							icon: 'danger',
							title: 'Gagal',
							text: 'Gagal konfirmasi pembayaran',
						})
					}
					$('#btn-konfirmasi-pembayaran').prop('disabled', false).html(`Konfirmasi Pembayaran`);
				}
			})
		})
		$("#btn-edit-pembayaran").click(function () {
			$('#btn-edit-pembayaran').prop('disabled', true).html(`Loading...`);
			var form = $("#form-edit-pembayaran");
			var data = form.serialize();
			$.ajax({
				url: '<?= base_url('admin/administrasi/tagihan_pembayaran/edit_pembayaran'); ?>',
				type: 'POST',
				data: data,
				success: function (data) {
					$("#edit-pembayaran").modal('hide');

					if (data.result == 'true') {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: 'Berhasil update pembayaran',
						})
						tagihan_pembayaran_result();
					} else if (data.result == 'false') {
						Swal.fire({
							icon: 'danger',
							title: 'Gagal',
							text: 'Gagal update pembayaran',
						})
					}
					$('#btn-edit-pembayaran').prop('disabled', false).html(`Update Tagihan`);
				}
			})
		})

		$('#dt-length-0').on('change', function () {
			const jumlah = parseInt($(this).val());
			paging($('#data_tagihan_pembayaran .card-mapel'), jumlah);
		});
	})

	function month_to_abjad_month(month) {
		month = month - 1
		let bulan = ''
		switch (month) {
			case 0:
				bulan = "Januari";
				break;
			case 1:
				bulan = "Februari";
				break;
			case 2:
				bulan = "Maret";
				break;
			case 3:
				bulan = "April";
				break;
			case 4:
				bulan = "Mei";
				break;
			case 5:
				bulan = "Juni";
				break;
			case 6:
				bulan = "Juli";
				break;
			case 7:
				bulan = "Agustus";
				break;
			case 8:
				bulan = "September";
				break;
			case 9:
				bulan = "Oktober";
				break;
			case 10:
				bulan = "November";
				break;
			case 11:
				bulan = "Desember";
				break;
		}

		return bulan
	}

	function tagihan_pembayaran_result() {
		let periode_tahun = $('#filter_periode_tahun').val()
		let periode_bulan = $('#filter_periode_bulan').val()
		let kelas = $('#filter_kelas').val()
		let jenis_administrasi = $('#filter_jenis_administrasi').val()
		let cari = $('#filter_cari').val()

		$.ajax({
			url: '<?= base_url('admin/administrasi/tagihan_pembayaran/tagihan_pembayaran_result'); ?>',
			type: 'POST',
			data: {
				periode_tahun,
				periode_bulan,
				kelas,
				jenis_administrasi
			},
			dataType: 'JSON',
			success: function (data) {
				$('.alert').alert('close')
				$('.div_tagihan_pembayaran').show()
				$('#tagihan_pembayaran').empty();

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

						let status_tagih = 'Perlu Ditagih'
						let bg_tagih = 'danger'
						let button = `<button type="button" class="btn btn-outline-warning w-50" onclick="tagih('${detail}')">
														<i class="ri-file-list-3-line"></i>
													</button>`
						if (item.perlu_ditagih == '0') {
							if (item.status == 'Belum') {
								status_tagih = 'Sudah Mengirim Tagihan'
								bg_tagih = 'warning'
								button = `
									<button type="button" class="btn btn-outline-warning w-50" onclick="edit_pembayaran('${detail}')">
														<i class="ri-edit-fill"></i>
													</button>
								<button type="button" class="btn btn-outline-info w-50" onclick="proses_pembayaran('${detail}')">
														<i class="ri-task-fill"></i>
													</button>
													<button type="button" class="btn btn-outline-success w-50" onclick="kirim_tagihan_online('${detail}')">
														<i class="ri-whatsapp-line"></i>
													</button>`
							} else if (item.status == 'Sudah Bayar') {
								status_tagih = 'Perlu Dicek Pembayaran nya'
								bg_tagih = 'info'

								button = `<button type="button" class="btn btn-outline-info w-50" onclick="bukti_pembayaran('${detail}')">
														<i class="ri-wallet-3-line"></i>
													</button>`
							} else if (item.status == 'Lunas') {
								status_tagih = 'Sudah Lunas'
								bg_tagih = 'success'
								button = `<button type="button" class="btn btn-outline-success w-50" onclick="detail_pembayaran('${detail}')">
														<i class="ri-eye-line"></i>
													</button>`
							}
						}

						table += `<div class="card-mapel" data-name="${(item.nama_siswa || '').replace(/"/g, '&quot;')}  data-status="${item.status}" data-status-tagih="${status_tagih}">
									<p class="keterangan-hari">
										  <span>Status: <span class="badge bg-${bg_tagih}">${status_tagih}</span> </span>
									  </p>
									  <div class="keterangan-mapel">
										  <div class="keterangan-mapel-kiri">
											  <h5 class="judul-mapel" style="margin:0; margin-top: 8px;"><span class="no-urut">${no++}</span>. ${item.nama_siswa}</h5>
														<p style="margin: 0; padding: 0; font-size: 12px; margin-bottom: 8px;">
														<b>Kelas:</b> ${item.nama_jenjang} - ${item.nama_kelas} , <b>Paket:</b> ${item.nama_paket}
														</p>
										  </div>
										  <div class="keterangan-mapel-kanan">
											  <div class="d-flex justify-content-center gap-2">
														${button}
											  </div>
										  </div>
									  </div>
								  </div>
								  `;
					});
				}
				$('#data_tagihan_pembayaran').html(table);

				var $cards = $('#data_tagihan_pembayaran .card-mapel');

				function filter_pembayaran() {
					var q = ($('#filter_cari').val() || '').trim().toLowerCase();
					var s = ($('#filter_status_pembayaran').val() || '').trim().toLowerCase();

					var $wrap = $('#data_tagihan_pembayaran');
					var $cards = $wrap.find('.card-mapel');
					var visibleCount = 0;

					$cards.each(function () {
						var $card = $(this);
						var name = ($card.data('name') || '').toString().toLowerCase();
						var stRaw = ($card.data('status') || '').toString().toLowerCase();
						var stTag = ($card.data('status-tagih') || '').toString().toLowerCase();

						var matchName = !q || name.includes(q);
						var matchStatus = !s || s === stRaw || s === stTag;

						var show = matchName && matchStatus;
						$card.toggle(show);
						if (show) visibleCount++;
					});

					let visible = $('#data_tagihan_pembayaran .card-mapel:visible');
					visible.each(function (i) {
						$(this).find('.no-urut').text(i + 1);
					});

					var jumlah_awal = parseInt($('#dt-length-0').val(), 10) || $cards.length;
					paging($('#data_tagihan_pembayaran .card-mapel:visible'), jumlah_awal);
				}

				filter_pembayaran();

				let t;
				$('#filter_cari').off('input.localfilter').on('input.localfilter', function () {
					clearTimeout(t);
					t = setTimeout(filter_pembayaran, 150);
				});
				$('#filter_status_pembayaran').off('change.localfilter').on('change.localfilter', filter_pembayaran)
			}
		});
	}

	function detail_pembayaran(detail) {
		$('#detail-pembayaran').modal('show');
		var item = JSON.parse(decodeURIComponent(escape(atob(detail))));

		let hitung = parseFloat(item.jumlah_meet) * parseFloat(item.harga_pertemuan)

		$('.span_siswa').html(`${item.nama_siswa}<br>${item.nama_jenjang} ${item.nama_kelas}`)
		$('.span_paket').html(`${item.tanggal} ${item.waktu}<br>${item.nama_paket}`)
		$('.span_pembayaran').html(`${item.tanggal_bayar} ${item.waktu_bayar}<br>${item.metode_pembayaran}`)
		$('.span_harga_pertemuan').html(`${NumberToMoney(item.harga_pertemuan)}`)
		$('.span_jumlah_pertemuan').html(`x ${NumberToMoney(item.jumlah_meet)}`)
		$('.span_sub_total_pertemuan').html(`${NumberToMoney(hitung)}`)
		$('.span_beasiswa').html(NumberToMoney(item.nilai_beasiswa))
		$('.span_diskon').html('(' + NumberToMoney(item.diskon) + ' x ' + NumberToMoney(item.jumlah_meet) + ')  ' + NumberToMoney(item.diskon * item.jumlah_meet))

		$('.span_total_akhir').html(`${NumberToMoney(item.total_akhir)}`)

		let link_bukti_pembayaran = `<?php echo base_url(); ?>${item.bukti_pembayaran}`
		if (item.metode_pembayaran == 'Cash') {
			$('.span_bukti_pembayaran').html('Tidak ada bukti pembayaran');
		} else {
			$('.span_bukti_pembayaran').html('<img src="' + link_bukti_pembayaran + '" width="400" class="img-fluid img-thumbnail">');
		}
	}

	function kirim_tagihan_online(detail) {
		var item = JSON.parse(atob(detail));

		let id_siswa = item.id_siswa;
		let nama_siswa = item.nama_siswa;
		let jenjang_kelas = item.nama_jenjang + ' ' + item.nama_kelas;
		let hp_wali = item.hp_wali;

		let toNumber = (s) => {
			if (!s) return 0;
			let onlyDigits = String(s).replace(/[^\d]/g, '');
			return Number(onlyDigits || 0);
		};

		let total_harga_pertemuan = toNumber(item.total_harga_pertemuan);
		let beasiswa = toNumber(item.nilai_beasiswa);



		// let total_bayar = total_harga_pertemuan - beasiswa;
		let total_bayar = toNumber(item.total_akhir);
		if (item.total_akhir === null || item.total_akhir === undefined || item.total_akhir === '') {
    total_bayar = toNumber(item.total_harga_pertemuan);
}
		// if (total_bayar <= 0) {
		// 	total_bayar = toNumber(item.total_harga_pertemuan - beasiswa);
		// }

		let rupiah = (n) => new Intl.NumberFormat('id-ID').format(n);

		let date = new Date();
		let tahun = $('#filter_periode_tahun').val();
		let bulan = $('#filter_periode_bulan').val();

		let month = month_to_abjad_month(bulan);

		let toBase64Url = s => btoa(s).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
		let token = toBase64Url(id_siswa);
		let link_tagihan = `<?= base_url('tagihan_online/c/') ?>` + token;
		let no_telepon = hp_wali;

		// let barisBeasiswa = beasiswa > 0 ? `%0ABeasiswa%3A%20Rp.%20${rupiah(beasiswa)}` : '';
		let textRaw =
			`Kepada Bapak / Ibu Wali Murid,\n\n` +
			`Izin mengingatkan, untuk tagihan bimbel di Aksara Course\n` +
			`Nama Siswa: ${nama_siswa}\n` +
			`Kelas: ${jenjang_kelas}\n` +
			`Bulan: ${month} ${tahun}\n` +
			`Total Biaya: Rp. ${rupiah(total_bayar)}\n\n` +
			`Best Regards,\nAksara Course\nCek Nota Tagihan:\n${link_tagihan}`;

		let link = `https://api.whatsapp.com/send?phone=${no_telepon}&text=${encodeURIComponent(textRaw)}`;
		window.open(link);
	}

	function tagih(detail) {
		$('#tagih').modal('show');
		var item = JSON.parse(atob(detail));

		let periode_tahun = $('#filter_periode_tahun').val()
		let periode_bulan = $('#filter_periode_bulan').val()

		let nilai_beasiswa = 0;
		let sub_total_harga_pertemuan = parseFloat(item.harga_pertemuan) * parseFloat(item.jumlah_meet)
		if (item.nilai_beasiswa != null) {
			if (item.tipe_beasiswa == 'Persen') {
				nilai_beasiswa = parseFloat(item.nilai_beasiswa) * (parseFloat(sub_total_harga_pertemuan) / 100);
			} else if (item.tipe_beasiswa == 'Nominal') {
				nilai_beasiswa = item.nilai_beasiswa;
			} else if (item.tipe_beasiswa == 'Harga Khusus') {
				// nilai_beasiswa = parseFloat(item.harga_pertemuan) - parseFloat(item.nilai_beasiswa);
				 nilai_beasiswa = (parseFloat(item.harga_pertemuan) - parseFloat(item.nilai_beasiswa)) * parseFloat(item.jumlah_meet);
			}

			$('#form-tagih input[name="nilai_beasiswa"]').val(NumberToMoney(nilai_beasiswa))
		} else {
			$('#form-tagih input[name="nilai_beasiswa"]').val(0)
		}

		let sub_total_diskon = parseFloat(item.diskon) * parseFloat(item.jumlah_meet)

		let harga_pertemuan = parseFloat(item.harga_pertemuan) - parseFloat(item.diskon) - parseFloat(nilai_beasiswa)
		let total_bayar_tagihan = parseFloat(sub_total_harga_pertemuan) - parseFloat(nilai_beasiswa) - parseFloat(sub_total_diskon)

		$('#form-tagih input[name="harga_pertemuan"]').val(NumberToMoney(item.harga_pertemuan))
		// $('#form-tagih input[name="total_harga_pertemuan"]').val(NumberToMoney(total_bayar_tagihan))
		$('#form-tagih input[name="total_harga_pertemuan"]').val(NumberToMoney(sub_total_harga_pertemuan));

		$('#form-tagih input[name="id_beasiswa"]').val(item.id_beasiswa)
		$('#form-tagih input[name="id_pendaftaran_paket"]').val(item.id_pendaftaran)
		$('#form-tagih input[name="id_siswa"]').val(item.id_siswa)
		$('#form-tagih input[name="periode_bulan"]').val(periode_bulan)
		$('#form-tagih input[name="periode_tahun"]').val(periode_tahun)
		$('#form-tagih input[name="nama_siswa"]').val(item.nama_siswa)
		$('#form-tagih input[name="hp_wali"]').val(item.hp_wali)
		$('#form-tagih input[name="nama_kelas"]').val(item.nama_jenjang + ' - ' + item.nama_kelas)
		$('#form-tagih input[name="nama_paket"]').val(item.nama_paket)
		$('#form-tagih input[name="diskon"]').val(NumberToMoney(item.diskon))
		$('#form-tagih input[name="pertemuan"]').val(NumberToMoney(item.jumlah_meet))
		$('#form-tagih input[name="kas"]').val(NumberToMoney(item.iuran_kas))
		$('#form-tagih input[name="total_kas"]').val(NumberToMoney(parseFloat(item.iuran_kas) * parseFloat(item.jumlah_meet)))
		$('#form-tagih input[name="total_akhir"]').val(NumberToMoney(total_bayar_tagihan))
		$('#form-tagih input[name="tipe_beasiswa"]').val(item.tipe_beasiswa)
		$('#form-tagih input[name="hitung_nilai_beasiswa"]').val(item.nilai_beasiswa)

		$('.edit_span_harga_pertemuan').html(NumberToMoney(item.harga_pertemuan))
		$('.edit_span_jumlah_pertemuan').html(NumberToMoney(item.jumlah_meet))
		$('.edit_span_sub_total_pertemuan').html(NumberToMoney(sub_total_harga_pertemuan))
		$('.edit_span_diskon').html('(' + NumberToMoney(item.diskon) + ' x ' + NumberToMoney(item.jumlah_meet) + ')  ' + NumberToMoney(sub_total_diskon))
		$('.edit_span_beasiswa').html(NumberToMoney(nilai_beasiswa))
		$('.edit_span_total_akhir').html(NumberToMoney(total_bayar_tagihan))

		jurnal_siswa('tagih', periode_bulan, periode_tahun, item.id_siswa)
	}

	function jurnal_siswa(nama_form, periode_bulan, periode_tahun, id_siswa) {
		$.ajax({
			url: '<?= base_url('admin/administrasi/tagihan_pembayaran/jurnal_siswa_result'); ?>',
			type: 'POST',
			data: {
				periode_bulan, periode_tahun, id_siswa
			},
			dataType: 'JSON',
			success: function (data) {
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
						table += `<div class="card-mapel">
												<p class="keterangan-hari">
													<span>Presensi: <span class="badge bg-${item.status_presensi == 'Hadir' ? 'success' : 'danger'}">${item.status_presensi}</span></span>
												</p>
									  <div class="keterangan-mapel">
										  <div class="keterangan-mapel-kiri">
											  <h5 class="judul-mapel" style="margin:0; margin-top: 8px;">${no++}. Tanggal : ${item.tanggal} - Tentor : ${item.nama_pegawai}</h5>
										  </div>
									  </div>
								  </div>
								  `;
					});
				}
				$(`#form_${nama_form}_data_jurnal_siswa`).html(table);
				let jumlah_awal = parseInt($('#dt-length-0').val());
			}
		});
	}

	function proses_pembayaran(detail) {
		$('#proses-pembayaran').modal('show');
		var item = JSON.parse(atob(detail));

		let periode_tahun = $('#filter_periode_tahun').val()
		let periode_bulan = $('#filter_periode_bulan').val()
		$('#form-proses-pembayaran input[name="id_pembayaran"]').val(item.id_pembayaran || '');
		$('#form-proses-pembayaran input[name="id_pendaftaran_paket"]').val(item.id_pendaftaran)
		$('#form-proses-pembayaran input[name="periode_bulan"]').val(periode_bulan)
		$('#form-proses-pembayaran input[name="periode_tahun"]').val(periode_tahun)

		$('#form-proses-pembayaran input[name="total_harga_pertemuan"]').val(NumberToMoney(item.total_akhir))
	}
	function edit_pembayaran(detail) {
		$('#edit-pembayaran').modal('show');
		var item = JSON.parse(atob(detail));

		let periode_tahun = $('#filter_periode_tahun').val()
		let periode_bulan = $('#filter_periode_bulan').val()

		let nilai_beasiswa = 0;
		let sub_total_harga_pertemuan = parseFloat(item.harga_pertemuan) * parseFloat(item.jumlah_meet);

		if (item.nilai_beasiswa != null) {
			if (item.tipe_beasiswa == 'Persen') {
				nilai_beasiswa = parseFloat(item.nilai_beasiswa);
			} else if (item.tipe_beasiswa == 'Nominal') {
				nilai_beasiswa = item.nilai_beasiswa;
			} else if (item.tipe_beasiswa == 'Harga Khusus') {
				nilai_beasiswa = parseFloat(item.harga_pertemuan) - parseFloat(item.nilai_beasiswa);
			}
			$('#form-edit-pembayaran input[name="nilai_beasiswa"]').val(NumberToMoney(nilai_beasiswa))
		} else {
			$('#form-edit-pembayaran input[name="nilai_beasiswa"]').val(0)
		}

		let sub_total_diskon = parseFloat(item.diskon) * parseFloat(item.jumlah_meet)

		let harga_pertemuan = parseFloat(item.harga_pertemuan) - parseFloat(item.diskon) - parseFloat(nilai_beasiswa)
		let total_bayar_tagihan = parseFloat(sub_total_harga_pertemuan) - parseFloat(nilai_beasiswa) - parseFloat(sub_total_diskon)

		$('#form-edit-pembayaran input[name="harga_pertemuan"]').val(NumberToMoney(item.harga_pertemuan))
		// $('#form-edit-pembayaran input[name="total_harga_pertemuan"]').val(NumberToMoney(total_bayar_tagihan))
		$('#form-edit-pembayaran input[name="total_harga_pertemuan"]').val(NumberToMoney(sub_total_harga_pertemuan));

		$('#id_pembayaran').val(item.id_pembayaran)
		$('#nilai_edit').val(item.nilai)
		$('#form-edit-pembayaran input[name="id_beasiswa"]').val(item.id_beasiswa)
		$('#form-edit-pembayaran input[name="id_pendaftaran_paket"]').val(item.id_pendaftaran)
		$('#form-edit-pembayaran input[name="id_siswa"]').val(item.id_siswa)
		$('#form-edit-pembayaran input[name="periode_bulan"]').val(periode_bulan)
		$('#form-edit-pembayaran input[name="periode_tahun"]').val(periode_tahun)
		$('#form-edit-pembayaran input[name="nama_siswa"]').val(item.nama_siswa)
		$('#form-edit-pembayaran input[name="hp_wali"]').val(item.hp_wali)
		$('#form-edit-pembayaran input[name="nama_kelas"]').val(item.nama_jenjang + ' - ' + item.nama_kelas)
		$('#form-edit-pembayaran input[name="nama_paket"]').val(item.nama_paket)
		$('#form-edit-pembayaran input[name="diskon"]').val(NumberToMoney(item.diskon))
		$('#form-edit-pembayaran input[name="pertemuan"]').val(NumberToMoney(item.jumlah_meet))
		$('#form-edit-pembayaran input[name="kas"]').val(NumberToMoney(item.iuran_kas))
		$('#form-edit-pembayaran input[name="total_kas"]').val(NumberToMoney(parseFloat(item.iuran_kas) * parseFloat(item.jumlah_meet)))
		$('#form-edit-pembayaran input[name="total_akhir"]').val(NumberToMoney(total_bayar_tagihan))
		$('#form-edit-pembayaran input[name="tipe_beasiswa"]').val(item.tipe_beasiswa)
		$('#form-edit-pembayaran input[name="hitung_nilai_beasiswa"]').val(item.nilai_beasiswa)

		$('.edit_span_harga_pertemuan_edit').html(NumberToMoney(item.harga_pertemuan))
		$('.edit_span_jumlah_pertemuan_edit').html(NumberToMoney(item.jumlah_meet))
		$('.edit_span_sub_total_pertemuan_edit').html(NumberToMoney(sub_total_harga_pertemuan))
		$('.edit_span_diskon_edit').html('(' + NumberToMoney(item.diskon) + ' x ' + NumberToMoney(item.jumlah_meet) + ')  ' + NumberToMoney(sub_total_diskon))
		$('.edit_span_beasiswa_edit').html(NumberToMoney(nilai_beasiswa))
		$('.edit_span_total_akhir_edit').html(NumberToMoney(total_bayar_tagihan))

		jurnal_siswa('tagih', periode_bulan, periode_tahun, item.id_siswa)
	}

	function bukti_pembayaran(detail) {
		$('#bukti-pembayaran').modal('show');
		var item = JSON.parse(atob(detail));

		let periode_tahun = $('#filter_periode_tahun').val()
		let periode_bulan = $('#filter_periode_bulan').val()
		let link_bukti_pembayaran = `<?php echo base_url(); ?>${item.bukti_pembayaran}`

		$('#form-bukti-pembayaran input[name="id_pembayaran"]').val(item.id_pembayaran || '');
		$('#form-bukti-pembayaran input[name="id_pendaftaran_paket"]').val(item.id_pendaftaran)
		$('#form-bukti-pembayaran input[name="periode_bulan"]').val(periode_bulan)
		$('#form-bukti-pembayaran input[name="periode_tahun"]').val(periode_tahun)

		$('#form-bukti-pembayaran input[name="total_harga_pertemuan"]').val(item.total_harga_pertemuan)
		$('#form-bukti-pembayaran input[name="tanggal_waktu_bayar"]').val(item.tanggal_bayar + ' ' + item.waktu_bayar)
		$('#form-bukti-pembayaran input[name="src_gambar"]').val(item.bukti_pembayaran)

		$('#bukti_pembayaran').attr('src', link_bukti_pembayaran)
	}

	function hitung_pertemuan() {
		let pertemuan = $('#form-tagih input[name="pertemuan"]').val();
		pertemuan = pertemuan.split(',').join('');
		if (pertemuan == null || pertemuan == '') {
			pertemuan = '0';
		}

		let diskon = $('#form-tagih input[name="diskon"]').val();
		diskon = diskon.split(',').join('');
		if (diskon == null || diskon == '') {
			diskon = '0';
		}


		let harga_pertemuan = $('#form-tagih input[name="harga_pertemuan"]').val();
		harga_pertemuan = harga_pertemuan.split(',').join('');
		if (harga_pertemuan == null || harga_pertemuan == '') {
			harga_pertemuan = '0';
		}

		let beasiswa = $('#form-tagih input[name="nilai_beasiswa"]').val();
		let tipe_beasiswa = $('#form-tagih input[name="tipe_beasiswa"]').val();
		let hitung_nilai_beasiswa = $('#form-tagih input[name="hitung_nilai_beasiswa"]').val();
		beasiswa = beasiswa.split(',').join('');
		if (beasiswa == null || beasiswa == '') {
			beasiswa = '0';
		} else {
			if (tipe_beasiswa == 'Persen') {
				var data_total_pertemuan = parseFloat(harga_pertemuan) * parseFloat(pertemuan)
				beasiswa = parseFloat(hitung_nilai_beasiswa) * parseFloat(data_total_pertemuan) / 100;
			} else if (tipe_beasiswa == 'Nominal') {
				beasiswa = beasiswa;
			} else if (tipe_beasiswa == 'Harga Khusus') {
				// beasiswa = parseFloat(harga_pertemuan) - parseFloat(hitung_nilai_beasiswa);
				beasiswa = (parseFloat(harga_pertemuan) - parseFloat(hitung_nilai_beasiswa)) * parseFloat(pertemuan);
			}
		}

		let kas = $('#form-tagih input[name="kas"]').val();
		kas = kas.split(',').join('');
		if (kas == null || kas == '') {
			kas = '0';
		}


		let hitung_harga_pertemuan = parseFloat(harga_pertemuan) - parseFloat(diskon)
		let total_bayar_tagihan = parseFloat(hitung_harga_pertemuan) * parseFloat(pertemuan) - parseFloat(beasiswa)

		let sub_total_kas = parseFloat(pertemuan) * parseFloat(kas)
		let sub_total_diskon = parseFloat(diskon) * parseFloat(pertemuan)
		let sub_total_beasiswa = parseFloat(beasiswa)
		let sub_total_harga_pertemuan = parseFloat(harga_pertemuan) * parseFloat(pertemuan)

		$('.edit_span_harga_pertemuan').html(NumberToMoney(harga_pertemuan))
		$('.edit_span_jumlah_pertemuan').html(NumberToMoney(pertemuan))
		$('.edit_span_sub_total_pertemuan').html(NumberToMoney(sub_total_harga_pertemuan))
		$('.edit_span_diskon').html(NumberToMoney(sub_total_diskon))
		$('.edit_span_diskon').html('(' + NumberToMoney(diskon) + ' x ' + NumberToMoney(pertemuan) + ')  ' + NumberToMoney(sub_total_diskon))
		$('.edit_span_beasiswa').html(NumberToMoney(sub_total_beasiswa))
		$('.edit_span_total_akhir').html(NumberToMoney(total_bayar_tagihan))
		$('#form-tagih input[name="nilai_beasiswa"]').val(NumberToMoney(sub_total_beasiswa))
		$('#form-tagih input[name="total_harga_pertemuan"]').val(NumberToMoney(sub_total_harga_pertemuan))
		$('#form-tagih input[name="total_kas"]').val(NumberToMoney(sub_total_kas))
		$('#form-tagih input[name="total_akhir"]').val(NumberToMoney(total_bayar_tagihan))
	}
	function hitung_pertemuan_edit() {
		let pertemuan = $('#form-edit-pembayaran input[name="pertemuan"]').val();
		pertemuan = pertemuan.split(',').join('');
		if (pertemuan == null || pertemuan == '') {
			pertemuan = '0';
		}

		let diskon = $('#form-edit-pembayaran input[name="diskon"]').val();
		diskon = diskon.split(',').join('');
		if (diskon == null || diskon == '') {
			diskon = '0';
		}


		let harga_pertemuan = $('#form-edit-pembayaran input[name="harga_pertemuan"]').val();
		harga_pertemuan = harga_pertemuan.split(',').join('');
		if (harga_pertemuan == null || harga_pertemuan == '') {
			harga_pertemuan = '0';
		}

		let beasiswa = $('#form-edit-pembayaran input[name="nilai_beasiswa"]').val();
		let tipe_beasiswa = $('#form-edit-pembayaran input[name="tipe_beasiswa"]').val();
		let hitung_nilai_beasiswa = $('#form-edit-pembayaran input[name="hitung_nilai_beasiswa"]').val();
		beasiswa = beasiswa.split(',').join('');
		if (beasiswa == null || beasiswa == '') {
			beasiswa = '0';
		} else {
			if (tipe_beasiswa == 'Persen') {
				let data_total_pertemuan = parseFloat(harga_pertemuan) * parseFloat(pertemuan)
				let nilai = $('#nilai_edit').val();
				beasiswa = parseFloat(nilai) * parseFloat(data_total_pertemuan) / 100;
			} else if (tipe_beasiswa == 'Nominal') {
				beasiswa = beasiswa;
			} else if (tipe_beasiswa == 'Harga Khusus') {
				// beasiswa = parseFloat(harga_pertemuan) - parseFloat(hitung_nilai_beasiswa);
				beasiswa = (parseFloat(harga_pertemuan) - parseFloat(hitung_nilai_beasiswa)) * parseFloat(pertemuan);
			}
		}

		let kas = $('#form-edit-pembayaran input[name="kas"]').val();
		kas = kas.split(',').join('');
		if (kas == null || kas == '') {
			kas = '0';
		}


		let hitung_harga_pertemuan = parseFloat(harga_pertemuan) - parseFloat(diskon)
		let total_bayar_tagihan = parseFloat(hitung_harga_pertemuan) * parseFloat(pertemuan) - parseFloat(beasiswa)

		let sub_total_kas = parseFloat(pertemuan) * parseFloat(kas)
		let sub_total_diskon = parseFloat(diskon) * parseFloat(pertemuan)
		let sub_total_beasiswa = parseFloat(beasiswa)
		let sub_total_harga_pertemuan = parseFloat(harga_pertemuan) * parseFloat(pertemuan)

		$('.edit_span_harga_pertemuan_edit').html(NumberToMoney(harga_pertemuan))
		$('.edit_span_jumlah_pertemuan_edit').html(NumberToMoney(pertemuan))
		$('.edit_span_sub_total_pertemuan_edit').html(NumberToMoney(sub_total_harga_pertemuan))
		$('.edit_span_diskon_edit').html(NumberToMoney(sub_total_diskon))
		$('.edit_span_diskon_edit').html('(' + NumberToMoney(diskon) + ' x ' + NumberToMoney(pertemuan) + ')  ' + NumberToMoney(sub_total_diskon))
		$('.edit_span_beasiswa_edit').html(NumberToMoney(sub_total_beasiswa))
		$('.edit_span_total_akhir_edit').html(NumberToMoney(total_bayar_tagihan))
		$('#form-edit-pembayaran input[name="nilai_beasiswa"]').val(NumberToMoney(sub_total_beasiswa))
		$('#form-edit-pembayaran input[name="total_harga_pertemuan"]').val(NumberToMoney(sub_total_harga_pertemuan))
		$('#form-edit-pembayaran input[name="total_kas"]').val(NumberToMoney(sub_total_kas))
		$('#form-edit-pembayaran input[name="total_akhir"]').val(NumberToMoney(total_bayar_tagihan))
	}

	function edit(detail) {
		$('#edit').modal('show');
		var item = JSON.parse(atob(detail));
		$('#id_tagihan_pembayaran').val(item.id);
		$('#nama_tagihan_pembayaran').val(item.nama_tagihan_pembayaran);
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
					url: `<?= base_url(); ?>admin/administrasi/tagihan_pembayaran/hapus`,
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
							tagihan_pembayaran();
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
			url: '<?= base_url('admin/administrasi/tagihan_pembayaran/kelas_result'); ?>',
			type: 'POST',
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

				$('#filter_kelas').html(option);
			}
		});
	}

	function siswa(id_siswa = null) {
		$.ajax({
			url: '<?= base_url('admin/administrasi/tagihan_pembayaran/siswa_result'); ?>',
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

				$('#select_siswa').html(option);
			}
		});
	}

	function validasi_bukti_pembayaran(file, maxMB) {
		if (!file) return { ok: false, msg: 'Silakan pilih file' };
		if (!file.type || !file.type.startsWith('image/')) return { ok: false, msg: 'File harus gambar' };
		if (file.size > maxMB * 1024 * 1024) return { ok: false, msg: `Maksimum ${maxMB} MB` };
		return { ok: true };
	}

	function pilih_metode_pembayaran() {
		let value = $(`#form-proses-pembayaran select[name="metode_pembayaran"]`).val();
		if (value == 'Transfer' || value == 'Qris') {
			$('.div_form_bukti_pembayaran').show();
		} else {
			$('.div_form_bukti_pembayaran').hide();
		}
	}

	function hitung_jumlah_bayar() {
		let nominal_bayar = $(`#form-proses-pembayaran input[name="nominal_bayar"]`).val();

		nominal_bayar = nominal_bayar.split(',').join('');
		if (nominal_bayar == null || nominal_bayar == '') {
			nominal_bayar = '0';
		}

		let total_harga_pertemuan = $(`#form-proses-pembayaran input[name="total_harga_pertemuan"]`).val();
		total_harga_pertemuan = total_harga_pertemuan.split(',').join('');
		if (total_harga_pertemuan == null || total_harga_pertemuan == '') {
			total_harga_pertemuan = '0';
		}

		let hitung = parseFloat(nominal_bayar) - parseFloat(total_harga_pertemuan);
		$(`#form-proses-pembayaran input[name="kembali"]`).val(NumberToMoney(hitung));
	}
</script>