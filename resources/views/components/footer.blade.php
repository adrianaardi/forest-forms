<footer>
    <div class="footer-content">
        <strong>Seksyen Pengurusan Dan Transformasi Digital</strong>
        <span class="divider">|</span>
        Tingkat 15, Bangunan Baitul Makmur II, Medan Raya, Petra Jaya, 93050 Kuching, Sarawak
    </div>

    <div class="footer-right">
        <span>© {{ date('Y') }} Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</span>

        @guest('web')
            @guest('booking_user')
                <a href="/login" class="footer-login-link" title="Login">
                    <svg class="user-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <circle cx="12" cy="10" r="3" />
                        <path d="M7 18c0-2.5 2-4.5 5-4.5s5 2 5 4.5" />
                    </svg>
                </a>
            @endguest
        @endguest
    </div>
</footer>
