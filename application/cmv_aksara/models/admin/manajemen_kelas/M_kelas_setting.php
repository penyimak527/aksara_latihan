<?php
class M_kelas_setting extends CI_Model
{

	public function kelas_setting_result()
	{
		$id_kelas = $this->input->post('id_kelas');
		$where_id_kelas = "";
		if ($id_kelas != "") {
			$where_id_kelas = "AND a.id_kelas = '$id_kelas'";
		}

		$id_pegawai = $this->input->post('id_pegawai');
		$where_id_pegawai = "";
		if ($id_pegawai != "") {
			$where_id_pegawai = "AND a.id_pegawai = '$id_pegawai'";
		}

		$sql = $this->db->query("SELECT
														 a.*,
														 b.nama_kelas,
														 c.nama_pegawai,
														 d.nama_jenjang
														 FROM kelas_setting a
														 INNER JOIN kelas b ON a.id_kelas = b.id
														 INNER JOIN pegawai c ON a.id_pegawai = c.id
														 INNER JOIN jenjang d ON a.id_jenjang = d.id
														 WHERE 1=1 AND a.status_aktif = '1'
														 $where_id_kelas
														 $where_id_pegawai
														 ORDER BY a.id DESC
														")->result_array();

		return $sql;
	}

	public function tambah()
	{
		$id_kelas = $this->input->post('id_kelas');
		$id_pegawai = $this->input->post('id_pegawai');

		$row_kelas = $this->db->get_where('kelas', array('id' => $id_kelas))->row_array();
		$id_jenjang = $row_kelas['id_jenjang'];

		$cek_data = $this->db->get_where('kelas_setting', array('id_pegawai' => $id_pegawai, 'id_kelas' => $id_kelas, 'status_aktif' => 1))->row_array();
		if ($cek_data) {
			$response = array(
				'result' => 'double'
			);
		} else {
			$data = [
				'id_jenjang' => $id_jenjang,
				'id_kelas' => $id_kelas,
				'id_pegawai' => $id_pegawai,
				'tanggal_mulai	' => date('d-m-Y'),
				'status_aktif' => 1
			];

			$this->db->trans_begin();
			$this->db->insert('kelas_setting', $data);
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
		}

		return $response;
	}

	public function edit()
	{
		$id_kelas_setting = $this->input->post('id_kelas_setting');
		$id_kelas = $this->input->post('id_kelas');
		$id_pegawai = $this->input->post('id_pegawai');

		$row_kelas = $this->db->get_where('kelas', array('id' => $id_kelas))->row_array();
		$id_jenjang = $row_kelas['id_jenjang'];

		$data = [
			'id_pegawai' => $id_pegawai,
			'id_jenjang' => $id_jenjang,
			'id_kelas' => $id_kelas
		];

		$this->db->trans_begin();
		$this->db->update('kelas_setting', $data, ['id' => $id_kelas_setting]);
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
			'tanggal_selesai' => date('d-m-Y'),
			'status_aktif' => '0'
		];

		$this->db->trans_begin();
		$this->db->update('kelas_setting', $data, ['id' => $id]);
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
}
?>