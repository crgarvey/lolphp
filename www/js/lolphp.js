/**
 * Created by Robbie Vaughn on 1/26/14.
 */

function getLoadingHtml() {
    var loading = '<div class="progress progress-striped active">';
    loading += '<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>';
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

    $('#summoner-search').autocomplete({
        minLength: 3,
        source: function(request, response) {
            var term = request.term;
            if ( term in cache ) {
                response( cache[ term ] );
                return;
            }

            $.getJSON("/index/search", request, function( data, status, xhr ) {
                cache[ term ] = data;
                response( data );
            });
        },
        select: function(event, ui) {
            var value = ui.item.value;

            if (typeof value != 'undefined') {
                loadContent('/index/summoner?id=' + value, 'body');
            }
        }
    });
});