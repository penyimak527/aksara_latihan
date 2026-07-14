<?php
class M_kelola_soal extends CI_Model
{
    private function post_text($name)
    {
        return trim((string) $this->input->post($name, true));
    }

    private function status_value($status)
    {
        return ((string) $status === '0') ? '0' : '1';
    }

    private function tipe_soal_label($tipe)
    {
        if ($tipe === 'pg') {
            return 'Pilihan Ganda';
        }
        if ($tipe === 'pg_kompleks') {
            return 'Pilihan Ganda Kompleks';
        }
        if ($tipe === 'benar_salah') {
            return 'Benar / Salah';
        }
        return $tipe;
    }

    public function get_naskah($id)
    {
        return $this->db
            ->select('a.*, b.nama_mata_pelajaran, c.nama_kategori_soal, COUNT(d.id) AS jumlah_soal')
            ->from('soal_naskah a')
            ->join('mata_pelajaran b', 'a.id_mata_pelajaran = b.id', 'left')
            ->join('soal_kategori c', 'a.id_kategori_soal = c.id', 'left')
            ->join('soal d', 'd.id_naskah_soal = a.id AND d.status_hapus IS NULL', 'left')
            ->where('a.id', (int) $id)
            ->group_by('a.id')
            ->get()
            ->row_array();
    }

    public function materi_by_mapel()
    {
        $id_mapel = (int) $this->input->post('id_mata_pelajaran');
        if ($id_mapel <= 0) {
            return ['status' => true, 'result' => 'true', 'message' => 'Data materi kosong.', 'data' => []];
        }

        $data = $this->db
            ->select('id, nama_materi, id_mata_pelajaran, status_aktif')
            ->from('materi')
            ->where('id_mata_pelajaran', $id_mapel)
            ->where('status_aktif', '1')
            ->order_by('nama_materi', 'ASC')
            ->get()
            ->result_array();

        return [
            'status' => true,
            'result' => 'true',
            'message' => 'Data materi berhasil dimuat.',
            'data' => $data
        ];
    }

    public function result($id_naskah)
    {
        $nomor_dari = (int) $this->input->post('nomor_dari');
        $nomor_sampai = (int) $this->input->post('nomor_sampai');
        $this->db
            ->select('a.id, a.id_naskah_soal, a.id_materi, a.nomor_soal, a.tipe_soal, a.bobot_nilai, a.pertanyaan, a.gambar_soal, a.pembahasan, a.status_aktif, a.created_at, a.updated_at')
            ->select('b.nama_materi')
            ->from('soal a')
            ->join('materi b', 'a.id_materi = b.id', 'left')
            ->where('a.id_naskah_soal', (int) $id_naskah)
            ->where('a.status_hapus IS NULL', null, false);
        if ($nomor_dari > 0) {
            $this->db->where('CAST(a.nomor_soal AS UNSIGNED) >= ' . $nomor_dari, null, false);
        }

        if ($nomor_sampai > 0) {
            $this->db->where('CAST(a.nomor_soal AS UNSIGNED) <= ' . $nomor_sampai, null, false);
        }
        $data = $this->db->order_by('CAST(a.nomor_soal AS UNSIGNED)', 'ASC', false)->get()->result_array();

        foreach ($data as $key => $row) {
            $data[$key]['tipe_soal_label'] = $this->tipe_soal_label($row['tipe_soal']);
            $data[$key]['pertanyaan_singkat'] = substr(strip_tags((string) $row['pertanyaan']), 0, 150);
        }

        return [
            'status' => true,
            'result' => 'true',
            'message' => 'Data soal berhasil dimuat.',
            'data' => $data
        ];
    }

    private function upload_gambar_soal($old_file = '')
    {
        if (empty($_FILES['gambar_soal']['name'])) {
            return ['file' => $old_file];
        }

        $folder = 'storage/gambar_soal/';
        $dir = FCPATH . $folder;

        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        $config = [
            'upload_path' => $dir,
            'allowed_types' => 'jpg|jpeg|png|gif|webp',
            'max_size' => 3072,
            'encrypt_name' => true
        ];

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('gambar_soal')) {
            return ['error' => strip_tags($this->upload->display_errors())];
        }

        $upload = $this->upload->data();

        // hapus file lama, baik old_file berupa URL penuh maupun path relatif
        if ($old_file !== '') {
            $old_path = $this->ambil_path_file_lokal($old_file);

            if ($old_path !== '' && is_file(FCPATH . $old_path)) {
                @unlink(FCPATH . $old_path);
            }
        }

        // URL lengkap yang disimpan ke database
        $full_url = base_url($folder . $upload['file_name']);

        return ['file' => $full_url];
    }

    private function ambil_path_file_lokal($file)
    {
        if (empty($file)) {
            return '';
        }

        // Jika file berupa URL lengkap
        if (preg_match('/^https?:\/\//', $file)) {
            $base_url = base_url();

            // Jika URL masih dari domain aplikasi ini, ambil path relatifnya
            if (strpos($file, $base_url) === 0) {
                return ltrim(str_replace($base_url, '', $file), '/');
            }

            // Jika URL beda domain, jangan dihapus dari lokal
            return '';
        }

        // Jika file masih path relatif lama
        return ltrim($file, '/');
    }

    private function parse_jawaban($tipe_soal)
    {
        if ($tipe_soal === 'pg' || $tipe_soal === 'pg_kompleks') {
            $jawaban = $this->input->post('jawaban_teks');
            if (!is_array($jawaban)) {
                $jawaban = [];
            }

            $items = [];
            foreach ($jawaban as $key => $value) {
                $teks = trim((string) $value);
                if ($teks === '') {
                    continue;
                }
                $items[] = [
                    'index' => (string) $key,
                    'label_jawaban' => chr(65 + count($items)),
                    'isi_jawaban' => $teks,
                    'kunci_jawaban' => '0',
                    'urutan' => count($items) + 1
                ];
            }

            if (count($items) < 2) {
                return ['error' => 'Pilihan jawaban minimal 2.'];
            }

            if ($tipe_soal === 'pg') {
                $benar = $this->input->post('jawaban_benar_pg');
                $found = false;
                foreach ($items as $i => $item) {
                    if ((string) $item['index'] === (string) $benar) {
                        $items[$i]['kunci_jawaban'] = '1';
                        $found = true;
                    }
                    unset($items[$i]['index']);
                }
                if (!$found) {
                    return ['error' => 'Pilih satu jawaban benar untuk pilihan ganda.'];
                }
            } else {
                $benar = $this->input->post('jawaban_benar_kompleks');
                if (!is_array($benar)) {
                    $benar = [];
                }
                $total_benar = 0;
                foreach ($items as $i => $item) {
                    if (in_array((string) $item['index'], array_map('strval', $benar))) {
                        $items[$i]['kunci_jawaban'] = '1';
                        $total_benar++;
                    }
                    unset($items[$i]['index']);
                }
                if ($total_benar < 1) {
                    return ['error' => 'Pilih minimal satu jawaban benar untuk pilihan ganda kompleks.'];
                }
            }

            return ['data' => $items];
        }

        if ($tipe_soal === 'benar_salah') {
            $pernyataan = $this->input->post('pernyataan_teks');
            $kunci = $this->input->post('pernyataan_kunci');
            if (!is_array($pernyataan)) {
                $pernyataan = [];
            }
            if (!is_array($kunci)) {
                $kunci = [];
            }

            $items = [];
            foreach ($pernyataan as $key => $value) {
                $teks = trim((string) $value);
                if ($teks === '') {
                    continue;
                }
                $nilai = isset($kunci[$key]) && (string) $kunci[$key] === '1' ? '1' : '0';
                $items[] = [
                    'label_jawaban' => 'Pernyataan ' . (count($items) + 1),
                    'isi_jawaban' => $teks,
                    'kunci_jawaban' => $nilai,
                    'urutan' => count($items) + 1
                ];
            }

            if (count($items) < 1) {
                return ['error' => 'Pernyataan benar/salah minimal 1.'];
            }

            return ['data' => $items];
        }

        return ['error' => 'Tipe soal tidak valid.'];
    }

    private function validate_soal($id_naskah, $id_soal = 0)
    {
        $naskah = $this->get_naskah($id_naskah);
        if (!$naskah) {
            return ['error' => 'Naskah soal tidak ditemukan.'];
        }

        $nomor = (int) $this->input->post('nomor_soal');
        $id_materi = (int) $this->input->post('id_materi');
        $tipe = $this->post_text('tipe_soal');
        $bobot = (float) str_replace(',', '.', $this->post_text('bobot_nilai'));
        $pertanyaan = trim((string) $this->input->post('pertanyaan', false));
        $pembahasan = trim((string) $this->input->post('pembahasan', false));
        $status = $this->status_value($this->post_text('status_aktif'));

        if ($nomor <= 0) {
            return ['error' => 'Nomor soal wajib diisi dan harus lebih dari 0.'];
        }
        if ($id_materi <= 0) {
            return ['error' => 'Materi wajib dipilih.'];
        }
        if (!in_array($tipe, ['pg', 'pg_kompleks', 'benar_salah'])) {
            return ['error' => 'Tipe soal wajib dipilih.'];
        }
        if ($bobot <= 0) {
            return ['error' => 'Bobot nilai wajib lebih dari 0.'];
        }
        if ($pertanyaan === '') {
            return ['error' => 'Pertanyaan wajib diisi.'];
        }

        $materi = $this->db
            ->where('id', $id_materi)
            ->where('id_mata_pelajaran', (int) $naskah['id_mata_pelajaran'])
            ->get('materi')
            ->row_array();
        if (!$materi) {
            return ['error' => 'Materi tidak sesuai dengan mata pelajaran naskah soal.'];
        }

        $this->db->where('id_naskah_soal', (int) $id_naskah);
        $this->db->where('nomor_soal', $nomor);
        $this->db->where('status_hapus IS NULL', null, false);
        if ($id_soal > 0) {
            $this->db->where('id !=', $id_soal);
        }
        $cek_nomor = $this->db->get('soal')->row_array();
        if ($cek_nomor) {
            return ['error' => 'Nomor soal sudah digunakan pada naskah ini.'];
        }

        $jawaban = $this->parse_jawaban($tipe);
        if (isset($jawaban['error'])) {
            return ['error' => $jawaban['error']];
        }

        return [
            'data' => [
                'id_naskah_soal' => (int) $id_naskah,
                'id_materi' => $id_materi,
                'nomor_soal' => $nomor,
                'tipe_soal' => $tipe,
                'bobot_nilai' => $bobot,
                'pertanyaan' => $pertanyaan,
                'pembahasan' => $pembahasan,
                'status_aktif' => $status,
                'updated_at' => date('d-m-Y H:i:s')
            ],
            'jawaban' => $jawaban['data']
        ];
    }

    public function tambah($id_naskah)
    {
        $valid = $this->validate_soal($id_naskah);
        if (isset($valid['error'])) {
            return ['status' => false, 'result' => 'false', 'message' => $valid['error']];
        }

        $upload = $this->upload_gambar_soal();
        if (isset($upload['error'])) {
            return ['status' => false, 'result' => 'false', 'message' => $upload['error']];
        }

        $data = $valid['data'];
        $data['gambar_soal'] = $upload['file'];
        $data['created_at'] = date('d-m-Y H:i:s');

        $this->db->trans_begin();
        $this->db->insert('soal', $data);
        $id_soal = $this->db->insert_id();

        foreach ($valid['jawaban'] as $row) {
            $row['id_soal'] = $id_soal;
            $row['created_at'] = date('d-m-Y H:i:s');
            $row['updated_at'] = date('d-m-Y H:i:s');
            $this->db->insert('soal_jawaban', $row);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['status' => false, 'result' => 'false', 'message' => 'Soal gagal disimpan.'];
        }

        $this->db->trans_commit();
        return ['status' => true, 'result' => 'true', 'message' => 'Soal berhasil disimpan.'];
    }

    public function detail($id_soal)
    {
        $soal = $this->db
            ->select('a.*, b.nama_materi, c.nama_naskah_soal, c.id_mata_pelajaran, d.nama_mata_pelajaran, e.nama_kategori_soal')
            ->from('soal a')
            ->join('materi b', 'a.id_materi = b.id', 'left')
            ->join('soal_naskah c', 'a.id_naskah_soal = c.id', 'left')
            ->join('mata_pelajaran d', 'c.id_mata_pelajaran = d.id', 'left')
            ->join('soal_kategori e', 'c.id_kategori_soal = e.id', 'left')
            ->where('a.id', (int) $id_soal)
            ->where('a.status_hapus IS NULL', null, false)
            ->get()
            ->row_array();

        if (!$soal) {
            return ['status' => false, 'result' => 'false', 'message' => 'Soal tidak ditemukan.'];
        }

        $jawaban = $this->db
            ->select('id, label_jawaban, isi_jawaban, kunci_jawaban, urutan')
            ->from('soal_jawaban')
            ->where('id_soal', (int) $id_soal)
            ->order_by('urutan', 'ASC')
            ->get()
            ->result_array();

        $soal['tipe_soal_label'] = $this->tipe_soal_label($soal['tipe_soal']);
        $soal['jawaban'] = $jawaban;

        return [
            'status' => true,
            'result' => 'true',
            'message' => 'Detail soal berhasil dimuat.',
            'data' => $soal
        ];
    }

    public function edit($id_naskah)
    {
        $id_soal = (int) $this->input->post('id_soal');
        if ($id_soal <= 0) {
            return ['status' => false, 'result' => 'false', 'message' => 'ID soal tidak valid.'];
        }

        $soal_lama = $this->db
            ->where('id', $id_soal)
            ->where('id_naskah_soal', (int) $id_naskah)
            ->where('status_hapus IS NULL', null, false)
            ->get('soal')
            ->row_array();
        if (!$soal_lama) {
            return ['status' => false, 'result' => 'false', 'message' => 'Soal tidak ditemukan.'];
        }

        $valid = $this->validate_soal($id_naskah, $id_soal);
        if (isset($valid['error'])) {
            return ['status' => false, 'result' => 'false', 'message' => $valid['error']];
        }

        $upload = $this->upload_gambar_soal($soal_lama['gambar_soal']);
        if (isset($upload['error'])) {
            return ['status' => false, 'result' => 'false', 'message' => $upload['error']];
        }

        $data = $valid['data'];
        $data['gambar_soal'] = $upload['file'];

        $this->db->trans_begin();
        $this->db->where('id', $id_soal);
        $this->db->update('soal', $data);

        $this->db->where('id_soal', $id_soal);
        $this->db->delete('soal_jawaban');

        foreach ($valid['jawaban'] as $row) {
            $row['id_soal'] = $id_soal;
            $row['created_at'] = date('d-m-Y H:i:s');
            $row['updated_at'] = date('d-m-Y H:i:s');
            $this->db->insert('soal_jawaban', $row);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['status' => false, 'result' => 'false', 'message' => 'Soal gagal diupdate.'];
        }

        $this->db->trans_commit();
        return ['status' => true, 'result' => 'true', 'message' => 'Soal berhasil diupdate.'];
    }

    public function hapus($id_naskah)
    {
        $id_soal = (int) $this->input->post('id_soal');
        if ($id_soal <= 0) {
            return ['status' => false, 'result' => 'false', 'message' => 'ID soal tidak valid.'];
        }

        $this->db->trans_begin();
        $this->db->where('id', $id_soal);
        $this->db->where('id_naskah_soal', (int) $id_naskah);
        $this->db->update('soal', [
            'status_hapus' => date('d-m-Y H:i:s'),
            'updated_at' => date('d-m-Y H:i:s')
        ]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['status' => false, 'result' => 'false', 'message' => 'Soal gagal dihapus.'];
        }

        $this->db->trans_commit();
        return ['status' => true, 'result' => 'true', 'message' => 'Soal berhasil dihapus.'];
    }
}
?>
