<div class="ticket-modal-overlay" id="loginModal">
    <div class="ticket-modal">
        <div class="ticket-modal-header">
            <h3>Log Masuk</h3>
            <button class="ticket-modal-close" onclick="closeLoginModal()">×</button>
        </div>
        <div class="ticket-modal-body">

            @auth('web')
                <div class="detail-field login-admin-warning">
                    <span class="bk-room-pill-icon">⚠️</span>
                    <div>
                        <strong>Anda sedang log masuk sebagai admin.</strong><br>
                        Sila log keluar dahulu sebelum log masuk sebagai pengguna tempahan.
                        <form method="POST" action="{{ route('logout') }}" class="logout-inline-form">
                            @csrf
                            <button type="submit" class="logout-inline-btn">Log Keluar</button>
                        </form>
                    </div>
                </div>
            @endauth

            <div id="login-error" class="form-error-box alert alert-error is-hidden"></div>
            <div id="login-resend-wrap" class="is-hidden" style="margin: 0.5rem 0 0.75rem;">
                <button type="button" id="login-resend-btn" style="background: transparent; border: 0; padding: 0; color: var(--primary); text-decoration: underline; cursor: pointer; font-size: 0.9rem;">
                    Hantar semula pautan pengesahan emel
                </button>
            </div>

            <form id="login-form">
                @csrf
                <div class="form-section">
                    <div class="field">
                        <label>Emel</label>
                        <input type="email" id="login-email" name="email" required>
                    </div>
                    <div class="field">
                        <label>Kata Laluan</label>
                        <input type="password" id="login-password" name="password" required>
                    </div>
                </div>
                <div class="form-footer">
                    <div class="login-links">
                        <a href="{{ route('booking.daftar') }}" style="color: var(--text-dark);">Daftar akaun disini</a><br>
                        <a href="{{ route('booking.password.request') }}" style="color: var(--text-muted); font-size: 0.875rem;">Lupa kata laluan?</a>
                    </div>
                    <div class="login-actions">
                        <button type="submit" id="login-btn" class="btn-submit">Log Masuk</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>