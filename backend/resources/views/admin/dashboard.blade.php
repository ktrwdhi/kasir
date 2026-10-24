@extends('template.admin')

@section('title', 'Dashboard Admin')

@section('page-title', 'Dashboard')

@section('content')



<div class="row g-4">

    {{-- Total Produk --}}
    <div class="col-md-4">

        <div class="card stat-card">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <p class="text-muted mb-2">
                        Total Produk
                    </p>

                    <h2 class="fw-bold mb-0">
                        {{ $totalProducts }}
                    </h2>

                </div>

                <div class="stat-icon">

                    <i class="bi bi-box-seam"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- Total Stock --}}
    <div class="col-md-4">

        <div class="card stat-card">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <p class="text-muted mb-2">
                        Total Stock
                    </p>

                    <h2 class="fw-bold mb-0">
                        {{ $totalStock }}
                    </h2>

                </div>

                <div class="stat-icon">

                    <i class="bi bi-stack"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- Low Stock --}}
    <div class="col-md-4">

        <div class="card stat-card">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <p class="text-muted mb-2">
                        Stock Menipis
                    </p>

                    <h2 class="fw-bold mb-0">
                        {{ $lowStock }}
                    </h2>

                </div>

                <div class="stat-icon">

                    <i class="bi bi-exclamation-triangle"></i>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="card mt-4">

    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h5 class="fw-bold mb-1">
                    Kelola Produk
                </h5>

                <p class="text-muted mb-0">
                    Tambahkan dan kelola produk kasir.
                </p>

            </div>

            <a
                href="{{ route('admin.products.index') }}"
                class="btn btn-primary"
            >
                <i class="bi bi-box-seam me-1"></i>
                Kelola Produk
            </a>

        </div>

    </div>

</div>

<div class="card mt-4">

    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h5 class="fw-bold mb-1">
                    Produk Baru Terjual
                </h5>

                <p class="text-muted mb-0">
                    {{ $transactionCount}} produk yang baru saja terjual.
                </p>

            </div>

            <a
                href="/admin/products"
                class="btn btn-light"
            >
                Lihat Semua
            </a>

        </div>


        <div class="table-responsive">

            <table class="table mb-0">

                <thead>

                    <tr>

                        <th>
                            Produk
                        </th>

                        <th>
                            Qty
                        </th>

                        <th>
                            Harga
                        </th>

                        <th>
                            Total
                        </th>

                        <th>
                            Waktu
                        </th>
                        <th>
                            Outlet
                        </th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($recentSales as $sale)

                        <tr>

                            <td>

                                <div class="fw-semibold">

                                    {{ $sale->product->name ?? 'Produk dihapus' }}

                                </div>

                            </td>


                            <td>

                                {{ $sale->quantity }}

                            </td>


                            <td>

                                Rp
                                {{ number_format($sale->price, 0, ',', '.') }}

                            </td>


                            <td>

                                <span class="fw-semibold">

                                    Rp
                                    {{ number_format($sale->price * $sale->quantity, 0, ',', '.') }}

                                </span>

                            </td>


                            <td>

                                <span class="text-muted">

                                    {{ $sale->created_at->format('d M Y, H:i') }}

                                </span>

                            </td>
                            <td>
                                {{ $sale->sale->outlet->name }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-4"
                            >

                                <i class="bi bi-receipt fs-2 text-muted"></i>

                                <p class="text-muted mb-0 mt-2">
                                    Belum ada produk yang terjual.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
@endsection