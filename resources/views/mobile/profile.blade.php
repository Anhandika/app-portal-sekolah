@extends('layouts.mobile-app')

@section('content')
<style>
    .pf-page { padding: 0 16px 120px; max-width: 640px; margin: 0 auto; }

    .pf-hero {
        background: linear-gradient(135deg, var(--navy) 0%, #1e1b4b 55%, #3730a3 100%);
        border-radius: var(--radius-lg); padding: 30px 20px 26px; margin-bottom: 16px;
        color: #fff; text-align: center; position: relative; overflow: hidden;
        box-shadow: 0 14px 40px rgba(30,27,75,0.3);
    }
    .pf-hero::before {
        content:''; position:absolute; top:-50px; right:-40px;
        width:170px; height:170px; border-radius:50%;
        background:radial-gradient(circle, rgba(139,92,246,0.35) 0%, transparent 70%);
    }
    .pf-hero::after {
        content:''; position:absolute; bottom:-60px; left:-40px;
        width:200px; height:200px; border-radius:50%;
        background:radial-gradient(circle, rgba(59,130,246,0.28) 0%, transparent 70%);
    }
    .pf-hero > * { position: relative; z-index: 1; }

    .pf-avatar-badge {
        width: 104px; height: 104px; margin: 0 auto 14px; position: relative;
    }
    .pf-avatar {
        width: 100%; height: 100%; border-radius: var(--radius-lg); overflow: hidden;
        background: transparent; display: flex; align-items: center; justify-content: center;
        position: relative; cursor: pointer;
        border: 3px solid rgba(255,255,255,0.22);
        box-shadow: 0 12px 30px rgba(0,0,0,0.3);
    }
    .pf-avatar img { width: 100%; height: 100%; object-fit: cover; display:block; }
    .pf-avatar .initial { font-size: 38px; font-weight: 800; color: rgba(255,255,255,0.85); }

    .pf-name { font-size:24px; font-weight:800; letter-spacing:-0.02em; }
    .pf-badges { margin-top:6px; }
    .pf-role-pill {
        background:rgba(255,255,255,0.14);border:1px solid rgba(255,255,255,0.18);
        padding:4px 12px;border-radius:8px;font-weight:700;font-size:11px;
        text-transform:uppercase;letter-spacing:0.05em;backdrop-filter:blur(6px);
    }
    .pf-status-pill {
        padding:4px 10px;border-radius:8px;font-weight:700;font-size:10px;
        text-transform:uppercase;letter-spacing:0.04em;display:inline-flex;align-items:center;gap:4px;
    }
    .pf-status-pill.online { background:rgba(34,197,94,0.2);color:#4ade80;border:1px solid rgba(34,197,94,0.3); }
    .pf-status-pill.offline { background:rgba(148,163,184,0.15);color:#94a3b8;border:1px solid rgba(148,163,184,0.2); }
    .pf-presence{margin-top:6px;font-size:12px;font-weight:600;letter-spacing:.01em}
    .pf-presence.online{color:#4ade80}
    .pf-presence.offline{color:rgba(255,255,255,.7)}
    .pf-dot{position:absolute;bottom:4px;right:4px;width:18px;height:18px;border-radius:50%;background:#94a3b8;border:3px solid #1e1b4b;display:grid;place-items:center}
    .pf-dot.online{background:#22c55e;box-shadow:0 0 0 6px rgba(34,197,94,.25);animation:pfPulse 1.6s infinite}
    .pf-dot i{font-size:9px;color:#fff}
    @keyframes pfPulse{0%{box-shadow:0 0 0 0 rgba(34,197,94,.35)}70%{box-shadow:0 0 0 10px rgba(34,197,94,0)}100%{box-shadow:0 0 0 0 rgba(34,197,94,0)}}

    .pf-info-card {
        background: var(--surface-card); border-radius: var(--radius-md); padding: 18px;
        margin-bottom: 12px; box-shadow: var(--shadow-card);
        border: 1px solid var(--line);
    }
    .pf-info-row { display: flex; align-items: center; gap: 12px; padding: 12px 0; }
    .pf-info-row + .pf-info-row { border-top: 1px solid var(--line); }
    .pf-info-icon {
        width: 38px; height: 38px; border-radius: var(--radius-sm); flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 16px;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.6), 0 4px 10px rgba(15,23,42,0.05);
    }
    .pf-info-label { font-size: 10px; font-weight: 700; color: var(--faint); text-transform: uppercase; letter-spacing: 0.04em; }
    .pf-info-value { font-size: 14px; font-weight: 700; color: var(--ink); }

    /* New Premium Styles */
    .pf-menu-card {
        background: var(--surface-card); border-radius: var(--radius-md); padding: 8px 0;
        margin-bottom: 12px; box-shadow: var(--shadow-card); border: 1px solid var(--line);
    }
    .pf-menu-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 18px; cursor: pointer; transition: background 0.2s;
    }
    .pf-menu-item:active { background: #f8fafc; }
    .pf-menu-item + .pf-menu-item { border-top: 1px solid var(--line); }
    .pf-menu-content { display: flex; align-items: center; gap: 14px; }
    .pf-menu-icon {
        width: 32px; height: 32px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center; font-size: 14px;
    }
    .pf-menu-text { font-size: 14px; font-weight: 600; color: var(--ink); }
    .pf-menu-arrow { color: var(--faint); font-size: 12px; }

    .pf-permission-card {
        background: #fffbeb; border: 1px solid #fef3c7; border-radius: var(--radius-md);
        padding: 16px; margin-bottom: 16px; display: none;
    }
    .pf-permission-card.active { display: block; }
    .pf-permission-title { font-size: 13px; font-weight: 800; color: #92400e; display: flex; align-items: center; gap: 8px; }
    .pf-permission-desc { font-size: 11px; color: #b45309; margin-top: 4px; line-height: 1.4; }
    .pf-permission-btn {
        margin-top: 12px; background: #92400e; color: #fff; border: none;
        padding: 8px 16px; border-radius: 8px; font-size: 11px; font-weight: 700;
    }

    .pf-toast {
        position: fixed; top: 16px; left: 16px; right: 16px; z-index: 9999;
        background: var(--surface-card); border-radius: var(--radius-sm); padding: 12px 16px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12); display: none;
        max-width: 640px; margin: 0 auto;
    }
    .pf-toast.show { display: flex; animation: fadeDown 0.3s ease; }
    @keyframes fadeDown { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }

    /* ==== Stat row 3D (pindahan dari dashboard): highlight atas + bayangan berlapis ==== */
    .pf-stat-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 12px; }
    .pf-stat-item {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px; padding: 18px 12px;
        border: 1px solid var(--line);
        box-shadow: 0 1px 0 rgba(255, 255, 255, 0.9) inset,
                    0 8px 20px rgba(15, 23, 42, 0.06);
        text-align: center; text-decoration: none; position: relative;
        overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;
        display: block; color: inherit;
    }
    .pf-stat-item:active { transform: translateY(2px) scale(0.98); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    @media (hover: hover) {
        .pf-stat-item:hover { transform: translateY(-3px); box-shadow: 0 1px 0 rgba(255, 255, 255, 0.9) inset, 0 14px 28px rgba(15, 23, 42, 0.1); }
    }
    .pf-stat-item .ico {
        width: 40px; height: 40px; border-radius: 12px; margin: 0 auto 10px;
        display: flex; align-items: center; justify-content: center; font-size: 18px;
    }
    .pf-stat-item .val { font-size: 22px; font-weight: 900; color: var(--navy); line-height: 1; }
    .pf-stat-item .lab { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-top: 6px; letter-spacing: 0.02em; }
    @media (max-width: 360px) {
        .pf-stat-item { padding: 14px 8px; }
        .pf-stat-item .val { font-size: 19px; }
    }
</style>

<div id="pfToast" class="pf-toast">
    <i id="pfToastIcon" class="bi bi-check-circle-fill" style="color:#16a34a;font-size:18px;"></i>
    <span id="pfToastMsg" style="flex:1;font-size:13px;font-weight:600;margin-left:8px;"></span>
</div>

<div class="pf-page" style="padding-top:16px;">
    {{-- Hero WA-style --}}
    <div class="pf-hero">
        <div class="pf-avatar-badge">
            <div class="pf-avatar" id="avatarDisplay">
                <img src="{{ $user->avatar_url }}" id="avatarImg" style="object-position: {{ $user->foto_posisi_x ?? 50 }}% {{ $user->foto_posisi_y ?? 50 }}%;">
            </div>
            <span id="pfHeroDot" class="pf-dot {{ $user->status_badge }}"><i class="bi {{ $user->status_badge === 'online' ? 'bi-check-lg' : 'bi-moon' }}"></i></span>
        </div>
        <div class="pf-name" id="nameDisplay">{{ $user->name }}</div>
        <div id="pfPresence" class="pf-presence {{ $user->status_badge }}"><i class="bi {{ $user->isOnline() ? 'bi-circle-fill' : 'bi-clock' }}" style="font-size:8px;"></i> {{ $user->last_seen }}</div>
        <div class="pf-badges" style="display:flex;align-items:center;justify-content:center;gap:8px;margin-top:8px;">
            <span class="pf-role-pill">{{ $user->role }}</span>
            <span id="pfStatusPill" class="pf-status-pill {{ $user->status_badge }}">
                <i class="bi {{ $user->status_badge === 'online' ? 'bi-circle-fill' : 'bi-person' }}" style="font-size:8px;"></i>
                <span id="pfStatusText">{{ ucfirst($user->status_label) }}</span>
            </span>
        </div>
    </div>

    {{-- Statistik ringkas 3D (pindahan dari dashboard) --}}
    @php
        $pfHadir = (int) (($absensiBulan['hadir'] ?? $absensiBulan['Hadir'] ?? 0));
        $pfIzin = (int) (($absensiBulan['izin'] ?? $absensiBulan['Izin'] ?? 0));
        $pfSakit = (int) (($absensiBulan['sakit'] ?? $absensiBulan['Sakit'] ?? 0));
        $pfAlpha = (int) (($absensiBulan['alpha'] ?? $absensiBulan['Alpha'] ?? 0));
        $pfTotal = $pfHadir + $pfIzin + $pfSakit + $pfAlpha;
        $pfPct = $pfTotal > 0 ? round(($pfHadir / $pfTotal) * 100) : 0;
    @endphp
    <div class="pf-stat-grid">
        <a href="{{ route('tugas.index') }}" class="pf-stat-item">
            <div class="ico" style="background: #eff6ff; color: #2563eb;"><i class="bi bi-journal-check"></i></div>
            <div class="val">{{ $tugasAktif ?? 0 }}</div>
            <div class="lab">Tugas</div>
        </a>
        <a href="{{ route('absensi.index') }}" class="pf-stat-item">
            <div class="ico" style="background: #f0fdf4; color: #16a34a;"><i class="bi bi-graph-up"></i></div>
            <div class="val">{{ $pfPct }}%</div>
            <div class="lab">Hadir</div>
        </a>
        <div class="pf-stat-item">
            <div class="ico" style="background: #fdf2f8; color: #db2777;"><i class="bi bi-people"></i></div>
            <div class="val">{{ $totalSiswaKelas ?? 0 }}</div>
            <div class="lab">Siswa</div>
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="pf-info-card">
        <div class="pf-info-row">
            <div class="pf-info-icon" style="background:#eef4ff;color:var(--blue);"><i class="bi bi-person-badge"></i></div>
            <div>
                <div class="pf-info-label">Nama Lengkap</div>
                <div class="pf-info-value" id="infoName">{{ $user->name }}</div>
            </div>
        </div>
        <div class="pf-info-row">
            <div class="pf-info-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-envelope-at"></i></div>
            <div>
                <div class="pf-info-label">Email</div>
                <div class="pf-info-value">
                    {{ $user->email }}
                </div>
            </div>
        </div>
        <div class="pf-info-row">
            <div class="pf-info-icon" style="background:#ede9fe;color:#7c3aed;"><i class="bi bi-mortarboard"></i></div>
            <div>
                <div class="pf-info-label">Kelas / Jabatan</div>
                <div class="pf-info-value">{{ $user->kelas?->nama ?? 'Staf Sekolah' }}</div>
            </div>
        </div>
        <div class="pf-info-row">
            <div class="pf-info-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-wifi"></i></div>
            <div style="flex:1;min-width:0">
                <div class="pf-info-label">Status Sesi</div>
                <div class="pf-info-value" id="pfSessionStatus">
                    @if($user->status_badge === 'online')
                        <span style="color:#22c55e;font-weight:700;"><i class="bi bi-circle-fill" style="font-size:8px;"></i> Online</span> <span style="font-weight:600;color:#64748b;font-size:11px;">— aktif sekarang</span>
                    @else
                        <span style="color:#94a3b8;font-weight:700;"><i class="bi bi-person" style="font-size:8px;"></i> Offline</span> <span style="font-weight:600;color:#64748b;font-size:11px;">— {{ $user->last_seen }}</span>
                    @endif
                </div>
            </div>
            <span id="pfLiveDot" style="width:10px;height:10px;border-radius:50%;background:{{ $user->isOnline() ? '#22c55e' : '#cbd5e1' }};box-shadow:0 0 0 4px {{ $user->isOnline() ? 'rgba(34,197,94,.18)' : 'transparent' }};flex-shrink:0"></span>
        </div>
    </div>

    {{-- Premium Menu Section --}}
    <div class="pf-menu-card">
        <div class="pf-menu-item" onclick="window.location.href='{{ route('profile.edit') }}'">
            <div class="pf-menu-content">
                <div class="pf-menu-icon" style="background:#eef2ff;color:#6366f1;"><i class="bi bi-person-gear"></i></div>
                <div class="pf-menu-text">Pengaturan Profil</div>
            </div>
            <i class="bi bi-chevron-right pf-menu-arrow"></i>
        </div>
        <div class="pf-menu-item" onclick="window.location.href='{{ route('help.faq') }}'">
            <div class="pf-menu-content">
                <div class="pf-menu-icon" style="background:#fff7ed;color:#ea580c;"><i class="bi bi-question-circle"></i></div>
                <div class="pf-menu-text">Pusat Bantuan & FAQ</div>
            </div>
            <i class="bi bi-chevron-right pf-menu-arrow"></i>
        </div>
        <div class="pf-menu-item" onclick="window.location.href='{{ route('security.settings') }}'">
            <div class="pf-menu-content">
                <div class="pf-menu-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-shield-lock"></i></div>
                <div class="pf-menu-text">Keamanan & Privasi</div>
            </div>
            <i class="bi bi-chevron-right pf-menu-arrow"></i>
        </div>
        <div class="pf-menu-item" onclick="window.location.href='{{ route('settings.notifications') }}'">
            <div class="pf-menu-content">
                <div class="pf-menu-icon" style="background:#f0f9ff;color:#0284c7;"><i class="bi bi-bell"></i></div>
                <div class="pf-menu-text">Pengaturan Notifikasi</div>
            </div>
            <i class="bi bi-chevron-right pf-menu-arrow"></i>
        </div>
        <div class="pf-menu-item" onclick="window.location.href='{{ route('about.show') }}'">
            <div class="pf-menu-content">
                <div class="pf-menu-icon" style="background:#f8fafc;color:#64748b;"><i class="bi bi-info-circle"></i></div>
                <div class="pf-menu-text">Tentang Aplikasi</div>
            </div>
            <i class="bi bi-chevron-right pf-menu-arrow"></i>
        </div>
    </div>

    {{-- Permission Warning Card --}}
    <div id="permissionWarning" class="pf-permission-card">
        <div class="pf-permission-title">
            <i class="bi bi-exclamation-triangle-fill"></i> Izin Belum Lengkap
        </div>
        <div class="pf-permission-desc">
            Fitur notifikasi dan presensi memerlukan izin tambahan (<span id="missingNames"></span>). Aktifkan sekarang untuk pengalaman terbaik.
        </div>
        <button class="pf-permission-btn" onclick="openNativeSettings()">Lengkapi Sekarang</button>
    </div>

    {{-- Logout --}}
    <div style="background:#fff5f5;border:1px solid #fee2e2;border-radius:var(--radius-md);padding:14px;display:flex;align-items:center;justify-content:space-between;margin-top:20px;">
        <div>
            <div style="font-size:13px;font-weight:700;color:#dc2626;">Keluar Akun?</div>
            <div style="font-size:11px;color:var(--faint);">Hentikan sesi aktif.</div>
        </div>
        <form method="POST" action="{{ route('logout') }}" id="profile-logout-form">
            @csrf
            <button type="button" style="padding:8px 20px;border-radius:10px;background:#dc2626;color:#fff;font-weight:700;font-size:12px;border:none;cursor:pointer;" onclick="event.preventDefault(); clearAllSessionData(); document.getElementById('profile-logout-form').submit();">KELUAR</button>
        </form>
    </div>
</div>

<script>
function showToast(msg, type) {
    var t = document.getElementById('pfToast');
    var icon = document.getElementById('pfToastIcon');
    var isError = type === 'error';

    document.getElementById('pfToastMsg').textContent = msg;
    icon.className = 'bi ' + (isError ? 'bi-exclamation-circle-fill' : 'bi-check-circle-fill');
    icon.style.color = isError ? '#dc2626' : '#16a34a';
    t.style.borderLeft = '4px solid ' + (isError ? '#dc2626' : '#16a34a');

    t.classList.add('show');
    setTimeout(function() { t.classList.remove('show'); }, 5000);
}

@if(session('success'))
showToast(@json(session('success')), 'success');
@endif
@if(session('error'))
showToast(@json(session('error')), 'error');
@endif

// --- Native Integration for Premium Features ---
document.addEventListener('DOMContentLoaded', function() {
    checkNativePermissions();
});

function checkNativePermissions() {
    if (window.Capacitor && window.Capacitor.Plugins.NativeBridge) {
        window.Capacitor.Plugins.NativeBridge.checkPermissionsStatus()
            .then(function(res) {
                if (!res.isComplete) {
                    var card = document.getElementById('permissionWarning');
                    document.getElementById('missingNames').textContent = res.missingPermissions;
                    card.classList.add('active');
                }
            })
            .catch(function(e) { console.error('Bridge Error:', e); });
    }
}

function openNativeSettings() {
    if (window.Capacitor && window.Capacitor.Plugins.NativeBridge) {
        window.Capacitor.Plugins.NativeBridge.openAppSettings();
    }
}
// WA-style realtime presence (poll session/status every 15s)
(function(){
    var url="{{ route('session.status') }}";
    function apply(data){
        if(!data || !data.authenticated) return;
        var online=!!data.is_online;
        var badge=data.status_badge||(online?'online':'offline');
        var label=data.status_label|| (online?'aktif':'terdaftar');
        var seen=data.last_seen|| (online?'online':'offline');
        var pill=document.getElementById('pfStatusPill');
        var txt=document.getElementById('pfStatusText');
        var pres=document.getElementById('pfPresence');
        var dot=document.getElementById('pfHeroDot');
        var live=document.getElementById('pfLiveDot');
        var sess=document.getElementById('pfSessionStatus');
        if(pill){ pill.className='pf-status-pill '+badge; }
        if(txt) txt.textContent=label.charAt(0).toUpperCase()+label.slice(1);
        if(pres){ pres.className='pf-presence '+badge; pres.innerHTML='<i class="bi '+(online?'bi-circle-fill':'bi-clock')+'" style="font-size:8px;"></i> '+seen; }
        if(dot){ dot.className='pf-dot '+badge; dot.innerHTML='<i class="bi '+(online?'bi-check-lg':'bi-moon')+'"></i>'; }
        if(live){ live.style.background=online?'#22c55e':'#cbd5e1'; live.style.boxShadow=online?'0 0 0 4px rgba(34,197,94,.18)':'none'; }
        if(sess){ sess.innerHTML = online ? '<span style="color:#22c55e;font-weight:700;"><i class="bi bi-circle-fill" style="font-size:8px;"></i> Online</span> <span style="font-weight:600;color:#64748b;font-size:11px;">— aktif sekarang</span>' : '<span style="color:#94a3b8;font-weight:700;"><i class="bi bi-person" style="font-size:8px;"></i> Offline</span> <span style="font-weight:600;color:#64748b;font-size:11px;">— '+seen+'</span>'; }
    }
    function tick(){ fetch(url+'?t='+Date.now(),{headers:{'X-Requested-With':'XMLHttpRequest'}}).then(r=>r.json()).then(apply).catch(()=>{}); }
    tick(); setInterval(tick,15000);
    document.addEventListener('visibilitychange',function(){ if(!document.hidden) tick(); });
})();
</script>
@endsection
