<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_perkembangan_belajar extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $admin = $this->session->userdata('admin');
        if (empty($admin) || empty($admin['username'])) {
            redirect('/');
        }

        $json = file_get_contents('php://input');
        $ambil = json_decode($json, true);

        if (!is_array($ambil)) {
            $ambil = [];
        }

        $id_siswa = (int) ($ambil['id_siswa_perkembangan'] ?? 0);
        $id_kelas = (int) ($ambil['id_kelas_perkembangan'] ?? 0);
        $semester = ucfirst(strtolower(trim((string) ($ambil['semester_perkembangan'] ?? ''))));
        $id_mapel_input = $ambil['id_mapel_perkembangan'] ?? 'semua';
        $id_mapel = ($id_mapel_input === '' || $id_mapel_input === 'semua') ? 0 : (int) $id_mapel_input;

        $tahun_ajaran = '';

        /*
         * Data default untuk laporan kosong.
         * Controller tetap mengembalikan view print dengan status HTTP 200,
         * sehingga pesan dapat dilihat oleh admin.
         */
        $data_kosong = [
            'data_tersedia' => false,
            'pesan_kosong' => 'Tidak ada data.',
            'siswa' => [],
            'ringkasan' => [],
            'perkembangan_mapel' => [],
            'perkembangan_materi' => [],
            'grafik_bulan' => [],
            'tahun_ajaran' => '-',
            'semester' => in_array($semester, ['Ganjil', 'Genap'], true) ? $semester : '-',
            'tanggal_cetak' => $this->tanggal_indonesia(date('Y-m-d'))
        ];

        if (
            $id_siswa <= 0 ||
            $id_kelas <= 0 ||
            !in_array($semester, ['Ganjil', 'Genap'], true)
        ) {
            $this->load->view(
                'admin/data_laporan/laporan_perkembangan_belajar',
                $data_kosong
            );
            return;
        }

        $tanggal_sql = "STR_TO_DATE(ps.waktu_selesai, '%d-%m-%Y %H:%i:%s')";
        $nilai_sql = "CAST(NULLIF(ps.nilai_akhir, '') AS DECIMAL(10,2))";

        /*
         * Tahun ajaran tidak lagi dipilih dari filter.
         * Sistem mengambil tahun ajaran terakhir dari pengerjaan siswa
         * pada kelas yang dipilih.
         */
        $periode = $this->db->query(
            "SELECT ps.tahun_ajaran
            FROM siswa_pengerjaan ps
            WHERE ps.id_siswa = ?
              AND ps.id_kelas = ?
              AND ps.tahun_ajaran IS NOT NULL
              AND ps.tahun_ajaran != ''
              AND ps.status_pengerjaan IN (
                    'Selesai',
                    'Waktu Habis',
                    'Selesai karena timer habis'
              )
              AND {$tanggal_sql} IS NOT NULL
              AND {$nilai_sql} IS NOT NULL
            ORDER BY {$tanggal_sql} DESC, ps.id DESC
            LIMIT 1",
            [$id_siswa, $id_kelas]
        )->row_array();

        $tahun_ajaran = trim((string) ($periode['tahun_ajaran'] ?? ''));

        if ($tahun_ajaran === '') {
            $this->load->view(
                'admin/data_laporan/laporan_perkembangan_belajar',
                $data_kosong
            );
            return;
        }

        $bagian_tahun = explode('/', $tahun_ajaran);
        $tahun_awal = (int) ($bagian_tahun[0] ?? 0);
        $tahun_akhir = (int) ($bagian_tahun[1] ?? 0);

        if ($tahun_awal <= 0 || $tahun_akhir <= 0) {
            $this->load->view(
                'admin/data_laporan/laporan_perkembangan_belajar',
                $data_kosong
            );
            return;
        }

        $data_kosong['tahun_ajaran'] = $tahun_ajaran;

        /*
         * Data siswa diambil berdasarkan siswa yang dipilih.
         * Nama kelas diambil dari master kelas yang dipilih,
         * bukan dari kelas siswa saat ini.
         */
        $siswa = $this->db->query(
            "SELECT
                s.id,
                s.nis,
                s.nama_siswa,
                k.id AS id_kelas,
                k.nama_kelas,
                j.nama_jenjang
            FROM siswa s
            INNER JOIN kelas k ON k.id = ?
            LEFT JOIN jenjang j ON j.id = k.id_jenjang
            WHERE s.id = ?
            LIMIT 1",
            [$id_kelas, $id_siswa]
        )->row_array();

        if (empty($siswa)) {
            $this->load->view(
                'admin/data_laporan/laporan_perkembangan_belajar',
                $data_kosong
            );
            return;
        }

        $where = [
            'ps.id_siswa = ?',
            'ps.tahun_ajaran = ?',
            'ps.id_kelas = ?',
            "ps.status_pengerjaan IN ('Selesai', 'Waktu Habis', 'Selesai karena timer habis')",
            "{$tanggal_sql} IS NOT NULL",
            "{$nilai_sql} IS NOT NULL"
        ];

        $params = [$id_siswa, $tahun_ajaran, $id_kelas];

        if ($semester === 'Ganjil') {
            $where[] = "YEAR({$tanggal_sql}) = ?";
            $where[] = "MONTH({$tanggal_sql}) BETWEEN 7 AND 12";
            $params[] = $tahun_awal;
        } else {
            $where[] = "YEAR({$tanggal_sql}) = ?";
            $where[] = "MONTH({$tanggal_sql}) BETWEEN 1 AND 6";
            $params[] = $tahun_akhir;
        }

        if ($id_mapel > 0) {
            $where[] = 'ss.id_mata_pelajaran = ?';
            $params[] = $id_mapel;
        }

        $where_sql = implode(' AND ', $where);

        $pengerjaan = $this->db->query(
            "SELECT
                ps.id,
                ps.jenis_pengerjaan,
                ROUND({$nilai_sql}, 2) AS nilai,
                DATE_FORMAT({$tanggal_sql}, '%Y-%m') AS periode,
                DATE_FORMAT({$tanggal_sql}, '%d-%m-%Y') AS tanggal,
                ss.nama_sesi,
                mp.nama_mata_pelajaran
            FROM siswa_pengerjaan ps
            INNER JOIN soal_sesi ss ON ss.id = ps.id_sesi_soal
            LEFT JOIN mata_pelajaran mp ON mp.id = ss.id_mata_pelajaran
            WHERE {$where_sql}
            ORDER BY {$tanggal_sql} ASC, ps.id ASC",
            $params
        )->result_array();

        if (empty($pengerjaan)) {
            $this->load->view(
                'admin/data_laporan/laporan_perkembangan_belajar',
                $data_kosong
            );
            return;
        }

        $daftar_nilai = [];
        foreach ($pengerjaan as $row) {
            $daftar_nilai[] = (float) ($row['nilai'] ?? 0);
        }

        $nama_mata_pelajaran = 'Semua Mata Pelajaran';
        if ($id_mapel > 0) {
            $mapel = $this->db->query(
                "SELECT nama_mata_pelajaran
                FROM mata_pelajaran
                WHERE id = ?
                LIMIT 1",
                [$id_mapel]
            )->row_array();

            $nama_mata_pelajaran = $mapel['nama_mata_pelajaran'] ?? '-';
        }

        $kelas = trim(
            ($siswa['nama_jenjang'] ?? '') . ' ' .
            ($siswa['nama_kelas'] ?? '')
        );

        $perkembangan_mapel = $this->db->query(
            "SELECT
                mp.id,
                mp.nama_mata_pelajaran,
                COUNT(ps.id) AS jumlah_sesi,
                ROUND(AVG({$nilai_sql}), 2) AS rata_rata
            FROM siswa_pengerjaan ps
            INNER JOIN soal_sesi ss ON ss.id = ps.id_sesi_soal
            INNER JOIN mata_pelajaran mp ON mp.id = ss.id_mata_pelajaran
            WHERE {$where_sql}
            GROUP BY mp.id, mp.nama_mata_pelajaran
            ORDER BY mp.nama_mata_pelajaran ASC",
            $params
        )->result_array();

        foreach ($perkembangan_mapel as $key => $row) {
            $perkembangan_mapel[$key]['capaian'] = $this->capaian(
                (float) ($row['rata_rata'] ?? 0)
            );
        }

        $jawaban_source = "(
            SELECT id_pengerjaan, id_soal, nilai, 'Bimbel' AS jenis_pengerjaan
            FROM siswa_jawaban_bimbel

            UNION ALL

            SELECT id_pengerjaan, id_soal, nilai, 'Rumah' AS jenis_pengerjaan
            FROM siswa_jawaban_rumah
        )";

        $materi_where = $where;
        $materi_where[] = 'm.id IS NOT NULL';
        $materi_where_sql = implode(' AND ', $materi_where);

        $perkembangan_materi = $this->db->query(
            "SELECT
                mp.nama_mata_pelajaran,
                m.id,
                m.nama_materi,
                COUNT(js.id_soal) AS jumlah_soal,
                ROUND(
                    (
                        SUM(COALESCE(CAST(js.nilai AS DECIMAL(10,2)), 0)) /
                        NULLIF(SUM(CAST(so.bobot_nilai AS DECIMAL(10,2))), 0)
                    ) * 100,
                    2
                ) AS hasil
            FROM {$jawaban_source} js
            INNER JOIN siswa_pengerjaan ps
                ON ps.id = js.id_pengerjaan
               AND ps.jenis_pengerjaan = js.jenis_pengerjaan
            INNER JOIN soal_sesi ss ON ss.id = ps.id_sesi_soal
            INNER JOIN mata_pelajaran mp ON mp.id = ss.id_mata_pelajaran
            INNER JOIN soal so
                ON so.id = js.id_soal
               AND so.status_hapus IS NULL
            INNER JOIN materi m ON m.id = so.id_materi
            WHERE {$materi_where_sql}
            GROUP BY mp.id, mp.nama_mata_pelajaran, m.id, m.nama_materi
            ORDER BY mp.nama_mata_pelajaran ASC, m.nama_materi ASC",
            $params
        )->result_array();

        foreach ($perkembangan_materi as $key => $row) {
            $hasil = max(0, min(100, (float) ($row['hasil'] ?? 0)));
            $perkembangan_materi[$key]['hasil'] = round($hasil, 2);
            $perkembangan_materi[$key]['capaian'] = $this->capaian($hasil);
        }

        $bulan_indonesia = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $kelompok_bulan = [];
        foreach ($pengerjaan as $row) {
            $periode = (string) ($row['periode'] ?? '');
            if ($periode === '') {
                continue;
            }

            if (!isset($kelompok_bulan[$periode])) {
                $kelompok_bulan[$periode] = [];
            }

            $kelompok_bulan[$periode][] = (float) ($row['nilai'] ?? 0);
        }

        $grafik_bulan = [];
        foreach ($kelompok_bulan as $periode => $nilai_bulan) {
            $bagian = explode('-', $periode);
            $tahun = $bagian[0] ?? '';
            $bulan = $bagian[1] ?? '';

            $grafik_bulan[] = [
                'periode' => $periode,
                'label' => ($bulan_indonesia[$bulan] ?? $bulan) . ' ' . $tahun,
                'nilai' => round(array_sum($nilai_bulan) / count($nilai_bulan), 2),
                'jumlah_sesi' => count($nilai_bulan)
            ];
        }

        $status_perkembangan = $this->status_perkembangan($pengerjaan);

        $ringkasan = [
            'periode' => $tahun_ajaran,
            'kelas' => $kelas,
            'mata_pelajaran' => $nama_mata_pelajaran,
            'jumlah_sesi' => count($daftar_nilai),
            'rata_rata' => round(array_sum($daftar_nilai) / count($daftar_nilai), 2),
            'nilai_tertinggi' => round(max($daftar_nilai), 2),
            'nilai_terendah' => round(min($daftar_nilai), 2),
            'status_perkembangan' => $status_perkembangan
        ];

        $data = [
            'data_tersedia' => true,
            'pesan_kosong' => '',
            'siswa' => $siswa,
            'ringkasan' => $ringkasan,
            'perkembangan_mapel' => $perkembangan_mapel,
            'perkembangan_materi' => $perkembangan_materi,
            'grafik_bulan' => $grafik_bulan,
            'tahun_ajaran' => $tahun_ajaran,
            'semester' => $semester,
            'tanggal_cetak' => $this->tanggal_indonesia(date('Y-m-d'))
        ];

        $this->load->view(
            'admin/data_laporan/laporan_perkembangan_belajar',
            $data
        );
    }

    private function capaian($nilai)
    {
        if ($nilai >= 80) {
            return 'Dikuasai';
        }

        if ($nilai >= 60) {
            return 'Cukup';
        }

        return 'Perlu Ditingkatkan';
    }

    private function status_perkembangan($pengerjaan)
{
    if (empty($pengerjaan)) {
        return 'Stabil';
    }

    if (count($pengerjaan) === 1) {
        return 'Stabil';
    }

    $nilai_awal = (float) ($pengerjaan[0]['nilai'] ?? 0);
    $nilai_akhir = (float) (
        $pengerjaan[count($pengerjaan) - 1]['nilai'] ?? 0
    );

    $selisih = $nilai_akhir - $nilai_awal;

    if (abs($selisih) <= 2) {
        return 'Stabil';
    }

    return $selisih > 0 ? 'Meningkat' : 'Menurun';
}

    private function semester_laporan($grafik_bulan)
    {
        $ada_ganjil = false;
        $ada_genap = false;

        foreach ($grafik_bulan as $row) {
            $periode = (string) ($row['periode'] ?? '');
            $bulan = (int) substr($periode, 5, 2);

            if ($bulan >= 7 && $bulan <= 12) {
                $ada_ganjil = true;
            }

            if ($bulan >= 1 && $bulan <= 6) {
                $ada_genap = true;
            }
        }

        if ($ada_ganjil && $ada_genap) {
            return 'Ganjil dan Genap';
        }

        return $ada_genap ? 'Genap' : 'Ganjil';
    }

    private function tanggal_indonesia($tanggal)
    {
        $bulan = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $timestamp = strtotime($tanggal);

        return date('d', $timestamp) . ' ' .
            $bulan[(int) date('n', $timestamp)] . ' ' .
            date('Y', $timestamp);
    }
}
