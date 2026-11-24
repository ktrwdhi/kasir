@extends('template.admin')

@section('title', 'Transaksi')

@section('page-title', 'Data Transaksi')

@section('content')

@php
    $currentUser = auth()->user();
    $isOwner = ($currentUser->role ?? null) === 'owner';
@endphp

<div class="container-fluid">

    {{-- Statistik --}}
    <div class="row g-4 mb-4">

        {{-- Total Transaksi --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Transaksi</p>
                            <h4 class="fw-bold mb-0">
                                {{ number_format($totalTransaksi, 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="text-primary fs-2">
                            <i class="bi bi-receipt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Pendapatan --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Pendapatan</p>
                            <h4 class="fw-bold text-dark mb-0">
                                Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="text-dark fs-2">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pendapatan Cash --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Pendapatan Cash</p>
                            <h4 class="fw-bold text-success mb-0">
                                Rp{{ number_format($totalCash, 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="text-success fs-2">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pendapatan QRIS --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Pendapatan QRIS</p>
                            <h4 class="fw-bold text-primary mb-0">
                                Rp{{ number_format($totalQris, 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="text-primary fs-2">
                            <i class="bi bi-qr-code-scan"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Tabel Transaksi --}}
    <div class="card border-0 shadow-sm">

        {{-- Header --}}
        <div class="card-header bg-white py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-receipt me-2"></i>
                        Riwayat Transaksi
                    </h5>
                    <small class="text-muted">
                        {{ $isOwner ? 'Data transaksi penjualan seluruh outlet' : 'Data transaksi penjualan outlet Anda' }}
                    </small>
                </div>

                @if(!$isOwner && isset($currentUser->outlet))
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                        <i class="bi bi-shop me-1"></i>{{ $currentUser->outlet->name }}
                    </span>
                @endif
            </div>
        </div>

        <div class="card-body">

            {{-- Filter --}}
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="row g-3 mb-4">

                {{-- Cari Invoice --}}
                <div class="{{ $isOwner ? 'col-md-3' : 'col-md-4' }}">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Cari invoice..."
                           value="{{ request('search') }}">
                </div>

                {{-- Filter Outlet khusus Owner --}}
                @if($isOwner)
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

                {{-- Filter Pembayaran --}}
                <div class="{{ $isOwner ? 'col-md-2' : 'col-md-3' }}">
                    <select name="payment" class="form-select">
                        <option value="">Semua Pembayaran</option>
                        <option value="cash" {{ request('payment') == 'cash' ? 'selected' : '' }}>
                            Cash
                        </option>
                        <option value="qris" {{ request('payment') == 'qris' ? 'selected' : '' }}>
                            QRIS
                        </option>
                    </select>
                </div>

                {{-- Filter Tanggal --}}
                <div class="{{ $isOwner ? 'col-md-2' : 'col-md-3' }}">
                    <input type="date"
                           name="date"
                           class="form-control"
                           value="{{ request('date') }}">
                </div>

                {{-- Tombol --}}
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Cari
                    </button>

                    @if(request()->hasAny(['search', 'outlet_id', 'payment', 'date']))
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>

            </form>

            {{-- Tabel Data --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Invoice</th>
                            @if($isOwner)
                                <th>Outlet</th>
                            @endif
                            <th>Kasir</th>
                            <th>Total</th>
                            <th>Pembayaran</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($transactions as $transaction)
                            @php
                                $paymentMethod = strtolower((string) ($transaction->payment_method ?? $transaction->payment ?? ''));
                            @endphp
                            <tr>
                                {{-- Nomor --}}
                                <td>
                                    {{ $transactions->firstItem() + $loop->index }}
                                </td>

                                {{-- Invoice --}}
                                <td>
                                    <span class="fw-bold">
                                        {{ $transaction->invoice }}
                                    </span>
                                </td>

                                {{-- Outlet (Khusus Owner) --}}
                                @if($isOwner)
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $transaction->outlet->name ?? '-' }}
                                        </span>
                                    </td>
                                @endif

                                {{-- Kasir --}}
                                <td>
                                    {{ $transaction->user->name ?? '-' }}
                                </td>

                                {{-- Total --}}
                                <td>
                                    <span class="fw-bold text-success">
                                        Rp{{ number_format($transaction->total, 0, ',', '.') }}
                                    </span>
                                </td>

                                {{-- Pembayaran --}}
                                <td>
                                    @if($paymentMethod === 'qris')
                                        <span class="badge bg-primary">
                                            <i class="bi bi-qr-code me-1"></i>QRIS
                                        </span>
                                    @elseif($paymentMethod === 'cash')
                                        <span class="badge bg-success">
                                            <i class="bi bi-cash me-1"></i>Cash
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ $transaction->payment_method ?? $transaction->payment ?? '-' }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Tanggal --}}
                                <td>
                                    <div>
                                        {{ $transaction->created_at?->format('d/m/Y') ?? '-' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $transaction->created_at?->format('H:i') ?? '' }}
                                    </small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isOwner ? 7 : 6 }}" class="text-center py-5">
                                    <i class="bi bi-receipt fs-1 text-muted"></i>
                                    <h6 class="mt-3 text-muted">
                                        Belum ada transaksi
                                    </h6>
                                    <p class="text-muted mb-0">
                                        {{ request()->hasAny(['search', 'outlet_id', 'payment', 'date'])
                                            ? 'Tidak ada transaksi yang sesuai dengan filter.'
                                            : 'Data transaksi belum tersedia.' }}
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                <small class="text-muted">
                    Menampilkan
                    {{ $transactions->firstItem() ?? 0 }}
                    -
                    {{ $transactions->lastItem() ?? 0 }}
                    dari
                    {{ $transactions->total() }}
                    transaksi
                </small>

                <div>
                    {{ $transactions->links() }}
                </div>
            </div>

        </div>

    </div>

</div>

@endsection