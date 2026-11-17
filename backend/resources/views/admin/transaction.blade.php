
@extends('template.admin')

@section('title', 'Transaksi')

@section('page-title', 'Data Transaksi')

@section('content')

<div class="container-fluid">

    {{-- Statistik --}}
    <div class="row g-4 mb-4">

        {{-- Total Transaksi --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <p class="text-muted mb-1">Total Transaksi</p>

                            <h3 class="fw-bold mb-0">
                                {{ number_format($totalTransaksi) }}
                            </h3>
                        </div>

                        <div class="text-primary fs-1">
                            <i class="bi bi-receipt"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Total Pendapatan --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <p class="text-muted mb-1">Total Pendapatan</p>

                            <h3 class="fw-bold text-success mb-0">
                                Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
                            </h3>
                        </div>

                        <div class="text-success fs-1">
                            <i class="bi bi-wallet2"></i>
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

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-receipt me-2"></i>
                        Riwayat Transaksi
                    </h5>

                    <small class="text-muted">
                        Data transaksi penjualan
                    </small>
                </div>

            </div>

        </div>

        <div class="card-body">

            {{-- Filter --}}
            <form method="GET"
                  action="{{ route('admin.transactions.index') }}"
                  class="row g-3 mb-4">

                {{-- Cari Invoice --}}
                <div class="{{ Auth::user()->role === 'owner' ? 'col-md-3' : 'col-md-4' }}">

                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Cari invoice..."
                           value="{{ request('search') }}">

                </div>

                {{-- Filter Outlet khusus Owner --}}
                @if(Auth::user()->role === 'owner')

                    <div class="col-md-3">

                        <select name="outlet_id" class="form-select">

                            <option value="">
                                Semua Outlet
                            </option>

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
                <div class="{{ Auth::user()->role === 'owner' ? 'col-md-2' : 'col-md-3' }}">

                    <select name="payment" class="form-select">

                        <option value="">
                            Semua Pembayaran
                        </option>

                        <option value="cash"
                            {{ request('payment') == 'cash' ? 'selected' : '' }}>
                            Cash
                        </option>

                        <option value="qris"
                            {{ request('payment') == 'qris' ? 'selected' : '' }}>
                            QRIS
                        </option>

                    </select>

                </div>

                {{-- Filter Tanggal --}}
                <div class="{{ Auth::user()->role === 'owner' ? 'col-md-2' : 'col-md-3' }}">

                    <input type="date"
                           name="date"
                           class="form-control"
                           value="{{ request('date') }}">

                </div>

                {{-- Tombol --}}
                <div class="col-md-2 d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary w-100">

                        <i class="bi bi-search"></i>
                        Cari

                    </button>

                    <a href="{{ route('admin.transactions.index') }}"
                       class="btn btn-secondary">

                        <i class="bi bi-arrow-counterclockwise"></i>

                    </a>

                </div>

            </form>

            {{-- Tabel Data --}}
            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>No</th>
                            <th>Invoice</th>
                            <th>Outlet</th>
                            <th>Kasir</th>
                            <th>Total</th>
                            <th>Pembayaran</th>
                            <th>Tanggal</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($transactions as $transaction)

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

                                {{-- Outlet --}}
                                <td>

                                    <span class="badge bg-light text-dark border">

                                        {{ $transaction->outlet->name ?? '-' }}

                                    </span>

                                </td>

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

                                    @if(strtolower($transaction->payment) == 'qris')

                                        <span class="badge bg-primary">
                                            <i class="bi bi-qr-code me-1"></i>
                                            QRIS
                                        </span>

                                    @elseif(strtolower($transaction->payment) == 'cash')

                                        <span class="badge bg-success">
                                            <i class="bi bi-cash me-1"></i>
                                            Cash
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ $transaction->payment ?? '-' }}
                                        </span>

                                    @endif

                                </td>

                                {{-- Tanggal --}}
                                <td>

                                    <div>
                                        {{ $transaction->created_at->format('d/m/Y') }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $transaction->created_at->format('H:i') }}
                                    </small>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center py-5">

                                    <i class="bi bi-receipt fs-1 text-muted"></i>

                                    <h6 class="mt-3 text-muted">
                                        Belum ada transaksi
                                    </h6>

                                    <p class="text-muted mb-0">
                                        Data transaksi belum tersedia.
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
