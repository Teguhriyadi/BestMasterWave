@extends('pages.layouts.app')

@push('content_app')
    <div class="d-flex align-items-center justify-content-center" style="min-height: 75vh">
        <div class="text-center">

            <div class="mb-4">
                <i class="fa fa-ban text-danger" style="font-size: 80px;"></i>
            </div>

            <h1 class="display-1 fw-bold text-danger mb-0">
                403
            </h1>

            <h2 class="fw-bold mt-3 mb-2">
                Akses Ditolak
            </h2>

            <p class="text-muted fs-5 mb-4">
                Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
            </p>

            <div class="d-flex justify-content-center gap-3">
                <a href="{{ url('/admin-panel/dashboard') }}" class="btn btn-outline-primary">
                    <i class="fa fa-sign-out-alt me-2"></i> Kembali
                </a>
            </div>

        </div>
    </div>
@endpush
