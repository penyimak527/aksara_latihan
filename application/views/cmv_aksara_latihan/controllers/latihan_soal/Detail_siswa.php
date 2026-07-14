<?php
class Detail_siswa extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('latihan_soal/analisa/M_detail_siswa', 'model');
	}

	public function index($id_siswa = 0)
	{
		if ($this->session->userdata('admin')['username'] == null) {
			redirect('/');
		}

		$id_siswa = $this->input->post('id_siswa');
		$tahun_ajaran = $this->input->post('tahun_ajaran');
		$id_kelas = $this->input->post('id_kelas');

		if ($id_siswa == '' || $tahun_ajaran == '' || $id_kelas == '') {
			redirect('latihan_soal/analisa_kelas');
		}

		$data['title'] = 'Detail Siswa';
		$data['page'] = $this->model->page_data($id_siswa, $tahun_ajaran, $id_kelas);
		$this->load->view('template/header', $data);
		$this->load->view('latihan_soal/analisa/detail_siswa', $data);
		$this->load->view('template/footer');
	}

	public function detail_result()
	{
		$data = $this->model->detail_result();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function result()
	{
		$this->detail_result();
	}
}
?>