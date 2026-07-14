<?php
$admin_login = $this->session->userdata('admin');
$id_level_login = $admin_login['id_level'] ?? '';
$id_user_login = $admin_login['id'] ?? '';
$base_sesi_url = $base_sesi_url ?? base_url('latihan_soal/sesi_soal');
?>
<?php
function render_sesi_soal_form($mode, $dropdown, $id_level_login, $id_user_login)
{
    $formPrefix = $mode === 'edit' ? 'edit' : 'tambah';
    ?>
    <div class="row g-2">
        <div class="col-md-12">
            <div class="mb-2">
                <label class="form-label">Nama Sesi</label>
                <input type="text" name="nama_sesi" class="form-control" placeholder="Contoh: UAS Ganjil Matematika Kelas 5">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label class="form-label">Tahun Ajaran</label>
                <select name="tahun_ajaran" class="form-control">
                    <option value="">Pilih Tahun Ajaran</option>
                    <?php foreach (($dropdown['tahun_ajaran'] ?? []) as $tahun): ?>
                        <option value="<?= htmlspecialchars($tahun); ?>"><?= htmlspecialchars($tahun); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label class="form-label">Guru Pengampu</label>
                <select name="id_guru_pengampu" class="form-control" <?= $id_level_login == '2' ? 'disabled' : ''; ?>>
                    <option value="">Pilih Guru Pengampu</option>
                    <?php foreach (($dropdown['guru'] ?? []) as $guru): ?>
                        <option value="<?= $guru['id']; ?>" <?= ($id_level_login == '2' && (string) $guru['id'] === (string) $id_user_login) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($guru['nama_guru'] ?? $guru['nama_user'] ?? '-'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($id_level_login == '2'): ?>
                    <input type="hidden" name="id_guru_pengampu" value="<?= htmlspecialchars($id_user_login); ?>">
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label class="form-label">Mata Pelajaran</label>
                <select name="id_mata_pelajaran" class="form-control">
                    <option value="">Pilih Mata Pelajaran</option>
                    <?php foreach (($dropdown['mapel'] ?? []) as $mapel): ?>
                        <option value="<?= $mapel['id']; ?>"><?= htmlspecialchars($mapel['nama_mata_pelajaran']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label class="form-label">Kategori Soal</label>
                <select name="id_kategori_soal" class="form-control">
                    <option value="">Pilih Kategori Soal</option>
                    <?php foreach (($dropdown['kategori'] ?? []) as $kategori): ?>
                        <option value="<?= $kategori['id']; ?>"><?= htmlspecialchars($kategori['nama_kategori_soal']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label class="form-label">Naskah Soal</label>
                <select name="id_naskah_soal" class="form-control">
                    <option value="">Pilih Naskah Soal</option>
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-2">
                <label class="form-label">Kelas</label>
                <div class="border rounded p-2" style="max-height:180px; overflow:auto;">
                    <div class="row g-1">
                        <?php foreach (($dropdown['kelas'] ?? []) as $kelas): ?>
                            <div class="col-md-3 col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="id_kelas[]" value="<?= $kelas['id']; ?>" id="<?= $formPrefix; ?>_kelas_<?= $kelas['id']; ?>">
                                    <label class="form-check-label" for="<?= $formPrefix; ?>_kelas_<?= $kelas['id']; ?>">Kelas <?= htmlspecialchars($kelas['nama_kelas']); ?></label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($dropdown['kelas'])): ?>
                            <div class="col-12 text-muted">Data kelas belum tersedia.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label class="form-label">Jam Mulai</label>
                <input type="time" name="jam_mulai" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label class="form-label">Jam Selesai</label>
                <input type="time" name="jam_selesai" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label class="form-label">Durasi Timer</label>
                <div class="input-group">
                    <input type="number" name="durasi_timer" class="form-control" placeholder="Durasi dalam menit ..." min="1">
                    <span class="input-group-text">menit</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label class="form-label">Status</label>
                <select name="status_aktif" class="form-control">
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>
        </div>
    </div>
    <?php
}
?>

<div class="card">
    <div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
        <div>
            <h4 class="header-title mb-1">Sesi Soal</h4>
            <small class="text-muted">Mengatur naskah soal, kelas, jadwal, dan timer.</small>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSesi" onclick="resetTambahSesi()">
            <i class="ri-add-line"></i> Tambah
        </button>
    </div>
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-md-12">
                <div class="input-group">
                    <input type="text" id="cari_sesi" class="form-control" placeholder="Cari sesi soal ..." onkeyup="loadSesi()">
                    <span class="input-group-text bg-primary text-white" id="inputGroupPrepend"><i class="ri-search-line"></i></span>
                </div>
            </div>
            <div class="col-md-3">
                <select id="filter_tahun_ajaran" class="form-control" onchange="loadSesi()">
                    <option value="Semua">Pilih Tahun Ajaran</option>
                    <?php foreach (($dropdown['tahun_ajaran'] ?? []) as $tahun): ?>
                        <option value="<?= htmlspecialchars($tahun); ?>"><?= htmlspecialchars($tahun); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filter_kelas" class="form-control" onchange="loadSesi()">
                    <option value="Semua">Pilih Kelas</option>
                    <?php foreach (($dropdown['kelas'] ?? []) as $kelas): ?>
                        <option value="<?= $kelas['id']; ?>"><?= $kelas['nama_jenjang']?> <?= htmlspecialchars($kelas['nama_kelas']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filter_mapel" class="form-control" onchange="loadSesi()">
                    <option value="Semua">Pilih Mata Pelajaran</option>
                    <?php foreach (($dropdown['mapel'] ?? []) as $mapel): ?>
                        <option value="<?= $mapel['id']; ?>"><?= htmlspecialchars($mapel['nama_mata_pelajaran']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filter_status" class="form-control" onchange="loadSesi()">
                    <option value="Semua">Pilih Status</option>
                    <option value="1">Status: Aktif</option>
                    <option value="0">Status: Tidak Aktif</option>
                </select>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-2">
            <h5 class="mb-0">Data Sesi Soal</h5>
            <small class="text-muted" id="total_sesi">0 data</small>
        </div>
        <div id="data_sesi"></div>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center flex-wrap gap-2 mt-2">
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

<div class="modal fade" id="modalTambahSesi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Sesi Soal</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahSesi">
                    <?php render_sesi_soal_form('tambah', $dropdown, $id_level_login, $id_user_login); ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="simpanSesi('tambah')">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditSesi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Sesi Soal</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditSesi">
                    <input type="hidden" name="id_sesi_soal">
                    <?php render_sesi_soal_form('edit', $dropdown, $id_level_login, $id_user_login); ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="simpanSesi('edit')">Update</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetailSesi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Sesi Soal</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detail_sesi_body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    let sesiCache = {};
    const BASE_SESI = '<?= rtrim($base_sesi_url, '/'); ?>';

    function escapeHtml(text) {
        return String(text ?? '').replace(/[&<>'"]/g, function (m) {
            return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' })[m];
        });
    }

    function statusText(status) {
        return String(status) === '1' ? 'Aktif' : 'Tidak Aktif';
    }

    function statusBadge(status) {
        return String(status) === '1' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Tidak Aktif</span>';
    }

    function emptyBox(text) {
        return `<div class="card-mapel"><div class="keterangan-mapel"><div class="keterangan-mapel-kiri"><h5 class="judul-mapel mb-0">${escapeHtml(text)}</h5></div></div></div>`;
    }

    function resetTambahSesi() {
        $('#formTambahSesi')[0].reset();
        $('#formTambahSesi [name="status_aktif"]').val('1');
        <?php if ($id_level_login == '2'): ?>
        $('#formTambahSesi [name="id_guru_pengampu"]').val('<?= $id_user_login; ?>');
        <?php endif; ?>
        loadNaskahOptions('#formTambahSesi');
    }

    function renderSesi(row, index) {
    const statusNow = String(row.status_aktif) === '0' ? '0' : '1';
    const isAktif = statusNow === '1';

    const next = isAktif ? '0' : '1';
    const btnTitle = isAktif ? 'Nonaktifkan' : 'Aktifkan';
    const btnClass = isAktif ? 'btn-outline-danger' : 'btn-outline-success';
    const btnIcon = isAktif ? 'ri-forbid-line' : 'ri-check-line';
    const bisaHapus = String(row.bisa_hapus || '0') === '1';
    const btnHapus = bisaHapus
        ? `<button type="button" class="btn btn-outline-danger btn-sm" title="Hapus sesi" onclick="hapusSesi(${row.id})"><i class="ri-delete-bin-line"></i></button>`
        : `<button type="button" class="btn btn-outline-secondary btn-sm" title="${escapeHtml(row.alasan_hapus || 'Sesi tidak dapat dihapus.')}" disabled><i class="ri-delete-bin-line"></i></button>`;

    return `<div class="card-mapel">
        <div class="keterangan-mapel">
            <div class="keterangan-mapel-kiri">
                <h5 class="judul-mapel" style="margin:0; margin-bottom: 8px;">
                    ${index + 1}. ${escapeHtml(row.nama_sesi || '-')}
                </h5>
                <p style="margin:0; font-size:12px;"><b>Tahun Ajaran</b> : ${escapeHtml(row.tahun_ajaran || '-')}</p>
                <p style="margin:0; font-size:12px;"><b>Mata Pelajaran</b> : ${escapeHtml(row.nama_mata_pelajaran || '-')}</p>
                <p style="margin:0; font-size:12px;"><b>Kategori</b> : ${escapeHtml(row.nama_kategori_soal || '-')}</p>
                <p style="margin:0; font-size:12px;"><b>Naskah Soal</b> : ${escapeHtml(row.nama_naskah_soal || '-')}</p>
                <p style="margin:0; font-size:12px;"><b>Kelas</b> : ${escapeHtml(row.nama_kelas || '-')}</p>
                <p style="margin:0; font-size:12px;"><b>Jumlah Siswa</b> : ${parseInt(row.jumlah_siswa || 0, 10)} siswa</p>
                <p style="margin:0; font-size:12px;">
                    <b>Jadwal</b> : ${escapeHtml(row.tanggal_mulai || '-')} s/d ${escapeHtml(row.tanggal_selesai || '-')} , ${escapeHtml(row.jam_mulai || '-')} - ${escapeHtml(row.jam_selesai || '-')}
                </p>
                <p style="margin:0; font-size:12px;"><b>Durasi Timer</b> : ${parseInt(row.durasi_timer || 0, 10)} menit</p>
                <p style="margin:0; font-size:12px;"><b>Status</b> : ${statusBadge(statusNow)}</p>
            </div>

            <div class="keterangan-mapel-kanan">
                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-info btn-sm" title="Detail" onclick="detailSesi(${row.id})"><i class="ri-eye-line"></i></button>
                    <button type="button" class="btn btn-outline-warning btn-sm" title="Edit" onclick="editSesi(${row.id})"><i class="ri-edit-line"></i></button>
                    <button type="button" class="btn ${btnClass} btn-sm" title="${btnTitle}" onclick="ubahStatusSesi(${row.id}, '${next}')"><i class="${btnIcon}"></i></button>
                    ${btnHapus}
                </div>
            </div>
        </div>
    </div>`;
}

    function loadSesi() {
        $.ajax({
            url: BASE_SESI + '/sesi_soal_result',
            type: 'POST',
            dataType: 'json',
            data: {
                search: $('#cari_sesi').val(),
                tahun_ajaran: $('#filter_tahun_ajaran').val(),
                id_kelas: $('#filter_kelas').val(),
                id_mata_pelajaran: $('#filter_mapel').val(),
                status: $('#filter_status').val()
            },
            success: function (res) {
                sesiCache = {};
                let data = (res && res.status) ? (res.data || []) : [];
                let table = '';

                $('#total_sesi').text(data.length + ' data');

                if (!res || !res.status) {
                    table += emptyBox((res && res.message) ? res.message : 'Data belum tersedia.');
                } else if (data.length === 0) {
                    table += emptyBox('Belum ada sesi soal sesuai filter.');
                } else {
                    data.forEach(function (row, index) {
                        sesiCache[row.id] = row;
                        table += renderSesi(row, index);
                    });
                }

                $('#data_sesi').html(table);
                let jumlah_awal = parseInt($('#dt-length-0').val());
                paging($('#data_sesi .card-mapel'), jumlah_awal);
            },
            error: function () {
                $('#total_sesi').text('0 data');
                $('#data_sesi').html(emptyBox('Terjadi kesalahan koneksi.'));
                $('#pagination').empty();
            }
        });
    }

    function loadNaskahOptions(formSelector, selectedId = '') {
        const idMapel = $(`${formSelector} [name="id_mata_pelajaran"]`).val();
        const idKategori = $(`${formSelector} [name="id_kategori_soal"]`).val();
        $.ajax({
            url: BASE_SESI + '/naskah_by_filter',
            type: 'POST',
            dataType: 'json',
            data: { id_mata_pelajaran: idMapel, id_kategori_soal: idKategori },
            success: function (res) {
                let option = '<option value="">Pilih Naskah Soal</option>';
                if (res.status && res.data) {
                    res.data.forEach(function (item) {
                        option += `<option value="${item.id}" ${String(item.id) === String(selectedId) ? 'selected' : ''}>${escapeHtml(item.nama_naskah_soal)} (${parseInt(item.jumlah_soal || 0, 10)} soal)</option>`;
                    });
                }
                $(`${formSelector} [name="id_naskah_soal"]`).html(option);
            }
        });
    }

    function simpanSesi(mode) {
        const form = mode === 'edit' ? '#formEditSesi' : '#formTambahSesi';
        const modal = mode === 'edit' ? '#modalEditSesi' : '#modalTambahSesi';
        const url = mode === 'edit' ? BASE_SESI + '/edit' : BASE_SESI + '/tambah';
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: $(form).serialize(),
            success: function (res) {
                if (res.status) {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Data berhasil disimpan.' });
                    $(modal).modal('hide');
                    $(form)[0].reset();
                    loadSesi();
                } else {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: res.message || 'Data gagal disimpan.' });
                }
            },
            error: function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan koneksi.' });
            }
        });
    }

    function editSesi(id) {
        const row = sesiCache[id];
        if (!row) return;
        const form = $('#formEditSesi');
        form[0].reset();
        form.find('[name="id_sesi_soal"]').val(row.id);
        form.find('[name="nama_sesi"]').val(row.nama_sesi);
        form.find('[name="tahun_ajaran"]').val(row.tahun_ajaran);
        form.find('[name="id_guru_pengampu"]').val(row.id_guru_pengampu);
        form.find('[name="id_mata_pelajaran"]').val(row.id_mata_pelajaran);
        form.find('[name="id_kategori_soal"]').val(row.id_kategori_soal);
        form.find('[name="tanggal_mulai"]').val(row.tanggal_mulai_input);
        form.find('[name="tanggal_selesai"]').val(row.tanggal_selesai_input);
        form.find('[name="jam_mulai"]').val(row.jam_mulai);
        form.find('[name="jam_selesai"]').val(row.jam_selesai);
        form.find('[name="durasi_timer"]').val(row.durasi_timer);
        form.find('[name="status_aktif"]').val(String(row.status_aktif) === '0' ? '0' : '1');
        form.find('[name="id_kelas[]"]').prop('checked', false);
        (row.kelas_ids || []).forEach(function (idKelas) {
            form.find(`[name="id_kelas[]"][value="${idKelas}"]`).prop('checked', true);
        });
        loadNaskahOptions('#formEditSesi', row.id_naskah_soal);
        $('#modalEditSesi').modal('show');
    }

    function detailRowHtml(label, value) {
        return `<tr><td style="width:180px;">${escapeHtml(label)}</td><td style="width:10px;">:</td><td>${value}</td></tr>`;
    }

    function detailSesi(id) {
        const row = sesiCache[id];
        if (!row) return;
        let html = '<div class="table-responsive"><table class="table table-sm table-borderless mb-0"><tbody>';
        html += detailRowHtml('Nama Sesi', escapeHtml(row.nama_sesi || '-'));
        html += detailRowHtml('Tahun Ajaran', escapeHtml(row.tahun_ajaran || '-'));
        html += detailRowHtml('Guru Pengampu', escapeHtml(row.nama_guru || '-'));
        html += detailRowHtml('Mata Pelajaran', escapeHtml(row.nama_mata_pelajaran || '-'));
        html += detailRowHtml('Kategori Soal', escapeHtml(row.nama_kategori_soal || '-'));
        html += detailRowHtml('Naskah Soal', escapeHtml(row.nama_naskah_soal || '-'));
        html += detailRowHtml('Jumlah Soal', parseInt(row.jumlah_soal || 0, 10) + ' soal');
        html += detailRowHtml('Kelas', escapeHtml(row.nama_kelas || '-'));
        html += detailRowHtml('Jumlah Siswa', parseInt(row.jumlah_siswa || 0, 10) + ' siswa');
        html += detailRowHtml('Tanggal Mulai', escapeHtml(row.tanggal_mulai || '-'));
        html += detailRowHtml('Tanggal Selesai', escapeHtml(row.tanggal_selesai || '-'));
        html += detailRowHtml('Jam Mulai', escapeHtml(row.jam_mulai || '-'));
        html += detailRowHtml('Jam Selesai', escapeHtml(row.jam_selesai || '-'));
        html += detailRowHtml('Durasi Timer', parseInt(row.durasi_timer || 0, 10) + ' menit');
        html += detailRowHtml('Status', statusBadge(row.status_aktif));
        html += '</tbody></table></div>';
        $('#detail_sesi_body').html(html);
        $('#modalDetailSesi').modal('show');
    }

    function ubahStatusSesi(id, status) {
        const teks = status === '1' ? 'mengaktifkan' : 'menonaktifkan';
        Swal.fire({
            icon: 'question',
            title: status === '1' ? 'Aktifkan Sesi Soal' : 'Nonaktifkan Sesi Soal',
            text: `Apakah Anda yakin ingin ${teks} sesi soal ini?`,
            showCancelButton: true,
            confirmButtonText: status === '1' ? 'Aktifkan' : 'Nonaktifkan',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (!result.isConfirmed) return;
            $.ajax({
                url: BASE_SESI + '/ubah_status',
                type: 'POST',
                dataType: 'json',
                data: { id: id, status_aktif: status },
                success: function (res) {
                    if (res.status) {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Status berhasil diubah.' });
                        loadSesi();
                    } else {
                        Swal.fire({ icon: 'warning', title: 'Perhatian', text: res.message || 'Status gagal diubah.' });
                    }
                },
                error: function () {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan koneksi.' });
                }
            });
        });
    }


    function hapusSesi(id) {
        const row = sesiCache[id];
        if (!row) return;
        if (String(row.bisa_hapus || '0') !== '1') {
            Swal.fire({ icon: 'warning', title: 'Tidak Bisa Dihapus', text: row.alasan_hapus || 'Sesi tidak dapat dihapus.' });
            return;
        }

        Swal.fire({
            icon: 'question',
            title: 'Hapus Sesi Soal',
            html: `Apakah Anda yakin ingin menghapus sesi <b>${escapeHtml(row.nama_sesi || '-')}</b>?<br><small>Sesi hanya bisa dihapus sebelum tanggal mulai dan sebelum dikerjakan siswa.</small>`,
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (!result.isConfirmed) return;
            $.ajax({
                url: BASE_SESI + '/hapus',
                type: 'POST',
                dataType: 'json',
                data: { id: id },
                success: function (res) {
                    if (res.status) {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Sesi berhasil dihapus.' });
                        loadSesi();
                    } else {
                        Swal.fire({ icon: 'warning', title: 'Perhatian', text: res.message || 'Sesi gagal dihapus.' });
                    }
                },
                error: function () {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan koneksi.' });
                }
            });
        });
    }

    function paging($selector, jumlah_tampil = 10) {
        if (typeof Pagination === 'undefined') {
            return;
        }
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

    $(document).ready(function () {
        loadSesi();
        $('#dt-length-0').on('change', function () {
            paging($('#data_sesi .card-mapel'), parseInt($(this).val()));
        });
        $('#formTambahSesi [name="id_mata_pelajaran"], #formTambahSesi [name="id_kategori_soal"]').on('change', function () { loadNaskahOptions('#formTambahSesi'); });
        $('#formEditSesi [name="id_mata_pelajaran"], #formEditSesi [name="id_kategori_soal"]').on('change', function () { loadNaskahOptions('#formEditSesi'); });
        loadNaskahOptions('#formTambahSesi');
        loadNaskahOptions('#formEditSesi');
    });
</script>
