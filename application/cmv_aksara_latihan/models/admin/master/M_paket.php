<?php
class M_paket extends CI_Model
{

	public function paket_result()
	{
		$search = $this->input->post('search');
		$where_search = "";
		if ($search != "") {
			$where_search = "AND (a.nama_paket LIKE '%$search%')";
		}

		$sql = $this->db->query("SELECT
														 a.*
														 FROM paket a
														 WHERE a.status_aktif = '1'
														 $where_search
                             ORDER BY a.urutan DESC
														")->result_array();

		return $sql;
	}

	public function tambah()
	{
		$nama_paket = $this->input->post('nama_paket');
		$urutan = $this->input->post('urutan');

		$data = [
			'nama_paket' => $nama_paket,
			'urutan' => $urutan,
			'status_aktif' => '1'
		];

		$this->db->trans_begin();
		$this->db->insert('paket', $data);
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
		$id_paket = $this->input->post('id_paket');
		$nama_paket = $this->input->post('nama_paket');
		$urutan = $this->input->post('urutan');

		$data = [
			'nama_paket' => $nama_paket,
			'urutan' => $urutan
		];

		$this->db->trans_begin();
		$this->db->update('paket', $data, ['id' => $id_paket]);
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
		$this->db->update('paket', $data, ['id' => $id]);
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