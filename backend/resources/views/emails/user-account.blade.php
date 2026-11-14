<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Akun KasirApp</title>
</head>
<body style="margin: 0; padding: 0; background-color: #edf2f8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #edf2f8; padding: 40px 15px;">
        <tr>
            <td align="center">

                <!-- Main Container -->
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">

                    {{-- HEADER --}}
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #19387c 0%, #2563eb 100%); padding: 45px 25px;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold; letter-spacing: 1px;">
                                🚀 KasirApp
                            </h1>
                            <p style="margin: 10px 0 0; color: #cbd5e1; font-size: 12px; letter-spacing: 2px; text-transform: uppercase;">
                                Cashier Management System
                            </p>
                            <div style="width: 50px; height: 3px; background-color: #60a5fa; margin: 20px auto 0; border-radius: 2px;"></div>
                        </td>
                    </tr>

                    {{-- CONTENT --}}
                    <tr>
                        <td style="padding: 40px 35px;">

                            <p style="margin: 0 0 10px; color: #2563eb; font-size: 11px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase;">
                                Account Activation
                            </p>

                            <h2 style="margin: 0 0 15px; color: #1e293b; font-size: 24px; line-height: 1.4;">
                                Selamat Datang, Pengguna KasirApp! 
                            </h2>

                            <p style="margin: 0 0 25px; color: #475569; font-size: 15px; line-height: 1.7;">
                                Akun kamu telah berhasil dibuat. Sekarang kamu dapat menggunakan KasirApp untuk mendukung seluruh aktivitas operasional kasir dengan mudah. Berikut adalah informasi kredensial akun kamu:
                            </p>

                            {{-- LOGIN CARD --}}
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <p style="margin: 0 0 6px; color: #64748b; font-size: 11px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase;">
                                            Username
                                        </p>
                                        <p style="margin: 0 0 20px; color: #19387c; font-size: 18px; font-weight: bold; word-break: break-word;">
                                            {{ $username }}
                                        </p>

                                        <div style="height: 1px; background-color: #e2e8f0; margin-bottom: 20px;"></div>

                                        <p style="margin: 0 0 6px; color: #64748b; font-size: 11px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase;">
                                            Password
                                        </p>
                                        <p style="margin: 0; color: #19387c; font-size: 18px; font-weight: bold; word-break: break-word; letter-spacing: 1px;">
                                            {{ $password }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- QUICK LINKS (DATA DIRI & EDIT AKUN) --}}
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f1f5f9; border-radius: 10px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 10px; color: #334155; font-size: 13px; font-weight: bold;">
                                            Kelola Profil & Akun Anda: 
                                            <a href="{{ route('user.edit',$uuid) }}" style="color: #2563eb; text-decoration: underline;">Edit Profil</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- SECURITY ALERT --}}
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #fffbeb; border-radius: 8px; border-left: 4px solid #f59e0b;">
                                <tr>
                                    <td style="padding: 18px 20px;">
                                        <p style="margin: 0 0 6px; color: #92400e; font-size: 13px; font-weight: bold;">
                                            Perhatian Keamanan
                                        </p>
                                        <p style="margin: 0; color: #92400e; font-size: 12px; line-height: 1.6;">
                                            Jangan bagikan informasi login ini kepada siapa pun. Segera ubah password Anda demi keamanan setelah berhasil masuk ke aplikasi.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 30px 0 0; color: #64748b; font-size: 14px; line-height: 1.6;">
                                Terima kasih telah bergabung!<br>
                                <strong>Tim KasirApp</strong>
                            </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td align="center" style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 25px 20px;">
                            <h3 style="margin: 0 0 8px; color: #19387c; font-size: 15px;">
                                KasirApp
                            </h3>
                            <p style="margin: 0; color: #94a3b8; font-size: 11px; line-height: 1.6;">
                                Cashier Management System<br>
                                Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
                            </p>
                            <p style="margin: 15px 0 0; color: #cbd5e1; font-size: 11px;">
                                &copy; {{ date('Y') }} KasirApp. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>