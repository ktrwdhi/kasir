@extends('template.admin')

@section('title', 'Produk')

@section('page-title', 'Produk')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            {{ isset($outlet) ? $outlet->name : "" }}

            @if(isset($outlet))
                - {{ $outlet->address }}
            @endif
        </h3>

    </div>

    @if (isset($outlet))    
        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addProductModal"
        >
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Produk
        </button>
    @endif

</div>


<div class="card">

    <div class="card-body p-4">


        {{-- Search & Kategori --}}

        <div class="row g-3 mb-4">


            {{-- Search --}}

            <div class="col-md-6">

                <form
                    action="{{ isset($outlet)
                        ? ""
                        : route('admin.products.index') }}"
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

                        <button
                            class="btn btn-primary"
                            type="submit"
                        >
                            <i class="bi bi-search"></i>
                        </button>

                    </div>

                </form>

            </div>


            {{-- Kategori --}}

            <div class="col-md-4">

                <form
                    action="{{ isset($outlet)
                        ? ""
                        : route('admin.products.index') }}"
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

                        <option value="">
                            Semua Kategori
                        </option>

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


        {{-- Table --}}

        <div class="table-responsive">

            <table class="table">

                <thead>

                    <tr>

                        <th width="70">
                            #
                        </th>

                        <th>
                            Gambar
                        </th>

                        <th>
                            Produk
                        </th>

                        <th>
                            Kategori
                        </th>

                        <th>
                            Harga
                        </th>

                        <th>
                            Stock
                        </th>

                        <th width="180">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($products as $product)

                        <tr>

                            {{-- Nomor --}}

                            <td>
                                {{ $products->firstItem() + $loop->index }}
                            </td>


                            {{-- Gambar --}}

                            <td>

                                @if($product->image)

                                    <img
                                        src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->name }}"
                                        width="50"
                                        height="50"
                                        style="
                                            object-fit: cover;
                                            border-radius: 8px;
                                        "
                                    >

                                @else

                                    <div
                                        class="bg-light d-flex align-items-center justify-content-center"
                                        style="
                                            width: 50px;
                                            height: 50px;
                                            border-radius: 8px;
                                        "
                                    >
                                        <i class="bi bi-image text-muted"></i>
                                    </div>

                                @endif

                            </td>


                            {{-- Nama Produk --}}

                            <td>

                                <div class="fw-bold">
                                    {{ $product->name }}
                                </div>

                            </td>


                            {{-- Kategori --}}

                            <td>

                                {{ $product->category->name ?? '-' }}

                            </td>


                            {{-- Harga --}}

                            <td>

                                Rp
                                {{ number_format($product->price, 0, ',', '.') }}

                            </td>


                            {{-- Stock --}}

                            <td>

                                @if($product->stock <= 5)

                                    <span class="badge bg-danger">
                                        {{ $product->stock }}
                                    </span>

                                @else

                                    <span class="badge bg-success">
                                        {{ $product->stock }}
                                    </span>

                                @endif

                            </td>


                            {{-- Aksi --}}

                            <td>

                                <a
                                    href=""
                                    class="btn btn-sm btn-primary"
                                >
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a
                                    href=""
                                    class="btn btn-sm btn-warning"
                                >
                                    <i class="bi bi-pencil"></i>
                                </a>


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


                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5"
                            >

                                <i
                                    class="bi bi-box-seam fs-1 text-muted"
                                ></i>

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


        {{-- Pagination --}}

        <div class="mt-3">

            {{ $products->links() }}

        </div>


    </div>

</div>


{{-- ================================================== --}}
{{-- MODAL TAMBAH PRODUK --}}
{{-- ================================================== --}}

<div
    class="modal fade"
    id="addProductModal"
    tabindex="-1"
    aria-labelledby="addProductModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">


            {{-- Header --}}

            <div class="modal-header">

                <div>

                    <h5
                        class="modal-title fw-bold"
                        id="addProductModalLabel"
                    >
                        Tambah Produk
                    </h5>

                    <small class="text-muted">

                        @if(isset($outlet))

                            Tambahkan produk ke {{ $outlet->name }}

                        @else

                            Tambahkan produk baru

                        @endif

                    </small>

                </div>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            {{-- Form --}}

            <form
                action="{{ route('admin.products.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf


                <div class="modal-body">


                    {{-- Outlet --}}

                    @if(isset($outlet))

                        <input
                            type="hidden"
                            name="outlet_id"
                            value="{{ $outlet->id }}"
                        >

                    @endif


                    {{-- Nama Produk --}}

                    <div class="mb-3">

                        <label
                            for="name"
                            class="form-label fw-semibold"
                        >
                            Nama Produk
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Contoh: Kopi Susu"
                            required
                        >

                        @error('name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Kategori --}}

                    <div class="mb-3">

                        <label
                            for="category_id"
                            class="form-label fw-semibold"
                        >
                            Kategori
                        </label>

                        <select
                            name="category_id"
                            id="category_id"
                            class="form-select @error('category_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Pilih Kategori
                            </option>

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


                    {{-- Harga --}}

                    <div class="mb-3">

                        <label
                            for="price"
                            class="form-label fw-semibold"
                        >
                            Harga
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                Rp
                            </span>

                            <input
                                type="number"
                                name="price"
                                id="price"
                                class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price') }}"
                                placeholder="15000"
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


                    {{-- Stock --}}

                    <div class="mb-3">

                        <label
                            for="stock"
                            class="form-label fw-semibold"
                        >
                            Stock
                        </label>

                        <input
                            type="number"
                            name="stock"
                            id="stock"
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


                    {{-- Image --}}

                    <div class="mb-3">

                        <label
                            for="image"
                            class="form-label fw-semibold"
                        >
                            Gambar Produk
                        </label>

                        <input
                            type="file"
                            name="image"
                            id="image"
                            class="form-control @error('image') is-invalid @enderror"
                            accept="image/*"
                        >

                        <small class="text-muted">
                            Format JPG, JPEG, PNG. Maksimal 2 MB.
                        </small>

                        @error('image')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                </div>


                {{-- Footer --}}

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
                        Simpan Produk
                    </button>

                </div>


            </form>

        </div>

    </div>

</div>


{{-- ================================================== --}}
{{-- BUKA MODAL OTOMATIS KALAU VALIDASI ERROR --}}
{{-- ================================================== --}}

@if($errors->any())

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const modalElement =
                document.getElementById('addProductModal');

            const modal =
                new bootstrap.Modal(modalElement);

            modal.show();

        });

    </script>

@endif


@endsection