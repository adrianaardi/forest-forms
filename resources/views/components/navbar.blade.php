<nav class="booking-nav">
    <!-- 1. COMMON HOME LINKS -->
    <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Laman Utama</a>
    <a href="/booking" class="{{ request()->is('booking') ? 'active' : '' }}">Tempahan</a>

    <!-- 2. ADMIN/SUB-ADMIN (Guard: web) -->
    @auth('web')
        <!-- ICT Aduan Admin -->
        @if(Auth::guard('web')->user()->email === 'admin.aduan@sarawak.gov.my')
            <a href="/admin/ict-aduan" class="{{ request()->is('admin/ict-aduan*') ? 'active' : '' }}">Aduan ICT</a>
        @endif

        <!-- Super Admin Accounts -->
        @if(Auth::guard('web')->user()->role === 'admin')
            <a href="/admin/accounts" class="{{ request()->is('admin/accounts*') ? 'active' : '' }}">Urus Akaun</a>
        @endif

        <!-- Sub-Admin ICT -->
        @if(Auth::guard('web')->user()->role === 'sub_admin')
            <a href="/admin/ict-aduan" class="{{ request()->is('admin/ict-aduan*') ? 'active' : '' }}">
                ICT Aduan ({{ Auth::guard('web')->user()->wilayah->nama_wilayah ?? '' }})
            </a>
        @endif

        <!-- Portal/Bahagian Admin -->
        @if(Auth::guard('web')->user()->email === 'admin.mohon@sarawak.gov.my')
            <a href="/admin/portal-upload" class="{{ request()->is('admin/portal-upload*') ? 'active' : '' }}">Muat Naik</a>
            <a href="/admin/bahagian" class="{{ request()->is('admin/bahagian') ? 'active' : '' }}">Bahagian</a>
        @endif

        <!-- Booking Admin (Specific Email) -->
        @if(Auth::guard('web')->user()->email === 'admin.booking@sarawak.gov.my')
            <a href="/booking/admin/dashboard" class="{{ request()->is('booking/admin/dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="/booking/admin/tempahan" class="{{ request()->is('booking/admin/tempahan') ? 'active' : '' }}">Tempahan</a>
            <a href="/booking/admin/users" class="{{ request()->is('booking/admin/users') ? 'active' : '' }}">Pengguna</a>
            <a href="/booking/admin/bilik" class="{{ request()->is('booking/admin/bilik') ? 'active' : '' }}">Bilik</a>
        @endif
    @endauth

    <!-- 3. BOOKING USER (Guard: booking_user) -->
    @auth('booking_user')
        <a href="/booking/user/dashboard" class="{{ request()->is('booking/user/*') ? 'active' : '' }}">Tempahan Saya</a>
    @endauth

    <!-- 4. RIGHT ALIGNED SECTION (Profile & Logout) -->
    <div class="nav-right" style="margin-left: auto; display: flex; align-items: center; gap: 1rem;">
        
        <!-- UI for Web Admin -->
        @auth('web')
            <a href="/admin/profile" class="{{ request()->is('admin/profile') ? 'active' : '' }}" style="font-size:13px; color:rgba(255,255,255,0.7);">
                👤 {{ Auth::guard('web')->user()->name }}
            </a>
            
            <form method="POST" action="{{ Auth::guard('web')->user()->email === 'admin.booking@sarawak.gov.my' ? route('booking.admin.logout') : route('logout') }}">
                @csrf
                <button type="submit" style="background:none; border:none; cursor:pointer; font-size:13px; color:rgba(255,255,255,0.7);">
                    Log Keluar
                </button>
            </form>
        @endauth

        <!-- UI for Booking User -->
        @auth('booking_user')
            <span style="font-size:13px; color:rgba(255,255,255,0.7);">👤 {{ Auth::guard('booking_user')->user()->name }}</span>
            <form method="POST" action="{{ route('booking.user.logout') }}">
                @csrf
                <button type="submit" style="background:none; border:none; cursor:pointer; font-size:13px; color:rgba(255,255,255,0.7);">
                    Log Keluar
                </button>
            </form>
        @endauth

        <!-- Guest Links -->
        @if(!Auth::guard('web')->check() && !Auth::guard('booking_user')->check())
            <a href="/login">Admin</a>
            <a href="/booking/login">Log Masuk</a>
            <a href="/booking/daftar">Daftar</a>
        @endif
    </div>
</nav>