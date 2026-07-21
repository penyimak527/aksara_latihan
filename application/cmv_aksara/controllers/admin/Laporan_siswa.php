<?php
use PhpOffice\PhpSpreadsheet\Cell\DataType;
class Laporan_siswa extends CI_Controller
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
		$id_jenjang = $ambil['id_jenjang'];
		$id_kelas = $ambil['id_kelas'];

		$button = $ambil['print'];

		$where_jenjang = '';
		if ($id_jenjang != 'semua') {
			$where_jenjang = "AND a.id_jenjang = $id_jenjang";
		}

		$where_kelas = '';
		if ($id_kelas != 'semua') {
			$where_kelas = "AND a.id_kelas = $id_kelas";
		}

		$siswa = $this->db->query("SELECT a.*,d.nama_jenjang, c.nama_kelas,e.jenis_administrasi as nama_paket FROM siswa a
		 left join jenjang d on a.id_jenjang = d.id left join kelas c on a.id_kelas = c.id 
		left join pendaftaran_paket e on a.id = e.id_siswa
		where  a.status_aktif = 1 $where_jenjang $where_kelas  ")->result_array();


		$data['siswa'] = $siswa;
		$data['periode'] = $periode;

		if ($button == 'pdf') {
			$this->load->view('admin/data_laporan/laporan_siswa', $data);
		} else {

			$s = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
			$sheet = $s->getActiveSheet();

			// Meta & default
			$s->getProperties()
				->setCreator('CI3')
				->setTitle('Laporan Siswa');
			$sheet->getDefaultRowDimension()->setRowHeight(20);
			$s->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);
			$s->getDefaultStyle()->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

			// Title
			$title = "LAPORAN Siswa — Periode   {$periode}";
			$sheet->setCellValue('A1', $title);
			$sheet->mergeCells('A1:H1');
			$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
			$sheet->getStyle('A1')->getAlignment()->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);

			// Header row
			$rowH = 3;
			$headers = [
				'A' => 'NO',
				'B' => 'NAMA',
				'C' => 'NIS',
				'D' => 'KELAS',
				'E' => 'JENJANG',
				'F' => 'PAKET',
				'G' => 'ALAMAT',
				'H' => 'NO HP WALI',

			];
			foreach ($headers as $col => $text) {
				$sheet->setCellValue($col . $rowH, $text);
			}

			$sheet->getRowDimension($rowH)->setRowHeight(28);
			$sheet->getStyle("A{$rowH}:H{$rowH}")->getFont()->setBold(true);
			$sheet->getStyle("A{$rowH}:H{$rowH}")->getAlignment()->setHorizontal(
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

				$no_hp = $row['hp_wali'];
				$sheet->setCellValue("A{$r}", $no++);
				$sheet->setCellValue("B{$r}", $row['nama_siswa']);
				$sheet->setCellValue("C{$r}", $row['nis']);
				$sheet->setCellValue("D{$r}", $row['nama_kelas']);
				$sheet->setCellValue("E{$r}", $row['nama_jenjang']);
				$sheet->setCellValue("F{$r}", $row['nama_paket']);
				$sheet->setCellValue("G{$r}", $row['alamat']);
				$sheet->setCellValueExplicit(
					"H{$r}",
					(string) $no_hp,
					DataType::TYPE_STRING
				);

				$r++;
			}
			foreach (range('A', 'H') as $col) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}
			$sheet->getStyle("A{$rowH}:H" . ($r - 1))->applyFromArray($borderThin);


			$filename = 'Laporan_siswa_' . $periode . '.xlsx';
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