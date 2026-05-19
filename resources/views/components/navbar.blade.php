@props(['breadcrumbs' => []])

<nav style="background:#202c38; padding:0 1.5rem; display:flex; align-items:center; gap:1.5rem; position:relative;">

    {{-- Hub Aplikasi always shows --}}
    <a href="/" style="color:{{ count($breadcrumbs) > 0 || request()->is('admin*') || request()->is('booking*') ? 'rgba(255,255,255,0.5)' : '#fff' }}; text-decoration:none; font-size:13px; white-space:nowrap;">Hub Aplikasi</a>

    {{-- Breadcrumbs --}}
    @if(count($breadcrumbs) > 0)
        @foreach($breadcrumbs as $i => $crumb)
            <span style="color:rgba(255,255,255,0.25); font-size:12px;">›</span>
            @if(isset($crumb['url']))
                <a href="{{ $crumb['url'] }}" style="color:{{ $i === count($breadcrumbs)-1 ? '#fff' : 'rgba(255,255,255,0.5)' }}; text-decoration:none; font-size:13px; white-space:nowrap;">{{ $crumb['label'] }}</a>
            @else
                <span style="color:#fff; font-size:13px; white-space:nowrap;">{{ $crumb['label'] }}</span>
            @endif
        @endforeach
    @endif

    {{-- ── Web Admin ── --}}
    @auth('web')
        @php
            $user  = Auth::guard('web')->user();
            $email = $user->email;
        @endphp

        <div style="margin-left:auto;" class="nav-dropdown-wrap">
            <button class="nav-dropdown-trigger">
                👤 {{ $user->name }} <span style="font-size:10px; opacity:0.5; margin-left:2px;">▾</span>
            </button>
            <div class="nav-dropdown">

                {{-- admin.booking --}}
                @if($email === 'admin.booking@sarawak.gov.my')
                    <div class="nav-dropdown-section">Tempahan Bilik</div>
                    <a href="/booking/admin/dashboard" class="{{ request()->is('booking/admin/dashboard') ? 'nav-dropdown-active' : '' }}">📊 Dashboard</a>
                    <a href="/booking/admin/users" class="{{ request()->is('booking/admin/users') ? 'nav-dropdown-active' : '' }}">👥 Urus Pengguna</a>
                    <a href="/booking/calendar">📅 Lihat Kalendar</a>
                @endif

                {{-- admin.aduan + sub_admin --}}
                @if($email === 'admin.aduan@sarawak.gov.my' || $user->role === 'sub_admin')
                    <div class="nav-dropdown-section">Aduan ICT</div>
                    <a href="/admin/ict-aduan" class="{{ request()->is('admin/ict-aduan*') ? 'nav-dropdown-active' : '' }}">📋 Senarai Aduan</a>
                    <a href="{{ route('admin.dashboard-ict') }}" class="{{ request()->is('admin/dashboard-ict*') ? 'nav-dropdown-active' : '' }}">📊 Dashboard ICT</a>
                    @if($email === 'admin.aduan@sarawak.gov.my')
                        <a href="/admin/accounts" class="{{ request()->is('admin/accounts*') ? 'nav-dropdown-active' : '' }}">🔑 Urus Akaun</a>
                    @endif
                @endif

                {{-- admin.mohon --}}
                @if($email === 'admin.mohon@sarawak.gov.my')
                    <div class="nav-dropdown-section">Portal Muat Naik</div>
                    <a href="/admin/dashboard-mohon" class="{{ request()->is('admin/dashboard-mohon') ? 'nav-dropdown-active' : '' }}">📊 Dashboard</a>
                    <a href="/admin/portal-upload" class="{{ request()->is('admin/portal-upload*') ? 'nav-dropdown-active' : '' }}">📂 Senarai Permohonan</a>
                    <a href="/admin/bahagian" class="{{ request()->is('admin/bahagian') ? 'nav-dropdown-active' : '' }}">🏢 Urus Bahagian</a>
                @endif

                <div class="nav-dropdown-divider"></div>
                <a href="/admin/profile" class="{{ request()->is('admin/profile*') ? 'nav-dropdown-active' : '' }}">✏️ Edit Profil</a>
                <form method="POST" action="{{ $email === 'admin.booking@sarawak.gov.my' ? route('booking.admin.logout') : route('logout') }}">
                    @csrf
                    <button type="submit">🚪 Log Keluar</button>
                </form>

            </div>
        </div>
    @endauth

    {{-- ── Booking User ── --}}
    @auth('booking_user')
        @if(count($breadcrumbs) === 0)
            <span style="color:rgba(255,255,255,0.25); font-size:12px;">›</span>
            <a href="/booking/calendar" style="color:rgba(255,255,255,0.5); text-decoration:none; font-size:13px;">Tempah Bilik Mesyuarat</a>
        @endif

        <div style="margin-left:auto;" class="nav-dropdown-wrap">
            <button class="nav-dropdown-trigger">
                👤 {{ Auth::guard('booking_user')->user()->name }} <span style="font-size:10px; opacity:0.5; margin-left:2px;">▾</span>
            </button>
            <div class="nav-dropdown">
                <div class="nav-dropdown-section">Tempahan Bilik</div>
                <a href="/booking/calendar" class="{{ request()->is('booking/calendar*') ? 'nav-dropdown-active' : '' }}">📅 Lihat Kalendar</a>
                <a href="/booking/book" class="{{ request()->is('booking/book*') ? 'nav-dropdown-active' : '' }}">➕ Buat Tempahan</a>
                <a href="/booking/my-bookings" class="{{ request()->is('booking/my-bookings*') ? 'nav-dropdown-active' : '' }}">📋 Tempahan Saya</a>
                <div class="nav-dropdown-divider"></div>
                <a href="/booking/profile" class="{{ request()->is('booking/profile*') ? 'nav-dropdown-active' : '' }}">✏️ Edit Profil</a>
                <form method="POST" action="{{ route('booking.logout') }}">
                    @csrf
                    <button type="submit">🚪 Log Keluar</button>
                </form>
            </div>
        </div>
    @endauth

    {{-- ── Guest ── --}}
    @guest('web')
        @guest('booking_user')
            <div style="margin-left:auto; display:flex; align-items:center; gap:1rem;">
                <a href="/login" style="font-size:13px; color:rgba(255,255,255,0.7); text-decoration:none;">Log Masuk Admin</a>
            </div>
        @endguest
    @endguest

</nav>

<style>
.nav-dropdown-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.nav-dropdown-trigger {
    background: none;
    border: none;
    color: rgba(255,255,255,0.75);
    font-size: 13px;
    cursor: pointer;
    padding: 0.6rem 0.5rem;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: color 0.15s;
    white-space: nowrap;
}
.nav-dropdown-trigger:hover { color: #fff; }

.nav-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 10px;
    box-shadow: 0 8px 28px rgba(0,0,0,0.13);
    min-width: 200px;
    z-index: 1000;
    overflow: hidden;
    animation: dropFade 0.18s ease;
}

@keyframes dropFade {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}

.nav-dropdown-section {
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #bbb;
    padding: 8px 14px 4px;
}

.nav-dropdown a,
.nav-dropdown form button {
    display: block;
    width: 100%;
    padding: 8px 14px;
    font-size: 13px;
    color: #333;
    text-decoration: none;
    background: none;
    border: none;
    text-align: left;
    cursor: pointer;
    transition: background 0.1s, color 0.1s;
    white-space: nowrap;
    box-sizing: border-box;
}

.nav-dropdown a:hover,
.nav-dropdown form button:hover {
    background: #f5f5f5;
    color: #1a4731;
}

.nav-dropdown-active {
    background: #f0f4f1 !important;
    color: #1a4731 !important;
    font-weight: 500;
}

.nav-dropdown-divider {
    height: 1px;
    background: #f0f0f0;
    margin: 4px 0;
}

.nav-dropdown-wrap:hover .nav-dropdown,
.nav-dropdown-wrap:focus-within .nav-dropdown {
    display: block;
}
</style>