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
public function download_pdf($id_naskah = null)
{
    if ($this->session->userdata('admin')['username'] == null) {
        redirect('/');
    }

    $id_naskah = (int) $id_naskah;
    $data = $this->model->detail_naskah_pdf($id_naskah);

    if (empty($data['status'])) {
        show_error($data['message'] ?? 'Data naskah soal tidak ditemukan.', 404, 'Naskah Soal Tidak Ditemukan');
        return;
    }

    $data['title'] = 'Naskah Soal';

    // Ambil HTML dari view, jangan langsung ditampilkan
    $html = $this->load->view('latihan_soal/bank/pdf_naskah_soal', $data, true);

    // Load Dompdf
    require_once APPPATH . 'libraries/dompdf/autoload.inc.php';

    $dompdf = new \Dompdf\Dompdf([
        'isRemoteEnabled' => true,
        'isHtml5ParserEnabled' => true,
    ]);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $nama_file = 'Naskah_Soal_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $data['naskah']['nama_naskah_soal'] ?? 'Latihan') . '.pdf';

    // Attachment true = langsung download, bukan tampil di browser
    $dompdf->stream($nama_file, ['Attachment' => true]);
    exit;
}
}
?>
