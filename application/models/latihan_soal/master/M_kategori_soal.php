<?php
class M_kategori_soal extends CI_Model
{
	private function post_text($name)
	{
		return trim((string) $this->input->post($name, true));
	}

	private function status_value($status)
	{
		return ((string) $status === '0') ? '0' : '1';
	}

	public function kategori_soal_result()
	{
		$search = $this->post_text('search');
		$status = $this->post_text('status');
		$where_search = '';
		$where_status = '';

		if ($search != '') {
			$search = $this->db->escape_like_str($search);
			$where_search = "AND (a.nama_kategori_soal LIKE '%$search%' OR a.keterangan LIKE '%$search%')";
		}

		if ($status != '' && $status != 'Semua') {
			$where_status = "AND a.status_aktif = '" . $this->status_value($status) . "'";
		}

		$sql = $this->db->query("SELECT
									a.*
								FROM soal_kategori a
								WHERE 1=1
								$where_search
								$where_status
								ORDER BY a.status_aktif DESC, a.nama_kategori_soal ASC
							")->result_array();

		return array('status' => true, 'result' => 'true', 'data' => $sql);
	}

	public function result()
	{
		return $this->kategori_soal_result();
	}

	public function tambah()
	{
		$nama = strtoupper($this->post_text('nama_kategori_soal'));
		$keterangan = $this->post_text('keterangan');
		$status = $this->status_value($this->post_text('status_aktif'));

		if ($nama == '') {
			return array('status' => false, 'result' => 'false', 'message' => 'Nama kategori soal wajib diisi');
		}

		$cek = $this->db->query("SELECT id FROM soal_kategori WHERE LOWER(nama_kategori_soal) = ?", array(strtolower($nama)))->row_array();
		if ($cek) {
			return array('status' => false, 'result' => 'false', 'message' => 'Nama kategori soal sudah ada');
		}

		$data = array(
			'nama_kategori_soal' => $nama,
			'keterangan' => $keterangan,
			'status_aktif' => $status,
			'created_at' => date('d-m-Y H:i:s'),
			'updated_at' => date('d-m-Y H:i:s')
		);

		$this->db->trans_begin();
		$this->db->insert('soal_kategori', $data);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return array('status' => false, 'result' => 'false', 'message' => 'Data gagal disimpan');
		}
		$this->db->trans_commit();
		return array('status' => true, 'result' => 'true', 'message' => 'Data berhasil disimpan');
	}

	public function edit()
	{
		$id = (int) $this->input->post('id_kategori_soal');
		$nama = strtoupper($this->post_text('nama_kategori_soal'));
		$keterangan = $this->post_text('keterangan');
		$status = $this->status_value($this->post_text('status_aktif'));

		if ($id <= 0 || $nama == '') {
			return array('status' => false, 'result' => 'false', 'message' => 'Data belum lengkap');
		}

		$cek = $this->db->query("SELECT id FROM soal_kategori WHERE LOWER(nama_kategori_soal) = ? AND id != ?", array(strtolower($nama), $id))->row_array();
		if ($cek) {
			return array('status' => false, 'result' => 'false', 'message' => 'Nama kategori soal sudah digunakan');
		}

		$data = array(
			'nama_kategori_soal' => $nama,
			'keterangan' => $keterangan,
			'status_aktif' => $status,
			'updated_at' => date('d-m-Y H:i:s')
		);

		$this->db->trans_begin();
		$this->db->where('id', $id);
		$this->db->update('soal_kategori', $data);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return array('status' => false, 'result' => 'false', 'message' => 'Data gagal diupdate');
		}
		$this->db->trans_commit();
		return array('status' => true, 'result' => 'true', 'message' => 'Data berhasil diupdate');
	}

	public function ubah_status()
	{
		$id = (int) $this->input->post('id');
		$status = $this->status_value($this->post_text('status_aktif'));
		if ($id <= 0) {
			return array('status' => false, 'result' => 'false', 'message' => 'ID kategori soal tidak valid');
		}

		$this->db->trans_begin();
		$this->db->where('id', $id);
		$this->db->update('soal_kategori', array('status_aktif' => $status, 'updated_at' => date('d-m-Y H:i:s')));
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return array('status' => false, 'result' => 'false', 'message' => 'Status gagal diubah');
		}
		$this->db->trans_commit();
		return array('status' => true, 'result' => 'true', 'message' => 'Status berhasil diubah');
	}
}
?>
