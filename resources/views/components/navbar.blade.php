@props(['breadcrumbs' => []])

<nav>  
    <div>

        {{-- Hub Aplikasi always shows --}}
        <a href="/">
            Hub Aplikasi
        </a>

        {{-- Breadcrumbs --}}
        @if(count($breadcrumbs) > 0)
            @foreach($breadcrumbs as $i => $crumb)
                <span>›</span>

                @if(isset($crumb['url']))
                    <a href="{{ $crumb['url'] }}">
                        {{ $crumb['label'] }}
                    </a>
                @else
                    <span>
                        {{ $crumb['label'] }}
                    </span>
                @endif
            @endforeach
        @endif

    </div>


    <div>

        {{-- ── Web Admin ── --}}
        @auth('web')

            @php
                $user  = Auth::guard('web')->user();
                $email = $user->email;
            @endphp

            <div class="nav-dropdown-wrap">
                <button type="button" class="nav-dropdown-trigger">
                    👤 {{ $user->name }}
                    <span>▾</span>
                </button>

                <div class="nav-dropdown">

                    {{-- admin.booking --}}
                    @if($email === 'admin.booking@sarawak.gov.my')
                        <div class="nav-dropdown-section">Tempahan Bilik</div>
                        <a href="/booking/admin/dashboard">📊 Dashboard</a>
                        <a href="/booking/admin/activity-log">📝 Log Aktiviti</a>
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

        <a href="{{ url('/help') }}">
                Manual Pengguna
        </a>

        {{-- ── Booking User ── --}}
        @auth('booking_user')

            <div class="nav-dropdown-wrap">
                <button type="button" class="nav-dropdown-trigger">
                    👤 {{ Auth::guard('booking_user')->user()->name }}
                    <span>▾</span>
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

    </div>

</nav>

{{-- JS to handle Click Toggle and Click-Outside behavior --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dropdownWraps = document.querySelectorAll('.nav-dropdown-wrap');

        dropdownWraps.forEach(wrap => {
            const trigger = wrap.querySelector('.nav-dropdown-trigger');

            trigger.addEventListener('click', (e) => {
                e.stopPropagation();

                // Close all other open dropdowns
                dropdownWraps.forEach(other => {
                    if (other !== wrap) other.classList.remove('is-open');
                });

                // Toggle current dropdown
                wrap.classList.toggle('is-open');
            });
        });

        // Close dropdown when clicking anywhere outside
        document.addEventListener('click', () => {
            dropdownWraps.forEach(wrap => wrap.classList.remove('is-open'));
        });
    });
</script>