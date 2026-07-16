<?php
class M_pendaftaran_paket extends CI_Model
{


	public function pendaftaran_paket_result()
	{
		$search = $this->input->post('search');
		$where_search = "";
		if ($search != "") {
			$where_search = "AND (b.nama_siswa LIKE '%$search%')";
		}

		$sql = $this->db->query("SELECT
														 a.*,
                             b.nama_siswa,
                             c.nama_paket,
                             d.nama_jenjang
														 FROM pendaftaran_paket a
                             INNER JOIN siswa b ON a.id_siswa = b.id
                             INNER JOIN paket c ON a.id_paket = c.id
                             INNER JOIN jenjang d ON a.id_jenjang = d.id
														 WHERE a.status_aktif = '1'
														 $where_search
														 ORDER BY a.id DESC
														")->result_array();

		return $sql;
	}

	public function tambah()
	{
		$jenis_administrasi = $this->input->post('jenis_administrasi');
		$id_siswa = $this->input->post('id_siswa');
		$id_kelas = $this->input->post('id_kelas');
		$id_jenjang = $this->input->post('id_jenjang');
		$id_paket = $this->input->post('id_paket');
		$id_paket_harga = $this->input->post('id_paket_harga');
		$diskon = str_replace(',', '', $this->input->post('diskon'));
		$tanggal_mulai = date('d-m-Y', strtotime($this->input->post('tanggal_mulai')));
		$tanggal_selesai = date('d-m-Y', strtotime($this->input->post('tanggal_selesai')));

		$data = [
			'id_siswa' => $id_siswa,
			'id_jenjang' => $id_jenjang,
			'id_kelas' => $id_kelas,
			'id_paket' => $id_paket,
			'id_paket_harga' => $id_paket_harga,
			'jenis_administrasi' => $jenis_administrasi,
			'diskon' => $diskon,
			'tanggal_mulai' => $tanggal_mulai,
			'tanggal_selesai' => $tanggal_selesai,
			'status_aktif' => '1'
		];

		$this->db->trans_begin();
		$this->db->insert('pendaftaran_paket', $data);
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
		$id_pendaftaran_paket = $this->input->post('id_pendaftaran_paket');
		$jenis_administrasi = $this->input->post('jenis_administrasi');
		$id_siswa = $this->input->post('id_siswa');
		$id_kelas = $this->input->post('id_kelas');
		$id_jenjang = $this->input->post('id_jenjang');
		$id_paket = $this->input->post('id_paket');
		$id_paket_harga = $this->input->post('id_paket_harga');
		$diskon = str_replace(',', '', $this->input->post('diskon'));
		$tanggal_mulai = date('d-m-Y', strtotime($this->input->post('tanggal_mulai')));
		$tanggal_selesai = date('d-m-Y', strtotime($this->input->post('tanggal_selesai')));

		$tanggal_mulai = date('d-m-Y', strtotime($this->input->post('tanggal_mulai')));
		$tanggal_selesai = date('d-m-Y', strtotime($this->input->post('tanggal_selesai')));

		$data = [
			'id_siswa' => $id_siswa,
			'id_jenjang' => $id_jenjang,
			'id_kelas' => $id_kelas,
			'id_paket' => $id_paket,
			'id_paket_harga' => $id_paket_harga,
			'jenis_administrasi' => $jenis_administrasi,
			'diskon' => $diskon,
			'tanggal_mulai' => $tanggal_mulai,
			'tanggal_selesai' => $tanggal_selesai,
			'status_aktif' => '1'
		];

		$this->db->trans_begin();
		$this->db->update('pendaftaran_paket', $data, ['id' => $id_pendaftaran_paket]);
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
		$this->db->update('pendaftaran_paket', $data, ['id' => $id]);
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

	public function siswa_result()
	{
		$bulan = date('m');
		$tahun = date('Y');
		$id_siswa = $this->input->post('id_siswa');

		if ($id_siswa == null) {
			$sql = $this->db->query("SELECT
																a.*,
																pp.id as id_pendaftaran_paket
																FROM siswa a
																LEFT JOIN (
																	SELECT
																	pp.id,
																	pp.id_siswa
																	FROM pendaftaran_paket pp
																	WHERE STR_TO_DATE(pp.tanggal_mulai,'%d-%m-%Y') <= LAST_DAY(STR_TO_DATE(CONCAT('01-', LPAD('$bulan',2,'0'), '-', '$tahun'), '%d-%m-%Y'))
																	AND STR_TO_DATE(pp.tanggal_selesai,'%d-%m-%Y') >= STR_TO_DATE(CONCAT('01-', LPAD('$bulan',2,'0'), '-', '$tahun'), '%d-%m-%Y')
																) pp ON a.id = pp.id_siswa
																WHERE a.status_aktif = '1'
																AND a.status_siswa = 'LAMA'
																AND pp.id IS NULL
																ORDER BY a.id DESC
															")->result_array();

		} else {
			$sql = $this->db->query("SELECT
																a.*
																FROM siswa a
																WHERE a.status_aktif = '1'
																AND a.status_siswa = 'LAMA'
																ORDER BY a.id DESC
															")->result_array();
		}

		return $sql;
	}

	public function siswa_row()
	{
		$id_siswa = $this->input->post('id_siswa');
		$sql = $this->db->query("SELECT
                              a.*,
															b.nama_jenjang,
															c.nama_kelas
                              FROM siswa a
															INNER JOIN jenjang b ON a.id_jenjang = b.id
															INNER JOIN kelas c ON a.id_kelas = c.id
                              WHERE a.id = '$id_siswa'
														")->row_array();
		return $sql;
	}

	public function paket_harga_result($id_jenjang)
	{
		$sql = $this->db->query("SELECT
                              a.*,
															b.nama_jenjang,
                              c.nama_paket
                              FROM paket_harga a
															INNER JOIN jenjang b ON a.id_jenjang = b.id
                              INNER JOIN paket c ON a.id_paket = c.id
															WHERE a.id_jenjang = '$id_jenjang'
														")->result_array();

		return $sql;
	}
}
?>
