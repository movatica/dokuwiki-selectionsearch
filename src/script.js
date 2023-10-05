// show a wiki search button for selected text
'use strict';

jQuery(function(){
    const tooltip = jQuery('#selectionsearch__tt');
    if (!tooltip[0]) return;

    const container = jQuery('#dokuwiki__content');

    // mouseup callback for content div
    container.on('mouseup', function(){
        tooltip.hide();

        const selection = window.getSelection();

        const query = selection.toString().trim();
        if (query.length < JSINFO.selectionsearch_minlength) return;

        tooltip.attr('href', '?' + jQuery.param({ id: JSINFO.id, do: 'search', q: query }));

        // for some reason, the selection rectangle is weirdly offset and we need to correct this
        const content_rect = container[0].getBoundingClientRect();
        // for a multiline selection, place the tooltip after the last line
        const selection_rects = selection.getRangeAt(0).getClientRects();
        const selection_rect = selection_rects[selection_rects.length-1];

        tooltip.css({top: selection_rect.bottom-content_rect.top, left: selection_rect.right-content_rect.left});
        tooltip.show();
    });
});
