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
		$data['tahun_ajaran_options'] = $this->tahun_ajaran_options();
		$data['mapel_options'] = $this->db->query("SELECT * FROM mata_pelajaran WHERE status_aktif = '1' ORDER BY nama_mata_pelajaran ASC")->result_array();
		$data['view'] = $this;
		$this->load->view('template/header', $data);
		$this->load->view('admin/laporan', $data);
		$this->load->view('template/footer');
	}

	private function tahun_ajaran_options()
{
    $options = [];

    $tahun_minimum = 2025;
    $tahun_sekarang = (int) date('Y');
    $bulan_sekarang = (int) date('m');

    // Januari-Juni masih termasuk tahun ajaran sebelumnya.
    $tahun_awal_aktif = $bulan_sekarang >= 7
        ? $tahun_sekarang
        : $tahun_sekarang - 1;

    // Tampilkan sampai 3 tahun ajaran setelah tahun ajaran aktif.
    $tahun_akhir = $tahun_awal_aktif + 3;

    for ($tahun = $tahun_minimum; $tahun <= $tahun_akhir; $tahun++) {
        $options[] = $tahun . '/' . ($tahun + 1);
    }

    return $options;
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

	public function siswa_by_kelas()
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

		$id_kelas = (int) $this->input->post('id_kelas', true);

		if ($id_kelas <= 0) {
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode([
					'result' => 'false',
					'message' => 'Kelas wajib dipilih.',
					'data' => []
				]));
			return;
		}

		// Query parameter binding mencegah nilai id_kelas dimasukkan langsung ke SQL.
		$siswa = $this->db->query("SELECT
				id,
				nama_siswa,
				nis,
				id_kelas
			FROM siswa
			WHERE id_kelas = ? AND status_aktif = '1'
			ORDER BY id DESC", [$id_kelas])->result_array();

		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode([
				'result' => 'true',
				'data' => $siswa
			]));
	}

}
?>