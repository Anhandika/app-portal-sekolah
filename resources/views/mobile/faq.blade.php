@extends('layouts.mobile-app')

@section('content')
<div class="pui-topbar">
    @if(session('user_id'))
    <a href="{{ route('profile.show') }}" class="back"><i class="bi bi-chevron-left"></i> Profil</a>
    @else
    <a href="{{ url('/') }}" class="back"><i class="bi bi-chevron-left"></i> Beranda</a>
    @endif
    <h1>Pusat Bantuan</h1>
</div>

<div class="p-3">
    <div class="pui-card p-3 mb-3" style="background:var(--grad-primary);color:#fff;border:none;">
        <h4 class="fw-bold mb-2">Ada pertanyaan?</h4>
        <p class="mb-0 small opacity-75">Temukan jawaban cepat seputar akun, privasi, kebijakan konten, dan akademik di bawah ini.</p>
    </div>

    <div class="pui-card p-2 mb-3 d-flex align-items-center gap-2">
        <i class="bi bi-search text-muted ps-2"></i>
        <input id="faqSearch" class="form-control border-0 shadow-none" style="font-size:13px;background:transparent;" placeholder="Cari: privasi, logout, SPP, bullying..." oninput="filterFaq()">
    </div>

    <div class="d-flex gap-2 mb-3 overflow-auto pb-1" id="faqCats" style="scrollbar-width:none;">
        <button class="pui-chip pui-chip-primary flex-shrink-0 border-0" data-cat="all" onclick="setCat('all',this)">Semua</button>
        @foreach(collect($faqs)->pluck('cat')->unique()->values() as $cat)
        <button class="pui-chip flex-shrink-0 border-0" data-cat="{{ $cat }}" onclick="setCat('{{ $cat }}',this)">{{ $cat }}</button>
        @endforeach
    </div>

    <div class="stagger" id="faqList">
        @foreach($faqs as $index => $faq)
        <div class="pui-card mb-3 overflow-hidden faq-item" data-cat="{{ $faq['cat'] }}" data-text="{{ strtolower($faq['q'].' '.$faq['a']) }}">
            <div class="p-3 d-flex align-items-center justify-content-between"
                 onclick="toggleFaq({{ $index }})" style="cursor:pointer;">
                <div class="pe-3" style="font-size:14px;line-height:1.4;">
                    <span class="pui-chip pui-chip-primary mb-1" style="font-size:10px;padding:4px 10px;">{{ $faq['cat'] }}</span>
                    <div class="fw-bold">{{ $faq['q'] }}</div>
                </div>
                <i id="icon-{{ $index }}" class="bi bi-chevron-down text-muted"></i>
            </div>
            <div id="ans-{{ $index }}" class="px-3 pb-3 small text-muted border-top pt-3" style="display:none;line-height:1.7;">
                {{ $faq['a'] }}
            </div>
        </div>
        @endforeach
    </div>
    <div id="faqEmpty" class="pui-empty" style="display:none;">
        <div class="ico"><i class="bi bi-search"></i></div>
        <h4>Tidak ditemukan</h4>
        <p>Coba kata kunci lain atau hubungi guru pembimbing Anda.</p>
    </div>

    <div class="text-center mt-5 mb-4 px-4 py-4" style="background:#f8fafc; border-radius:var(--radius-md); border:1px dashed var(--line-strong);">
        <i class="bi bi-person-video3 text-primary mb-2" style="font-size:28px;"></i>
        <p class="small text-muted mb-0">Jika masih butuh bantuan atau memiliki kendala yang tidak tercantum di atas, silakan tanyakan kepada <strong>guru pembimbing</strong> Anda.</p>
    </div>
</div>

<script>
var activeCat = 'all';
function toggleFaq(i) {
    var ans = document.getElementById('ans-' + i);
    var icon = document.getElementById('icon-' + i);
    if (ans.style.display === 'none') {
        ans.style.display = 'block';
        icon.classList.replace('bi-chevron-down', 'bi-chevron-up');
    } else {
        ans.style.display = 'none';
        icon.classList.replace('bi-chevron-up', 'bi-chevron-down');
    }
}
function setCat(cat, btn) {
    activeCat = cat;
    document.querySelectorAll('#faqCats .pui-chip').forEach(function(b) {
        b.classList.remove('pui-chip-primary');
    });
    btn.classList.add('pui-chip-primary');
    filterFaq();
}
function filterFaq() {
    var kw = (document.getElementById('faqSearch').value || '').toLowerCase().trim();
    var shown = 0;
    document.querySelectorAll('.faq-item').forEach(function(el) {
        var okCat = (activeCat === 'all' || el.getAttribute('data-cat') === activeCat);
        var okKw = (!kw || el.getAttribute('data-text').indexOf(kw) !== -1);
        var show = okCat && okKw;
        el.style.display = show ? '' : 'none';
        if (show) shown++;
    });
    document.getElementById('faqEmpty').style.display = shown ? 'none' : 'block';
}
</script>
@endsection
