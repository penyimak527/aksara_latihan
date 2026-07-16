<?php
class M_jenjang extends CI_Model
{


	public function jenjang_result()
	{
		$search = $this->input->post('search');
		$where_search = "";
		if ($search != "") {
			$where_search = "AND (a.nama_jenjang LIKE '%$search%')";
		}

		$sql = $this->db->query("SELECT
														 a.*
														 FROM jenjang a
														 WHERE a.status_aktif = '1'
														 $where_search
														 ORDER BY a.id DESC
														")->result_array();

		return $sql;
	}

	public function tambah()
	{
		$nama_jenjang = $this->input->post('nama_jenjang');

		$data = [
			'nama_jenjang' => $nama_jenjang,
			'status_aktif' => '1'
		];

		$this->db->trans_begin();
		$this->db->insert('jenjang', $data);
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
		$id_jenjang = $this->input->post('id_jenjang');
		$nama_jenjang = $this->input->post('nama_jenjang');

		$data = [
			'nama_jenjang' => $nama_jenjang
		];

		$this->db->trans_begin();
		$this->db->update('jenjang', $data, ['id' => $id_jenjang]);
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
		$this->db->update('jenjang', $data, ['id' => $id]);
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