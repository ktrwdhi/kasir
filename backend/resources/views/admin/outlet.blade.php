@extends('template.admin')

@section('content')
@section('page-title', 'Outlets')

<div class="container-fluid outlet-page">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                {{ isset($product) ? 'Outlet Penjual Produk' : 'Outlet' }}
            </h3>

            <p class="text-muted mb-0">
                @if(isset($product))
                    Daftar outlet yang menjual {{ $product->name }}.
                @else
                    Kelola outlet yang tersedia.
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

            @if(!isset($product))
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
                            Belum ada outlet yang tersedia.
                        @else
                            Data outlet belum tersedia.
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

                @if($availableOutlets->isNotEmpty())
                    
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
                                    {{ old('outlet_id') == $outlet->id ? 'selected' : '' }}
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
                    value="Semua outlet sudah menjual produk ini"
                    disabled
                >
            @endif
                    {{-- PILIH OUTLET --}}


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
                        <i class="bi bi-plus-lg me-1"></i>
                        Tambahkan Produk
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif


{{-- ================================================== --}}
{{-- MODAL TAMBAH OUTLET --}}
{{-- ================================================== --}}

@if(!isset($product))

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

        @else

            const modalElement = document.getElementById('addOutletModal');

        @endif

        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }

    });
</script>

@endif


<style>

    .outlet-page {
        padding-bottom: 30px;
    }

    .outlet-link {
        display: block;
        height: 100%;
        color: inherit;
        text-decoration: none;
    }

    .outlet-card {
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

    .outlet-icon {
        width: 52px;
        height: 52px;
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
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
        color: #0d6efd;
        font-size: 13px;
        font-weight: 600;
    }

    .empty-outlet {
        background: #fff;
        border-radius: 16px;
        border: 1px dashed #dee2e6;
    }

    .card-body-product {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .button button {
        border: none;
        padding: 10px;
        border-radius: 10px;
        background-color: rgb(57, 126, 253);
        color: white;
        font-weight: bold;
    }

    .button button:hover {
        background-color: rgb(35, 105, 235);
    }

</style>

@endsection
