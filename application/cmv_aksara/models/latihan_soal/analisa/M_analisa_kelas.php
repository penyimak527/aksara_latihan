<?php
class M_analisa_kelas extends CI_Model
{
    private $status_selesai = ['Selesai', 'Waktu Habis', 'Selesai karena timer habis'];

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

    private function jenis_normalize($jenis)
    {
        $jenis = trim((string) $jenis);
        if (!in_array($jenis, ['Bimbel', 'Rumah', 'Semua'], true)) {
            return 'Bimbel';
        }
        return $jenis;
    }

    private function is_completed($status)
    {
        return in_array((string) $status, $this->status_selesai, true);
    }

    private function completed_status_where($alias = 'p')
    {
        $this->db->where_in($alias . '.status_pengerjaan', $this->status_selesai);
    }

    private function completed_status_sql($alias = 'p')
    {
        $escaped = [];
        foreach ($this->status_selesai as $status) {
            $escaped[] = $this->db->escape($status);
        }
        return $alias . '.status_pengerjaan IN (' . implode(',', $escaped) . ')';
    }

    private function active_siswa_where($alias = 's')
    {
        $this->db->where('(' . $alias . ".status_aktif = '1' OR " . $alias . ".status_aktif IS NULL OR " . $alias . ".status_aktif = '')", null, false);
    }

    private function source_condition_sql($jenis, $alias = 'p')
    {
        $jenis = $this->jenis_normalize($jenis);

        // Sumber jenis pengerjaan yang benar ada pada header siswa_pengerjaan.
        // soal_sesi tetap tidak memiliki jenis pengerjaan, sedangkan tabel jawaban hanya untuk detail jawaban/nilai per soal.
        if ($jenis === 'Semua') {
            return $alias . ".jenis_pengerjaan IN ('Bimbel', 'Rumah')";
        }

        return $alias . '.jenis_pengerjaan = ' . $this->db->escape($jenis);
    }

    private function jawaban_source_sql($jenis)
    {
        $jenis = $this->jenis_normalize($jenis);
        $selects = [];

        if (($jenis === 'Bimbel' || $jenis === 'Semua') && $this->db->table_exists('siswa_jawaban_bimbel')) {
            $selects[] = "SELECT id_pengerjaan, id_soal, nilai, 'Bimbel' AS jenis_pengerjaan FROM siswa_jawaban_bimbel";
        }

        if (($jenis === 'Rumah' || $jenis === 'Semua') && $this->db->table_exists('siswa_jawaban_rumah')) {
            $selects[] = "SELECT id_pengerjaan, id_soal, nilai, 'Rumah' AS jenis_pengerjaan FROM siswa_jawaban_rumah";
        }

        if (empty($selects)) {
            return "(SELECT 0 AS id_pengerjaan, 0 AS id_soal, 0 AS nilai, '' AS jenis_pengerjaan WHERE 1 = 0)";
        }

        return '(' . implode(' UNION ALL ', $selects) . ')';
    }

    private function select_pengerjaan_flags()
    {
        $this->db->select('p.jenis_pengerjaan');
    }

    private function resolve_jenis_pengerjaan($row)
    {
        $jenis = trim((string) ($row['jenis_pengerjaan'] ?? ''));
        if (in_array($jenis, ['Bimbel', 'Rumah'], true)) {
            return $jenis;
        }

        return 'Bimbel';
    }

    public function dropdown()
    {
        $tahun = [];
        $kelas = [];
        $mapel = [];
        $kategori = [];

        if ($this->db->table_exists('soal_sesi')) {
            $tahun = $this->db
                ->select('tahun_ajaran')
                ->from('soal_sesi')
                ->where('tahun_ajaran !=', '')
                ->group_by('tahun_ajaran')
                ->order_by('tahun_ajaran', 'DESC')
                ->get()
                ->result_array();
        }

        if ($this->db->table_exists('kelas')) {
            $kelas = $this->db
                ->select('k.id, k.nama_kelas, k.status_aktif, j.nama_jenjang')
                ->from('kelas k')
                ->join('jenjang j', 'k.id_jenjang = j.id')
                ->where("(k.status_aktif = '1' OR k.status_aktif IS NULL OR k.status_aktif = '')", null, false)
                // ->order_by('CAST(k.urutan_kelas AS UNSIGNED)', 'ASC', false)
                // ->order_by('k.nama_kelas', 'ASC')
                ->get()
                ->result_array();
        }

        if ($this->db->table_exists('mata_pelajaran')) {
            $mapel = $this->db
                ->select('id, nama_mata_pelajaran')
                ->from('mata_pelajaran')
                ->where('status_aktif', '1')
                ->order_by('nama_mata_pelajaran', 'ASC')
                ->get()
                ->result_array();
        }

        if ($this->db->table_exists('soal_kategori')) {
            $kategori = $this->db
                ->select('id, nama_kategori_soal')
                ->from('soal_kategori')
                ->where('status_aktif', '1')
                ->order_by('nama_kategori_soal', 'ASC')
                ->get()
                ->result_array();
        }

        return [
            'tahun' => $tahun,
            'kelas' => $kelas,
            'mapel' => $mapel,
            'kategori' => $kategori
        ];
    }

    private function filter_input()
    {
        return [
            'tahun_ajaran' => $this->post_text('tahun_ajaran'),
            'id_kelas' => (int) $this->input->post('id_kelas'),
            'id_mata_pelajaran' => $this->post_text('id_mata_pelajaran'),
            'jenis_pengerjaan' => $this->jenis_normalize($this->post_text('jenis_pengerjaan')),
            'id_kategori_soal' => $this->post_text('id_kategori_soal'),
            'search_siswa' => $this->post_text('search_siswa')
        ];
    }

    private function get_sesi_rows($filter, $apply_filter_analisis = true)
    {
        $this->db->select('a.id, a.nama_sesi, a.id_mata_pelajaran, a.id_kategori_soal, a.tanggal_mulai, a.jam_mulai');
        $this->db->from('soal_sesi a');
        $this->db->join('soal_sesi_kelas b', 'a.id = b.id_sesi_soal', 'inner');
        $this->db->where('a.status_hapus IS NULL', null, false);
        $this->db->where('a.tahun_ajaran', $filter['tahun_ajaran']);
        $this->db->where('b.id_kelas', $filter['id_kelas']);

        if ($apply_filter_analisis) {
            if ($filter['id_mata_pelajaran'] !== '' && $filter['id_mata_pelajaran'] !== 'Semua') {
                $this->db->where('a.id_mata_pelajaran', (int) $filter['id_mata_pelajaran']);
            }

            // Tidak ada filter jenis pengerjaan di soal_sesi.
            // Jenis Bimbel/Rumah dipakai saat membaca hasil/jawaban siswa.

            if ($filter['id_kategori_soal'] !== '' && $filter['id_kategori_soal'] !== 'Semua') {
                $this->db->where('a.id_kategori_soal', (int) $filter['id_kategori_soal']);
            }
        }

        $rows = $this->db
            ->group_by('a.id')
            ->order_by("STR_TO_DATE(a.tanggal_mulai, '%d-%m-%Y')", 'ASC', false)
            ->order_by('a.jam_mulai', 'ASC')
            ->order_by('a.id', 'ASC')
            ->get()
            ->result_array();

        foreach ($rows as $key => $row) {
            $rows[$key]['id'] = (int) $row['id'];
            $rows[$key]['nama_sesi'] = $row['nama_sesi'] ?: ('Sesi #' . $row['id']);
        }

        return $rows;
    }

    private function sesi_ids($sesi_rows)
    {
        $ids = [];
        foreach ($sesi_rows as $row) {
            $ids[] = (int) $row['id'];
        }
        return array_values(array_unique($ids));
    }

    private function siswa_rows($filter, $with_search = false)
    {
        $this->db->select('s.id, s.nis, s.nama_siswa');
        $this->db->from('siswa s');
        $this->db->where('s.id_kelas', $filter['id_kelas']);
        $this->active_siswa_where('s');

        if ($with_search && $filter['search_siswa'] !== '') {
            $this->db->group_start();
            $this->db->like('s.nama_siswa', $filter['search_siswa']);
            $this->db->or_like('s.nis', $filter['search_siswa']);
            $this->db->group_end();
        }

        return $this->db
            ->order_by('s.nama_siswa', 'ASC')
            ->get()
            ->result_array();
    }

    private function jumlah_siswa_kelas($id_kelas)
    {
        $this->db->from('siswa s');
        $this->db->where('s.id_kelas', $id_kelas);
        $this->active_siswa_where('s');
        return (int) $this->db->count_all_results();
    }

    private function nama_kelas($id_kelas)
    {
        $row = $this->db->select('nama_kelas')->get_where('kelas', ['id' => $id_kelas])->row_array();
        return $row ? $row['nama_kelas'] : '-';
    }

    private function pengerjaan_map($filter, $sesi_ids, $jenis = 'Semua', $id_siswa = 0)
    {
        $map = [];
        $jenis = $this->jenis_normalize($jenis);
        if (empty($sesi_ids)) {
            return $map;
        }

        $this->db->select('p.id, p.id_siswa, p.id_sesi_soal, p.nilai_akhir, p.status_pengerjaan, p.waktu_mulai, p.waktu_selesai, p.reset_jawaban');
        $this->select_pengerjaan_flags();
        $this->db->from('siswa_pengerjaan p');
        $this->db->where('p.id_kelas', $filter['id_kelas']);
        $this->db->where_in('p.id_sesi_soal', $sesi_ids);
        if ($id_siswa > 0) {
            $this->db->where('p.id_siswa', $id_siswa);
        }

        $source_sql = $this->source_condition_sql($jenis, 'p');
        if ($source_sql !== '') {
            $this->db->where($source_sql, null, false);
        }

        $rows = $this->db->order_by('p.id', 'ASC')->get()->result_array();

        foreach ($rows as $row) {
            $id_siswa_row = (int) $row['id_siswa'];
            $id_sesi = (int) $row['id_sesi_soal'];
            $jenis_row = $this->resolve_jenis_pengerjaan($row);

            if ($jenis !== 'Semua' && $jenis_row !== $jenis) {
                continue;
            }

            if (!isset($map[$id_siswa_row])) {
                $map[$id_siswa_row] = [];
            }
            if (!isset($map[$id_siswa_row][$jenis_row])) {
                $map[$id_siswa_row][$jenis_row] = [];
            }

            $lama = $map[$id_siswa_row][$jenis_row][$id_sesi] ?? null;
            if ($lama === null) {
                $row['jenis_pengerjaan_resolved'] = $jenis_row;
                $map[$id_siswa_row][$jenis_row][$id_sesi] = $row;
                continue;
            }

            $lama_selesai = $this->is_completed($lama['status_pengerjaan'] ?? '');
            $baru_selesai = $this->is_completed($row['status_pengerjaan'] ?? '');

            if ($lama_selesai && !$baru_selesai) {
                continue;
            }

            if ((!$lama_selesai && $baru_selesai) || ((int) $row['id'] > (int) $lama['id'])) {
                $row['jenis_pengerjaan_resolved'] = $jenis_row;
                $map[$id_siswa_row][$jenis_row][$id_sesi] = $row;
            }
        }

        return $map;
    }

    private function nilai_sesi_siswa($id_siswa, $sesi_rows, $map, $jenis = 'Bimbel')
    {
        $jenis = $this->jenis_normalize($jenis);
        $target = 0;
        $done = 0;
        $total = 0;
        $missing = [];
        $jenis_target = ($jenis === 'Semua') ? ['Bimbel', 'Rumah'] : [$jenis];

        foreach ($sesi_rows as $sesi) {
            foreach ($jenis_target as $jenis_item) {
                $target++;
                $id_sesi = (int) $sesi['id'];
                $row = $map[$id_siswa][$jenis_item][$id_sesi] ?? null;

                if ($row && $this->is_completed($row['status_pengerjaan'] ?? '')) {
                    $done++;
                    $total += (float) $row['nilai_akhir'];
                } else {
                    $missing[] = [
                        'nama_sesi' => $sesi['nama_sesi'],
                        'jenis_pengerjaan' => $jenis_item
                    ];
                }
            }
        }

        return [
            'target' => $target,
            'done' => $done,
            'total' => $total,
            'rata' => $target > 0 ? ($total / $target) : null,
            'missing' => $missing
        ];
    }

    private function latest_date_from_map($id_siswa, $map, $jenis = 'Semua')
    {
        $jenis = $this->jenis_normalize($jenis);
        $jenis_target = ($jenis === 'Semua') ? ['Bimbel', 'Rumah'] : [$jenis];
        $latest = '';

        foreach ($jenis_target as $jenis_item) {
            if (empty($map[$id_siswa][$jenis_item])) {
                continue;
            }

            foreach ($map[$id_siswa][$jenis_item] as $row) {
                if (!$this->is_completed($row['status_pengerjaan'] ?? '')) {
                    continue;
                }
                $tanggal = $row['waktu_selesai'] ?: $row['waktu_mulai'];
                if ($tanggal !== '' && ($latest === '' || strtotime($tanggal) > strtotime($latest))) {
                    $latest = $tanggal;
                }
            }
        }

        return $latest !== '' ? date('d-m-Y', strtotime($latest)) : '-';
    }

    private function status_siswa($id_siswa, $target, $done, $map, $jenis = 'Bimbel')
    {
        $jenis = $this->jenis_normalize($jenis);
        $jenis_target = ($jenis === 'Semua') ? ['Bimbel', 'Rumah'] : [$jenis];
        $sedang = 0;
        $hapus_jawaban = 0;
        $timer_habis = 0;

        foreach ($jenis_target as $jenis_item) {
            if (empty($map[$id_siswa][$jenis_item])) {
                continue;
            }
            foreach ($map[$id_siswa][$jenis_item] as $row) {
                if (($row['status_pengerjaan'] ?? '') === 'Proses') {
                    $sedang++;
                }
                if ((int) ($row['reset_jawaban'] ?? 0) > 0) {
                    $hapus_jawaban++;
                }
                if (in_array(($row['status_pengerjaan'] ?? ''), ['Waktu Habis', 'Selesai karena timer habis'], true)) {
                    $timer_habis++;
                }
            }
        }

        if ($sedang > 0) {
            return 'Sedang Mengerjakan';
        }
        if ($hapus_jawaban > 0) {
            return 'Jawaban pernah dihapus karena keluar halaman';
        }
        if ($timer_habis > 0 && $done > 0) {
            return 'Selesai karena timer habis';
        }
        if ($target > 0 && $done >= $target) {
            return 'Lengkap';
        }
        if ($done > 0) {
            return 'Belum Lengkap';
        }
        return 'Belum Mengerjakan';
    }

    private function tooltip_sesi($missing)
    {
        if (empty($missing)) {
            return 'Semua sesi sudah dikerjakan';
        }

        $list = [];
        foreach ($missing as $sesi) {
            $jenis = isset($sesi['jenis_pengerjaan']) ? ' (' . $sesi['jenis_pengerjaan'] . ')' : '';
            $list[] = '- ' . $sesi['nama_sesi'] . $jenis;
        }

        return "Belum dikerjakan:\n" . implode("\n", $list);
    }

    private function ringkasan($filter, $sesi_rows)
    {
        $jumlah_siswa = $this->jumlah_siswa_kelas($filter['id_kelas']);
        $jumlah_sesi = count($sesi_rows);

        $data = [
            'kelas' => $this->nama_kelas($filter['id_kelas']),
            'tahun_ajaran' => $filter['tahun_ajaran'],
            'jumlah_siswa' => $jumlah_siswa,
            'jumlah_sesi' => $jumlah_sesi,
            'rata_rata_kelas' => '-',
            'nilai_tertinggi' => '-',
            'nilai_terendah' => '-',
            'sudah_mengerjakan' => 0,
            'belum_mengerjakan' => $jumlah_siswa,
            'rata_rata_bimbel' => '-',
            'rata_rata_rumah' => '-'
        ];

        if ($jumlah_siswa <= 0 || $jumlah_sesi <= 0) {
            return $data;
        }

        $siswa = $this->siswa_rows($filter, false);
        $map = $this->pengerjaan_map($filter, $this->sesi_ids($sesi_rows), 'Semua');

        $total_rata_bimbel = 0;
        $total_rata_rumah = 0;
        $total_rata_semua = 0;
        $nilai_siswa = [];
        $lengkap_bimbel = 0;

        foreach ($siswa as $row) {
            $id_siswa = (int) $row['id'];
            $nilai_bimbel = $this->nilai_sesi_siswa($id_siswa, $sesi_rows, $map, 'Bimbel');
            $nilai_rumah = $this->nilai_sesi_siswa($id_siswa, $sesi_rows, $map, 'Rumah');
            $nilai_semua = $this->nilai_sesi_siswa($id_siswa, $sesi_rows, $map, 'Semua');

            $rata_bimbel = (float) ($nilai_bimbel['rata'] ?? 0);
            $rata_rumah = (float) ($nilai_rumah['rata'] ?? 0);
            $rata_semua = (float) ($nilai_semua['rata'] ?? 0);

            $total_rata_bimbel += $rata_bimbel;
            $total_rata_rumah += $rata_rumah;
            $total_rata_semua += $rata_semua;
            $nilai_siswa[] = $rata_semua;

            // Ringkasan progress utama mengikuti pengerjaan pertama/Bimbel.
            if ($nilai_bimbel['target'] > 0 && $nilai_bimbel['done'] >= $nilai_bimbel['target']) {
                $lengkap_bimbel++;
            }
        }

        $pembagi = max(1, count($siswa));
        $data['rata_rata_kelas'] = $this->nilai_format($total_rata_semua / $pembagi);
        $data['nilai_tertinggi'] = !empty($nilai_siswa) ? $this->nilai_format(max($nilai_siswa)) : '-';
        $data['nilai_terendah'] = !empty($nilai_siswa) ? $this->nilai_format(min($nilai_siswa)) : '-';
        $data['sudah_mengerjakan'] = $lengkap_bimbel;
        $data['belum_mengerjakan'] = max(0, $jumlah_siswa - $lengkap_bimbel);
        $data['rata_rata_bimbel'] = $this->nilai_format($total_rata_bimbel / $pembagi);
        $data['rata_rata_rumah'] = $this->nilai_format($total_rata_rumah / $pembagi);

        return $data;
    }

    private function peringkat($filter, $sesi_rows)
    {
        if (empty($sesi_rows)) {
            return [];
        }

        $jenis = $this->jenis_normalize($filter['jenis_pengerjaan']);
        $siswa = $this->siswa_rows($filter, false);

        // Penting: ambil semua data pengerjaan, tetapi nilai rata-rata TIDAK memakai AVG database.
        // Ranking harus fair: semua sesi wajib pada filter analisis menjadi pembagi.
        // Sesi yang belum dikerjakan siswa otomatis bernilai 0.
        $map = $this->pengerjaan_map($filter, $this->sesi_ids($sesi_rows), 'Semua');
        $rows = [];

        foreach ($siswa as $row) {
            $id_siswa = (int) $row['id'];
            $nilai = $this->nilai_sesi_siswa($id_siswa, $sesi_rows, $map, $jenis);
            $target = (int) ($nilai['target'] ?? 0);
            $done = (int) ($nilai['done'] ?? 0);
            $total_nilai = (float) ($nilai['total'] ?? 0);
            $rata_fair = $target > 0 ? ($total_nilai / $target) : 0;

            $rows[] = [
                'id' => $id_siswa,
                'nama_siswa' => $row['nama_siswa'],
                'rata' => $rata_fair,
                'rata_format' => $this->nilai_format($rata_fair),
                'sesi_dikerjakan' => $done,
                'sesi_target' => $target,
                'total_nilai' => $total_nilai
            ];
        }

        usort($rows, function ($a, $b) {
            if ((float) $a['rata'] == (float) $b['rata']) {
                if ((int) $a['sesi_dikerjakan'] == (int) $b['sesi_dikerjakan']) {
                    return strcmp($a['nama_siswa'], $b['nama_siswa']);
                }
                return ((int) $a['sesi_dikerjakan'] < (int) $b['sesi_dikerjakan']) ? 1 : -1;
            }
            return ((float) $a['rata'] < (float) $b['rata']) ? 1 : -1;
        });

        return array_slice($rows, 0, 10);
    }

    private function analisa_materi($filter, $sesi_ids)
    {
        if (empty($sesi_ids)) {
            return [
                'mapel' => []
            ];
        }

        $jenis = $this->jenis_normalize($filter['jenis_pengerjaan']);

        $this->db->select('ss.id_mata_pelajaran, mp.nama_mata_pelajaran');
        $this->db->from('soal_sesi ss');
        $this->db->join('mata_pelajaran mp', 'ss.id_mata_pelajaran = mp.id', 'left');
        $this->db->where_in('ss.id', $sesi_ids);
        $mapel_rows = $this->db
            ->group_by('ss.id_mata_pelajaran')
            ->order_by('mp.nama_mata_pelajaran', 'ASC')
            ->get()
            ->result_array();

        $hasil_mapel = [];
        $completed_sql = $this->completed_status_sql('p');
        $source_sql = $this->source_condition_sql($jenis, 'p');
        $jawaban_sql = $this->jawaban_source_sql($jenis);

        foreach ($mapel_rows as $mapel) {
            $id_mapel = (int) ($mapel['id_mata_pelajaran'] ?? 0);

            $this->db->select('m.id, m.nama_materi');
            $this->db->select('COUNT(DISTINCT s.id) AS total_soal', false);
            $this->db->select('COUNT(DISTINCT p.id) AS total_pengerjaan', false);
            $this->db->select("\n                ROUND(\n                    (\n                        SUM(COALESCE(CAST(j.nilai AS DECIMAL(10,2)), 0)) /\n                        NULLIF(SUM(CASE WHEN p.id IS NOT NULL THEN CAST(s.bobot_nilai AS DECIMAL(10,2)) ELSE 0 END), 0)\n                    ) * 100, 2\n                ) AS persen\n            ", false);
            $this->db->from('soal_sesi ss');
            $this->db->join('soal s', 's.id_naskah_soal = ss.id_naskah_soal AND s.status_hapus IS NULL', 'inner');
            $this->db->join('materi m', 's.id_materi = m.id', 'left');
            $join_pengerjaan = 'p.id_sesi_soal = ss.id AND p.id_kelas = ' . (int) $filter['id_kelas'] . ' AND ' . $completed_sql;
            if ($source_sql !== '') {
                $join_pengerjaan .= ' AND ' . $source_sql;
            }
            $this->db->join('siswa_pengerjaan p', $join_pengerjaan, 'left');
            $this->db->join($jawaban_sql . ' j', 'j.id_pengerjaan = p.id AND j.id_soal = s.id AND j.jenis_pengerjaan = p.jenis_pengerjaan', 'left', false);

            $this->db->where_in('ss.id', $sesi_ids);
            $this->db->where('ss.id_mata_pelajaran', $id_mapel);

            $materi_rows = $this->db
                ->group_by('m.id')
                ->order_by('persen', 'ASC')
                ->order_by('m.nama_materi', 'ASC')
                ->get()
                ->result_array();

            foreach ($materi_rows as $key => $row) {
                $persen = ($row['persen'] === null || $row['persen'] === '') ? 0 : (float) $row['persen'];
                $materi_rows[$key]['persen'] = $persen;
                $materi_rows[$key]['persen_format'] = $this->nilai_format($persen);
                $materi_rows[$key]['nama_materi'] = $row['nama_materi'] ?: '-';
            }

            $hasil_mapel[] = [
                'id_mata_pelajaran' => $id_mapel,
                'nama_mata_pelajaran' => $mapel['nama_mata_pelajaran'] ?: 'Mata Pelajaran',
                'materi' => $materi_rows
            ];
        }

        return [
            'mapel' => $hasil_mapel
        ];
    }

    private function daftar_siswa($filter, $sesi_rows)
    {
        $jenis = $this->jenis_normalize($filter['jenis_pengerjaan']);
        $siswa = $this->siswa_rows($filter, true);
        $sesi_ids = $this->sesi_ids($sesi_rows);
        $map = $this->pengerjaan_map($filter, $sesi_ids, 'Semua');
        $hasil = [];

        foreach ($siswa as $row) {
            $id_siswa = (int) $row['id'];
            $nilai = $this->nilai_sesi_siswa($id_siswa, $sesi_rows, $map, $jenis);
            $nilai_bimbel = $this->nilai_sesi_siswa($id_siswa, $sesi_rows, $map, 'Bimbel');
            $nilai_rumah = $this->nilai_sesi_siswa($id_siswa, $sesi_rows, $map, 'Rumah');

            $hasil[] = [
                'id_siswa' => $id_siswa,
                'nis' => $row['nis'],
                'nama_siswa' => $row['nama_siswa'],
                'rata_rata' => $nilai['target'] > 0 ? $this->nilai_format($nilai['rata']) : '-',
                'bimbel' => $nilai_bimbel['target'] > 0 ? $this->nilai_format($nilai_bimbel['rata']) : '-',
                'rumah' => $nilai_rumah['target'] > 0 ? $this->nilai_format($nilai_rumah['rata']) : '-',
                'sesi' => $nilai['done'] . '/' . $nilai['target'],
                'terakhir' => $this->latest_date_from_map($id_siswa, $map, $jenis),
                'status' => $this->status_siswa($id_siswa, $nilai['target'], $nilai['done'], $map, $jenis),
                'tooltip_sesi' => $this->tooltip_sesi($nilai['missing'])
            ];
        }

        return $hasil;
    }

    public function analisa_result()
    {
        $filter = $this->filter_input();
        if ($filter['tahun_ajaran'] === '') {
            return ['result' => 'false', 'status' => false, 'message' => 'Tahun ajaran wajib dipilih.'];
        }

        if ($filter['id_kelas'] <= 0) {
            return ['result' => 'false', 'status' => false, 'message' => 'Kelas wajib dipilih.'];
        }

        $sesi_utama = $this->get_sesi_rows($filter, false);
        $sesi_analisis = $this->get_sesi_rows($filter, true);
        $sesi_ids_analisis = $this->sesi_ids($sesi_analisis);

        return [
            'result' => 'true',
            'status' => true,
            'message' => 'Data analisa kelas berhasil dimuat.',
            'filter' => $filter,
            'ringkasan' => $this->ringkasan($filter, $sesi_utama),
            'peringkat' => $this->peringkat($filter, $sesi_analisis),
            'materi' => $this->analisa_materi($filter, $sesi_ids_analisis),
            'siswa' => $this->daftar_siswa($filter, $sesi_analisis)
        ];
    }
}
?>