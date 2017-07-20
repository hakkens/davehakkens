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

// Place any jQuery/helper plugins in here.

(function () {
  var $ = jQuery.noConflict();
  $(document).ready(function () {

    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $(`#${input.id}-preview`).css('background-image', 'url(' + e.target.result + ')');
          $(`#${input.id}-preview .pin-edit__upload__icon`).css('opacity', '0');
          $(`#${input.id}-preview .pin-edit__upload__desc`).css('opacity', '0');
        }

        reader.readAsDataURL(input.files[0]);
      }
    }

    $("input[id^='img']").change(function () {
      readURL(this);
    });

  });
}());

window.initMap = () => {
  var $ = jQuery.noConflict()
  $(document).ready(function () {
    var mapElement = document.getElementById('pin-edit-map');
    var defaultLocation = {
      lat: 52.373,
      lng: 4.8925
    } //TODO: should change this, other map too
    var searchMarkers = []

    var map = new google.maps.Map(mapElement, {
      mapTypeControlOptions: {
        mapTypeIds: []
      },
      center: defaultLocation,
      zoom: 4,
      minZoom: 3
    })

    var input = document.getElementById('address')
    var searchBox = new google.maps.places.SearchBox(input)

    map.addListener('bounds_changed', () => {
      searchBox.setBounds(map.getBounds())
    })

    searchBox.addListener('places_changed', () => {
      var places = searchBox.getPlaces()
      if (places.length === 0) return

      resetSearchMarkers(searchMarkers)
      searchMarkers = []

      var bounds = new google.maps.LatLngBounds()
      places.forEach(place => {
        if (!place.geometry) return

        createSearchMarker(place, searchMarkers, map)

        if (place.geometry.viewport) {
          bounds.union(place.geometry.viewport)
        } else {
          bounds.extend(place.geometry.location)
        }
      })
      map.fitBounds(bounds)
    })
  })
}

function resetSearchMarkers(markers) {
  markers.forEach(marker => marker.setMap(null))
}

function createSearchMarker(place, searchMarkers, map) {
  var icon = {
    url: place.icon,
    size: new google.maps.Size(71, 71),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(17, 34),
    scaledSize: new google.maps.Size(25, 25)
  }

  searchMarkers.push(new google.maps.Marker({
    map: map,
    icon: icon,
    title: place.name,
    position: place.geometry.location
  }))
}
