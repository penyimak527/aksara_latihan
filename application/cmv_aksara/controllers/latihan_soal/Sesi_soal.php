<?php
class Sesi_soal extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('latihan_soal/sesi/M_sesi_soal', 'model');
	}

	public function index()
	{
		if ($this->session->userdata('admin')['username'] == null) {
			redirect('/');
		}

		$data['title'] = 'Sesi Soal';
		$data['dropdown'] = $this->model->dropdown();
		$data['base_sesi_url'] = base_url('latihan_soal/sesi_soal');
		$this->load->view('template/header', $data);
		$this->load->view('latihan_soal/sesi/sesi_soal', $data);
		$this->load->view('template/footer');
	}

	public function sesi_soal_result()
	{
		$data = $this->model->sesi_soal_result();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function result()
	{
		$this->sesi_soal_result();
	}

	public function dropdown()
	{
		$data = array('result' => 'true', 'status' => true, 'data' => $this->model->dropdown());

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function naskah_by_filter()
	{
		$data = $this->model->naskah_by_filter();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function detail()
	{
		$data = $this->model->detail();

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
