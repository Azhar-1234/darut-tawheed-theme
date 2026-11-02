(function () {
    var toggle = document.querySelector('.nav-toggle');
    var nav = document.getElementById('primary-nav');
    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            var open = nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        nav.querySelectorAll('.menu-item-has-children > a').forEach(function (a) {
            a.addEventListener('click', function (e) {
                if (window.matchMedia('(max-width: 900px)').matches) {
                    e.preventDefault();
                    a.parentNode.classList.toggle('is-open');
                }
            });
        });
    }

    document.querySelectorAll('[data-slider]').forEach(function (root) {
        var slides = root.querySelectorAll('.hero-slider__slide');
        if (slides.length < 2) { return; }
        var i = 0, timer;
        function show(n) {
            slides[i].classList.remove('is-active');
            i = (n + slides.length) % slides.length;
            slides[i].classList.add('is-active');
        }
        function start() { timer = setInterval(function () { show(i + 1); }, 5000); }
        root.querySelectorAll('[data-dir]').forEach(function (b) {
            b.addEventListener('click', function () {
                clearInterval(timer);
                show(i + parseInt(b.getAttribute('data-dir'), 10));
                start();
            });
        });
        start();
    });
})();
