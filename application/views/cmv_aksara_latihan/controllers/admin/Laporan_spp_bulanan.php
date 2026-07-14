<?php
class Laporan_spp_bulanan extends CI_Controller
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


		$id_kelas = $ambil['id_kelas'];
		$id_paket = $ambil['id_paket'];
		$id_jenjang = $ambil['id_jenjang'];
		$bulan = $ambil['filter_bulan'];
		$periode = $ambil['filter_tahun'];

		$button = $ambil['print'];


		$where_jenjang = '';
		if ($id_jenjang != 'semua') {
			$where_jenjang = "AND a.id_jenjang = '$id_jenjang'";
		}

		$where_kelas = '';
		if ($id_kelas != 'semua') {
			$where_kelas = "AND a.id_kelas = '$id_kelas'";
		}
		$where_paket = '';
		if ($id_paket != 'semua') {
			$where_paket = "AND pp.id_paket = '$id_paket'";
		}

		$pembayaran = $this->db->query("SELECT a.nama_siswa,b.nama_kelas,p.pertemuan,p.harga_pertemuan,p.total_harga_pertemuan,
		p.total_akhir,p.nominal_bayar,p.status,p.kas,p.total_kas, p.nilai_beasiswa,j.nama_jenjang
		FROM siswa a LEFT JOIN kelas b on a.id_kelas = b.id LEFT JOIN jenjang j on a.id_jenjang = j.id LEFT JOIN pendaftaran_paket pp on a.id = pp.id_siswa
		LEFT JOIN paket c on pp.id = c.id $where_paket LEFT JOIN pembayaran p on pp.id = p.id_pendaftaran_paket
		AND p.periode_bulan = '$bulan'AND p.periode_tahun = '$periode'
		WHERE 1=1 $where_kelas $where_jenjang
		AND pp.status_aktif = 1
		")->result_array();


		$data['pembayaran'] = $pembayaran;
		$data['periode'] = $periode;
		$data['bulan'] = $this->bulan($bulan);


		if ($button == 'pdf') {
			$this->load->view('admin/data_laporan/laporan_spp_bulanan', $data);
		} else {

			$s = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
			$sheet = $s->getActiveSheet();

			// Meta & default
			$s->getProperties()
				->setCreator('CI3')
				->setTitle('Laporan SPP Bulanan');
			$sheet->getDefaultRowDimension()->setRowHeight(20);
			$s->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);
			$s->getDefaultStyle()->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

			// Title
			$title = "LAPORAN SPP BULANAN — Periode {$data['bulan']} {$periode}";
			$sheet->setCellValue('A1', $title);
			$sheet->mergeCells('A1:K1');
			$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
			$sheet->getStyle('A1')->getAlignment()->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);

			// Header row
			$rowH = 3;
			$headers = [
				'A' => 'NAMA',
				'B' => 'KLS',
				'C' => 'KODE',
				'D' => 'PERTEMUAN',
				'E' => 'HARGA /PERTEMUAN (Rp)',
				'F' => 'TOTAL YG HARUS DIBAYAR (Rp)',
				'G' => 'TERBAYAR (Rp)',
				'H' => 'SISA TAGIHAN (Rp)',
				'I' => 'STATUS',
				'J' => 'KAS (Rp)',
				'K' => 'TOTAL KAS (Rp)',
			];
			foreach ($headers as $col => $text) {
				$sheet->setCellValue($col . $rowH, $text);
			}

			// Column widths (mirip gambar)
			$sheet->getColumnDimension('A')->setWidth(32);
			$sheet->getColumnDimension('B')->setWidth(8);
			$sheet->getColumnDimension('C')->setWidth(10);
			$sheet->getColumnDimension('D')->setWidth(12);
			$sheet->getColumnDimension('E')->setWidth(20);
			$sheet->getColumnDimension('F')->setWidth(26);
			$sheet->getColumnDimension('G')->setWidth(16);
			$sheet->getColumnDimension('H')->setWidth(20);
			$sheet->getColumnDimension('I')->setWidth(14);
			$sheet->getColumnDimension('J')->setWidth(14);
			$sheet->getColumnDimension('K')->setWidth(18);

			// Header style
			$sheet->getRowDimension($rowH)->setRowHeight(28);
			$sheet->getStyle("A{$rowH}:K{$rowH}")->getFont()->setBold(true);
			$sheet->getStyle("A{$rowH}:K{$rowH}")->getAlignment()->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);
			$sheet->getStyle("A{$rowH}:K{$rowH}")->getFill()->setFillType(
				\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
			)->getStartColor()->setARGB('FFEFEFEF'); // abu header

			// Warna khusus seperti gambar (merah/pink di area pembayaran)
			// TERBAYAR (G): merah tua + font putih
			$sheet->getStyle("G{$rowH}")->getFill()->setFillType(
				\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
			)->getStartColor()->setARGB('FFC0504D');
			$sheet->getStyle("G{$rowH}")->getFont()->getColor()->setARGB('FFFFFFFF');

			// SISA TAGIHAN (H) & STATUS (I): pink muda
			$sheet->getStyle("H{$rowH}:I{$rowH}")->getFill()->setFillType(
				\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
			)->getStartColor()->setARGB('FFF8CBAD');

			// Border tipis untuk semua tabel
			$borderThin = [
				'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
			];

			// Format angka (Rp)
			$fmtRp = '"Rp" #,##0;[Red]"Rp" -#,##0';

			// Data
			$r = $rowH + 1;

			$total_akhir = 0;
			$total_dibayar = 0;
			$total_harus = 0;
			$total_sisa = 0;
			$total_kas_all = 0;
			$total_kas_total_all = 0;
			$total_pertemuan = 0;
			foreach ($pembayaran as $i => $row) {

				$nama = $row['nama_siswa'];
				$kelas = $row['nama_kelas'];

				$kode = $row['nama_jenjang'];

				$pertemuan = (int) ($row['pertemuan'] ?? 0);
				$hargaPert = (int) ($row['harga_pertemuan'] ?? 0);
				$totalHarus = (int) ($row['total_akhir'] ?? ($pertemuan * $hargaPert));
				$nilai_beasiswa = (int) ($row['nilai_beasiswa'] ?? 0);

				$terbayar = 0;
				if ($row['status'] == 'Lunas') {
					$terbayar = $row['total_akhir'];
				}

				$sisa = max(0, $totalHarus - $terbayar);
				if ($pertemuan == 0) {
					$status = '';
				} else {
					$status = ($row['status'] === 'Lunas') ? 'Lunas' : 'Tidak Lunas';
					if ($row['status'] === 'Belum') {
						// $sisa = (int) $row['total_harga_pertemuan'] - $nilai_beasiswa;
						$sisa = (int) $row['total_akhir'];
					} else {
						$sisa = 0;
					}
					// if ($row['status'] == 'Lunas') {
					// 	$status = $row['status'];
					// } else {
					// 	$status = $sisa <= 0 ? 'Lunas' : 'Tidak Lunas';
					// }
				}
				$kas = (int) ($row['kas'] ?? 0);
				$totalKas = (int) ($row['total_kas'] ?? 0);

				$sheet->setCellValue("A{$r}", $nama);
				$sheet->setCellValue("B{$r}", $kelas);
				$sheet->setCellValue("C{$r}", $kode);
				$sheet->setCellValue("D{$r}", $pertemuan);
				$sheet->setCellValue("E{$r}", $hargaPert);
				$sheet->setCellValue("F{$r}", $totalHarus);
				$sheet->setCellValue("G{$r}", $terbayar);
				$sheet->setCellValue("H{$r}", $sisa);
				$sheet->setCellValue("I{$r}", $status);
				$sheet->setCellValue("J{$r}", $kas);
				$sheet->setCellValue("K{$r}", $totalKas);

				// Align & format
				$sheet->getStyle("D{$r}")->getAlignment()->setHorizontal(
					\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
				);
				$sheet->getStyle("E{$r}:G{$r}")->getNumberFormat()->setFormatCode($fmtRp);
				$sheet->getStyle("H{$r}")->getNumberFormat()->setFormatCode($fmtRp);
				$sheet->getStyle("J{$r}:K{$r}")->getNumberFormat()->setFormatCode($fmtRp);

				// Status warna
				if (strtolower($status) === 'lunas') {
					$sheet->getStyle("I{$r}")->getFont()->getColor()->setARGB('FF006100'); // hijau
				} else {
					$sheet->getStyle("I{$r}")->getFont()->getColor()->setARGB('FF9C0006'); // merah
				}
				$total_pertemuan += $pertemuan;
				$total_akhir += $hargaPert;
				$total_dibayar += $terbayar;
				$total_harus += $totalHarus;
				$total_sisa += $sisa;
				$total_kas_all += $kas;
				$total_kas_total_all += $totalKas;
				$r++;
			}

			$rowTotalTop = $rowH - 1;
			$sheet->mergeCells("A{$rowTotalTop}:C{$rowTotalTop}");
			$sheet->setCellValue("A{$rowTotalTop}", 'TOTAL');
			$sheet->getStyle("A{$rowTotalTop}")->getAlignment()->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
			);
			$sheet->getStyle("A{$rowTotalTop}:K{$rowTotalTop}")->getFont()->setBold(true);


			$sheet->setCellValue("D{$rowTotalTop}", $total_pertemuan);
			$sheet->getStyle("D{$rowTotalTop}")->getAlignment()->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);
			$sheet->setCellValue("E{$rowTotalTop}", $total_akhir);
			$sheet->setCellValue("F{$rowTotalTop}", $total_harus);
			$sheet->setCellValue("G{$rowTotalTop}", $total_dibayar);
			$sheet->setCellValue("H{$rowTotalTop}", $total_sisa);
			$sheet->setCellValue("I{$rowTotalTop}", '-');
			$sheet->setCellValue("J{$rowTotalTop}", $total_kas_all);
			$sheet->setCellValue("K{$rowTotalTop}", $total_kas_total_all);
			$sheet->getStyle("E{$rowTotalTop}:K{$rowTotalTop}")
				->getNumberFormat()->setFormatCode('"Rp." #,##0');

			$sheet->getStyle("A{$rowTotalTop}:K{$r}")->applyFromArray($borderThin);

			$sheet->getRowDimension($r)->setRowHeight(24);

			$sheet->freezePane('A4');

			// Output
			$filename = 'Laporan_SPP_Bulanan_' . $periode . '.xlsx';
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