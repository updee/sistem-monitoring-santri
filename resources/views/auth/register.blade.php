{{--
    resources/views/auth/register.blade.php
    PENTING: Timpa file bawaan Laravel UI
--}}
@extends('layouts.auth')
@section('title', 'Daftar Akun Wali Santri')

@section('content')
<div class="auth-card">

    <div class="auth-left">
        <div class="auth-logo-box">AS</div>
        <div class="auth-school-name">Daftar Akun<br>Wali Santri</div>
        <div class="auth-school-sub">
            Buat akun untuk memantau perkembangan santri secara real-time melalui
            Sistem Monitoring IBS Ash-Shiddiiqi Jambi.
        </div>
        <div class="auth-motto">
            "Pemantauan aktif orang tua adalah bagian dari amanah pendidikan"
        </div>
        <div class="auth-accent-bars">
            <div class="auth-bar active" style="flex:2;"></div>
            <div class="auth-bar active" style="flex:2;"></div>
            <div class="auth-bar" style="flex:1;"></div>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-form-title">Buat Akun Wali Santri</div>
        <div class="auth-form-sub">Lengkapi data diri Anda sebagai wali santri</div>

        @if ($errors->any())
            <div class="alert-error">
                <ul style="margin:0;padding-left:16px;">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group-auth">
                <label class="form-label-custom">Nama Lengkap <span style="color:#e53935;">*</span></label>
                <input type="text" name="name"
                    class="form-input-custom {{ $errors->has('name') ? 'is-invalid' : '' }}"
                    value="{{ old('name') }}"
                    placeholder="Nama lengkap Anda" required>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group-auth">
                    <label class="form-label-custom">Email <span style="color:#e53935;">*</span></label>
                    <input type="email" name="email"
                        class="form-input-custom {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        value="{{ old('email') }}"
                        placeholder="email@gmail.com" required>
                </div>
                <div class="form-group-auth">
                    <label class="form-label-custom">No. Telepon</label>
                    <input type="text" name="no_telepon"
                        class="form-input-custom"
                        value="{{ old('no_telepon') }}"
                        placeholder="0812-xxxx-xxxx">
                </div>
                <div class="form-group-auth">
                    <label class="form-label-custom">Password <span style="color:#e53935;">*</span></label>
                    <input type="password" name="password"
                        class="form-input-custom {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Minimal 8 karakter" required>
                </div>
                <div class="form-group-auth">
                    <label class="form-label-custom">Konfirmasi Password <span style="color:#e53935;">*</span></label>
                    <input type="password" name="password_confirmation"
                        class="form-input-custom"
                        placeholder="Ulangi password" required>
                </div>
            </div>

            <div style="background:var(--hijau-pale);border:1px solid #b8d8c0;border-radius:8px;padding:10px 14px;font-size:12px;color:var(--hijau);margin-bottom:18px;display:flex;gap:8px;align-items:flex-start;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>Akun akan dihubungkan ke data santri oleh Admin setelah pendaftaran. Hubungi admin pesantren untuk proses verifikasi.</div>
            </div>

            <button type="submit" class="btn-auth">Buat Akun</button>

            <div class="auth-divider">atau</div>
            <div style="text-align:center;font-size:13px;color:var(--txt3);">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="auth-footer-link">Masuk di sini</a>
            </div>
        </form>
    </div>
</div>
@endsection
