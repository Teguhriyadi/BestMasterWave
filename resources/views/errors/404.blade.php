<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 | Halaman Tidak Ditemukan</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="d-flex align-items-center justify-content-center" style="min-height: 75vh">
        <div class="text-center">

            <div class="mb-4">
                <i class="fa fa-exclamation-triangle text-warning" style="font-size: 80px;"></i>
            </div>

            <h1 class="display-1 fw-bold text-warning mb-0">
                404
            </h1>

            <h2 class="fw-bold mt-3 mb-2">
                Halaman Tidak Ditemukan
            </h2>

            <p class="text-muted fs-5 mb-4">
                Maaf, halaman yang Anda cari tidak tersedia atau sudah dipindahkan.
            </p>

            <div class="d-flex justify-content-center">
                <a href="{{ url('/admin-panel/dashboard') }}" class="btn btn-outline-primary">
                    <i class="fa fa-home mr-2"></i> Kembali ke Dashboard
                </a>
            </div>

        </div>
    </div>

</body>

</html>
