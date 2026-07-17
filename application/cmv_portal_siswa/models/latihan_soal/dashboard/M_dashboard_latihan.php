<?php
class M_dashboard_latihan extends CI_Model
{
    private function safe_count($table, $where = [])
    {
        if (!$this->db->table_exists($table)) {
            return 0;
        }

        if (!empty($where)) {
            $this->db->where($where);
        }

        return (int) $this->db->count_all_results($table);
    }

    public function ringkasan_menu()
    {
        return [
            'total_mapel' => $this->safe_count('mata_pelajaran', ['status_aktif' => '1']),
            'total_materi' => $this->safe_count('materi', ['status_aktif' => '1']),
            'total_kategori' => $this->safe_count('soal_kategori', ['status_aktif' => '1']),
            'total_soal' => $this->safe_count('soal_naskah', ['status_aktif' => '1']),
            'total_sesi' => $this->safe_count('soal_sesi', ['status_aktif' => '1']),
            'total_hasil' => $this->safe_count('siswa_pengerjaan')
        ];
    }
}
?>
