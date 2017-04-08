(function($) {
    $('.cmb-order-items').sortable({
        handle: 'span',
        placeholder: 'ui-state-highlight',
        forcePlaceholderSize: true,
    });

    $(document).on('widget-updated widget-added', function() {
        $('.cmb-order-items').sortable({
            handle: 'span',
            placeholder: 'ui-state-highlight',
            forcePlaceholderSize: true,
        });
    });
})(jQuery);