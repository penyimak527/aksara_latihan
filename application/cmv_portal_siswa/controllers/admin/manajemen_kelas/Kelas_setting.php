<?php
class Kelas_setting extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/manajemen_kelas/M_kelas_setting', 'model');
	}

	public function index()
	{
		if ($this->session->userdata('admin')['username'] == null) {
			redirect('/');
		}

		$data['title'] = 'Kelas Setting';
		$this->load->view('template/header', $data);
		$this->load->view('admin/manajemen_kelas/kelas_setting', $data);
		$this->load->view('template/footer');
	}

	public function kelas_setting_result()
	{
		$data = $this->model->kelas_setting_result();

		$this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($data,  JSON_PRETTY_PRINT))
        ->_display();
        exit;
	}

	public function tambah()
	{
		$data = $this->model->tambah();

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
}
?>
