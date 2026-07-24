<?php
class Laporan extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if ($this->session->userdata('admin')['username'] == null) {
			redirect('/');
		}

		$data['title'] = 'Laporan';
		$data['paket'] = $this->db->get('paket')->result_array();
		$data['kelas'] = $this->db->get('kelas')->result_array();
		$data['jenjang'] = $this->db->get('jenjang')->result_array();
		$data['beasiswa'] = $this->db->get('beasiswa')->result_array();
		$data['siswa'] = $this->db->get('siswa')->result_array();
		$data['pegawai'] = $this->db->get('pegawai')->result_array();


		$data['mapel_options'] = $this->db->query("SELECT * FROM mata_pelajaran WHERE status_aktif = '1' ORDER BY nama_mata_pelajaran ASC")->result_array();
		$data['view'] = $this;
		$this->load->view('template/header', $data);
		$this->load->view('admin/laporan', $data);
		$this->load->view('template/footer');
	}


	public function laporan_result()
	{
		$id_level = $this->session->userdata('admin')['id_level'];
		$search = $this->input->post('search');

		if ($search != null) {
			$this->db->where('group', 'Laporan');
			$this->db->where('id_level', $id_level);
			$this->db->like('name', $search);
			$data = $this->db->get('list_menu')->result_array();
		} else {
			$data = $this->db->get_where('list_menu', [
				'group' => 'Laporan',
				'id_level' => $id_level
			])->result_array();
		}
		echo json_encode($data);
	}

	public function kelas_riwayat_by_siswa()
	{
		$admin = $this->session->userdata('admin');

		if (empty($admin) || empty($admin['username'])) {
			$this->output
				->set_status_header(401)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode([
					'result' => 'false',
					'message' => 'Sesi admin tidak ditemukan.',
					'data' => []
				]));
			return;
		}

		$id_siswa = (int) $this->input->post('id_siswa', true);
		$tahun_ajaran = trim((string) $this->input->post('tahun_ajaran', true));

		if ($id_siswa <= 0 || $tahun_ajaran === '') {
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode([
					'result' => 'false',
					'message' => 'Siswa dan tahun ajaran wajib dipilih.',
					'data' => []
				]));
			return;
		}

		$kelas = $this->db->query(
			"SELECT DISTINCT
				ps.id_kelas,
				k.nama_kelas,
				j.nama_jenjang
			FROM siswa_pengerjaan ps
			INNER JOIN kelas k ON k.id = ps.id_kelas
			LEFT JOIN jenjang j ON j.id = k.id_jenjang
			WHERE ps.id_siswa = ?
			  AND ps.tahun_ajaran = ?
			  AND ps.id_kelas IS NOT NULL
			ORDER BY j.nama_jenjang ASC, k.nama_kelas ASC",
			[$id_siswa, $tahun_ajaran]
		)->result_array();

		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode([
				'result' => 'true',
				'data' => $kelas
			]));
	}

}
?>