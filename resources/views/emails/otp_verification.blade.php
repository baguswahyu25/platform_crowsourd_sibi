<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Verifikasi Email</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background-color: #f8fafc; margin: 0; padding: 40px 20px;">
    <div style="max-width: 500px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; padding: 32px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <!-- Logo Header -->
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="display: inline-block; width: 56px; height: 56px; background-color: #2563eb; border-radius: 14px; line-height: 56px; color: #ffffff; font-size: 24px; font-weight: bold; text-align: center;">
                SIBI
            </div>
            <h2 style="color: #0f172a; margin-top: 16px; font-size: 22px; font-weight: 700;">Verifikasi Email Anda</h2>
        </div>
        
        <!-- Greeting & Content -->
        <p style="color: #475569; font-size: 15px; line-height: 1.6; margin-bottom: 12px;">
            Halo <strong>{{ $userName }}</strong>,
        </p>
        <p style="color: #475569; font-size: 15px; line-height: 1.6; margin-bottom: 24px;">
            Terima kasih telah mendaftar di <strong>SIBI Dataset Platform</strong>. Gunakan kode OTP 6-digit di bawah ini untuk memverifikasi akun email Anda:
        </p>
        
        <!-- OTP Display Box -->
        <div style="text-align: center; margin: 28px 0;">
            <div style="display: inline-block; background-color: #eff6ff; border: 2px dashed #2563eb; padding: 16px 32px; border-radius: 12px; font-size: 32px; font-weight: 800; letter-spacing: 8px; color: #1d4ed8;">
                {{ $otpCode }}
            </div>
        </div>
        
        <!-- Expiration Notice -->
        <p style="color: #64748b; font-size: 13px; text-align: center; margin-top: 24px; line-height: 1.5;">
            Kode OTP ini berlaku selama <strong>10 menit</strong>. Jangan berikan kode ini kepada siapa pun demi menjaga keamanan akun Anda.
        </p>
        
        <!-- Footer Divider -->
        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 32px 0 20px 0;">
        <p style="color: #94a3b8; font-size: 12px; text-align: center; margin: 0;">
            © {{ date('Y') }} SIBI Dataset Platform. Hak Cipta Dilindungi.
        </p>
    </div>
</body>
</html>
