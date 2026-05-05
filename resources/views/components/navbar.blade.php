<nav style="background:#14381f; padding:0 1.5rem; display:flex; align-items:center; gap:1.5rem; overflow-x:auto;">

    <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Laman Utama</a>

    @auth('web')
        @php $email = Auth::guard('web')->user()->email; @endphp

        {{-- Booking admin --}}
        @if($email === 'admin.booking@sarawak.gov.my')
            <a href="/booking/admin/dashboard" class="{{ request()->is('booking/admin/dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="/booking/admin/users" class="{{ request()->is('booking/admin/users') ? 'active' : '' }}">Pengguna</a>

        {{-- ICT aduan admin --}}
        @elseif($email === 'admin.aduan@sarawak.gov.my')
            <a href="/admin/ict-aduan" class="{{ request()->is('admin/ict-aduan*') ? 'active' : '' }}">Aduan ICT</a>

        {{-- Portal upload admin --}}
        @elseif($email === 'admin.mohon@sarawak.gov.my')
            <a href="/admin/portal-upload" class="{{ request()->is('admin/portal-upload*') ? 'active' : '' }}">Muat Naik</a>
            <a href="/admin/bahagian" class="{{ request()->is('admin/bahagian') ? 'active' : '' }}">Bahagian</a>
        @endif

        {{-- Profile + logout --}}
        <div style="margin-left:auto; display:flex; align-items:center; gap:1rem;">
            @if($email !== 'admin.booking@sarawak.gov.my')
                <a href="/admin/profile"
                   class="{{ request()->is('admin/profile*') ? 'active' : '' }}"
                   style="font-size:13px; color:rgba(255,255,255,0.7);">
                    👤 {{ Auth::guard('web')->user()->name }}
                </a>
            @else
                <span style="font-size:13px; color:rgba(255,255,255,0.7);">
                    👤 {{ Auth::guard('web')->user()->name }}
                </span>
            @endif

            <form method="POST" action="{{ $email === 'admin.booking@sarawak.gov.my' ? route('booking.admin.logout') : route('logout') }}">
                @csrf
                <button type="submit" style="background:none; border:none; cursor:pointer; font-size:13px; color:rgba(255,255,255,0.7);">
                    Log Keluar
                </button>
            </form>
        </div>
    @endauth

    @auth('booking_user')
        <a href="/booking/calendar" class="{{ request()->is('booking/calendar*') ? 'active' : '' }}">Kalendar</a>

        <div style="margin-left:auto; display:flex; align-items:center; gap:1rem;">
            <span style="font-size:13px; color:rgba(255,255,255,0.7);">
                👤 {{ Auth::guard('booking_user')->user()->name }}
            </span>
            <form method="POST" action="{{ route('booking.logout') }}">
                @csrf
                <button type="submit" style="background:none; border:none; cursor:pointer; font-size:13px; color:rgba(255,255,255,0.7);">
                    Log Keluar
                </button>
            </form>
        </div>
    @endauth

    @guest('web')
        @guest('booking_user')
            <div style="margin-left:auto;">
                <a href="/login" style="font-size:13px; color:rgba(255,255,255,0.7);">Admin</a>
            </div>
        @endguest
    @endguest

</nav>