<?php
class M_pegawai extends CI_Model
{


	public function pegawai_result()
	{
		$search = $this->input->post('search');
		$where_search = "";
		if ($search != "") {
			$where_search = "AND (a.nama_pegawai LIKE '%$search%')";
		}

		$sql = $this->db->query("SELECT
														 a.*,
                             b.nama_jabatan
														 FROM pegawai a
                             INNER JOIN jabatan b ON a.id_jabatan = b.id
														 WHERE a.status_aktif = '1'
														 $where_search
														 ORDER BY a.id DESC
														")->result_array();

		return $sql;
	}

	public function tambah()
	{
		$nama_pegawai = $this->input->post('nama_pegawai');
		$id_jabatan = $this->input->post('id_jabatan');
		$jenis_kelamin = $this->input->post('jenis_kelamin');
		$tempat_lahir = $this->input->post('tempat_lahir');
		$tanggal_lahir = $this->input->post('tanggal_lahir');
		$no_telepon = $this->input->post('no_telepon');
		$pendidikan_sma = $this->input->post('pendidikan_sma');
		$pendidikan_kuliah = $this->input->post('pendidikan_kuliah');
		$alamat = $this->input->post('alamat');

		$data = [
			'nama_pegawai' => $nama_pegawai,
			'id_jabatan' => $id_jabatan,
			'pendidikan_sma' => $pendidikan_sma,
			'pendidikan_kuliah' => $pendidikan_kuliah,
			'jenis_kelamin' => $jenis_kelamin,
			'tempat_lahir' => $tempat_lahir,
			'tanggal_lahir' => $tanggal_lahir,
			'no_telepon' => $no_telepon,
			'alamat' => $alamat,
			'status_aktif' => '1'
		];

		$this->db->trans_begin();
		$this->db->insert('pegawai', $data);
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
		$id_pegawai = $this->input->post('id_pegawai');
		$nama_pegawai = $this->input->post('nama_pegawai');
		$id_jabatan = $this->input->post('id_jabatan');
		$jenis_kelamin = $this->input->post('jenis_kelamin');
		$tempat_lahir = $this->input->post('tempat_lahir');
		$tanggal_lahir = $this->input->post('tanggal_lahir');
		$no_telepon = $this->input->post('no_telepon');
		$pendidikan_sma = $this->input->post('pendidikan_sma');
		$pendidikan_kuliah = $this->input->post('pendidikan_kuliah');
		$alamat = $this->input->post('alamat');

		$data = [
			'nama_pegawai' => $nama_pegawai,
			'id_jabatan' => $id_jabatan,
			'pendidikan_sma' => $pendidikan_sma,
			'pendidikan_kuliah' => $pendidikan_kuliah,
			'jenis_kelamin' => $jenis_kelamin,
			'tempat_lahir' => $tempat_lahir,
			'tanggal_lahir' => $tanggal_lahir,
			'alamat'	=> $alamat,
			'no_telepon' => $no_telepon
		];

		$this->db->trans_begin();
		$this->db->update('pegawai', $data, ['id' => $id_pegawai]);
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
		$this->db->update('pegawai', $data, ['id' => $id]);
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