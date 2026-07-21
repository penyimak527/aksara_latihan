<?php
class Laporan_administrasi extends CI_Controller
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


		$periode = $ambil['filter_tahun'];
		$bulan = $ambil['filter_bulan'];
		$id_kelas = $ambil['id_kelas'];
		$id_jenjang = $ambil['id_jenjang'];
		$button = $ambil['print'];


		// $periode = (int) $ambil['single_filter_tahun'];
		$id_kelas = ($ambil['id_kelas'] === 'semua') ? null : (int) $ambil['id_kelas'];
		$id_jenjang = ($ambil['id_jenjang'] === 'semua') ? null : (int) $ambil['id_jenjang'];

		$conds = [];
		$paramsF = [];
		if (!is_null($id_kelas)) {
			$conds[] = "b.id_kelas = ?";
			$paramsF[] = $id_kelas;
		}
		if (!is_null($id_jenjang)) {
			$conds[] = "b.id_jenjang = ?";
			$paramsF[] = $id_jenjang;
		}
		$whereFilters = $conds ? ' AND ' . implode(' AND ', $conds) : '';

		$sql = " SELECT
  x.nama_siswa,
  x.nama_kelas,
  x.nama_jenjang,
  SUM(CASE WHEN x.src='awal'  THEN x.nominal_bayar ELSE 0 END) AS daftar_awal,
  SUM(CASE WHEN x.src='ulang' THEN x.nominal_bayar ELSE 0 END) AS daftar_ulang,
 GROUP_CONCAT(
    DISTINCT DATE_FORMAT(CASE WHEN x.src='awal'  THEN x.tgl END, '%d-%m-%Y')
    ORDER BY x.tgl ASC SEPARATOR ', '
  ) AS tanggal_awal_all, 
  GROUP_CONCAT(
    DISTINCT DATE_FORMAT(CASE WHEN x.src='ulang' THEN x.tgl END, '%d-%m-%Y')
    ORDER BY x.tgl ASC SEPARATOR ', '
  ) AS tanggal_ulang_all,
 
  SUBSTRING_INDEX(
    GROUP_CONCAT(CASE WHEN x.src='awal'
                      THEN COALESCE(NULLIF(x.metode,''),'-')
                 END ORDER BY x.tgl DESC SEPARATOR '||'),
    '||', 1
  ) AS metode_awal,
  SUBSTRING_INDEX(
    GROUP_CONCAT(CASE WHEN x.src='ulang'
                      THEN COALESCE(NULLIF(x.metode,''),'-')
                 END ORDER BY x.tgl DESC SEPARATOR '||'),
    '||', 1
  ) AS metode_ulang,

  DATE_FORMAT(MAX(x.tgl), '%d-%m-%Y') AS tanggal
FROM (
  SELECT
    a.id_siswa,
    COALESCE(NULLIF(b.nama_siswa,''), '-')   AS nama_siswa,
    COALESCE(NULLIF(c.nama_kelas,''), '-')   AS nama_kelas,
    COALESCE(NULLIF(d.nama_jenjang,''), '-') AS nama_jenjang,
    COALESCE(NULLIF(a.nominal_bayar,''), 0)  AS nominal_bayar,
    COALESCE(NULLIF(a.metode_pembayaran,''), '-') AS metode,
    STR_TO_DATE(a.tanggal, '%d-%m-%Y')       AS tgl,
    'awal'                                   AS src
  FROM daftar_awal a
  LEFT JOIN siswa   b ON a.id_siswa = b.id
  LEFT JOIN kelas   c ON b.id_kelas = c.id
  LEFT JOIN jenjang d ON b.id_jenjang = d.id
  WHERE MONTH(STR_TO_DATE(a.tanggal, '%d-%m-%Y')) = ? AND YEAR(STR_TO_DATE(a.tanggal, '%d-%m-%Y')) = ? {$whereFilters}


  UNION ALL

  SELECT
    a.id_siswa,
    COALESCE(NULLIF(b.nama_siswa,''), '-')   AS nama_siswa,
    COALESCE(NULLIF(c.nama_kelas,''), '-')   AS nama_kelas,
    COALESCE(NULLIF(d.nama_jenjang,''), '-') AS nama_jenjang,
    COALESCE(NULLIF(a.nominal_bayar,''), 0)  AS nominal_bayar,
    COALESCE(NULLIF(a.metode_pembayaran,''), '-') AS metode,
    STR_TO_DATE(a.tanggal, '%d-%m-%Y')       AS tgl,
    'ulang'                                  AS src
  FROM daftar_ulang a
  LEFT JOIN siswa   b ON a.id_siswa = b.id
  LEFT JOIN kelas   c ON b.id_kelas = c.id
  LEFT JOIN jenjang d ON b.id_jenjang = d.id
  WHERE  MONTH(STR_TO_DATE(a.tanggal, '%d-%m-%Y')) = ? AND YEAR(STR_TO_DATE(a.tanggal, '%d-%m-%Y')) = ? {$whereFilters}
) x
GROUP BY x.id_siswa, x.nama_siswa, x.nama_kelas, x.nama_jenjang
ORDER BY x.nama_kelas, x.nama_siswa
";

		$params = array_merge(
			[$bulan, $periode],
			$paramsF,
			[$bulan, $periode],
			$paramsF
		);
		$administrasi = $this->db->query($sql, $params)->result_array();



		$data['administrasi'] = $administrasi;
		$data['periode'] = $periode;
		$data['bulan'] = $this->bulan($bulan);

		if ($button == 'pdf') {
			$this->load->view('admin/data_laporan/laporan_administrasi', $data);
		} else {

			$s = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
			$sheet = $s->getActiveSheet();

			// Meta & default
			$s->getProperties()
				->setCreator('CI3')
				->setTitle('Laporan Administrasi');
			$sheet->getDefaultRowDimension()->setRowHeight(20);
			$s->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);
			$s->getDefaultStyle()->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

			// Title
			$title = "Laporan Administrasi — Periode   {$periode}";
			$sheet->setCellValue('A1', $title);
			$sheet->mergeCells('A1:E1');
			$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
			$sheet->getStyle('A1')->getAlignment()->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);

			// Header row
			$rowH = 3;
			$headers = [
				'A' => 'NO',
				'B' => 'NAMA',
				'C' => 'KELAS',
				'D' => 'DAFTAR AWAL',
				'E' => 'DAFTAR ULANG',
			];
			foreach ($headers as $col => $text) {
				$sheet->setCellValue($col . $rowH, $text);
			}


			// Header style
			$sheet->getRowDimension($rowH)->setRowHeight(28);
			$sheet->getStyle("A{$rowH}:E{$rowH}")->getFont()->setBold(true);
			$sheet->getStyle("A{$rowH}:E{$rowH}")->getAlignment()->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);


			$borderThin = [
				'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
			];


			// Data
			$r = $rowH + 1;
			$no = 1;

			$total_daftar_awal = 0;
			$total_daftar_ulang = 0;

			foreach ($administrasi as $i => $row) {

				$sheet->setCellValue("A{$r}", $no++);
				$sheet->setCellValue("B{$r}", $row['nama_siswa']);
				$sheet->setCellValue("C{$r}", $row['nama_kelas'] . ' ' . $row['nama_jenjang']);
				$sheet->setCellValue(
					"D{$r}",
					'Rp. ' . number_format((int) $row['daftar_awal'], 0, ',', '.') .
					"\nMetode Pembayaran: " . $row['metode_awal'] .
					"\nTanggal: " . $row['tanggal_awal_all']
				);
				$sheet->getStyle("D{$r}")->getAlignment()->setWrapText(true);

				$sheet->setCellValue(
					"E{$r}",
					'Rp. ' . number_format((int) $row['daftar_ulang'], 0, ',', '.') .
					"\nMetode Pembayaran: " . $row['metode_ulang'] .
					"\nTanggal: " . $row['tanggal_ulang_all']
				);
				$sheet->getStyle("E{$r}")->getAlignment()->setWrapText(true);

				$total_daftar_awal += (int) $row['daftar_awal'];
				$total_daftar_ulang += (int) $row['daftar_ulang'];

				$r++;
			}
			$rowTotalTop = $rowH - 1;
			$sheet->mergeCells("A{$rowTotalTop}:C{$rowTotalTop}");
			$sheet->setCellValue("A{$rowTotalTop}", 'TOTAL');
			$sheet->getStyle("A{$rowTotalTop}")->getAlignment()->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
			);
			$sheet->getStyle("A{$rowTotalTop}:E{$rowTotalTop}")->getFont()->setBold(true);


			$sheet->setCellValue("D{$rowTotalTop}", $total_daftar_awal);
			$sheet->setCellValue("E{$rowTotalTop}", $total_daftar_ulang);
			$sheet->getStyle("D{$rowTotalTop}:E{$rowTotalTop}")
				->getNumberFormat()->setFormatCode('"Rp." #,##0');

			$sheet->getStyle("A{$rowTotalTop}:E{$r}")->applyFromArray($borderThin);

			$sheet->getRowDimension($r)->setRowHeight(24);

			foreach (range('A', 'E') as $col) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}
			$sheet->freezePane('A4');

			$filename = 'Laporan_administrasi_periode_' . $periode . '.xlsx';
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
	public function bulan($bln)
	{
		$bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
		return $bulan[$bln - 1];
	}

}
?>