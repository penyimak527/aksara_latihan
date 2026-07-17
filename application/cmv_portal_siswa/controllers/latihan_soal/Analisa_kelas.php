<?php
class Analisa_kelas extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('latihan_soal/analisa/M_analisa_kelas', 'model');
	}

	public function index()
	{
		if ($this->session->userdata('admin')['username'] == null) {
			redirect('/');
		}

		$data['title'] = 'Analisa Kelas';
		$data['dropdown'] = $this->model->dropdown();
		$this->load->view('template/header', $data);
		$this->load->view('latihan_soal/analisa/analisa_kelas', $data);
		$this->load->view('template/footer');
	}

	public function analisa_result()
	{
		$data = $this->model->analisa_result();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	// public function result()
	// {
	// 	$this->analisa_result();
	// }
}
?>
