<?php
class M_beasiswa extends CI_Model
{


	public function beasiswa_result()
	{
		$search = $this->input->post('search');
		$where_search = "";
		if ($search != "") {
			$where_search = "AND (b.nama_siswa LIKE '%$search%')";
		}

		$sql = $this->db->query("SELECT
														 a.*,b.nama_siswa,c.nama_beasiswa
														 FROM siswa_beasiswa a
                             INNER JOIN siswa b ON a.id_siswa = b.id JOIN beasiswa c on a.id_beasiswa = c.id
														 WHERE a.status_aktif = '1'
														 $where_search
														 ORDER BY a.id DESC
														")->result_array();

		return $sql;
	}

	public function tambah()
	{
		$id_siswa = $this->input->post('id_siswa');
		$id_beasiswa = $this->input->post('id_beasiswa');
		$berlaku_mulai = $this->input->post('berlaku_mulai');
		$berlaku_sampai = $this->input->post('berlaku_sampai');

		$data = [
			'id_siswa' => $id_siswa,
			'id_beasiswa' => $id_beasiswa,
			'berlaku_mulai' => date('d-m-Y', strtotime($berlaku_mulai)),
			'berlaku_sampai' => date('d-m-Y', strtotime($berlaku_sampai)),
			'status_aktif' => '1'
		];

		$this->db->trans_begin();
		$this->db->trans_complete();
		$this->db->insert('siswa_beasiswa', $data);


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
		$id_beasiswa_siswa = $this->input->post('id');
		$id_siswa = $this->input->post('id_siswa');
		$id_beasiswa = $this->input->post('id_beasiswa');
		$berlaku_mulai = $this->input->post('berlaku_mulai');
		$berlaku_sampai = $this->input->post('berlaku_sampai');
		$data = [
			'id_siswa' => $id_siswa,
			'id_beasiswa' => $id_beasiswa,
			'berlaku_mulai' => date('d-m-Y', strtotime($berlaku_mulai)),
			'berlaku_sampai' => date('d-m-Y', strtotime($berlaku_sampai)),
			'status_aktif' => '1'
		];


		$this->db->trans_begin();
		$this->db->update('siswa_beasiswa', $data, ['id' => $id_beasiswa_siswa]);
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
		$this->db->update('siswa_beasiswa', $data, ['id' => $id]);
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