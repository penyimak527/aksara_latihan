<?php
class Laporan_beasiswa extends CI_Controller
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


		// $periode = $ambil['single_filter_tahun'];
		// echo $periode;
		$bulan = $ambil['filter_bulan'];
		$tahun = $ambil['filter_tahun'];
		$id_beasiswa = $ambil['id_beasiswa'];
		$id_kelas = $ambil['id_kelas'];

		$id_jenjang = $ambil['id_jenjang'];
		$button = $ambil['print'];

		$where_beasiswa = '';
		if ($id_beasiswa != 'semua') {
			$where_beasiswa = "AND a.id_beasiswa = $id_beasiswa";
		}
		$where_jenjang = '';
		if ($id_jenjang != 'semua') {
			$where_jenjang = "AND b.id_jenjang = $id_jenjang";
		}

		$where_kelas = '';
		if ($id_kelas != 'semua') {
			$where_kelas = "AND b.id_kelas = $id_kelas";
		}

		// $beasiswa = $this->db->query("SELECT   a.*,b.nama_siswa,c.nama_kelas,d.nama_jenjang,e.nilai,ph.harga_pertemuan,e.tipe
		// FROM siswa_beasiswa a left join siswa b on a.id_siswa = b.id left join kelas c on b.id_kelas = c.id 
		// left join jenjang d on b.id_jenjang = d.id 
		// left join beasiswa e on a.id_beasiswa = e.id
		// left join pendaftaran_paket pp on b.id = pp.id_siswa 
		// left join paket_harga ph on pp.id_paket_harga = ph.id 
		// WHERE 1=1  $where_beasiswa AND YEAR(STR_TO_DATE(a.berlaku_mulai, '%d-%m-%Y')) = $periode 
		// $where_kelas $where_jenjang 
		// ")->result_array();

		$beasiswa = $this->db->query("SELECT a.*,b.nama_siswa,c.nama_kelas,d.nama_jenjang,e.nilai,ph.harga_pertemuan,e.tipe, js.pertemuan
		FROM siswa_beasiswa a left join siswa b on a.id_siswa = b.id left join kelas c on b.id_kelas = c.id 
		left join jenjang d on b.id_jenjang = d.id 
		left join beasiswa e on a.id_beasiswa = e.id
		left join pendaftaran_paket pp on b.id = pp.id_siswa 
		left join paket_harga ph on pp.id_paket_harga = ph.id
		LEFT JOIN (
    SELECT 
        x.id_siswa,
        SUM(x.jumlah_meet) AS pertemuan
    FROM (
        -- JURNAL NORMAL
        SELECT 
            js.id_siswa,
            COUNT(js.id) AS jumlah_meet
        FROM jurnal_siswa js
        INNER JOIN jurnal j ON j.id = js.id_jurnal
        WHERE js.status_presensi = 'Hadir' AND MONTH(STR_TO_DATE(j.tanggal, '%d-%m-%Y')) = $bulan AND YEAR(STR_TO_DATE(j.tanggal, '%d-%m-%Y')) = $tahun
        GROUP BY js.id_siswa

        UNION ALL

        -- JURNAL PENGGANTI
        SELECT 
            js.id_siswa,
            COUNT(js.id) AS jumlah_meet
        FROM jurnal_siswa_pengganti js
        INNER JOIN jurnal_pengganti j ON j.id = js.id_jurnal
        WHERE js.status_presensi = 'Hadir' AND MONTH(STR_TO_DATE(j.tanggal, '%d-%m-%Y')) = $bulan AND YEAR(STR_TO_DATE(j.tanggal, '%d-%m-%Y')) = $tahun
		GROUP BY js.id_siswa
		) x
		GROUP BY x.id_siswa
		) js ON js.id_siswa = b.id 
		WHERE 1=1  $where_beasiswa AND STR_TO_DATE(a.berlaku_mulai, '%d-%m-%Y') <= LAST_DAY('$tahun-$bulan-01') AND (a.berlaku_sampai IS NULL
        OR STR_TO_DATE(a.berlaku_sampai, '%d-%m-%Y') >= '$tahun-$bulan-01')
		-- $tahun BETWEEN YEAR(STR_TO_DATE(a.berlaku_mulai, '%d-%m-%Y')) AND YEAR(STR_TO_DATE(a.berlaku_sampai, '%d-%m-%Y')) 
		$where_kelas $where_jenjang 
		")->result_array();

		$data['beasiswa'] = $beasiswa;
		$data['bulan'] = $this->bulan($bulan);
		$data['periode'] = $tahun;

		if ($button == 'pdf') {
			$this->load->view('admin/data_laporan/laporan_beasiswa', $data);
		} else {

			$s = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
			$sheet = $s->getActiveSheet();

			$s->getProperties()
				->setCreator('CI3')
				->setTitle('Laporan Beasiswa');
			$sheet->getDefaultRowDimension()->setRowHeight(20);
			$s->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);
			$s->getDefaultStyle()->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

			$title = "LAPORAN BEASISWA — Periode {$data['bulan']} {$tahun}";
			$sheet->setCellValue('A1', $title);
			$sheet->mergeCells('A1:D1');
			$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
			$sheet->getStyle('A1')->getAlignment()->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);

			$rowH = 3;
			$headers = [
				'A' => 'NO',
				'B' => 'NAMA',
				'C' => 'KELAS',
				'D' => 'POTONGAN',
				'E' => 'PERTEMUAN',
				'F' => 'TOTAL POTONGAN',
			];
			foreach ($headers as $col => $text) {
				$sheet->setCellValue($col . $rowH, $text);
			}

			$sheet->getRowDimension($rowH)->setRowHeight(28);
			$sheet->getStyle("A{$rowH}:F{$rowH}")->getFont()->setBold(true);
			$sheet->getStyle("A{$rowH}:F{$rowH}")->getAlignment()->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);
			$borderThin = [
				'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
			];

			// Data
			$r = $rowH + 1;
			$no = 1;
			$total_potongan_all = 0;
			foreach ($beasiswa as $i => $row) {

				$total_potongan = $row['nilai'];
				if ($row['tipe'] == 'Persen') {
					$potongan = $row['nilai'] * $row['harga_pertemuan'] / 100;
					$total_potongan = ($row['nilai'] * $row['pertemuan']) * $row['harga_pertemuan'] / 100;
				}

				if ($row['tipe'] == 'Nominal') {
					$potongan = $row['nilai'] ;
					$total_potongan = $row['nilai'] * $row['pertemuan'];
				}

				if ($row['tipe'] == 'Harga Khusus') {
					$potongan = $row['harga_pertemuan'] - $row['nilai'] ;
					$total_potongan = $row['harga_pertemuan'] - ($row['nilai'] * $row['pertemuan']);
				}

				$sheet->setCellValue("A{$r}", $no++);
				$sheet->setCellValue("B{$r}", $row['nama_siswa']);
				$sheet->setCellValue("C{$r}", $row['nama_kelas'] . ' ' . $row['nama_jenjang']);
				$sheet->setCellValue("D{$r}", 'Rp ' . number_format($potongan, 2, ',', '.'));
				$sheet->setCellValue("E{$r}", $row['pertemuan'] ?? 0);
				$sheet->setCellValue("F{$r}", 'Rp ' . number_format($total_potongan, 2, ',', '.'));

				$sheet->getStyle("D{$r}")->getAlignment()->setHorizontal(
					\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
				);
				$total_potongan_all += $total_potongan;
				$r++;
			}

			$sheet->mergeCells("A{$r}:E{$r}");
			$sheet->setCellValue("A{$r}", 'TOTAL POTONGAN');
			$sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
			);
			$sheet->getStyle("A{$r}:F{$r}")->getFont()->setBold(true);


			$sheet->setCellValue("F{$r}", $total_potongan_all);
			$sheet->getStyle("F{$r}")
				->getNumberFormat()->setFormatCode('"Rp." #,##0');

			$sheet->getStyle("A{$rowH}:F{$r}")->applyFromArray($borderThin);

			$sheet->getRowDimension($r)->setRowHeight(24);

			foreach (range('A', 'F') as $col) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$sheet->getStyle("A{$rowH}:F" . ($r - 1))->applyFromArray($borderThin);

			$sheet->freezePane('A4');

			$filename = 'Laporan_beasiswa_' . $data['bulan'] . '_' . $tahun . '.xlsx';
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