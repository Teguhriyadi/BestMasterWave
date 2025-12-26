<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Preview Import Income</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="card shadow-sm">
            <div class="card-body">

                <h4 class="mb-3">Preview Data Income</h4>

                <p class="text-muted mb-4">
                    Berikut adalah preview data hasil import Excel
                </p>

                @php
                    if (isset($result)) {
                        $rows = collect($result)->first()['rows'] ?? [];
                        $isDb = false;
                    }

                    if (isset($rows) && isset($upload)) {
                        $isDb = true;
                    }
                @endphp

                @if (empty($rows))
                    <div class="alert alert-warning">
                        Tidak ada data untuk ditampilkan
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    @foreach (array_keys($rows[0] ?? []) as $header)
                                        <th>{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($rows as $row)
                                    <tr>
                                        @foreach ($row as $value)
                                            <td>
                                                @if (is_int($value) || is_float($value))
                                                    <b>Convert To Int :</b> {{ $value }}<br>
                                                    <b>Number Format :</b> {{ number_format($value, 0, ',', '.') }}
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @endif

                @if (isset($isDb) && $isDb === true)
                    @php
                        $totalPage = (int) ceil($total / $perPage);
                        $range = 2;
                    @endphp

                    <div class="row align-items-center mt-4">
                        <div class="col-md-6">
                            <nav>
                                <ul class="pagination mb-0">

                                    <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ request()->url() }}?page={{ $currentPage - 1 }}">
                                            &laquo;
                                        </a>
                                    </li>

                                    <li class="page-item {{ $currentPage == 1 ? 'active' : '' }}">
                                        <a class="page-link" href="{{ request()->url() }}?page=1">1</a>
                                    </li>

                                    @if ($currentPage > $range + 2)
                                        <li class="page-item disabled">
                                            <span class="page-link">…</span>
                                        </li>
                                    @endif

                                    @for ($i = max(2, $currentPage - $range); $i <= min($totalPage - 1, $currentPage + $range); $i++)
                                        <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                            <a class="page-link"
                                                href="{{ request()->url() }}?page={{ $i }}">
                                                {{ $i }}
                                            </a>
                                        </li>
                                    @endfor

                                    @if ($currentPage < $totalPage - $range - 1)
                                        <li class="page-item disabled">
                                            <span class="page-link">…</span>
                                        </li>
                                    @endif

                                    @if ($totalPage > 1)
                                        <li class="page-item {{ $currentPage == $totalPage ? 'active' : '' }}">
                                            <a class="page-link"
                                                href="{{ request()->url() }}?page={{ $totalPage }}">
                                                {{ $totalPage }}
                                            </a>
                                        </li>
                                    @endif

                                    <li class="page-item {{ $currentPage >= $totalPage ? 'disabled' : '' }}">
                                        <a class="page-link"
                                            href="{{ request()->url() }}?page={{ $currentPage + 1 }}">
                                            &raquo;
                                        </a>
                                    </li>

                                </ul>
                            </nav>
                        </div>

                        <div class="col-md-6 text-md-end text-muted">
                            Menampilkan
                            <b>{{ ($currentPage - 1) * $perPage + 1 }}</b>
                            –
                            <b>{{ min($currentPage * $perPage, $total) }}</b>
                            dari
                            <b>{{ number_format($total) }}</b> data
                        </div>
                    </div>
                @endif

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ url('/admin-panel') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>

            </div>
        </div>

    </div>

</body>

</html>
