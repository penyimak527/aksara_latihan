<?php
class Laporan_aging_piutang extends CI_Controller
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
		$button = $ambil['print'];

		$aging = $this->db->query(" SELECT 
		x.nama_siswa,
		x.nama_jenjang,
		x.nama_kelas,
		SUM(x.sisa)                                                              AS total_piutang,
		SUM(CASE WHEN x.hari BETWEEN 0  AND 30  THEN x.sisa ELSE 0 END)          AS d_0_30,
		SUM(CASE WHEN x.hari BETWEEN 31 AND 60  THEN x.sisa ELSE 0 END)          AS d_31_60,
		SUM(CASE WHEN x.hari BETWEEN 61 AND 90  THEN x.sisa ELSE 0 END)          AS d_61_90,
		SUM(CASE WHEN x.hari > 90                       THEN x.sisa ELSE 0 END)  AS d_90_plus
		FROM (
		SELECT
			p.id_pendaftaran_paket, 
			c.nama_siswa,
			e.nama_jenjang,
			d.nama_kelas,
			GREATEST(
			(COALESCE(NULLIF(p.total_akhir,''), '0') + 0)
			- (COALESCE(NULLIF(p.nominal_bayar,''), '0') + 0),
			0
			) AS sisa,
		
			GREATEST(
			DATEDIFF(CURDATE(), STR_TO_DATE(p.tanggal, '%d-%m-%Y')),
			0
			) AS hari
		FROM pembayaran p left join pendaftaran_paket b 
				on p.id_pendaftaran_paket = b.id left join siswa c on b.id_siswa = c.id  LEFT JOIN 
				kelas d ON b.id_kelas = d.id left join jenjang e on d.id_jenjang = e.id where c.status_aktif = 1 AND p.status = 'Belum'
		) x
		WHERE  x.sisa > 0
		GROUP BY x.id_pendaftaran_paket
		ORDER BY x.id_pendaftaran_paket; 
		")->result_array();



		$data['aging'] = $aging;
		$data['periode'] = $periode;

		if ($button == 'pdf') {
			$this->load->view('admin/data_laporan/laporan_aging_piutang', $data);
		} else {

			$s = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
			$sheet = $s->getActiveSheet();

			// Meta & default
			$s->getProperties()
				->setCreator('CI3')
				->setTitle('Laporan Aging Piutang');
			$sheet->getDefaultRowDimension()->setRowHeight(20);
			$s->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);
			$s->getDefaultStyle()->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

			// Title
			$title = "Laporan Aging Piutang";
			$sheet->setCellValue('A1', $title);
			$sheet->mergeCells('A1:K1');
			$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
			$sheet->getStyle('A1')->getAlignment()->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);

			// Header row
			$rowH = 3;
			$headers = [
				'A' => 'NO',
				'B' => 'NAMA SISWA',
				'C' => 'KELAS',
				'D' => 'TOTAL PIUTANG',
				'E' => '0 - 30',
				'F' => '31 - 60',
				'G' => '61 - 90',
				'H' => '90+',
			];
			foreach ($headers as $col => $text) {
				$sheet->setCellValue($col . $rowH, $text);
			}

			// Header style
			$sheet->getRowDimension($rowH)->setRowHeight(28);
			$sheet->getStyle("A{$rowH}:H{$rowH}")->getFont()->setBold(true);
			$sheet->getStyle("A{$rowH}:H{$rowH}")->getAlignment()->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);
			$sheet->getStyle("A{$rowH}:H{$rowH}")->getFill()->setFillType(
				\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
			)->getStartColor()->setARGB('FFEFEFEF'); // abu header


			// Border tipis untuk semua tabel
			$borderThin = [
				'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
			];

			// Format angka (Rp)
			$fmtRp = '"Rp" #,##0;[Red]"Rp" -#,##0';

			// Data
			$r = $rowH + 1;
			$no = 1;
			$total_piutang_all = 0;
			$total_0_30 = 0;
			$total_31_60 = 0;
			$total_61_90 = 0;
			$total_90_plus = 0;
			foreach ($aging as $i => $row) {
				$sheet->setCellValue("A{$r}", $no++);
				$sheet->setCellValue("B{$r}", $row['nama_siswa']);
				$sheet->setCellValue("C{$r}", $row['nama_kelas'] . ' ' . $row['nama_jenjang']);
				$sheet->setCellValue("D{$r}", $row['total_piutang']);
				$sheet->setCellValue("E{$r}", $row['d_0_30']);
				$sheet->setCellValue("F{$r}", $row['d_31_60']);
				$sheet->setCellValue("G{$r}", $row['d_61_90']);
				$sheet->setCellValue("H{$r}", $row['d_90_plus']);


				$sheet->getStyle("D{$r}:H{$r}")->getAlignment()->setHorizontal(
					\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
				);
				$sheet->getStyle("C{$r}:H{$r}")->getNumberFormat()->setFormatCode($fmtRp);

				$total_piutang_all += $row['total_piutang'];
				$total_0_30 += $row['d_0_30'];
				$total_31_60 += $row['d_31_60'];
				$total_61_90 += $row['d_61_90'];
				$total_90_plus += $row['d_90_plus'];
				$r++;
			}
			$rowTotalTop = $rowH;
			$sheet->insertNewRowBefore($rowTotalTop, 1);

			$sheet->mergeCells("A{$rowTotalTop}:C{$rowTotalTop}");
			$sheet->setCellValue("A{$rowTotalTop}", 'TOTAL');
			$sheet->getStyle("A{$rowTotalTop}")->getAlignment()->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
			);
			$sheet->getStyle("A{$rowTotalTop}:H{$rowTotalTop}")->getFont()->setBold(true);

			// nilai total
			$sheet->setCellValue("D{$rowTotalTop}", $total_piutang_all);
			$sheet->setCellValue("E{$rowTotalTop}", $total_0_30);
			$sheet->setCellValue("F{$rowTotalTop}", $total_31_60);
			$sheet->setCellValue("G{$rowTotalTop}", $total_61_90);
			$sheet->setCellValue("H{$rowTotalTop}", $total_90_plus);

			// format & rata kanan untuk total
			$sheet->getStyle("D{$rowTotalTop}:H{$rowTotalTop}")
				->getNumberFormat()->setFormatCode($fmtRp);
			$sheet->getStyle("D{$rowTotalTop}:H{$rowTotalTop}")
				->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

			foreach (range('A', 'H') as $col) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}
			$sheet->getStyle("A{$rowTotalTop}:H" . ($r - 1))->applyFromArray($borderThin);

			$sheet->freezePane('A4');

			$filename = 'Laporan_aging_piutang.xlsx';
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
