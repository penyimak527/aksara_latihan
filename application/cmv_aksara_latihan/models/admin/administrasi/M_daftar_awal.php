<?php
class M_daftar_awal extends CI_Model
{


	public function kode_nis()
	{
		$date = date('d-m-Y');
		$q = $this->db->query("SELECT
													MAX(RIGHT(nis,2)) AS kd_max,
													da.tanggal
													FROM siswa s
													INNER JOIN daftar_awal da ON s.id = da.id_siswa
                          WHERE tanggal = '$date'
                          ");
		$kd = "";
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $k) {
				$tmp = ((int) $k->kd_max) + 1;
				$kd = sprintf("%02s", $tmp);
			}
		} else {
			$kd = "01";
		}

		$kode = date('dmy') . $kd;
		return $kode;
	}

	public function daftar_awal_result()
	{
		$search = $this->input->post('search');
		$where_search = "";
		if ($search != "") {
			$where_search = "AND (b.nama_siswa LIKE '%$search%' OR b.alamat LIKE '%$search%')";
		}

		$sql = $this->db->query("SELECT
                              a.*,
                              b.nama_siswa,
                              b.alamat
                              FROM daftar_awal a
                              INNER JOIN siswa b ON a.id_siswa = b.id
                              $where_search
                              ORDER BY a.id DESC
														")->result_array();

		return $sql;
	}

	public function tambah()
	{
		$id_jenjang = $this->input->post('id_jenjang');
		$id_kelas = $this->input->post('id_kelas');
		$nis = $this->input->post('nis');
		$nama_siswa = $this->input->post('nama_siswa');
		$nama_wali = $this->input->post('nama_wali');
		$hp_wali = $this->input->post('hp_wali');
		$alamat = $this->input->post('alamat');
		$asal_sekolah = $this->input->post('asal_sekolah');
		$id_siswa = $this->input->post('id_siswa');
		$id_pegawai = $this->session->userdata('admin')['id_pegawai'];
		$nama_pegawai = $this->session->userdata('admin')['nama_lengkap'];

		$data_siswa = [
			'id_jenjang' => $id_jenjang,
			'id_kelas' => $id_kelas,
			'nis' => $nis,
			'nama_siswa' => $nama_siswa,
			'nama_wali' => $nama_wali,
			'hp_wali' => $hp_wali,
			'alamat' => $alamat,
			'asal_sekolah' => $asal_sekolah,
			'status_siswa' => 'LAMA',
			'status_aktif' => '1'
		];

		$this->db->trans_begin();
		if ($id_siswa == '') {
			$this->db->insert('siswa', $data_siswa);
			$id_siswa = $this->db->insert_id();
		} else {
			$id_siswa = $this->input->post('id_siswa');
		}

		$periode_tahun = $this->input->post('periode_tahun');
		$biaya_daftar_awal = str_replace(',', '', $this->input->post('biaya_daftar_awal'));
		$nominal_bayar = str_replace(',', '', $this->input->post('nominal_bayar'));
		$metode_pembayaran = $this->input->post('metode_pembayaran');
		$kembali = str_replace(',', '', $this->input->post('kembali'));
		$keterangan = $this->input->post('keterangan');

		$data = [
			'id_siswa' => $id_siswa,
			'periode_tahun' => $periode_tahun,
			'biaya_daftar_awal' => $biaya_daftar_awal,
			'nominal_bayar' => $nominal_bayar,
			'metode_pembayaran' => $metode_pembayaran,
			'kembali' => $kembali,
			'keterangan' => $keterangan,
			'tanggal' => date('d-m-Y'),
			'waktu' => date('H:i:s'),
			'id_pegawai' => $id_pegawai,
			'nama_pegawai' => $nama_pegawai
		];

		$this->db->insert('daftar_awal', $data);
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

	public function edit()
	{
		$id_daftar_awal = $this->input->post('id_daftar_awal');
		$id_siswa = $this->input->post('id_siswa');
		$periode_tahun = $this->input->post('periode_tahun');
		$biaya_daftar_awal = str_replace(',', '', $this->input->post('biaya_daftar_awal'));
		$nominal_bayar = str_replace(',', '', $this->input->post('nominal_bayar'));
		$metode_pembayaran = $this->input->post('metode_pembayaran');
		$kembali = str_replace(',', '', $this->input->post('kembali'));
		$keterangan = $this->input->post('keterangan');

		$data = [
			'id_siswa' => $id_siswa,
			'periode_tahun' => $periode_tahun,
			'biaya_daftar_awal' => $biaya_daftar_awal,
			'nominal_bayar' => $nominal_bayar,
			'metode_pembayaran' => $metode_pembayaran,
			'kembali' => $kembali,
			'keterangan' => $keterangan
		];

		$this->db->trans_begin();
		$this->db->update('daftar_awal', $data, ['id' => $id_daftar_awal]);
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
		$id_siswa = $this->input->post('id_siswa');

		$this->db->trans_begin();
		$this->db->delete('daftar_awal', ['id' => $id]);
		$this->db->delete('siswa', ['id' => $id_siswa]);
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

	public function kelas_result()
	{
		$id_jenjang = $this->input->post('id_jenjang');
		$sql = $this->db->query("SELECT
                              a.*
                              FROM kelas a
                              WHERE a.id_jenjang = '$id_jenjang'
														")->result_array();

		return $sql;
	}
}
?>