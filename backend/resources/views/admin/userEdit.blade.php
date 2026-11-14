
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pengaturan Profil</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .form-section-title {
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
            color: #495057;
        }

        .credential-input:disabled {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

        /* Animasi ikon sukses */
        .success-icon {
            width: 90px;
            height: 90px;
            background-color: #d1fae5;
            color: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: popSuccess 0.5s ease;
        }

        .success-icon i {
            font-size: 50px;
        }

        @keyframes popSuccess {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            60% {
                transform: scale(1.2);
                opacity: 1;
            }

            100% {
                transform: scale(1);
            }
        }

        /* Animasi teks */
        .success-title {
            animation: fadeUp 0.6s ease;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow-sm border-0 rounded-4">

                <div class="card-body p-4 p-md-5">

                    <!-- Header -->
                    <div class="mb-4">

                        <h2 class="fw-bold mb-1">
                            Pengaturan Profil
                        </h2>

                        <p class="text-muted">
                            Perbarui data diri dan kredensial akun Anda di sini.
                        </p>

                    </div>

                    <!-- Form -->
                    <form action="{{ route('user.update') }}"
                          method="POST"
                          id="profileForm">

                        @csrf
                        @method('PUT')

                        <!-- BAGIAN DATA DIRI -->
                        <section class="mb-5">

                            <h4 class="form-section-title">
                                <i class="bi bi-person-lines-fill me-2"></i>
                                Data Diri
                            </h4>

                            <div class="row g-3">

                                <input type="hidden"
                                       name="uuid"
                                       value="{{ $user->uuid }}">

                                <!-- Nama Lengkap -->
                                <div class="col-md-12">

                                    <label class="form-label fw-medium text-secondary">
                                        Nama Lengkap
                                    </label>

                                    <input type="text"
                                           name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $user->name ?? '') }}"
                                           placeholder="Masukkan nama Anda"
                                           required>

                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <!-- No HP -->
                                <div class="col-md-6">

                                    <label class="form-label fw-medium text-secondary">
                                        No. Handphone
                                    </label>

                                    <input type="tel"
                                           name="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           placeholder="08xxxx"
                                           value="{{ old('phone', $user->phone ?? '') }}"
                                           required>

                                    @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <!-- Jenis Kelamin -->
                                <div class="col-md-6">

                                    <label class="form-label fw-medium text-secondary">
                                        Jenis Kelamin
                                    </label>

                                    <select name="sex"
                                            class="form-select @error('sex') is-invalid @enderror"
                                            required>

                                        <option value="" disabled
                                            {{ old('sex', $user->sex ?? '') == '' ? 'selected' : '' }}>
                                            Pilih Jenis Kelamin
                                        </option>

                                        <option value="L"
                                            {{ old('sex', $user->sex ?? '') == 'L' ? 'selected' : '' }}>
                                            Laki-laki
                                        </option>

                                        <option value="P"
                                            {{ old('sex', $user->sex ?? '') == 'P' ? 'selected' : '' }}>
                                            Perempuan
                                        </option>

                                    </select>

                                    @error('sex')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>

                        </section>

                        <!-- BAGIAN KREDENSIAL AKUN -->
                        <section class="mb-4 p-4 bg-light border rounded-3">

                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">

                                <h5 class="fw-bold mb-0 text-secondary">
                                    <i class="bi bi-shield-lock me-2"></i>
                                    Kredensial Akun
                                </h5>

                                <!-- Tombol Edit -->
                                <button type="button"
                                        id="btnToggleEdit"
                                        onclick="toggleEditMode()"
                                        class="btn btn-outline-primary btn-sm fw-semibold">

                                    <i id="iconEdit"
                                       class="bi bi-pencil-square me-1"></i>

                                    <span id="btnText">
                                        Edit Akun
                                    </span>

                                </button>

                            </div>

                            <div class="row g-3">

                                <!-- Username -->
                                <div class="col-md-6">

                                    <label class="form-label fw-medium text-secondary">
                                        Username
                                    </label>

                                    <input type="text"
                                           name="username"
                                           id="inputUsername"
                                           class="form-control credential-input"
                                           value="{{ old('username', $user->username) }}"
                                           disabled>

                                </div>

                                <!-- Password -->
                                <div class="col-md-6">

                                    <label class="form-label fw-medium text-secondary">
                                        Password Baru
                                    </label>

                                    <input type="password"
                                           name="password"
                                           id="inputPassword"
                                           class="form-control credential-input"
                                           placeholder="********"
                                           minlength="8"
                                           disabled>

                                </div>

                            </div>

                            <!-- Peringatan Edit -->
                            <div id="editWarning"
                                 class="alert alert-info mt-3 mb-0 py-2 d-none align-items-start"
                                 role="alert">

                                <i class="bi bi-info-circle-fill me-2 mt-1"></i>

                                <small>
                                    <strong>Mode edit aktif.</strong>
                                    Username dan Password akan ikut tersimpan saat Anda
                                    menekan tombol simpan. Biarkan password kosong
                                    jika tidak ingin mengubahnya.
                                </small>

                            </div>

                        </section>

                        <!-- TOMBOL SUBMIT -->
                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">

                            <button type="submit"
                                    id="btnSubmit"
                                    class="btn btn-dark px-4 py-2 fw-semibold">

                                <i class="bi bi-save2 me-2"></i>
                                Simpan Perubahan

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- MODAL SUKSES -->
<div class="modal fade"
     id="successModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow">

            <div class="modal-body text-center p-5">

                <!-- Ikon Centang -->
                <div class="success-icon">

                    <i class="bi bi-check-lg"></i>

                </div>

                <!-- Judul -->
                <h3 class="fw-bold success-title">
                    Berhasil!
                </h3>

                <!-- Pesan -->
                <p class="text-muted mt-2 mb-4">
                    {{ session('success', 'Perubahan data profil Anda berhasil disimpan.') }}
                </p>

                <!-- Tombol -->
                <button type="button"
                        class="btn btn-success px-5 rounded-pill fw-semibold"
                        data-bs-dismiss="modal">

                    <i class="bi bi-check-circle me-1"></i>
                    Oke

                </button>

            </div>

        </div>

    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let isEditing = false;

    function toggleEditMode() {

        isEditing = !isEditing;

        const username = document.getElementById('inputUsername');
        const password = document.getElementById('inputPassword');

        const btnToggle = document.getElementById('btnToggleEdit');
        const btnText = document.getElementById('btnText');
        const iconEdit = document.getElementById('iconEdit');

        const warningBox = document.getElementById('editWarning');

        if (isEditing) {

            // Aktifkan input
            username.removeAttribute('disabled');
            password.removeAttribute('disabled');

            password.placeholder = "Isi password baru...";

            // Ubah tombol menjadi batal
            btnToggle.classList.replace(
                'btn-outline-primary',
                'btn-danger'
            );

            btnText.innerText = "Batalkan Edit";

            // Ubah icon
            iconEdit.classList.replace(
                'bi-pencil-square',
                'bi-x-lg'
            );

            // Tampilkan peringatan
            warningBox.classList.remove('d-none');
            warningBox.classList.add('d-flex');

            // Fokus ke username
            username.focus();

        } else {

            // Nonaktifkan input
            username.setAttribute('disabled', 'true');
            password.setAttribute('disabled', 'true');

            // Kosongkan password
            password.value = '';

            password.placeholder = "********";

            // Kembalikan tombol
            btnToggle.classList.replace(
                'btn-danger',
                'btn-outline-primary'
            );

            btnText.innerText = "Edit Akun";

            // Kembalikan icon
            iconEdit.classList.replace(
                'bi-x-lg',
                'bi-pencil-square'
            );

            // Sembunyikan peringatan
            warningBox.classList.add('d-none');
            warningBox.classList.remove('d-flex');

        }
    }

    // Tampilkan modal jika update berhasil
    document.addEventListener('DOMContentLoaded', function () {

        @if(session('success'))

            const successModalElement =
                document.getElementById('successModal');

            const successModal =
                new bootstrap.Modal(successModalElement);

            successModal.show();

        @endif

    });
</script>

</body>
</html>
