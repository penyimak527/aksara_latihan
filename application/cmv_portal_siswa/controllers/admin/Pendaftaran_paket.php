<?php
class Pendaftaran_paket extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/M_pendaftaran_paket', 'model');
	}

	public function index()
	{
		if ($this->session->userdata('admin')['username'] == null) {
			redirect('/');
		}

		$data['title'] = 'Pendaftaran Paket';
		$this->load->view('template/header', $data);
		$this->load->view('admin/pendaftaran_paket', $data);
		$this->load->view('template/footer');
	}

	public function pendaftaran_paket_result()
	{
		$data = $this->model->pendaftaran_paket_result();

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
		$id_jenjang = $data['siswa']['id_jenjang'];
		$data['paket_harga'] = $this->model->paket_harga_result($id_jenjang);

		$this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($data,  JSON_PRETTY_PRINT))
        ->_display();
        exit;
	}
}
?>
