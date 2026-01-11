<div class="modal-body">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th>Deskripsi</th>
                <th>Dibuat Oleh</th>
                <th class="text-center">Tanggal Dibuat</th>
                <th class="text-center">Tanggal Diubah</th>
            </tr>
        </thead>
        <tbody>
            @php
                $nomer = 0;
            @endphp
            @foreach ($log as $item)
                <tr>
                    <td class="text-center">{{ ++$nomer }}.</td>
                    <td>{{ $item['deskripsi'] }}</td>
                    <td>{{ $item['dibuat_oleh'] }}</td>
                    <td class="text-center">{{ $item['dibuat_tanggal'] }}</td>
                    <td class="text-center">{{ $item['diubah_tanggal'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
