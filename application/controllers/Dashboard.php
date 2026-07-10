<?php
class Dashboard extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_dashboard', 'model');
	}

	public function index()
	{
		if ($this->session->userdata('admin')['username'] == null) {
			redirect('/');
		}

		$id_pegawai = $this->session->userdata('admin')['id_pegawai'];

		$data['title'] = 'Dashboard';

		$sql_kelas = "SELECT
									ks.id_kelas,
									j.nama_jenjang,
									k.nama_kelas
									FROM kelas_setting ks
									INNER JOIN jenjang j ON ks.id_jenjang = j.id
									INNER JOIN kelas k ON ks.id_kelas = k.id
									WHERE ks.status_aktif = '1'
									AND ks.id_pegawai = ?";
		$res_kelas = $this->db->query($sql_kelas, [$id_pegawai])->result_array();

		$data['data_kelas'] = $res_kelas;
		$data['total_siswa_aktif'] = $this->db->get_where('siswa', ['status_aktif' => 1])->num_rows();
		$data['total_paket_aktif'] = $this->db->get_where('pendaftaran_paket', ['status_aktif' => 1])->num_rows();

		$bulan = date('m');
		$tahun = date('Y');

		// $sql = "SELECT *
		// FROM pembayaran
		// WHERE   MONTH(STR_TO_DATE(tanggal_bayar, '%d-%m-%Y')) = ?
		//  AND YEAR(STR_TO_DATE(tanggal_bayar, '%d-%m-%Y'))  = ?; ";
		// $row = $this->db->query($sql, [(int) $bulan, (int) $tahun])->result_array();

		// $data_belum = 0;
		// $data_lunas = 0;
		// $data_cicilan = 0;

		// foreach ($row as $key => $value) {
		// 	if ($value['status'] == 'Belum') {
		// 		$data_belum += !empty($value['total_harga_pertemuan']) ? $value['total_harga_pertemuan'] : 0;
		// 	}
		// 	if ($value['status'] == 'Lunas') {
		// 		$data_lunas += !empty($value['nominal_bayar']) ? $value['nominal_bayar'] : 0;
		// 	}
		// 	if ($value['status'] == 'Sudah Bayar') {
		// 		$data_cicilan += !empty($value['nominal_bayar']) ? $value['nominal_bayar'] : 0;
		// 	}
		// }
		$sql = "SELECT *
        FROM daftar_awal
        WHERE YEAR(STR_TO_DATE(tanggal, '%d-%m-%Y'))  = ?; ";
		$row = $this->db->query($sql, [(int) $tahun])->result_array();

		$daftar_awal = 0;

		foreach ($row as $key => $value) {
			$nominal = floatval(str_replace(['.', ',', 'Rp', ' '], '', $value['nominal_bayar'] ?? 0));
			$daftar_awal += $nominal;
		}
		$sql = "SELECT *
        FROM daftar_ulang
        WHERE YEAR(STR_TO_DATE(tanggal, '%d-%m-%Y'))  = ?; ";
		$row = $this->db->query($sql, [(int) $tahun])->result_array();

		$daftar_ulang = 0;

		foreach ($row as $key => $value) {
			$daftar_ulang += (int) $value['nominal_bayar'];
		}

		$sql = "SELECT a.*,s.nama_siswa,k.nama_kelas,s.id as id_siswa,s.hp_wali
        FROM pembayaran a left join pendaftaran_paket pp on a.id_pendaftaran_paket = pp.id
		left join siswa s on pp.id_siswa = s.id left join kelas k on s.id_kelas = k.id
      WHERE STR_TO_DATE(tanggal_bayar, '%d-%m-%Y')
      >= DATE(CONCAT(?, '-', LPAD(?, 2, '0'), '-01'))";
		$row_jatuh = $this->db->query($sql, [(int) $bulan, (int) $tahun])->result_array();

		$data['daftar_ulang'] = $daftar_ulang;
		$data['daftar_awal'] = $daftar_awal;
		// start
		// $data['total_cicilan'] = $data_cicilan;
		// $data['total_lunas'] = $data_lunas;

		// $data['total_belum'] = $data_belum;
		// end
		$data['jatuh_tempo'] = $row_jatuh;
		$data['bulan_sekarang'] = $bulan;
		$data['tahun_sekarang'] = $tahun;


		$this->load->view('template/header', $data);
		$this->load->view('dashboard', $data);
		$this->load->view('template/footer');
	}

	// public function tagihan_pembayaran_card()
	// {
	// 	$bulan = $this->input->post('periode_bulan');
	// 	$tahun = $this->input->post('periode_tahun');

	// 	$sql = "SELECT a.*
	// 	FROM pembayaran a
	// 	LEFT JOIN pendaftaran_paket pp ON a.id_pendaftaran_paket = pp.id
	// 	WHERE a.periode_bulan = ?
	// 	 AND a.periode_tahun  = ?
	// 	 AND pp.status_aktif = 1
	// 	 ; ";
	// 	$row = $this->db->query($sql, [(int) $bulan, (int) $tahun])->result_array();

	// 	$data_belum = 0;
	// 	$data_lunas = 0;
	// 	$data_cicilan = 0;

	// 	foreach ($row as $key => $value) {
	// 		if ($value['status'] == 'Belum') {
	// 			$nilai_beasiswa = !empty($value['nilai_beasiswa']) ? $value['nilai_beasiswa'] : 0;
	// 			$total_harga_pertemuan = !empty($value['total_harga_pertemuan']) ? $value['total_harga_pertemuan'] : 0;
	// 			$total_akhir = $total_harga_pertemuan - $nilai_beasiswa;
	// 			// $data_belum += !empty($value['total_harga_pertemuan']) ? $value['total_harga_pertemuan'] : 0;
	// 			$data_belum += $total_akhir;
	// 		}
	// 		if ($value['status'] == 'Lunas') {
	// 			// $nilai_beasiswa = !empty($value['nilai_beasiswa']) ? $value['nilai_beasiswa'] : 0;
	// 			// $total = !empty($value['total_akhir']) ? $value['total_akhir'] : 0;
	// 			// $total_akhir = $total - $nilai_beasiswa;
	// 			// $data_lunas += $total_akhir;
	// 			$data_lunas += !empty($value['total_akhir']) ? $value['total_akhir'] : 0;
	// 		}
	// 		if ($value['status'] == 'Sudah Bayar') {
	// 			$data_cicilan += !empty($value['nominal_bayar']) ? $value['nominal_bayar'] : 0;
	// 		}
	// 	}
	// 	echo json_encode(array(
	// 		'total_belum' => $data_belum,
	// 		'total_lunas' => $data_lunas,
	// 		'total_cicilan' => $data_cicilan
	// 	));
	// }
	public function tagihan_pembayaran_card()
{
    $bulan = str_pad((int) $this->input->post('periode_bulan'), 2, '0', STR_PAD_LEFT);
    $tahun = (int) $this->input->post('periode_tahun');

    $sql = "SELECT
            COALESCE(SUM(CASE 
                WHEN p.status = 'Lunas' 
                THEN p.total_akhir 
                ELSE 0 
            END), 0) AS total_lunas,

            COALESCE(SUM(CASE 
                WHEN p.status = 'Belum' 
                THEN p.total_akhir 
                ELSE 0 
            END), 0) AS total_belum,

            COALESCE(SUM(CASE 
                WHEN p.status = 'Sudah Bayar' 
                THEN p.total_akhir 
                ELSE 0 
            END), 0) AS total_menunggu_konfirmasi
        FROM pembayaran p
        INNER JOIN pendaftaran_paket pp ON pp.id = p.id_pendaftaran_paket
        INNER JOIN siswa s ON s.id = pp.id_siswa
        WHERE p.periode_bulan = ?
        AND p.periode_tahun = ?
        AND pp.status_aktif = 1
    ";

    $row = $this->db->query($sql, [$bulan, $tahun])->row_array();

    echo json_encode([
        'total_lunas' => (int) $row['total_lunas'],
        'total_belum' => (int) $row['total_belum'],
        'total_cicilan' => (int) $row['total_menunggu_konfirmasi']
    ]);
}
// public function tagihan_pembayaran_card()
// {
//     $bulan = str_pad((int) $this->input->post('periode_bulan'), 2, '0', STR_PAD_LEFT);
//     $tahun = (int) $this->input->post('periode_tahun');

//     $sql = "
//         SELECT
//             COALESCE(SUM(CASE 
//                 WHEN p.status = 'Lunas' 
//                 THEN p.total_akhir 
//                 ELSE 0 
//             END), 0) AS total_lunas,

//             COALESCE(SUM(CASE 
//                 WHEN p.status = 'Belum' 
//                 THEN p.total_akhir 
//                 ELSE 0 
//             END), 0) AS total_belum,

//             COALESCE(SUM(CASE 
//                 WHEN p.status = 'Sudah Bayar' 
//                 THEN p.total_akhir 
//                 ELSE 0 
//             END), 0) AS total_menunggu_konfirmasi

//         FROM (
//             SELECT p1.*
//             FROM pembayaran p1
//             INNER JOIN (
//                 SELECT
//                     id_pendaftaran_paket,
//                     periode_tahun,
//                     LPAD(periode_bulan, 2, '0') AS periode_bulan,
//                     SUBSTRING_INDEX(
//                         GROUP_CONCAT(
//                             id
//                             ORDER BY
//                                 CASE
//                                     WHEN status = 'Lunas' THEN 1
//                                     WHEN status = 'Sudah Bayar' THEN 2
//                                     ELSE 3
//                                 END ASC,
//                                 id DESC
//                         ),
//                         ',',
//                         1
//                     ) AS id_terpilih
//                 FROM pembayaran
//                 WHERE periode_tahun = ?
//                 AND LPAD(periode_bulan, 2, '0') = ?
//                 GROUP BY id_pendaftaran_paket, periode_tahun, LPAD(periode_bulan, 2, '0')
//             ) x ON x.id_terpilih = p1.id
//         ) p
//         INNER JOIN pendaftaran_paket pp 
//             ON pp.id = p.id_pendaftaran_paket
//         WHERE pp.status_aktif = 1
//     ";

//     $row = $this->db->query($sql, [$tahun, $bulan])->row_array();

//     echo json_encode([
//         'total_lunas' => (int) $row['total_lunas'],
//         'total_belum' => (int) $row['total_belum'],
//         'total_cicilan' => (int) $row['total_menunggu_konfirmasi']
//     ]);
// }
	public function mapel_result()
	{
		$data = $this->model->mapel_result();

		echo json_encode($data);
	}

	public function dashboard_result()
	{
		$data = $this->model->dashboard_result();

		echo json_encode($data);
	}

	public function jadwal_result()
	{
		$data = $this->model->jadwal_result();

		echo json_encode($data);
	}
}
?>