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

//Edit pin form validation
(function () {
  var $ = jQuery.noConflict();
  $(document).ready(function () {

    $('#pin-edit').submit(function(e) {
      $('.pin-edit__validation').remove();

      var fields = $(this).serializeArray();
      var required = ['name', 'address', 'lat'];

      $.each(fields, function(i, field) {
        var name = field.name;
        if ($.inArray(name, required) != -1 && field.value === "") {
          var fieldName = (name === 'lat') ? 'Map pin' : name.charAt(0).toUpperCase() + name.slice(1);
          setValidation(fieldName + ' is required');
        } 
      });

      if ($('input[name="filters[]"]:checked').length === 0) {
        setValidation('How are you involved is required');
      };

      if ($('.pin-edit__validation').length === 0) return;

      e.preventDefault();
    });

    function setValidation(msg) {
      $('#pin-edit').prepend('<p class="pin-edit__validation">' + msg + '</p>');
      $('html, body').animate({ scrollTop: 0 }, 'fast');
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
          $(`#${input.id}-preview`).css('background-image', 'url(' + e.target.result + ')');
          $(`#${input.id}-preview`).addClass('pin-edit__upload--active');
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

//TODO: should change default lat and lng, other map too

//Initialise google map on pin edit page
window.initMap = () => {
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
      zoom: pinExists ? 18 : 4,
      minZoom: 3
    })

    if (pinExists) {
      marker = createMarker(location, map)
    }

    var input = document.getElementById('address')
    var autocomplete = new google.maps.places.Autocomplete(input)

    autocomplete.bindTo('bounds', map);

    autocomplete.addListener('place_changed', () => {
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

      updateLatLngValues(marker)

      marker.addListener('dragend', () => {
        updateLatLngValues(marker)
      })
    })

    marker && marker.addListener('dragend', () => {
      updateLatLngValues(marker)
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

function updateLatLngValues(marker) {
  var pos = marker.getPosition()
  document.getElementById('lat').value = pos.lat()
  document.getElementById('lng').value = pos.lng()
}