@extends('layouts.mobile-app')

@section('content')
@php
    $isGuru = $user->role === 'guru';
    $spp = $sppStats ?? null;
    $hadir = (int) ($absensiBulan['hadir'] ?? $absensiBulan['Hadir'] ?? 0);
    $izin = (int) ($absensiBulan['izin'] ?? $absensiBulan['Izin'] ?? 0);
    $sakit = (int) ($absensiBulan['sakit'] ?? $absensiBulan['Sakit'] ?? 0);
    $alpha = (int) ($absensiBulan['alpha'] ?? $absensiBulan['Alpha'] ?? 0);
    $totalAbsen = $hadir + $izin + $sakit + $alpha;
    $pctHadir = $totalAbsen > 0 ? round(($hadir / $totalAbsen) * 100) : 0;

    $hour = date('H');
    $greetingTime = match(true) {
        $hour < 11 => 'Selamat Pagi',
        $hour < 15 => 'Selamat Siang',
        $hour < 18 => 'Selamat Sore',
        default => 'Selamat Malam',
    };
    $honorific = $isGuru ? 'Pak' : 'Kak';
@endphp

<style>
    .db-body { padding: 12px 16px 120px; max-width: 640px; margin: 0 auto; }
    .db-section { padding: 20px 18px; }

    /* ==== Hero 3D: gradient + orb CSS + highlight dalam (tanpa gambar eksternal) ==== */
    .hero-card {
        background: var(--grad-hero);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: var(--radius-lg);
        padding: 28px 24px;
        margin-bottom: 24px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.25),
                    inset 0 1px 0 rgba(255, 255, 255, 0.15);
    }
    .hero-card::before {
        content: ''; position: absolute; top: -60px; right: -60px; width: 220px; height: 220px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.4) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-card::after {
        content: ''; position: absolute; bottom: -50px; left: -50px; width: 180px; height: 180px;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.3) 0%, transparent 70%);
        pointer-events: none;
    }

    .hero-avatar-wrap { position: relative; }
    .hero-avatar {
        width: 56px; height: 56px; border-radius: 18px;
        overflow: hidden; display: flex; align-items: center; justify-content: center;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25),
                    0 0 0 3px rgba(255, 255, 255, 0.12),
                    inset 0 1px 0 rgba(255, 255, 255, 0.25);
    }
    .hero-avatar img { width: 100%; height: 100%; object-fit: cover; }

    .role-indicator {
        position: absolute; bottom: -6px; right: -6px;
        width: 24px; height: 24px; border-radius: 8px;
        background: {{ $isGuru ? '#4f46e5' : '#10b981' }};
        border: 2px solid var(--navy);
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; color: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .hero-greeting { font-size: 13px; opacity: 0.8; font-weight: 600; color: #cbd5e1; text-shadow: 0 1px 6px rgba(0, 0, 0, 0.3); }
    .hero-name { font-size: 26px; font-weight: 900; letter-spacing: -0.03em; margin-top: 2px; text-shadow: 0 2px 12px rgba(0, 0, 0, 0.35); }

    .hero-badge-row { display: flex; gap: 8px; margin-top: 18px; flex-wrap: wrap; }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 12px; padding: 6px 12px; font-size: 11px; font-weight: 700; color: #f8fafc;
    }

    .hero-bell {
        width: 44px; height: 44px; border-radius: 14px;
        background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.15);
        display: flex; align-items: center; justify-content: center; color: #fff;
        text-decoration: none; position: relative; transition: all 0.2s;
    }
    .hero-bell:active { transform: scale(0.92); background: rgba(255, 255, 255, 0.15); }

    /* ==== Quick Menu geser (swipeable): sinkron dengan container 3D ==== */
    .menu-swipe {
        display: flex; gap: 14px; overflow-x: auto;
        scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch;
        scrollbar-width: none; padding: 6px 2px 14px;
        margin: 0 -18px; padding-left: 18px; padding-right: 18px;
    }
    .menu-swipe::-webkit-scrollbar { display: none; }
    .menu-btn {
        flex: 0 0 auto; width: 76px; scroll-snap-align: start;
        display: flex; flex-direction: column; align-items: center; gap: 8px;
        text-decoration: none; transition: transform 0.18s ease;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid var(--line); border-radius: 20px; padding: 14px 6px 12px;
        box-shadow: 0 1px 0 rgba(255, 255, 255, 0.9) inset,
                    0 8px 20px rgba(15, 23, 42, 0.06);
        position: relative; overflow: hidden;
    }
    .menu-btn::before {
        content: ''; position: absolute; inset: 0; border-radius: 20px; pointer-events: none;
        background: linear-gradient(180deg, rgba(255,255,255,0.5) 0%, transparent 35%);
    }
    .menu-btn:active { transform: translateY(2px) scale(0.96); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    @media (hover: hover) {
        .menu-btn:hover { transform: translateY(-3px); box-shadow: 0 1px 0 rgba(255, 255, 255, 0.9) inset, 0 14px 28px rgba(15, 23, 42, 0.1); }
    }
    .menu-btn-ico {
        width: 52px; height: 52px; border-radius: 17px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; position: relative; color: #fff;
        text-shadow: 0 1px 4px rgba(0, 0, 0, 0.25);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12),
                    inset 0 1px 0 rgba(255, 255, 255, 0.3),
                    inset 0 -2px 4px rgba(0, 0, 0, 0.12);
    }
    .menu-btn-ico::after {
        content: ''; position: absolute; inset: 0; border-radius: 18px; pointer-events: none;
        background: linear-gradient(180deg, rgba(255,255,255,0.22) 0%, transparent 45%);
    }
    .menu-btn-lab { font-size: 11px; font-weight: 700; color: #64748b; }
    .menu-dots { display: flex; gap: 6px; justify-content: center; margin-top: 12px; }
    .menu-dots span { width: 6px; height: 6px; border-radius: 99px; background: #e2e8f0; transition: all 0.25s; }
    .menu-dots span.on { width: 22px; background: var(--blue, #2563eb); }

    .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
    .section-header h3 { font-size: 17px; font-weight: 800; color: var(--navy); margin: 0; }
    .section-header a { font-size: 13px; font-weight: 700; color: var(--blue); text-decoration: none; }

    .lms-row {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 18px; padding: 12px;
        display: flex; align-items: center; gap: 12px; margin-bottom: 12px;
        border: 1px solid var(--line); text-decoration: none; color: inherit;
        box-shadow: 0 1px 0 rgba(255, 255, 255, 0.9) inset,
                    0 6px 16px rgba(15, 23, 42, 0.04);
        transition: transform 0.2s, background 0.2s, box-shadow 0.2s;
    }
    .lms-row:active { background: #f8fafc; transform: scale(0.99); }
    @media (hover: hover) {
        .lms-row:hover { transform: translateX(3px); box-shadow: 0 1px 0 rgba(255, 255, 255, 0.9) inset, 0 10px 22px rgba(15, 23, 42, 0.08); }
    }
    .lms-ico {
        width: 48px; height: 48px; border-radius: 14px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 20px; color: #fff;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.25);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.3),
                    inset 0 -2px 4px rgba(0, 0, 0, 0.1),
                    0 6px 14px rgba(15, 23, 42, 0.12);
    }
    .lms-info { flex: 1; min-width: 0; }
    .lms-title { font-size: 14px; font-weight: 700; color: var(--navy); margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .lms-meta { font-size: 11px; color: #94a3b8; font-weight: 600; }

    .absen-summary {
        background: linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%);
        border: 1px solid var(--line); border-radius: 16px; padding: 12px;
        display: flex; align-items: center; justify-content: space-between;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8),
                    inset 0 -1px 2px rgba(15, 23, 42, 0.04);
    }
    .absen-item { text-align: center; flex: 1; }
    .absen-val { font-size: 16px; font-weight: 800; color: var(--navy); }
    .absen-lab { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; }

    /* Bottom Sheet Classmates */
    .sheet {
        position: fixed; inset: 0; z-index: 3000; display: none;
        align-items: flex-end; justify-content: center;
        background: rgba(15, 23, 42, .45); backdrop-filter: blur(4px);
    }
    .sheet.open { display: flex; }
    .sheet-card {
        width: 100%; max-width: 640px; background: #fff;
        border-radius: 32px 32px 0 0; padding: 24px 20px 40px;
        animation: slideSheet 0.3s ease-out;
    }
    @keyframes slideSheet { from { transform: translateY(100%); } to { transform: translateY(0); } }
    .classmate-row {
        display: flex; align-items: center; gap: 12px; padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .classmate-avatar { width: 44px; height: 44px; border-radius: 14px; object-fit: cover; background: #f1f5f9; }

    @keyframes slideIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-up { animation: slideIn 0.5s ease both; }

    /* ==== Tur onboarding (spotlight + tooltip) ==== */
    #pas-spot {
        position: fixed; z-index: 12000; display: none; pointer-events: none;
        border: 2px solid rgba(255, 255, 255, 0.95); border-radius: 20px;
        box-shadow: 0 0 0 9999px rgba(15, 23, 42, 0.75),
                    0 0 0 4px rgba(220, 38, 38, 0.55),
                    0 0 34px rgba(220, 38, 38, 0.45);
        transition: top 0.3s ease, left 0.3s ease, width 0.3s ease, height 0.3s ease;
    }
    #pas-tip {
        position: fixed; z-index: 12001; display: none;
        width: min(300px, calc(100vw - 32px));
        background: #fff; border-radius: 20px; padding: 18px;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.35);
        animation: slideIn 0.3s ease both;
    }
    #pas-tip .t-cat { font-size: 10px; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: var(--red, #dc2626); }
    #pas-tip .t-title { font-size: 16px; font-weight: 800; letter-spacing: -0.01em; margin: 4px 0 6px; color: var(--navy, #0f172a); }
    #pas-tip .t-desc { font-size: 12.5px; line-height: 1.6; color: #64748b; }
    #pas-tip .t-dots { display: flex; gap: 6px; margin: 12px 0; }
    #pas-tip .t-dots span { width: 6px; height: 6px; border-radius: 99px; background: #e2e8f0; transition: all 0.25s; }
    #pas-tip .t-dots span.on { width: 22px; background: #dc2626; }
    #pas-tip .t-foot { display: flex; align-items: center; gap: 8px; }
    #pas-tip .t-count { font-size: 11px; font-weight: 800; color: #94a3b8; margin-right: auto; }
    #pas-tip .t-skip { background: none; border: 0; color: #94a3b8; font-size: 12px; font-weight: 700; cursor: pointer; padding: 8px 4px; }
    #pas-tip .t-back { background: #f1f5f9; border: 0; color: #0f172a; font-size: 12px; font-weight: 800; border-radius: 10px; padding: 9px 14px; cursor: pointer; }
    #pas-tip .t-next { background: linear-gradient(135deg, #dc2626, #ef4444); border: 0; color: #fff; font-size: 12px; font-weight: 800; border-radius: 10px; padding: 9px 16px; cursor: pointer; box-shadow: 0 6px 14px rgba(220, 38, 38, 0.35); }
    #pas-tip .t-back:active, #pas-tip .t-next:active { transform: scale(0.96); }

    /* ==== Pengaman layar sangat kecil: cegah gepeng/overflow ==== */
    @media (max-width: 360px) {
        .hero-card { padding: 22px 18px; }
        .hero-name { font-size: 22px; }
        .menu-btn { width: 70px; padding: 12px 4px 10px; }
        .menu-btn-ico { width: 46px; height: 46px; border-radius: 15px; font-size: 20px; }
        .menu-btn-lab { font-size: 10px; }
    }
</style>

<div class="db-body">
    {{-- Enhanced Hero Section --}}
    <div class="hero-card animate-up">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;position:relative;z-index:1;">
            <div style="display:flex;align-items:center;gap:16px;">
                <div class="hero-avatar-wrap">
                    <div class="hero-avatar">
                        <img src="{{ $user->avatar_url }}">
                    </div>
                    <div class="role-indicator">
                        <i class="bi {{ $isGuru ? 'bi-person-badge-fill' : 'bi-mortarboard-fill' }}"></i>
                    </div>
                </div>
                <div>
                    <div class="hero-greeting">{{ $greetingTime }}, {{ $honorific }}</div>
                    <div class="hero-name">{{ explode(' ', $user->name)[0] }}!</div>
                </div>
            </div>
            <a href="{{ route('notifications.index') }}" class="hero-bell">
                <i class="bi bi-bell-fill"></i>
                @if($unreadNotificationsCount > 0)
                    <span style="position:absolute; top:-4px; right:-4px; min-width:20px; height:20px; padding:0 4px; border-radius:10px; background:#ef4444; border:3px solid var(--navy); color:#fff; font-size:10px; font-weight:900; display:flex; align-items:center; justify-content:center;">
                        {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                    </span>
                @endif
            </a>
        </div>

        <div class="hero-badge-row" style="position:relative;z-index:1;">
            @if($user->kelas)
                <div class="hero-badge">
                    <i class="bi bi-building"></i> {{ $user->kelas->nama }}
                </div>
            @endif
            <div class="hero-badge" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.2); color: #34d399;">
                <i class="bi bi-patch-check-fill"></i> Akun Aktif
            </div>
            <div class="hero-badge">
                <i class="bi bi-calendar2-event"></i> {{ now()->translatedFormat('d M') }}
            </div>
        </div>
    </div>

    {{-- Premium Quick Menu (geser horizontal) --}}
    <div class="pui-card db-section animate-up" style="animation-delay: 0.15s; margin-bottom: 24px;">
        <div class="section-header">
            <h3>Menu Utama</h3>
            <a href="#" onclick="if(window.pasTourReplay){window.pasTourReplay();}return false;" style="font-size:11px;">✦ Tur Ulang</a>
        </div>
        <div class="menu-swipe" id="menuSwipe">
            <a href="{{ route('absensi.index') }}" class="menu-btn">
                <div class="menu-btn-ico" style="background: linear-gradient(135deg, #60a5fa, #2563eb); color: #fff;">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="menu-btn-lab">Absensi</div>
            </a>
            <a href="{{ route('tugas.index') }}" class="menu-btn">
                <div class="menu-btn-ico" style="background: linear-gradient(135deg, #facc15, #ca8a04); color: #fff;">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div class="menu-btn-lab">Tugas</div>
            </a>
            <a href="{{ route('spp.index') }}" class="menu-btn">
                <div class="menu-btn-ico" style="background: linear-gradient(135deg, #34d399, #059669); color: #fff;">
                    <i class="bi bi-credit-card-2-front"></i>
                </div>
                <div class="menu-btn-lab">SPP</div>
            </a>
            <a href="{{ route('chat.index') }}" class="menu-btn">
                <div class="menu-btn-ico" style="background: linear-gradient(135deg, #a78bfa, #7c3aed); color: #fff;">
                    <i class="bi bi-chat-dots"></i>
                </div>
                <div class="menu-btn-lab">Chat</div>
            </a>
            <a href="{{ route('perpustakaan.index') }}" class="menu-btn">
                <div class="menu-btn-ico" style="background: linear-gradient(135deg, #fb923c, #ea580c); color: #fff;">
                    <i class="bi bi-book"></i>
                </div>
                <div class="menu-btn-lab">Perpus</div>
            </a>
            <a href="{{ route('jadwal.index') }}" class="menu-btn">
                <div class="menu-btn-ico" style="background: linear-gradient(135deg, #818cf8, #4f46e5); color: #fff;">
                    <i class="bi bi-calendar3"></i>
                </div>
                <div class="menu-btn-lab">Jadwal</div>
            </a>
            <a href="{{ route('nilai.index') }}" class="menu-btn">
                <div class="menu-btn-ico" style="background: linear-gradient(135deg, #f472b6, #db2777); color: #fff;">
                    <i class="bi bi-award"></i>
                </div>
                <div class="menu-btn-lab">Nilai</div>
            </a>
            <a href="{{ route('eskul.index') }}" class="menu-btn">
                <div class="menu-btn-ico" style="background: linear-gradient(135deg, #2dd4bf, #0d9488); color: #fff;">
                    <i class="bi bi-trophy"></i>
                </div>
                <div class="menu-btn-lab">Eskul</div>
            </a>
        </div>
        <div class="menu-dots" id="menuDots" aria-hidden="true"></div>
        <div class="text-center" style="font-size:10px;font-weight:700;color:#94a3b8;margin-top:6px;">← Geser untuk menu lainnya →</div>
    </div>

    {{-- Today's Summary / Absensi --}}
    @if($totalAbsen > 0)
        <div class="pui-card db-section animate-up" style="animation-delay: 0.2s; margin-bottom: 24px;">
            <div class="section-header">
                <h3>Kehadiran Bulan Ini</h3>
                <span class="badge rounded-pill bg-light text-dark fw-bold" style="font-size:10px;">{{ $totalAbsen }} Hari</span>
            </div>
            <div class="absen-summary">
                <div class="absen-item">
                    <div class="absen-val" style="color:#16a34a;">{{ $hadir }}</div>
                    <div class="absen-lab">Hadir</div>
                </div>
                <div class="absen-item">
                    <div class="absen-val" style="color:#ca8a04;">{{ $sakit + $izin }}</div>
                    <div class="absen-lab">S/I</div>
                </div>
                <div class="absen-item">
                    <div class="absen-val" style="color:#dc2626;">{{ $alpha }}</div>
                    <div class="absen-lab">Alpha</div>
                </div>
                <div class="absen-item" style="border-left: 1px solid #e2e8f0; padding-left: 12px; margin-left: 4px;">
                    <div class="absen-val" style="color:var(--blue);">{{ $pctHadir }}%</div>
                    <div class="absen-lab">Rate</div>
                </div>
            </div>
        </div>
    @endif

    {{-- LMS Section --}}
    <div class="pui-card db-section animate-up" style="animation-delay: 0.25s; margin-bottom: 24px;">
        <div class="section-header">
            <h3>{{ $isGuru ? 'Mata Pelajaran Diampu' : 'Mata Pelajaran Saya' }}</h3>
        </div>
        @forelse($mapels->take(4) as $m)
            @php
                $colors = [['#eff6ff', '#2563eb'], ['#f0fdf4', '#16a34a'], ['#fffbeb', '#d97706'], ['#fef2f2', '#dc2626']];
                $c = $colors[$loop->index % count($colors)];
            @endphp
            <a href="{{ route('mapel.show', $m->id) }}" class="lms-row">
                <div class="lms-ico" style="background:{{ $c[0] }}; color:{{ $c[1] }};">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div class="lms-info">
                    <div class="lms-title">{{ $m->nama }}</div>
                    <div class="lms-meta">
                        @if($isGuru)
                            <i class="bi bi-people me-1"></i> {{ $m->kelas->nama }}
                        @else
                            <i class="bi bi-person me-1"></i> {{ explode(' ', $m->guru?->name ?? 'Guru')[0] }}
                        @endif
                    </div>
                </div>
                <i class="bi bi-chevron-right text-muted" style="font-size: 14px;"></i>
            </a>
        @empty
            <div class="text-center py-4 text-muted small">Belum ada mata pelajaran.</div>
        @endforelse
    </div>

    {{-- Latest Announcements --}}
    @if($publicPengumumans->count() > 0)
        <div class="pui-card db-section animate-up" style="animation-delay: 0.3s; margin-bottom: 24px;">
            <div class="section-header">
                <h3>Pengumuman Terbaru</h3>
                <a href="{{ route('pengumuman.index') }}">Semua</a>
            </div>
            @foreach($publicPengumumans as $p)
                <div class="pui-row" style="padding: 10px 0;">
                    <div style="width: 40px; height: 40px; border-radius: 12px; background: #fff7ed; color: #f97316; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <div class="grow ms-3">
                        <div class="fw-bold" style="font-size: 14px; color: var(--navy);">{{ $p->judul }}</div>
                        <div class="small text-muted">{{ $p->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    // Indikator dots untuk menu geser (sinkron dengan posisi scroll)
    (function () {
        var swipe = document.getElementById('menuSwipe');
        var dots = document.getElementById('menuDots');
        if (!swipe || !dots) return;
        var pages = 3;
        for (var i = 0; i < pages; i++) {
            var d = document.createElement('span');
            if (i === 0) d.className = 'on';
            dots.appendChild(d);
        }
        function sync() {
            var max = swipe.scrollWidth - swipe.clientWidth;
            var p = max > 0 ? swipe.scrollLeft / max : 0;
            var idx = Math.min(pages - 1, Math.round(p * (pages - 1)));
            dots.querySelectorAll('span').forEach(function (s, j) {
                s.className = j === idx ? 'on' : '';
            });
        }
        swipe.addEventListener('scroll', function () { requestAnimationFrame(sync); }, { passive: true });
        window.addEventListener('resize', sync);
        sync();
    })();

    // Voice notification if any unread
    var unreadCount = {{ $unreadNotificationsCount }};
    var lastKnown = localStorage.getItem('last_notif_count');
    if (unreadCount > 0 && unreadCount > (parseInt(lastKnown) || 0)) {
        try {
            var msg = new SpeechSynthesisUtterance("Ada notifikasi baru untuk {{ $honorific }}");
            msg.lang = 'id-ID';
            window.speechSynthesis.speak(msg);
        } catch(e) {}
    }
    localStorage.setItem('last_notif_count', unreadCount);
</script>

{{-- Tur onboarding: spotlight perkenalan fitur (sekali per perangkat) --}}
<div id="pas-spot" aria-hidden="true"></div>
<div id="pas-tip" role="dialog" aria-label="Panduan fitur">
    <div class="t-cat" id="pasTipCat">Beranda</div>
    <div class="t-title" id="pasTipTitle"></div>
    <div class="t-desc" id="pasTipDesc"></div>
    <div class="t-dots" id="pasTipDots"></div>
    <div class="t-foot">
        <span class="t-count" id="pasTipCount"></span>
        <button type="button" class="t-skip" id="pasTipSkip">Lewati</button>
        <button type="button" class="t-back" id="pasTipBack" style="display:none;">Kembali</button>
        <button type="button" class="t-next" id="pasTipNext">Lanjut</button>
    </div>
</div>
<script>
(function () {
    var FLAG = 'pas_tour_v1_done';
    var steps = [
        { sel: '.hero-card', cat: 'Beranda', title: 'Halo! Ini Beranda Anda 👋',
          desc: 'Foto, sapaan, nama kelas, dan status akun tampil di kartu ini. Semua aktivitas harian dimulai dari sini.' },
        { sel: '.hero-bell', cat: 'Notifikasi', title: 'Lonceng Notifikasi 🔔',
          desc: 'Angka merah = info belum dibaca (tugas baru, nilai, pengumuman). Ketuk untuk membuka kotak masuk.' },
        { sel: '.menu-swipe', cat: 'Menu Utama', title: 'Geser Menu 🧭',
          desc: 'Geser ke kiri untuk 8 fitur: Absensi, Tugas, SPP, Chat, Perpus, Jadwal, Nilai, Eskul. Titik di bawah menunjukkan posisi.' },
        { sel: '.lms-row', cat: 'Belajar', title: 'Mata Pelajaran 📚',
          desc: 'Daftar mapel Anda. Masuk ke dalamnya untuk materi, tugas, dan nilai per pelajaran.', optional: true },
        { sel: '.absen-summary', cat: 'Kehadiran', title: 'Ringkasan Bulanan ✅',
          desc: 'Total Hadir, Sakit/Izin, Alpha, dan persen kehadiran bulan berjalan.', optional: true },
        { sel: '.bottom-nav', cat: 'Navigasi', title: 'Navigasi Jempol 👍',
          desc: 'Beranda, Global, Absen, Chat, Tugas, Profil — ikon menyala menandai posisi Anda. Bisa juga tarik layar ke bawah untuk memuat ulang!' }
    ];
    var idx = 0, active = [];
    var spot = document.getElementById('pas-spot');
    var tip = document.getElementById('pas-tip');

    function lockVisible() {
        var l = document.getElementById('app-lock-overlay');
        return l && getComputedStyle(l).display !== 'none';
    }
    function collect() {
        active = steps.filter(function (s) { return document.querySelector(s.sel); });
    }
    function place() {
        var s = active[idx];
        if (!s) return endTour(false);
        var el = document.querySelector(s.sel);
        if (!el) { next(); return; }
        var r = el.getBoundingClientRect();
        var pad = 8;
        spot.style.display = 'block';
        spot.style.top = Math.max(8, r.top - pad) + 'px';
        spot.style.left = Math.max(8, r.left - pad) + 'px';
        spot.style.width = Math.min(window.innerWidth - 16, r.width + pad * 2) + 'px';
        spot.style.height = (r.height + pad * 2) + 'px';
        document.getElementById('pasTipCat').textContent = s.cat;
        document.getElementById('pasTipTitle').textContent = s.title;
        document.getElementById('pasTipDesc').textContent = s.desc;
        document.getElementById('pasTipCount').textContent = (idx + 1) + ' / ' + active.length;
        document.getElementById('pasTipBack').style.display = idx === 0 ? 'none' : '';
        document.getElementById('pasTipNext').textContent = idx === active.length - 1 ? 'Mulai! 🚀' : 'Lanjut';
        var dots = document.getElementById('pasTipDots');
        dots.innerHTML = '';
        active.forEach(function (_, i) {
            var d = document.createElement('span');
            if (i === idx) d.className = 'on';
            dots.appendChild(d);
        });
        tip.style.display = 'block';
        var tw = Math.min(300, window.innerWidth - 32);
        var below = r.bottom + 12 + 230 < window.innerHeight;
        tip.style.top = (below ? r.bottom + 12 : Math.max(12, r.top - 242)) + 'px';
        tip.style.left = Math.max(16, Math.min(window.innerWidth - tw - 16, r.left)) + 'px';
    }
    function show(i) {
        idx = i;
        var s = active[idx];
        if (!s) return endTour(false);
        var el = document.querySelector(s.sel);
        if (el && el.scrollIntoView) {
            try { el.scrollIntoView({ block: 'center', behavior: 'smooth' }); } catch (e) {}
        }
        setTimeout(place, 450);
    }
    function next() {
        if (idx >= active.length - 1) { endTour(true); return; }
        show(idx + 1);
    }
    function back() { if (idx > 0) show(idx - 1); }
    function endTour(done) {
        spot.style.display = 'none';
        tip.style.display = 'none';
        if (done) { try { localStorage.setItem(FLAG, '1'); } catch (e) {} }
    }
    document.getElementById('pasTipNext').addEventListener('click', next);
    document.getElementById('pasTipBack').addEventListener('click', back);
    document.getElementById('pasTipSkip').addEventListener('click', function () { endTour(true); });
    var raf = null;
    window.addEventListener('scroll', function () {
        if (tip.style.display !== 'block') return;
        if (raf) cancelAnimationFrame(raf);
        raf = requestAnimationFrame(place);
    }, { passive: true });
    window.addEventListener('resize', function () {
        if (tip.style.display === 'block') place();
    });

    function startTour() {
        collect();
        if (!active.length) return;
        idx = 0;
        show(0);
    }
    window.pasTourReplay = function () {
        if (lockVisible()) return;
        startTour();
    };

    // Otomatis sekali per perangkat, setelah halaman siap & tidak terkunci.
    try {
        if (localStorage.getItem(FLAG)) return;
    } catch (e) { return; }
    var waited = 0;
    var timer = setInterval(function () {
        waited += 500;
        if (!lockVisible() && document.readyState === 'complete') {
            clearInterval(timer);
            setTimeout(startTour, 900);
        } else if (waited > 20000) {
            clearInterval(timer);
        }
    }, 500);
})();
</script>
@endsection
