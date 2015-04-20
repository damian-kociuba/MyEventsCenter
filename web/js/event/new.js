var map;
var geocoder;
var mapOptions = {center: new google.maps.LatLng(0.0, 0.0), zoom: 2,
    mapTypeId: google.maps.MapTypeId.ROADMAP};

function initialize() {
    var myOptions = {
        center: new google.maps.LatLng(52, 20),
        zoom: 6,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    geocoder = new google.maps.Geocoder();
    var map = new google.maps.Map(document.getElementById("googleMap"), myOptions);
    google.maps.event.addListener(map, 'click', function (event) {
        placeMarker(event.latLng);
    });

    var marker;
    function placeMarker(location) {
        if (marker) {
            marker.setPosition(location);
        } else {
            marker = new google.maps.Marker({
                position: location,
                map: map
            });
        }
        $("#eventForm_latitude").val(location.lat());
        $("#eventForm_longitude").val(location.lng());
        getAddress(location);
    }

    function getAddress(latLng) {
        geocoder.geocode({'latLng': latLng},
        function (results, status) {
            var addressToShow;
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    addressToShow = results[0].formatted_address;
                }
                else {
                    addressToShow = "No results";
                }
            }
            else {
                addressToShow = status;
            }
            $("#eventForm_address").val(addressToShow);
        });
    }
}