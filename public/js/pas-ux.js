/* PAS-UX: toast, apiFetch(retry+timeout+sesi), upload progress, offline banner, button loading.
   Auto-aktif untuk: form[data-pas-form] (guard double-submit + spinner),
   form[data-pas-upload] (upload XHR dengan progress + batal + retry pesan). */
(function () {
    'use strict';
    if (window.PAS && window.PAS.__ux) return;
    window.PAS = window.PAS || {};
    window.PAS.__ux = true;

    var LOGIN_URL = '/login';
    try {
        var m = document.querySelector('meta[name="pas-login"]');
        if (m) LOGIN_URL = m.getAttribute('content') || LOGIN_URL;
    } catch (e) {}

    /* ---------- Toast ---------- */
    function toast(msg, type) {
        var box = document.getElementById('pas-toasts');
        if (!box) return alert(msg);
        var el = document.createElement('div');
        el.className = 'pas-toast ' + (type || '');
        var ico = type === 'ok' ? 'check-circle-fill' : (type === 'err' ? 'exclamation-triangle-fill' : (type === 'warn' ? 'wifi-off' : 'info-circle-fill'));
        el.innerHTML = '<i class="bi bi-' + ico + '"></i><span></span>';
        el.querySelector('span').textContent = msg;
        box.appendChild(el);
        setTimeout(function () { el.style.opacity = '0'; el.style.transition = 'opacity .3s'; setTimeout(function(){ el.remove(); }, 320); }, 3600);
    }

    /* ---------- Offline banner ---------- */
    function setOffline(off) {
        var b = document.getElementById('pas-offline');
        if (b) b.classList.toggle('on', !!off);
        if (off) toast('Koneksi terputus — menunggu jaringan…', 'warn');
        else if (b && b.dataset.wasOff === '1') toast('Kembali online — menyambungkan…', 'ok');
        if (b) b.dataset.wasOff = off ? '1' : '0';
    }
    window.addEventListener('offline', function () { setOffline(true); });
    window.addEventListener('online', function () { setOffline(false); });
    if (typeof navigator !== 'undefined' && navigator.onLine === false) setOffline(true);

    /* ---------- Sesi habis ---------- */
    var sessionShown = false;
    function sessionExpired() {
        if (sessionShown) return;
        sessionShown = true;
        var s = document.getElementById('pas-session');
        if (s) { s.classList.add('on'); }
        try {
            ['PAS_SESS','pas_session','token','user'].forEach(function (k) { localStorage.removeItem(k); sessionStorage.removeItem(k); });
        } catch (e) {}
        setTimeout(function () { window.location.href = LOGIN_URL; }, 2200);
    }

    /* ---------- apiFetch: timeout + retry backoff + sesi ---------- */
    function sleep(ms) { return new Promise(function (r) { setTimeout(r, ms); }); }
    function apiFetch(url, opts, retry) {
        opts = opts || {};
        var maxRetry = (retry == null ? 2 : retry);
        var attempt = 0;
        function once() {
            attempt++;
            var ctrl = new AbortController();
            var timer = setTimeout(function () { ctrl.abort(); }, opts.timeout || 20000);
            return fetch(url, Object.assign({}, opts, { signal: ctrl.signal, headers: Object.assign({ 'X-Requested-With': 'XMLHttpRequest' }, opts.headers || {}) }))
                .then(function (res) {
                    clearTimeout(timer);
                    if (res.status === 401 || res.status === 419) { sessionExpired(); throw new Error(' sesi-habis'); }
                    if (res.status >= 500 && attempt <= maxRetry) return sleep(700 * attempt).then(once);
                    return res;
                })
                .catch(function (err) {
                    clearTimeout(timer);
                    if (err && err.message === ' sesi-habis') throw err;
                    if (attempt <= maxRetry) return sleep(700 * attempt).then(once);
                    throw err;
                });
        }
        return once();
    }

    /* ---------- Upload modal ---------- */
    var upXhr = null;
    function uploadUI() {
        var ov = document.getElementById('pas-upload');
        return {
            show: function (name) {
                if (!ov) return;
                ov.classList.add('on');
                var t = document.getElementById('pas-upload-name');
                if (t) t.textContent = name || 'Mengunggah berkas…';
                this.set(0, 'Menyiapkan…');
            },
            set: function (pct, label) {
                var f = document.getElementById('pas-upload-fill');
                var p = document.getElementById('pas-upload-pct');
                var s = document.getElementById('pas-upload-status');
                if (f) f.style.width = pct + '%';
                if (p) p.textContent = Math.round(pct) + '%';
                if (s && label) s.textContent = label;
                var ring = document.getElementById('pas-upload-fg');
                if (ring) { var C = 2 * Math.PI * 17; ring.style.strokeDasharray = C; ring.style.strokeDashoffset = C * (1 - pct / 100); }
            },
            hide: function () { if (ov) ov.classList.remove('on'); upXhr = null; }
        };
    }
    window.pasCancelUpload = function () {
        try { if (upXhr) upXhr.abort(); } catch (e) {}
        uploadUI().hide();
        toast('Unggahan dibatalkan', 'warn');
    };
    window.pasCloseUpload = function () { uploadUI().hide(); };

    function fileSummary(form) {
        var names = [];
        form.querySelectorAll('input[type="file"]').forEach(function (inp) {
            Array.prototype.forEach.call(inp.files || [], function (f) { names.push(f.name); });
        });
        return names.slice(0, 2).join(', ') + (names.length > 2 ? ' (+' + (names.length - 2) + ' lagi)' : '');
    }

    /* ---------- Auto-enhance forms ---------- */
    function loadingBtn(form, on) {
        var btn = form.querySelector('[type="submit"]');
        if (!btn) return null;
        if (on) { btn.dataset.pasLabel = btn.innerHTML; btn.classList.add('pas-btn-loading'); btn.disabled = true; }
        else { if (btn.dataset.pasLabel) btn.innerHTML = btn.dataset.pasLabel; btn.classList.remove('pas-btn-loading'); btn.disabled = false; }
        return btn;
    }

    document.addEventListener('submit', function (ev) {
        var form = ev.target;
        if (!form || form.tagName !== 'FORM' || form.dataset.pasBusy === '1') return;
        if (!form.hasAttribute('data-pas-form') && !form.hasAttribute('data-pas-upload')) return;
        if (form.hasAttribute('data-pas-upload')) {
            ev.preventDefault();
            if (navigator.onLine === false) { toast('Offline — unggahan menunggu koneksi. Coba lagi setelah online.', 'warn'); return; }
            form.dataset.pasBusy = '1';
            var btn = loadingBtn(form, true);
            var ui = uploadUI();
            ui.show(fileSummary(form) || 'Mengunggah…');
            var xhr = new XMLHttpRequest();
            upXhr = xhr;
            xhr.open(form.method || 'POST', form.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.upload.addEventListener('progress', function (e) {
                if (e.lengthComputable) ui.set(e.loaded / e.total * 100, 'Mengunggah… ' + Math.round(e.loaded / 1024) + ' / ' + Math.round(e.total / 1024) + ' KB');
            });
            xhr.addEventListener('load', function () {
                form.dataset.pasBusy = '0'; loadingBtn(form, false);
                if (xhr.status === 401 || xhr.status === 419) { ui.hide(); sessionExpired(); return; }
                if (xhr.status >= 200 && xhr.status < 300) {
                    ui.set(100, 'Berhasil! Memuat ulang…');
                    var redirect = null;
                    try { var j = JSON.parse(xhr.responseText); redirect = j.redirect || j.url || null; } catch (e) {}
                    setTimeout(function () { ui.hide(); toast('Berhasil diunggah', 'ok'); if (redirect) window.location.href = redirect; else window.location.reload(); }, 600);
                } else {
                    ui.hide();
                    toast('Gagal mengunggah (kode ' + xhr.status + '). Periksa koneksi lalu coba lagi.', 'err');
                }
            });
            xhr.addEventListener('error', function () { form.dataset.pasBusy = '0'; loadingBtn(form, false); ui.hide(); toast('Jaringan bermasalah — coba lagi.', 'err'); });
            xhr.addEventListener('abort', function () { form.dataset.pasBusy = '0'; loadingBtn(form, false); });
            xhr.send(new FormData(form));
        } else {
            // form biasa: cegah double-submit + spinner (biarkan submit normal)
            form.dataset.pasBusy = '1';
            loadingBtn(form, true);
            setTimeout(function () { form.dataset.pasBusy = '0'; loadingBtn(form, false); }, 15000); // pengaman bila navigasi gagal
        }
    }, true);

    /* ---------- Echo/Reverb reconnect notice (best-effort) ---------- */
    var echoWarned = false;
    var echoTimer = setInterval(function () {
        try {
            var conn = window.Echo && window.Echo.connector && (window.Echo.connector.pusher ? window.Echo.connector.pusher.connection : null);
            if (!conn) return;
            var st = conn.state;
            if ((st === 'disconnected' || st === 'failed' || st === 'unavailable') && !echoWarned) {
                echoWarned = true;
                toast('Koneksi realtime terputus — mencoba menyambung ulang otomatis…', 'warn');
                try { conn.connect(); } catch (e) {}
                setTimeout(function () { echoWarned = false; }, 30000);
            }
        } catch (e) {}
        if (echoWarned && window.Echo) { /* menunggu pulih */ }
    }, 5000);
    if (echoTimer && window.__pasUxStop) clearInterval(echoTimer);

    window.PAS.toast = toast;
    window.PAS.apiFetch = apiFetch;
    window.PAS.sessionExpired = sessionExpired;
})();
