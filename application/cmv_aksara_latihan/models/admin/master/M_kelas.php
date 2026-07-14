<?php
class M_kelas extends CI_Model
{


	public function kelas_result()
	{
		$search = $this->input->post('search');
		$where_search = "";
		if ($search != "") {
			$where_search = "AND (a.nama_kelas LIKE '%$search%' OR b.nama_jenjang LIKE '%$search%')";
		}

		$sql = $this->db->query("SELECT
														 a.*,
                             b.nama_jenjang
														 FROM kelas a
                             INNER JOIN jenjang b ON a.id_jenjang = b.id
														 WHERE a.status_aktif = '1'
														 $where_search
														 ORDER BY a.id DESC
														")->result_array();

		return $sql;
	}

	public function tambah()
	{
		$id_jenjang = $this->input->post('id_jenjang');
		$nama_kelas = $this->input->post('nama_kelas');
		$urutan_kelas = $this->input->post('urutan_kelas');

		$data = [
			'id_jenjang' => $id_jenjang,
			'nama_kelas' => $nama_kelas,
			'urutan_kelas' => $urutan_kelas,
			'status_aktif' => '1'
		];

		$this->db->trans_begin();
		$this->db->insert('kelas', $data);
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
		$id_kelas = $this->input->post('id_kelas');
		$id_jenjang = $this->input->post('id_jenjang');
		$nama_kelas = $this->input->post('nama_kelas');
		$urutan_kelas = $this->input->post('urutan_kelas');

		$data = [
			'id_jenjang' => $id_jenjang,
			'nama_kelas' => $nama_kelas,
			'urutan_kelas' => $urutan_kelas
		];

		$this->db->trans_begin();
		$this->db->update('kelas', $data, ['id' => $id_kelas]);
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
		$this->db->update('kelas', $data, ['id' => $id]);
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