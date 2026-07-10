<?php
class Laporan_riwayat_kelas extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if ($this->session->userdata('admin')['username'] == null) {
			redirect('/');
		}

		$json = file_get_contents('php://input');
		$ambil = json_decode($json, true);


		$periode = $ambil['single_filter_tahun'];
		$id_siswa = $ambil['id_siswa'];

		$button = $ambil['print'];


		$siswa = $this->db->query(" SELECT b.nis,b.nama_siswa,d.nama_jenjang, c.nama_kelas,a.tanggal FROM daftar_awal a left join siswa b 
				on a.id_siswa = b.id  left join jenjang d on b.id_jenjang = d.id left join kelas c on a.id_kelas = c.id 
				where a.periode_tahun = '$periode' AND a.id_siswa ='$id_siswa'

				UNION ALL

				SELECT b.nis,b.nama_siswa,d.nama_jenjang, c.nama_kelas,a.tanggal FROM daftar_ulang a left join siswa b 
				on a.id_siswa = b.id  left join jenjang d on b.id_jenjang = d.id left join kelas c on a.id_kelas = c.id 
				where a.periode_tahun = '$periode' AND a.id_siswa ='$id_siswa'    
		 ")->result_array();


		$data['siswa'] = $siswa;
		$data['periode'] = $periode;

		if ($button == 'pdf') {
			$this->load->view('admin/data_laporan/laporan_riwayat_kelas', $data);
		} else {

			$s = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
			$sheet = $s->getActiveSheet();

			// Meta & default
			$s->getProperties()
				->setCreator('CI3')
				->setTitle('Laporan Riwayat Kelas');
			$sheet->getDefaultRowDimension()->setRowHeight(20);
			$s->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);
			$s->getDefaultStyle()->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

			// Title
			$title = "LAPORAN Riwayat Kelas — Periode   {$periode}";
			$sheet->setCellValue('A1', $title);
			$sheet->mergeCells('A1:D1');
			$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
			$sheet->getStyle('A1')->getAlignment()->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);
			$sheet->mergeCells('A2:D2');
			$sheet->setCellValue('A2', "Nama Siswa : {$data['siswa'][0]['nama_siswa']}");
			$sheet->mergeCells('A3:D3');
			$sheet->setCellValue('A3', "NIS : {$data['siswa'][0]['nis']}");

			// Header row
			$rowH = 4;
			$headers = [
				'A' => 'NO',
				'B' => 'KELAS',
				'C' => 'JENJANG',
				'D' => 'TANGGAL BELAJAR',
			];
			foreach ($headers as $col => $text) {
				$sheet->setCellValue($col . $rowH, $text);
			}


			// Header style
			$sheet->getRowDimension($rowH)->setRowHeight(28);
			$sheet->getStyle("A{$rowH}:D{$rowH}")->getFont()->setBold(true);
			$sheet->getStyle("A{$rowH}:D{$rowH}")->getAlignment()->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);

			// Border tipis untuk semua tabel
			$borderThin = [
				'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
			];

			// Data
			$r = $rowH + 1;
			$no = 1;
			foreach ($siswa as $i => $row) {


				$sheet->setCellValue("A{$r}", $no++);
				$sheet->setCellValue("B{$r}", $row['nama_kelas']);
				$sheet->setCellValue("C{$r}", $row['nama_jenjang']);
				$sheet->setCellValue("D{$r}", $row['tanggal']);



				$r++;
			}

			foreach (range('A', 'D') as $col) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}
			$sheet->getStyle("A{$rowH}:D" . ($r - 1))->applyFromArray($borderThin);



			$filename = 'Laporan_riwayat_kelas_' . $siswa[0]['nama_siswa'] . '.xlsx';
			while (ob_get_level() > 0) {
				ob_end_clean();
			}
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $filename . '"');
			header('Cache-Control: max-age=0');
			header('Pragma: public');

			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($s);
			$writer->save('php://output');
			exit;
		}

	}

}
?>
