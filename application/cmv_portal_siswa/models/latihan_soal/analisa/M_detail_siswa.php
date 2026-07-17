<?php
class M_detail_siswa extends CI_Model
{
    private function get_text($name)
    {
        return trim((string) $this->input->get($name, true));
    }

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

    private function datetime_format($value)
    {
        if (empty($value) || $value == '0000-00-00 00:00:00') {
            return '-';
        }
        return date('d-m-Y H:i', strtotime($value));
    }

    private function date_format_only($value)
    {
        if (empty($value) || $value == '0000-00-00 00:00:00') {
            return '-';
        }
        return date('d-m-Y', strtotime($value));
    }

    private function durasi_format($detik, $menit = 0)
    {
        $detik = (int) $detik;
        if ($detik <= 0 && (int) $menit > 0) {
            $detik = ((int) $menit) * 60;
        }
        if ($detik <= 0) {
            return '-';
        }
        $jam = floor($detik / 3600);
        $menit = floor(($detik % 3600) / 60);
        $sisa = $detik % 60;
        return sprintf('%02d:%02d:%02d', $jam, $menit, $sisa);
    }

    private function decode_answer($json)
    {
        $json = trim((string) $json);
        if ($json === '') {
            return '-';
        }

        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $json;
        }

        if (is_array($data)) {
            $flat = [];
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $flat[] = implode(', ', $value);
                } else {
                    if (is_string($key) && !is_numeric($key)) {
                        $flat[] = $key . ': ' . $value;
                    } else {
                        $flat[] = $value;
                    }
                }
            }
            return implode(', ', $flat);
        }

        return (string) $data;
    }
    private function label_jawaban_text($id_soal, $json)
{
    $json = trim((string) $json);

    if ($json === '') {
        return '-';
    }

    $jawaban = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        $jawaban = $json;
    }

    if ($jawaban === null || $jawaban === '') {
        return '-';
    }

    $rows = $this->db->query("
        SELECT id, label_jawaban, isi_jawaban
        FROM soal_jawaban
        WHERE id_soal = ?
        ORDER BY urutan ASC, id ASC
    ", [(int) $id_soal])->result_array();

    $map = [];
    $map_pernyataan = [];

    foreach ($rows as $i => $row) {
        $id = (string) ($row['id'] ?? '');
        $label = (string) ($row['label_jawaban'] ?? '');
        $isi = (string) ($row['isi_jawaban'] ?? '');

        if ($label !== '') {
            $map[$label] = $label . '. ' . $isi;
        }

        if ($id !== '') {
            $map[$id] = ($label !== '' ? $label . '. ' : '') . $isi;
            $map_pernyataan[$id] = ($i + 1) . '. ' . $isi;
        }
    }

    if (is_array($jawaban)) {
        $out = [];

        foreach ($jawaban as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    $v = (string) $v;
                    $out[] = $map[$v] ?? $v;
                }
                continue;
            }

            $key_string = (string) $key;
            $value_string = (string) $value;

            if ($value_string === 'Benar' || $value_string === 'Salah') {
                $teks_pernyataan = $map_pernyataan[$key_string] ?? ($map[$key_string] ?? $key_string);
                $out[] = $teks_pernyataan . ' = ' . $value_string;
            } else {
                $out[] = $map[$value_string] ?? $value_string;
            }
        }

        return count($out) > 0 ? implode(', ', $out) : '-';
    }

    $jawaban_string = (string) $jawaban;

    return $map[$jawaban_string] ?? $jawaban_string;
}

    public function page_data($id_siswa = 0, $tahun_ajaran = '', $id_kelas = 0, $id_pengerjaan = 0)
    {
        // $id_siswa = (int) $id_siswa;
        // $tahun_ajaran = $this->get_text('tahun_ajaran');
        // $id_kelas = (int) $this->input->get('id_kelas');
        // $id_pengerjaan = (int) $this->input->get('id_pengerjaan');

        return [
            'id_siswa' => $id_siswa,
            'tahun_ajaran' => $tahun_ajaran,
            'id_kelas' => $id_kelas,
            'id_pengerjaan' => $id_pengerjaan
        ];
    }

    private function filter_input()
    {
        return [
            'id_siswa' => (int) $this->input->post('id_siswa'),
            'tahun_ajaran' => $this->post_text('tahun_ajaran'),
            'id_kelas' => (int) $this->input->post('id_kelas'),
            'id_pengerjaan' => (int) $this->input->post('id_pengerjaan'),
            'id_mata_pelajaran' => (int) $this->input->post('id_mata_pelajaran'),
            'jenis_pengerjaan' => $this->post_text('jenis_pengerjaan')
        ];
    }

    private function siswa_info($id_siswa, $id_kelas, $tahun_ajaran)
    {
        $this->db->select('s.id, s.nis, s.nama_siswa, s.id_kelas, k.nama_kelas');
        $this->db->from('siswa s');
        $this->db->join('kelas k', 's.id_kelas = k.id', 'left');
        $this->db->where('s.id', $id_siswa);
        $row = $this->db->get()->row_array();

        if (!$row) {
            return null;
        }

        if ($id_kelas <= 0) {
            $id_kelas = (int) $row['id_kelas'];
        }

        $kelas = $this->db->select('nama_kelas')->get_where('kelas', ['id' => $id_kelas])->row_array();
        $row['kelas_filter'] = $kelas ? $kelas['nama_kelas'] : $row['nama_kelas'];
        $row['tahun_ajaran'] = $tahun_ajaran;
        return $row;
    }

    private function base_pengerjaan_query($filter, $apply_mapel = true)
    {
        $this->db->from('siswa_pengerjaan p');
        $this->db->join('soal_sesi ss', 'p.id_sesi_soal = ss.id', 'left');
        $this->db->join('mata_pelajaran mp', 'ss.id_mata_pelajaran = mp.id', 'left');
        $this->db->join('soal_kategori ks', 'ss.id_kategori_soal = ks.id', 'left');
        $this->db->join('soal_naskah ns', 'ss.id_naskah_soal = ns.id', 'left');
        $this->db->where('p.id_siswa', $filter['id_siswa']);
        if ($filter['tahun_ajaran'] !== '') {
            $this->db->where('p.tahun_ajaran', $filter['tahun_ajaran']);
        }
        if ($filter['id_kelas'] > 0) {
            $this->db->where('p.id_kelas', $filter['id_kelas']);
        }
        if ($apply_mapel && isset($filter['id_mata_pelajaran']) && (int) $filter['id_mata_pelajaran'] > 0) {
            $this->db->where('ss.id_mata_pelajaran', (int) $filter['id_mata_pelajaran']);
        }
    }

    private function mapel_options($filter)
    {
        $this->db->select('mp.id AS id_mata_pelajaran, mp.nama_mata_pelajaran');
        $this->db->select('AVG(CASE WHEN p.status_pengerjaan IN (\'Selesai\',\'Waktu Habis\') THEN CAST(p.nilai_akhir AS DECIMAL(10,2)) ELSE NULL END) AS rata', false);
        $this->db->select('COUNT(p.id) AS jumlah_sesi', false);
        $this->base_pengerjaan_query($filter, false);
        $this->db->where('mp.id IS NOT NULL', null, false);
        $rows = $this->db
            ->group_by('mp.id')
            ->order_by('mp.nama_mata_pelajaran', 'ASC')
            ->get()
            ->result_array();

        foreach ($rows as $key => $row) {
            $rows[$key]['id_mata_pelajaran'] = (int) $row['id_mata_pelajaran'];
            $rows[$key]['rata_format'] = $this->nilai_format($row['rata']);
            $rows[$key]['jumlah_sesi'] = (int) $row['jumlah_sesi'];
        }

        return $rows;
    }

    private function mapel_by_pengerjaan($id_pengerjaan)
    {
        $id_pengerjaan = (int) $id_pengerjaan;
        if ($id_pengerjaan <= 0) {
            return 0;
        }

        $row = $this->db->query("
            SELECT ss.id_mata_pelajaran
            FROM siswa_pengerjaan p
            LEFT JOIN soal_sesi ss ON p.id_sesi_soal = ss.id
            WHERE p.id = ?
            LIMIT 1
        ", [$id_pengerjaan])->row_array();

        return $row ? (int) $row['id_mata_pelajaran'] : 0;
    }

    private function ringkasan($filter)
    {
        $this->db->select('COUNT(p.id) AS jumlah_sesi');
        $this->db->select('AVG(CASE WHEN p.status_pengerjaan IN (\'Selesai\',\'Waktu Habis\') THEN CAST(p.nilai_akhir AS DECIMAL(10,2)) ELSE NULL END) AS rata');
        $this->db->select('MAX(CASE WHEN p.status_pengerjaan IN (\'Selesai\',\'Waktu Habis\') THEN CAST(p.nilai_akhir AS DECIMAL(10,2)) ELSE NULL END) AS tertinggi');
        $this->db->select('MIN(CASE WHEN p.status_pengerjaan IN (\'Selesai\',\'Waktu Habis\') THEN CAST(p.nilai_akhir AS DECIMAL(10,2)) ELSE NULL END) AS terendah');
        $this->db->select('AVG(CASE WHEN p.jenis_pengerjaan = \'Bimbel\' AND p.status_pengerjaan IN (\'Selesai\',\'Waktu Habis\') THEN CAST(p.nilai_akhir AS DECIMAL(10,2)) ELSE NULL END) AS bimbel');
        $this->db->select('AVG(CASE WHEN p.jenis_pengerjaan = \'Rumah\' AND p.status_pengerjaan IN (\'Selesai\',\'Waktu Habis\') THEN CAST(p.nilai_akhir AS DECIMAL(10,2)) ELSE NULL END) AS rumah');
        $this->base_pengerjaan_query($filter, false);
        $row = $this->db->get()->row_array();

        $mapel = $this->mapel_options($filter);
        $mapel_aktif = null;
        foreach ($mapel as $m) {
            if ((int) $m['id_mata_pelajaran'] === (int) ($filter['id_mata_pelajaran'] ?? 0)) {
                $mapel_aktif = $m;
                break;
            }
        }

        return [
            'jumlah_sesi' => (int) ($row['jumlah_sesi'] ?? 0),
            'rata' => $this->nilai_format($row['rata'] ?? null),
            'tertinggi' => $this->nilai_format($row['tertinggi'] ?? null),
            'terendah' => $this->nilai_format($row['terendah'] ?? null),
            'bimbel' => $this->nilai_format($row['bimbel'] ?? null),
            'rumah' => $this->nilai_format($row['rumah'] ?? null),
            'mapel' => $mapel,
            'mapel_aktif' => $mapel_aktif
        ];
    }

    private function riwayat($filter)
    {
        $this->db->select('p.id, p.id_sesi_soal, p.nilai_akhir, p.status_pengerjaan, p.waktu_mulai, p.waktu_selesai, p.jenis_pengerjaan');
        $this->db->select('ss.nama_sesi, ss.tahun_ajaran, ss.id_mata_pelajaran, mp.nama_mata_pelajaran, ks.nama_kategori_soal');
        $this->base_pengerjaan_query($filter);

        if (!empty($filter['jenis_pengerjaan'])) {
            $this->db->where('p.jenis_pengerjaan', $filter['jenis_pengerjaan']);
        }

        $rows = $this->db->order_by('p.waktu_mulai', 'DESC')->get()->result_array();

        foreach ($rows as $key => $row) {
            $rows[$key]['tanggal'] = $this->date_format_only($row['waktu_selesai'] ?: $row['waktu_mulai']);
            $rows[$key]['nilai_format'] = $this->nilai_format($row['nilai_akhir']);
        }

        return $rows;
    }

    private function detail_sesi($id_pengerjaan)
    {
        if ($id_pengerjaan <= 0) {
            return null;
        }

        $this->db->select('p.*, ss.nama_sesi, ss.durasi_timer, ss.id_mata_pelajaran, mp.nama_mata_pelajaran, ks.nama_kategori_soal');
        $this->db->from('siswa_pengerjaan p');
        $this->db->join('soal_sesi ss', 'p.id_sesi_soal = ss.id', 'left');
        $this->db->join('mata_pelajaran mp', 'ss.id_mata_pelajaran = mp.id', 'left');
        $this->db->join('soal_kategori ks', 'ss.id_kategori_soal = ks.id', 'left');
        $this->db->where('p.id', $id_pengerjaan);
        $row = $this->db->get()->row_array();

        if (!$row) {
            return null;
        }

        $row['nilai_format'] = $this->nilai_format($row['nilai_akhir']);
        $row['waktu_mulai_format'] = $this->datetime_format($row['waktu_mulai']);
        $row['waktu_selesai_format'] = $this->datetime_format($row['waktu_selesai']);
        $row['durasi_format'] = $this->durasi_format($row['durasi_detik'], $row['durasi_menit']);
        return $row;
    }

    private function analisa_materi($id_pengerjaan)
    {
        if ($id_pengerjaan <= 0) {
            return ['kekuatan' => [], 'kelemahan' => [], 'semua' => []];
        }

        $pengerjaan = $this->db->select('jenis_pengerjaan')->get_where('siswa_pengerjaan', ['id' => $id_pengerjaan])->row_array();

        if (!$pengerjaan) {
            return ['kekuatan' => [], 'kelemahan' => [], 'semua' => []];
        }

        $tabel_jawaban = $pengerjaan['jenis_pengerjaan'] === 'Rumah' ? 'siswa_jawaban_rumah' : 'siswa_jawaban_bimbel';

        $this->db->select('m.id, m.nama_materi');
        $this->db->select('COUNT(j.id) AS total_soal', false);
        $this->db->select("SUM(CASE WHEN j.status_jawaban = 'Benar' THEN 1 ELSE 0 END) AS jumlah_benar", false);
        $this->db->select("
        AVG(
            CASE 
                WHEN CAST(s.bobot_nilai AS DECIMAL(10,2)) > 0 
                    THEN (CAST(j.nilai AS DECIMAL(10,2)) / CAST(s.bobot_nilai AS DECIMAL(10,2))) * 100
                ELSE 
                    CASE WHEN j.status_jawaban = 'Benar' THEN 100 ELSE 0 END
            END
        ) AS persen
    ", false);
        $this->db->from($tabel_jawaban . ' j');
        $this->db->join('soal s', 'j.id_soal = s.id', 'inner');
        $this->db->join('materi m', 's.id_materi = m.id', 'inner');
        $this->db->where('j.id_pengerjaan', $id_pengerjaan);

        $rows = $this->db->group_by('m.id')->order_by('persen', 'DESC')->get()->result_array();

        $semua = [];
        $kekuatan = [];
        $kelemahan = [];
        foreach ($rows as $row) {
            $item = [
                'nama_materi' => $row['nama_materi'],
                'jumlah_benar' => (int) $row['jumlah_benar'],
                'total_soal' => (int) $row['total_soal'],
                'persen' => (float) $row['persen'],
                'persen_format' => $this->nilai_format($row['persen'])
            ];

            $semua[] = $item;
            if ((float) $row['persen'] >= 80) {
                $kekuatan[] = $item;
            }

            if ((float) $row['persen'] < 70) {
                $kelemahan[] = $item;
            }
        }

        return [
            'kekuatan' => $kekuatan,
            'kelemahan' => $kelemahan,
            'semua' => $semua
        ];
    }

    private function preview_jawaban($id_pengerjaan)
    {
        if ($id_pengerjaan <= 0) {
            return [];
        }

        $pengerjaan = $this->db
            ->select('jenis_pengerjaan')
            ->get_where('siswa_pengerjaan', ['id' => $id_pengerjaan])
            ->row_array();

        if (!$pengerjaan) {
            return [];
        }

        $tabel_jawaban = $pengerjaan['jenis_pengerjaan'] === 'Rumah' ? 'siswa_jawaban_rumah' : 'siswa_jawaban_bimbel';
        $this->db->select('j.*, s.nomor_soal, s.pertanyaan, s.gambar_soal, s.pembahasan, s.tipe_soal, m.nama_materi');
        $this->db->from($tabel_jawaban . ' j');
        $this->db->join('soal s', 'j.id_soal = s.id', 'inner');
        $this->db->join('materi m', 's.id_materi = m.id', 'left');
        $this->db->where('j.id_pengerjaan', $id_pengerjaan);

        $rows = $this->db->order_by('CAST(s.nomor_soal AS UNSIGNED)', 'ASC', false)->order_by('s.id', 'ASC')->get()->result_array();

        foreach ($rows as $key => $row) {
            // $rows[$key]['jawaban_siswa_text'] = $this->decode_answer($row['jawaban_siswa']);
            // $rows[$key]['jawaban_benar_text'] = $this->decode_answer($row['jawaban_benar']);
            $rows[$key]['jawaban_siswa_text'] = $this->label_jawaban_text($row['id_soal'], $row['jawaban_siswa']);
            $rows[$key]['jawaban_benar_text'] = $this->label_jawaban_text($row['id_soal'], $row['jawaban_benar']);
            $rows[$key]['nilai_format'] = rtrim(rtrim(number_format((float) ($row['nilai'] ?? 0), 2, '.', ''), '0'), '.');
        }

        return $rows;
    }

    public function detail_result()
    {
        $filter = $this->filter_input();
        if ($filter['id_siswa'] <= 0) {
            return ['result' => 'false', 'status' => false, 'message' => 'Siswa tidak valid.'];
        }

        $siswa = $this->siswa_info($filter['id_siswa'], $filter['id_kelas'], $filter['tahun_ajaran']);
        if (!$siswa) {
            return ['result' => 'false', 'status' => false, 'message' => 'Data siswa tidak ditemukan.'];
        }

        $mapel_options = $this->mapel_options($filter);

        if ((int) $filter['id_mata_pelajaran'] <= 0 && (int) $filter['id_pengerjaan'] > 0) {
            $filter['id_mata_pelajaran'] = $this->mapel_by_pengerjaan($filter['id_pengerjaan']);
        }

        if ((int) $filter['id_mata_pelajaran'] <= 0 && !empty($mapel_options)) {
            $filter['id_mata_pelajaran'] = (int) $mapel_options[0]['id_mata_pelajaran'];
        }

        $riwayat = $this->riwayat($filter);
        $id_pengerjaan = $filter['id_pengerjaan'];

        if ($id_pengerjaan > 0) {
            $ada_di_riwayat = false;
            foreach ($riwayat as $row) {
                if ((int) $row['id'] === (int) $id_pengerjaan) {
                    $ada_di_riwayat = true;
                    break;
                }
            }

            if (!$ada_di_riwayat) {
                $id_pengerjaan = 0;
            }
        }

        if ($id_pengerjaan <= 0 && !empty($riwayat)) {
            $id_pengerjaan = (int) $riwayat[0]['id'];
        }

        return [
            'result' => 'true',
            'status' => true,
            'message' => 'Detail siswa berhasil dimuat.',
            'filter' => $filter,
            'siswa' => $siswa,
            'mapel_options' => $mapel_options,
            'ringkasan' => $this->ringkasan($filter),
            'riwayat' => $riwayat,
            'id_pengerjaan_aktif' => $id_pengerjaan,
            'detail_sesi' => $this->detail_sesi($id_pengerjaan),
            'materi' => $this->analisa_materi($id_pengerjaan),
            'preview' => $this->preview_jawaban($id_pengerjaan)
        ];
    }

}
?>