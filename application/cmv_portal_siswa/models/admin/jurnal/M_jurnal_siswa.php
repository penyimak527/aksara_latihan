<?php
class M_jurnal_siswa extends CI_Model
{


	public function jurnal_siswa_result()
	{
		$id_pegawai = $this->session->userdata('admin')['id_pegawai'];
		$level = $this->session->userdata('admin')['level'];

		$tanggal = $this->input->post('tanggal');
		$id_kelas = $this->input->post('id_kelas');
		$where = '';
		if ($tanggal != '') {
			$where .= " AND a.tanggal = '$tanggal'";
		}
		if ($id_kelas != '') {
			$where .= " AND a.id_kelas = '$id_kelas'";
		}

		if ($level == 'Admin' || $level == 'Owner') {
			$sql = $this->db->query("SELECT
															 a.*
															 FROM jurnal a
															 WHERE 1=1
															 $where
															 ORDER BY a.id DESC
															")->result_array();
		} else {
			$sql = $this->db->query("SELECT
															 a.*
															 FROM jurnal a
															 WHERE a.id_pegawai = '$id_pegawai'
															 $where
															 ORDER BY a.id DESC
															")->result_array();
		}

		return $sql;
	}

	public function tambah()
	{
		$tanggal = $this->input->post('tanggal');
		$id_pegawai = $this->session->userdata('admin')['id_pegawai'];
		$nama_pegawai = $this->session->userdata('admin')['nama_lengkap'];
		$id_jenjang = $this->input->post('id_jenjang');
		$nama_jenjang = $this->input->post('nama_jenjang');
		$id_kelas = $this->input->post('id_kelas');
		$nama_kelas = $this->input->post('nama_kelas');

		$id_siswa = $this->input->post('id_siswa');
		$nama_siswa = $this->input->post('nama_siswa');
		$nis = $this->input->post('nis');
		$status_presensi_siswa = $this->input->post('status_presensi_siswa');

		$data = [
			'id_pegawai' => $id_pegawai,
			'nama_pegawai' => $nama_pegawai,
			'id_jenjang' => $id_jenjang,
			'nama_jenjang' => $nama_jenjang,
			'id_kelas' => $id_kelas,
			'nama_kelas' => $nama_kelas,
			'tanggal' => $tanggal,
			'waktu' => date('H:i:s')
		];

		$this->db->trans_begin();
		$this->db->insert('jurnal', $data);
		$id_jurnal = $this->db->insert_id();

		$data_siswa = [];
		foreach ($id_siswa as $key => $value_id_siswa) {
			$data_siswa[] = [
				'id_jurnal' => $id_jurnal,
				'id_siswa' => $value_id_siswa,
				'nama_siswa' => $nama_siswa[$key],
				'nis' => $nis[$key],
				'status_presensi' => $status_presensi_siswa[$key]
			];
		}

		$this->db->insert_batch('jurnal_siswa', $data_siswa);
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
		$id_jurnal_siswa = $this->input->post('id_jurnal_siswa');
		$tanggal = $this->input->post('tanggal');
		$id_pegawai = $this->session->userdata('admin')['id_pegawai'];
		$nama_pegawai = $this->session->userdata('admin')['nama_lengkap'];
		$id_jenjang = $this->input->post('id_jenjang');
		$nama_jenjang = $this->input->post('nama_jenjang');
		$id_kelas = $this->input->post('id_kelas');
		$nama_kelas = $this->input->post('nama_kelas');

		$id_siswa = $this->input->post('id_siswa');
		$nama_siswa = $this->input->post('nama_siswa');
		$nis = $this->input->post('nis');
		$status_presensi_siswa = $this->input->post('status_presensi_siswa');

		$data = [
			'id_pegawai' => $id_pegawai,
			'nama_pegawai' => $nama_pegawai,
			'id_jenjang' => $id_jenjang,
			'nama_jenjang' => $nama_jenjang,
			'id_kelas' => $id_kelas,
			'nama_kelas' => $nama_kelas,
			'tanggal' => $tanggal,
			'waktu' => date('H:i:s')
		];

		$this->db->trans_begin();
		$this->db->update('jurnal', $data, ['id' => $id_jurnal_siswa]);
		$id_jurnal = $id_jurnal_siswa;
		$this->db->delete('jurnal_siswa', ['id_jurnal' => $id_jurnal]);


		$data_siswa = [];
		foreach ($id_siswa as $key => $value_id_siswa) {
			$data_siswa[] = [
				'id_jurnal' => $id_jurnal,
				'id_siswa' => $value_id_siswa,
				'nama_siswa' => $nama_siswa[$key],
				'nis' => $nis[$key],
				'status_presensi' => $status_presensi_siswa[$key]
			];
		}

		$this->db->insert_batch('jurnal_siswa', $data_siswa);
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

		$this->db->trans_begin();
		$this->db->delete('jurnal', ['id' => $id]);
		$this->db->delete('jurnal_siswa', ['id_jurnal' => $id]);
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
		$tanggal = $this->input->post('tanggal');
		$id_jenjang = $this->input->post('id_jenjang');
		$id_kelas = $this->input->post('id_kelas');
		$sql = $this->db->query("SELECT
                            s.id AS id_siswa,
                            s.nis,
                            s.nama_siswa,
                            IFNULL(js.status_presensi, 'Tidak Hadir') as status_presensi,
                            IFNULL(js.keterangan, '-') as keterangan
                            FROM kelas_setting ks
                            INNER JOIN (
															SELECT
															s.*
															FROM siswa s
															WHERE s.status_aktif = '1'
															AND s.status_siswa = 'LAMA'
														) s ON ks.id_kelas = s.id_kelas AND ks.id_jenjang = s.id_jenjang
                            LEFT JOIN (
                            	SELECT
                            	js.*,
                            	j.id_kelas,
                            	j.id_jenjang,
                            	j.tanggal
                            	FROM jurnal_siswa js
                            	INNER JOIN jurnal j ON js.id_jurnal = j.id
                            	WHERE j.tanggal = '$tanggal'
                            ) js ON ks.id_kelas = js.id_kelas AND ks.id_jenjang = js.id_jenjang AND s.id = js.id_siswa
                            WHERE ks.status_aktif = '1'
                            AND ks.id_jenjang = '$id_jenjang'
                            AND ks.id_kelas = '$id_kelas'
														GROUP BY s.id
														")->result_array();

		return $sql;
	}

	public function detail_siswa_result()
	{
		$tanggal = $this->input->post('tanggal');
		$id_pegawai = $this->input->post('id_pegawai');
		$id_jenjang = $this->input->post('id_jenjang');
		$id_kelas = $this->input->post('id_kelas');
		$sql = $this->db->query("SELECT
														js.*,
														j.id_kelas,
														j.id_jenjang,
														j.tanggal
														FROM jurnal_siswa js
														INNER JOIN jurnal j ON js.id_jurnal = j.id
														WHERE j.tanggal = '$tanggal'
														AND j.id_jenjang = '$id_jenjang'
														AND j.id_kelas = '$id_kelas'
														AND j.id_pegawai = '$id_pegawai'
														")->result_array();

		return $sql;
	}

	public function kelas_result()
	{
		$id_pegawai = $this->input->post('id_pegawai');
		$level = $this->session->userdata('admin')['level'];

		if ($level == 'Admin' || $level == 'Owner') {
			$sql = $this->db->query("SELECT
	                             k.*,
	                             j.nama_jenjang
	                             FROM kelas k
	                             INNER JOIN jenjang j ON k.id_jenjang = j.id
	                             WHERE k.status_aktif = '1'
															")->result_array();
		} else {
			$sql = $this->db->query("SELECT
	                             ks.*,
	                             j.nama_jenjang,
	                             k.nama_kelas
	                             FROM kelas_setting ks
	                             INNER JOIN jenjang j ON ks.id_jenjang = j.id
	                             INNER JOIN kelas k ON ks.id_kelas = k.id
	                             WHERE ks.status_aktif = '1'
	                             AND ks.id_pegawai = '$id_pegawai'
															")->result_array();
		}

		return $sql;
	}
}
?>