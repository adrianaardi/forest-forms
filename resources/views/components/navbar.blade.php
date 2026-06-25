@props(['breadcrumbs' => []])

<nav style="background:#213458; padding:0 1.5rem; display:flex; flex-wrap:wrap; align-items:center; gap:1.5rem; row-gap:0.5rem; position:relative;">    {{-- LEFT SIDE --}}
    <div style="display:flex; align-items:center; gap:1.5rem; white-space:nowrap;">

        {{-- Hub Aplikasi always shows --}}
        <a href="/"
           style="color:{{ count($breadcrumbs) > 0 || request()->is('admin*') || request()->is('booking*') ? 'rgba(255,255,255,0.5)' : '#fff' }};
                  text-decoration:none;
                  font-size:13px;
                  white-space:nowrap;">
            Hub Aplikasi
        </a>

        {{-- Breadcrumbs --}}
        @if(count($breadcrumbs) > 0)
            @foreach($breadcrumbs as $i => $crumb)
                <span style="color:rgba(255,255,255,0.25); font-size:12px;">›</span>

                @if(isset($crumb['url']))
                    <a href="{{ $crumb['url'] }}"
                       style="color:{{ $i === count($breadcrumbs)-1 ? '#fff' : 'rgba(255,255,255,0.5)' }};
                              text-decoration:none;
                              font-size:13px;
                              white-space:nowrap;">
                        {{ $crumb['label'] }}
                    </a>
                @else
                    <span style="color:#fff; font-size:13px; white-space:nowrap;">
                        {{ $crumb['label'] }}
                    </span>
                @endif
            @endforeach
        @endif

    </div>

    {{-- FLEX SPACER (THIS FIXES ALIGNMENT) --}}
    <div style="flex:1;"></div>

    {{-- RIGHT SIDE --}}
    <div style="display:flex; align-items:center; gap:1rem; white-space:nowrap;">

        {{-- ── Web Admin ── --}}
        @auth('web')

            @php
                $user  = Auth::guard('web')->user();
                $email = $user->email;
            @endphp

            <div class="nav-dropdown-wrap">
                <button class="nav-dropdown-trigger">
                    👤 {{ $user->name }}
                    <span style="font-size:10px; opacity:0.5; margin-left:2px;">▾</span>
                </button>

                <div class="nav-dropdown">

                    {{-- admin.booking --}}
                    @if($email === 'admin.booking@sarawak.gov.my')
                        <div class="nav-dropdown-section">Tempahan Bilik</div>
                        <a href="/booking/admin/dashboard">📊 Dashboard</a>
                        <a href="/booking/admin/users">👥 Urus Pengguna</a>
                        <a href="/booking/calendar">📅 Lihat Kalendar</a>
                    @endif

                    {{-- admin.aduan + sub_admin --}}
                    @if($email === 'admin.aduan@sarawak.gov.my' || $user->role === 'sub_admin')
                        <div class="nav-dropdown-section">Aduan ICT</div>
                        <a href="/admin/ict-aduan">📋 Senarai Aduan</a>
                        <a href="{{ route('admin.dashboard-ict') }}">📊 Dashboard ICT</a>

                        @if($email === 'admin.aduan@sarawak.gov.my')
                            <a href="/admin/accounts">🔑 Urus Akaun</a>
                        @endif
                    @endif

                    {{-- admin.mohon --}}
                    @if($email === 'admin.mohon@sarawak.gov.my')
                        <div class="nav-dropdown-section">Portal Muat Naik</div>
                        <a href="/admin/dashboard-mohon">📊 Dashboard</a>
                        <a href="/admin/portal-upload">📂 Senarai Permohonan</a>
                        <a href="/admin/bahagian">➕ Tambah Supervisor Bahagian</a>
                    @endif

                    {{-- Pergerakan Pegawai --}}
                    @if($email === 'admin.pergerakan@sarawak.gov.my' || $user->role === 'subadmin' || $user->role === 'subadmin_pergerakan')
                        <div class="nav-dropdown-section">Pergerakan Pegawai</div>
                        <a href="{{ route('admin.pergerakan.index') }}">
                            📊 Dashboard
                        </a>
                    @endif

                    <div class="nav-dropdown-divider"></div>

                    <a href="/admin/profile">✏️ Edit Profile</a>

                    <form method="POST"
                          action="{{ $email === 'admin.booking@sarawak.gov.my'
                              ? route('booking.admin.logout')
                              : route('logout') }}">
                        @csrf
                        <button type="submit">🚪 Log Keluar</button>
                    </form>

                </div>
            </div>

        @endauth

        <a href="{{ url('/help') }}"
            style="color:rgba(255,255,255,0.7);
                    text-decoration:none;
                    font-size:13px;
                    white-space:nowrap;">
                Manual Pengguna
        </a>
        {{-- ── Booking User ── --}}
        @auth('booking_user')

            <div class="nav-dropdown-wrap">
                <button class="nav-dropdown-trigger">
                    👤 {{ Auth::guard('booking_user')->user()->name }}
                    <span style="font-size:10px; opacity:0.5; margin-left:2px;">▾</span>
                </button>

                <div class="nav-dropdown">

                    <div class="nav-dropdown-section">Tempahan Bilik</div>
                    <a href="/booking/calendar">📅 Lihat Kalendar</a>
                    <a href="/booking/book">➕ Buat Tempahan</a>
                    <a href="/booking/my-bookings">📋 Tempahan Saya</a>

                    <div class="nav-dropdown-divider"></div>

                    <a href="/booking/profile">✏️ Edit Profile</a>

                    <form method="POST" action="{{ route('booking.logout') }}">
                        @csrf
                        <button type="submit">🚪 Log Keluar</button>
                    </form>
                </div>
            </div>

        @endauth

        @guest('web')
            @guest('booking_user')      
                <a href="/login"
                   style="color:rgba(255,255,255,0.7);
                          text-decoration:none;
                          font-size:13px;
                          white-space:nowrap;">
                    Log Masuk Admin
                </a>
            @endguest
        @endguest

    </div>

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
    white-space: nowrap;
}

.nav-dropdown-trigger:hover {
    color: #fff;
}

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
}

.nav-dropdown-wrap:hover .nav-dropdown,
.nav-dropdown-wrap:focus-within .nav-dropdown {
    display: block;
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
}

.nav-dropdown a:hover,
.nav-dropdown form button:hover {
    background: #f5f5f5;
}

.nav-dropdown-divider {
    height: 1px;
    background: #eee;
    margin: 4px 0;
}
</style>