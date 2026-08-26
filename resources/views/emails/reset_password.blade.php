<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Akun</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background-color: #f8fafc; margin: 0; padding: 40px 20px;">
    <div style="max-width: 500px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; padding: 32px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <!-- Logo Header -->
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="display: inline-block; width: 56px; height: 56px; background-color: #2563eb; border-radius: 14px; line-height: 56px; color: #ffffff; font-size: 24px; font-weight: bold; text-align: center;">
                SIBI
            </div>
            <h2 style="color: #0f172a; margin-top: 16px; font-size: 22px; font-weight: 700;">Atur Ulang Kata Sandi</h2>
        </div>
        
        <!-- Content -->
        <p style="color: #475569; font-size: 15px; line-height: 1.6; margin-bottom: 12px;">
            Halo <strong>{{ $userName }}</strong>,
        </p>
        <p style="color: #475569; font-size: 15px; line-height: 1.6; margin-bottom: 24px;">
            Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda di <strong>SIBI Dataset Platform</strong>. Silakan klik tombol di bawah ini untuk membuat kata sandi baru:
        </p>
        
        <!-- Action Button -->
        <div style="text-align: center; margin: 32px 0;">
            <a href="{{ $resetUrl }}" style="display: inline-block; background-color: #2563eb; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 12px; font-size: 15px; font-weight: 700; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                Atur Ulang Kata Sandi Saya
            </a>
        </div>
        
        <p style="color: #64748b; font-size: 13px; text-align: center; margin-top: 24px; line-height: 1.5;">
            Tautan reset password ini berlaku selama <strong>60 menit</strong>. Jika Anda tidak merasa meminta reset password, abaikan email ini dan kata sandi Anda tidak akan berubah.
        </p>

        <!-- Direct Link Backup -->
        <div style="margin-top: 24px; padding: 12px; bg-color: #f1f5f9; border-radius: 8px; font-size: 11px; color: #64748b; word-break: break-all;">
            Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut di browser Anda:<br>
            <a href="{{ $resetUrl }}" style="color: #2563eb;">{{ $resetUrl }}</a>
        </div>
        
        <!-- Footer Divider -->
        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 32px 0 20px 0;">
        <p style="color: #94a3b8; font-size: 12px; text-align: center; margin: 0;">
            © {{ date('Y') }} SIBI Dataset Platform. Hak Cipta Dilindungi.
        </p>
    </div>
</body>
</html>
