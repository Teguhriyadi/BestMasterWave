<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Pendapatan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .income-card {
            border-radius: 12px;
            transition: box-shadow .2s;
        }

        .income-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
        }

        .toggle-btn {
            cursor: pointer;
            font-size: 18px;
            transition: transform .2s;
        }

        .toggle-btn.rotate {
            transform: rotate(180deg);
        }

        .label {
            font-size: 13px;
            color: #6c757d;
        }

        .value {
            font-weight: 600;
        }
    </style>
</head>

<body class="bg-light">

<div class="container py-5">

    <h4 class="mb-4 fw-bold">📊 Kelola Pendapatan Shopee</h4>

    @foreach ($kelola as $i => $row)
        <div class="card income-card mb-3">
            <div class="card-body">

                {{-- HEADER CARD --}}
                <div class="d-flex justify-content-between align-items-center"
                     data-bs-toggle="collapse"
                     data-bs-target="#detail-{{ $i }}"
                     role="button">

                    <div>
                        <div class="fw-bold">
                            No Pesanan: {{ $row->no_pesanan }}
                        </div>
                        <div class="text-muted small">
                            {{ $row->username }} • {{ $row->nama_seller ?? '-' }}
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="fw-bold text-success">
                            Rp {{ number_format($row->total_penghasilan) }}
                        </div>
                        <div class="toggle-btn" id="icon-{{ $i }}">▼</div>
                    </div>
                </div>

                {{-- DETAIL --}}
                <div class="collapse mt-4" id="detail-{{ $i }}">
                    <div class="row g-3">

                        <div class="col-md-3">
                            <div class="label">Tanggal Pesanan</div>
                            <div class="value">{{ $row->waktu_pesanan }}</div>
                        </div>

                        <div class="col-md-3">
                            <div class="label">Tanggal Dana</div>
                            <div class="value">{{ $row->tanggal_dana_dilepaskan }}</div>
                        </div>

                        <div class="col-md-3">
                            <div class="label">Metode Pembayaran</div>
                            <div class="value">{{ $row->metode_pembayaran }}</div>
                        </div>

                        <div class="col-md-3">
                            <div class="label">Jasa Kirim</div>
                            <div class="value">{{ $row->jasa_kirim }} - {{ $row->nama_kurir }}</div>
                        </div>

                        <hr>

                        <div class="col-md-3">
                            <div class="label">Harga Asli</div>
                            <div class="value">Rp {{ number_format($row->harga_asli) }}</div>
                        </div>

                        <div class="col-md-3">
                            <div class="label">Total Diskon</div>
                            <div class="value text-danger">
                                Rp {{ number_format($row->total_diskon) }}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="label">Gratis Ongkir</div>
                            <div class="value">
                                Rp {{ number_format($row->gratis_ongkir) }}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="label">Biaya Layanan</div>
                            <div class="value text-danger">
                                Rp {{ number_format($row->biaya_layanan) }}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="label">Biaya Administrasi</div>
                            <div class="value text-danger">
                                Rp {{ number_format($row->biaya_administrasi) }}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="label">Komisi AMS</div>
                            <div class="value text-danger">
                                Rp {{ number_format($row->biaya_komisi_ams) }}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="label">Cashback Koin</div>
                            <div class="value">
                                Rp {{ number_format($row->cashback_koin) }}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="label">Total Penghasilan</div>
                            <div class="value text-success fs-5">
                                Rp {{ number_format($row->total_penghasilan) }}
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    @endforeach

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach((el, index) => {
        el.addEventListener('click', () => {
            const icon = document.getElementById('icon-' + index);
            icon.classList.toggle('rotate');
        });
    });
</script>

</body>
</html>
