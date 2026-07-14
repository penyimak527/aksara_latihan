<div class="card mb-3">
	<div class="card-header border-bottom border-dashed">
		<h4 class="header-title mb-1">Dashboard Latihan Soal</h4>
		<small class="text-muted">Pusat menu admin/tentor untuk master soal, bank soal, sesi soal, hasil pengerjaan, analisa kelas, detail siswa, dan izin preview jawaban.</small>
	</div>
	<div class="card-body">
		<div class="row g-3 mb-3">
			<div class="col-md-2 col-6"><div class="border rounded p-3 text-center"><h4><?= $ringkasan['total_mapel']; ?></h4><small>Mata Pelajaran</small></div></div>
			<div class="col-md-2 col-6"><div class="border rounded p-3 text-center"><h4><?= $ringkasan['total_materi']; ?></h4><small>Materi</small></div></div>
			<div class="col-md-2 col-6"><div class="border rounded p-3 text-center"><h4><?= $ringkasan['total_kategori']; ?></h4><small>Kategori Soal</small></div></div>
			<div class="col-md-2 col-6"><div class="border rounded p-3 text-center"><h4><?= $ringkasan['total_soal']; ?></h4><small>Bank Soal</small></div></div>
			<div class="col-md-2 col-6"><div class="border rounded p-3 text-center"><h4><?= $ringkasan['total_sesi']; ?></h4><small>Sesi Aktif</small></div></div>
			<div class="col-md-2 col-6"><div class="border rounded p-3 text-center"><h4><?= $ringkasan['total_hasil']; ?></h4><small>Hasil</small></div></div>
		</div>
		<div class="row g-3">
			<?php
			$menus = [
				['Master Soal', 'Mengelola mata pelajaran, materi, dan kategori soal.', 'latihan_soal/master/mata_pelajaran'],
				['Bank Soal', 'Membuat dan mengelola naskah/paket soal.', 'latihan_soal/bank/naskah_soal'],
				['Sesi Soal', 'Mengatur jadwal, kelas, timer, dan soal.', 'latihan_soal/sesi_soal'],
				['Hasil Pengerjaan', 'Melihat nilai dan hasil pengerjaan siswa.', 'latihan_soal/hasil_pengerjaan'],
				['Analisa Kelas', 'Ringkasan kelas, ranking siswa, dan analisa materi.', 'latihan_soal/analisa_kelas'],
				['Izin Preview Jawaban', 'Memberi izin preview soal dan kunci jawaban per siswa.', 'latihan_soal/izin_preview'],
			];
			foreach ($menus as $menu): ?>
				<div class="col-md-4">
					<div class="border rounded p-3 h-100">
						<h5><?= $menu[0]; ?></h5>
						<p class="text-muted"><?= $menu[1]; ?></p>
						<a href="<?= base_url($menu[2]); ?>" class="btn btn-sm btn-outline-primary">Buka Menu</a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
