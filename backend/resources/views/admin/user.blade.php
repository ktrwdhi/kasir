
@extends('template.admin')

@section('title', 'Users')

@section('page-title', 'Data Users')

@section('content')

<div class="container-fluid">

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Statistik --}}
    <div class="row g-4 mb-4">

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <p class="text-muted mb-1">Total Users</p>
                            <h3 class="fw-bold mb-0">
                                {{ number_format($totalUsers) }}
                            </h3>
                        </div>

                        <div class="text-primary fs-1">
                            <i class="bi bi-people"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <p class="text-muted mb-1">Outlet</p>

                            <h3 class="fw-bold mb-0">
                                @if(Auth::user()->role === 'owner')
                                    Semua Outlet
                                @else
                                    {{ Auth::user()->outlet->name ?? '-' }}
                                @endif
                            </h3>
                        </div>

                        <div class="text-success fs-1">
                            <i class="bi bi-shop"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Card Users --}}
    <div class="card border-0 shadow-sm">

        {{-- Header --}}
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-people me-2"></i>
                        Data Users
                    </h5>

                    <small class="text-muted">
                        Daftar pengguna berdasarkan outlet
                    </small>
                </div>

                <button type="button"
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#addUserModal">

                    <i class="bi bi-person-plus me-1"></i>
                    Tambah User

                </button>

            </div>
        </div>

        <div class="card-body">

            {{-- Filter --}}
            <form method="GET"
                  action="{{ route('admin.users.index') }}"
                  class="row g-3 mb-4">

                {{-- Pencarian --}}
                <div class="{{ Auth::user()->role === 'owner' ? 'col-md-3' : 'col-md-4' }}">

                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Cari nama atau username..."
                           value="{{ request('search') }}">

                </div>

                {{-- Filter Outlet khusus Owner --}}
                @if(Auth::user()->role === 'owner')

                    <div class="col-md-3">

                        <select name="outlet_id" class="form-select">

                            <option value="">Semua Outlet</option>

                            @foreach($outlets as $outlet)
                                <option value="{{ $outlet->id }}"
                                    {{ request('outlet_id') == $outlet->id ? 'selected' : '' }}>

                                    {{ $outlet->name }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                @endif

                {{-- Filter Role --}}
                <div class="{{ Auth::user()->role === 'owner' ? 'col-md-2' : 'col-md-3' }}">

                    <select name="role" class="form-select">

                        <option value="">Semua Role</option>

                        <option value="owner"
                            {{ request('role') == 'owner' ? 'selected' : '' }}>
                            Owner
                        </option>

                        <option value="admin"
                            {{ request('role') == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="kasir"
                            {{ request('role') == 'kasir' ? 'selected' : '' }}>
                            Kasir
                        </option>

                    </select>

                </div>

                {{-- Tombol --}}
                <div class="col-md-2 d-flex gap-2">

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i>
                        Cari
                    </button>

                    <a href="{{ route('admin.users.index') }}"
                       class="btn btn-secondary">

                        <i class="bi bi-arrow-counterclockwise"></i>

                    </a>

                </div>

            </form>

            {{-- Tabel Users --}}
            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Outlet</th>
                            <th>Role</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($users as $user)

                            <tr>

                                {{-- Nomor --}}
                                <td>
                                    {{ $users->firstItem() + $loop->index }}
                                </td>

                                {{-- Nama --}}
                                <td>
                                    <div class="d-flex align-items-center">

                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary
                                                    d-flex justify-content-center align-items-center me-2"
                                             style="width: 38px; height: 38px;">

                                            <i class="bi bi-person"></i>

                                        </div>

                                        <span class="fw-semibold">
                                            {{ $user->name }}
                                        </span>

                                    </div>
                                </td>

                                {{-- Username --}}
                                <td>
                                    {{ $user->username ?? '-' }}
                                </td>

                                {{-- Outlet --}}
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $user->outlet->name ?? '-' }}
                                    </span>
                                </td>

                                {{-- Role --}}
                                <td>

                                    @if($user->role === 'owner')

                                        <span class="badge bg-danger">
                                            Owner
                                        </span>

                                    @elseif($user->role === 'admin')

                                        <span class="badge bg-primary">
                                            Admin
                                        </span>

                                    @elseif($user->role === 'kasir')

                                        <span class="badge bg-success">
                                            Kasir
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ ucfirst($user->role) }}
                                        </span>

                                    @endif

                                </td>

                                {{-- Tanggal --}}
                                <td>
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>

                                {{-- Aksi --}}
                                <td>

                                    @if(
                                        Auth::id() !== $user->id &&
                                        $user->role !== 'owner' &&
                                        (
                                            Auth::user()->role === 'owner' ||
                                            (
                                                Auth::user()->role === 'admin' &&
                                                $user->role === 'kasir' &&
                                                (int) $user->outlet_id === (int) Auth::user()->outlet_id
                                            )
                                        )
                                    )

                                        <form action="{{ route('admin.users.destroy', $user->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger">

                                                <i class="bi bi-trash"></i>
                                                Hapus

                                            </button>

                                        </form>

                                    @else

                                        <span class="text-muted">-</span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center py-5">

                                    <i class="bi bi-people fs-1 text-muted"></i>

                                    <h6 class="mt-3 text-muted">
                                        User tidak ditemukan
                                    </h6>

                                    <p class="text-muted mb-0">
                                        Tidak ada data pengguna pada filter ini.
                                    </p>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3">

                <small class="text-muted">

                    Menampilkan
                    {{ $users->firstItem() ?? 0 }}
                    -
                    {{ $users->lastItem() ?? 0 }}

                    dari
                    {{ $users->total() }}
                    users

                </small>

                <div class="mt-3 product-pagination">
                    {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
                </div>

            </div>

        </div>

    </div>

</div>

{{-- Modal Tambah User --}}
<div class="modal fade"
     id="addUserModal"
     tabindex="-1"
     aria-labelledby="addUserModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form action="{{ route('admin.users.store') }}"
                  method="POST">

                @csrf

                {{-- Header Modal --}}
                <div class="modal-header">

                    <h5 class="modal-title fw-bold"
                        id="addUserModalLabel">

                        <i class="bi bi-person-plus me-2"></i>
                        Tambah User

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                    </button>

                </div>

                {{-- Body Modal --}}
                <div class="modal-body">

                    {{-- Email --}}
                    <div class="mb-3">

                        <label for="email" class="form-label fw-semibold">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control"
                               placeholder="Masukkan email aktif"
                               value="{{ old('email') }}"
                               required>

                        <small class="text-muted">
                            Username dan password akan dikirim ke email user.
                        </small>

                    </div>

                    {{-- Role --}}
                    <div class="mb-3">

                        <label for="role" class="form-label fw-semibold">
                            Role
                        </label>

                        <select name="role"
                                id="role"
                                class="form-select"
                                required>

                            <option value="">Pilih Role</option>

                            <option value="admin"
                                {{ old('role') == 'admin' ? 'selected' : '' }}>
                                Admin
                            </option>

                            <option value="kasir"
                                {{ old('role') == 'kasir' ? 'selected' : '' }}>
                                Kasir
                            </option>

                        </select>

                    </div>

                    {{-- Outlet khusus Owner --}}
                    @if(Auth::user()->role === 'owner')

                        <div class="mb-3">

                            <label for="outlet_id" class="form-label fw-semibold">
                                Outlet
                            </label>

                            <select name="outlet_id"
                                    id="outlet_id"
                                    class="form-select"
                                    required>

                                <option value="">Pilih Outlet</option>

                                @foreach($outlets as $outlet)

                                    <option value="{{ $outlet->id }}"
                                        {{ old('outlet_id') == $outlet->id ? 'selected' : '' }}>

                                        {{ $outlet->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    @endif

                </div>

                {{-- Footer Modal --}}
                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-save me-1"></i>
                        Simpan User

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection