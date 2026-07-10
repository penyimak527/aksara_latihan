<?php
class Naskah_soal extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('latihan_soal/bank/M_naskah_soal', 'model');
	}

	public function index()
	{
		if ($this->session->userdata('admin')['username'] == null) {
			redirect('/');
		}

		$data['title'] = 'Bank Soal / Naskah Soal';
		$data['dropdown'] = $this->model->dropdown();
		$this->load->view('template/header', $data);
		$this->load->view('latihan_soal/bank/naskah_soal', $data);
		$this->load->view('template/footer');
	}

	public function naskah_soal_result()
	{
		$data = $this->model->naskah_soal_result();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function result()
	{
		$this->naskah_soal_result();
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

	public function ubah_status()
	{
		$data = $this->model->ubah_status();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}
}
?>
