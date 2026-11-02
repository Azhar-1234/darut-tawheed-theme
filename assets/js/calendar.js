(function () {
    var root = document.getElementById('dt-calendar');
    var dataEl = document.getElementById('dt-calendar-data');
    if (!root || !dataEl) { return; }

    var events = JSON.parse(dataEl.textContent);
    var todayStr = root.getAttribute('data-today');
    var today = parse(todayStr);
    var year = today.getFullYear(), month = today.getMonth(), view = 'month';

    var MONTHS = ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];
    var DAYS = ['রবি', 'সোম', 'মঙ্গল', 'বুধ', 'বৃহস্পতি', 'শুক্র', 'শনি'];

    function parse(s) { var p = s.split('-'); return new Date(+p[0], +p[1] - 1, +p[2]); }
    function iso(d) { return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0'); }
    function bn(n) { return String(n).replace(/[0-9]/g, function (c) { return '০১২৩৪৫৬৭৮৯'.charAt(c); }); }
    function el(tag, cls, text) {
        var e = document.createElement(tag);
        if (cls) { e.className = cls; }
        if (text !== undefined) { e.textContent = text; }
        return e;
    }
    function btn(label, cls, onClick, aria) {
        var b = el('button', cls, label);
        b.type = 'button';
        if (aria) { b.setAttribute('aria-label', aria); }
        b.addEventListener('click', onClick);
        return b;
    }
    function shift(n) {
        month += n;
        if (month < 0) { month = 11; year--; }
        if (month > 11) { month = 0; year++; }
        render();
    }
    function onDay(dayStr) {
        return events.filter(function (e) { return e.start <= dayStr && dayStr <= e.end; });
    }

    function toolbar() {
        var bar = el('div', 'cal__bar');
        var left = el('div', 'cal__nav');
        left.appendChild(btn('‹', 'cal__btn', function () { shift(-1); }, 'আগের মাস'));
        left.appendChild(btn('›', 'cal__btn', function () { shift(1); }, 'পরের মাস'));
        left.appendChild(btn('আজ', 'cal__btn cal__btn--ghost', function () { year = today.getFullYear(); month = today.getMonth(); render(); }));
        bar.appendChild(left);
        bar.appendChild(el('h2', 'cal__title', MONTHS[month] + ' ' + bn(year)));
        var right = el('div', 'cal__nav');
        right.appendChild(btn('মাস', 'cal__btn' + (view === 'month' ? ' is-active' : ''), function () { view = 'month'; render(); }));
        right.appendChild(btn('তালিকা', 'cal__btn' + (view === 'list' ? ' is-active' : ''), function () { view = 'list'; render(); }));
        bar.appendChild(right);
        return bar;
    }

    function monthView() {
        var grid = el('div', 'cal__grid');
        DAYS.forEach(function (d) { grid.appendChild(el('div', 'cal__dow', d.slice(0, 3))); });
        var first = new Date(year, month, 1);
        var start = new Date(year, month, 1 - first.getDay());
        for (var i = 0; i < 42; i++) {
            var d = new Date(start.getFullYear(), start.getMonth(), start.getDate() + i);
            if (i >= 35 && d.getMonth() !== month) { break; }
            var ds = iso(d);
            var cell = el('div', 'cal__cell' + (d.getMonth() !== month ? ' is-other' : '') + (ds === todayStr ? ' is-today' : '') + (d.getDay() === 0 ? ' is-sun' : ''));
            cell.appendChild(el('span', 'cal__num', bn(d.getDate())));
            onDay(ds).forEach(function (e) {
                var showTitle = e.start === ds || d.getDay() === 0;
                var chip = el('div', 'cal__ev' + (e.hl ? ' cal__ev--hl' : ''), showTitle ? e.title : '\u00a0');
                chip.title = e.title;
                cell.appendChild(chip);
            });
            grid.appendChild(cell);
        }
        return grid;
    }

    function listView() {
        var wrap = el('div', 'cal__list');
        var monthStart = iso(new Date(year, month, 1)), monthEnd = iso(new Date(year, month + 1, 0));
        var inMonth = events.filter(function (e) { return e.start <= monthEnd && e.end >= monthStart; });
        if (!inMonth.length) {
            wrap.appendChild(el('p', 'cal__empty', 'এই মাসে কোনো ইভেন্ট নেই।'));
            return wrap;
        }
        inMonth.forEach(function (e) {
            var row = el('div', 'cal__row' + (e.hl ? ' cal__row--hl' : ''));
            var a = parse(e.start), b = parse(e.end);
            var when = bn(a.getDate()) + ' ' + MONTHS[a.getMonth()];
            if (e.end !== e.start) { when += ' – ' + bn(b.getDate()) + ' ' + MONTHS[b.getMonth()]; }
            row.appendChild(el('span', 'cal__when', when));
            row.appendChild(el('span', 'cal__what', e.title));
            wrap.appendChild(row);
        });
        return wrap;
    }

    function render() {
        root.textContent = '';
        root.appendChild(toolbar());
        root.appendChild(view === 'month' ? monthView() : listView());
    }
    render();
})();
