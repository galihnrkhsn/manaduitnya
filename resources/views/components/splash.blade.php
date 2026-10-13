<style>
    #splash {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #FFFFFF;
        transition: opacity .2s ease;
    }
    #splash.hide { opacity: 0; pointer-events: none; }
    #splash img { width: 120px; height: auto; }
</style>

<div id="splash" aria-hidden="true">
    <img src="{{ asset('icons/mascot.png') }}" alt="" fetchpriority="high">
</div>

<script>
    (function () {
        var el = document.getElementById('splash');
        if (!el) return;

        // Tampil sekali per pembukaan app, bukan di setiap pindah halaman
        try {
            if (sessionStorage.getItem('splash_seen')) { el.remove(); return; }
            sessionStorage.setItem('splash_seen', '1');
        } catch (e) {}

        function hide() {
            el.classList.add('hide');
            setTimeout(function () { el.remove(); }, 250);
        }

        var loaded = new Promise(function (resolve) {
            if (document.readyState === 'complete') resolve();
            else window.addEventListener('load', resolve);
        });

        Promise.all([loaded, document.fonts ? document.fonts.ready : null]).then(hide);
        setTimeout(hide, 5000); // jaga-jaga kalau ada yang menggantung
    })();
</script>
