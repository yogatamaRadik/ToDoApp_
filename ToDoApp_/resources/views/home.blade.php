<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyToDoApp</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <!-- =========================
         NAVBAR
    ========================== -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">
                📝 MyToDo
            </a>

            <!-- Toggler (HP) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ !request()->has('view') ? 'active fw-semibold' : '' }}"
                            href="{{ route('home') }}">
                            <i class="bi bi-house-door"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->get('view') === 'lihat' ? 'active fw-semibold' : '' }}"
                            href="{{ route('home', ['view' => 'lihat']) }}">
                            <i class="bi bi-list-check"></i> Lihat Tugas
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- =========================
         MAIN CONTENT
    ========================== -->
    <div class="container py-5 mt-5">

        <!-- Alert Success -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- =========================
             HALAMAN HOME
        ========================== -->
        @if (!request()->has('view'))
            <div class="card todo-card p-4 shadow-sm">
                <h2 class="text-center mb-4">Tambah Tugas Baru</h2>

                <!-- Form Tambah -->
                <form action="{{ route('add-task') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="task" class="form-control" placeholder="Judul tugas..." required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="description" class="form-control" placeholder="Deskripsi (opsional)">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Tambah</button>
                        </div>
                    </div>
                </form>

                <hr>
                <h4 class="text-center mb-3">Daftar Semua Tugas</h4>

                <ul class="list-group">
                    @forelse ($tasks as $task)
                        <li class="list-group-item d-flex justify-content-between align-items-center 
                                   {{ $task->is_done ? 'list-group-item-success' : '' }}">
                            <span class="fw-semibold">{{ $task->task }}</span>
                            <a href="{{ route('home', ['view' => 'lihat', 'id' => $task->id]) }}"
                                class="btn btn-info btn-sm">Lihat</a>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">
                            Belum ada tugas. Yuk tambahkan!
                        </li>
                    @endforelse
                </ul>
            </div>

        <!-- =========================
             HALAMAN LIHAT SEMUA TUGAS
        ========================== -->
        @elseif (request()->get('view') === 'lihat' && !request()->has('id'))
            <div class="card todo-card p-4 shadow-sm">
                <h2 class="text-center mb-4">Semua Tugas</h2>

                <ul class="list-group">
                    @forelse ($tasks as $task)
                        <li class="list-group-item d-flex justify-content-between align-items-start 
                                   {{ $task->is_done ? 'list-group-item-success' : '' }}">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">{{ $task->task }}</div>
                                {{ $task->description }}
                                <small class="text-muted d-block">
                                    Dibuat: {{ $task->created_at->format('d M Y, H:i') }}
                                </small>

                                @if ($task->completed_at)
                                    <small class="text-success d-block">
                                        Selesai: {{ $task->completed_at->format('d M Y, H:i') }}
                                    </small>
                                @endif
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('home', ['view' => 'lihat', 'id' => $task->id]) }}"
                                    class="btn btn-sm btn-info">Detail</a>
                                <form action="{{ route('delete-task', $task->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus tugas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">
                            Belum ada tugas untuk ditampilkan.
                        </li>
                    @endforelse
                </ul>
            </div>

        <!-- =========================
             HALAMAN DETAIL TUGAS
        ========================== -->
        @elseif (request()->get('view') === 'lihat' && request()->has('id'))
            @php
                $task = $tasks->where('id', request()->get('id'))->first();
            @endphp

            @if ($task)
                <div class="card todo-card p-4 shadow-sm">
                    <h3 class="text-center mb-4">{{ $task->task }}</h3>

                    <p><strong>Deskripsi:</strong> {{ $task->description ?: 'Tidak ada deskripsi' }}</p>
                    <p class="text-muted">
                        📅 Dibuat: {{ $task->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                    </p>

                    @if ($task->completed_at)
                        <p class="text-success">
                            ✅ Selesai: {{ $task->completed_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                        </p>
                    @else
                        <p class="text-danger">⏳ Belum selesai</p>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('home', ['view' => 'lihat']) }}" class="btn btn-secondary">
                            ⬅ Kembali
                        </a>

                        <div class="d-flex gap-2">
                            <form action="{{ route('update-task', $task->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="btn {{ $task->is_done ? 'btn-warning' : 'btn-success' }}">
                                    {{ $task->is_done ? 'Batal Selesai' : 'Tandai Selesai' }}
                                </button>
                            </form>

                            <form action="{{ route('delete-task', $task->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning text-center mt-4">
                    Tugas tidak ditemukan.
                    <a href="{{ route('home') }}" class="alert-link">Kembali ke Home</a>
                </div>
            @endif
        @endif
    </div>

    <!-- =========================
         FOOTER
    ========================== -->
    <footer class="mt-5 text-center text-white bg-primary py-3">
        <p class="mb-0">© {{ date('Y') }} ToDo App | by Radixixixixi_</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
