<!DOCTYPE html>
<html lang="en" data-sidenav-size="sm-hover">

<head>
	<meta charset="utf-8" />
	<title>Halaman <?= $title; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
	<meta content="Coderthemes" name="author" />
	<link rel="shortcut icon" href="<?= base_url(); ?>assets/logo-a.jpg">
	<link href="<?= base_url(); ?>assets/vendor/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css">
	<script src="<?= base_url(); ?>assets/js/config.js"></script>
	<link href="<?= base_url(); ?>assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
	<link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
	<link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
	<link href="<?= base_url(); ?>assets/css/lightbox.css" rel="stylesheet" type="text/css" />
	<link href="<?= base_url(); ?>assets/vendor/quill/quill.snow.css" rel="stylesheet" type="text/css" />
	<script src="<?= base_url(); ?>assets/vendor/quill/quill.js"></script>
	<link rel="stylesheet" href="https://unpkg.com/@tabler/icons-webfont@latest/tabler-icons.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" rel="stylesheet"
		type="text/css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@3.1.3/build/jodit.min.css" />
	<link rel="stylesheet" href="https://smkryoyuwaraja.sch.id/assets/admin/css/lightbox.css" />
	<link href="<?= base_url(); ?>assets/css/jquery.autocomplete.css" rel="stylesheet" type="text/css" />
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<style>
	.dropify-wrapper .dropify-message span.file-icon {
		font-size: 20px;
		color: #CCC;
	}

	@media (min-width: 320px) and (max-width: 767.98px) {
		.app-topbar .logo {
			display: none;
		}
	}

	@media (min-width: 767.98px) {
		.app-topbar .logo {
			display: block;
		}
	}

	.card-mapel {
		background-color: #F5F5F5FF;
		border: 1px solid #CACACAFF;
		padding: 12px;
		border-radius: 8px;
		margin-bottom: 12px;
	}

	.card-mapel .keterangan-hari {
		padding: 0;
		margin: 0;
		font-size: 12px;
		margin-bottom: 4px;
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-direction: row;
	}

	.card-mapel .keterangan-mapel {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.card-mapel .judul-mapel {
		padding: 0;
		margin-bottom: 8px;
		font-size: 18px;
	}

	.card-mapel .keterangan-jam-mapel {
		padding: 0;
		margin: 0;
		font-size: 12px;
		color: #199BE2;
	}

	.keterangan-mapel-kiri {
		margin-bottom: 8px;
	}

	.text-tanggal-input {
		font-size: 14px;
		margin: 0;
		display: flex;
		align-items: center;
		column-gap: 1rem;
	}

	.wrap-tanggal-kegiatan {
		position: relative;
	}

	.wrap-tanggal-kegiatan::after {
		content: '';
		height: 40px;
		width: 1px;
		background-color: #C2C2C2FF;
		position: absolute;
		right: -18px;
		top: 0;
	}

	.keterangan-mapel-kiri-utama {
		display: flex;
		justify-content: space-between;
		align-items: flex-start;
	}

	@media (min-width: 320px) and (max-width: 767px) {
		.card-mapel .keterangan-mapel {
			flex-direction: column;
			align-items: flex-start;
		}

		.keterangan-mapel-kiri-utama {
			flex-direction: column;
			align-items: flex-start;
			row-gap: 8px;
		}

		.card-mapel .keterangan-hari {
			font-size: 12px;
			align-items: flex-start;
		}

		.keterangan-mapel-kanan {
			width: 100%;
		}

		.text-tanggal-input {
			flex-direction: column;
			align-items: flex-start;
			row-gap: .2rem;
		}
	}
</style>

<body>
	<!-- Begin page -->
	<div class="wrapper">

		<!-- Menu -->
		<!-- Sidenav Menu Start -->
		<div class="sidenav-menu">

			<a href="<?= base_url('dashboard'); ?>" class="logo">
				<span class="logo-light">
					<span class="logo-lg"><img src="<?= base_url(); ?>assets/logo-a.jpg" alt="logo"></span>
					<span class="logo-sm"><img src="<?= base_url(); ?>assets/logo-a.jpg" alt="small logo"></span>
				</span>
				<span class="logo-dark">
					<span class="logo-lg"><img src="<?= base_url(); ?>assets/logo-a.jpg" alt="dark logo"></span>
					<span class="logo-sm"><img src="<?= base_url(); ?>assets/logo-a.jpg" alt="small logo"></span>
				</span>
			</a>

			<!-- Sidebar Hover Menu Toggle Button -->
			<button class="button-sm-hover">
				<i class="ri-circle-line align-middle"></i>
			</button>

			<!-- Sidebar Menu Toggle Button -->
			<button class="sidenav-toggle-button">
				<i class="ri-menu-5-line fs-20"></i>
			</button>

			<!-- Full Sidebar Menu Close Button -->
			<button class="button-close-fullsidebar">
				<i class="ti ti-x align-middle"></i>
			</button>

			<div data-simplebar>

				<!-- User -->
				<div class="sidenav-user">
					<div class="dropdown-center text-center">
						<a class="topbar-link dropdown-toggle text-reset drop-arrow-none px-2" data-bs-toggle="dropdown"
							type="button" aria-haspopup="false" aria-expanded="false">
							<img src="<?= base_url(); ?>assets/user.png" width="46" class="rounded-circle"
								alt="user-image">
							<span class="d-flex gap-1 sidenav-user-name my-2">
								<span>
									<span class="mb-0 fw-semibold lh-base fs-15" onclick="logout()">
										<i class="ri-shut-down-line"></i>
										Keluar
									</span>
								</span>
							</span>
						</a>

					</div>
				</div>

				<?php
				$id_level = $this->session->userdata('admin')['id_level'];

				$menuDashboard = $this->db->query("SELECT a.* FROM list_menu a where id_level in ($id_level) and a.group = 'Dashboard'")->row_array();
				$menuSiswa = $this->db->query("SELECT a.* FROM list_menu a where id_level in ($id_level) and a.group = 'Siswa'")->row_array();
				$menuAdministrasi = $this->db->query("SELECT a.* FROM list_menu a left join menu b on a.id_menu = b.id where id_level in ($id_level) and a.group = 'Administrasi'  order by b.urut asc")->result_array();
				$menuMaster = $this->db->query("SELECT a.* FROM list_menu a left join menu b on a.id_menu = b.id where id_level in ($id_level) and a.group = 'Master'  order by b.urut asc")->result_array();
				$menuPegawai = $this->db->query("SELECT a.* FROM list_menu a left join menu b on a.id_menu = b.id where id_level in ($id_level) and a.group = 'Pegawai'  order by b.urut asc")->result_array();
				$menuJurnal = $this->db->query("SELECT a.* FROM list_menu a left join menu b on a.id_menu = b.id where id_level in ($id_level) and a.group = 'Jurnal'  order by b.urut asc")->result_array();
				$menuKelas = $this->db->query("SELECT a.* FROM list_menu a left join menu b on a.id_menu = b.id where id_level in ($id_level) and a.group = 'Manajemen Kelas'  order by b.urut asc")->result_array();
				$menuPendaftaran = $this->db->query("SELECT a.* FROM list_menu a left join menu b on a.id_menu = b.id where id_level in ($id_level) and a.group = 'Pendaftaran'  order by b.urut asc")->row_array();
				$menuBeasiswa = $this->db->query("SELECT a.* FROM list_menu a left join menu b on a.id_menu = b.id where id_level in ($id_level) and a.group = 'Beasiswa'  order by b.urut asc")->row_array();
				$menuLaporan = $this->db->query("SELECT a.* FROM list_menu a left join menu b on a.id_menu = b.id where id_level in ($id_level) and a.group = 'Laporan' and b.urut = 1")->row_array();
				$menuPengaturan = $this->db->query("SELECT a.* FROM list_menu a where id_level in ($id_level) and a.group = 'Pengaturan'")->result_array();
				$menuLatihanSoalDashboard = $this->db->query("SELECT a.* FROM list_menu a LEFT JOIN menu b ON a.id_menu = b.id WHERE a.id_level IN ($id_level) AND a.`group` = 'Latihan Soal' AND a.path = 'latihan_soal/dashboard' ORDER BY CAST(b.urut AS UNSIGNED) ASC")->row_array();
				$menuLatihanSoalMaster = $this->db->query("SELECT a.* FROM list_menu a LEFT JOIN menu b ON a.id_menu = b.id WHERE a.id_level IN ($id_level) AND a.`group` = 'Latihan Soal' AND a.path LIKE 'latihan_soal/master/%' ORDER BY CAST(b.urut AS UNSIGNED) ASC")->result_array();
				$menuLatihanSoalLain = $this->db->query("SELECT a.* FROM list_menu a LEFT JOIN menu b ON a.id_menu = b.id WHERE a.id_level IN ($id_level) AND a.`group` = 'Latihan Soal' AND a.path != 'latihan_soal/dashboard' AND a.path NOT LIKE 'latihan_soal/master/%' ORDER BY CAST(b.urut AS UNSIGNED) ASC")->result_array();
				?>
				<ul class="side-nav">
					<?php if ($menuDashboard): ?>
						<li class="side-nav-item ">
							<a href="<?= base_url("/" . $menuDashboard['path']) ?>" class="side-nav-link">
								<span class="menu-icon"><i class="ti ti-dashboard"></i></span>
								<span class="menu-text"> Dashboard </span>
							</a>
						</li>
					<?php endif; ?>
					<?php if ($menuLatihanSoalDashboard || $menuLatihanSoalMaster || $menuLatihanSoalLain): ?>
						<li class="side-nav-item">
							<a data-bs-toggle="collapse" href="#latihan_soal" aria-expanded="false"
								aria-controls="latihan_soal" class="side-nav-link">
								<span class="menu-icon"><i class="ti ti-school"></i></span>
								<span class="menu-text"> Latihan Soal </span>
								<span class="menu-arrow"></span>
							</a>
							<div class="collapse" id="latihan_soal">
								<ul class="sub-menu">
									<?php if ($menuLatihanSoalDashboard): ?>
										<li class="side-nav-item">
											<a href="<?= base_url("/" . $menuLatihanSoalDashboard['path']) ?>" class="side-nav-link">
												<span class="menu-text">Dashboard Latihan Soal</span>
											</a>
										</li>
									<?php endif; ?>

									<?php if ($menuLatihanSoalMaster): ?>
										<li class="side-nav-item">
											<a data-bs-toggle="collapse" href="#master_soal_latihan" aria-expanded="false"
												aria-controls="master_soal_latihan" class="side-nav-link">
												<span class="menu-text">Master Soal</span>
												<span class="menu-arrow"></span>
											</a>
											<div class="collapse" id="master_soal_latihan">
												<ul class="sub-menu">
													<?php foreach ($menuLatihanSoalMaster as $menu): ?>
												<?php
												$namaMenuMasterSoal = $menu['name'];
												if ($menu['path'] == 'latihan_soal/master/mata_pelajaran') {
													$namaMenuMasterSoal = 'Mata Pelajaran';
												} elseif ($menu['path'] == 'latihan_soal/master/materi') {
													$namaMenuMasterSoal = 'Materi';
												} elseif ($menu['path'] == 'latihan_soal/master/kategori_soal') {
													$namaMenuMasterSoal = 'Kategori Soal';
												}
												?>
												<li class="side-nav-item">
													<a href="<?= base_url("/" . $menu['path']) ?>" class="side-nav-link">
														<span class="menu-text"><?= $namaMenuMasterSoal ?></span>
													</a>
												</li>
											<?php endforeach; ?>
												</ul>
											</div>
										</li>
									<?php endif; ?>

									<?php foreach ($menuLatihanSoalLain as $menu): ?>
										<li class="side-nav-item">
											<a href="<?= base_url("/" . $menu['path']) ?>" class="side-nav-link">
												<span class="menu-text"><?= $menu['name'] ?></span>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</li>
					<?php endif; ?>
					<?php if ($menuAdministrasi): ?>
						<li class="side-nav-item">
							<a data-bs-toggle="collapse" href="#administrasi" aria-expanded="false"
								aria-controls="administrasi" class="side-nav-link">
								<span class="menu-icon"><i class="ti ti-pencil"></i></span>
								<span class="menu-text"> Administrasi </span>
								<span class="menu-arrow"></span>
							</a>
							<div class="collapse" id="administrasi">
								<ul class="sub-menu">
									<?php foreach ($menuAdministrasi as $menu): ?>
										<li class="side-nav-item">
											<a href="<?= base_url("/" . $menu['path']) ?>" class="side-nav-link">
												<span class="menu-text"><?= $menu['name'] ?></span>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</li>
					<?php endif; ?>
					<?php if ($menuPendaftaran): ?>
						<li class="side-nav-item ">
							<a href="<?= base_url("/" . $menuPendaftaran['path']) ?>" class="side-nav-link">
								<span class="menu-icon"><i class="ti ti-folder"></i></span>
								<span class="menu-text"> Pendaftaran Paket </span>
							</a>
						</li>
					<?php endif; ?>

					<?php if ($menuJurnal): ?>
						<li class="side-nav-item">
							<a data-bs-toggle="collapse" href="#jurnal" aria-expanded="false" aria-controls="jurnal"
								class="side-nav-link">
								<span class="menu-icon"><i class="ti ti-book"></i></span>
								<span class="menu-text"> Jurnal </span>
								<span class="menu-arrow"></span>
							</a>
							<div class="collapse" id="jurnal">
								<ul class="sub-menu">
									<?php foreach ($menuJurnal as $menu): ?>
										<li class="side-nav-item">
											<a href="<?= base_url("/" . $menu['path']) ?>" class="side-nav-link">
												<span class="menu-text"><?= $menu['name'] ?></span>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</li>
					<?php endif; ?>
					<?php if ($menuSiswa): ?>
						<li class="side-nav-item ">
							<a href="<?= base_url("/" . $menuSiswa['path']) ?>" class="side-nav-link">
								<span class="menu-icon"><i class="ti ti-user"></i></span>
								<span class="menu-text"> Siswa </span>
							</a>
						</li>
					<?php endif; ?>
					<?php if ($menuBeasiswa): ?>
						<li class="side-nav-item ">
							<a href="<?= base_url("/" . $menuBeasiswa['path']) ?>" class="side-nav-link">
								<span class="menu-icon"><i class="ti ti-gift"></i></span>
								<span class="menu-text"> Beasiswa Siswa </span>
							</a>
						</li>
					<?php endif; ?>

					<?php if ($menuKelas): ?>
						<li class="side-nav-item">
							<a data-bs-toggle="collapse" href="#manajemen_kelas" aria-expanded="false"
								aria-controls="manajemen_kelas" class="side-nav-link">
								<span class="menu-icon"><i class="ti ti-home"></i></span>
								<span class="menu-text"> Manajemen Kelas </span>
								<span class="menu-arrow"></span>
							</a>
							<div class="collapse" id="manajemen_kelas">
								<ul class="sub-menu">
									<?php foreach ($menuKelas as $menu): ?>
										<li class="side-nav-item">
											<a href="<?= base_url("/" . $menu['path']) ?>" class="side-nav-link">
												<span class="menu-text"><?= $menu['name'] ?></span>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</li>
					<?php endif; ?>
					<?php if ($menuPegawai): ?>
						<li class="side-nav-item">
							<a data-bs-toggle="collapse" href="#data-pegawai" aria-expanded="false"
								aria-controls="data-pegawai" class="side-nav-link">
								<span class="menu-icon"><i class="ti ti-users"></i></span>
								<span class="menu-text"> Kepegawaian </span>
								<span class="menu-arrow"></span>
							</a>
							<div class="collapse" id="data-pegawai">
								<ul class="sub-menu">
									<?php foreach ($menuPegawai as $menu): ?>
										<li class="side-nav-item">
											<a href="<?= base_url("/" . $menu['path']) ?>" class="side-nav-link">
												<span class="menu-text"><?= $menu['name'] ?></span>
											</a>
										</li>
									<?php endforeach; ?>

								</ul>
							</div>
						</li>
					<?php endif; ?>
					<?php if ($menuMaster): ?>
						<li class="side-nav-item">
							<a data-bs-toggle="collapse" href="#sidebarPages" aria-expanded="false"
								aria-controls="sidebarPages" class="side-nav-link">
								<span class="menu-icon"><i class="ti ti-layout-dashboard"></i></span>
								<span class="menu-text"> Master Data </span>
								<span class="menu-arrow"></span>
							</a>
							<div class="collapse" id="sidebarPages">
								<ul class="sub-menu">
									<?php foreach ($menuMaster as $menu): ?>
										<li class="side-nav-item">
											<a href="<?= base_url("/" . $menu['path']) ?>" class="side-nav-link">
												<span class="menu-text"><?= $menu['name'] ?></span>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</li>
					<?php endif; ?>
					<?php if ($menuLaporan): ?>
						<li class="side-nav-item ">
							<a href="<?= base_url("/" . $menuLaporan['path']) ?>" class="side-nav-link">
								<span class="menu-icon"><i class="ti ti-file"></i></span>
								<span class="menu-text"> <?= $menuLaporan['name'] ?> </span>
							</a>
						</li>
					<?php endif; ?>
					<?php if ($menuPengaturan): ?>
						<li class="side-nav-item">
							<a data-bs-toggle="collapse" href="#pengaturan" aria-expanded="false" aria-controls="pengaturan"
								class="side-nav-link">
								<span class="menu-icon"><i class="ti ti-settings"></i></span>
								<span class="menu-text"> Pengaturan </span>
								<span class="menu-arrow"></span>
							</a>
							<div class="collapse" id="pengaturan">
								<ul class="sub-menu">
									<?php foreach ($menuPengaturan as $menu): ?>
										<li class="side-nav-item">
											<a href="<?= base_url("/" . $menu['path']) ?>" class="side-nav-link">
												<span class="menu-text"><?= $menu['name'] ?></span>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</li>
					<?php endif; ?>
				</ul>
				<div class="clearfix"></div>
			</div>
		</div>
		<!-- Sidenav Menu End -->


		<!-- Topbar Start -->
		<header class="app-topbar" id="header">
			<div class="page-container topbar-menu">
				<div class="d-flex align-items-center gap-2">
					<!-- Sidebar Menu Toggle Button -->
					<button class="sidenav-toggle-button px-2">
						<i class="ri-menu-5-line fs-24"></i>
					</button>
					<!-- Horizontal Menu Toggle Button -->
					<button class="topnav-toggle-button px-2" data-bs-toggle="collapse"
						data-bs-target="#topnav-menu-content">
						<i class="ri-menu-5-line fs-24"></i>
					</button>
					<!-- Topbar Page Title -->
					<div class="topbar-item d-none d-md-flex px-2">
						<div>
							<h4 class="page-title fs-20 fw-semibold mb-0"><?= $title; ?></h4>
						</div>
					</div>
				</div>

				<div class="d-flex align-items-center gap-2">
					<div class="topbar-item d-none d-sm-flex">
						<button class="topbar-link" id="light-dark-mode" type="button">
							<i class="ri-moon-line light-mode-icon fs-22"></i>
							<i class="ri-sun-line dark-mode-icon fs-22"></i>
						</button>
					</div>

					<!-- User Dropdown -->
					<div class="topbar-item nav-user">
						<div class="dropdown">
							<span class="d-lg-flex flex-column gap-1 d-none">
								<h5 class="my-0"><?= $this->session->userdata('admin')['nama_lengkap']; ?></h5>
							</span>
						</div>
					</div>
				</div>
			</div>
		</header>
		<!-- Topbar End -->

		<!-- Search Modal -->
		<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content bg-transparent">
					<form>
						<div class="card mb-1">
							<div class="px-3 py-2 d-flex flex-row align-items-center" id="top-search">
								<i class="ri-search-line fs-22"></i>
								<input type="search" class="form-control border-0" id="search-modal-input"
									placeholder="Search for actions, people,">
								<button type="submit" class="btn p-0" data-bs-dismiss="modal"
									aria-label="Close">[esc]</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="page-content">
			<div class="page-container">
				<script>
					function logout() {
						Swal.fire({
							title: 'Yakin ingin keluar?',
							icon: 'warning',
							showCancelButton: true,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Ya',
							cancelButtonText: 'Tidak'
						}).then((result) => {
							if (result.isConfirmed) {
								window.location.href = "<?= base_url() ?>login/keluar";
							}
						})
					}
				</script>
