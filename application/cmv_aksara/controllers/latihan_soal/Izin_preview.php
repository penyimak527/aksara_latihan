<?php
class Izin_preview extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('latihan_soal/analisa/M_izin_preview', 'model');
	}

	public function index()
	{
		if ($this->session->userdata('admin')['username'] == null) {
			redirect('/');
		}

		$data['title'] = 'Izin Preview Jawaban';
		$data['dropdown'] = $this->model->dropdown();
		$this->load->view('template/header', $data);
		$this->load->view('latihan_soal/analisa/izin_preview', $data);
		$this->load->view('template/footer');
	}

	public function izin_preview_result()
	{
		$data = $this->model->result();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function result()
	{
		$this->izin_preview_result();
	}

	public function simpan()
	{
		$data = $this->model->simpan();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}
}
?>
