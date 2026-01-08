<!doctype html>
<html lang="en">

<head>
    <title>Login Admin Panel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="{{ asset('templating/login/css/style.css') }}">

</head>

<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <h2 class="heading-section">
                        Login Admin Panel
                    </h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-6">
                    <div class="wrap">
                        <div class="login-wrap p-4 p-lg-5 w-100">
                            @if (session("error"))
                                <div class="alert alert-danger">
                                    <strong>Gagal,</strong> {{ session('error') }}
                                </div>
                            @elseif (session("success"))
                                <div class="alert alert-success">
                                    <strong>Berhasil,</strong> {{ session('success') }}
                                </div>
                            @endif
                            <div class="d-flex">
                                <div class="w-100">
                                    <h3 class="mb-4">
                                        Silahkan Login Terlebih Dahulu
                                    </h3>
                                </div>
                            </div>
                            <form action="{{ url('/login') }}" method="POST" class="signin-form">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="label" for="username">
                                        Username
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" placeholder="Masukkan Username" id="username" value="{{ old('username') }}">
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label class="label" for="password">
                                        Password
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Masukkan Password" id="password">
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="divisi_id" class="divisi_id"> Nama Divisi </label>
                                    <select name="divisi_id" class="form-control" id="divisi_id">
                                        <option value="">- Pilih Divisi -</option>
                                        @foreach ($divisi as $item)
                                            <option value="{{ $item['id'] }}">
                                                {{ $item['nama_divisi'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="form-control btn btn-primary submit px-3">
                                        Masuk
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('templating/login/js/jquery.min.js') }}"></script>
    <script src="{{ asset('templating/login/js/popper.js') }}"></script>
    <script src="{{ asset('templating/login/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('templating/login/js/main.js') }}"></script>

</body>

</html>
