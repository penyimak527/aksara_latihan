<?php
class Tagihan_pembayaran extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/administrasi/M_tagihan_pembayaran', 'model');
	}

	public function index()
	{
		if ($this->session->userdata('admin')['username'] == null) {
			redirect('/');
		}

		$data['title'] = 'Tagihan Pembayaran';
		$this->load->view('template/header', $data);
		$this->load->view('admin/administrasi/tagihan_pembayaran', $data);
		$this->load->view('template/footer');
	}

	public function tagihan_pembayaran_result()
	{
		$data = $this->model->tagihan_pembayaran_result();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function tambah()
	{
		$data = $this->model->tambah();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function edit()
	{
		$data = $this->model->edit();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function hapus()
	{
		$data = $this->model->hapus();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function siswa_result()
	{
		$data = $this->model->siswa_result();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function kelas_result()
	{
		$data = $this->model->kelas_result();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function generate_tagihan()
	{
		$data = $this->model->generate_tagihan();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function tagihan_online($id_siswa)
	{
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

		$data['row'] = $sql_row;

		$tanggal_bulan_mulai = substr($sql_row['tanggal_mulai'], 3, 2);
		$tanggal_tahun_mulai = substr($sql_row['tanggal_mulai'], 6, 4);

		$tanggal_bulan_selesai = date('m');
		$tanggal_tahun_selesai = date('Y');

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
		 																					s.nama_siswa,
		 																					j.nama_jenjang,
		 																					k.nama_kelas,
		 																					p.nama_paket,
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
		 																					JOIN siswa s ON s.id = pp.id_siswa
		 																					JOIN jenjang j ON j.id = pp.id_jenjang
		 																					JOIN kelas k ON k.id = pp.id_kelas
		 																					JOIN paket p ON p.id = pp.id_paket
		 																					JOIN paket_harga ph ON ph.id = pp.id_paket_harga
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
		 																					LEFT JOIN pembayaran byrs ON byrs.id_pendaftaran_paket = pp.id AND byrs.periode_tahun = '$tahun_filter' AND byrs.periode_bulan = '$bulan_filter'
		 																					WHERE pp.status_aktif = 1
																							AND pp.id_siswa = '$id_siswa'
																							AND STR_TO_DATE(pp.tanggal_mulai,'%d-%m-%Y') <= LAST_DAY(STR_TO_DATE(CONCAT('01-', LPAD('$bulan_filter',2,'0'), '-', '$tahun_filter'), '%d-%m-%Y'))
																							AND STR_TO_DATE(pp.tanggal_selesai,'%d-%m-%Y') >= STR_TO_DATE(CONCAT('01-', LPAD('$bulan_filter',2,'0'), '-', '$tahun_filter'), '%d-%m-%Y')
																					 ) a
																					 WHERE (a.status != 'Lunas' AND a.status != 'Sudah Bayar')
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

		$this->load->view('admin/administrasi/tagihan_online', $data);
	}

	function tambah_bukti_pembayaran()
	{
		$data = $this->model->tambah_bukti_pembayaran();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	function proses_pembayaran()
	{
		$data = $this->model->proses_pembayaran();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	function batalkan_pembayaran()
	{
		$data = $this->model->batalkan_pembayaran();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	function konfirmasi_pembayaran()
	{
		$data = $this->model->konfirmasi_pembayaran();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}
	function edit_pembayaran()
	{
		$data = $this->model->edit_pembayaran();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	function jurnal_siswa_result()
	{
		$data = $this->model->jurnal_siswa_result();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}
}
?>