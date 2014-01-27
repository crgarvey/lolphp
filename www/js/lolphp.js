/**
 * Created by Robbie Vaughn on 1/26/14.
 */

function getLoadingHtml() {
    var loading = '<div class="progress progress-striped active">';
    loading += '<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Loading...</div>';
    loading += '</div>'
    return loading;
}

function loadContent(url, element, onComplete) {
    $(element).html(getLoadingHtml()).load(url, {}, onComplete);
}

$(function () {
    var cache               = {};
    cache['summoner']       = {};

    $('form.formurl').on('submit', function(e) {
        e.preventDefault();
        var url             = $(this).attr('action');

        url                += '?' + $(this).serialize();
        loadContent(url, 'body');
    });

    $('.summoner-search').autocomplete({
        minLength: 3,
        source: function(request, response) {
            var term = request.term;
            if ( term in cache ) {
                response( cache[ term ] );
                return;
            }

            $.getJSON("/summoner/search", request, function( data, status, xhr ) {
                cache[ term ] = data;
                response( data );
            });
        },
        select: function(event, ui) {
            var value = ui.item.value;

            if (typeof value != 'undefined') {
                loadContent('/summoner/profile?term=' + value, 'body');
            }
        }
    });

    var copy_sel = $('.clipboard');

    // Disables other default handlers on click (avoid issues)
    copy_sel.on('click', function(e) {
        e.preventDefault();
    });

    // Apply clipboard click event
    copy_sel.clipboard({
        path: '/js/jquery.clipboard.swf',

        copy: function() {
            var this_sel = $(this);

            // Return text in closest element (useful when you have multiple boxes that can be copied)
            return this_sel.data('to-copy');
        }
    });
});