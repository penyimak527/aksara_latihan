<?php
class M_tagihan_pembayaran extends CI_Model
{


	public function tagihan_pembayaran_result()
	{
		$periode_tahun = $this->input->post('periode_tahun');
		$periode_bulan = $this->input->post('periode_bulan');

		$kelas = $this->input->post('kelas');
		$where_kelas = "";
		if ($kelas != "") {
			$where_kelas = "AND pp.id_kelas = '$kelas'";
		}

		$jenis_administrasi = $this->input->post('jenis_administrasi');
		$where_jenis_administrasi = "";
		if ($jenis_administrasi != "") {
			$where_jenis_administrasi = "AND pp.jenis_administrasi = '$jenis_administrasi'";
		}

		$cari = $this->input->post('cari');
		$where_cari = "";
		if ($cari != "") {
			$where_cari = "AND (s.nama_siswa LIKE '%$cari%')";
		}

		$sql = $this->db->query("SELECT	
														pp.id AS id_pendaftaran,
														pp.diskon,
														s.id AS id_siswa,
														s.nama_siswa,
														s.hp_wali,
														j.nama_jenjang,
														k.nama_kelas,
														p.nama_paket,
														ph.harga_pertemuan,
														ph.iuran_kas,
														b.tipe as tipe_beasiswa,
														b.id as id_beasiswa,
														b.nilai,
														byrs.id as id_pembayaran,
														CASE
															WHEN byrs.id IS NULL THEN b.nilai
															ELSE byrs.nilai_beasiswa
														END AS nilai_beasiswa,
														CASE
															WHEN byrs.id IS NULL THEN IFNULL(js.jumlah_meet, '1')
															ELSE byrs.pertemuan
														END AS jumlah_meet,
														CASE
															WHEN byrs.id IS NULL THEN '$periode_tahun'
															ELSE byrs.periode_tahun
														END AS periode_tahun,
														CASE
															WHEN byrs.id IS NULL THEN '$periode_bulan'
															ELSE byrs.periode_bulan
														END AS periode_bulan,
														CASE
															WHEN byrs.id IS NULL THEN (IFNULL(js.jumlah_meet, '1') * ph.iuran_kas)
															ELSE byrs.total_kas
														END AS total_kas,
														CASE
															WHEN byrs.id IS NULL THEN (IFNULL(js.jumlah_meet, '1') * ph.harga_pertemuan)
															ELSE byrs.total_harga_pertemuan
														END AS total_harga_pertemuan,
														COALESCE(byrs.nominal_bayar, 0) AS nominal_bayar,
														CASE
    														WHEN byrs.id IS NULL THEN 0
    														ELSE GREATEST((byrs.total_akhir - COALESCE(byrs.nominal_bayar, 0)), 0)
														END AS sisa,
														-- CASE
														-- 	WHEN byrs.id IS NULL THEN '0'
														-- 	ELSE (byrs.total_harga_pertemuan - COALESCE(byrs.nominal_bayar,0))
														-- END AS sisa,
														CASE
															WHEN byrs.id IS NULL THEN 'Belum'
															ELSE byrs.status
														END AS status,
														byrs.tanggal,
														byrs.waktu,
														byrs.tanggal_bayar,
														byrs.waktu_bayar,
														byrs.bukti_pembayaran,
														byrs.metode_pembayaran,
														CASE
															WHEN byrs.id IS NULL THEN '0'
															ELSE byrs.total_akhir
														END AS total_akhir,
														CASE WHEN byrs.id IS NULL THEN 1 ELSE 0 END AS perlu_ditagih
														FROM pendaftaran_paket pp
														JOIN siswa s ON s.id = pp.id_siswa
														JOIN jenjang j ON j.id = pp.id_jenjang
														JOIN kelas k ON k.id = pp.id_kelas
														JOIN paket p ON p.id = pp.id_paket
														JOIN paket_harga ph ON ph.id = pp.id_paket_harga
														LEFT JOIN (
															SELECT
															sb.id_beasiswa,
															sb.id_siswa
															FROM siswa_beasiswa sb
															WHERE STR_TO_DATE(sb.berlaku_mulai,'%d-%m-%Y') <= LAST_DAY(STR_TO_DATE(CONCAT('01-', LPAD('$periode_bulan',2,'0'), '-', '$periode_tahun'), '%d-%m-%Y'))
															AND STR_TO_DATE(sb.berlaku_sampai,'%d-%m-%Y') >= STR_TO_DATE(CONCAT('01-', LPAD('$periode_bulan',2,'0'), '-', '$periode_tahun'), '%d-%m-%Y')
														) sb ON sb.id_siswa = pp.id_siswa
														LEFT JOIN beasiswa b ON b.id = sb.id_beasiswa
														LEFT JOIN (
															SELECT
															a.id_jenjang,
															a.id_kelas,
															a.id_siswa,
															SUM(a.jumlah_meet) AS jumlah_meet
															FROM (
																SELECT
																j.id_jenjang,
																j.id_kelas,
																js.id_siswa,
																COUNT(js.id) AS jumlah_meet
																FROM jurnal_siswa js
																INNER JOIN jurnal j ON j.id = js.id_jurnal
																WHERE j.tanggal LIKE '%-$periode_bulan-$periode_tahun%'
																AND js.status_presensi = 'Hadir'
																GROUP BY js.id_siswa

																UNION ALL

																SELECT
																j.id_jenjang,
																j.id_kelas,
																js.id_siswa,
																COUNT(js.id) AS jumlah_meet
																FROM jurnal_siswa_pengganti js
																INNER JOIN jurnal_pengganti j ON j.id = js.id_jurnal
																WHERE j.tanggal LIKE '%-$periode_bulan-$periode_tahun%'
																AND js.status_presensi = 'Hadir'
																GROUP BY js.id_siswa
															) a
															GROUP BY a.id_siswa
														) js ON js.id_jenjang = pp.id_jenjang AND js.id_kelas = pp.id_kelas AND js.id_siswa = pp.id_siswa
														-- LEFT JOIN pembayaran byrs ON byrs.id_pendaftaran_paket = pp.id AND byrs.periode_tahun = '$periode_tahun' AND byrs.periode_bulan = '$periode_bulan'
														LEFT JOIN (SELECT p1.* FROM pembayaran p1
    INNER JOIN (
        SELECT 
            id_pendaftaran_paket,
            periode_tahun,
            LPAD(periode_bulan, 2, '0') AS periode_bulan_norm,
            COALESCE(
                MAX(CASE WHEN status IN ('Lunas', 'Sudah Bayar') THEN id END),
                MAX(id)
            ) AS id_pembayaran_utama
        FROM pembayaran
        WHERE periode_tahun = '$periode_tahun'
          AND LPAD(periode_bulan, 2, '0') = LPAD('$periode_bulan', 2, '0')
        GROUP BY 
            id_pendaftaran_paket,
            periode_tahun,
            LPAD(periode_bulan, 2, '0')
    ) x ON x.id_pembayaran_utama = p1.id
) byrs ON byrs.id_pendaftaran_paket = pp.id
AND byrs.periode_tahun = '$periode_tahun' AND LPAD(byrs.periode_bulan, 2, '0') = LPAD('$periode_bulan', 2, '0')
														WHERE pp.status_aktif = 1
														$where_kelas
														$where_jenis_administrasi
														$where_cari
														AND STR_TO_DATE(pp.tanggal_mulai,'%d-%m-%Y') <= LAST_DAY(STR_TO_DATE(CONCAT('01-', LPAD('$periode_bulan',2,'0'), '-', '$periode_tahun'), '%d-%m-%Y'))
														AND STR_TO_DATE(pp.tanggal_selesai,'%d-%m-%Y') >= STR_TO_DATE(CONCAT('01-', LPAD('$periode_bulan',2,'0'), '-', '$periode_tahun'), '%d-%m-%Y')
														")->result_array();
		return $sql;
	}

	// public function generate_tagihan()
	// {
	// 	$id_pendaftaran_paket = $this->input->post('id_pendaftaran_paket');
	// 	$periode_bulan = $this->input->post('periode_bulan');
	// 	$periode_tahun = $this->input->post('periode_tahun');
	// 	$id_beasiswa = $this->input->post('id_beasiswa');
	// 	$pertemuan = str_replace(',', '', $this->input->post('pertemuan'));
	// 	$nilai_beasiswa = str_replace(',', '', $this->input->post('nilai_beasiswa'));
	// 	$kas = str_replace(',', '', $this->input->post('kas'));
	// 	$harga_pertemuan = str_replace(',', '', $this->input->post('harga_pertemuan'));
	// 	$total_kas = str_replace(',', '', $this->input->post('total_kas'));
	// 	$total_harga_pertemuan = str_replace(',', '', $this->input->post('total_harga_pertemuan'));
	// 	$total_akhir = str_replace(',', '', $this->input->post('total_akhir'));
	// 	$diskon = str_replace(',', '', $this->input->post('diskon'));
	// 	$id_pegawai = $this->session->userdata('admin')['id_pegawai'];
	// 	$nama_pegawai = $this->session->userdata('admin')['nama_lengkap'];

	// 	$data = [
	// 		'id_pendaftaran_paket' => $id_pendaftaran_paket,
	// 		'periode_bulan' => $periode_bulan,
	// 		'periode_tahun' => $periode_tahun,
	// 		'pertemuan' => $pertemuan,
	// 		'id_beasiswa' => $id_beasiswa,
	// 		'nilai_beasiswa' => $nilai_beasiswa,
	// 		'harga_pertemuan' => $harga_pertemuan,
	// 		'kas' => $kas,
	// 		'diskon' => $diskon,
	// 		'total_kas' => $total_kas,
	// 		'total_harga_pertemuan' => $total_harga_pertemuan,
	// 		'total_akhir' => $total_akhir,
	// 		'status' => 'Belum',
	// 		'tanggal' => date('d-m-Y'),
	// 		'waktu' => date('H:i:s'),
	// 		'id_pegawai' => $id_pegawai,
	// 		'nama_pegawai' => $nama_pegawai
	// 	];

	// 	$this->db->trans_begin();
	// 	$this->db->insert('pembayaran', $data);
	// 	$this->db->trans_complete();

	// 	if ($this->db->trans_status() === FALSE) {
	// 		$this->db->trans_rollback();
	// 		$response = array(
	// 			'result' => 'false'
	// 		);
	// 	} else {
	// 		$this->db->trans_commit();
	// 		$response = array(
	// 			'result' => 'true'
	// 		);
	// 	}

	// 	return $response;
	// }

	private function angka($value)
	{
		if ($value === null || $value === '') {
			return 0;
		}

		return (int) preg_replace('/[^\d-]/', '', (string) $value);
	}

	private function bulan2($value)
	{
		$value = preg_replace('/\D/', '', (string) $value);
		$bulan = (int) $value;

		if ($bulan < 1 || $bulan > 12) {
			return '';
		}

		return str_pad($bulan, 2, '0', STR_PAD_LEFT);
	}

	private function lock_pembayaran($id_pendaftaran_paket, $periode_tahun, $periode_bulan)
	{
		$lock_key = 'pembayaran_' . $id_pendaftaran_paket . '_' . $periode_tahun . '_' . $periode_bulan;
		$lock_key = substr($lock_key, 0, 64);

		$row = $this->db->query(
			"SELECT GET_LOCK(?, 10) AS locked",
			[$lock_key]
		)->row_array();

		return [
			'status' => !empty($row) && (int) $row['locked'] === 1,
			'key' => $lock_key
		];
	}

	private function unlock_pembayaran($lock_key)
	{
		if ($lock_key != '') {
			$this->db->query("SELECT RELEASE_LOCK(?)", [$lock_key]);
		}
	}

	private function get_pembayaran_utama($id_pendaftaran_paket, $periode_tahun, $periode_bulan)
	{
		return $this->db->query("
        SELECT *
        FROM pembayaran
        WHERE id_pendaftaran_paket = ?
          AND periode_tahun = ?
          AND LPAD(periode_bulan, 2, '0') = ?
        ORDER BY 
            CASE 
                WHEN status IN ('Lunas', 'Sudah Bayar') THEN 1 
                ELSE 0 
            END DESC,
            id DESC
        LIMIT 1
    ", [
			$id_pendaftaran_paket,
			$periode_tahun,
			$periode_bulan
		])->row_array();
	}

	private function get_pembayaran_by_form($id_pembayaran, $id_pendaftaran_paket, $periode_tahun, $periode_bulan)
	{
		if ($id_pembayaran != '') {
			$row = $this->db->query("
            SELECT *
            FROM pembayaran
            WHERE id = ?
            LIMIT 1
        ", [$id_pembayaran])->row_array();

			if (!empty($row)) {
				return $row;
			}
		}

		return $this->get_pembayaran_utama(
			$id_pendaftaran_paket,
			$periode_tahun,
			$periode_bulan
		);
	}

	public function generate_tagihan()
	{
		$id_pendaftaran_paket = trim($this->input->post('id_pendaftaran_paket'));
		$periode_bulan = $this->bulan2($this->input->post('periode_bulan'));
		$periode_tahun = trim($this->input->post('periode_tahun'));

		if ($id_pendaftaran_paket == '' || $periode_bulan == '' || $periode_tahun == '') {
			return [
				'result' => 'false',
				'message' => 'Data tagihan tidak lengkap.'
			];
		}

		$lock = $this->lock_pembayaran($id_pendaftaran_paket, $periode_tahun, $periode_bulan);

		if (!$lock['status']) {
			return [
				'result' => 'false',
				'message' => 'Tagihan sedang diproses. Coba beberapa saat lagi.'
			];
		}

		try {
			$id_pegawai = $this->session->userdata('admin')['id_pegawai'] ?? '';
			$nama_pegawai = $this->session->userdata('admin')['nama_lengkap'] ?? '';

			$data = [
				'id_pendaftaran_paket' => $id_pendaftaran_paket,
				'periode_bulan' => $periode_bulan,
				'periode_tahun' => $periode_tahun,
				'pertemuan' => $this->angka($this->input->post('pertemuan')),
				'id_beasiswa' => $this->input->post('id_beasiswa') ?: 0,
				'nilai_beasiswa' => $this->angka($this->input->post('nilai_beasiswa')),
				'harga_pertemuan' => $this->angka($this->input->post('harga_pertemuan')),
				'kas' => $this->angka($this->input->post('kas')),
				'diskon' => $this->angka($this->input->post('diskon')),
				'total_kas' => $this->angka($this->input->post('total_kas')),
				'total_harga_pertemuan' => $this->angka($this->input->post('total_harga_pertemuan')),
				'total_akhir' => $this->angka($this->input->post('total_akhir')),
				'status' => 'Belum',
				'tanggal' => date('d-m-Y'),
				'waktu' => date('H:i:s'),
				'id_pegawai' => $id_pegawai,
				'nama_pegawai' => $nama_pegawai
			];

			if ($data['total_akhir'] < 0) {
				$data['total_akhir'] = 0;
			}

			$this->db->trans_begin();

			$pembayaran_lunas = $this->db->query("
            SELECT id, status
            FROM pembayaran
            WHERE id_pendaftaran_paket = ?
              AND periode_tahun = ?
              AND LPAD(periode_bulan, 2, '0') = ?
              AND status IN ('Lunas', 'Sudah Bayar')
            ORDER BY id DESC
            LIMIT 1
        ", [$id_pendaftaran_paket, $periode_tahun, $periode_bulan])->row_array();

			if (!empty($pembayaran_lunas)) {
				$this->db->trans_rollback();
				return [
					'result' => 'false',
					'message' => 'Tagihan sudah memiliki pembayaran, tidak boleh dibuat ulang.'
				];
			}

			$cek = $this->db->query("
            SELECT id, status
            FROM pembayaran
            WHERE id_pendaftaran_paket = ?
              AND periode_tahun = ?
              AND LPAD(periode_bulan, 2, '0') = ?
            ORDER BY id DESC
            LIMIT 1
        ", [
				$id_pendaftaran_paket,
				$periode_tahun,
				$periode_bulan
			])->row_array();

			if (!empty($cek)) {
				$this->db->where('id', $cek['id']);
				$this->db->update('pembayaran', $data);

				$id_pembayaran = $cek['id'];
				$message = 'Tagihan sudah ada, data diperbarui tanpa membuat duplikat.';
			} else {
				$this->db->insert('pembayaran', $data);

				$id_pembayaran = $this->db->insert_id();
				$message = 'Tagihan berhasil dibuat.';
			}

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();

				return [
					'result' => 'false',
					'message' => 'Tagihan gagal disimpan.'
				];
			}

			$this->db->trans_commit();

			return [
				'result' => 'true',
				'id_pembayaran' => $id_pembayaran,
				'message' => $message
			];
		} finally {
			$this->unlock_pembayaran($lock['key']);
		}
	}

	public function edit()
	{
		$id_tagihan_pembayaran = $this->input->post('id_tagihan_pembayaran');
		$nama_tagihan_pembayaran = $this->input->post('nama_tagihan_pembayaran');

		$data = [
			'nama_tagihan_pembayaran' => $nama_tagihan_pembayaran
		];

		$this->db->trans_begin();
		$this->db->update('tagihan_pembayaran', $data, ['id' => $id_tagihan_pembayaran]);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = array(
				'result' => 'false'
			);
		} else {
			$this->db->trans_commit();
			$response = array(
				'result' => 'true'
			);
		}

		return $response;
	}

	public function hapus()
	{
		$id = $this->input->post('id');

		$data = [
			'status_aktif' => '0'
		];

		$this->db->trans_begin();
		$this->db->update('tagihan_pembayaran', $data, ['id' => $id]);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = array(
				'result' => 'false'
			);
		} else {
			$this->db->trans_commit();
			$response = array(
				'result' => 'true'
			);
		}

		return $response;
	}

	public function siswa_result()
	{
		$sql = $this->db->query("SELECT
                              a.*
                              FROM siswa a
                              WHERE a.status_aktif = '1'
                              AND a.status_siswa = 'LAMA'
                              ORDER BY a.id DESC
														")->result_array();

		return $sql;
	}

	public function tambah_bukti_pembayaran()
	{
		try {
			$this->db->trans_begin();
			$id_pendaftaran = $this->input->post('id_pendaftaran');
			$periode_bulan = sprintf("%02d", $this->input->post('periode_bulan'));
			$periode_tahun = $this->input->post('periode_tahun');
			$this->load->library('upload');

			$src_gambar = '';
			if ($_FILES['file']['name'] != '') {
				$config['upload_path'] = './storage/bukti_pembayaran';
				$config['allowed_types'] = '*';
				$config['encrypt_name'] = true;
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('file')) {
					$erros = array('error' => $this->upload->display_errors());
					$src_gambar = '';
				} else {
					$upload_data = $this->upload->data();
					$source = $upload_data['full_path'];

					$mime = mime_content_type($source);
					if (strpos($mime, 'image') === false) {
						unlink($source);
						$response = array(
							'status' => false,
							'message' => 'File bukti pembayaran harus berupa gambar'
						);
					}


					$type = @exif_imagetype($source);

					switch ($type) {
						case IMAGETYPE_JPEG:
							$img = imagecreatefromjpeg($source);
							break;

						case IMAGETYPE_PNG:
							$img = imagecreatefrompng($source);

							$bg = imagecreatetruecolor(imagesx($img), imagesy($img));
							$white = imagecolorallocate($bg, 255, 255, 255);
							imagefilledrectangle($bg, 0, 0, imagesx($img), imagesy($img), $white);
							imagecopy($bg, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
							$img = $bg;
							break;

						case IMAGETYPE_WEBP:
							$img = imagecreatefromwebp($source);
							break;

						case IMAGETYPE_GIF:
							$img = imagecreatefromgif($source);
							break;

						case IMAGETYPE_BMP:
							$img = imagecreatefrombmp($source);
							break;

						default:
							unlink($source);
							$response = array(
								'status' => false,
								'message' => 'File bukti pembayaran harus berupa gambar'
							);

					}

					imagejpeg($img, $source, 80);
					imagedestroy($img);

					$new_path = preg_replace('/\.[^.]+$/', '.jpg', $source);
					if ($source !== $new_path) {
						rename($source, $new_path);
					}

					$gambar = basename($new_path);
					$src_gambar = 'storage/bukti_pembayaran/' . $gambar;


				}
			}

			$inputan = array(
				'bukti_pembayaran' => $src_gambar,
				'status' => 'Sudah Bayar',
				'tanggal_bayar' => date('d-m-Y'),
				'waktu_bayar' => date('H:i:s')
			);

			$this->db->where('periode_bulan', $periode_bulan);
			$this->db->where('periode_tahun', $periode_tahun);
			$this->db->where('id_pendaftaran_paket', $id_pendaftaran);
			$this->db->update('pembayaran', $inputan);

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$response = array(
					'status' => false,
					'message' => "File Gagal Diupload"
				);
			} else {
				$this->db->trans_commit();
				$response = array(
					'status' => true,
					'message' => "File Berhasil Diupload"
				);
			}

			return $response;
		} catch (\Exception $ex) {
			$response = array(
				'status' => false,
				'code' => $ex->getCode(),
				'message' => $ex->getMessage(),
			);

			return $response;
		}
	}

	// public function proses_pembayaran()
	// {
	// 	$id_pendaftaran_paket = $this->input->post('id_pendaftaran_paket');
	// 	$periode_bulan = sprintf("%02d", $this->input->post('periode_bulan'));
	// 	$periode_tahun = $this->input->post('periode_tahun');
	// 	$metode_pembayaran = $this->input->post('metode_pembayaran');
	// 	$total_harga_pertemuan = str_replace(',', '', $this->input->post('total_harga_pertemuan'));
	// 	$nominal_bayar = str_replace(',', '', $this->input->post('nominal_bayar'));
	// 	$kembali = str_replace(',', '', $this->input->post('kembali'));

	// 	$src_gambar = null;
	// 	if ($metode_pembayaran != 'Cash') {
	// 		$this->load->library('upload');

	// 		if ($_FILES['bukti_pembayaran']['name'] != '') {
	// 			$config['upload_path'] = './storage/bukti_pembayaran';
	// 			$config['allowed_types'] = 'jpg|jpeg|png|pdf';
	// 			$config['encrypt_name'] = true;
	// 			$this->upload->initialize($config);

	// 			if (!$this->upload->do_upload('bukti_pembayaran')) {
	// 				$erros = array('error' => $this->upload->display_errors());
	// 				$src_gambar = '';
	// 			} else {
	// 				$gambar = $this->upload->data()['file_name'];
	// 				$src_gambar = 'storage/bukti_pembayaran/' . $gambar;
	// 			}
	// 		}
	// 	}

	// 	$data = [
	// 		'bukti_pembayaran' => $src_gambar,
	// 		'nominal_bayar' => $nominal_bayar,
	// 		'metode_pembayaran' => $metode_pembayaran,
	// 		'kembali' => $kembali,
	// 		'tanggal_bayar' => date('d-m-Y'),
	// 		'waktu_bayar' => date('H:i:s'),
	// 		'status' => 'Lunas'
	// 	];

	// 	$this->db->trans_begin();
	// 	$this->db->where('periode_bulan', $periode_bulan);
	// 	$this->db->where('periode_tahun', $periode_tahun);
	// 	$this->db->where('id_pendaftaran_paket', $id_pendaftaran_paket);
	// 	$this->db->update('pembayaran', $data);
	// 	$this->db->trans_complete();

	// 	if ($this->db->trans_status() === FALSE) {
	// 		$this->db->trans_rollback();
	// 		$response = array(
	// 			'result' => 'false'
	// 		);
	// 	} else {
	// 		$this->db->trans_commit();
	// 		$response = array(
	// 			'result' => 'true'
	// 		);
	// 	}

	// 	return $response;
	// }
	public function proses_pembayaran()
	{
		$id_pembayaran = trim($this->input->post('id_pembayaran'));
		$id_pendaftaran_paket = trim($this->input->post('id_pendaftaran_paket'));
		$periode_bulan = $this->bulan2($this->input->post('periode_bulan'));
		$periode_tahun = trim($this->input->post('periode_tahun'));
		$metode_pembayaran = trim($this->input->post('metode_pembayaran'));

		$nominal_bayar = $this->angka($this->input->post('nominal_bayar'));

		if ($id_pendaftaran_paket == '' || $periode_bulan == '' || $periode_tahun == '') {
			return [
				'result' => 'false',
				'message' => 'Data pembayaran tidak lengkap.'
			];
		}

		if ($nominal_bayar <= 0) {
			return [
				'result' => 'false',
				'message' => 'Nominal bayar wajib diisi.'
			];
		}

		$src_gambar = null;

		if ($metode_pembayaran != 'Cash') {
			$this->load->library('upload');

			if (empty($_FILES['bukti_pembayaran']['name'])) {
				return [
					'result' => 'false',
					'message' => 'Bukti pembayaran wajib diupload.'
				];
			}

			$config['upload_path'] = './storage/bukti_pembayaran';
			$config['allowed_types'] = 'jpg|jpeg|png|pdf';
			$config['encrypt_name'] = true;
			$config['max_size'] = 2048;

			$this->upload->initialize($config);

			if (!$this->upload->do_upload('bukti_pembayaran')) {
				return [
					'result' => 'false',
					'message' => strip_tags($this->upload->display_errors())
				];
			}

			$gambar = $this->upload->data()['file_name'];
			$src_gambar = 'storage/bukti_pembayaran/' . $gambar;
		}

		$lock = $this->lock_pembayaran($id_pendaftaran_paket, $periode_tahun, $periode_bulan);

		if (!$lock['status']) {
			return [
				'result' => 'false',
				'message' => 'Pembayaran sedang diproses. Coba beberapa saat lagi.'
			];
		}

		try {
			$this->db->trans_begin();

			$row = $this->get_pembayaran_by_form(
				$id_pembayaran,
				$id_pendaftaran_paket,
				$periode_tahun,
				$periode_bulan
			);

			if (empty($row)) {
				$this->db->trans_rollback();

				return [
					'result' => 'false',
					'message' => 'Tagihan pembayaran tidak ditemukan.'
				];
			}

			if ($row['status'] == 'Lunas') {
				$this->db->trans_rollback();

				return [
					'result' => 'false',
					'message' => 'Tagihan ini sudah lunas.'
				];
			}

			$total_tagihan = $this->angka($row['total_akhir']);
			$kembali = $nominal_bayar > $total_tagihan ? $nominal_bayar - $total_tagihan : 0;
			$status = $nominal_bayar >= $total_tagihan ? 'Lunas' : 'Sudah Bayar';

			$data = [
				'nominal_bayar' => $nominal_bayar,
				'metode_pembayaran' => $metode_pembayaran,
				'kembali' => $kembali,
				'tanggal_bayar' => date('d-m-Y'),
				'waktu_bayar' => date('H:i:s'),
				'status' => $status
			];

			if ($src_gambar !== null) {
				$data['bukti_pembayaran'] = $src_gambar;
			}

			$this->db->where('id', $row['id']);
			$this->db->update('pembayaran', $data);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();

				return [
					'result' => 'false',
					'message' => 'Pembayaran gagal disimpan.'
				];
			}

			$this->db->trans_commit();

			return [
				'result' => 'true',
				'message' => 'Pembayaran berhasil disimpan.'
			];
		} finally {
			$this->unlock_pembayaran($lock['key']);
		}
	}

	// public function batalkan_pembayaran()
	// {
	// 	$id_pendaftaran_paket = $this->input->post('id_pendaftaran_paket');
	// 	$periode_bulan = $this->input->post('periode_bulan');
	// 	$periode_tahun = $this->input->post('periode_tahun');
	// 	$src_gambar = $this->input->post('src_gambar');

	// 	$file_path = FCPATH . $src_gambar;
	// 	if (file_exists($file_path)) {
	// 		unlink($file_path);
	// 	}

	// 	$data = [
	// 		'status' => 'Belum'
	// 	];

	// 	$this->db->trans_begin();
	// 	$this->db->where('periode_bulan', $periode_bulan);
	// 	$this->db->where('periode_tahun', $periode_tahun);
	// 	$this->db->where('id_pendaftaran_paket', $id_pendaftaran_paket);
	// 	$this->db->update('pembayaran', $data);
	// 	$this->db->trans_complete();

	// 	if ($this->db->trans_status() === FALSE) {
	// 		$this->db->trans_rollback();
	// 		$response = array(
	// 			'result' => 'false'
	// 		);
	// 	} else {
	// 		$this->db->trans_commit();
	// 		$response = array(
	// 			'result' => 'true'
	// 		);
	// 	}

	// 	return $response;
	// }

	public function batalkan_pembayaran()
	{
		$id_pembayaran = trim($this->input->post('id_pembayaran'));
		$id_pendaftaran_paket = trim($this->input->post('id_pendaftaran_paket'));
		$periode_bulan = $this->bulan2($this->input->post('periode_bulan'));
		$periode_tahun = trim($this->input->post('periode_tahun'));

		$lock = $this->lock_pembayaran($id_pendaftaran_paket, $periode_tahun, $periode_bulan);

		if (!$lock['status']) {
			return [
				'result' => 'false',
				'message' => 'Pembayaran sedang diproses.'
			];
		}

		try {
			$this->db->trans_begin();

			$row = $this->get_pembayaran_by_form(
				$id_pembayaran,
				$id_pendaftaran_paket,
				$periode_tahun,
				$periode_bulan
			);

			if (empty($row)) {
				$this->db->trans_rollback();

				return [
					'result' => 'false',
					'message' => 'Data pembayaran tidak ditemukan.'
				];
			}

			if (!empty($row['bukti_pembayaran'])) {
				$file_path = FCPATH . $row['bukti_pembayaran'];

				if (file_exists($file_path)) {
					unlink($file_path);
				}
			}

			$data = [
				'status' => 'Belum',
				'nominal_bayar' => null,
				'metode_pembayaran' => null,
				'kembali' => null,
				'bukti_pembayaran' => null,
				'tanggal_bayar' => null,
				'waktu_bayar' => null
			];

			$this->db->where('id', $row['id']);
			$this->db->update('pembayaran', $data);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();

				return [
					'result' => 'false',
					'message' => 'Pembatalan pembayaran gagal.'
				];
			}

			$this->db->trans_commit();

			return [
				'result' => 'true',
				'message' => 'Pembayaran berhasil dibatalkan.'
			];
		} finally {
			$this->unlock_pembayaran($lock['key']);
		}
	}

	public function edit_pembayaran()
	{
		$id_pembayaran = $this->input->post('id_pembayaran');
		$total_harga_pertemuan = str_replace(',', '', $this->input->post('total_harga_pertemuan'));
		$harga_pertemuan = str_replace(',', '', $this->input->post('harga_pertemuan'));
		$diskon = str_replace(',', '', $this->input->post('diskon'));
		$pertemuan = str_replace(',', '', $this->input->post('pertemuan'));
		$kas = str_replace(',', '', $this->input->post('kas'));
		$nilai_beasiswa = str_replace(',', '', $this->input->post('nilai_beasiswa'));
		$total_kas = str_replace(',', '', $this->input->post('total_kas'));
		$total_akhir = str_replace(',', '', $this->input->post('total_akhir'));
		$total_harga_pertemuan = str_replace(',', '', $this->input->post('total_harga_pertemuan'));
		$data = [
			'total_akhir' => $total_akhir,
			'nilai_beasiswa' => $nilai_beasiswa,
			'pertemuan' => $pertemuan,
			'kas' => $kas,
			'total_kas' => $total_kas,
			'diskon' => $diskon,
			'harga_pertemuan' => $harga_pertemuan,
			'total_harga_pertemuan' => $total_harga_pertemuan,
		];

		$this->db->trans_begin();
		$this->db->where('id', $id_pembayaran);
		$this->db->update('pembayaran', $data);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = array(
				'result' => 'false'
			);
		} else {
			$this->db->trans_commit();
			$response = array(
				'result' => 'true'
			);
		}

		return $response;
	}
	// public function konfirmasi_pembayaran()
	// {
	// 	$id_pendaftaran_paket = $this->input->post('id_pendaftaran_paket');
	// 	$periode_bulan = $this->input->post('periode_bulan');
	// 	$periode_tahun = $this->input->post('periode_tahun');
	// 	$total_harga_pertemuan = str_replace(',', '', $this->input->post('total_harga_pertemuan'));

	// 	$data = [
	// 		'nominal_bayar' => $total_harga_pertemuan,
	// 		'metode_pembayaran' => 'Transfer',
	// 		'kembali' => '0',
	// 		'status' => 'Lunas'
	// 	];

	// 	$this->db->trans_begin();
	// 	$this->db->where('periode_bulan', $periode_bulan);
	// 	$this->db->where('periode_tahun', $periode_tahun);
	// 	$this->db->where('id_pendaftaran_paket', $id_pendaftaran_paket);
	// 	$this->db->update('pembayaran', $data);
	// 	$this->db->trans_complete();

	// 	if ($this->db->trans_status() === FALSE) {
	// 		$this->db->trans_rollback();
	// 		$response = array(
	// 			'result' => 'false'
	// 		);
	// 	} else {
	// 		$this->db->trans_commit();
	// 		$response = array(
	// 			'result' => 'true'
	// 		);
	// 	}

	// 	return $response;
	// }

	public function konfirmasi_pembayaran()
	{
		$id_pembayaran = trim($this->input->post('id_pembayaran'));
		$id_pendaftaran_paket = trim($this->input->post('id_pendaftaran_paket'));
		$periode_bulan = $this->bulan2($this->input->post('periode_bulan'));
		$periode_tahun = trim($this->input->post('periode_tahun'));

		$lock = $this->lock_pembayaran($id_pendaftaran_paket, $periode_tahun, $periode_bulan);

		if (!$lock['status']) {
			return [
				'result' => 'false',
				'message' => 'Pembayaran sedang diproses.'
			];
		}

		try {
			$this->db->trans_begin();

			$row = $this->get_pembayaran_by_form(
				$id_pembayaran,
				$id_pendaftaran_paket,
				$periode_tahun,
				$periode_bulan
			);

			if (empty($row)) {
				$this->db->trans_rollback();

				return [
					'result' => 'false',
					'message' => 'Data pembayaran tidak ditemukan.'
				];
			}

			$total_tagihan = $this->angka($row['total_akhir']);

			$data = [
				'nominal_bayar' => $total_tagihan,
				'metode_pembayaran' => $row['metode_pembayaran'] ?: 'Transfer',
				'kembali' => 0,
				'tanggal_bayar' => date('d-m-Y'),
				'waktu_bayar' => date('H:i:s'),
				'status' => 'Lunas'
			];

			$this->db->where('id', $row['id']);
			$this->db->update('pembayaran', $data);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();

				return [
					'result' => 'false',
					'message' => 'Konfirmasi pembayaran gagal.'
				];
			}

			$this->db->trans_commit();

			return [
				'result' => 'true',
				'message' => 'Pembayaran berhasil dikonfirmasi.'
			];
		} finally {
			$this->unlock_pembayaran($lock['key']);
		}
	}

	public function jurnal_siswa_result()
	{
		$periode_bulan = $this->input->post('periode_bulan');
		$periode_tahun = $this->input->post('periode_tahun');
		$id_siswa = $this->input->post('id_siswa');

		$sql = $this->db->query("SELECT
															j.nama_pegawai,
															j.tanggal,
															j.waktu,
															js.status_presensi
															FROM jurnal_siswa js
															INNER JOIN jurnal j ON j.id = js.id_jurnal
															WHERE j.tanggal LIKE '%-$periode_bulan-$periode_tahun'
															AND js.id_siswa = '$id_siswa'

															UNION ALL

															SELECT
															j.nama_pegawai,
															j.tanggal,
															j.waktu,
															js.status_presensi
															FROM jurnal_siswa_pengganti js
															INNER JOIN jurnal_pengganti j ON j.id = js.id_jurnal
															WHERE j.tanggal LIKE '%-$periode_bulan-$periode_tahun'
															AND js.id_siswa = '$id_siswa'
															");

		return $sql->result_array();
	}
	public function kelas_result()
	{
		$sql = $this->db->query(" SELECT k.*,
	                             j.nama_jenjang
	                             FROM kelas k
	                             INNER JOIN jenjang j ON k.id_jenjang = j.id
	                             WHERE k.status_aktif = '1'
								 ORDER BY k.id_jenjang ASC,
								 k.urutan_kelas ASC
								 ");
		return $sql->result_array();
	}
}
?>