<?php
class M_sesi_soal extends CI_Model
{
    private function post_text($name)
    {
        return trim((string) $this->input->post($name, true));
    }

    private function status_value($status)
    {
        return ((string) $status === '0') ? '0' : '1';
    }

    private function tanggal_db($tanggal)
    {
        $tanggal = trim((string) $tanggal);
        if ($tanggal === '') {
            return '';
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            return date('d-m-Y', strtotime($tanggal));
        }

        return $tanggal;
    }

    private function tanggal_input($tanggal)
    {
        $tanggal = trim((string) $tanggal);
        if ($tanggal === '') {
            return '';
        }

        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $tanggal)) {
            $parts = explode('-', $tanggal);
            return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }

        return $tanggal;
    }

    private function tanggal_timestamp($tanggal)
    {
        $tanggal = trim((string) $tanggal);
        if ($tanggal === '') {
            return false;
        }

        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $tanggal)) {
            $parts = explode('-', $tanggal);
            return strtotime($parts[2] . '-' . $parts[1] . '-' . $parts[0]);
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            return strtotime($tanggal);
        }

        return strtotime(str_replace('/', '-', $tanggal));
    }

    private function info_hapus_sesi($row)
    {
        $id_sesi = (int) ($row['id'] ?? 0);
        if ($id_sesi <= 0) {
            return ['bisa_hapus' => '0', 'alasan_hapus' => 'Sesi tidak valid.'];
        }

        $sudah_dikerjakan = 0;
        if ($this->db->table_exists('siswa_pengerjaan')) {
            $sudah_dikerjakan = (int) $this->db
                ->where('id_sesi_soal', $id_sesi)
                ->count_all_results('siswa_pengerjaan');
        }

        if ($sudah_dikerjakan > 0) {
            return ['bisa_hapus' => '0', 'alasan_hapus' => 'Sesi sudah pernah dikerjakan siswa.'];
        }

        $mulai = $this->tanggal_timestamp($row['tanggal_mulai'] ?? '');
        if ($mulai === false) {
            return ['bisa_hapus' => '0', 'alasan_hapus' => 'Tanggal mulai sesi tidak valid.'];
        }

        $hari_ini = strtotime(date('Y-m-d'));
        $hari_mulai = strtotime(date('Y-m-d', $mulai));
        if ($hari_ini >= $hari_mulai) {
            return ['bisa_hapus' => '0', 'alasan_hapus' => 'Sesi hanya bisa dihapus sebelum tanggal mulai.'];
        }

        return ['bisa_hapus' => '1', 'alasan_hapus' => 'Sesi masih bisa dihapus sebelum tanggal mulai.'];
    }

    private function only_time($jam)
    {
        $jam = trim((string) $jam);
        if ($jam === '') {
            return '';
        }
        return substr($jam, 0, 5);
    }

    private function safe_kelas_array($kelas)
    {
        if (!is_array($kelas)) {
            return [];
        }

        $result = [];
        foreach ($kelas as $id) {
            $id = (int) $id;
            if ($id > 0) {
                $result[] = $id;
            }
        }

        return array_values(array_unique($result));
    }

    private function tahun_ajaran_options()
    {
        $options = [];

        $currentYear = (int) date('Y');
        for ($i = -1; $i <= 3; $i++) {
            $tahunAwal = $currentYear + $i;
            $options[] = $tahunAwal . '/' . ($tahunAwal + 1);
        }

        if ($this->db->table_exists('soal_sesi')) {
            $rows = $this->db
                ->select('tahun_ajaran')
                ->from('soal_sesi')
                ->where('tahun_ajaran IS NOT NULL', null, false)
                ->where('tahun_ajaran !=', '')
                ->group_by('tahun_ajaran')
                ->order_by('tahun_ajaran', 'DESC')
                ->get()
                ->result_array();
            foreach ($rows as $row) {
                if (!empty($row['tahun_ajaran'])) {
                    $options[] = $row['tahun_ajaran'];
                }
            }
        }

        $options = array_values(array_unique($options));
        sort($options);
        return $options;
    }

    public function dropdown()
    {
        $kelas = [];
        $mapel = [];
        $kategori = [];
        $naskah = [];
        $guru = [];

        if ($this->db->table_exists('kelas')) {
            $kelas = $this->db
                ->select('k.id, k.nama_kelas, k.urutan_kelas, k.status_aktif, j.nama_jenjang')
                ->from('kelas k')
                ->join('jenjang j', 'k.id_jenjang = j.id')
                ->where('k.status_aktif', '1')
                // ->order_by('CAST(urutan_kelas AS UNSIGNED)', 'ASC', false)
                // ->order_by('nama_kelas', 'ASC')
                ->get()
                ->result_array();
        }

        if ($this->db->table_exists('mata_pelajaran')) {
            $mapel = $this->db
                ->select('id, nama_mata_pelajaran, status_aktif')
                ->from('mata_pelajaran')
                ->where('status_aktif', '1')
                ->order_by('nama_mata_pelajaran', 'ASC')
                ->get()
                ->result_array();
        }

        if ($this->db->table_exists('soal_kategori')) {
            $kategori = $this->db
                ->select('id, nama_kategori_soal, status_aktif')
                ->from('soal_kategori')
                ->where('status_aktif', '1')
                ->order_by('nama_kategori_soal', 'ASC')
                ->get()
                ->result_array();
        }

        if ($this->db->table_exists('soal_naskah')) {
            $naskah = $this->db
                ->select('a.id, a.nama_naskah_soal, a.id_mata_pelajaran, a.id_kategori_soal, a.status_aktif')
                ->select('COUNT(b.id) AS jumlah_soal', false)
                ->from('soal_naskah a')
                ->join('soal b', 'b.id_naskah_soal = a.id AND b.status_hapus IS NULL AND b.status_aktif = "1"', 'left')
                ->where('a.status_aktif', '1')
                ->group_by('a.id')
                ->order_by('a.nama_naskah_soal', 'ASC')
                ->get()
                ->result_array();
        }

        if ($this->db->table_exists('user')) {
            $this->db->select('a.id, a.id_pegawai, a.nama_user, a.level, a.id_level');
            if ($this->db->table_exists('pegawai')) {
                $this->db->select('COALESCE(b.nama_pegawai, a.nama_user) AS nama_guru', false);
                $this->db->join('pegawai b', 'a.id_pegawai = b.id', 'left');
            } else {
                $this->db->select('a.nama_user AS nama_guru', false);
            }
            $guru = $this->db
                ->from('user a')
                ->where_in('a.id_level', ['1', '2', '5'])
                ->order_by('nama_guru', 'ASC')
                ->get()
                ->result_array();
        }

        return [
            'tahun_ajaran' => $this->tahun_ajaran_options(),
            'kelas' => $kelas,
            'mapel' => $mapel,
            'kategori' => $kategori,
            'naskah' => $naskah,
            'guru' => $guru
        ];
    }

    public function naskah_by_filter()
    {
        $id_mapel = (int) $this->input->post('id_mata_pelajaran');
        $id_kategori = (int) $this->input->post('id_kategori_soal');

        if (!$this->db->table_exists('soal_naskah')) {
            return ['status' => false, 'result' => 'false', 'message' => 'Tabel naskah soal belum tersedia.', 'data' => []];
        }

        $this->db->select('a.id, a.nama_naskah_soal, a.id_mata_pelajaran, a.id_kategori_soal');
        $this->db->select('COUNT(b.id) AS jumlah_soal', false);
        $this->db->from('soal_naskah a');
        $this->db->join('soal b', 'b.id_naskah_soal = a.id AND b.status_hapus IS NULL AND b.status_aktif = "1"', 'left');
        $this->db->where('a.status_aktif', '1');
        if ($id_mapel > 0) {
            $this->db->where('a.id_mata_pelajaran', $id_mapel);
        }
        if ($id_kategori > 0) {
            $this->db->where('a.id_kategori_soal', $id_kategori);
        }
        $data = $this->db
            ->group_by('a.id')
            ->order_by('a.nama_naskah_soal', 'ASC')
            ->get()
            ->result_array();

        return ['status' => true, 'result' => 'true', 'data' => $data];
    }

    public function sesi_soal_result()
    {
        $search = $this->post_text('search');
        $tahun_ajaran = $this->post_text('tahun_ajaran');
        $id_kelas = $this->post_text('id_kelas');
        $id_mapel = $this->post_text('id_mata_pelajaran');
        $status = $this->post_text('status');

        $this->db->select('a.*');
        $this->db->select('b.nama_mata_pelajaran, c.nama_kategori_soal, d.nama_naskah_soal');
        $this->db->select('COALESCE(p.nama_pegawai, u.nama_user, "-") AS nama_guru', false);
        $this->db->select('COUNT(DISTINCT sk.id_kelas) AS jumlah_kelas', false);
        $this->db->select('GROUP_CONCAT(DISTINCT k.nama_kelas ORDER BY CAST(k.urutan_kelas AS UNSIGNED), k.nama_kelas SEPARATOR ", ") AS nama_kelas', false);
        $this->db->select('COUNT(DISTINCT s.id) AS jumlah_siswa', false);
        $this->db->select('COUNT(DISTINCT so.id) AS jumlah_soal', false);
        $this->db->from('soal_sesi a');
        $this->db->join('mata_pelajaran b', 'a.id_mata_pelajaran = b.id', 'left');
        $this->db->join('soal_kategori c', 'a.id_kategori_soal = c.id', 'left');
        $this->db->join('soal_naskah d', 'a.id_naskah_soal = d.id', 'left');
        $this->db->join('user u', 'a.id_guru_pengampu = u.id', 'left');
        if ($this->db->table_exists('pegawai')) {
            $this->db->join('pegawai p', 'u.id_pegawai = p.id', 'left');
        }
        $this->db->join('soal_sesi_kelas sk', 'a.id = sk.id_sesi_soal', 'left');
        $this->db->join('kelas k', 'sk.id_kelas = k.id', 'left');
        $this->db->join('siswa s', 's.id_kelas = sk.id_kelas AND s.status_aktif = "1"', 'left');
        $this->db->join('soal so', 'so.id_naskah_soal = a.id_naskah_soal AND so.status_hapus IS NULL AND so.status_aktif = "1"', 'left');
        $this->db->where('a.status_hapus IS NULL', null, false);

        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('a.nama_sesi', $search);
            $this->db->or_like('a.tahun_ajaran', $search);
            $this->db->or_like('b.nama_mata_pelajaran', $search);
            $this->db->or_like('c.nama_kategori_soal', $search);
            $this->db->or_like('d.nama_naskah_soal', $search);
            $this->db->group_end();
        }

        if ($tahun_ajaran !== '' && $tahun_ajaran !== 'Semua') {
            $this->db->where('a.tahun_ajaran', $tahun_ajaran);
        }
        if ($id_kelas !== '' && $id_kelas !== 'Semua') {
            $this->db->where('sk.id_kelas', (int) $id_kelas);
        }
        if ($id_mapel !== '' && $id_mapel !== 'Semua') {
            $this->db->where('a.id_mata_pelajaran', (int) $id_mapel);
        }
        if ($status !== '' && $status !== 'Semua') {
            $this->db->where('a.status_aktif', $this->status_value($status));
        }

        $data = $this->db
            ->group_by('a.id')
            ->order_by('a.id', 'DESC')
            ->get()
            ->result_array();

        foreach ($data as &$row) {
            $row['tanggal_mulai_input'] = $this->tanggal_input($row['tanggal_mulai']);
            $row['tanggal_selesai_input'] = $this->tanggal_input($row['tanggal_selesai']);
            $row['jam_mulai'] = $this->only_time($row['jam_mulai']);
            $row['jam_selesai'] = $this->only_time($row['jam_selesai']);
            $row['kelas_ids'] = $this->kelas_ids($row['id']);
            $info_hapus = $this->info_hapus_sesi($row);
            $row['bisa_hapus'] = $info_hapus['bisa_hapus'];
            $row['alasan_hapus'] = $info_hapus['alasan_hapus'];
        }
        unset($row);

        return ['status' => true, 'result' => 'true', 'message' => 'Data sesi soal berhasil dimuat.', 'data' => $data];
    }

    private function kelas_ids($id_sesi_soal)
    {
        if (!$this->db->table_exists('soal_sesi_kelas')) {
            return [];
        }

        $rows = $this->db
            ->select('id_kelas')
            ->from('soal_sesi_kelas')
            ->where('id_sesi_soal', (int) $id_sesi_soal)
            ->get()
            ->result_array();

        $ids = [];
        foreach ($rows as $row) {
            $ids[] = (string) $row['id_kelas'];
        }

        return $ids;
    }

    private function validate_sesi($id = 0)
    {
        $nama_sesi = strtoupper($this->post_text('nama_sesi'));
        $tahun_ajaran = $this->post_text('tahun_ajaran');
        $id_guru_pengampu = (int) $this->input->post('id_guru_pengampu');
        $id_mata_pelajaran = (int) $this->input->post('id_mata_pelajaran');
        $id_kategori_soal = (int) $this->input->post('id_kategori_soal');
        $id_naskah_soal = (int) $this->input->post('id_naskah_soal');
        $tanggal_mulai = $this->tanggal_db($this->post_text('tanggal_mulai'));
        $tanggal_selesai = $this->tanggal_db($this->post_text('tanggal_selesai'));
        $jam_mulai = $this->only_time($this->post_text('jam_mulai'));
        $jam_selesai = $this->only_time($this->post_text('jam_selesai'));
        $durasi_timer = (int) $this->input->post('durasi_timer');
        $status_aktif = $this->status_value($this->post_text('status_aktif'));
        $kelas = $this->safe_kelas_array($this->input->post('id_kelas'));

        if ($nama_sesi === '') {
            return ['error' => 'Nama sesi wajib diisi.'];
        }
        if ($tahun_ajaran === '') {
            return ['error' => 'Tahun ajaran wajib dipilih.'];
        }
        if ($id_guru_pengampu <= 0) {
            return ['error' => 'Guru pengampu wajib dipilih.'];
        }
        if ($id_mata_pelajaran <= 0) {
            return ['error' => 'Mata pelajaran wajib dipilih.'];
        }
        if ($id_kategori_soal <= 0) {
            return ['error' => 'Kategori soal wajib dipilih.'];
        }
        if ($id_naskah_soal <= 0) {
            return ['error' => 'Naskah soal wajib dipilih.'];
        }
        if (empty($kelas)) {
            return ['error' => 'Minimal pilih satu kelas.'];
        }
        if ($tanggal_mulai === '') {
            return ['error' => 'Tanggal mulai wajib diisi.'];
        }
        if ($tanggal_selesai === '') {
            return ['error' => 'Tanggal selesai wajib diisi.'];
        }
        if ($jam_mulai === '') {
            return ['error' => 'Jam mulai wajib diisi.'];
        }
        if ($jam_selesai === '') {
            return ['error' => 'Jam selesai wajib diisi.'];
        }
        if ($durasi_timer <= 0) {
            return ['error' => 'Durasi timer wajib diisi dan harus lebih dari 0 menit.'];
        }

        $naskah = $this->db->get_where('soal_naskah', ['id' => $id_naskah_soal])->row_array();
        if (!$naskah) {
            return ['error' => 'Naskah soal tidak ditemukan.'];
        }
        if ((int) $naskah['id_mata_pelajaran'] !== $id_mata_pelajaran || (int) $naskah['id_kategori_soal'] !== $id_kategori_soal) {
            return ['error' => 'Naskah soal tidak sesuai dengan mata pelajaran dan kategori soal yang dipilih.'];
        }

        $data = [
            'nama_sesi' => $nama_sesi,
            'tahun_ajaran' => $tahun_ajaran,
            'id_guru_pengampu' => $id_guru_pengampu,
            'id_mata_pelajaran' => $id_mata_pelajaran,
            'id_kategori_soal' => $id_kategori_soal,
            'id_naskah_soal' => $id_naskah_soal,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'jam_mulai' => $jam_mulai,
            'jam_selesai' => $jam_selesai,
            'durasi_timer' => $durasi_timer,
            'status_aktif' => $status_aktif,
            'updated_at' => date('d-m-Y H:i:s')
        ];

        return ['data' => $data, 'kelas' => $kelas];
    }

    public function tambah()
    {
        $valid = $this->validate_sesi();
        if (isset($valid['error'])) {
            return ['status' => false, 'result' => 'false', 'message' => $valid['error']];
        }

        $data = $valid['data'];
        $data['created_at'] = date('d-m-Y H:i:s');

        $this->db->trans_begin();
        $this->db->insert('soal_sesi', $data);
        $id_sesi = $this->db->insert_id();

        foreach ($valid['kelas'] as $id_kelas) {
            $this->db->insert('soal_sesi_kelas', [
                'id_sesi_soal' => $id_sesi,
                'id_kelas' => $id_kelas
            ]);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['status' => false, 'result' => 'false', 'message' => 'Sesi soal gagal disimpan.'];
        }

        $this->db->trans_commit();
        return ['status' => true, 'result' => 'true', 'message' => 'Sesi soal berhasil disimpan.'];
    }

    public function edit()
    {
        $id = (int) $this->input->post('id_sesi_soal');
        if ($id <= 0) {
            return ['status' => false, 'result' => 'false', 'message' => 'ID sesi soal tidak valid.'];
        }

        $cek = $this->db->get_where('soal_sesi', ['id' => $id])->row_array();
        if (!$cek) {
            return ['status' => false, 'result' => 'false', 'message' => 'Sesi soal tidak ditemukan.'];
        }

        $valid = $this->validate_sesi($id);
        if (isset($valid['error'])) {
            return ['status' => false, 'result' => 'false', 'message' => $valid['error']];
        }

        $this->db->trans_begin();
        $this->db->where('id', $id);
        $this->db->update('soal_sesi', $valid['data']);
        $this->db->delete('soal_sesi_kelas', ['id_sesi_soal' => $id]);
        foreach ($valid['kelas'] as $id_kelas) {
            $this->db->insert('soal_sesi_kelas', [
                'id_sesi_soal' => $id,
                'id_kelas' => $id_kelas
            ]);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['status' => false, 'result' => 'false', 'message' => 'Sesi soal gagal diupdate.'];
        }

        $this->db->trans_commit();
        return ['status' => true, 'result' => 'true', 'message' => 'Sesi soal berhasil diupdate.'];
    }

    public function detail()
    {
        $id = (int) $this->input->post('id');
        if ($id <= 0) {
            return ['status' => false, 'result' => 'false', 'message' => 'ID sesi soal tidak valid.'];
        }

        $this->input->post('search');
        $_POST['search'] = '';
        $_POST['tahun_ajaran'] = 'Semua';
        $_POST['id_kelas'] = 'Semua';
        $_POST['id_mata_pelajaran'] = 'Semua';
        $_POST['status'] = 'Semua';

        $res = $this->sesi_soal_result();
        if (!$res['status']) {
            return $res;
        }

        foreach ($res['data'] as $row) {
            if ((int) $row['id'] === $id) {
                return ['status' => true, 'result' => 'true', 'data' => $row];
            }
        }

        return ['status' => false, 'result' => 'false', 'message' => 'Sesi soal tidak ditemukan.'];
    }

    public function ubah_status()
    {
        $id = (int) $this->input->post('id');
        $status = $this->status_value($this->post_text('status_aktif'));
        if ($id <= 0) {
            return ['status' => false, 'result' => 'false', 'message' => 'ID sesi soal tidak valid.'];
        }

        $this->db->trans_begin();
        $this->db->where('id', $id);
        $this->db->update('soal_sesi', [
            'status_aktif' => $status,
            'updated_at' => date('d-m-Y H:i:s')
        ]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['status' => false, 'result' => 'false', 'message' => 'Status sesi soal gagal diubah.'];
        }

        $this->db->trans_commit();
        return ['status' => true, 'result' => 'true', 'message' => 'Status sesi soal berhasil diubah.'];
    }

    public function hapus()
    {
        $id = (int) $this->input->post('id');
        if ($id <= 0) {
            return ['status' => false, 'result' => 'false', 'message' => 'ID sesi soal tidak valid.'];
        }

        $sesi = $this->db
            ->where('id', $id)
            ->where('status_hapus IS NULL', null, false)
            ->get('soal_sesi')
            ->row_array();
        if (!$sesi) {
            return ['status' => false, 'result' => 'false', 'message' => 'Sesi soal tidak ditemukan.'];
        }

        $info_hapus = $this->info_hapus_sesi($sesi);
        if ($info_hapus['bisa_hapus'] !== '1') {
            return ['status' => false, 'result' => 'false', 'message' => $info_hapus['alasan_hapus']];
        }

        $this->db->trans_begin();
        $this->db->where('id', $id);
        $this->db->update('soal_sesi', [
            'status_hapus' => date('d-m-Y H:i:s'),
            'updated_at' => date('d-m-Y H:i:s')
        ]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['status' => false, 'result' => 'false', 'message' => 'Sesi soal gagal dihapus.'];
        }

        $this->db->trans_commit();
        return ['status' => true, 'result' => 'true', 'message' => 'Sesi soal berhasil dihapus.'];
    }
}
?>
