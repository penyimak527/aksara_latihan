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
		// $data['mapel_options'] = $this->db->get_where('mata_pelajaran', ['status_aktif' => '1'])->result_array()->order_by('nama_mata_pelajaran', 'ASC');
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


}
?>