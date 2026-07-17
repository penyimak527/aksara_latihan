<?php
class M_jabatan extends CI_Model
{


	public function jabatan_result()
	{
		$search = $this->input->post('search');
		$where_search = "";
		if ($search != "") {
			$where_search = "AND (a.nama_jabatan LIKE '%$search%')";
		}

		$sql = $this->db->query("SELECT
														 a.*
														 FROM jabatan a
														 WHERE a.status_aktif = '1'
														 $where_search
														 ORDER BY a.id DESC
														")->result_array();

		return $sql;
	}

	public function tambah()
	{
		$nama_jabatan = $this->input->post('nama_jabatan');

		$data = [
			'nama_jabatan' => $nama_jabatan,
			'status_aktif' => '1'
		];

		$this->db->trans_begin();
		$this->db->insert('jabatan', $data);
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
		$id_jabatan = $this->input->post('id_jabatan');
		$nama_jabatan = $this->input->post('nama_jabatan');

		$data = [
			'nama_jabatan' => $nama_jabatan
		];

		$this->db->trans_begin();
		$this->db->update('jabatan', $data, ['id' => $id_jabatan]);
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
		$this->db->update('jabatan', $data, ['id' => $id]);
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