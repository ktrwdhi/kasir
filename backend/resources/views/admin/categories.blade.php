
@extends('template.admin')

@section('title', 'Kelola Kategori')
@section('page-title', 'Kelola Kategori')

@section('content')

<div class="container-fluid p-0">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Kategori Produk</h4>
            <p class="text-muted mb-0">
                Kelola kategori untuk mengelompokkan produk.
            </p>
        </div>

        <button class="btn btn-primary px-4" data-bs-toggle="modal"
            data-bs-target="#createCategoryModal">
            <i class="bi bi-plus-lg me-2"></i>
            Tambah Kategori
        </button>
    </div>

    {{-- TOTAL KATEGORI --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon">
                        <i class="bi bi-tags"></i>
                    </div>

                    <div>
                        <small class="text-muted">Total Kategori</small>
                        <h4 class="fw-bold mb-0">
                            {{ $categories->total() ?? $categories->count() }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE KATEGORI --}}
    <div class="card">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Daftar Kategori</h5>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="70">No</th>
                            <th>Nama Kategori</th>
                            <th width="150" class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="stat-icon"
                                            style="width:38px;height:38px;font-size:17px;">
                                            <i class="bi bi-tag"></i>
                                        </div>

                                        <span class="fw-semibold">
                                            {{ $category->name }}
                                        </span>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">

                                        {{-- EDIT --}}
                                        <button class="btn btn-sm btn-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editCategoryModal"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        {{-- DELETE --}}
                                        <form
                                            action="{{ route('admin.categories.destroy', $category->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <i class="bi bi-inbox text-muted"
                                        style="font-size:40px;"></i>

                                    <p class="text-muted mt-2 mb-0">
                                        Belum ada kategori.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if(method_exists($categories, 'links'))
                <div class="mt-3">
                    {{ $categories->links() }}
                </div>
            @endif

        </div>
    </div>

</div>


{{-- MODAL TAMBAH KATEGORI --}}
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">

            <form action="{{ route('admin.categories.store') }}" method="POST">

                @csrf

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Kategori
                    </h5>

                    <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label class="form-label fw-semibold">
                        Nama Kategori
                    </label>

                    <input type="text"
                        name="name"
                        class="form-control"
                        placeholder="Contoh: Makanan"
                        required>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


{{-- MODAL EDIT KATEGORI --}}
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">

            <form id="editCategoryForm" method="POST">

                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Kategori
                    </h5>

                    <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label class="form-label fw-semibold">
                        Nama Kategori
                    </label>

                    <input type="text"
                        name="name"
                        id="editCategoryName"
                        class="form-control"
                        placeholder="Nama kategori"
                        required>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection


@section('scripts')
<script>
    const editCategoryModal = document.getElementById('editCategoryModal');

    editCategoryModal.addEventListener('show.bs.modal', function(event) {

        const button = event.relatedTarget;

        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');

        document.getElementById('editCategoryName').value = name;

        document.getElementById('editCategoryForm').action =
            "{{ url('/admin/categories') }}/" + id;
    });
</script>
@endsection
