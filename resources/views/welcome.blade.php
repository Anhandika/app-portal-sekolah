<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="theme-color" content="#0f172a">
<title>PAS - Portal Academy Sekolah — Platform Digital Antar Sekolah</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Inter',system-ui,-apple-system,sans-serif;background:#f6f7fb;color:#0f172a;overflow-x:hidden}

/* ===== HERO ===== */
.hero{background:linear-gradient(135deg,#0f172a 0%,#1e1b4b 40%,#991b1b 100%);color:#fff;position:relative;overflow:hidden}
.hero::before{content:'';position:absolute;top:-120px;right:-80px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(220,38,38,.25),transparent 70%)}
.hero::after{content:'';position:absolute;bottom:-100px;left:-60px;width:350px;height:350px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.2),transparent 70%)}

/* Nav */
.nav{max-width:1120px;margin:0 auto;padding:18px 24px;display:flex;align-items:center;gap:14px;position:relative;z-index:2}
.nav-logo{width:48px;height:48px;border-radius:14px;overflow:hidden;flex-shrink:0}
.nav-logo img{width:100%;height:100%;object-fit:contain}
.nav-brand{font-weight:900;font-size:17px;letter-spacing:-.02em}
.nav-brand span{color:#fca5a5}
.nav-ver{font-size:10px;opacity:.5;margin-left:4px;background:rgba(255,255,255,.1);padding:3px 8px;border-radius:999px;font-weight:700}
.nav-links{margin-left:auto;display:flex;gap:10px}
.btn{appearance:none;border:0;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px;padding:11px 20px;border-radius:14px;font-weight:800;font-size:13px;transition:all .2s}
.btn:active{transform:scale(.96)}
.btn-ghost{background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.18);backdrop-filter:blur(8px)}
.btn-primary{background:#fff;color:#991b1b;box-shadow:0 10px 28px rgba(15,23,42,.2)}

/* Hero Main */
.hero-main{max-width:1120px;margin:0 auto;padding:48px 24px 56px;display:grid;grid-template-columns:1.1fr .9fr;gap:36px;align-items:center;position:relative;z-index:2}
@media(max-width:900px){.hero-main{grid-template-columns:1fr;padding:32px 20px 40px}.nav-links .btn-primary{display:none}}
.eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:11px;letter-spacing:.14em;opacity:.7;font-weight:800;text-transform:uppercase;margin-bottom:12px}
.eyebrow i{font-size:14px}
.h1{font-size:42px;font-weight:900;letter-spacing:-.04em;line-height:1.05}
@media(max-width:640px){.h1{font-size:32px}}
.h1 .highlight{background:linear-gradient(135deg,#fca5a5,#fbbf24);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.lead{font-size:15px;opacity:.8;line-height:1.7;margin-top:16px;max-width:480px}
.hero-cta{display:flex;gap:12px;margin-top:28px;flex-wrap:wrap}
.hero-cta .btn-primary{padding:14px 28px;font-size:14px;border-radius:16px}
.hero-cta .btn-ghost{padding:14px 28px;font-size:14px;border-radius:16px}
.hero-stats{display:flex;gap:24px;margin-top:28px}
.hero-stat{text-align:center}
.hero-stat .num{font-size:28px;font-weight:900;letter-spacing:-.02em}
.hero-stat .lab{font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;opacity:.5;margin-top:2px}

/* Logo showcase */
.hero-logo-showcase{display:flex;justify-content:center;align-items:center}
.logo-big{width:280px;height:280px;border-radius:40px;overflow:hidden;background:rgba(255,255,255,.06);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,.12);box-shadow:0 30px 80px rgba(0,0,0,.3);display:grid;place-items:center;padding:20px}
.logo-big img{width:100%;height:100%;object-fit:contain}
@media(max-width:640px){.logo-big{width:200px;height:200px;border-radius:32px}}

/* ===== FEATURES SECTION ===== */
.section{max-width:1120px;margin:0 auto;padding:40px 24px}
.section-label{font-size:11px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:#dc2626;margin-bottom:8px}
.section-title{font-size:28px;font-weight:900;letter-spacing:-.03em;line-height:1.1}
.section-desc{font-size:14px;color:#64748b;line-height:1.6;margin-top:8px;max-width:560px}

/* Feature Grid */
.feat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:24px}
@media(max-width:900px){.feat-grid{grid-template-columns:1fr 1fr}}
@media(max-width:640px){.feat-grid{grid-template-columns:1fr}}
.feat-card{background:#fff;border:1px solid rgba(15,23,42,.06);border-radius:20px;padding:24px;box-shadow:0 6px 20px rgba(15,23,42,.04);transition:all .3s}
.feat-card:hover{transform:translateY(-4px);box-shadow:0 14px 34px rgba(15,23,42,.1)}
.feat-icon{width:48px;height:48px;border-radius:14px;display:grid;place-items:center;color:#fff;font-size:20px;margin-bottom:14px;box-shadow:0 6px 16px rgba(0,0,0,.12)}
.feat-card h3{font-size:16px;font-weight:800;letter-spacing:-.01em}
.feat-card p{font-size:12px;color:#64748b;line-height:1.5;margin-top:6px}

/* Steps */
.steps-section{background:#fff;border:1px solid rgba(15,23,42,.06);border-radius:28px;box-shadow:0 12px 30px rgba(15,23,42,.05);margin-top:12px;padding:40px 32px}
.steps{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-top:20px}
@media(max-width:900px){.steps{grid-template-columns:1fr 1fr}}
@media(max-width:640px){.steps{grid-template-columns:1fr}}
.step{position:relative;background:var(--surface);border:1px solid rgba(15,23,42,.05);border-radius:18px;padding:20px}
.step .num{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;display:grid;place-items:center;font-weight:900;font-size:14px;margin-bottom:12px;box-shadow:0 4px 12px rgba(220,38,38,.3)}
.step h4{font-size:14px;font-weight:800}
.step p{font-size:12px;color:#64748b;margin-top:4px;line-height:1.5}

/* CTA Banner */
.cta-banner{background:linear-gradient(135deg,#0f172a,#1e1b4b);border-radius:28px;padding:40px 36px;color:#fff;text-align:center;position:relative;overflow:hidden;margin-top:32px}
.cta-banner::before{content:'';position:absolute;top:-60px;right:-60px;width:200px;height:200px;border-radius:50%;background:radial-gradient(circle,rgba(220,38,38,.3),transparent)}
.cta-banner h2{font-size:24px;font-weight:900;letter-spacing:-.02em;position:relative;z-index:1}
.cta-banner p{font-size:13px;opacity:.7;margin-top:8px;position:relative;z-index:1}
.cta-buttons{display:flex;gap:10px;justify-content:center;margin-top:20px;position:relative;z-index:1}
.cta-buttons .btn{border-radius:14px;padding:13px 24px}

/* Footer */
.footer{max-width:1120px;margin:0 auto;padding:32px 24px;color:#94a3b8;font-size:11px;text-align:center;border-top:1px solid rgba(15,23,42,.06)}
.footer a{color:#dc2626;text-decoration:none}

/* ===== ANIMATIONS ===== */
.fade-in{opacity:0;transform:translateY(20px);transition:all .6s cubic-bezier(.22,.9,.3,1)}
.fade-in.visible{opacity:1;transform:translateY(0)}
</style>
</head>
<body>

<!-- ===== HERO ===== -->
<div class="hero">
  <nav class="nav">
    <div class="nav-logo"><img src="{{ asset('logo_sekolah.png') }}" alt="PAS"></div>
    <div class="nav-brand">PAS <span>Portal Academy Sekolah</span></div>
    <div class="nav-ver">v2026</div>
    <div class="nav-links">
      <a href="{{ route('login') }}" class="btn btn-ghost"><i class="bi bi-box-arrow-in-right"></i> Masuk</a>
      <a href="{{ route('register') }}" class="btn btn-primary"><i class="bi bi-person-plus"></i> Daftar</a>
    </div>
  </nav>

  <div class="hero-main">
    <div>
      <div class="eyebrow"><i class="bi bi-mortarboard-fill"></i> Platform Digital Antar Sekolah</div>
      <div class="h1">Satu Portal untuk<br><span class="highlight">Seluruh Sekolah</span></div>
      <p class="lead">Absensi, tugas, nilai, SPP, perpustakaan, eskul, chat, hingga Global Portal antar sekolah — dalam satu aplikasi ringan untuk Android & Web.</p>
      <div class="hero-cta">
        <a href="{{ route('login') }}" class="btn btn-primary"><i class="bi bi-box-arrow-in-right"></i> Masuk Portal</a>
        <a href="{{ route('register') }}" class="btn btn-ghost"><i class="bi bi-person-plus"></i> Daftar dengan Kode</a>
      </div>
      <div class="hero-stats">
        <div class="hero-stat"><div class="num">12+</div><div class="lab">Modul Fitur</div></div>
        <div class="hero-stat"><div class="num">100%</div><div class="lab">Digital</div></div>
        <div class="hero-stat"><div class="num">Free</div><div class="lab">Untuk Sekolah</div></div>
      </div>
    </div>
    <div class="hero-logo-showcase">
      <div class="logo-big">
        <img src="{{ asset('logo_sekolah.png') }}" alt="PAS - Portal Academy Sekolah">
      </div>
    </div>
  </div>
</div>

<!-- ===== FITUR UNGGULAN ===== -->
<div class="section fade-in">
  <div class="section-label">Fitur Unggulan</div>
  <div class="section-title">12 Modul Lengkap untuk Sekolah Modern</div>
  <div class="section-desc">Setiap sekolah dapat mengaktifkan modul sesuai kebutuhan. Semua terintegrasi dalam satu platform.</div>

  <div class="feat-grid">
    <div class="feat-card">
      <div class="feat-icon" style="background:linear-gradient(135deg,#2563eb,#3b82f6)"><i class="bi bi-calendar-check"></i></div>
      <h3>Absensi Digital</h3>
      <p>Check-in/out harian dengan face scanner, GPS tracking, dan laporan real-time untuk guru & orang tua.</p>
    </div>
    <div class="feat-card">
      <div class="feat-icon" style="background:linear-gradient(135deg,#7c3aed,#8b5cf6)"><i class="bi bi-book"></i></div>
      <h3>LMS / E-Learning</h3>
      <p>Materi pelajaran, tugas online, pengumpulan tugas dengan deadline, dan input nilai otomatis.</p>
    </div>
    <div class="feat-card">
      <div class="feat-icon" style="background:linear-gradient(135deg,#059669,#10b981)"><i class="bi bi-chat-dots"></i></div>
      <h3>Chat Real-time</h3>
      <p>Pesan instan via WebSocket: chat kelas, kelompok eskul, dan pesan pribadi antar pengguna.</p>
    </div>
    <div class="feat-card">
      <div class="feat-icon" style="background:linear-gradient(135deg,#d97706,#f59e0b)"><i class="bi bi-wallet2"></i></div>
      <h3>Pembayaran SPP</h3>
      <p>Tracking pembayaran iuran sekolah, reminder notifikasi, dan rekap keuangan untuk admin.</p>
    </div>
    <div class="feat-card">
      <div class="feat-icon" style="background:linear-gradient(135deg,#dc2626,#ef4444)"><i class="bi bi-globe2"></i></div>
      <h3>Global Portal</h3>
      <p>Social feed antar sekolah: posting, stories, like, comment, follow — ala Instagram untuk pendidikan.</p>
    </div>
    <div class="feat-card">
      <div class="feat-icon" style="background:linear-gradient(135deg,#0891b2,#22d3ee)"><i class="bi bi-bookmark"></i></div>
      <h3>Perpustakaan Digital</h3>
      <p>Katalog buku online, sistem peminjaman, dan baca buku langsung dari aplikasi.</p>
    </div>
    <div class="feat-card">
      <div class="feat-icon" style="background:linear-gradient(135deg,#db2777,#f472b6)"><i class="bi bi-flag"></i></div>
      <h3>Ekstrakurikuler</h3>
      <p>Manajemen eskul: daftar, approval keanggotaan, chat group per eskul, dan jadwal kegiatan.</p>
    </div>
    <div class="feat-card">
      <div class="feat-icon" style="background:linear-gradient(135deg,#ea580c,#f97316)"><i class="bi bi-bar-chart-line"></i></div>
      <h3>Nilai & Rapor</h3>
      <p>Input nilai guru, rekap rapor otomatis, export PDF/Excel untuk siswa dan orang tua.</p>
    </div>
    <div class="feat-card">
      <div class="feat-icon" style="background:linear-gradient(135deg,#4f46e5,#6366f1)"><i class="bi bi-shield-lock"></i></div>
      <h3>Keamanan App</h3>
      <p>PIN lock, autentikasi biometrik (fingerprint), session monitoring, dan moderasi konten AI.</p>
    </div>
  </div>
</div>

<!-- ===== CARA KERJA ===== -->
<div class="section">
  <div class="steps-section fade-in">
    <div class="section-label">Cara Memulai</div>
    <div class="section-title">4 Langkah Mulai Pakai PAS</div>
    <div class="section-desc">Daftar cepat dengan kode sekolah. Tanpa ribet, langsung bisa akses semua fitur.</div>

    <div class="steps">
      <div class="step">
        <div class="num">1</div>
        <h4>Minta Kode Sekolah</h4>
        <p>Tanyakan kode pendaftaran ke admin sekolah Anda. Kode terdiri dari ID sekolah + kode kota.</p>
      </div>
      <div class="step">
        <div class="num">2</div>
        <h4>Buka Portal PAS</h4>
        <p>Kunjungi halaman registrasi, masukkan kode sekolah. Data sekolah terisi otomatis.</p>
      </div>
      <div class="step">
        <div class="num">3</div>
        <h4>Lengkapi Profil</h4>
        <p>Pilih peran (Siswa/Guru), isi data diri lengkap, dan upload foto profil Anda.</p>
      </div>
      <div class="step">
        <div class="num">4</div>
        <h4>Siap Digunakan!</h4>
        <p>Admin verifikasi → Anda bisa absen, chat, lihat nilai, bayar SPP, dan jelajahi Global Portal.</p>
      </div>
    </div>

    <div style="display:flex;gap:10px;margin-top:24px;flex-wrap:wrap">
      <a href="{{ ($registrationOpen ?? true) ? route('register') : route('login') }}" class="btn" style="background:#dc2626;color:#fff;border-radius:14px;box-shadow:0 8px 20px rgba(220,38,38,.3)">{{ ($registrationOpen ?? true) ? 'Daftar Sekarang' : 'Masuk ke Portal' }}</a>
      <a href="{{ route('help.faq') }}" class="btn" style="background:#f1f5f9;color:#0f172a;border:1px solid #e2e8f0;border-radius:14px">Lihat FAQ</a>
    </div>
  </div>
</div>

<!-- ===== CTA BANNER ===== -->
<div class="section">
  <div class="cta-banner fade-in">
    <h2>Siap Digitalisasi Sekolah Anda?</h2>
    <p>Bergabung dengan PAS dan rasakan kemudahan pengelolaan akademik digital yang terintegrasi.</p>
    <div class="cta-buttons">
      <a href="{{ route('register') }}" class="btn btn-primary" style="background:#fff;color:#991b1b"><i class="bi bi-rocket-takeoff"></i> Mulai Gratis</a>
      <a href="{{ route('download.apk') }}" class="btn btn-ghost"><i class="bi bi-cloud-arrow-down"></i> Download APK</a>
    </div>
  </div>
</div>

<!-- ===== FOOTER ===== -->
<div class="footer">
  &copy; {{ date('Y') }} PAS - Portal Academy Sekolah &bull; Platform Digital Antar Sekolah &bull;
  <a href="{{ route('offline') }}">Offline Mode</a> &bull; Admin Pusat: adminpusat@pusat.com
</div>

<script>
// Fade-in on scroll
var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(e) { if (e.isIntersecting) { e.target.classList.add('visible'); } });
}, { threshold: 0.1 });
document.querySelectorAll('.fade-in').forEach(function(el) { observer.observe(el); });
</script>
</body>
</html>
