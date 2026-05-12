<nav style="background:#14381f; padding:0 1.5rem; display:flex; align-items:center; gap:1.5rem; overflow-x:auto;">

    <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Hub Aplikasi</a>

    @auth('web')
        @php 
            $user = Auth::guard('web')->user(); 
            $email = $user->email;
        @endphp

        {{-- Booking admin (UNCHANGED) --}}
        @if($email === 'admin.booking@sarawak.gov.my')
            <a href="/booking/admin/dashboard" class="{{ request()->is('booking/admin/dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="/booking/admin/users" class="{{ request()->is('booking/admin/users') ? 'active' : '' }}">Pengguna</a>
        @endif

        {{-- ICT Aduan system --}}
        @if(
            $email === 'admin.aduan@sarawak.gov.my' ||
            $user->role === 'sub_admin'
        )
            <a href="/admin/ict-aduan" class="{{ request()->is('admin/ict-aduan*') ? 'active' : '' }}">Aduan ICT</a>
        @endif

        {{-- ONLY admin.aduan gets Urus Akaun --}}
        @if($email === 'admin.aduan@sarawak.gov.my')
            <a href="/admin/accounts" class="{{ request()->is('admin/accounts*') ? 'active' : '' }}">Urus Akaun</a>
        @endif

        {{-- Portal Upload admin (UNCHANGED) --}}
        @if($email === 'admin.mohon@sarawak.gov.my')
            <a href="/admin/portal-upload" class="{{ request()->is('admin/portal-upload*') ? 'active' : '' }}">Muat Naik</a>
            <a href="/admin/bahagian" class="{{ request()->is('admin/bahagian') ? 'active' : '' }}">Bahagian</a>
        @endif

        <div style="margin-left:auto; display:flex; align-items:center; gap:1rem;">
            <a href="/admin/profile"
               class="{{ request()->is('admin/profile*') ? 'active' : '' }}"
               style="font-size:13px; color:rgba(255,255,255,0.7);">
                👤 {{ $user->name }}
            </a>

            <form method="POST" action="{{ $email === 'admin.booking@sarawak.gov.my' ? route('booking.admin.logout') : route('logout') }}">
                @csrf
                <button type="submit" style="background:none; border:none; cursor:pointer; font-size:13px; color:rgba(255,255,255,0.7);">
                    Log Keluar
                </button>
            </form>
        </div>
    @endauth

    {{-- Booking user --}}
    @auth('booking_user')
        <a href="/booking/calendar" class="{{ request()->is('booking/calendar*') ? 'active' : '' }}">Tempah Bilik</a>
        <div style="margin-left:auto; display:flex; align-items:center; gap:1rem;">
            <a href="/booking/profile"
               class="{{ request()->is('booking/profile*') ? 'active' : '' }}"
               style="font-size:13px; color:rgba(255,255,255,0.7); text-decoration:none;">
                👤 {{ Auth::guard('booking_user')->user()->name }}
            </a>
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
            <div style="margin-left:auto; display:flex; align-items:center; gap:1rem;">
                <a href="/login" style="font-size:13px; color:rgba(255,255,255,0.7);">Admin</a>
            </div>
        @endguest
    @endguest

</nav>