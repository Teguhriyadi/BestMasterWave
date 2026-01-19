<form action="{{ url('/admin-panel/ketidakhadiran/' . $edit['id'] . '/ubah-status') }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="form-group">
            <label for="status_approval" class="form-label">Status</label>
            <select name="status" class="form-control" id="status_approval">
                <option value="">- Pilih -</option>
                <option value="Ditolak" {{ $edit['status_approval'] == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                <option value="Disetujui" {{ $edit['status_approval'] == 'Disetujui' ? 'selected' : '' }}>Disetujui
                </option>
            </select>
        </div>

        <div class="form-group" id="wrapper-alasan-approval" style="display: none">
            <label for="alasan_approval" class="form-label">Alasan</label>
            <textarea name="alasan" class="form-control" id="alasan_approval" rows="5" placeholder="Masukkan Alasan">{{ old('alasan', $edit['alasan']) }}</textarea>
        </div>

    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-secondary btn-sm">
            <i class="fa fa-times"></i> Batalkan
        </button>
        <button type="submit" class="btn btn-success btn-sm">
            <i class="fa fa-save"></i> Simpan
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        function toggleAlasan() {
            var status = $('#status_approval').val();
            if (status === 'Ditolak') {
                $('#wrapper-alasan-approval').show();
                $('#alasan_approval').attr('required', true);
            } else {
                $('#wrapper-alasan-approval').hide();
                $('#alasan_approval').attr('required', false);
            }
        }

        // Jalankan saat modal terbuka
        toggleAlasan();

        // Jalankan saat dropdown berubah
        $('#status_approval').on('change', function() {
            toggleAlasan();
        });
    });
</script>
