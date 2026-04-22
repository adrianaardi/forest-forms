<nav>
    <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Laman Utama</a>

    @auth
        @if(Auth::user()->email === 'admin.aduan@sarawak.gov.my')
            <a href="/admin/ict-aduan" class="{{ request()->is('admin/ict-aduan*') ? 'active' : '' }}">Aduan ICT</a>
        @endif

        @if(Auth::user()->email === 'admin.mohon@sarawak.gov.my')
            <a href="/admin/portal-upload" class="{{ request()->is('admin/portal-upload*') ? 'active' : '' }}">Muat Naik</a>
            <a href="/admin/bahagian" class="{{ request()->is('admin/bahagian') ? 'active' : '' }}">Bahagian</a>
        @endif

        <a href="/admin/profile" class="{{ request()->is('admin/profile') ? 'active' : '' }}" style="margin-left:auto;">
            👤 {{ Auth::user()->name }}
        </a>

        <form method="POST" action="{{ route('logout') }}" style="display:flex; align-items:center; margin-left:1rem;">
            @csrf
            <button type="submit" style="background:none; border:none; cursor:pointer; font-size:13px; color:rgba(255,255,255,0.7); padding:0;">
                Log Keluar
            </button>
        </form>
    @endauth

    @guest
        <a href="/login" style="margin-left:auto;">Admin</a>
    @endguest
</nav>