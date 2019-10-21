var $b = jQuery.noConflict();

$b(document).ready(function() {
    var $locationSelect = $b('.js-article-form-location');
    var $specificLocationTarget = $b('.js-specific-location-target');
    $locationSelect.on('change', function(e) {
        $b.ajax({
            url: $locationSelect.data('specific-location-url'),
            data: {
                Category: $locationSelect.val()
            },
            success: function (html) {
                if (!html) {
                    $specificLocationTarget.find('select').remove();
                    $specificLocationTarget.addClass('d-none');
                    return;
                }
                // Replace the current field and show
                $specificLocationTarget
                    .html(html)
                    .removeClass('d-none')
            }
        });
    });
});