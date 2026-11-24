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
                    <p class="text-muted mb-2">Total Produk</p>
                    <h2 class="fw-bold mb-0">{{ $totalProducts }}</h2>
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
                    <p class="text-muted mb-2">Total Stock</p>
                    <h2 class="fw-bold mb-0">{{ $totalStock }}</h2>
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
                    <p class="text-muted mb-2">Stock Menipis</p>
                    <h2 class="fw-bold mb-0">{{ $lowStock }}</h2>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- BARIS GRAFIK 1: TREN PENJUALAN & TRANSAKSI PER OUTLET --}}
<div class="row g-4 mt-1">

    {{-- Grafik Tren Penjualan Harian --}}
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h5 class="fw-bold mb-1">Tren Penjualan Harian</h5>
                    <p class="text-muted mb-0">Akumulasi nilai penjualan berdasarkan tanggal.</p>
                </div>
                <div style="height: 320px;">
                    <canvas id="dailySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik Transaksi per Outlet --}}
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h5 class="fw-bold mb-1">Analisis Transaksi Outlet</h5>
                    <p class="text-muted mb-0">Perbandingan jumlah transaksi dari setiap outlet.</p>
                </div>
                <div style="height: 320px;">
                    <canvas id="outletTransactionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- BARIS GRAFIK 2: TOP PRODUK TERLARIS & KOMPOSISI STOK --}}
<div class="row g-4 mt-1">

    {{-- Grafik Top Produk Terlaris --}}
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h5 class="fw-bold mb-1">Produk Terlaris</h5>
                    <p class="text-muted mb-0">Daftar produk dengan jumlah unit terjual tertinggi.</p>
                </div>
                <div style="height: 320px;">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik Komposisi Stok --}}
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h5 class="fw-bold mb-1">Distribusi Kondisi Stok</h5>
                    <p class="text-muted mb-0">Ringkasan stok aman, menipis, dan habis.</p>
                </div>
                <div style="height: 320px;">
                    <canvas id="stockStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- KELOLA PRODUK --}}
<div class="card mt-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-1">Kelola Produk</h5>
                <p class="text-muted mb-0">Tambahkan dan kelola produk kasir.</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-primary">
                <i class="bi bi-box-seam me-1"></i>
                Kelola Produk
            </a>
        </div>
    </div>
</div>

{{-- PRODUK BARU TERJUAL --}}
<div class="card mt-4">
    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1">Produk Baru Terjual</h5>
                <p class="text-muted mb-0">{{ $transactionCount }} produk yang baru saja terjual.</p>
            </div>
            <a href="/admin/products" class="btn btn-light">Lihat Semua</a>
        </div>

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Total</th>
                        <th>Waktu</th>
                        <th>Outlet</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSales as $sale)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $sale->product->name ?? 'Produk dihapus' }}</div>
                            </td>
                            <td>{{ $sale->quantity }}</td>
                            <td>Rp {{ number_format($sale->price, 0, ',', '.') }}</td>
                            <td>
                                <span class="fw-semibold">
                                    Rp {{ number_format($sale->price * $sale->quantity, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $sale->created_at->format('d M Y, H:i') }}</span>
                            </td>
                            <td>{{ $sale->sale->outlet->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            {{-- Diperbaiki dari colspan="5" menjadi colspan="6" sesuai jumlah kolom --}}
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-receipt fs-2 text-muted"></i>
                                <p class="text-muted mb-0 mt-2">Belum ada produk yang terjual.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- CHART JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // 1. Grafik Transaksi per Outlet (Horizontal Bar)
    const outletLabels = @json($transactionsByOutlet->pluck('outlet_name'));
    const outletTransactions = @json($transactionsByOutlet->pluck('total_transactions'));

    new Chart(document.getElementById('outletTransactionChart'), {
        type: 'bar',
        data: {
            labels: outletLabels,
            datasets: [{
                label: 'Jumlah Transaksi',
                data: outletTransactions,
                backgroundColor: ['#19387c', '#258cec', '#4da6ff', '#72bdff', '#a3d5ff'],
                borderRadius: 6,
                barThickness: 32
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => context.parsed.x + ' Transaksi'
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    title: { display: true, text: 'Jumlah Transaksi' }
                },
                y: {
                    title: { display: true, text: 'Nama Outlet' }
                }
            }
        }
    });

    // 2. Grafik Tren Penjualan Harian (Line Chart)
    const dailyLabels = @json($dailySales->pluck('date'));
    const dailyTotals = @json($dailySales->pluck('total'));

    new Chart(document.getElementById('dailySalesChart'), {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: dailyTotals,
                borderColor: '#19387c',
                backgroundColor: 'rgba(37, 140, 236, 0.15)',
                fill: true,
                tension: 0.35,
                pointRadius: 4,
                pointBackgroundColor: '#258cec'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => 'Rp ' + Number(context.parsed.y).toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => 'Rp ' + Number(value).toLocaleString('id-ID')
                    }
                }
            }
        }
    });

    // 3. Grafik Top Produk Terlaris (Vertical Bar)
    const topProductLabels = @json($topProducts->pluck('product_name'));
    const topProductQty = @json($topProducts->pluck('total_qty'));

    new Chart(document.getElementById('topProductsChart'), {
        type: 'bar',
        data: {
            labels: topProductLabels,
            datasets: [{
                label: 'Unit Terjual',
                data: topProductQty,
                backgroundColor: '#258cec',
                borderRadius: 6,
                maxBarThickness: 45
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => context.parsed.y + ' unit'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    title: { display: true, text: 'Jumlah Terjual' }
                }
            }
        }
    });

    // 4. Grafik Distribusi Kondisi Stok (Doughnut)
    const stockStatusData = @json($stockStatus); // urutan: [Aman, Menipis, Habis]

    new Chart(document.getElementById('stockStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Stok Aman', 'Stok Menipis', 'Stok Habis'],
            datasets: [{
                data: stockStatusData,
                backgroundColor: ['#198754', '#ffc107', '#dc3545'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endsection