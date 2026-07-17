<?php
class M_daftar_ulang extends CI_Model
{


	public function daftar_ulang_result()
	{
		$search = $this->input->post('search');
		$where_search = "";
		if ($search != "") {
			$where_search = "AND (b.nama_siswa LIKE '%$search%' OR b.alamat LIKE '%$search%')";
		}

		$sql = $this->db->query("SELECT
                              a.*,
                              b.nama_siswa,
                              b.alamat
                              FROM daftar_ulang a
                              INNER JOIN siswa b ON a.id_siswa = b.id
                              $where_search
                              ORDER BY a.id DESC
														")->result_array();

		return $sql;
	}

	public function tambah()
	{
		$id_kelas_berikutnya = $this->input->post('id_kelas_berikutnya');
		$row_kelas = $this->db->get_where('kelas', array('id' => $id_kelas_berikutnya))->row_array();
		$id_jenjang = $row_kelas['id_jenjang'];
		$id_pegawai = $this->session->userdata('admin')['id_pegawai'];
		$nama_pegawai = $this->session->userdata('admin')['nama_lengkap'];

		$data_siswa = [
			'id_jenjang' => $id_jenjang,
			'id_kelas' => $id_kelas_berikutnya
		];

		$id_siswa = $this->input->post('id_siswa');
		$periode_tahun = $this->input->post('periode_tahun');
		$biaya_daftar_ulang = str_replace(',', '', $this->input->post('biaya_daftar_ulang'));
		$nominal_bayar = str_replace(',', '', $this->input->post('nominal_bayar'));
		$metode_pembayaran = $this->input->post('metode_pembayaran');
		$kembali = str_replace(',', '', $this->input->post('kembali'));
		$keterangan = $this->input->post('keterangan');

		$data = [
			'id_siswa' => $id_siswa,
			'periode_tahun' => $periode_tahun,
			'biaya_daftar_ulang' => $biaya_daftar_ulang,
			'nominal_bayar' => $nominal_bayar,
			'metode_pembayaran' => $metode_pembayaran,
			'kembali' => $kembali,
			'status_aktif' => 1,
			'keterangan' => $keterangan,
			'tanggal' => date('d-m-Y'),
			'waktu' => date('H:i:s'),
			'id_pegawai' => $id_pegawai,
			'nama_pegawai' => $nama_pegawai
		];

		$this->db->trans_begin();
		$this->db->update('siswa', $data_siswa, ['id' => $id_siswa]);
		$this->db->insert('daftar_ulang', $data);
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
		$id_daftar_ulang = $this->input->post('id_daftar_ulang');
		$id_siswa = $this->input->post('id_siswa');
		$periode_tahun = $this->input->post('periode_tahun');
		$biaya_daftar_ulang = str_replace(',', '', $this->input->post('biaya_daftar_ulang'));
		$nominal_bayar = str_replace(',', '', $this->input->post('nominal_bayar'));
		$metode_pembayaran = $this->input->post('metode_pembayaran');
		$kembali = str_replace(',', '', $this->input->post('kembali'));
		$keterangan = $this->input->post('keterangan');

		$data = [
			'id_siswa' => $id_siswa,
			'periode_tahun' => $periode_tahun,
			'biaya_daftar_ulang' => $biaya_daftar_ulang,
			'nominal_bayar' => $nominal_bayar,
			'metode_pembayaran' => $metode_pembayaran,
			'kembali' => $kembali,
			'keterangan' => $keterangan
		];

		$this->db->trans_begin();
		$this->db->update('daftar_ulang', $data, ['id' => $id_daftar_ulang]);
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

		$this->db->trans_begin();
		$this->db->delete('daftar_ulang', ['id' => $id]);
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

	public function siswa_result()
	{
		$sql = $this->db->query("SELECT
                              a.*
                              FROM siswa a
                              WHERE a.status_aktif = '1'
                              AND a.status_siswa = 'LAMA'
                              ORDER BY a.id DESC
														")->result_array();

		return $sql;
	}

	public function siswa_row()
	{
		$id_siswa = $this->input->post('id_siswa');
		$sql = $this->db->query("SELECT
                              a.*,
															b.nama_jenjang,
															c.nama_kelas
                              FROM siswa a
															INNER JOIN jenjang b ON a.id_jenjang = b.id
															INNER JOIN kelas c ON a.id_kelas = c.id
                              WHERE a.id = '$id_siswa'
														")->row_array();

		return $sql;
	}

	public function kelas_berikutnya_result($id_kelas)
	{
		$sql = $this->db->query("SELECT
                              a.*,
															b.nama_jenjang
                              FROM kelas a
															INNER JOIN jenjang b ON a.id_jenjang = b.id
															WHERE a.id != '$id_kelas'
															ORDER BY a.id_jenjang ASC,
															a.urutan_kelas ASC
														")->result_array();

		return $sql;
	}
}
?>