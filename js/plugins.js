// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeline', 'timelineEnd', 'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

// Place any jQuery/helper plugins in here.

(function() {
var $ = jQuery.noConflict();
$(document).ready(function() {

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $(`#${input.id}-preview`).css('background-image', 'url(' + e.target.result + ')');
                $(`#${input.id}-preview .pin-edit__upload__icon`).css('opacity', '0');
                $(`#${input.id}-preview .pin-edit__upload__desc`).css('opacity', '0');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("input[id^='img']").change(function(){
        readURL(this);
    });

});
}());