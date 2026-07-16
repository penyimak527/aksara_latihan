<?php
class M_izin_preview extends CI_Model
{
    private function post_text($name)
    {
        return trim((string) $this->input->post($name, true));
    }

    private function nilai_format($nilai)
    {
        if ($nilai === null || $nilai === '') {
            return '-';
        }
        return rtrim(rtrim(number_format((float) $nilai, 2, '.', ''), '0'), '.') . '%';
    }

    public function dropdown()
    {
        $tahun = [];
        $kelas = [];
        $sesi = [];

        if ($this->db->table_exists('soal_sesi')) {
            $tahun = $this->db->select('tahun_ajaran')
                ->from('soal_sesi')
                ->where('tahun_ajaran !=', '')
                ->group_by('tahun_ajaran')
                ->order_by('tahun_ajaran', 'DESC')
                ->get()
                ->result_array();

            $sesi = $this->db->select('id, nama_sesi')
                ->from('soal_sesi')
                ->where('status_hapus IS NULL', null, false)
                ->order_by('created_at', 'DESC')
                ->get()
                ->result_array();
        }

        if ($this->db->table_exists('kelas')) {
            $kelas = $this->db->select('id, nama_kelas')
                ->from('kelas')
                ->where("(status_aktif = '1' OR status_aktif IS NULL OR status_aktif = '')", null, false)
                ->order_by('CAST(urutan_kelas AS UNSIGNED)', 'ASC', false)
                ->order_by('nama_kelas', 'ASC')
                ->get()
                ->result_array();
        }

        return ['tahun' => $tahun, 'kelas' => $kelas, 'sesi' => $sesi];
    }

    public function result()
    {
        $search = $this->post_text('search');
        $tahun_ajaran = $this->post_text('tahun_ajaran');
        $id_kelas = $this->post_text('id_kelas');
        $id_sesi = $this->post_text('id_sesi_soal');
        $preview = $this->post_text('preview_diizinkan');

        $this->db->select('p.id_sesi_soal, p.id_siswa, p.id_kelas, p.tahun_ajaran');
        $this->db->select('s.nama_siswa, s.nis, k.nama_kelas, ss.nama_sesi, mp.nama_mata_pelajaran');
        $this->db->select('GROUP_CONCAT(DISTINCT p.jenis_pengerjaan ORDER BY FIELD(p.jenis_pengerjaan, "Bimbel", "Rumah") SEPARATOR ", ") AS jenis_pengerjaan', false);
        $this->db->select('COUNT(DISTINCT p.id) AS total_pengerjaan', false);
        $this->db->select('MIN(COALESCE(CAST(p.preview_diizinkan AS UNSIGNED), 0)) AS preview_diizinkan', false);
        $this->db->select('MAX(CAST(p.nilai_akhir AS DECIMAL(10,2))) AS nilai_akhir', false);
        $this->db->select('MAX(p.updated_at) AS updated_at', false);
        $this->db->from('siswa_pengerjaan p');
        $this->db->join('siswa s', 'p.id_siswa = s.id', 'left');
        $this->db->join('kelas k', 'p.id_kelas = k.id', 'left');
        $this->db->join('soal_sesi ss', 'p.id_sesi_soal = ss.id', 'left');
        $this->db->join('mata_pelajaran mp', 'ss.id_mata_pelajaran = mp.id', 'left');
        $this->db->where_in('p.status_pengerjaan', ['Selesai', 'Waktu Habis', 'Selesai karena timer habis']);

        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('s.nama_siswa', $search);
            $this->db->or_like('s.nis', $search);
            $this->db->or_like('ss.nama_sesi', $search);
            $this->db->group_end();
        }

        if ($tahun_ajaran !== '' && $tahun_ajaran !== 'Semua') {
            $this->db->where('p.tahun_ajaran', $tahun_ajaran);
        }

        if ($id_kelas !== '' && $id_kelas !== 'Semua') {
            $this->db->where('p.id_kelas', (int) $id_kelas);
        }

        if ($id_sesi !== '' && $id_sesi !== 'Semua') {
            $this->db->where('p.id_sesi_soal', (int) $id_sesi);
        }

        $this->db->group_by('p.id_sesi_soal, p.id_siswa');

        if ($preview !== '' && $preview !== 'Semua') {
            $this->db->having('preview_diizinkan', $preview == '1' ? 1 : 0);
        }

        $rows = $this->db->order_by('updated_at', 'DESC')->get()->result_array();
        foreach ($rows as $key => $row) {
            $rows[$key]['nilai_format'] = $this->nilai_format($row['nilai_akhir']);
            $rows[$key]['preview_text'] = ((string) $row['preview_diizinkan'] === '1') ? 'Diizinkan' : 'Belum Diizinkan';
            $rows[$key]['row_key'] = (int) $row['id_sesi_soal'] . '-' . (int) $row['id_siswa'];
        }

        return ['result' => 'true', 'status' => true, 'message' => 'Data izin preview berhasil dimuat.', 'data' => $rows];
    }

    public function simpan()
    {
        $id_sesi = (int) $this->input->post('id_sesi_soal');
        $id_siswa = (int) $this->input->post('id_siswa');
        $preview = $this->post_text('preview_diizinkan') == '1' ? '1' : '0';

        if ($id_sesi <= 0 || $id_siswa <= 0) {
            return ['result' => 'false', 'status' => false, 'message' => 'Data siswa atau sesi tidak valid.'];
        }

        $this->db->trans_begin();
        $this->db->where('id_sesi_soal', $id_sesi);
        $this->db->where('id_siswa', $id_siswa);
        $this->db->update('siswa_pengerjaan', [
            'preview_diizinkan' => $preview,
            'updated_at' => date('d-m-Y H:i:s')
        ]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['result' => 'false', 'status' => false, 'message' => 'Izin preview gagal disimpan.'];
        }

        $this->db->trans_commit();
        return ['result' => 'true', 'status' => true, 'message' => 'Izin preview berhasil disimpan untuk semua jenis pengerjaan pada sesi ini.'];
    }
}
?>