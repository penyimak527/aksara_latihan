<?php
class M_paket_harga extends CI_Model
{


	public function paket_harga_result()
	{
		$search = $this->input->post('search');
		$where_search = "";
		if ($search != "") {
			$where_search = "AND (b.nama_paket LIKE '%$search%' OR c.nama_jenjang LIKE '%$search%')";
		}

		$sql = $this->db->query("SELECT
                              a.*,
                              b.nama_paket,
                              c.nama_jenjang
                              FROM paket_harga a
                              INNER JOIN paket b ON a.id_paket = b.id
                              INNER JOIN jenjang c ON a.id_jenjang = c.id
                              WHERE a.status_aktif = '1'
                              $where_search
                              ORDER BY a.id DESC
														")->result_array();

		return $sql;
	}

	public function tambah()
	{
		$id_paket = $this->input->post('id_paket');
		$id_jenjang = $this->input->post('id_jenjang');
		$harga_pertemuan = str_replace(',', '', $this->input->post('harga_pertemuan'));
		$iuran_kas = str_replace(',', '', $this->input->post('iuran_kas'));
		$target_meet_bulanan = str_replace(',', '', $this->input->post('target_meet_bulanan'));

		$data = [
			'id_paket' => $id_paket,
			'id_jenjang' => $id_jenjang,
			'harga_pertemuan' => $harga_pertemuan,
			'iuran_kas' => $iuran_kas,
			'target_meet_bulanan' => $target_meet_bulanan,
			'status_aktif' => '1'
		];

		$this->db->trans_begin();
		$this->db->insert('paket_harga', $data);
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
		$id_paket_harga = $this->input->post('id_paket_harga');
		$id_paket = $this->input->post('id_paket');
		$id_jenjang = $this->input->post('id_jenjang');
		$harga_pertemuan = str_replace(',', '', $this->input->post('harga_pertemuan'));
		$iuran_kas = str_replace(',', '', $this->input->post('iuran_kas'));
		$target_meet_bulanan = str_replace(',', '', $this->input->post('target_meet_bulanan'));

		$data = [
			'id_paket' => $id_paket,
			'id_jenjang' => $id_jenjang,
			'harga_pertemuan' => $harga_pertemuan,
			'iuran_kas' => $iuran_kas,
			'target_meet_bulanan' => $target_meet_bulanan
		];

		$this->db->trans_begin();
		$this->db->update('paket_harga', $data, ['id' => $id_paket_harga]);
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
		$this->db->update('paket_harga', $data, ['id' => $id]);
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