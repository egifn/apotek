<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h4 class="mb-0">Akses Ditolak</h4>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-ban fa-3x text-danger"></i>
                        </div>
                        <h5>Anda tidak memiliki akses ke halaman ini</h5>
                        <p class="text-muted">Silakan hubungi administrator untuk mendapatkan akses.</p>

                        @if (session('error'))
                            <div class="alert alert-warning">
                                {{ session('error') }}
                            </div>
                        @endif

                        <a href="{{ url()->previous() }}" class="btn btn-primary">Kembali</a>
                        <a href="{{ route('home') }}" class="btn btn-secondary">Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
