// show a wiki search button for selected text
jQuery(function(){
    'use strict';

    var tooltip = jQuery('#selectionsearch__tooltip');
    if (!tooltip[0]) return;

    var container = jQuery('#dokuwiki__content')

    // mouseup callback for content div
    container.mouseup(function(event){
        tooltip.hide();

        var selection = window.getSelection();

        var query = selection.toString().trim();
        if (query.length < JSINFO.selectionsearch_minlength) return;

        var href = '?' + jQuery.param({ id: JSINFO.id, do: 'search', q: query });
        jQuery('#selectionsearch__link').attr('href', href);

        // for some reason, the selection rectangle is weirdly offset and we need to correct this
        var content_rect = container[0].getBoundingClientRect();
        // for a multiline selection, place the tooltip after the last line
        var selection_rects = selection.getRangeAt(0).getClientRects();
        var selection_rect = selection_rects[selection_rects.length-1];

        tooltip.css({top: selection_rect.bottom-content_rect.top, left: selection_rect.right-content_rect.left});
        tooltip.show();
    });
});