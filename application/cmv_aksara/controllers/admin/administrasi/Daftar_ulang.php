<?php
class Daftar_ulang extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/administrasi/M_daftar_ulang', 'model');
	}

	public function index()
	{
		if ($this->session->userdata('admin')['username'] == null) {
			redirect('/');
		}

		$data['title'] = 'Daftar Ulang';
		$this->load->view('template/header', $data);
		$this->load->view('admin/administrasi/daftar_ulang', $data);
		$this->load->view('template/footer');
	}

	public function daftar_ulang_result()
	{
		$data = $this->model->daftar_ulang_result();

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

	public function siswa_row()
	{
		$data['siswa'] = $this->model->siswa_row();
		$id_kelas = $data['siswa']['id_kelas'];
		$data['kelas_berikutnya'] = $this->model->kelas_berikutnya_result($id_kelas);

		$this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($data,  JSON_PRETTY_PRINT))
        ->_display();
        exit;
	}
}
?>
