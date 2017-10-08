// Avoid `console` errors in browsers that lack a console.
(function () {
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

var formLabels = {
  'WORKSHOP' : {
    'name-label': 'Pick a name for your pin',
    'description-label': 'Tell us bit about workspace, what machines you have and products you make',
    'website-label': 'Put a link to your website or even better your Bazar account',
    'status-label': 'Can people drop by your workspace?',
    'image-label': 'Share some images of your workspace/machines/team (900 x 525 px). *Pin without images are not approved'
  },
  'MACHINE' : {
    'name-label': 'Pick a name for your pin',
    'description-label': 'Tell us about yourself and what machines you can build',
    'website-label': 'Put a link to your website or even better your Bazar account',
    'status-label': 'Can people drop by your workspace?',
    'image-label': 'Share some images of you or your workspace (900 x 525 px)'
  },
  'STARTED' : {
    'name-label': 'What’s your name',
    'description-label': 'Tell a bit about yourself, your skills or things you can offer',
    'website-label': 'Put a link to your Facebook/twitter/website',
    'status-label': 'Are you up to meet people in real life?',
    'image-label': 'Upload a picture of yourself so we can make this place a bit more friendly (900 x 525 px)'
  }
};

(function () {
  var $ = jQuery.noConflict();
  $(document).ready(function () {

    $('#pin-type input').click(function () {
      changeLabels($(this).attr('value'));
    });

    var selectedType = $('#pin-type input:checked').val();
    changeLabels(selectedType ? selectedType : 'WORKSHOP');
  });

  function changeLabels(type) {
    var labels = formLabels[type];
    $.each(Object.keys(labels), function(index, value) {
      $('#' + value).html(labels[value]);
    });
  }
}());


//Edit pin form validation
(function () {
  var $ = jQuery.noConflict();
  $(document).ready(function () {

    $('#pin-edit').submit(function(e) {
      valAddress();
      valName();
      valMap();
      valFilters();
      valWebsite();

      scrollToFirstError();
      if (!$('.pin-edit__error').is(':visible')) return;
      e.preventDefault();
    });

    $('input[name=address]').blur(valAddress);
    $('input[name=lat]').change(valMap);
    $('input[name=name]').blur(valName);
    $('input[name="filters[]"]').change(valFilters);
    $('input[name=website]').blur(valWebsite);

    function valAddress() {
      isEmpty('input[name=address]') ? showError('address') : hideError('address');
    }

    function valName() {
      isEmpty('input[name=name]') ? showError('name') : hideError('name');
    }

    function valMap() {
      isEmpty('input[name=lat]') || isEmpty('input[name=lng]') ? showError('map') :hideError('map');
    }

    function valFilters() {
      $('input[name="filters[]"]:checked').length === 0 ? showError('filters') : hideError('filters');
    }

    function valWebsite() {
      !isUrlValid($('input[name=website]').val()) ? showError('website') : hideError('website');
    }

    function isEmpty(el) {
      return $(el).val() === '';
    }

    function isUrlValid(url) {
      return url === '' || /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
    }

    function hideError(name) {
      $('.pin-edit__error--' + name).hide();
      $('input[id=' + name + ']').removeClass('pin-edit__error--outline');

      if (name === 'map') {
        $('#pin-edit-map').removeClass('pin-edit__error--outline');
      }
    }

    function showError(name) {
      $('.pin-edit__error--' + name).show();
      $('input[id=' + name + ']').addClass('pin-edit__error--outline');

      if (name === 'map') {
        $('#pin-edit-map').addClass('pin-edit__error--outline');
      }
    }

    function scrollToFirstError() {
      if ($('.pin-edit__error:visible').length === 0) return;
      var location = $('.pin-edit__error:visible').first().parent().offset().top - 80;
      $('html, body').animate({ scrollTop: location }, 'fast');
    }
  });
}());

//Handle image preview in pin edit form
(function () {
  var $ = jQuery.noConflict();
  $(document).ready(function() {

    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $('#' + input.id + '-preview').css('background-image', 'url(' + e.target.result + ')');
          $('#' + input.id + '-preview').addClass('pin-edit__upload--active');
        }

        reader.readAsDataURL(input.files[0]);
      }
    }

    $("input[id^='img']").change(function () {
      readURL(this);
    });

    $("label[id^='img']").keydown(function (e) {
      if (e.which === 13 || e.which === 32) {
        $(this).click();
      }
    });

  });
}());

//Initialise google map on pin edit page
window.initMap = function() {
  var $ = jQuery.noConflict()
  $(document).ready(function () {
    var mapElement = document.getElementById('pin-edit-map')
    if (!mapElement) return

    var marker = null

    var lat = document.getElementById('lat').value
    var lng = document.getElementById('lng').value
    var pinExists = lat && lng
    var location = {
      lat: parseFloat(lat) || 52.373,
      lng: parseFloat(lng) || 4.8925
    }

    var map = new google.maps.Map(mapElement, {
      mapTypeControlOptions: { mapTypeIds: [] },
      center: location,
      zoom: pinExists ? 18 : 1,
      minZoom: 1
    })

    if (pinExists) {
      marker = createMarker(location, map)
    }

    var input = document.getElementById('address')
    var autocomplete = new google.maps.places.Autocomplete(input)

    autocomplete.bindTo('bounds', map);

    autocomplete.addListener('place_changed', function() {
      var place = autocomplete.getPlace()
      if (!place.geometry) return

      marker && marker.setMap(null)
      marker = createMarker(place.geometry.location, map)

      var bounds = new google.maps.LatLngBounds()
      if (place.geometry.viewport) {
        bounds.union(place.geometry.viewport)
      } else {
        bounds.extend(place.geometry.location)
      }
      map.fitBounds(bounds)

      updateLatLngValues($, marker)

      marker.addListener('dragend', function() {
        updateLatLngValues($, marker)
      })
    })

    marker && marker.addListener('dragend', function() {
      updateLatLngValues($, marker)
    })
  })
}

function createMarker(location, map) {
  return new google.maps.Marker({
    map: map,
    draggable: true,
    position: location
  })
}

function updateLatLngValues($, marker) {
  var pos = marker.getPosition()
  $('#lat').val(pos.lat())
  $('#lng').val(pos.lng())
  $('#lat').trigger('change') //to activate validation
}
