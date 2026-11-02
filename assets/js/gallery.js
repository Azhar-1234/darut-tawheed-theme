(function () {
    var grid = document.querySelector('[data-lightbox]');
    if (!grid) { return; }
    var links = Array.prototype.slice.call(grid.querySelectorAll('a'));
    var index = 0, startX = null, box, img, cap, count;

    function build() {
        box = document.createElement('div');
        box.className = 'lb';
        box.setAttribute('role', 'dialog');
        box.setAttribute('aria-modal', 'true');
        box.hidden = true;
        box.innerHTML = '<button class="lb__close" aria-label="বন্ধ করুন">&times;</button>' +
            '<button class="lb__nav lb__nav--prev" aria-label="আগের ছবি">&#10094;</button>' +
            '<figure class="lb__fig"><img alt=""><figcaption></figcaption></figure>' +
            '<button class="lb__nav lb__nav--next" aria-label="পরের ছবি">&#10095;</button>' +
            '<span class="lb__count"></span>';
        document.body.appendChild(box);
        img = box.querySelector('img');
        cap = box.querySelector('figcaption');
        count = box.querySelector('.lb__count');
        box.querySelector('.lb__close').addEventListener('click', close);
        box.querySelector('.lb__nav--prev').addEventListener('click', function (e) { e.stopPropagation(); go(-1); });
        box.querySelector('.lb__nav--next').addEventListener('click', function (e) { e.stopPropagation(); go(1); });
        box.addEventListener('click', function (e) { if (e.target === box || e.target.classList.contains('lb__fig')) { close(); } });
        box.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
        box.addEventListener('touchend', function (e) {
            if (startX === null) { return; }
            var dx = e.changedTouches[0].clientX - startX;
            if (Math.abs(dx) > 50) { go(dx < 0 ? 1 : -1); }
            startX = null;
        });
    }

    function bn(n) { return String(n).replace(/[0-9]/g, function (c) { return '০১২৩৪৫৬৭৮৯'.charAt(c); }); }

    function show(i) {
        index = (i + links.length) % links.length;
        var a = links[index];
        img.src = a.href;
        img.alt = (a.querySelector('img') || {}).alt || '';
        cap.textContent = a.getAttribute('data-caption') || '';
        count.textContent = bn(index + 1) + ' / ' + bn(links.length);
        // Warm the neighbours so sliding feels instant.
        [1, -1].forEach(function (d) { (new Image()).src = links[(index + d + links.length) % links.length].href; });
    }

    function open(i) {
        if (!box) { build(); }
        box.hidden = false;
        document.body.classList.add('lb-open');
        show(i);
        box.querySelector('.lb__close').focus();
    }
    function close() { box.hidden = true; document.body.classList.remove('lb-open'); img.removeAttribute('src'); }
    function go(d) { show(index + d); }

    links.forEach(function (a, i) {
        a.addEventListener('click', function (e) { e.preventDefault(); open(i); });
    });
    document.addEventListener('keydown', function (e) {
        if (!box || box.hidden) { return; }
        if (e.key === 'Escape') { close(); }
        else if (e.key === 'ArrowRight') { go(1); }
        else if (e.key === 'ArrowLeft') { go(-1); }
    });
})();
