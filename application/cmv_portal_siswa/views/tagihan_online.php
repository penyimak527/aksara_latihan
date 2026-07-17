<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Tagihan Online</title>
	<link rel="shortcut icon" href="<?= base_url(); ?>assets/logo-a.jpg">
	<!-- Bootstrap cdn 3.3.7 -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
		integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<!-- Custom font montseraat -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700" rel="stylesheet">
	<!-- Custom style invoice1.css -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/invoice/invoice2.css">
	<script type="text/javascript" src="<?php echo base_url() ?>assets/invoice/sweetalert.js"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
		integrity="sha256-k2WSCIexGzOj3Euiig+TlR8gA0EmPjuc79OEeY5L45g=" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body style="background: url(<?php echo base_url() ?>assets/invoice/bg.png) rgb(246, 207, 34, .2);">
	<section class="back">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div class="invoice-wrapper">
						<div class="invoice-top">
							<div class="row">
								<div class="col-sm-6">
									<div class="invoice-top-left">
										<img src="<?php echo base_url() ?>assets/logo-aksara.png" alt="logo"
											class="logo">
										<h3 class="judul-invoice">Invoice</h3>
										<h6>Diharapkan Untuk Mengupload Bukti Pembayaran Jika Sudah Melakukan
											Pembayaran.</h6>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="invoice-top-right">
										<p>Tagihan Kepada Wali Murid Dari</p>
										<h2><?php echo $row['nama_siswa']; ?></h2>
										<h5><?php echo $row['alamat'] ?></h5>
									</div>
								</div>
							</div>
						</div>
						<div class="invoice-bottom">
							<div class="row">
								<div class="col-xs-12">
									<div class="task-table-wrapper">
										<div class="invoice-detail">
											<div class="invoice-detail-header">
												<p>Tagihan</p>
												<p>Pertemuan</p>
												<p>Sub Total</p>
												<p class="header-bukti-pembayaran">Bukti Pembayaran</p>
											</div>
											<?php
											$total_harga_pertemuan = 0;
											if ($res) {
												foreach ($res as $r) {
													$sub_total_harga_pertemuan = ($r['harga_pertemuan'] * $r['jumlah_meet']) - $r['nilai_beasiswa'] - ($r['diskon'] * $r['jumlah_meet']);
													if ($r['status'] == 'Belum') {
														$total_harga_pertemuan += $sub_total_harga_pertemuan;
													}
													?>
													<div class="invoice-detail-content">
														<div class="invoice-detail-tagihan">
															<p>Tagihan Bulan
																<?php echo DateTime::createFromFormat('!m', $r['periode_bulan'])->format('M'); ?>
																<?php echo $r['periode_tahun']; ?>
															</p>
															<p><?php echo $r['nama_jenjang']; ?> -
																<?php echo $r['nama_kelas']; ?> Paket
																<?php echo $r['nama_paket']; ?>
															</p>
														</div>
														<?php if ($r['status'] == 'Belum') { ?>
															<div class="invoice-detail-price">
																<p><?php echo number_format($r['jumlah_meet']); ?></p>
															</div>
															<div class="invoice-detail-price">
																<p>Rp. <?php echo number_format($sub_total_harga_pertemuan); ?></p>
															</div>
															<div class="invoice-detail-bukti-pembayaran">
																<button class="btn btn-upload-file"
																	onclick="swal_bukti_pembayaran(<?php echo $r['id_pendaftaran']; ?>, <?php echo $r['periode_bulan']; ?>, <?php echo $r['periode_tahun']; ?>, <?php echo $r['total_harga_pertemuan']; ?>);"
																	type="button" data-id="1">Pilih File</button>
															</div>
														<?php } elseif ($r['status'] == 'Sudah Bayar') { ?>
															<div class="invoice-detail-price">
																<p style="color: #777C6D">Menunggu Proses Validasi Admin</p>
															</div>
														<?php } elseif ($r['status'] == 'Lunas') { ?>
															<div class="invoice-detail-price">
																<p style="color: #016B61">Lunas</p>
															</div>
														<?php } ?>
													</div>
													<?php
												}
											} else {
												?>
												<div class="invoice-detail-content">
													<div class="invoice-detail-tagihan">
														<p>Belum ada tagihan</p>
													</div>
												</div>
												<?php
											}
											?>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-12">
									<div class="invoice-bottom-total">
										<div class="invoice-bottom-first">
											<div class="sub-total-box">
												<h6>METODE PEMBAYARAN</h6>
												<div>
													<img src="<?php echo base_url() ?>assets/invoice/logo-bri.png"
														alt="" width="60">
													<h5>BRI 6331-0102-8735-537 <br>(An. DESTY DIAN ARISANDY)</h5>
												</div>
											</div>
										</div>
										<div class="invoice-bottom-last mb-2">
											<div class="total-box">
												<h6>TOTAL</h6>
												<h3>Rp.
													<?php echo number_format($total_harga_pertemuan); ?>
												</h3>
											</div>
										</div>
									</div>
								</div>
								<div class="col-xs-12">
									<hr class="divider">
								</div>
								<div class="row footer">
									<div class="col-xs-6 no-padding">
										<h6 class="text-left" style="padding: 0 15px;">aksaracourse.com</h6>
									</div>
									<div class="col-xs-6 no-padding">
										<h6 class="text-right" style="padding: 0 15px;">aksaracourse@johndoe.com</h6>
									</div>
								</div>
							</div>
							<div class="bottom-bar"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script>
		async function swal_bukti_pembayaran(id_pendaftaran, periode_bulan, periode_tahun, total_harga_pertemuan) {
			let $btn = $(this);
			let accept = $btn.data('accept') || '.jpg,.jpeg,.png';
			let maxMB = parseFloat($btn.data('maxmb') || '10');
			let id = $btn.data('id') || 0;

			let swal = await Swal.fire({
				title: 'Pilih Bukti Pembayaran',
				input: 'file',
				inputAttributes: { accept },
				showCancelButton: true,
				confirmButtonText: 'Upload',
				cancelButtonText: 'Batal',
				showLoaderOnConfirm: false,
				preConfirm: (f) => {
					if (!f) { Swal.showValidationMessage('Silakan pilih file'); return false; }
					if (!/^image\//.test(f.type)) { Swal.showValidationMessage('File harus gambar'); return false; }
					if (f.size > maxMB * 1024 * 1024) { Swal.showValidationMessage(`Maksimum ${maxMB} MB`); return false; }
					return f;
				},
				allowOutsideClick: () => !Swal.isLoading()
			});

			let file = swal.value;

			if (!file) return;

			Swal.fire({
				title: 'Mengunggah...',
				html: `
			<div id="swal-progress" style="height:8px;background:#eee;border-radius:4px;overflow:hidden">
				<div id="swal-bar" style="height:8px;width:0%"></div>
			</div>
			<div id="swal-percent" style="margin-top:8px;font-size:12px">0%</div>
			`,
				allowOutsideClick: false,
				showConfirmButton: false,
				didOpen: () => Swal.showLoading()
			});

			const fd = new FormData();
			fd.append('file', file);
			fd.append('id_pendaftaran', id_pendaftaran);
			fd.append('periode_bulan', periode_bulan);
			fd.append('periode_tahun', periode_tahun);
			fd.append('total_harga_pertemuan', total_harga_pertemuan);

			$.ajax({
				url: '<?= base_url('admin/administrasi/tagihan_pembayaran/tambah_bukti_pembayaran'); ?>',
				method: 'POST',
				data: fd,
				processData: false,
				contentType: false,
				dataType: 'json',
				xhr: function () {
					const xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener('progress', function (e) {
						if (e.lengthComputable) {
							const pct = Math.round((e.loaded / e.total) * 100);
							$('#swal-bar').css('width', pct + '%');
							$('#swal-percent').text(pct + '%');
						}
					});
					return xhr;
				},
				success: function (res) {
					if (res.status) {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil diupload!',
							text: res.message
						});
						location.reload()
					} else {
						Swal.fire({ icon: 'error', title: 'Gagal', text: (res && res.message) || 'Upload gagal' });
					}
				},
				error: function (xhr, status, errorThrown) {
					console.group('DEBUG AJAX UPLOAD');
					console.log('Status:', status);
					console.log('Error:', errorThrown);
					console.log('Response Text:', xhr.responseText);
					console.groupEnd();

					let msg = 'Terjadi kesalahan';

					// kalau JSON valid
					try {
						const json = JSON.parse(xhr.responseText);
						msg = json.message || JSON.stringify(json);
					} catch (e) {
						// kalau bukan JSON
						msg = xhr.responseText?.substring(0, 300) || xhr.statusText || errorThrown;
					}

					Swal.fire({
						icon: 'error',
						title: 'Gagal',
						html: `<pre style="text-align:left;white-space:pre-wrap">${msg}</pre>`
					});
				}

			});
		}

	</script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<!-- jquery slim version 3.2.1 minified -->
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
		integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
		crossorigin="anonymous"></script>
</body>

</html>
