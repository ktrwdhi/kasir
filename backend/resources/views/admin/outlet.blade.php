@extends('template.admin')

@section('content')

@section('page-title','Outlets')

<div class="container-fluid outlet-page">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Outlet
            </h3>

            <p class="text-muted mb-0">
                Kelola outlet yang tersedia.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">

            <span class="badge bg-primary rounded-pill px-3 py-2">
                {{ $outlets->count() }} Outlet
            </span>

            <button
                type="button"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#addOutletModal"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Tambah Outlet
            </button>

        </div>

    </div>


    {{-- List Outlet --}}

    <div class="row g-4">

        @forelse ($outlets as $outlet)

            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">

                <a
                    href="{{ route('admin.outlet.show',$outlet->uuid) }}"
                    class="outlet-link"
                >

                    <div class="card outlet-card h-100 border-0">

                        <div class="card-body p-4">

                            <div class="d-flex justify-content-between
                                        align-items-start mb-4">

                                <div class="outlet-icon">

                                    <i class="bi bi-shop"></i>

                                </div>

                                <i class="bi bi-arrow-up-right
                                          outlet-arrow"></i>

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
                        Data outlet belum tersedia.
                    </p>

                </div>

            </div>

        @endforelse

    </div>

</div>


{{-- ================================================== --}}
{{-- MODAL TAMBAH OUTLET --}}
{{-- ================================================== --}}

<div
    class="modal fade"
    id="addOutletModal"
    tabindex="-1"
    aria-labelledby="addOutletModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            {{-- Header Modal --}}

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


            {{-- Form --}}

            <form
                action=""
                method="POST"
            >

                @csrf

                <div class="modal-body">


                    {{-- Nama Outlet --}}

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


                    {{-- Alamat --}}

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


                {{-- Footer Modal --}}

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


{{-- Buka modal otomatis jika validasi error --}}

@if($errors->any())

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const modalElement =
            document.getElementById('addOutletModal');

        const modal =
            new bootstrap.Modal(modalElement);

        modal.show();

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

</style>

@endsection