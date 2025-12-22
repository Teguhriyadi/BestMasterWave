<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Preview Import Income</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="card mb-4">
        <div class="card-body">
            <table class="table table-sm">
                <tr>
                    <th>Seller</th>
                    <td>{{ $file->seller->nama }}</td>
                </tr>
                <tr>
                    <th>Platform</th>
                    <td>{{ $file->seller->platform->nama }}</td>
                </tr>
                <tr>
                    <th>Periode</th>
                    <td>{{ $file->from_date }} s/d {{ $file->to_date }}</td>
                </tr>
                <tr>
                    <th>Total Baris</th>
                    <td>{{ number_format($file->total_rows) }}</td>
                </tr>
                <tr>
                    <th>Jumlah Chunk</th>
                    <td>{{ $chunkCount }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($file->validated_at)
                            <span class="badge bg-success">VALID</span>
                        @else
                            <span class="badge bg-warning">BELUM VALID</span>
                        @endif
                    </td>
                </tr>
            </table>

            @if(!$file->validated_at)
                <form method="POST"
                      action="{{ url('/admin-panel/pendapatan/'.$file->id.'/validate') }}">
                    @csrf
                    <button class="btn btn-success">
                        ✔ Tandai Valid
                    </button>
                </form>
            @endif
        </div>
    </div>

    <h5>Preview Data (20 baris pertama)</h5>

    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    @foreach($headers as $h)
                        <th>{{ $h }}</th>
                    @endforeach
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        @foreach($headers as $h)
                            <td>{{ $row[$h] ?? '-' }}</td>
                        @endforeach
                        <td>{{ $row['_date'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    </div>

</body>

</html>
