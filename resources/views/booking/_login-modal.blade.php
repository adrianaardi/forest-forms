<div class="modal-overlay" id="loginModal" onclick="if(event.target===this)closeLoginModal()">
    <div class="modal" style="max-width:420px;">
        <div class="modal-header">
            <h2 style="font-size:14px; color:#fff; margin:0;">Log Masuk — Sistem Tempahan</h2>
            <button class="modal-close" onclick="closeLoginModal()" style="color:rgba(255,255,255,0.7);">×</button>
        </div>
        <div class="modal-body">

            @auth('web')
                <div style="background:#faeeda; border:1px solid #f5d5a0; color:#854f0b; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px; display:flex; align-items:center; gap:0.75rem;">
                    <span style="font-size:18px;">⚠️</span>
                    <div>
                        <strong>Anda sedang log masuk sebagai admin.</strong><br>
                        Sila log keluar dahulu sebelum log masuk sebagai pengguna tempahan.
                        <form method="POST" action="{{ route('logout') }}" style="display:inline; margin-left:0.5rem;">
                            @csrf
                            <button type="submit" style="background:none; border:none; color:#854f0b; font-size:13px; cursor:pointer; text-decoration:underline; padding:0;">Log Keluar</button>
                        </form>
                    </div>
                </div>
            @endauth

            <div id="login-error" style="display:none; background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;"></div>

            <form id="login-form">
                @csrf
                <div class="form-section">
                    <div class="field">
                        <label>Emel</label>
                        <input type="email" id="login-email" name="email" placeholder="emel@domain.com" required>
                    </div>
                    <div class="field">
                        <label>Kata Laluan</label>
                        <input type="password" id="login-password" name="password" placeholder="••••••••" required>
                    </div>
                </div>
                <div class="form-footer">
                    <a href="{{ route('booking.daftar') }}" style="font-size:13px; ">Belum ada akaun? Daftar</a>
                    <button type="submit" id="login-btn" class="btn-submit" >Log Masuk</button>
                </div>
            </form>
        </div>
    </div>
</div>