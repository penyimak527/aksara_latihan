<?php
class Jurnal_siswa_pengganti extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/jurnal/M_jurnal_siswa_pengganti', 'model');
	}

	public function index()
	{
		if ($this->session->userdata('admin')['username'] == null) {
			redirect('/');
		}

		$data['title'] = 'Jurnal Siswa Pengganti';
		$this->load->view('template/header', $data);
		$this->load->view('admin/jurnal/jurnal_siswa_pengganti', $data);
		$this->load->view('template/footer');
	}
	public function cek_session()
	{
		if (!$this->session->userdata('admin')) {
			echo json_encode(['status' => 'logout']);
		} else {
			echo json_encode(['status' => 'login']);
		}
		exit;
	}

	public function jurnal_siswa_pengganti_result()
	{
		$data = $this->model->jurnal_siswa_pengganti_result();

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

	public function detail_siswa_result()
	{
		$data = $this->model->detail_siswa_result();

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
}
?>