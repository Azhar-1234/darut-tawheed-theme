jQuery(function ($) {
    $(document).on('click', '.dt-media-pick', function () {
        var box = $(this).closest('.dt-media');
        var frame = wp.media({ title: 'Select image', multiple: false, library: { type: 'image' } });
        frame.on('select', function () {
            var a = frame.state().get('selection').first().toJSON();
            box.find('input').val(a.id);
            box.find('img').attr('src', (a.sizes && a.sizes.thumbnail ? a.sizes.thumbnail.url : a.url)).show();
        });
        frame.open();
    });

    $(document).on('click', '.dt-media-clear', function () {
        var box = $(this).closest('.dt-media');
        box.find('input').val('');
        box.find('img').hide();
    });

    $(document).on('click', '.dt-add-row', function () {
        var rep = $(this).closest('.dt-repeater');
        var next = rep.find('.dt-row').length;
        while (rep.find('[name*="[' + next + ']"]').length) { next++; }
        rep.find('.dt-rows').append(rep.find('.dt-row-tpl').html().replace(/__i__/g, next));
    });

    $(document).on('click', '.dt-remove-row', function () {
        $(this).closest('.dt-row').remove();
    });

    // Multi-image gallery field (slider).
    function galleryIds(box) {
        return box.find('.dt-gallery__item').map(function () { return $(this).data('id'); }).get();
    }
    function galleryAdd(box, id, url) {
        var item = $('<span class="dt-gallery__item" style="position:relative"></span>').attr('data-id', id).data('id', id);
        item.append($('<img style="width:100px;height:70px;object-fit:cover;display:block">').attr('src', url));
        item.append('<button type="button" class="dt-gallery-remove" style="position:absolute;top:0;right:0;cursor:pointer" aria-label="Remove">×</button>');
        box.find('.dt-gallery__items').append(item);
    }
    function gallerySync(box) { box.find('> input').val(galleryIds(box).join(',')); }

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

    if ($.fn.sortable) {
        $('.dt-gallery__items').sortable({ update: function () { gallerySync($(this).closest('.dt-gallery')); } });
    }

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

    $(document).on('click', '.dt-add-row', function () {
        initEditors($(this).closest('.dt-repeater').find('.dt-rows .dt-row').last());
    });

    $(document).on('click', '.dt-move-row', function () {
        var row = $(this).closest('.dt-row');
        var other = $(this).data('dir') < 0 ? row.prev('.dt-row') : row.next('.dt-row');
        if (!other.length) { return; }
        removeEditors(row);
        removeEditors(other);
        if ($(this).data('dir') < 0) { row.insertBefore(other); } else { row.insertAfter(other); }
        initEditors(row);
        initEditors(other);
    });

    $(document).on('click', '.dt-remove-row', function () {
        // The row is already detached here; this only tears down its editors.
        removeEditors($(this).closest('.dt-row'));
    });
});
