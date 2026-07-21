<?php
class M_dashboard extends CI_Model
{


	public function mapel_result()
	{


		$id_siswa = $this->session->userdata('siswa')['id_user'];

		$kelas_siswa = $this->db->get_where('kelas_siswa', array('id_siswa' => $id_siswa))->row_array();

		$kelas_setting = $this->db->get_where('kelas_setting', array('id' => $kelas_siswa['id_kelas_setting']))->row_array();
		$this->db->select('id_mapel, mapel');
		$this->db->from('kelas_jadwal_pelajaran');
		$this->db->where('id_kelas_setting', $kelas_setting['id']);
		$this->db->group_by('mapel');
		$mapel = $this->db->get()->result_array();

		$data = array();
		foreach ($mapel as $m) {
			$data[] = array(
				'id_mapel' => $m['id_mapel'],
				'mapel' => $m['mapel'],
			);
		}
		return $data;
	}
	public function jadwal_result()
	{

		$hariInggris = date('l');
		$daftarHari = [
			'Sunday' => 'Minggu',
			'Monday' => 'Senin',
			'Tuesday' => 'Selasa',
			'Wednesday' => 'Rabu',
			'Thursday' => 'Kamis',
			'Friday' => 'Jumat',
			'Saturday' => 'Sabtu'
		];

		$hari = $daftarHari[$hariInggris];
		$tanggal = date('d-m-Y');

		$sql = " SELECT 
				a.*, 
				a.id AS id_jadwal,
				b.kode_kelas  
			FROM 
				kelas_jadwal_pelajaran a left join kelas b on a.id_kelas=b.id
			WHERE 
				a.hari = ?
				AND a.id NOT IN (
					SELECT id_kelas_jadwal_pelajaran 
					FROM jurnal_guru 
					WHERE tanggal = ?
				)
			ORDER BY 
				a.jam_pelajaran_awal ASC
		";


		$query = $this->db->query($sql, [$hari, $tanggal]);
		$result = $query->result_array();




		return $result;
	}
	public function dashboard_result()
	{


		$id_siswa = $this->session->userdata('siswa')['id_user'];

		$kelas_siswa = $this->db->get_where('kelas_siswa', array('id_siswa' => $id_siswa))->row_array();

		$kelas_setting = $this->db->get_where('kelas_setting', array('id' => $kelas_siswa['id_kelas_setting']))->row_array();
		$this->db->select('id_mapel, mapel,nama_guru');
		$this->db->from('kelas_jadwal_pelajaran');
		$this->db->where('id_kelas_setting', $kelas_setting['id']);
		$this->db->group_by('mapel');
		$mapel = $this->db->get()->result_array();

		$data = array();
		foreach ($mapel as $m) {
			$data[] = array(
				'id_mapel' => $m['id_mapel'],
				'mapel' => $m['mapel'],
				'guru' => $m['nama_guru'],
			);
		}
		return $data;
	}
}
?>