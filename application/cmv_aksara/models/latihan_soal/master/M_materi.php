<?php
class M_materi extends CI_Model
{
	private function post_text($name)
	{
		return trim((string) $this->input->post($name, true));
	}

	private function status_value($status)
	{
		return ((string) $status === '0') ? '0' : '1';
	}

	public function mapel_options($active_only = false)
	{
		if (!$this->db->table_exists('mata_pelajaran')) {
			return array();
		}

		$this->db->select('id, nama_mata_pelajaran, status_aktif');
		$this->db->from('mata_pelajaran');
		if ($active_only) {
			$this->db->where('status_aktif', '1');
		}
		$this->db->order_by('nama_mata_pelajaran', 'ASC');
		return $this->db->get()->result_array();
	}

	public function materi_result()
	{
		$search = $this->post_text('search');
		$id_mapel = $this->post_text('id_mata_pelajaran');
		$status = $this->post_text('status');

		$where_search = '';
		$where_mapel = '';
		$where_status = '';

		if ($search != '') {
			$search = $this->db->escape_like_str($search);
			$where_search = "AND (a.nama_materi LIKE '%$search%' OR a.keterangan LIKE '%$search%' OR b.nama_mata_pelajaran LIKE '%$search%')";
		}

		if ($id_mapel != '' && $id_mapel != 'Semua') {
			$where_mapel = "AND a.id_mata_pelajaran = '" . (int) $id_mapel . "'";
		}

		if ($status != '' && $status != 'Semua') {
			$where_status = "AND a.status_aktif = '" . $this->status_value($status) . "'";
		}

		$sql = $this->db->query("SELECT
									a.*,
									b.nama_mata_pelajaran
								FROM materi a
								LEFT JOIN mata_pelajaran b ON a.id_mata_pelajaran = b.id
								WHERE 1=1
								$where_search
								$where_mapel
								$where_status
								ORDER BY a.status_aktif DESC, b.nama_mata_pelajaran ASC, a.nama_materi ASC
							")->result_array();

		return array('status' => true, 'result' => 'true', 'data' => $sql);
	}

	public function result()
	{
		return $this->materi_result();
	}

	public function tambah()
	{
		$id_mapel = (int) $this->input->post('id_mata_pelajaran');
		$nama = strtoupper($this->post_text('nama_materi'));
		$keterangan = $this->post_text('keterangan');
		$status = $this->status_value($this->post_text('status_aktif'));

		if ($id_mapel <= 0 || $nama == '') {
			return array('status' => false, 'result' => 'false', 'message' => 'Mata pelajaran dan nama materi wajib diisi');
		}

		$mapel = $this->db->get_where('mata_pelajaran', array('id' => $id_mapel))->row_array();
		if (!$mapel) {
			return array('status' => false, 'result' => 'false', 'message' => 'Mata pelajaran tidak ditemukan');
		}

		$cek = $this->db->query("SELECT id FROM materi WHERE LOWER(nama_materi) = ? AND id_mata_pelajaran = ?", array(strtolower($nama), $id_mapel))->row_array();
		if ($cek) {
			return array('status' => false, 'result' => 'false', 'message' => 'Materi pada mata pelajaran tersebut sudah ada');
		}

		$data = array(
			'id_mata_pelajaran' => $id_mapel,
			'nama_materi' => $nama,
			'keterangan' => $keterangan,
			'status_aktif' => $status,
			'created_at' => date('d-m-Y H:i:s'),
			'updated_at' => date('d-m-Y H:i:s')
		);

		$this->db->trans_begin();
		$this->db->insert('materi', $data);
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
		$id = (int) $this->input->post('id_materi');
		$id_mapel = (int) $this->input->post('id_mata_pelajaran');
		$nama = strtoupper($this->post_text('nama_materi'));
		$keterangan = $this->post_text('keterangan');
		$status = $this->status_value($this->post_text('status_aktif'));

		if ($id <= 0 || $id_mapel <= 0 || $nama == '') {
			return array('status' => false, 'result' => 'false', 'message' => 'Data belum lengkap');
		}

		$cek = $this->db->query("SELECT id FROM materi WHERE LOWER(nama_materi) = ? AND id_mata_pelajaran = ? AND id != ?", array(strtolower($nama), $id_mapel, $id))->row_array();
		if ($cek) {
			return array('status' => false, 'result' => 'false', 'message' => 'Materi pada mata pelajaran tersebut sudah digunakan');
		}

		$data = array(
			'id_mata_pelajaran' => $id_mapel,
			'nama_materi' => $nama,
			'keterangan' => $keterangan,
			'status_aktif' => $status,
			'updated_at' => date('d-m-Y H:i:s')
		);

		$this->db->trans_begin();
		$this->db->where('id', $id);
		$this->db->update('materi', $data);
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
			return array('status' => false, 'result' => 'false', 'message' => 'ID materi tidak valid');
		}

		$this->db->trans_begin();
		$this->db->where('id', $id);
		$this->db->update('materi', array('status_aktif' => $status, 'updated_at' => date('d-m-Y H:i:s')));
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