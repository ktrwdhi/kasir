<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Akun KasirApp</title>
</head>
<body style="margin: 0; padding: 0; background-color: #eef2f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #0f172a;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #eef2f6; padding: 40px 16px;">
        <tr>
            <td align="center">

                <!-- Wrapper -->
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 24px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 20px 45px -15px rgba(15, 23, 42, 0.12);">

                    <!-- TOP BRAND BAR -->
                    <tr>
                        <td style="background-color: #0f172a; padding: 28px 36px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="left" valign="middle">
                                        <span style="display: inline-block; width: 10px; height: 10px; background-color: #38bdf8; border-radius: 50%; margin-right: 8px;"></span>
                                        <span style="color: #ffffff; font-size: 20px; font-weight: 800; letter-spacing: -0.3px; vertical-align: middle;">
                                            KasirApp
                                        </span>
                                    </td>
                                    <td align="right" valign="middle">
                                        <span style="display: inline-block; background-color: rgba(56, 189, 248, 0.15); color: #7dd3fc; border: 1px solid rgba(125, 211, 252, 0.25); font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; padding: 5px 12px; border-radius: 999px;">
                                            Akun Baru
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- HERO ACCENT STRIP -->
                    <tr>
                        <td style="background: linear-gradient(90deg, #19387c 0%, #2563eb 60%, #0ea5e9 100%); height: 5px; font-size: 0; line-height: 0;">
                            &nbsp;
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td style="padding: 36px 36px 32px;">

                            <h1 style="margin: 0 0 10px; color: #0f172a; font-size: 24px; font-weight: 800; letter-spacing: -0.4px; line-height: 1.3;">
                                Selamat datang di KasirApp
                            </h1>

                            <p style="margin: 0 0 28px; color: #475569; font-size: 15px; line-height: 1.65;">
                                Akun operasional kamu sudah aktif dan siap dipakai. Gunakan kredensial di bawah ini untuk login, lalu atur ulang profil serta password kamu.
                            </p>

                            <!-- 2-COLUMN CREDENTIALS CARD -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; margin-bottom: 28px;">
                                <tr>
                                    <td width="50%" valign="top" style="padding: 20px; border-right: 1px solid #e2e8f0;">
                                        <div style="color: #64748b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">
                                            Username
                                        </div>
                                        <div style="color: #19387c; font-size: 15px; font-weight: 700; font-family: 'Cascadia Code', Consolas, 'Courier New', monospace; word-break: break-all;">
                                            {{ $username }}
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 20px;">
                                        <div style="color: #64748b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">
                                            Password Awal
                                        </div>
                                        <div style="color: #0f172a; font-size: 15px; font-weight: 700; font-family: 'Cascadia Code', Consolas, 'Courier New', monospace; word-break: break-all;">
                                            {{ $password }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- ACTION BOX -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f0f9ff; border: 1px solid #bae6fd; border-radius: 16px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 22px 24px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td valign="middle" style="padding-bottom: 14px;">
                                                    <div style="color: #0369a1; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                                        Aktivasi &amp; Lengkapi Profil
                                                    </div>
                                                    <div style="color: #334155; font-size: 13.5px; line-height: 1.5;">
                                                        Sesuaikan nama lengkap dan ganti password awal agar akun lebih aman.
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="middle">
                                                    <a href="{{ route('user.edit', $uuid) }}"
                                                       style="display: inline-block; background-color: #19387c; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; padding: 12px 24px; border-radius: 12px;">
                                                        Lengkapi Data Anda &rarr;
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- SECURITY NOTE -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 28px;">
                                <tr>
                                    <td width="4" style="background-color: #f59e0b; border-radius: 4px;"></td>
                                    <td style="padding: 6px 0 6px 14px; color: #64748b; font-size: 12.5px; line-height: 1.6;">
                                        <strong style="color: #334155;">Tips keamanan:</strong> Jangan bagikan kredensial ini ke siapa pun dan segera ganti password setelah login pertama.
                                    </td>
                                </tr>
                            </table>

                            <div style="border-top: 1px solid #f1f5f9; padding-top: 20px; color: #475569; font-size: 14px; line-height: 1.6;">
                                Sampai jumpa di dashboard,<br>
                                <strong style="color: #0f172a;">Tim KasirApp</strong>
                            </div>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td align="center" style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 22px 24px;">
                            <div style="color: #475569; font-size: 12px; font-weight: 600;">
                                KasirApp &bull; Cashier Management System
                            </div>
                            <div style="color: #94a3b8; font-size: 11px; margin-top: 6px; line-height: 1.5;">
                                Email otomatis — mohon tidak membalas pesan ini.<br>
                                &copy; {{ date('Y') }} KasirApp. All rights reserved.
                            </div>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>