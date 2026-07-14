<?php
class M_siswa extends CI_Model
{

	public function siswa_result()
	{
		$search = $this->input->post('search');
		$kelas = $this->input->post('kelas');
		$where_search = "";
		$where_kelas = "";
		if ($search != "") {
			$where_search = "AND (a.nama_siswa LIKE '%$search%')";
		}
		if ($kelas != "") {
			$where_kelas = "AND (a.id_kelas = '$kelas')";
		}

		$sql = $this->db->query("SELECT
														 a.*,
                             b.nama_jenjang,
														 c.nama_kelas
														 FROM siswa a
                             INNER JOIN jenjang b ON a.id_jenjang = b.id
														 INNER JOIN kelas c ON a.id_kelas = c.id
														 WHERE a.status_aktif = '1'
														 $where_search $where_kelas
														 ORDER BY a.id DESC
														")->result_array();

		return $sql;
	}
public function kelas_result(){
	$sql = $this->db->query(" SELECT k.*,
	                             j.nama_jenjang
	                             FROM kelas k
	                             INNER JOIN jenjang j ON k.id_jenjang = j.id
	                             WHERE k.status_aktif = '1'");
	return $sql->result_array();
}
	public function edit()
	{
		$id_siswa = $this->input->post('id_siswa');
		$id_jenjang = $this->input->post('id_jenjang');
		$id_kelas = $this->input->post('id_kelas');
		$nis = $this->input->post('nis');
		$nama_siswa = $this->input->post('nama_siswa');
		$nama_wali = $this->input->post('nama_wali');
		$hp_wali = $this->input->post('hp_wali');
		$alamat = $this->input->post('alamat');
		$password_siswa = $this->input->post('password_siswa');
		$hashed_password = password_hash($password_siswa, PASSWORD_DEFAULT);
		$data = [
			'id_jenjang' => $id_jenjang,
			'id_kelas' => $id_kelas,
			'nis' => $nis,
			'nama_siswa' => $nama_siswa,
			'nama_wali' => $nama_wali,
			'hp_wali' => $hp_wali,
			'alamat' => $alamat,
			'password_siswa' => $hashed_password,
			'password_siswa_text' => $password_siswa
		];

		$this->db->trans_begin();
		$this->db->update('siswa', $data, ['id' => $id_siswa]);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = array(
				'result' => 'false'
			);
		} else {
			$this->db->trans_commit();
			$response = array(
				'result' => 'true'
			);
		}

		return $response;
	}

	public function hapus()
	{
		$id = $this->input->post('id');

		$data = [
			'status_aktif' => '0'
		];

		$this->db->trans_begin();
		$this->db->update('siswa', $data, ['id' => $id]);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = array(
				'result' => 'false'
			);
		} else {
			$this->db->trans_commit();
			$response = array(
				'result' => 'true'
			);
		}

		return $response;
	}

	public function detail_administrasi_result()
	{
		$id_siswa = $this->input->post('id_siswa');

		$sql = $this->db->query("SELECT
														a.*
														FROM (
															SELECT
															a.periode_tahun,
															a.biaya_daftar_awal AS biaya,
															a.nominal_bayar,
															a.metode_pembayaran,
															a.kembali,
															a.keterangan,
															a.tanggal,
															a.waktu,
															'Daftar Awal' AS status_biaya
															FROM daftar_awal a
															WHERE a.id_siswa = '$id_siswa'

															UNION ALL

															SELECT
															a.periode_tahun,
															a.biaya_daftar_ulang AS biaya,
															a.nominal_bayar,
															a.metode_pembayaran,
															a.kembali,
															a.keterangan,
															a.tanggal,
															a.waktu,
															'Daftar Ulang' AS status_biaya
															FROM daftar_ulang a
															WHERE a.id_siswa = '$id_siswa'
														) a
														ORDER BY STR_TO_DATE(a.tanggal, '%d-%m-%Y') ASC,
														STR_TO_DATE(a.waktu, '%H:%i:%s') ASC
														")->result_array();

		return $sql;
	}

	public function detail_kelas_result()
	{
		$id_siswa = $this->input->post('id_siswa');

		$sql = $this->db->query("SELECT
														j.nama_jenjang,
														k.nama_kelas,
														pp.tanggal_mulai,
														pp.tanggal_selesai,
														SUBSTRING(pp.tanggal_mulai, 7, 4) AS periode_tahun
														FROM pendaftaran_paket pp
														INNER JOIN siswa s ON s.id = pp.id_siswa
														INNER JOIN jenjang j ON j.id = pp.id_jenjang
														INNER JOIN kelas k ON k.id = pp.id_kelas
														WHERE pp.id_siswa = '$id_siswa'
														")->result_array();

		return $sql;
	}

	public function detail_paket_result()
	{
		$id_siswa = $this->input->post('id_siswa');

		$sql = $this->db->query("SELECT
														j.nama_jenjang,
														k.nama_kelas,
														pp.tanggal_mulai,
														pp.tanggal_selesai,
														p.nama_paket
														FROM pendaftaran_paket pp
														INNER JOIN jenjang j ON j.id = pp.id_jenjang
														INNER JOIN kelas k ON k.id = pp.id_kelas
														INNER JOIN paket p ON p.id = pp.id_paket
														WHERE pp.id_siswa = '$id_siswa'
														")->result_array();

		return $sql;
	}

	public function detail_pembayaran_result()
	{
		$id_siswa = $this->input->post('id_siswa');

		$sql = $this->db->query("SELECT
															byrs.pertemuan AS jumlah_meet,
															byrs.periode_tahun,
															byrs.periode_bulan,
															byrs.total_kas,
															byrs.total_harga_pertemuan,
															byrs.total_akhir,
															COALESCE(byrs.nominal_bayar, 0) AS nominal_bayar,
															COALESCE(byrs.kembali, 0) AS kembali,
															COALESCE(byrs.metode_pembayaran, 'Kosong') AS metode_pembayaran,
															byrs.`status`
															FROM pembayaran byrs
															INNER JOIN (
																SELECT
																a.*
																FROM pendaftaran_paket a
																WHERE a.id_siswa = '$id_siswa'
															) pp ON byrs.id_pendaftaran_paket = pp.id
															ORDER BY periode_tahun ASC,
															periode_bulan ASC
														 ")->result_array();

		return $sql;
	}
}
?>
