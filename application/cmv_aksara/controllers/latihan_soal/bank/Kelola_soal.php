<?php
class Kelola_soal extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('latihan_soal/bank/M_kelola_soal', 'model');
	}

	public function index($id_naskah = null)
	{
		if ($this->session->userdata('admin')['username'] == null) {
			redirect('/');
		}

		$post_id_naskah = $this->input->post('id_naskah');
		if ($post_id_naskah !== null && $post_id_naskah !== '') {
			$id_naskah = $post_id_naskah;
		}
		$id_naskah = (int) $id_naskah;
		if ($id_naskah <= 0) {
			redirect('latihan_soal/bank/naskah_soal');
		}

		$naskah = $this->model->get_naskah($id_naskah);
		if (!$naskah) {
			show_404();
		}

		$data['title'] = 'Kelola Soal';
		$data['naskah'] = $naskah;
		$this->load->view('template/header', $data);
		$this->load->view('latihan_soal/bank/kelola_soal', $data);
		$this->load->view('template/footer');
	}

	public function materi_by_mapel()
	{
		$data = $this->model->materi_by_mapel();

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function result($id_naskah = null)
	{
		$data = $this->model->result((int) $id_naskah);

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function tambah($id_naskah = null)
	{
		$data = $this->model->tambah((int) $id_naskah);

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function detail($id_soal = null)
	{
		$data = $this->model->detail((int) $id_soal);

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function edit($id_naskah = null)
	{
		$data = $this->model->edit((int) $id_naskah);

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function hapus($id_naskah = null)
	{
		$data = $this->model->hapus((int) $id_naskah);

		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}
}
?>