<?php
class M_jurnal_siswa_pengganti extends CI_Model
{

	public function jurnal_siswa_pengganti_result()
	{
		$id_pegawai = $this->session->userdata('admin')['id_pegawai'];
		$level = $this->session->userdata('admin')['level'];

		$tanggal = $this->input->post('tanggal');
		$where = '';
		if ($tanggal != '') {
			$where .= " AND a.tanggal = '$tanggal'";
		}

		$id_kelas = $this->input->post('id_kelas');
		if ($id_kelas != '') {
			$where .= " AND a.id_kelas = '$id_kelas'";
		}

		if ($level == 'Admin' || $level == 'Owner') {
			$sql = $this->db->query("SELECT
															 a.*
															 FROM jurnal_pengganti a
															 WHERE 1=1
															 $where
															 ORDER BY a.id DESC
															")->result_array();
		} else {
			$sql = $this->db->query("SELECT
															 a.*
															 FROM jurnal_pengganti a
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
		$this->db->insert('jurnal_pengganti', $data);
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

		$this->db->insert_batch('jurnal_siswa_pengganti', $data_siswa);
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
		$id_jurnal_siswa_pengganti = $this->input->post('id_jurnal_siswa_pengganti');
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
		$this->db->update('jurnal_pengganti', $data, ['id' => $id_jurnal_siswa_pengganti]);
		$id_jurnal = $id_jurnal_siswa_pengganti;
		$this->db->delete('jurnal_siswa_pengganti', ['id_jurnal' => $id_jurnal]);

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

		$this->db->insert_batch('jurnal_siswa_pengganti', $data_siswa);
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
		$this->db->delete('jurnal_pengganti', ['id' => $id]);
		$this->db->delete('jurnal_siswa_pengganti', ['id_jurnal' => $id]);
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
                            'Tidak Hadir' as status_presensi,
                            '-' as keterangan
                            FROM kelas_setting ks
                            INNER JOIN (
                            	SELECT
                            	s.*
                            	FROM siswa s
                            	WHERE s.status_aktif = '1'
                            	AND s.status_siswa = 'LAMA'
                            ) s ON ks.id_kelas = s.id_kelas AND ks.id_jenjang = s.id_jenjang
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
														FROM jurnal_siswa_pengganti js
														INNER JOIN jurnal_pengganti j ON js.id_jurnal = j.id
														WHERE j.tanggal = '$tanggal'
														AND j.id_pegawai = '$id_pegawai'
														AND j.id_jenjang = '$id_jenjang'
														AND j.id_kelas = '$id_kelas'
														")->result_array();
		return $sql;
	}

	public function kelas_result()
	{
		$id_pegawai = $this->input->post('id_pegawai');
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

		return $sql;
	}
}
?>