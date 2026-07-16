<style>
    .soal-list-card {
        border: 1px solid #e8ecf1;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 12px;
        background: #fff;
    }

    .soal-empty {
        border: 1px dashed #d9e1ea;
        background: #fbfcfe;
        border-radius: 10px;
        padding: 24px;
        text-align: center;
        color: #6c757d;
    }

    .jawaban-box {
        border: 1px dashed #d9e1ea;
        background: #fbfcfe;
        border-radius: 10px;
        padding: 14px;
    }

    .jawaban-row {
        border: 1px solid #e8ecf1;
        background: #fff;
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 10px;
    }

    .jawaban-label {
        min-width: 100px;
        font-weight: 700;
        color: #405261;
    }

    .detail-question {
        border: 1px solid #e8ecf1;
        border-radius: 10px;
        padding: 12px;
        background: #fbfcfe;
        white-space: pre-wrap;
    }

    #editor_pembahasan {
        min-height: 160px;
        background: #fff;
    }

    .detail-pembahasan {
        white-space: normal;
        line-height: 1.4;
    }

    .detail-pembahasan p {
        margin-top: 0;
        margin-bottom: 4px;
    }

    .detail-pembahasan ol,
    .detail-pembahasan ul {
        margin-top: 0;
        margin-bottom: 6px;
        padding-left: 20px;
    }

    .detail-pembahasan li {
        margin-bottom: 2px;
    }

    .detail-pembahasan> :last-child {
        margin-bottom: 0;
    }
</style>

<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
            <div>
                <h4 class="header-title mb-1">Kelola Soal: <?= htmlspecialchars($naskah['nama_naskah_soal']); ?></h4>
                <p class="text-muted mb-0">Tambahkan dan atur soal nomor 1 sampai seterusnya di dalam naskah soal ini.
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= base_url('latihan_soal/bank/naskah_soal'); ?>" class="btn btn-light"><i
                        class="ti ti-arrow-left"></i> Kembali</a>
                <button type="button" class="btn btn-primary" onclick="bukaTambahSoal()"><i class="ti ti-plus"></i>
                    Tambah Soal</button>
            </div>
        </div>

        <hr class="my-3">

        <div class="row g-2">
            <div class="col-md-3"><strong>Mata
                    Pelajaran</strong><br><?= htmlspecialchars($naskah['nama_mata_pelajaran'] ?? '-'); ?></div>
            <div class="col-md-3">
                <strong>Kategori</strong><br><?= htmlspecialchars($naskah['nama_kategori_soal'] ?? '-'); ?>
            </div>
            <div class="col-md-3"><strong>Jumlah Soal</strong><br><span
                    id="jumlah_soal_info"><?= (int) ($naskah['jumlah_soal'] ?? 0); ?></span></div>
            <div class="col-md-3"><strong>Status</strong><br><span
                    class="badge <?= $naskah['status_aktif'] == '1' ? 'bg-success' : 'bg-secondary'; ?>"><?= $naskah['status_aktif'] == '1' ? 'Aktif' : 'Tidak Aktif'; ?></span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-2 align-items-end mb-3">
            <div class="col-md-3">
                <label class="form-label">Dari Soal</label>
                <input type="text" id="filter_nomor_dari" class="form-control" min="1" placeholder="Contoh: 1"
                    onkeyup="FormatCurrency(this);">
            </div>

            <div class="col-md-3">
                <label class="form-label">Sampai Soal</label>
                <input type="text" id="filter_nomor_sampai" class="form-control" min="1" placeholder="Contoh: 5"
                    onkeyup="FormatCurrency(this);">
            </div>

            <div class="col-md-6">
                <button type="button" class="btn btn-primary" onclick="loadSoal()"><i class="ri-search-line"></i>
                </button>
                <!-- <button type="button" class="btn btn-light" onclick="resetFilterNomor()">
                    Reset
                </button> -->
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">Daftar Soal</h5>
            <small class="text-muted" id="total_soal">0 data</small>
        </div>
        <div id="data_soal"></div>
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-center align-items-md-center flex-wrap gap-2 mt-2">
            <ul class="pagination pagination-sm pagination-boxed mb-0" id="pagination"></ul>
            <div class="d-flex align-items-center gap-2">
                <label for="dt-length-0" class="mb-0">Tampilkan</label>
                <select class="form-select form-select-sm" id="dt-length-0">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>entri</span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSoal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalSoalTitle">Tambah Soal</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formSoal" enctype="multipart/form-data">
                    <input type="hidden" name="id_soal">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Nomor Soal</label>
                            <input type="number" name="nomor_soal" class="form-control" min="1" placeholder="1">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label">Materi</label>
                            <select name="id_materi" class="form-select" id="id_materi_soal">
                                <option value="">Pilih Materi</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tipe Soal</label>
                            <select name="tipe_soal" class="form-select" id="tipe_soal"
                                onchange="renderJawabanByTipe(true)">
                                <option value="">Pilih Tipe Soal</option>
                                <option value="pg">Pilihan Ganda</option>
                                <option value="pg_kompleks">Pilihan Ganda Kompleks</option>
                                <option value="benar_salah">Benar / Salah</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Bobot Nilai</label>
                            <input type="number" name="bobot_nilai" class="form-control" min="0" step="0.01"
                                placeholder="Bobot Nilai ...">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Gambar Soal</label>
                            <input type="file" name="gambar_soal" class="form-control" accept="image/*">
                            <small class="text-muted" id="info_gambar_soal"></small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status_aktif" class="form-select">
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pertanyaan</label>
                        <textarea name="pertanyaan" class="form-control" rows="4"
                            placeholder="Masukkan pertanyaan soal ..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pembahasan</label>
                        <div id="editor_pembahasan"></div>
                        <input type="hidden" name="pembahasan" id="pembahasan">
                    </div>
                    <div class="jawaban-box">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0" id="judul_area_jawaban">Area jawaban berubah sesuai tipe soal yang dipilih
                            </h5>
                            <div id="tombol_tambah_jawaban"></div>
                        </div>
                        <div id="area_jawaban" class="text-muted">Pilih tipe soal terlebih dahulu.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="simpanSoal()" id="btnSimpanSoal">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetailSoal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Soal</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detail_soal_content"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    const idNaskah = <?= (int) $naskah['id']; ?>;
    const idMapelNaskah = <?= (int) $naskah['id_mata_pelajaran']; ?>;
    const baseKelolaUrl = '<?= base_url('latihan_soal/bank/kelola_soal/'); ?>';
    let soalCache = [];
    let modeFormSoal = 'tambah';
    let jumlahPilihan = 0;
    let jumlahPernyataan = 0;

    function escapeHtml(text) {
        return String(text ?? '').replace(/[&<>'"]/g, function (m) {
            return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' })[m];
        });
    }

    function statusBadge(status) {
        return String(status) === '1'
            ? '<span class="badge bg-success">Aktif</span>'
            : '<span class="badge bg-secondary">Tidak Aktif</span>';
    }

    function tipeLabel(tipe) {
        if (tipe === 'pg') return 'Pilihan Ganda';
        if (tipe === 'pg_kompleks') return 'Pilihan Ganda Kompleks';
        if (tipe === 'benar_salah') return 'Benar / Salah';
        return '-';
    }

    function loadMateri(selectedId = '') {
        $.ajax({
            url: baseKelolaUrl + 'materi_by_mapel',
            type: 'POST',
            dataType: 'json',
            data: { id_mata_pelajaran: idMapelNaskah },
            success: function (res) {
                let html = '<option value="">Pilih Materi</option>';
                (res.data || []).forEach(function (row) {
                    html += `<option value="${row.id}">${escapeHtml(row.nama_materi)}</option>`;
                });
                $('#id_materi_soal').html(html).val(selectedId);
            }
        });
    }
    function resetFilterNomor() {
        $('#filter_nomor_dari').val('');
        $('#filter_nomor_sampai').val('');
        loadSoal();
    }
    function loadSoal() {
        let nomorDari = $('#filter_nomor_dari').val();
        let nomorSampai = $('#filter_nomor_sampai').val();

        $.ajax({
            url: baseKelolaUrl + 'result/' + idNaskah,
            type: 'POST',
            data: {
                nomor_dari: nomorDari,
                nomor_sampai: nomorSampai
            },
            dataType: 'json',
            success: function (res) {
                const $target = $('#data_soal');
                $target.html('');

                if (!res.status) {
                    $('#total_soal').text('0 data');
                    $('#jumlah_soal_info').text('0');
                    $target.html(`<div class="soal-empty">${escapeHtml(res.message || 'Data belum tersedia.')}</div>`);
                    $('#pagination').empty();
                    return;
                }

                soalCache = res.data || [];
                $('#total_soal').text(soalCache.length + ' data');
                $('#jumlah_soal_info').text(soalCache.length);

                if (soalCache.length === 0) {
                    $target.html('<div class="soal-empty">Belum ada soal pada naskah ini. Klik Tambah Soal untuk membuat soal pertama.</div>');
                    $('#pagination').empty();
                    return;
                }

                let html = '';
                soalCache.forEach(function (row) {
                    html += `
                        <div class="soal-list-card card-mapel">
                            <div class="row align-items-start g-2">
                                <div class="col-lg-8">
                                    <h5 class="mb-2">Soal ${escapeHtml(row.nomor_soal)}</h5>
                                    <div class="mb-1">Materi : ${escapeHtml(row.nama_materi || '-')}</div>
                                    <div class="mb-1">Tipe Soal : ${escapeHtml(row.tipe_soal_label || tipeLabel(row.tipe_soal))}</div>
                                    <div class="mb-1">Pertanyaan : ${escapeHtml(row.pertanyaan_singkat || row.pertanyaan || '-')}</div>
                                    <div class="mb-1">Bobot : ${escapeHtml(row.bobot_nilai || 0)}</div>
                                    <div>Status : ${statusBadge(row.status_aktif)}</div>
                                </div>
                                <div class="col-lg-4 text-lg-end">
                                    <button type="button" class="btn btn-sm btn-outline-info me-1 mb-1" onclick="detailSoal(${row.id})"><i class="ri-eye-line"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-warning me-1 mb-1" onclick="editSoal(${row.id})"><i class="ri-edit-line"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-danger mb-1" onclick="hapusSoal(${row.id})"><i class="ri-delete-bin-line"></i></button>
                                </div>
                            </div>
                        </div>`;
                });
                $target.html(html);
                let jumlah_awal = parseInt($('#dt-length-0').val());
                paging($('#data_soal .card-mapel'), jumlah_awal);
            },
            error: function () {
                $('#data_soal').html('<div class="soal-empty">Terjadi kesalahan koneksi.</div>');
                $('#pagination').empty();
            }
        });
    }

    function nextNomorSoal() {
        let max = 0;
        soalCache.forEach(function (row) {
            const nomor = parseInt(row.nomor_soal, 10) || 0;
            if (nomor > max) max = nomor;
        });
        return max + 1;
    }

    function bukaTambahSoal() {
        modeFormSoal = 'tambah';
        $('#modalSoalTitle').text('Tambah Soal');
        $('#btnSimpanSoal').text('Simpan');
        $('#formSoal')[0].reset();
        quillPembahasan.setText('');
        $('#pembahasan').val('');
        $('#formSoal').find('[name="id_soal"]').val('');
        $('#formSoal').find('[name="nomor_soal"]').val(nextNomorSoal());
        $('#formSoal').find('[name="status_aktif"]').val('1');
        $('#info_gambar_soal').text('');
        loadMateri('');
        renderAreaKosong();
        $('#modalSoal').modal('show');
    }

    function renderAreaKosong() {
        $('#judul_area_jawaban').text('Area jawaban berubah sesuai tipe soal yang dipilih');
        $('#tombol_tambah_jawaban').html('');
        $('#area_jawaban').html('<span class="text-muted">Pilih tipe soal terlebih dahulu.</span>');
    }

    function renderJawabanByTipe(reset) {
        const tipe = $('#tipe_soal').val();
        if (!tipe) {
            renderAreaKosong();
            return;
        }

        if (tipe === 'pg') {
            $('#judul_area_jawaban').text('Jawaban Pilihan Ganda');
            $('#tombol_tambah_jawaban').html('<button type="button" class="btn btn-sm btn-outline-primary" onclick="tambahPilihan()"><i class="ti ti-plus"></i> Tambah Pilihan</button>');
            jumlahPilihan = 0;
            $('#area_jawaban').html('');
            tambahPilihan('', true);
            tambahPilihan('', false);
            tambahPilihan('', false);
            tambahPilihan('', false);
            return;
        }

        if (tipe === 'pg_kompleks') {
            $('#judul_area_jawaban').text('Jawaban Pilihan Ganda Kompleks');
            $('#tombol_tambah_jawaban').html('<button type="button" class="btn btn-sm btn-outline-primary" onclick="tambahPilihanKompleks()"><i class="ti ti-plus"></i> Tambah Pilihan</button>');
            jumlahPilihan = 0;
            $('#area_jawaban').html('');
            tambahPilihanKompleks('', false);
            tambahPilihanKompleks('', false);
            tambahPilihanKompleks('', false);
            tambahPilihanKompleks('', false);
            return;
        }

        if (tipe === 'benar_salah') {
            $('#judul_area_jawaban').text('Pernyataan Benar / Salah');
            $('#tombol_tambah_jawaban').html('<button type="button" class="btn btn-sm btn-outline-primary" onclick="tambahPernyataan()"><i class="ti ti-plus"></i> Tambah Pernyataan</button>');
            jumlahPernyataan = 0;
            $('#area_jawaban').html('');
            tambahPernyataan('', '1');
            return;
        }
    }

    function hapusJawabanRow(button) {
        const tipe = $('#tipe_soal').val();
        const totalRow = $('#area_jawaban .jawaban-row').length;

        if ((tipe === 'pg' || tipe === 'pg_kompleks') && totalRow <= 2) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Minimal harus ada 2 pilihan jawaban.'
            });
            return;
        }

        if (tipe === 'benar_salah' && totalRow <= 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Minimal harus ada 1 pernyataan.'
            });
            return;
        }

        $(button).closest('.jawaban-row').remove();

        if (tipe === 'pg') {
            refreshPilihanPg();
        } else if (tipe === 'pg_kompleks') {
            refreshPilihanKompleks();
        } else if (tipe === 'benar_salah') {
            refreshPernyataan();
        }
    }

    function refreshPilihanPg() {
        jumlahPilihan = 0;

        $('#area_jawaban .jawaban-row').each(function (i) {
            const label = String.fromCharCode(65 + i);

            $(this).find('.jawaban-label').text('Pilihan ' + label);
            $(this).find('input[type="text"]')
                .attr('name', 'jawaban_teks[' + i + ']')
                .attr('placeholder', 'Masukkan pilihan ' + label + ' ...');

            $(this).find('input[type="radio"]')
                .attr('name', 'jawaban_benar_pg')
                .val(i);

            jumlahPilihan++;
        });
    }

    function refreshPilihanKompleks() {
        jumlahPilihan = 0;

        $('#area_jawaban .jawaban-row').each(function (i) {
            const label = String.fromCharCode(65 + i);

            $(this).find('.jawaban-label').text('Pilihan ' + label);
            $(this).find('input[type="text"]')
                .attr('name', 'jawaban_teks[' + i + ']')
                .attr('placeholder', 'Masukkan pilihan ' + label + ' ...');

            $(this).find('input[type="checkbox"]')
                .attr('name', 'jawaban_benar_kompleks[]')
                .val(i);

            jumlahPilihan++;
        });
    }

    function refreshPernyataan() {
        jumlahPernyataan = 0;

        $('#area_jawaban .jawaban-row').each(function (i) {
            $(this).find('.jawaban-label').text('Pernyataan ' + (i + 1));

            $(this).find('input[type="text"]')
                .attr('name', 'pernyataan_teks[' + i + ']')
                .attr('placeholder', 'Masukkan pernyataan ...');

            $(this).find('select')
                .attr('name', 'pernyataan_kunci[' + i + ']');

            jumlahPernyataan++;
        });
    }

    function tambahPilihan(teks = '', benar = false) {
        const index = jumlahPilihan++;
        const label = String.fromCharCode(65 + index);

        $('#area_jawaban').append(`
        <div class="jawaban-row">
            <div class="d-flex flex-column flex-md-row gap-2 align-items-md-center">
                <span class="jawaban-label">Pilihan ${label}</span>

                <input 
                    type="text" 
                    name="jawaban_teks[${index}]" 
                    class="form-control" 
                    value="${escapeHtml(teks)}" 
                    placeholder="Masukkan pilihan ${label} ...">

                <div class="form-check ms-md-2">
                    <input 
                        class="form-check-input" 
                        type="radio" 
                        name="jawaban_benar_pg" 
                        value="${index}" 
                        ${benar ? 'checked' : ''}>
                    <label class="form-check-label">Jawaban Benar</label>
                </div>

                <button 
                    type="button" 
                    class="btn btn-outline-danger btn-sm ms-md-2" 
                    title="Hapus pilihan"
                    onclick="hapusJawabanRow(this)">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </div>
    `);
    }
    function tambahPilihanKompleks(teks = '', benar = false) {
        const index = jumlahPilihan++;
        const label = String.fromCharCode(65 + index);

        $('#area_jawaban').append(`
        <div class="jawaban-row">
            <div class="d-flex flex-column flex-md-row gap-2 align-items-md-center">
                <span class="jawaban-label">Pilihan ${label}</span>

                <input 
                    type="text" 
                    name="jawaban_teks[${index}]" 
                    class="form-control" 
                    value="${escapeHtml(teks)}" 
                    placeholder="Masukkan pilihan ${label} ...">

                <div class="form-check ms-md-2">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        name="jawaban_benar_kompleks[]" 
                        value="${index}" 
                        ${benar ? 'checked' : ''}>
                    <label class="form-check-label">Jawaban Benar</label>
                </div>

                <button 
                    type="button" 
                    class="btn btn-outline-danger btn-sm ms-md-2" 
                    title="Hapus pilihan"
                    onclick="hapusJawabanRow(this)">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </div>
    `);
    }
    function tambahPernyataan(teks = '', kunci = '1') {
        const index = jumlahPernyataan++;

        $('#area_jawaban').append(`
        <div class="jawaban-row">
            <div class="d-flex flex-column flex-md-row gap-2 align-items-md-center">
                <span class="jawaban-label">Pernyataan ${index + 1}</span>

                <input 
                    type="text" 
                    name="pernyataan_teks[${index}]" 
                    class="form-control" 
                    value="${escapeHtml(teks)}" 
                    placeholder="Masukkan pernyataan ...">

                <select 
                    name="pernyataan_kunci[${index}]" 
                    class="form-select" 
                    style="max-width:150px">
                    <option value="1" ${String(kunci) === '1' ? 'selected' : ''}>Benar</option>
                    <option value="0" ${String(kunci) === '0' ? 'selected' : ''}>Salah</option>
                </select>

                <button 
                    type="button" 
                    class="btn btn-outline-danger btn-sm ms-md-2" 
                    title="Hapus pernyataan"
                    onclick="hapusJawabanRow(this)">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </div>
    `);
    }
    function renderJawabanEdit(tipe, jawaban) {
        if (tipe === 'pg') {
            $('#judul_area_jawaban').text('Jawaban Pilihan Ganda');
            $('#tombol_tambah_jawaban').html('<button type="button" class="btn btn-sm btn-outline-primary" onclick="tambahPilihan()"><i class="ti ti-plus"></i> Tambah Pilihan</button>');
            jumlahPilihan = 0;
            $('#area_jawaban').html('');
            (jawaban || []).forEach(function (row) { tambahPilihan(row.isi_jawaban, String(row.kunci_jawaban) === '1'); });
            return;
        }
        if (tipe === 'pg_kompleks') {
            $('#judul_area_jawaban').text('Jawaban Pilihan Ganda Kompleks');
            $('#tombol_tambah_jawaban').html('<button type="button" class="btn btn-sm btn-outline-primary" onclick="tambahPilihanKompleks()"><i class="ti ti-plus"></i> Tambah Pilihan</button>');
            jumlahPilihan = 0;
            $('#area_jawaban').html('');
            (jawaban || []).forEach(function (row) { tambahPilihanKompleks(row.isi_jawaban, String(row.kunci_jawaban) === '1'); });
            return;
        }
        if (tipe === 'benar_salah') {
            $('#judul_area_jawaban').text('Pernyataan Benar / Salah');
            $('#tombol_tambah_jawaban').html('<button type="button" class="btn btn-sm btn-outline-primary" onclick="tambahPernyataan()"><i class="ti ti-plus"></i> Tambah Pernyataan</button>');
            jumlahPernyataan = 0;
            $('#area_jawaban').html('');
            (jawaban || []).forEach(function (row) { tambahPernyataan(row.isi_jawaban, row.kunci_jawaban); });
        }
    }

    function simpanSoal() {
        const isiPembahasan = quillPembahasan.getText().trim() === ''
            ? ''
            : quillPembahasan.root.innerHTML;

        $('#pembahasan').val(isiPembahasan);

        const form = $('#formSoal')[0];
        const data = new FormData(form);
        const url = modeFormSoal === 'edit'
            ? baseKelolaUrl + 'edit/' + idNaskah
            : baseKelolaUrl + 'tambah/' + idNaskah;

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: data,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.status) {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Soal berhasil disimpan.' });
                    $('#modalSoal').modal('hide');
                    loadSoal();
                } else {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: res.message || 'Soal gagal disimpan.' });
                }
            },
            error: function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan koneksi.' });
            }
        });
    }

    function editSoal(id) {
        $.ajax({
            url: baseKelolaUrl + 'detail/' + id,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                if (!res.status) {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: res.message || 'Soal tidak ditemukan.' });
                    return;
                }
                const row = res.data;
                modeFormSoal = 'edit';
                $('#modalSoalTitle').text('Edit Soal');
                $('#btnSimpanSoal').text('Update');
                $('#formSoal')[0].reset();
                $('#formSoal').find('[name="id_soal"]').val(row.id);
                $('#formSoal').find('[name="nomor_soal"]').val(row.nomor_soal);
                $('#formSoal').find('[name="tipe_soal"]').val(row.tipe_soal);
                $('#formSoal').find('[name="bobot_nilai"]').val(row.bobot_nilai);
                $('#formSoal').find('[name="pertanyaan"]').val(row.pertanyaan || '');
                setIsiPembahasan(row.pembahasan || '');
                $('#formSoal').find('[name="status_aktif"]').val(row.status_aktif);
                $('#info_gambar_soal').html(row.gambar_soal ? `Gambar saat ini: <a href="${row.gambar_soal}" target="_blank">Lihat gambar</a>` : 'Belum ada gambar.');
                loadMateri(row.id_materi);
                renderJawabanEdit(row.tipe_soal, row.jawaban || []);
                $('#modalSoal').modal('show');
            },
            error: function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan koneksi.' });
            }
        });
    }

    function detailSoal(id) {
        $.ajax({
            url: baseKelolaUrl + 'detail/' + id,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                if (!res.status) {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: res.message || 'Soal tidak ditemukan.' });
                    return;
                }
                const row = res.data;
                let jawaban = '';
                (row.jawaban || []).forEach(function (j) {
                    const label = row.tipe_soal === 'benar_salah' ? escapeHtml(j.label_jawaban) : 'Pilihan ' + escapeHtml(j.label_jawaban);
                    const benar = String(j.kunci_jawaban) === '1'
                        ? '<span class="badge bg-success ms-2">Kunci Benar</span>'
                        : (row.tipe_soal === 'benar_salah' ? '<span class="badge bg-secondary ms-2">Kunci Salah</span>' : '');
                    jawaban += `<div class="jawaban-row"><strong>${label}</strong>${benar}<br>${escapeHtml(j.isi_jawaban)}</div>`;
                });

                const gambar = row.gambar_soal
                    ? `<div class="mb-3"><img src="${row.gambar_soal}" class="img-fluid rounded border" style="max-height:260px"></div>`
                    : '';

                $('#detail_soal_content').html(`
                    <div class="row g-3 mb-3">
                        <div class="col-md-3"><div class="ls-stat-card"><span class="ls-muted">Nomor Soal</span><div class="fw-bold mt-1">${escapeHtml(row.nomor_soal)}</div></div></div>
                        <div class="col-md-3"><div class="ls-stat-card"><span class="ls-muted">Materi</span><div class="fw-bold mt-1">${escapeHtml(row.nama_materi || '-')}</div></div></div>
                        <div class="col-md-3"><div class="ls-stat-card"><span class="ls-muted">Tipe</span><div class="fw-bold mt-1">${escapeHtml(row.tipe_soal_label || tipeLabel(row.tipe_soal))}</div></div></div>
                        <div class="col-md-3"><div class="ls-stat-card"><span class="ls-muted">Bobot</span><div class="fw-bold mt-1">${escapeHtml(row.bobot_nilai || 0)}</div></div></div>
                    </div>
                    ${gambar}
                    <h5>Pertanyaan</h5>
                    <div class="detail-question mb-3">${escapeHtml(row.pertanyaan || '-')}</div>
                    <h5>Jawaban / Kunci</h5>
                    ${jawaban || '<div class="soal-empty">Belum ada jawaban.</div>'}
                    <h5 class="mt-3">Pembahasan</h5>
                    <div class="detail-question detail-pembahasan">${formatPembahasan(row.pembahasan)}</div>
                `);
                $('#modalDetailSoal').modal('show');
            },
            error: function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan koneksi.' });
            }
        });
    }

    function hapusSoal(id) {
        Swal.fire({
            icon: 'question',
            title: 'Konfirmasi',
            text: 'Apakah yakin ingin menghapus soal ini dari naskah?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (!result.isConfirmed) return;
            $.ajax({
                url: baseKelolaUrl + 'hapus/' + idNaskah,
                type: 'POST',
                dataType: 'json',
                data: { id_soal: id },
                success: function (res) {
                    if (res.status) {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Soal berhasil dihapus.' });
                        loadSoal();
                    } else {
                        Swal.fire({ icon: 'warning', title: 'Perhatian', text: res.message || 'Soal gagal dihapus.' });
                    }
                },
                error: function () {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan koneksi.' });
                }
            });
        });
    }


    function paging($selector, jumlah_tampil = 10) {
        window.tp = new Pagination('#pagination', {
            itemsCount: $selector.length,
            pageSize: parseInt(jumlah_tampil),
            onPageChange: function (paging) {
                let start = paging.pageSize * (paging.currentPage - 1);
                let end = start + paging.pageSize;
                let $rows = $selector;

                $rows.hide();
                for (let i = start; i < end; i++) {
                    $rows.eq(i).show();
                }
            }
        });
    }
    const quillPembahasan = new Quill('#editor_pembahasan', {
        theme: 'snow',
        placeholder: 'Masukkan pembahasan soal ...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{ header: [1, 2, 3, false] }],
                [{ list: 'ordered' }, { list: 'bullet' }],
                [{ align: [] }],
                ['link'],
                ['clean']
            ]
        }
    });

    function setIsiPembahasan(html) {
        quillPembahasan.setText('');

        if (!html) {
            $('#pembahasan').val('');
            return;
        }

        quillPembahasan.clipboard.dangerouslyPasteHTML(String(html));
        $('#pembahasan').val(String(html));
    }

    function formatPembahasan(html) {
        if (!html || String(html).trim() === '') {
            return 'Pembahasan belum diisi.';
        }

        const template = document.createElement('template');
        template.innerHTML = String(html);

        const allowedTags = new Set([
            'P', 'BR', 'STRONG', 'B', 'EM', 'I', 'U', 'S',
            'OL', 'UL', 'LI', 'H1', 'H2', 'H3', 'A', 'SPAN'
        ]);

        Array.from(template.content.querySelectorAll('*')).forEach(function (element) {
            if (!allowedTags.has(element.tagName)) {
                element.replaceWith(...element.childNodes);
                return;
            }

            Array.from(element.attributes).forEach(function (attribute) {
                const name = attribute.name.toLowerCase();
                const value = attribute.value.trim();
                const isSafeLink = element.tagName === 'A'
                    && name === 'href'
                    && /^(https?:|mailto:|tel:|\/|#)/i.test(value);
                const isSafeTarget = element.tagName === 'A'
                    && name === 'target'
                    && ['_blank', '_self'].includes(value);
                const isQuillClass = name === 'class'
                    && value.split(/\s+/).every(function (className) {
                        return /^ql-(align|indent|direction)-/.test(className);
                    });

                if (!isSafeLink && !isSafeTarget && !isQuillClass) {
                    element.removeAttribute(attribute.name);
                }
            });

            if (element.tagName === 'A' && element.getAttribute('target') === '_blank') {
                element.setAttribute('rel', 'noopener noreferrer');
            }
        });

        return template.innerHTML || 'Pembahasan belum diisi.';
    }

    $(document).ready(function () {
        loadMateri();
        loadSoal();
        $('#dt-length-0').on('change', function () {
            paging($('#data_soal .card-mapel'), parseInt($(this).val()));
        });
    });
</script>