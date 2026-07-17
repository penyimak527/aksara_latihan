<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
            <div>
                <h4 class="header-title mb-1">Bank Soal / Naskah Soal</h4>
                <p class="text-muted mb-0">Satu naskah soal dapat berisi banyak soal. Saat membuat sesi soal, guru/admin
                    cukup memilih naskah soal.</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahNaskah"
                onclick="resetTambahNaskah()">
                <i class="ti ti-plus"></i> Tambah
            </button>
        </div>

        <hr class="my-3">

        <div class="row g-2 mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" id="cari_naskah" placeholder="Cari naskah soal ..."
                        onkeyup="loadNaskah()">
                    <span class="input-group-text bg-primary text-white" id="inputGroupPrepend"><i
                            class="ri-search-line"></i></span>
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="filter_mapel" onchange="loadNaskah()">
                    <option value="Semua">Pilih Mata Pelajaran</option>
                    <?php foreach (($dropdown['mapel'] ?? []) as $mp): ?>
                        <option value="<?= $mp['id']; ?>">
                            <?= htmlspecialchars($mp['nama_mata_pelajaran']); ?>
                            <?= $mp['status_aktif'] == '0' ? ' (Tidak Aktif)' : ''; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="filter_kategori" onchange="loadNaskah()">
                    <option value="Semua">Pilih Kategori Soal</option>
                    <?php foreach (($dropdown['kategori'] ?? []) as $kg): ?>
                        <option value="<?= $kg['id']; ?>">
                            <?= htmlspecialchars($kg['nama_kategori_soal']); ?>
                            <?= $kg['status_aktif'] == '0' ? ' (Tidak Aktif)' : ''; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" id="filter_status" onchange="loadNaskah()">
                    <option value="Semua">Pilih Status</option>
                    <option value="1">Status: Aktif</option>
                    <option value="0">Status: Tidak Aktif</option>
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">Data Naskah Soal</h5>
            <small class="text-muted" id="total_naskah">0 data</small>
        </div>
        <div id="data_naskah"></div>
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

<div class="modal fade" id="modalTambahNaskah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Naskah Soal</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahNaskah">
                    <div class="mb-3">
                        <label class="form-label">Nama Naskah Soal</label>
                        <input type="text" name="nama_naskah_soal" class="form-control"
                            placeholder="Contoh: Paket Matematika UAS Ganjil Kelas 5">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mata Pelajaran</label>
                        <select name="id_mata_pelajaran" class="form-control">
                            <option value="">Pilih Mata Pelajaran</option>
                            <?php foreach (($dropdown['mapel'] ?? []) as $mp): ?>
                                <?php if ($mp['status_aktif'] == '1'): ?>
                                    <option value="<?= $mp['id']; ?>"><?= htmlspecialchars($mp['nama_mata_pelajaran']); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori Soal</label>
                        <select name="id_kategori_soal" class="form-control">
                            <option value="">Pilih Kategori Soal</option>
                            <?php foreach (($dropdown['kategori'] ?? []) as $kg): ?>
                                <?php if ($kg['status_aktif'] == '1'): ?>
                                    <option value="<?= $kg['id']; ?>"><?= htmlspecialchars($kg['nama_kategori_soal']); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="4"
                            placeholder="Keterangan naskah soal ..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status_aktif" class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="simpanNaskah('tambah')">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditNaskah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Naskah Soal</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditNaskah">
                    <input type="hidden" name="id_naskah_soal">
                    <div class="mb-3">
                        <label class="form-label">Nama Naskah Soal</label>
                        <input type="text" name="nama_naskah_soal" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mata Pelajaran</label>
                        <select name="id_mata_pelajaran" class="form-control">
                            <option value="">Pilih Mata Pelajaran</option>
                            <?php foreach (($dropdown['mapel'] ?? []) as $mp): ?>
                                <option value="<?= $mp['id']; ?>">
                                    <?= htmlspecialchars($mp['nama_mata_pelajaran']); ?>
                                    <?= $mp['status_aktif'] == '0' ? ' (Tidak Aktif)' : ''; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori Soal</label>
                        <select name="id_kategori_soal" class="form-control">
                            <option value="">Pilih Kategori Soal</option>
                            <?php foreach (($dropdown['kategori'] ?? []) as $kg): ?>
                                <option value="<?= $kg['id']; ?>">
                                    <?= htmlspecialchars($kg['nama_kategori_soal']); ?>
                                    <?= $kg['status_aktif'] == '0' ? ' (Tidak Aktif)' : ''; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status_aktif" class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="simpanNaskah('edit')">Update</button>
            </div>
        </div>
    </div>
</div>
<form id="formKelolaSoal" action="<?= base_url('latihan_soal/bank/kelola_soal'); ?>" method="POST"
    style="display:none;">
    <input type="hidden" name="id_naskah" id="kelola_id_naskah">
</form>
<script>
    $(document).ready(function () {
        loadNaskah();
        $('#dt-length-0').on('change', function () {
            paging($('#data_naskah .card-mapel'), parseInt($(this).val()));
        });
    });
    let naskahCache = {};
    function escapeHtml(text) {
        return String(text ?? '').replace(/[&<>'"]/g, function (m) { return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' })[m]; });
    }
    function statusBadge(status) {
        return String(status) === '1' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Tidak Aktif</span>';
    }
    function normalizeStatus(status) {
        status = String(status ?? '1').toLowerCase(); return (status === '0' || status === 'tidak aktif' || status === 'nonaktif' || status === 'non aktif') ? '0' : '1';
    }
    function emptyBox(text) { return `<div class="border rounded p-4 text-center text-muted bg-light">${escapeHtml(text)}</div>`; }
    function resetTambahNaskah() { $('#formTambahNaskah')[0].reset(); $('#formTambahNaskah [name="status_aktif"]').val('1'); }
    function renderCard(row, index) {
        const statusNow = normalizeStatus(row.status_aktif);
        const isAktif = statusNow === '1';

        const next = isAktif ? '0' : '1';
        const btnTitle = isAktif ? 'Nonaktifkan' : 'Aktifkan';
        const btnClass = isAktif ? 'btn-outline-danger' : 'btn-outline-success';
        const btnIcon = isAktif ? 'ri-forbid-line' : 'ri-check-line';

        return `<div class="card-mapel border rounded p-3 mb-2 bg-white">
        <div class="row align-items-start g-2">
            <div class="col-lg-8">
                <div class="d-flex gap-3">
                    <div class="bg-primary-subtle text-primary rounded fw-bold d-flex align-items-center justify-content-center" style="width:34px;height:34px;min-width:34px;">
                        ${index + 1}
                    </div>
                    <div>
                        <h5 class="mb-2 text-uppercase">${escapeHtml(row.nama_naskah_soal || '-')}</h5>
                        <div class="mb-1">Mata Pelajaran : ${escapeHtml(row.nama_mata_pelajaran || '-')}</div>
                        <div class="mb-1">Kategori : ${escapeHtml(row.nama_kategori_soal || '-')}</div>
                        <div class="mb-1">Jumlah Soal : ${parseInt(row.jumlah_soal || 0, 10)}</div>
                        <div class="mb-1">Status : ${statusBadge(statusNow)}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <button type="button" class="btn btn-sm btn-outline-primary me-1 mb-1" title="Kelola Soal" onclick="kelolaSoal('${row.id}')"><i class="ri-file-list-3-line"></i></button>
                <button type="button" class="btn btn-sm btn-outline-secondary me-1 mb-1" title="Download Naskah PDF" onclick="downloadNaskah(${row.id})"><i class="ri-download-2-line"></i></button>
                <button type="button" class="btn btn-sm btn-outline-warning me-1 mb-1" title="Edit" onclick="editNaskah(${row.id})"><i class="ri-edit-line"></i></button>
                <button type="button" class="btn btn-sm ${btnClass} mb-1" title="${btnTitle}" onclick="ubahStatusNaskah(${row.id}, '${next}')"><i class="${btnIcon}"></i></button>
            </div>
        </div>
    </div>`;
    }
    function downloadNaskah(id) {
        if (!id || id == '0') {
            Swal.fire('Perhatian', 'Data naskah soal tidak valid.', 'warning');
            return;
        }
        window.open('<?= base_url('latihan_soal/bank/naskah_soal/download_pdf/'); ?>' + id, '_blank');
    }

    function kelolaSoal(id_naskah) {
        if (!id_naskah || id_naskah == '0') {
            Swal.fire('Perhatian', 'Data naskah soal tidak valid.', 'warning');
            return;
        }

        $('#kelola_id_naskah').val(id_naskah);
        $('#formKelolaSoal').trigger('submit');
    }
    function loadNaskah() {
        $.ajax({
            url: '<?= base_url('latihan_soal/bank/naskah_soal/result'); ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                search: $('#cari_naskah').val(),
                id_mata_pelajaran: $('#filter_mapel').val(),
                id_kategori_soal: $('#filter_kategori').val(),
                status: $('#filter_status').val()
            },
            success: function (res) {
                const $target = $('#data_naskah');
                naskahCache = {};
                if (!res.status) {
                    $('#total_naskah').text('0 data');
                    $target.html(emptyBox(res.message || 'Data belum tersedia.'));
                    $('#pagination').empty();
                    return;
                }
                const data = res.data || [];
                $('#total_naskah').text(data.length + ' data');
                if (data.length === 0) {
                    $target.html(emptyBox('Belum ada naskah soal sesuai filter.'));
                    $('#pagination').empty();
                    return;
                }
                let html = '';
                data.forEach(function (row, index) {
                    naskahCache[row.id] = row;
                    html += renderCard(row, index);
                });
                $target.html(html);
                let jumlah_awal = parseInt($('#dt-length-0').val());
                paging($('#data_naskah .card-mapel'), jumlah_awal);
            },
            error: function () {
                $('#data_naskah').html(emptyBox('Terjadi kesalahan koneksi.'));
                $('#pagination').empty();
            }
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

    function simpanNaskah(mode) {
        const form = mode === 'edit' ? '#formEditNaskah' : '#formTambahNaskah';
        const modal = mode === 'edit' ? '#modalEditNaskah' : '#modalTambahNaskah';
        const url = mode === 'edit' ? '<?= base_url('latihan_soal/bank/naskah_soal/edit'); ?>' : '<?= base_url('latihan_soal/bank/naskah_soal/tambah'); ?>';
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: $(form).serialize(),
            success: function (res) {
                if (res.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message || 'Data berhasil disimpan.'
                    });
                    $(modal).modal('hide');
                    $(form)[0].reset(); loadNaskah();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian', text: res.message || 'Data gagal disimpan.'
                    });
                }
            }, error: function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan koneksi.' });
            }
        });
    }
    function editNaskah(id) {
        const row = naskahCache[id];
        if (!row) return;
        const form = $('#formEditNaskah');
        form.find('[name="id_naskah_soal"]').val(row.id);
        form.find('[name="nama_naskah_soal"]').val(row.nama_naskah_soal);
        form.find('[name="id_mata_pelajaran"]').val(row.id_mata_pelajaran);
        form.find('[name="id_kategori_soal"]').val(row.id_kategori_soal);
        form.find('[name="keterangan"]').val(row.keterangan || '');
        form.find('[name="status_aktif"]').val(normalizeStatus(row.status_aktif));
        $('#modalEditNaskah').modal('show');
    }
    function ubahStatusNaskah(id, status) {
        const teks = status === '1' ? 'mengaktifkan' : 'menonaktifkan';
        Swal.fire({
            icon: 'question',
            title: 'Konfirmasi',
            text: `Apakah yakin ingin ${teks} naskah soal ini?`,
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then(
            function (result) {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: '<?= base_url('latihan_soal/bank/naskah_soal/ubah_status'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: { id: id, status_aktif: status },
                    success: function (res) {
                        if (res.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message || 'Status berhasil diubah.'
                            }); loadNaskah();
                        } else {
                            Swal.fire({ icon: 'warning', title: 'Perhatian', text: res.message || 'Status gagal diubah.' });
                        }
                    }, error: function () {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan koneksi.' });
                    }
                });
            });
    }
</script>