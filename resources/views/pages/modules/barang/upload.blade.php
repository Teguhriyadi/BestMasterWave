<form action="{{ url('/admin-panel/barang/upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Import Excel</button>
</form>
