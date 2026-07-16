<?php
class Laporan_pertemuan_tentor extends CI_Controller
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

		$tahun = $ambil['filter_tahun'];
		$bulan = $ambil['filter_bulan'];
		$button = $ambil['print'];
		$periode = (int) $ambil['single_filter_tahun'];

		// $sql = "SELECT
		// 				a.nama_pegawai,
		// 				s.nama_siswa,
		// 				b.tanggal,b.waktu,b.nama_jenjang,b.nama_kelas,
		// 				COUNT(CASE WHEN js.status_presensi = 'Hadir' THEN 1 END) AS kehadiran
		// 				FROM pegawai a
		// 				LEFT JOIN jurnal b ON a.id = b.id_pegawai
		// 				LEFT JOIN jurnal_siswa js ON js.id_jurnal = b.id
		// 				LEFT JOIN siswa s ON s.id = js.id_siswa
		// 				WHERE MONTH(STR_TO_DATE(b.tanggal, '%d-%m-%Y')) = ? AND YEAR(STR_TO_DATE(b.tanggal, '%d-%m-%Y')) = ?
		// 				GROUP BY a.id, a.nama_pegawai, s.id, s.nama_siswa
		// 				ORDER BY b.id_jenjang ASC,b.nama_kelas ASC;";

		// $sql = "SELECT
		// a.*
		// FROM (
		// 	SELECT
		// 	a.id AS id_pegawai,
		// 	a.nama_pegawai,
		// 	s.id AS id_siswa,s.nama_siswa,
		// 	b.id_jenjang,b.nama_jenjang,b.nama_kelas,
		// 	COUNT(CASE WHEN js.status_presensi = 'Hadir' THEN 1 END) AS kehadiran
		// 	FROM pegawai a
		// 	LEFT JOIN jurnal b ON a.id = b.id_pegawai
		// 	LEFT JOIN jurnal_siswa js ON js.id_jurnal = b.id
		// 	LEFT JOIN siswa s ON s.id = js.id_siswa
		// 	WHERE MONTH(STR_TO_DATE(b.tanggal, '%d-%m-%Y')) = ?
		// 	AND YEAR(STR_TO_DATE(b.tanggal, '%d-%m-%Y')) = ?
		// 	GROUP BY a.id, a.nama_pegawai, s.id, s.nama_siswa

		// 	UNION ALL

		// 	SELECT
		// 	a.id AS id_pegawai,
		// 	a.nama_pegawai,
		// 	s.id AS id_siswa,s.nama_siswa,
		// 	b.id_jenjang,b.nama_jenjang,b.nama_kelas,
		// 	COUNT(CASE WHEN js.status_presensi = 'Hadir' THEN 1 END) AS kehadiran
		// 	FROM pegawai a
		// 	LEFT JOIN jurnal_pengganti b ON a.id = b.id_pegawai
		// 	LEFT JOIN jurnal_siswa_pengganti js ON js.id_jurnal = b.id
		// 	LEFT JOIN siswa s ON s.id = js.id_siswa
		// 	WHERE MONTH(STR_TO_DATE(b.tanggal, '%d-%m-%Y')) = ?
		// 	AND YEAR(STR_TO_DATE(b.tanggal, '%d-%m-%Y')) = ?
		// 	GROUP BY a.id, a.nama_pegawai, s.id, s.nama_siswa
		// ) a
		// WHERE a.kehadiran > 0
		// ORDER BY a.id_jenjang ASC,
		// a.nama_kelas ASC,
		// a.nama_siswa ASC";
		$sql = "SELECT
 a.id_pegawai,
    a.nama_pegawai,
    a.id_siswa,
    a.nama_siswa,
    a.id_jenjang,
    a.nama_jenjang,
    a.nama_kelas,
    SUM(a.kehadiran) AS kehadiran
						FROM (
							SELECT
							a.id AS id_pegawai,
							a.nama_pegawai,
							s.id AS id_siswa,s.nama_siswa,
							b.id_jenjang,b.nama_jenjang,b.nama_kelas,
							COUNT(CASE WHEN js.status_presensi = 'Hadir' THEN 1 END) AS kehadiran
							FROM pegawai a
							LEFT JOIN jurnal b ON a.id = b.id_pegawai
							LEFT JOIN jurnal_siswa js ON js.id_jurnal = b.id
							LEFT JOIN siswa s ON s.id = js.id_siswa
							WHERE MONTH(STR_TO_DATE(b.tanggal, '%d-%m-%Y')) = ?
							AND YEAR(STR_TO_DATE(b.tanggal, '%d-%m-%Y')) = ?
							GROUP BY a.id, a.nama_pegawai, s.id, s.nama_siswa, b.id_jenjang, b.nama_jenjang, b.nama_kelas

							UNION ALL

							SELECT
							a.id AS id_pegawai,
							a.nama_pegawai,
							s.id AS id_siswa,s.nama_siswa,
							b.id_jenjang,b.nama_jenjang,b.nama_kelas,
							COUNT(CASE WHEN js.status_presensi = 'Hadir' THEN 1 END) AS kehadiran
							FROM pegawai a
							LEFT JOIN jurnal_pengganti b ON a.id = b.id_pegawai
							LEFT JOIN jurnal_siswa_pengganti js ON js.id_jurnal = b.id
							LEFT JOIN siswa s ON s.id = js.id_siswa
							WHERE MONTH(STR_TO_DATE(b.tanggal, '%d-%m-%Y')) = ?
							AND YEAR(STR_TO_DATE(b.tanggal, '%d-%m-%Y')) = ?
							GROUP BY a.id, a.nama_pegawai, s.id, s.nama_siswa, b.id_jenjang, b.nama_jenjang, b.nama_kelas
						) a
						GROUP BY a.id_pegawai, a.nama_pegawai, a.id_siswa, a.nama_siswa, a.id_jenjang, a.nama_jenjang, a.nama_kelas
						HAVING kehadiran > 0
						ORDER BY a.id_jenjang ASC, a.nama_kelas ASC, a.nama_siswa ASC
						";

		$params = array_merge(
			[$bulan, $tahun, $bulan, $tahun],
		);

		$absen = $this->db->query($sql, $params)->result_array();
		// echo $this->db->last_query();
		// die;

		$group_pegawai = [];
		foreach ($absen as $d) {
			$pegawai = $d['nama_pegawai'];
			if (!isset($group_pegawai[$pegawai]))
				$group_pegawai[$pegawai] = [];
			$group_pegawai[$pegawai][] = $d;
		}



		$data['absen'] = $group_pegawai;
		$data['bulan'] = $this->bulan($bulan);
		$data['tahun'] = $tahun;

		if ($button == 'pdf') {
			$this->load->view('admin/data_laporan/laporan_pertemuan_tentor', $data);
		} else {

			$s = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
			$sheet = $s->getActiveSheet();


			$s->getProperties()
				->setCreator('CI3')
				->setTitle('REKAP TENTOR "AKSARA COURSE"');
			$sheet->getDefaultRowDimension()->setRowHeight(20);
			$s->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);
			$s->getDefaultStyle()->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

			$bulan_upper = strtoupper($this->bulan($bulan));
			$title = "REKAP TENTOR AKSARA COURSE — BULAN {$bulan_upper} {$tahun}";
			$sheet->setCellValue('A1', $title);
			$sheet->mergeCells('A1:F1');
			$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
			$sheet->getStyle('A1')->getAlignment()->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);

			$rowH = 2;
			$headers = [
				'A' => 'No',
				'B' => 'Nama Tentor',
				'C' => 'Kelas',
				'D' => 'Siswa',
				'E' => 'Paket',
				'F' => 'Jumlah',
			];
			foreach ($headers as $col => $text) {
				$sheet->setCellValue($col . $rowH, $text);
			}
			$sheet->getRowDimension($rowH)->setRowHeight(28);
			$sheet->getStyle('A' . $rowH . ':F' . $rowH)->getFont()->setBold(true);
			$sheet->getStyle('A' . $rowH . ':F' . $rowH)->getAlignment()->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);
			$sheet->getStyle('A' . $rowH . ':F' . $rowH)
				->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				->getStartColor()->setRGB('EFEFEF');

			// ===== Border tipis =====
			$borderThin = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
						'color' => ['rgb' => '999999']
					]
				]
			];


			$r = $rowH + 1;
			$no = 1;

			if (!empty($group_pegawai)) {

				$no = 1;

				foreach ($group_pegawai as $namaPegawai => $rows) {
					$rowspan = count($rows);
					$first = true;

					foreach ($rows as $row) {

						if ($first) {
							$sheet->setCellValue('A' . $r, $no++);

							$sheet->setCellValue('B' . $r, $namaPegawai);

							$sheet->mergeCells('A' . $r . ':A' . ($r + $rowspan - 1));
							$sheet->mergeCells('B' . $r . ':B' . ($r + $rowspan - 1));

							$sheet->getStyle('A' . $r . ':B' . $r)->getAlignment()->setVertical(
								\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
							);
							$sheet->getStyle('A' . $r . ':B' . $r)->getAlignment()->setHorizontal(
								\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
							);
							$first = false;
						} else {
							$sheet->setCellValue('A' . $r, '');
						}

						$sheet->setCellValue('C' . $r, $row['nama_kelas'] ?? '');
						$sheet->setCellValue('D' . $r, $row['nama_siswa'] ?? '');
						$sheet->setCellValue('E' . $r, $row['nama_jenjang'] ?? '');
						$sheet->setCellValue('F' . $r, $row['kehadiran'] ?? '');

						$sheet->getStyle('C' . $r)->getAlignment()->setHorizontal(
							\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
						);
						$sheet->getStyle('E' . $r . ':F' . $r)->getAlignment()->setHorizontal(
							\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
						);

						$r++;
					}
				}

			} else {
				$sheet->mergeCells('A' . $r . ':F' . $r);
				$sheet->setCellValue('A' . $r, 'Tidak ada data.');
				$sheet->getStyle('A' . $r)->getAlignment()->setHorizontal(
					\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
				);
				$r++;
			}


			// Terapkan border untuk blok detail
			$sheet->getStyle('A' . $rowH . ':F' . ($r - 1))->applyFromArray($borderThin);


			// ===== Auto size & freeze =====
			foreach (range('A', 'F') as $col) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}
			$sheet->freezePane('A3'); // di bawah header

			// ===== Output =====
			$periode_text = $this->bulan($bulan) . ' ' . $tahun;
			$safe_periode = preg_replace('/[^\w\-]+/', '_', $periode_text);
			$filename = 'Laporan_pertemuan_tentor_periode_' . $safe_periode . '.xlsx';

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