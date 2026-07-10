<?php
class Dashboard extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('latihan_soal/dashboard/M_dashboard_latihan', 'model');
	}

	public function index()
	{
		if ($this->session->userdata('admin')['username'] == null) {
			redirect('/');
		}

		$data['title'] = 'Dashboard Latihan Soal';
		$data['ringkasan'] = $this->model->ringkasan_menu();
		$this->load->view('template/header', $data);
		$this->load->view('latihan_soal/dashboard', $data);
		$this->load->view('template/footer');
	}
}
?>
