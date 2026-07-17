<?php
class Siswa extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/M_siswa', 'model');
	}

	public function index()
	{
		if ($this->session->userdata('admin')['username'] == null) {
			redirect('/');
		}

		$data['title'] = 'Siswa';
		$this->load->view('template/header', $data);
		$this->load->view('admin/siswa', $data);
		$this->load->view('template/footer');
	}

	public function siswa_result()
	{
		$data = $this->model->siswa_result();

		$this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($data,  JSON_PRETTY_PRINT))
        ->_display();
        exit;
	}
	public function kelas_result()
	{
		$data = $this->model->kelas_result();

		$this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($data,  JSON_PRETTY_PRINT))
        ->_display();
        exit;
	}

	public function edit()
	{
		$data = $this->model->edit();

		$this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($data,  JSON_PRETTY_PRINT))
        ->_display();
        exit;
	}

	public function hapus()
	{
		$data = $this->model->hapus();

		$this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($data,  JSON_PRETTY_PRINT))
        ->_display();
        exit;
	}

	public function detail_siswa_result()
	{
		$data['administrasi'] = $this->model->detail_administrasi_result();
		$data['kelas'] = $this->model->detail_kelas_result();
		$data['paket'] = $this->model->detail_paket_result();
		$data['pembayaran'] = $this->model->detail_pembayaran_result();

		$this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($data,  JSON_PRETTY_PRINT))
        ->_display();
        exit;
	}
}
?>
