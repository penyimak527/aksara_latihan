<?php
class Tagihan_online extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
	}

	private function base64url_decode($data)
	{
		$data = strtr($data, '-_', '+/');
		$pad = strlen($data) % 4;
		if ($pad) {
			$data .= str_repeat('=', 4 - $pad);
		}
		return base64_decode($data, true);
	}

	public function c($id_siswa)
	{
		$id_siswa = $this->base64url_decode($id_siswa);

		$sql_row = $this->db->query("SELECT
																	s.nis,
																	s.nama_siswa,
																	s.nama_wali,
																	s.alamat,
																	j.nama_jenjang,
																	k.nama_kelas,
																	p.nama_paket,
																	DATE_FORMAT(MIN(STR_TO_DATE(tanggal_mulai, '%d-%m-%Y')), '%d-%m-%Y') AS tanggal_mulai
																	FROM pendaftaran_paket pp
																	JOIN siswa s ON s.id = pp.id_siswa
																	JOIN jenjang j ON j.id = pp.id_jenjang
																	JOIN kelas k ON k.id = pp.id_kelas
																	JOIN paket p ON p.id = pp.id_paket
																	WHERE s.id = '$id_siswa'
																	AND pp.status_aktif = '1'
																")->row_array();

		$sql_row_pem = $this->db->query("SELECT
																		  CASE
																		    WHEN (p.periode_tahun > YEAR(CURDATE()))
																		         OR (p.periode_tahun = YEAR(CURDATE()) AND p.periode_bulan > MONTH(CURDATE()))
																		      THEN CONCAT(LPAD(p.periode_bulan, 2, '0'), '-', p.periode_tahun)
																		    ELSE DATE_FORMAT(CURDATE(), '%m-%Y')
																		  END AS hasil_periode
																		FROM (
																		  SELECT byrs.periode_tahun, byrs.periode_bulan
																		  FROM pembayaran byrs
																		  INNER JOIN (
																		    SELECT id
																		    FROM pendaftaran_paket
																		    WHERE status_aktif = 1 AND id_siswa = '$id_siswa'
																		  ) pp ON byrs.id_pendaftaran_paket = pp.id
																		  ORDER BY byrs.periode_tahun DESC, byrs.periode_bulan DESC
																		  LIMIT 1
																		) AS p;
																		")->row_array();

		$data['row'] = $sql_row;

		$tanggal_bulan_mulai = substr($sql_row['tanggal_mulai'], 3, 2);
		$tanggal_tahun_mulai = substr($sql_row['tanggal_mulai'], 6, 4);

		list($bulan_periode, $tahun_periode) = explode('-', $sql_row_pem['hasil_periode']);
		$tanggal_bulan_selesai = ($tahun_periode > date('Y') || ($tahun_periode == date('Y') && $bulan_periode > date('m'))) ? $bulan_periode : date('m');
		$tanggal_tahun_selesai = ($tahun_periode > date('Y') || ($tahun_periode == date('Y') && $bulan_periode > date('m'))) ? $tahun_periode : date('Y');

		$bulan_akhir = ltrim($tanggal_bulan_selesai, '0');
		$tahun_akhir = ltrim($tanggal_tahun_selesai, '0');

		$bulan_filter = ltrim($tanggal_bulan_mulai, '0');
		$tahun_filter = ltrim($tanggal_tahun_mulai, '0');

		$result = [];
		while ($tahun_filter < $tahun_akhir || ($tahun_filter == $tahun_akhir && $bulan_filter <= $bulan_akhir)) {
			$bulan_filter = str_pad($bulan_filter, 2, '0', STR_PAD_LEFT);

			$row_pendaftaran = $this->db->query("SELECT
																					 a.*
																					 FROM(
																						 SELECT
																						 	pp.id AS id_pendaftaran,
																							pp.diskon,
		 																					j.nama_jenjang,
		 																					k.nama_kelas,
		 																					p.nama_paket,
																							byrs.harga_pertemuan,
																							b.nama_beasiswa,
																							CASE
																								WHEN byrs.id IS NULL THEN b.nilai
																								ELSE byrs.nilai_beasiswa
																							END AS nilai_beasiswa,
		 																					CASE
		 																						WHEN byrs.id IS NULL THEN IFNULL(js.jumlah_meet, '1')
		 																						ELSE byrs.pertemuan
		 																					END AS jumlah_meet,
		 																					CASE
		 																						WHEN byrs.id IS NULL THEN '$tahun_filter'
		 																						ELSE byrs.periode_tahun
		 																					END AS periode_tahun,
		 																					CASE
		 																						WHEN byrs.id IS NULL THEN '$bulan_filter'
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
		 																						WHEN byrs.id IS NULL THEN '0'
		 																						ELSE (byrs.total_harga_pertemuan - COALESCE(byrs.nominal_bayar,0))
		 																					END AS sisa,
		 																					CASE
		 																						WHEN byrs.id IS NULL THEN 'Belum'
		 																						ELSE byrs.status
		 																					END AS status,
		 																					CASE WHEN byrs.id IS NULL THEN 1 ELSE 0 END AS perlu_ditagih
		 																					FROM pendaftaran_paket pp
		 																					JOIN jenjang j ON j.id = pp.id_jenjang
		 																					JOIN kelas k ON k.id = pp.id_kelas
		 																					JOIN paket p ON p.id = pp.id_paket
		 																					JOIN paket_harga ph ON ph.id = pp.id_paket_harga
																							LEFT JOIN (
																								SELECT
																								sb.id_beasiswa,
																								sb.id_siswa
																								FROM siswa_beasiswa sb
																								WHERE STR_TO_DATE(sb.berlaku_mulai,'%d-%m-%Y') <= LAST_DAY(STR_TO_DATE(CONCAT('01-', LPAD('$bulan_filter',2,'0'), '-', '$tahun_filter'), '%d-%m-%Y'))
																								AND STR_TO_DATE(sb.berlaku_sampai,'%d-%m-%Y') >= STR_TO_DATE(CONCAT('01-', LPAD('$bulan_filter',2,'0'), '-', '$tahun_filter'), '%d-%m-%Y')
																								AND sb.id_siswa = '$id_siswa'
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
																									WHERE j.tanggal LIKE '%-$bulan_filter-$tahun_filter%'
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
																									WHERE j.tanggal LIKE '%-$bulan_filter-$tahun_filter%'
																									AND js.status_presensi = 'Hadir'
																									GROUP BY js.id_siswa
																								) a
																								GROUP BY a.id_siswa
		 																					) js ON js.id_jenjang = pp.id_jenjang AND js.id_kelas = pp.id_kelas AND js.id_siswa = pp.id_siswa
		 																					INNER JOIN pembayaran byrs ON byrs.id_pendaftaran_paket = pp.id AND byrs.periode_tahun = '$tahun_filter' AND byrs.periode_bulan = '$bulan_filter'
		 																					WHERE pp.status_aktif = 1
																							AND pp.id_siswa = '$id_siswa'
																							AND STR_TO_DATE(pp.tanggal_mulai,'%d-%m-%Y') <= LAST_DAY(STR_TO_DATE(CONCAT('01-', LPAD('$bulan_filter',2,'0'), '-', '$tahun_filter'), '%d-%m-%Y'))
																							AND STR_TO_DATE(pp.tanggal_selesai,'%d-%m-%Y') >= STR_TO_DATE(CONCAT('01-', LPAD('$bulan_filter',2,'0'), '-', '$tahun_filter'), '%d-%m-%Y')
																					 ) a
																					 -- WHERE (a.status != 'Lunas' AND a.status != 'Sudah Bayar')
																					")->row_array();

			if (!empty($row_pendaftaran)) {
				$result[] = [
					'id_pendaftaran' => $row_pendaftaran['id_pendaftaran'],
					'periode_bulan' => $row_pendaftaran['periode_bulan'],
					'periode_tahun' => $row_pendaftaran['periode_tahun'],
					'nama_jenjang' => $row_pendaftaran['nama_jenjang'],
					'nama_kelas' => $row_pendaftaran['nama_kelas'],
					'nama_paket' => $row_pendaftaran['nama_paket'],
					'jumlah_meet' => $row_pendaftaran['jumlah_meet'],
					'total_kas' => $row_pendaftaran['total_kas'],
					'total_harga_pertemuan' => $row_pendaftaran['total_harga_pertemuan'],
					'harga_pertemuan' => $row_pendaftaran['harga_pertemuan'],
					'nilai_beasiswa' => $row_pendaftaran['nilai_beasiswa'],
					'nama_beasiswa' => $row_pendaftaran['nama_beasiswa'],
					'diskon' => $row_pendaftaran['diskon'],
					'status' => $row_pendaftaran['status'],
					'perlu_ditagih' => $row_pendaftaran['perlu_ditagih'],
				];
			}

			$bulan_filter++;
			if ($bulan_filter > 12) {
				$bulan_filter = 1;
				$tahun_filter++;
			}
		}

		$data['res'] = $result;

		$this->load->view('tagihan_online', $data);
	}
}
?>
