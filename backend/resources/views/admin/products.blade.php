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

    {{-- @if(isset($outlet)) --}}
        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addProductModal"
        >
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Produk
        </button>
    {{-- @endif --}}

</div>


<div class="card">
    <div class="card-body p-4">

        {{-- SEARCH DAN KATEGORI --}}

        <div class="row g-3 mb-4">

            <div class="col-md-6">

                <form
                    action="{{ isset($outlet) ? url()->current() : route('admin.products.index') }}"
                    method="GET"
                >

                    <div class="input-group">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Cari nama produk..."
                            value="{{ request('search') }}"
                        >

                        @if(request('category'))
                            <input
                                type="hidden"
                                name="category"
                                value="{{ request('category') }}"
                            >
                        @endif

                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>

                    </div>

                </form>

            </div>


            <div class="col-md-4">

                <form
                    action="{{ isset($outlet) ? url()->current() : route('admin.products.index') }}"
                    method="GET"
                >

                    @if(request('search'))
                        <input
                            type="hidden"
                            name="search"
                            value="{{ request('search') }}"
                        >
                    @endif

                    <select
                        name="category"
                        class="form-select"
                        onchange="this.form.submit()"
                    >

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

            <table class="table">

                <thead>
                    <tr>
                        <th width="70">#</th>
                        <th>Gambar</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>{{ isset($outlet) ? "Stock" : "" }}</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>


                <tbody>

                    @forelse($products as $product)

                        <tr>

                            <td>
                                {{ $products->firstItem() + $loop->index }}
                            </td>


                            {{-- GAMBAR --}}

                            <td>

                                @if($product->image)

                                    <img
                                        src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->name }}"
                                        width="50"
                                        height="50"
                                        style="object-fit: cover; border-radius: 8px;"
                                    >

                                @else

                                    <div
                                        class="bg-light d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px; border-radius: 8px;"
                                    >
                                        <i class="bi bi-image text-muted"></i>
                                    </div>

                                @endif

                            </td>


                            {{-- NAMA PRODUK --}}

                            <td>
                                <div class="fw-bold">
                                    {{ $product->name }}
                                </div>
                            </td>


                            {{-- KATEGORI --}}

                            <td>
                                {{ $product->category->name ?? '-' }}
                            </td>


                            {{-- HARGA --}}

                            <td>
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>


                            {{-- STOCK --}}

                            <td>
                                @if (isset($outlet))
                                    @if($product->stock <= 5)

                                        <span class="badge bg-danger">
                                            {{ $product->stock }}
                                        </span>

                                    @else

                                        <span class="badge bg-success">
                                            {{ $product->stock }}
                                        </span>

                                    @endif
                                @endif
                            </td>


                            {{-- AKSI --}}

                            <td>

                                @if(!isset($outlet))

                                    <a
                                        href="{{ route('admin.product.show', $product->id) }}"
                                        class="btn btn-sm btn-primary"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>

                                @endif


                                <button
                                    type="button"
                                    class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editChoice{{ $product->id }}"
                                >
                                    <i class="bi bi-pencil"></i>
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
                                        class="btn btn-sm btn-danger"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </td>

                        </tr>


                        {{-- ====================================== --}}
                        {{-- MODAL PILIHAN EDIT --}}
                        {{-- ====================================== --}}

                        <div
                            class="modal fade"
                            id="editChoice{{ $product->id }}"
                            tabindex="-1"
                            aria-hidden="true"
                        >

                            <div class="modal-dialog modal-dialog-centered">

                                <div class="modal-content">

                                    <div class="modal-header">

                                        <h5 class="modal-title fw-bold">
                                            Edit Produk
                                        </h5>

                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                        ></button>

                                    </div>


                                    <div class="modal-body">

                                        <p class="text-muted mb-3">
                                            Pilih data yang ingin diubah:
                                        </p>

                                        <div class="d-grid gap-2">

                                            <button
                                                type="button"
                                                class="btn btn-outline-primary text-start p-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editStock{{ $product->id }}"
                                            >
                                                <i class="bi bi-box-seam me-2"></i>
                                                Edit Stock
                                            </button>


                                            <button
                                                type="button"
                                                class="btn btn-outline-primary text-start p-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editImage{{ $product->id }}"
                                            >
                                                <i class="bi bi-image me-2"></i>
                                                Edit Gambar
                                            </button>


                                            <button
                                                type="button"
                                                class="btn btn-outline-primary text-start p-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editData{{ $product->id }}"
                                            >
                                                <i class="bi bi-pencil-square me-2"></i>
                                                Edit Data Lainnya
                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>



                        {{-- ====================================== --}}
                        {{-- MODAL EDIT STOCK --}}
                        {{-- ====================================== --}}

                        <div
                            class="modal fade"
                            id="editStock{{ $product->id }}"
                            tabindex="-1"
                            aria-hidden="true"
                        >

                            <div class="modal-dialog modal-dialog-centered">

                                <div class="modal-content">

                                    <div class="modal-header">

                                        <h5 class="modal-title fw-bold">
                                            Edit Stock
                                        </h5>

                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                        ></button>

                                    </div>


                                    <form
                                        action="{{ route('admin.products.update', $product->id) }}"
                                        method="POST"
                                    >

                                        @csrf
                                        @method('PUT')

                                        <input
                                            type="hidden"
                                            name="type"
                                            value="stock"
                                        >


                                        <div class="modal-body">

                                            <div class="mb-3">

                                                <label class="form-label">
                                                    Nama Produk
                                                </label>

                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    value="{{ $product->name }}"
                                                    disabled
                                                >

                                            </div>


                                            <div class="mb-3">

                                                <label class="form-label fw-semibold">
                                                    Stock
                                                </label>

                                                <input
                                                    type="number"
                                                    name="stock"
                                                    class="form-control"
                                                    value="{{ $product->stock }}"
                                                    min="0"
                                                    required
                                                >

                                            </div>

                                        </div>


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
                                                Simpan Stock
                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>



                        {{-- ====================================== --}}
                        {{-- MODAL EDIT GAMBAR --}}
                        {{-- ====================================== --}}

                        <div
                            class="modal fade"
                            id="editImage{{ $product->id }}"
                            tabindex="-1"
                            aria-hidden="true"
                        >

                            <div class="modal-dialog modal-dialog-centered">

                                <div class="modal-content">

                                    <div class="modal-header">

                                        <h5 class="modal-title fw-bold">
                                            Edit Gambar Produk
                                        </h5>

                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                        ></button>

                                    </div>


                                    <form
                                        action="{{ route('admin.products.update', $product->id) }}"
                                        method="POST"
                                        enctype="multipart/form-data"
                                    >

                                        @csrf
                                        @method('PUT')

                                        <input
                                            type="hidden"
                                            name="type"
                                            value="image"
                                        >


                                        <div class="modal-body">

                                            <div class="text-center mb-3">

                                                @if($product->image)

                                                    <img
                                                        src="{{ asset('storage/' . $product->image) }}"
                                                        width="120"
                                                        height="120"
                                                        style="object-fit: cover; border-radius: 10px;"
                                                    >

                                                @else

                                                    <div class="bg-light p-4 rounded">
                                                        <i class="bi bi-image fs-1 text-muted"></i>
                                                    </div>

                                                @endif

                                            </div>


                                            <div class="mb-3">

                                                <label class="form-label fw-semibold">
                                                    Pilih Gambar Baru
                                                </label>

                                                <input
                                                    type="file"
                                                    name="image"
                                                    class="form-control"
                                                    accept="image/jpeg,image/png,image/jpg"
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
                                                Simpan Gambar
                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>



                        {{-- ====================================== --}}
                        {{-- MODAL EDIT DATA LAINNYA --}}
                        {{-- ====================================== --}}

                        <div
                            class="modal fade"
                            id="editData{{ $product->id }}"
                            tabindex="-1"
                            aria-hidden="true"
                        >

                            <div class="modal-dialog modal-dialog-centered">

                                <div class="modal-content">

                                    <div class="modal-header">

                                        <h5 class="modal-title fw-bold">
                                            Edit Data Produk
                                        </h5>

                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                        ></button>

                                    </div>


                                    <form
                                        action="{{ route('admin.products.update', $product->id) }}"
                                        method="POST"
                                    >

                                        @csrf
                                        @method('PUT')

                                        <input
                                            type="hidden"
                                            name="type"
                                            value="data"
                                        >


                                        <div class="modal-body">

                                            <div class="mb-3">

                                                <label class="form-label fw-semibold">
                                                    Nama Produk
                                                </label>

                                                <input
                                                    type="text"
                                                    name="name"
                                                    class="form-control"
                                                    value="{{ $product->name }}"
                                                    required
                                                >

                                            </div>


                                            <div class="mb-3">

                                                <label class="form-label fw-semibold">
                                                    Kategori
                                                </label>

                                                <select
                                                    name="category_id"
                                                    class="form-select"
                                                    required
                                                >

                                                    @foreach($categories as $category)

                                                        <option
                                                            value="{{ $category->id }}"
                                                            {{ $product->category_id == $category->id ? 'selected' : '' }}
                                                        >
                                                            {{ $category->name }}
                                                        </option>

                                                    @endforeach

                                                </select>

                                            </div>


                                            <div class="mb-3">

                                                <label class="form-label fw-semibold">
                                                    Harga
                                                </label>

                                                <div class="input-group">

                                                    <span class="input-group-text">
                                                        Rp
                                                    </span>

                                                    <input
                                                        type="number"
                                                        name="price"
                                                        class="form-control"
                                                        value="{{ $product->price }}"
                                                        min="0"
                                                        required
                                                    >

                                                </div>

                                            </div>

                                        </div>


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
                                                Simpan Perubahan
                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>


                    @empty

                        <tr>

                            <td colspan="7" class="text-center py-5">

                                <i class="bi bi-box-seam fs-1 text-muted"></i>

                                <p class="text-muted mt-2 mb-0">

                                    @if(request('search') || request('category'))
                                        Produk tidak ditemukan.
                                    @else
                                        Belum ada produk di outlet ini.
                                    @endif

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



{{-- ====================================== --}}
{{-- MODAL PILIHAN TAMBAH PRODUK --}}
{{-- ====================================== --}}

@if(isset($outlet))

<div
    class="modal fade"
    id="addProductModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title fw-bold">
                    Tambah Produk
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <div class="modal-body">

                <p class="text-muted mb-3">
                    Pilih jenis produk yang ingin ditambahkan ke {{ $outlet->name }}.
                </p>

                <div class="d-grid gap-3">

                    <button
                        type="button"
                        class="btn btn-outline-primary text-start p-3"
                        onclick="switchProductModal('newProductModal')"
                    >

                        <div class="d-flex align-items-center gap-3">

                            <i class="bi bi-plus-circle fs-2"></i>

                            <div>
                                <div class="fw-bold">
                                    Tambah Produk Baru
                                </div>

                                <small class="text-muted">
                                    Membuat produk baru.
                                </small>
                            </div>

                        </div>

                    </button>


                    <button
                        type="button"
                        class="btn btn-outline-primary text-start p-3"
                        onclick="switchProductModal('existingProductModal')"
                    >

                        <div class="d-flex align-items-center gap-3">

                            <i class="bi bi-box-seam fs-2"></i>

                            <div>
                                <div class="fw-bold">
                                    Tambah Produk yang Sudah Ada
                                </div>

                                <small class="text-muted">
                                    Mengambil produk dari outlet lain.
                                </small>
                            </div>

                        </div>

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>



{{-- ====================================== --}}
{{-- MODAL TAMBAH PRODUK BARU --}}
{{-- ====================================== --}}

<div
    class="modal fade"
    id="newProductModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title fw-bold">
                    Tambah Produk Baru
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                action="{{ route('admin.products.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="modal-body">

                    <input
                        type="hidden"
                        name="outlet_id"
                        value="{{ $outlet->id }}"
                    >


                    {{-- NAMA --}}

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Nama Produk
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Contoh: Kopi Susu"
                            value="{{ old('name') }}"
                            required
                        >

                    </div>


                    {{-- KATEGORI --}}

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Kategori
                        </label>

                        <select
                            name="category_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Pilih Kategori
                            </option>

                            @foreach($categories as $category)

                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- HARGA --}}

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Harga
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                Rp
                            </span>

                            <input
                                type="number"
                                name="price"
                                class="form-control"
                                placeholder="15000"
                                min="0"
                                required
                            >

                        </div>

                    </div>


                    {{-- STOCK --}}

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Stock
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


                    {{-- GAMBAR --}}

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Gambar Produk
                        </label>

                        <input
                            type="file"
                            name="image"
                            class="form-control"
                            accept="image/jpeg,image/png,image/jpg"
                        >

                        <small class="text-muted">
                            Format JPG, JPEG, PNG. Maksimal 2 MB.
                        </small>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        onclick="switchProductModal('addProductModal')"
                    >
                        Kembali
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-save me-1"></i>
                        Simpan Produk
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



{{-- ====================================== --}}
{{-- MODAL TAMBAH PRODUK YANG SUDAH ADA --}}
{{-- ====================================== --}}

<div
    class="modal fade"
    id="existingProductModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title fw-bold">
                    Tambah Produk yang Sudah Ada
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                action="{{ route('admin.products.attach') }}"
                method="POST"
            >

                @csrf

                <input
                    type="hidden"
                    name="outlet_id"
                    value="{{ $outlet->id }}"
                >


                <div class="modal-body">

                    {{-- PILIH PRODUK --}}

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Pilih Produk
                        </label>

                        <select
                            name="product_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Pilih Produk
                            </option>

                            @foreach($availableProducts as $availableProduct)

                                <option value="{{ $availableProduct->id }}">
                                    {{ $availableProduct->name }}
                                    - {{ $availableProduct->category->name ?? 'Tanpa Kategori' }}
                                </option>

                            @endforeach

                        </select>

                        @if($availableProducts->isEmpty())

                            <small class="text-danger">
                                Tidak ada produk lain yang tersedia untuk ditambahkan.
                            </small>

                        @endif

                    </div>


                    {{-- HARGA --}}

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Harga di Outlet Ini
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                Rp
                            </span>

                            <input
                                type="number"
                                name="price"
                                class="form-control"
                                min="0"
                                placeholder="15000"
                                required
                            >

                        </div>

                    </div>


                    {{-- STOCK --}}

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Stock Awal
                        </label>

                        <input
                            type="number"
                            name="stock"
                            class="form-control"
                            min="0"
                            value="0"
                            required
                        >

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        onclick="switchProductModal('addProductModal')"
                    >
                        Kembali
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        {{ $availableProducts->isEmpty() ? 'disabled' : '' }}
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Tambahkan ke Outlet
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif


{{-- ====================================== --}}
{{-- JAVASCRIPT PINDAH MODAL --}}
{{-- ====================================== --}}

<script>

    function switchProductModal(targetId) {

        const modalIds = [
            'addProductModal',
            'newProductModal',
            'existingProductModal'
        ];

        modalIds.forEach(function(id) {

            const element = document.getElementById(id);

            if (!element) return;

            const instance = bootstrap.Modal.getInstance(element);

            if (instance) {
                instance.hide();
            }

        });

        setTimeout(function() {

            const target = document.getElementById(targetId);

            if (target) {
                bootstrap.Modal.getOrCreateInstance(target).show();
            }

        }, 200);

    }

</script>


{{-- ====================================== --}}
{{-- BUKA MODAL TAMBAH JIKA VALIDASI ERROR --}}
{{-- ====================================== --}}

@if($errors->any() && isset($outlet))

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const modalElement = document.getElementById('addProductModal');

        if (modalElement) {
            bootstrap.Modal.getOrCreateInstance(modalElement).show();
        }

    });

</script>

@endif

@endsection
