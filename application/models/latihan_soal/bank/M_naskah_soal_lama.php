<?php
class M_naskah_soal extends CI_Model
{
    private function post_text($name)
    {
        return trim((string) $this->input->post($name, true));
    }

    private function status_value($status)
    {
        return ((string) $status === '0') ? '0' : '1';
    }

    public function dropdown()
    {
        $mapel = [];
        $kategori = [];

        if ($this->db->table_exists('mata_pelajaran')) {
            $mapel = $this->db
                ->select('id, nama_mata_pelajaran, status_aktif')
                ->from('mata_pelajaran')
                ->order_by('nama_mata_pelajaran', 'ASC')
                ->get()
                ->result_array();
        }

        if ($this->db->table_exists('soal_kategori')) {
            $kategori = $this->db
                ->select('id, nama_kategori_soal, status_aktif')
                ->from('soal_kategori')
                ->order_by('nama_kategori_soal', 'ASC')
                ->get()
                ->result_array();
        }

        return [
            'mapel' => $mapel,
            'kategori' => $kategori
        ];
    }

    public function naskah_soal_result()
    {
        return $this->result();
    }

    public function result()
    {
        $search = $this->post_text('search');
        $id_mapel = $this->post_text('id_mata_pelajaran');
        $id_kategori = $this->post_text('id_kategori_soal');
        $status = $this->post_text('status');

        $this->db->select('a.id, a.nama_naskah_soal, a.id_mata_pelajaran, a.id_kategori_soal, a.keterangan, a.status_aktif, a.created_at, a.updated_at');
        $this->db->select('b.nama_mata_pelajaran, c.nama_kategori_soal');
        $this->db->select('COUNT(d.id) AS jumlah_soal', false);
        $this->db->from('soal_naskah a');
        $this->db->join('mata_pelajaran b', 'a.id_mata_pelajaran = b.id', 'left');
        $this->db->join('soal_kategori c', 'a.id_kategori_soal = c.id', 'left');
        $this->db->join('soal d', 'd.id_naskah_soal = a.id AND d.status_hapus IS NULL', 'left');

        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('a.nama_naskah_soal', $search);
            $this->db->or_like('a.keterangan', $search);
            $this->db->or_like('b.nama_mata_pelajaran', $search);
            $this->db->or_like('c.nama_kategori_soal', $search);
            $this->db->group_end();
        }

        if ($id_mapel !== '' && $id_mapel !== 'Semua') {
            $this->db->where('a.id_mata_pelajaran', (int) $id_mapel);
        }

        if ($id_kategori !== '' && $id_kategori !== 'Semua') {
            $this->db->where('a.id_kategori_soal', (int) $id_kategori);
        }

        if ($status !== '' && $status !== 'Semua') {
            $this->db->where('a.status_aktif', $this->status_value($status));
        }

        $data = $this->db
            ->group_by('a.id')
            ->order_by('a.status_aktif', 'DESC')
            ->order_by('a.nama_naskah_soal', 'ASC')
            ->get()
            ->result_array();

        return [
            'status' => true,
            'result' => 'true',
            'message' => 'Data naskah soal berhasil dimuat.',
            'data' => $data
        ];
    }

    private function validate_naskah($id = 0)
    {
        $nama = strtoupper($this->post_text('nama_naskah_soal'));
        $id_mapel = (int) $this->input->post('id_mata_pelajaran');
        $id_kategori = (int) $this->input->post('id_kategori_soal');
        $keterangan = $this->post_text('keterangan');
        $status = $this->status_value($this->post_text('status_aktif'));

        if ($nama === '') {
            return ['error' => 'Nama naskah soal wajib diisi.'];
        }
        if ($id_mapel <= 0) {
            return ['error' => 'Mata pelajaran wajib dipilih.'];
        }
        if ($id_kategori <= 0) {
            return ['error' => 'Kategori soal wajib dipilih.'];
        }

        $mapel = $this->db->get_where('mata_pelajaran', ['id' => $id_mapel])->row_array();
        if (!$mapel) {
            return ['error' => 'Mata pelajaran tidak ditemukan.'];
        }

        $kategori = $this->db->get_where('soal_kategori', ['id' => $id_kategori])->row_array();
        if (!$kategori) {
            return ['error' => 'Kategori soal tidak ditemukan.'];
        }

        $this->db->where('LOWER(nama_naskah_soal)', strtolower($nama));
        if ($id > 0) {
            $this->db->where('id !=', $id);
        }
        $cek = $this->db->get('soal_naskah')->row_array();
        if ($cek) {
            return ['error' => 'Nama naskah soal sudah digunakan.'];
        }

        return [
            'data' => [
                'nama_naskah_soal' => $nama,
                'id_mata_pelajaran' => $id_mapel,
                'id_kategori_soal' => $id_kategori,
                'keterangan' => $keterangan,
                'status_aktif' => $status,
                'updated_at' => date('d-m-Y H:i:s')
            ]
        ];
    }

    public function tambah()
    {
        $valid = $this->validate_naskah();
        if (isset($valid['error'])) {
            return ['status' => false, 'result' => 'false', 'message' => $valid['error']];
        }

        $data = $valid['data'];
        $data['created_at'] = date('d-m-Y H:i:s');

        $this->db->trans_begin();
        $this->db->insert('soal_naskah', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['status' => false, 'result' => 'false', 'message' => 'Naskah soal gagal disimpan.'];
        }

        $this->db->trans_commit();
        return ['status' => true, 'result' => 'true', 'message' => 'Naskah soal berhasil disimpan.'];
    }

    public function edit()
    {
        $id = (int) $this->input->post('id_naskah_soal');
        if ($id <= 0) {
            return ['status' => false, 'result' => 'false', 'message' => 'ID naskah soal tidak valid.'];
        }

        $naskah = $this->db->get_where('soal_naskah', ['id' => $id])->row_array();
        if (!$naskah) {
            return ['status' => false, 'result' => 'false', 'message' => 'Naskah soal tidak ditemukan.'];
        }

        $valid = $this->validate_naskah($id);
        if (isset($valid['error'])) {
            return ['status' => false, 'result' => 'false', 'message' => $valid['error']];
        }

        $data = $valid['data'];

        $this->db->trans_begin();
        $this->db->where('id', $id);
        $this->db->update('soal_naskah', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['status' => false, 'result' => 'false', 'message' => 'Naskah soal gagal diupdate.'];
        }

        $this->db->trans_commit();
        return ['status' => true, 'result' => 'true', 'message' => 'Naskah soal berhasil diupdate.'];
    }

    public function ubah_status()
    {
        $id = (int) $this->input->post('id');
        $status = $this->status_value($this->post_text('status_aktif'));
        if ($id <= 0) {
            return ['status' => false, 'result' => 'false', 'message' => 'ID naskah soal tidak valid.'];
        }

        $this->db->trans_begin();
        $this->db->where('id', $id);
        $this->db->update('soal_naskah', [
            'status_aktif' => $status,
            'updated_at' => date('d-m-Y H:i:s')
        ]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['status' => false, 'result' => 'false', 'message' => 'Status naskah soal gagal diubah.'];
        }

        $this->db->trans_commit();
        return ['status' => true, 'result' => 'true', 'message' => 'Status naskah soal berhasil diubah.'];
    }
}
?>
