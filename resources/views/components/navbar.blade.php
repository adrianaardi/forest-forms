
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

    @if(session('daftar_success'))
    <div id="daftar-modal" class="daftar-overlay">
    <div class="daftar-card">
        <div class="daftar-emoji">🎉</div>
        <h3 class="daftar-title">Pendaftaran Berjaya!</h3>
        <p class="daftar-text">
            Akaun anda telah didaftarkan. Kami telah menghantar pautan pengesahan ke emel anda.
            Sila sahkan emel terlebih dahulu sebelum log masuk.
        </p>
        <button onclick="document.getElementById('daftar-modal').remove()" class="daftar-btn">
            Faham, Terima Kasih
        </button>
    </div>
</div>
<style>

        .daftar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .daftar-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: var(--shadow-lg);
            animation: slideUp 0.3s ease;
        }
        .daftar-emoji { font-size: 42px; margin-bottom: 0.75rem; }
        .daftar-title { font-size: 15px; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-dark); }
        .daftar-text { font-size: 13px; color: var(--text-muted); line-height: 1.6; margin-bottom: 1.25rem; }
        .daftar-btn {
            background: var(--primary);
            color: #ffffff;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.15s;
        }
        .daftar-btn:hover { background: var(--primary-hover); }
</style>
@endif
    <div>

        {{-- ── Web Admin ── --}}
        @auth('web')

            @php
                $user  = Auth::guard('web')->user();
                $email = $user->email;
            @endphp

            <div class="nav-dropdown-wrap">
                <button type="button" class="nav-dropdown-trigger">
                    {{ $user->name }}
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

        @if(request()->is('/'))
        <a href="{{ url('/help') }}">
                Manual Pengguna
        </a>
        @endif

        @if(!Auth::guard('booking_user')->check() && !Auth::guard('web')->check())
            <button onclick="openModal('loginModal')">Log Masuk</button>
        @endif

        {{-- ── Booking User ── --}}
        @auth('booking_user')
            <div class="nav-dropdown-wrap">
                <button type="button" class="nav-dropdown-trigger">
                    {{ Auth::guard('booking_user')->user()->name }}
                    <span>▾</span>
                </button>

                <div class="nav-dropdown">
                    <div class="nav-dropdown-section">
                    👤 {{ Auth::guard('booking_user')->user()->name }}
                    </div>
                    @if(Auth::guard('booking_user')->user()->can_book)
                        <div class="nav-dropdown-divider"></div>

                        <div class="nav-dropdown-section">Tempahan Bilik</div>
                        <a href="/booking/calendar">📅 Lihat Kalendar</a>
                        <a href="/booking/book">➕ Buat Tempahan</a>
                        <a href="/booking/my-bookings">📋 Tempahan Saya</a>
                    @endif
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
{{-- Login modal --}}
@include('booking._login-modal')

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

    function openModal(id) {
        document.getElementById(id).classList.add('active');
    }

    function closeModal(id) {
        const overlay = document.getElementById(id);
        const modal   = overlay.querySelector('.ticket-modal');
        modal.style.transform = 'translateY(10px) scale(0.97)';
        modal.style.opacity   = '0';
        setTimeout(() => {
            overlay.classList.remove('active');
            modal.style.transform = '';
            modal.style.opacity   = '';
        }, 220);
    }
    function closeLoginModal() { closeModal('loginModal'); }

    // AJAX login form
    document.getElementById('login-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('login-btn');
        btn.disabled = true;
        btn.textContent = '...';

        const errorEl = document.getElementById('login-error');
        errorEl.classList.add('is-hidden');
        errorEl.textContent = '';

        const formData = new FormData(this);

        try {
            const res  = await fetch('{{ route("booking.login.post") }}', {
                method:  'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body:    formData,
            });
            const data = await res.json();

            if (res.ok && data.success) {
                window.location.reload();
                return;
            }

            errorEl.textContent  = data.message ?? 'Ralat tidak diketahui.';
            errorEl.classList.remove('is-hidden');
        } catch {
            errorEl.textContent  = 'Gagal berhubung dengan pelayan.';
            errorEl.classList.remove('is-hidden');
        }

        btn.disabled    = false;
        btn.textContent = 'Log Masuk';
    });
</script>