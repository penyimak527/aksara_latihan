<?php
class M_beasiswa extends CI_Model
{


	public function beasiswa_result()
	{
		$search = $this->input->post('search');
		$where_search = "";
		if ($search != "") {
			$where_search = "AND (a.nama_beasiswa LIKE '%$search%')";
		}

		$sql = $this->db->query("SELECT
														 a.*
														 FROM beasiswa a
														 WHERE a.status_aktif = '1'
														 $where_search
														 ORDER BY a.id DESC
														")->result_array();

		return $sql;
	}

	public function tambah()
	{
		$nama_beasiswa = $this->input->post('nama_beasiswa');
		$tipe = $this->input->post('tipe');
		$nilai = str_replace(',', '', $this->input->post('nilai'));
		$keterangan = $this->input->post('keterangan');

		$data = [
			'nama_beasiswa' => $nama_beasiswa,
			'tipe' => $tipe,
			'nilai' => $nilai,
			'keterangan' => $keterangan,
			'status_aktif' => '1'
		];

		$this->db->trans_begin();
		$this->db->insert('beasiswa', $data);
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
		$id_beasiswa = $this->input->post('id_beasiswa');
		$nama_beasiswa = $this->input->post('nama_beasiswa');
		$tipe = $this->input->post('tipe');
		$nilai = str_replace(',', '', $this->input->post('nilai'));
		$keterangan = $this->input->post('keterangan');

		$data = [
			'nama_beasiswa' => $nama_beasiswa,
			'tipe' => $tipe,
			'nilai' => $nilai,
			'keterangan' => $keterangan
		];

		$this->db->trans_begin();
		$this->db->update('beasiswa', $data, ['id' => $id_beasiswa]);
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
		$this->db->update('beasiswa', $data, ['id' => $id]);
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