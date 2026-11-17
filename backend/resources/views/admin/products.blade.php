@extends('template.admin')

@php
    $availableProducts = $availableProducts ?? collect();
@endphp

@section('title', 'Produk')
@section('page-title', 'Produk')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">
            {{ isset($outlet) ? $outlet->name : 'Produk' }}
            @if(isset($outlet))
                - {{ $outlet->address }}
            @endif
        </h3>
    </div>

    <div class="d-flex flex-wrap gap-2">
        @if(isset($outlet))
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editOutletModal">
                <i class="bi bi-pencil me-1"></i> Edit Outlet
            </button>

            <form action="{{ route('admin.outlet.delete',$outlet->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus outlet ini?')">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i> Hapus Outlet
                </button>
            </form>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="bi bi-plus-lg me-1"></i> Tambah Produk
            </button>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-body p-4">

        {{-- SEARCH DAN KATEGORI --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <form action="{{ isset($outlet) ? url()->current() : route('admin.products.index') }}" method="GET">
                    <div class="input-group">
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Cari nama produk..."
                            value="{{ request('search') }}"
                        >

                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif

                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-md-4">
                <form action="{{ isset($outlet) ? url()->current() : route('admin.products.index') }}" method="GET">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>

                        @foreach($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        {{-- TABEL PRODUK --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle border-top">
                <thead class="table-light">
                    <tr>
                        <th width="50" class="text-center">#</th>
                        <th width="80">Gambar</th>
                        <th>Info Produk</th>
                        <th>Kategori</th>

                        @if(isset($outlet))
                            <th>Harga</th>
                            <th>Stock</th>
                        @endif

                        <th width="220" class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($products as $product)

                        @php
                            $hasVariants = false;
                            $variantDetails = collect();
                            $minPrice = null;
                            $maxPrice = null;
                            $totalStock = 0;
                            $stock = 0;

                            if (isset($outlet)) {
                                $details = $detailsByProduct->get(
                                    $product->id,
                                    collect()
                                );

                                $variantDetails = $details->filter(function ($detail) {
                                    return $detail->varians !== null;
                                });

                                $hasVariants = $variantDetails->count() > 0;

                                $minPrice = $details->min('price');
                                $maxPrice = $details->max('price');

                                $totalStock = $details->sum('stock');

                                $stock = $details->first()->stock ?? 0;
                            }
                        @endphp

                        {{-- BARIS PRODUK --}}
                        <tr>
                            <td class="text-center">
                                {{ $products->firstItem() + $loop->index }}
                            </td>

                            {{-- GAMBAR --}}
                            <td>
                                @if($product->image)
                                    <img
                                        src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->name }}"
                                        class="rounded shadow-sm"
                                        width="60"
                                        height="60"
                                        style="object-fit: cover;"
                                    >
                                @else
                                    <div
                                        class="bg-light d-flex align-items-center justify-content-center rounded"
                                        style="width: 60px; height: 60px;"
                                    >
                                        <i class="bi bi-image text-muted fs-4"></i>
                                    </div>
                                @endif
                            </td>

                            {{-- INFO PRODUK --}}
                            <td>
                                <div class="fw-bold fs-6 text-dark">
                                    {{ $product->name }}
                                </div>

                                @if(isset($outlet))
                                    @if($hasVariants)
                                        <div class="mt-1 d-flex align-items-center gap-2">
                                            <span class="badge bg-info text-dark">
                                                <i class="bi bi-tags-fill me-1"></i>
                                                {{ $variantDetails->count() }} Varian
                                            </span>

                                            <button
                                                type="button"
                                                class="btn btn-link btn-sm p-0 text-decoration-none"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#variantRow{{ $product->id }}"
                                            >
                                                Lihat Varian
                                                <i class="bi bi-chevron-down"></i>
                                            </button>
                                        </div>
                                    @else
                                        <div class="mt-1">
                                            <span class="badge bg-secondary">
                                                Non-Varian
                                            </span>
                                        </div>
                                    @endif
                                @endif
                            </td>

                            {{-- KATEGORI --}}
                            <td>
                                <span class="badge bg-light text-secondary border">
                                    {{ $product->category->name ?? '-' }}
                                </span>
                            </td>

                            {{-- HARGA --}}
                            @if(isset($outlet))
                                <td class="fw-semibold text-success">
                                    @if($hasVariants && $minPrice != $maxPrice)
                                        <div class="d-flex flex-column">
                                            Rp {{ number_format($minPrice, 0, ',', '.') }}
                                            -
                                            Rp {{ number_format($maxPrice, 0, ',', '.') }}
                                        </div>
                                    @else
                                        Rp {{ number_format($minPrice ?? 0, 0, ',', '.') }}
                                    @endif
                                </td>
                            @endif

                            {{-- STOCK --}}
                            @if(isset($outlet))
                                <td>
                                    @if($hasVariants)
                                        <span class="badge {{ $totalStock <= 5 ? 'bg-danger' : 'bg-success' }} px-2 py-1 fs-6">
                                            {{ $totalStock }}
                                        </span>
                                    @else
                                        <span class="badge {{ $stock <= 5 ? 'bg-danger' : 'bg-success' }} px-2 py-1 fs-6">
                                            {{ $stock }}
                                        </span>
                                    @endif
                                </td>
                            @endif

                            {{-- AKSI --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">

                                    @if(!isset($outlet))
                                        <a
                                            href="{{ route('admin.product.show', $product->id) }}"
                                            class="btn btn-sm btn-outline-info"
                                            title="Detail"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endif

                                    @if(isset($outlet))
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#addVariantModal{{ $product->id }}"
                                            title="Tambah Varian"
                                        >
                                            <i class="bi bi-plus-circle"></i>
                                            Varian
                                        </button>
                                    @endif

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#chooseEditModal{{ $product['id'] }}"
                                    >
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </button>

                                    <form
                                        action=""
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus produk ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Hapus"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                        {{-- MODAL PILIH EDIT PRODUK --}}
                        <div
                            class="modal fade"
                            id="chooseEditModal{{ $product['id'] }}"
                            tabindex="-1"
                            aria-hidden="true"
                        >
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            Edit Produk
                                        </h5>

                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                        ></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="d-grid gap-3">

                                            {{-- UPDATE GAMBAR --}}
                                            <button
                                                type="button"
                                                class="btn btn-outline-primary p-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#updateImageModal{{ $product['id'] }}"
                                            >
                                                <i class="bi bi-image fs-4"></i>

                                                <div class="fw-bold mt-2">
                                                    Update Gambar
                                                </div>
                                            </button>

                                            {{-- UPDATE STOK --}}
                                            @if(isset($outlet))
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-warning p-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#updateStockModal{{ $product['id'] }}"
                                                >
                                                    <i class="bi bi-box-seam fs-4"></i>

                                                    <div class="fw-bold mt-2">
                                                        Update Stok
                                                    </div>
                                                </button>
                                            @endif

                                            {{-- UPDATE DATA --}}
                                            <button
                                                type="button"
                                                class="btn btn-outline-success p-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#updateDataModal{{ $product['id'] }}"
                                            >
                                                <i class="bi bi-pencil fs-4"></i>

                                                <div class="fw-bold mt-2">
                                                    Update Produk
                                                </div>
                                            </button>

                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button
                                            type="button"
                                            class="btn btn-secondary"
                                            data-bs-dismiss="modal"
                                        >
                                            Tutup
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- MODAL UPDATE GAMBAR --}}
                        <div
                            class="modal fade"
                            id="updateImageModal{{ $product['id'] }}"
                            tabindex="-1"
                            aria-hidden="true"
                        >
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <form
                                        action="{{ route('admin.products.update', $product['id']) }}"
                                        method="POST"
                                        enctype="multipart/form-data"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                Update Gambar Produk
                                            </h5>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                            ></button>
                                        </div>

                                        <div class="modal-body">

                                            {{-- PREVIEW GAMBAR --}}
                                            <div class="text-center mb-3">
                                                <img
                                                    id="previewImage{{ $product['id'] }}"
                                                    src="{{ !empty($product['image']) ? asset('storage/' . $product['image']) : 'https://placehold.co/150x150?text=No+Image' }}"
                                                    class="img-thumbnail"
                                                    style="width: 150px; height: 150px; object-fit: cover;"
                                                >
                                            </div>

                                            {{-- INPUT GAMBAR --}}
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Gambar Baru
                                                </label>

                                                <input
                                                    type="file"
                                                    name="image"
                                                    id="inputImage{{ $product['id'] }}"
                                                    class="form-control"
                                                    accept=".jpg,.jpeg,.png"
                                                    required
                                                >

                                                <small class="text-muted">
                                                    Format JPG, JPEG, PNG. Maksimal 2 MB.
                                                </small>
                                            </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button
                                                type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal"
                                            >
                                                Batal
                                            </button>

                                            <button
                                                type="submit"
                                                class="btn btn-primary"
                                                name="submit"
                                                value="updateImage"
                                            >
                                                Simpan Gambar
                                            </button>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>

                        {{-- SCRIPT PREVIEW GAMBAR --}}
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const inputImage = document.getElementById(
                                    'inputImage{{ $product['id'] }}'
                                );

                                if (inputImage) {
                                    inputImage.addEventListener('change', function (event) {
                                        const file = event.target.files[0];

                                        if (file) {
                                            const preview = document.getElementById(
                                                'previewImage{{ $product['id'] }}'
                                            );

                                            if (preview) {
                                                preview.src = URL.createObjectURL(file);
                                            }
                                        }
                                    });
                                }
                            });
                        </script>

                        {{-- COLLAPSE DAFTAR VARIAN --}}
                        @if($hasVariants)
                            <tr class="collapse bg-light" id="variantRow{{ $product->id }}">
                                <td colspan="{{ isset($outlet) ? 7 : 6 }}" class="p-0 border-bottom">

                                    <div class="p-3 m-2 bg-white rounded border shadow-sm">

                                        <h6 class="fw-bold mb-3 text-primary">
                                            <i class="bi bi-list-ul me-2"></i>
                                            Daftar Varian: {{ $product->name }}
                                        </h6>

                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered mb-0 align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="40" class="text-center">#</th>
                                                        <th>Nama Varian</th>
                                                        <th>Harga Varian</th>
                                                        <th>Stock Varian</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach($variantDetails as $detail)
                                                        <tr>
                                                            <td class="text-center">
                                                                {{ $loop->iteration }}
                                                            </td>

                                                            <td class="fw-semibold">
                                                                {{ $product->name }}
                                                                {{ $detail->varians->name }}
                                                            </td>

                                                            <td>
                                                                Rp {{ number_format($detail->price, 0, ',', '.') }}
                                                            </td>

                                                            <td>
                                                                <span class="badge {{ $detail->stock <= 5 ? 'bg-danger' : 'bg-success' }}">
                                                                    {{ $detail->stock }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                        @endif

                        {{-- MODAL UPDATE STOCK --}}
                        @if(isset($outlet))
                            <x-modal
                                id="updateStockModal{{ $product['id'] }}"
                                title="Update Stock Product"
                                :is-form="true"
                                action="{{ route('admin.products.update', $product['id']) }}"
                                form-method="PATCH"
                            >

                                {{-- NAMA PRODUK --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        Nama Produk <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $product['name']) }}"
                                        disabled
                                    >

                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- VARIAN --}}
                                @if($hasVariants)
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            Varian <span class="text-danger">*</span>
                                        </label>

                                        <select
                                            name="variant_id"
                                            id="variantSelect{{ $product['id'] }}"
                                            class="form-select @error('variant_id') is-invalid @enderror"
                                            required
                                        >
                                            <option value="">-- Pilih Varian --</option>

                                            @foreach($variantDetails as $detail)
                                                <option
                                                    value="{{ $detail->varians->id }}"
                                                    data-stock="{{ $detail->stock }}"
                                                    {{ old('variant_id', $product['variant_id'] ?? '') == $detail->varians->id ? 'selected' : '' }}
                                                >
                                                    {{ $detail->varians->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('variant_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                @endif

                                {{-- STOCK --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        Stock <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="number"
                                        name="stock"
                                        id="stockInput{{ $product['id'] }}"
                                        class="form-control @error('stock') is-invalid @enderror"
                                        value="{{ old('stock', $hasVariants ? '' : $stock) }}"
                                        min="0"
                                    >

                                    @error('stock')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <x-slot name="footer">
                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        data-bs-dismiss="modal"
                                    >
                                        Batal
                                    </button>

                                    <button
                                        type="submit"
                                        class="btn btn-success"
                                        name="submit"
                                        value="updateStock"
                                    >
                                        <i class="bi bi-save me-1"></i>
                                        Update Stock
                                    </button>
                                </x-slot>

                            </x-modal>
                        @endif

                        {{-- MODAL UPDATE DATA PRODUK --}}
                        <x-modal
                            id="updateDataModal{{ $product['id'] }}"
                            title="Update Data Produk"
                            :is-form="true"
                            action="{{ route('admin.products.update', $product['id']) }}"
                            form-method="PATCH"
                        >

                            {{-- KATEGORI --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Kategori <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror"
                                    required
                                >
                                    <option value="">-- Pilih Kategori --</option>

                                    @foreach($categories as $category)
                                        <option
                                            value="{{ $category->id }}"
                                            {{ old('category_id', $product['category_id']) == $category->id ? 'selected' : '' }}
                                        >
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('category_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- NAMA PRODUK --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Nama Produk <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $product['name']) }}"
                                    placeholder="Masukkan nama produk"
                                    required
                                >

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <x-slot name="footer">
                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal"
                                >
                                    Batal
                                </button>

                                <button
                                    type="submit"
                                    class="btn btn-success"
                                    name="submit"
                                    value="updateProduct"
                                >
                                    <i class="bi bi-save me-1"></i>
                                    Update Produk
                                </button>
                            </x-slot>

                        </x-modal>

                        {{-- MODAL TAMBAH VARIAN --}}
                        @if(isset($outlet))
                            <x-modal
                                id="addVariantModal{{ $product['id'] }}"
                                title="Tambah Varian Baru"
                                :is-form="true"
                                action="{{ route('admin.varian.store') }}"
                                form-method="POST"
                            >
                                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                                <input type="hidden" name="outlet_id" value="{{ $outlet->id }}">

                                <div class="alert alert-info py-2 d-flex align-items-center">
                                    <i class="bi bi-info-circle-fill me-2 fs-5"></i>

                                    <div>
                                        Menambahkan varian untuk produk:
                                        <strong>{{ $product['name'] }}</strong>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        Nama Varian <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        placeholder="Contoh: XL, Pedas, Warna Merah"
                                        required
                                    >
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Harga Varian <span class="text-danger">*</span>
                                        </label>

                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>

                                            <input
                                                type="number"
                                                name="price"
                                                class="form-control"
                                                value="{{ $minPrice ?? 0 }}"
                                                min="0"
                                                required
                                            >
                                        </div>

                                        <small class="text-muted">
                                            Default mengikuti harga produk
                                        </small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Stock Varian <span class="text-danger">*</span>
                                        </label>

                                        <input
                                            type="number"
                                            name="stock"
                                            class="form-control"
                                            value="0"
                                            min="0"
                                            required
                                        >
                                    </div>
                                </div>

                                <x-slot name="footer">
                                    <button
                                        type="button"
                                        class="btn btn-light"
                                        data-bs-dismiss="modal"
                                    >
                                        Batal
                                    </button>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i>
                                        Simpan Varian
                                    </button>
                                </x-slot>

                            </x-modal>
                        @endif

                    @empty
                        <tr>
                            <td colspan="{{ isset($outlet) ? 7 : 6 }}" class="text-center py-5">
                                <i class="bi bi-box-seam fs-1 text-muted"></i>

                                <p class="text-muted mt-2 mb-0">
                                    {{ request('search') || request('category')
                                        ? 'Produk tidak ditemukan.'
                                        : 'Belum ada produk di outlet ini.' }}
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-3">
            {{ $products->links() }}
        </div>

    </div>
</div>

{{-- MODAL TAMBAH PRODUK --}}
@if(isset($outlet))

    <x-modal
        id="addProductModal"
        title="Tambah Produk Baru"
        :is-form="true"
        action="{{ route('admin.products.store') }}"
        :has-file="true"
    >
        <input type="hidden" name="outlet_id" value="{{ $outlet->id }}">

        {{-- KATEGORI --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">
                Kategori <span class="text-danger">*</span>
            </label>

            <select
                name="category_id"
                class="form-select @error('category_id') is-invalid @enderror"
                required
            >
                <option value="">-- Pilih Kategori --</option>

                @foreach($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            @error('category_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- NAMA DAN VARIAN --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Nama Produk <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="Contoh: Indomie"
                    value="{{ old('name') }}"
                    required
                >

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Varian <small class="text-muted fw-normal">(Opsional)</small>
                </label>

                <input
                    type="text"
                    name="varian"
                    class="form-control @error('varian') is-invalid @enderror"
                    placeholder="Contoh: Goreng Spesial"
                    value="{{ old('varian') }}"
                >

                @error('varian')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        {{-- HARGA DAN STOCK --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Harga <span class="text-danger">*</span>
                </label>

                <div class="input-group">
                    <span class="input-group-text">Rp</span>

                    <input
                        type="number"
                        name="price"
                        class="form-control @error('price') is-invalid @enderror"
                        placeholder="15000"
                        value="{{ old('price') }}"
                        min="0"
                        required
                    >
                </div>

                @error('price')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Stock Awal <span class="text-danger">*</span>
                </label>

                <input
                    type="number"
                    name="stock"
                    class="form-control @error('stock') is-invalid @enderror"
                    value="{{ old('stock', 0) }}"
                    min="0"
                    required
                >

                @error('stock')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        {{-- GAMBAR --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">
                Gambar Produk <small class="text-muted fw-normal">(Opsional)</small>
            </label>

            <input
                type="file"
                name="image"
                class="form-control @error('image') is-invalid @enderror"
                accept="image/jpeg,image/png,image/jpg"
            >

            <div class="form-text">
                Format JPG, JPEG, PNG. Maksimal 2 MB.
            </div>

            @error('image')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <x-slot name="footer">
            <button
                type="button"
                class="btn btn-light"
                data-bs-dismiss="modal"
            >
                Batal
            </button>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                Simpan Produk
            </button>
        </x-slot>

    </x-modal>

    {{-- MODAL EDIT OUTLET --}}
    <x-modal
        id="editOutletModal"
        title="Edit Outlet"
        :is-form="true"
        action="{{ route('admin.outlet.update',$outlet->id) }}"
        form-method="PUT"
    >
        <div class="mb-3">
            <label class="form-label fw-semibold">
                Nama Outlet
            </label>

            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ old('name', $outlet->name) }}"
                placeholder="Masukkan nama outlet"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">
                Alamat Outlet
            </label>

            <textarea
                name="address"
                class="form-control"
                rows="3"
                placeholder="Masukkan alamat outlet"
                required
            >{{ old('address', $outlet->address) }}</textarea>
        </div>

        <x-slot name="footer">
            <button
                type="button"
                class="btn btn-light"
                data-bs-dismiss="modal"
            >
                Batal
            </button>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                Simpan Perubahan
            </button>
        </x-slot>

    </x-modal>

    {{-- TAMPILKAN MODAL TAMBAH PRODUK JIKA VALIDASI GAGAL --}}
    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalElement = document.getElementById('addProductModal');

                if (modalElement) {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            });
        </script>
    @endif

    {{-- SCRIPT UPDATE STOCK --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Ambil semua dropdown varian dari setiap produk
            document.querySelectorAll('[id^="variantSelect"]').forEach(function (variantSelect) {

                // Ambil ID produk dari ID dropdown
                const productId = variantSelect.id.replace('variantSelect', '');

                // Ambil input stock sesuai ID produk
                const stockInput = document.getElementById('stockInput' + productId);

                // Jika input stock tidak ditemukan, hentikan proses
                if (!stockInput) {
                    return;
                }

                // Fungsi untuk mengisi stock secara otomatis
                function updateStock() {
                    const selectedOption = variantSelect.options[variantSelect.selectedIndex];

                    if (selectedOption && selectedOption.value !== '') {
                        stockInput.value = selectedOption.dataset.stock;
                    } else {
                        stockInput.value = '';
                    }
                }

                // Jalankan ketika varian dipilih
                variantSelect.addEventListener('change', updateStock);

                // Isi stock ketika halaman pertama kali dimuat
                updateStock();

            });

        });
    </script>

@endif

<style>
/* ================================
   RESPONSIVE HALAMAN PRODUK
================================ */

/* Search dan kategori */
@media (max-width: 768px) {
    .card-body {
        padding: 15px !important;
    }

    .row.g-3.mb-4 {
        gap: 10px !important;
    }

    .row.g-3.mb-4 .col-md-6,
    .row.g-3.mb-4 .col-md-4 {
        width: 100%;
    }

    /* Header produk */
    .d-flex.justify-content-between.align-items-center.mb-4 {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 15px;
    }

    .d-flex.justify-content-between.align-items-center.mb-4 h3 {
        font-size: 20px;
    }

    /* Tombol header */
    .d-flex.flex-wrap.gap-2 {
        width: 100%;
    }

    .d-flex.flex-wrap.gap-2 .btn {
        flex: 1 1 auto;
        font-size: 13px;
    }

    /* Tabel */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table {
        min-width: 750px;
    }

    .table th,
    .table td {
        white-space: nowrap;
        font-size: 13px;
        padding: 10px;
    }

    /* Info produk */
    .table td .fw-bold.fs-6 {
        font-size: 14px !important;
    }

    /* Tombol aksi */
    .table td .d-flex.justify-content-center.gap-1 {
        flex-wrap: wrap;
        justify-content: flex-start !important;
        min-width: 180px;
    }

    .table td .btn {
        font-size: 12px;
        padding: 5px 8px;
    }

    /* Gambar */
    .table img {
        width: 45px !important;
        height: 45px !important;
    }

    /* Pagination */
    .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }

    /* Modal */
    .modal-dialog {
        margin: 10px;
    }

    .modal-content {
        border-radius: 12px;
    }

    .modal-body {
        padding: 15px;
    }

    .modal-footer {
        flex-wrap: wrap;
    }

    .modal-footer .btn {
        flex: 1;
    }
}

/* Tablet */
@media (min-width: 769px) and (max-width: 1024px) {
    .card-body {
        padding: 20px !important;
    }

    .table th,
    .table td {
        padding: 10px;
        font-size: 14px;
    }

    .table td .btn {
        padding: 5px 8px;
    }
}

/* HP kecil */
@media (max-width: 480px) {
    .d-flex.justify-content-between.align-items-center.mb-4 h3 {
        font-size: 18px;
    }

    .d-flex.flex-wrap.gap-2 .btn {
        font-size: 11px;
        padding: 8px;
    }

    .card-body {
        padding: 10px !important;
    }

    .modal-title {
        font-size: 16px;
    }

    .modal-body label {
        font-size: 13px;
    }

    .modal-body .form-control,
    .modal-body .form-select {
        font-size: 14px;
    }
}
</style>

@endsection