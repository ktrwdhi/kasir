@extends('template.admin')

@section('content')
@section('page-title', 'Outlets')

@php
    $currentUser = auth()->user();
    $isAdmin = ($currentUser->role ?? null) === 'admin';
@endphp

<div class="container-fluid outlet-page">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                {{ isset($product) ? 'Outlet Penjual Produk' : 'Outlet' }}
            </h3>

            <p class="text-muted mb-0">
                @if(isset($product))
                    @if($isAdmin)
                        Status produk {{ $product->name }} di outlet Anda.
                    @else
                        Daftar outlet yang menjual {{ $product->name }}.
                    @endif
                @else
                    @if($isAdmin)
                        Outlet yang Anda kelola.
                    @else
                        Kelola outlet yang tersedia.
                    @endif
                @endif
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">

            @if(isset($product))
                <a href="{{ route('admin.products.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali
                </a>
            @endif

            <span class="badge bg-primary rounded-pill px-3 py-2">
                {{ $outlets->count() }} Outlet
            </span>

            @if(!isset($product) && !$isAdmin)
                <button
                    type="button"
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#addOutletModal"
                >
                    <i class="bi bi-plus-lg me-1"></i>
                    Tambah Outlet
                </button>
            @endif

        </div>

    </div>


    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- INFORMASI PRODUK --}}
    @if(isset($product))
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body-product p-3">

                <div class="d-flex align-items-center gap-3">

                    @if($product->image)
                        <img
                            src="{{ asset('storage/' . $product->image) }}"
                            alt="{{ $product->name }}"
                            width="65"
                            height="65"
                            style="object-fit: cover; border-radius: 10px;"
                        >
                    @else
                        <div
                            class="bg-light d-flex align-items-center justify-content-center"
                            style="width: 65px; height: 65px; border-radius: 10px;"
                        >
                            <i class="bi bi-image fs-3 text-muted"></i>
                        </div>
                    @endif

                    <div>
                        <h5 class="fw-bold mb-1">
                            {{ $product->name }}
                        </h5>

                        <p class="text-muted mb-1">
                            {{ $product->category->name ?? '-' }}
                        </p>

                        <span class="text-muted small">
                            Harga ditentukan berdasarkan outlet.
                        </span>
                    </div>

                </div>

                @if(isset($availableOutlets) && $availableOutlets->isNotEmpty())
                    <div class="button">
                        <button
                            type="button"
                            class="tambah"
                            data-bs-toggle="modal"
                            data-bs-target="#addProductToOutletModal"
                        >
                            <i class="bi bi-plus-lg me-1"></i>
                            Tambahkan ke Outlet yang Tersedia
                        </button>
                    </div>
                @endif

            </div>
        </div>
    @endif


    {{-- LIST OUTLET --}}
    <div class="row g-4">

        @forelse ($outlets as $outlet)

            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">

                <a
                    href="{{ route('admin.outlet.show', $outlet->uuid) }}"
                    class="outlet-link"
                >

                    <div class="card outlet-card h-100 border-0">

                        <div class="card-body p-4">

                            <div class="d-flex justify-content-between align-items-start mb-4">

                                <div class="outlet-icon">
                                    <i class="bi bi-shop"></i>
                                </div>

                                <i class="bi bi-arrow-up-right outlet-arrow"></i>

                            </div>

                            <h5 class="fw-bold mb-2">
                                {{ $outlet->name }}
                            </h5>

                            <p class="text-muted small mb-4">
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ $outlet->address ?? 'Alamat belum tersedia' }}
                            </p>

                            <div class="outlet-footer">

                                <span>
                                    Lihat detail outlet
                                </span>

                                <i class="bi bi-arrow-right"></i>

                            </div>

                        </div>

                    </div>

                </a>

            </div>

        @empty

            <div class="col-12">

                <div class="empty-outlet text-center p-5">

                    <i class="bi bi-shop display-4 text-muted"></i>

                    <h5 class="fw-bold mt-3">
                        Belum Ada Outlet
                    </h5>

                    <p class="text-muted mb-0">
                        @if(isset($product))
                            @if($isAdmin)
                                Produk ini belum tersedia di outlet Anda.
                            @else
                                Belum ada outlet yang menjual produk ini.
                            @endif
                        @else
                            @if($isAdmin)
                                Anda belum terhubung ke outlet mana pun.
                            @else
                                Data outlet belum tersedia.
                            @endif
                        @endif
                    </p>

                </div>

            </div>

        @endforelse

    </div>

</div>


{{-- ================================================== --}}
{{-- MODAL TAMBAH PRODUK KE OUTLET --}}
{{-- ================================================== --}}

@if(isset($product))

<div
    class="modal fade"
    id="addProductToOutletModal"
    tabindex="-1"
    aria-labelledby="addProductToOutletModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            {{-- HEADER MODAL --}}
            <div class="modal-header">

                <div>
                    <h5
                        class="modal-title fw-bold"
                        id="addProductToOutletModalLabel"
                    >
                        Tambahkan Produk ke Outlet
                    </h5>

                    <small class="text-muted">
                        Pilih outlet dan tentukan harga serta stok produk.
                    </small>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            {{-- FORM --}}
            <form
                action="{{ route('admin.products.attach') }}"
                method="POST"
            >

                @csrf

                <div class="modal-body">

                    {{-- PRODUCT ID --}}
                    <input
                        type="hidden"
                        name="product_id"
                        value="{{ $product->id }}"
                    >

                    {{-- INFORMASI PRODUK --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Nama Produk
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $product->name }}"
                            disabled
                        >

                    </div>

                    @if(isset($availableOutlets) && $availableOutlets->isNotEmpty())

                        {{-- PILIH OUTLET --}}
                        <div class="mb-3">

                            <label
                                for="outlet_id"
                                class="form-label fw-semibold"
                            >
                                Pilih Outlet
                            </label>

                            <select
                                name="outlet_id"
                                id="outlet_id"
                                class="form-select @error('outlet_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    -- Pilih Outlet --
                                </option>

                                @foreach($availableOutlets as $outlet)
                                    <option
                                        value="{{ $outlet->id }}"
                                        {{ (old('outlet_id') == $outlet->id || ($isAdmin && $availableOutlets->count() === 1)) ? 'selected' : '' }}
                                    >
                                        {{ $outlet->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('outlet_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- HARGA PRODUK --}}
                        <div class="mb-3">

                            <label
                                for="price"
                                class="form-label fw-semibold"
                            >
                                Harga Produk
                            </label>

                            <input
                                type="number"
                                name="price"
                                id="price"
                                class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price') }}"
                                placeholder="Masukkan harga produk"
                                min="0"
                                required
                            >

                            @error('price')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- STOK PRODUK --}}
                        <div class="mb-3">

                            <label
                                for="stock"
                                class="form-label fw-semibold"
                            >
                                Stok Awal
                            </label>

                            <input
                                type="number"
                                name="stock"
                                id="stock"
                                class="form-control @error('stock') is-invalid @enderror"
                                value="{{ old('stock') }}"
                                placeholder="Masukkan stok awal"
                                min="0"
                                required
                            >

                            @error('stock')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    @else

                        <label
                            for="outlet_id"
                            class="form-label fw-semibold"
                        >
                            Pilih Outlet
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $isAdmin ? 'Produk sudah terdaftar di outlet Anda' : 'Semua outlet sudah menjual produk ini' }}"
                            disabled
                        >

                    @endif

                </div>


                {{-- FOOTER MODAL --}}
                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>

                    @if(isset($availableOutlets) && $availableOutlets->isNotEmpty())
                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-plus-lg me-1"></i>
                            Tambahkan Produk
                        </button>
                    @endif

                </div>

            </form>

        </div>

    </div>

</div>

@endif


{{-- ================================================== --}}
{{-- MODAL TAMBAH OUTLET (HANYA NON-ADMIN) --}}
{{-- ================================================== --}}

@if(!isset($product) && !$isAdmin)

<div
    class="modal fade"
    id="addOutletModal"
    tabindex="-1"
    aria-labelledby="addOutletModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            {{-- HEADER MODAL --}}
            <div class="modal-header">

                <div>

                    <h5
                        class="modal-title fw-bold"
                        id="addOutletModalLabel"
                    >
                        Tambah Outlet
                    </h5>

                    <small class="text-muted">
                        Tambahkan outlet baru
                    </small>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            {{-- FORM --}}
            <form
                action="{{ route('admin.outlet.store') }}"
                method="POST"
            >

                @csrf

                <div class="modal-body">

                    {{-- NAMA OUTLET --}}
                    <div class="mb-3">

                        <label
                            for="name"
                            class="form-label fw-semibold"
                        >
                            Nama Outlet
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Contoh: Toko Baju"
                            required
                        >

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- ALAMAT --}}
                    <div class="mb-3">

                        <label
                            for="address"
                            class="form-label fw-semibold"
                        >
                            Alamat
                        </label>

                        <textarea
                            name="address"
                            id="address"
                            rows="3"
                            class="form-control @error('address') is-invalid @enderror"
                            placeholder="Masukkan alamat outlet"
                        >{{ old('address') }}</textarea>

                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- FOOTER MODAL --}}
                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-save me-1"></i>
                        Simpan Outlet
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif


{{-- ================================================== --}}
{{-- BUKA MODAL OTOMATIS JIKA VALIDASI ERROR --}}
{{-- ================================================== --}}

@if($errors->any())

<script>
    document.addEventListener('DOMContentLoaded', function () {

        @if(isset($product))
            const modalElement = document.getElementById('addProductToOutletModal');
        @elseif(!$isAdmin)
            const modalElement = document.getElementById('addOutletModal');
        @else
            const modalElement = null;
        @endif

        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }

    });
</script>

@endif


<style>
    /* ==============================
       HALAMAN OUTLET
    ============================== */
    .outlet-page {
        width: 100%;
        max-width: 100%;
        padding: 0 12px 30px;
        box-sizing: border-box;
    }

    /* ==============================
       LINK & CARD OUTLET
    ============================== */
    .outlet-link {
        display: block;
        height: 100%;
        color: inherit;
        text-decoration: none;
    }

    .outlet-card {
        height: 100%;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        transition: transform .2s ease, box-shadow .2s ease;
        overflow: hidden;
    }

    .outlet-link:hover .outlet-card {
        transform: translateY(-5px);
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.09);
    }

    .outlet-card .card-body {
        padding: 24px;
        min-width: 0;
    }

    .outlet-card h5 {
        overflow-wrap: anywhere;
    }

    .outlet-card p {
        overflow-wrap: anywhere;
    }

    .outlet-icon {
        width: 52px;
        height: 52px;
        min-width: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: #eaf2ff;
        color: #0d6efd;
        font-size: 25px;
    }

    .outlet-arrow {
        color: #adb5bd;
        font-size: 20px;
        transition: color .2s ease;
    }

    .outlet-link:hover .outlet-arrow {
        color: #0d6efd;
    }

    .outlet-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
        color: #0d6efd;
        font-size: 13px;
        font-weight: 600;
    }

    /* ==============================
       EMPTY OUTLET
    ============================== */
    .empty-outlet {
        background: #fff;
        border-radius: 16px;
        border: 1px dashed #dee2e6;
        overflow-wrap: anywhere;
    }

    /* ==============================
       INFORMASI PRODUK
    ============================== */
    .card-body-product {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .card-body-product > div:first-child {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        flex: 1;
    }

    .card-body-product img {
        flex-shrink: 0;
        max-width: 100%;
    }

    .card-body-product h5,
    .card-body-product p {
        overflow-wrap: anywhere;
    }

    /* ==============================
       BUTTON
    ============================== */
    .button {
        max-width: 100%;
    }

    .button button {
        border: none;
        padding: 10px 14px;
        border-radius: 10px;
        background-color: rgb(57, 126, 253);
        color: white;
        font-weight: bold;
        max-width: 100%;
        white-space: normal;
        overflow-wrap: anywhere;
    }

    .button button:hover {
        background-color: rgb(35, 105, 235);
    }

    /* ==============================
       MODAL
    ============================== */
    .modal-dialog {
        width: auto;
        max-width: 500px;
        margin: 1.75rem auto;
    }

    .modal-content {
        border-radius: 16px;
        overflow: hidden;
    }

    .modal-header,
    .modal-body,
    .modal-footer {
        min-width: 0;
    }

    .modal-footer {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .modal-footer button {
        white-space: normal;
    }

    /* ==============================
       TABLET
    ============================== */
    @media (max-width: 991.98px) {
        .outlet-page {
            padding-left: 10px;
            padding-right: 10px;
        }

        .outlet-card .card-body {
            padding: 20px;
        }

        .card-body-product {
            gap: 15px;
        }
    }

    /* ==============================
       MOBILE
    ============================== */
    @media (max-width: 575.98px) {
        .outlet-page {
            padding: 0 8px 20px;
        }

        .outlet-page > .d-flex:first-child {
            flex-direction: column;
            align-items: stretch !important;
            gap: 15px;
        }

        .outlet-page > .d-flex:first-child h3 {
            font-size: 22px;
        }

        .outlet-page > .d-flex:first-child > div:last-child {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }

        .outlet-page > .d-flex:first-child .btn {
            flex: 1;
        }

        .outlet-card .card-body {
            padding: 18px;
        }

        .outlet-card h5 {
            font-size: 17px;
        }

        .outlet-icon {
            width: 45px;
            height: 45px;
            min-width: 45px;
            font-size: 22px;
        }

        .outlet-footer {
            font-size: 12px;
        }

        .card-body-product {
            flex-direction: column;
            align-items: stretch;
        }

        .card-body-product > div:first-child {
            width: 100%;
        }

        .card-body-product h5 {
            font-size: 16px;
        }

        .button {
            width: 100%;
        }

        .button button {
            width: 100%;
            font-size: 13px;
        }

        .modal-dialog {
            max-width: none;
            width: auto;
            margin: 10px;
        }

        .modal-header {
            padding: 15px;
        }

        .modal-body {
            padding: 15px;
        }

        .modal-footer {
            padding: 12px 15px;
        }

        .modal-footer button {
            flex: 1;
            min-width: 100px;
        }

        .modal-title {
            font-size: 17px;
        }
    }

    /* ==============================
       HP KECIL
    ============================== */
    @media (max-width: 375px) {
        .outlet-page > .d-flex:first-child h3 {
            font-size: 20px;
        }

        .outlet-card .card-body {
            padding: 14px;
        }

        .card-body-product img {
            width: 50px !important;
            height: 50px !important;
        }

        .card-body-product h5 {
            font-size: 14px;
        }

        .modal-footer button {
            width: 100%;
            flex: 1 1 100%;
        }
    }
</style>
@endsection