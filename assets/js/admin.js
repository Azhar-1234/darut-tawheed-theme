jQuery(function ($) {
    $(document).on('click', '.dt-media-pick', function () {
        var box = $(this).closest('.dt-media'), isVideo = box.hasClass('dt-media--video');
        var frame = wp.media({ title: isVideo ? 'Select video' : 'Select image', multiple: false, library: { type: isVideo ? 'video' : 'image' } });
        frame.on('select', function () {
            var a = frame.state().get('selection').first().toJSON();
            box.find('input').val(a.id).trigger('change');
            if (isVideo) {
                box.find('.dt-media__name').text(a.filename || a.title || '');
                box.addClass('has-image');
                return;
            }
            box.find('img').attr('src', (a.sizes && a.sizes.medium ? a.sizes.medium.url : a.url)).show();
            box.addClass('has-image');
        });
        frame.open();
    });

    $(document).on('click', '.dt-media-clear', function () {
        var box = $(this).closest('.dt-media');
        box.find('input').val('').trigger('change');
        box.find('img').hide();
        box.removeClass('has-image');
    });

    $(document).on('click', '.dt-add-row', function () {
        var rep = $(this).closest('.dt-repeater');
        var next = rep.find('.dt-row').length;
        while (rep.find('[name*="[' + next + ']"]').length) { next++; }
        rep.find('.dt-rows').append(rep.find('.dt-row-tpl').html().replace(/__i__/g, next));
    });

    $(document).on('click', '.dt-remove-row', function () {
        var row = $(this).closest('.dt-row'), list = row.parent();
        row.remove();
        rowsSync(list);
    });

    // Multi-image gallery field (slider).
    function galleryIds(box) {
        return box.find('.dt-gallery__item').map(function () { return $(this).data('id'); }).get();
    }
    function galleryAdd(box, id, url) {
        var item = $('<span class="dt-gallery__item"><span class="dt-gallery__num"></span><img alt=""></span>').attr('data-id', id).data('id', id);
        item.find('img').attr('src', url);
        item.append('<button type="button" class="dt-gallery-remove" aria-label="Remove" title="Remove">×</button>');
        item.append('<span class="dt-gallery__tools"><button type="button" data-to="first" title="Move to first">⇤</button><button type="button" data-to="prev" title="Move left">←</button><button type="button" data-to="next" title="Move right">→</button><button type="button" data-to="last" title="Move to last">⇥</button></span>');
        box.find('.dt-gallery__items').append(item);
    }
    // Save the order into the hidden input and refresh serial numbers / disabled buttons.
    function gallerySync(box) {
        var input = box.find('> input'), ids = galleryIds(box).join(',');
        if (input.val() !== ids) { input.val(ids).trigger('change'); }
        var items = box.find('.dt-gallery__item'), n = items.length;
        items.each(function (i) {
            $(this).find('.dt-gallery__num').text(i + 1);
            $(this).find('[data-to="first"], [data-to="prev"]').prop('disabled', i === 0);
            $(this).find('[data-to="next"], [data-to="last"]').prop('disabled', i === n - 1);
        });
        box.find('.dt-gallery__count').text(n ? n + (n === 1 ? ' image' : ' images') : 'No images yet');
    }

    $(document).on('click', '.dt-gallery__tools button', function () {
        var item = $(this).closest('.dt-gallery__item'), list = item.parent(), to = $(this).data('to');
        if (to === 'first') { list.prepend(item); }
        else if (to === 'last') { list.append(item); }
        else if (to === 'prev') { item.insertBefore(item.prev('.dt-gallery__item')); }
        else { item.insertAfter(item.next('.dt-gallery__item')); }
        gallerySync(item.closest('.dt-gallery'));
    });

    $(document).on('click', '.dt-gallery-pick', function () {
        var box = $(this).closest('.dt-gallery');
        var frame = wp.media({ title: 'Select slider images', multiple: 'add', library: { type: 'image' } });
        frame.on('select', function () {
            var have = galleryIds(box);
            frame.state().get('selection').each(function (a) {
                a = a.toJSON();
                if (have.indexOf(a.id) === -1) {
                    galleryAdd(box, a.id, a.sizes && a.sizes.thumbnail ? a.sizes.thumbnail.url : a.url);
                }
            });
            gallerySync(box);
        });
        frame.open();
    });

    $(document).on('click', '.dt-gallery-remove', function () {
        var box = $(this).closest('.dt-gallery');
        $(this).closest('.dt-gallery__item').remove();
        gallerySync(box);
    });


    // WYSIWYG editors inside repeater rows (visible tab only).
    var editorSeq = 0;
    var editorSettings = { tinymce: { toolbar1: 'formatselect,bold,italic,bullist,numlist,link,unlink,undo,redo', toolbar2: '', height: 220 }, quicktags: true, mediaButtons: false };

    function initEditors(scope) {
        if (!window.wp || !wp.editor) { return; }
        $(scope).find('textarea.dt-wysiwyg').each(function () {
            if ($(this).closest('[hidden]').length || $(this).closest('script').length || this.dataset.dtEditor) { return; }
            this.id = this.id || 'dtw' + (++editorSeq);
            this.dataset.dtEditor = '1';
            wp.editor.initialize(this.id, editorSettings);
        });
    }
    function removeEditors(scope) {
        if (!window.wp || !wp.editor) { return; }
        $(scope).find('textarea.dt-wysiwyg').each(function () {
            if (this.dataset.dtEditor) {
                wp.editor.remove(this.id);
                delete this.dataset.dtEditor;
            }
        });
    }

    initEditors(document);

    // Repeater rows: serial numbers, a readable title (first filled text field), YouTube preview, move-button state.
    function youtubeId(url) {
        var m = String(url || '').match(/(?:youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/|live\/)|youtu\.be\/)([\w-]{11})/);
        return m ? m[1] : '';
    }
    function rowTitle(row) {
        var title = '', url = '';
        row.find('.dt-row__body').find('input[type="text"], input[type="url"], input:not([type]), textarea').each(function () {
            var v = $.trim($(this).val() || '');
            if (!v) { return; }
            if (/^https?:\/\//i.test(v)) { url = url || v; } else if (!title) { title = $('<div>').html(v).text().slice(0, 120); }
        });
        var t = row.find('.dt-row__title');
        t.text(title || url || '(empty row)').toggleClass('is-empty', !title && !url);
        var yt = youtubeId(url), img = row.find('.dt-row__thumb');
        if (yt) { img.attr('src', 'https://img.youtube.com/vi/' + yt + '/default.jpg').prop('hidden', false); } else { img.prop('hidden', true).removeAttr('src'); }
    }
    function rowsSync(list) {
        var rows = $(list).children('.dt-row'), n = rows.length;
        rows.each(function (i) {
            var r = $(this);
            r.find('> .dt-row__head .dt-row__num').text(i + 1);
            r.find('> .dt-row__head [data-to="first"], > .dt-row__head [data-to="up"]').prop('disabled', i === 0);
            r.find('> .dt-row__head [data-to="last"], > .dt-row__head [data-to="down"]').prop('disabled', i === n - 1);
            rowTitle(r);
        });
    }
    function setCollapsed(row, collapsed) {
        if (collapsed) { removeEditors(row); }
        row.toggleClass('is-collapsed', collapsed);
        row.find('> .dt-row__body, > .dt-row__foot').prop('hidden', collapsed);
        row.find('> .dt-row__head .dt-toggle-row').attr('aria-expanded', String(!collapsed))
            .find('.dashicons').toggleClass('dashicons-arrow-up-alt2', !collapsed).toggleClass('dashicons-arrow-down-alt2', collapsed);
        if (!collapsed) { initEditors(row); }
    }
    $(document).on('click', '.dt-toggle-row', function () {
        var row = $(this).closest('.dt-row');
        setCollapsed(row, !row.hasClass('is-collapsed'));
    });
    $(document).on('click', '.dt-rows-collapse', function () {
        var c = $(this).data('collapse') === 1 || $(this).data('collapse') === '1';
        $(this).closest('.dt-repeater').find('.dt-rows > .dt-row').each(function () { setCollapsed($(this), c); });
    });
    // Buttons inside the drag bar should click, not start a drag.
    $(document).on('mousedown', '.dt-row__tools', function (e) { e.stopPropagation(); });
    $(document).on('input change', '.dt-row__body input, .dt-row__body textarea', function () { rowTitle($(this).closest('.dt-row')); });

    // Drag to reorder: gallery thumbnails, and repeater rows by their handle bar.
    // Runs again for rows added later, since their galleries are new elements.
    function makeSortable(scope) {
        if (!$.fn.sortable) { return; }
        $(scope).find('.dt-gallery__items').not('.ui-sortable').sortable({
            items: '.dt-gallery__item',
            tolerance: 'pointer',
            placeholder: 'ui-sortable-placeholder',
            update: function () { gallerySync($(this).closest('.dt-gallery')); }
        });
        $(scope).find('.dt-rows').not('.ui-sortable').sortable({
            items: '> .dt-row',
            handle: '.dt-row__head',
            cancel: '.dt-row__tools, .dt-row__tools *',
            axis: 'y',
            tolerance: 'pointer',
            placeholder: 'ui-sortable-placeholder',
            forcePlaceholderSize: true,
            // TinyMCE editors break when their iframe moves in the DOM: remove before, rebuild after.
            start: function (e, ui) { removeEditors(ui.item); },
            stop: function (e, ui) { if (!ui.item.hasClass('is-collapsed')) { initEditors(ui.item); } rowsSync(ui.item.parent()); }
        });
    }
    makeSortable(document);
    $('.dt-gallery').each(function () { gallerySync($(this)); });
    $('.dt-rows').each(function () { rowsSync(this); });

    $(document).on('click', '.dt-add-row', function () {
        var row = $(this).closest('.dt-repeater').find('.dt-rows .dt-row').last();
        initEditors(row);
        makeSortable(row);
        row.find('.dt-gallery').each(function () { gallerySync($(this)); });
        rowsSync(row.parent());
        row[0].scrollIntoView({ block: 'nearest' });
    });

    $(document).on('click', '.dt-move-row', function () {
        var row = $(this).closest('.dt-row'), list = row.parent(), to = $(this).data('to');
        var target = to === 'first' ? list.children('.dt-row').first() : to === 'last' ? list.children('.dt-row').last() : to === 'up' ? row.prev('.dt-row') : row.next('.dt-row');
        if (!target.length || target[0] === row[0]) { return; }
        removeEditors(row);
        if (to === 'first' || to === 'up') { row.insertBefore(target); } else { row.insertAfter(target); }
        if (!row.hasClass('is-collapsed')) { initEditors(row); }
        rowsSync(list);
        row[0].scrollIntoView({ block: 'nearest' });
    });

    $(document).on('click', '.dt-remove-row', function () {
        // The row is already detached here; this only tears down its editors.
        removeEditors($(this).closest('.dt-row'));
    });

    // Colour pickers (Design tab), each with WordPress's built-in "Default" button.
    if ($.fn.wpColorPicker) {
        $('.dt-color').wpColorPicker({ change: function () { $(this).trigger('input'); }, clear: function () { $(this).trigger('input'); } });
    }

    // Unsaved-changes notice in the save bar, and a warning before leaving the page.
    var dirty = false, form = $('.dt-admin__main');
    function markDirty() {
        if (dirty) { return; }
        dirty = true;
        $('.dt-savebar').addClass('is-dirty').find('.dt-savebar__status').text('● Unsaved changes');
    }
    form.on('input change', ':input', markDirty);
    $(document).on('click', '.dt-add-row, .dt-remove-row, .dt-move-row, .dt-gallery__tools button, .dt-gallery-remove', markDirty);
    $('.dt-rows').on('sortupdate', markDirty);
    if (window.tinymce) {
        tinymce.on('AddEditor', function (e) { e.editor.on('change input', markDirty); });
    }
    form.on('submit', function () { dirty = false; });
    $(window).on('beforeunload', function () { if (dirty) { return 'You have unsaved changes.'; } });
    if (/[?&]settings-updated=true/.test(location.search)) {
        $('.dt-savebar__status').text('✓ All changes saved');
    }
});
