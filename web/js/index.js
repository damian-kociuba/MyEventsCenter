



var homepageMap;
var homepageMapMarker;
function initialize() {
    var mapProp = {
        center: new google.maps.LatLng(50.264302, 19.023628),
        zoom: 6,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    homepageMap = new google.maps.Map(document.getElementById("googleMap"), mapProp);
}

function changeLocalization(newAddress) {
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({
        'address': newAddress
    }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            console.log(results[0].geometry.location);
            if (typeof (homepageMapMarker) !== 'undefined') {
                homepageMapMarker.setMap(null);
            }
            homepageMapMarker = new google.maps.Marker({
                map: homepageMap,
                position: results[0].geometry.location
            });

            homepageMap.setCenter(homepageMapMarker.getPosition());
        }
    });
}


var myEventCenterApp = angular.module('MyEventCenterApp', []);
myEventCenterApp.controller('EventBrowserController', ['$scope', '$http', function ($scope, $http) {
        $scope.eventsByAddress = null;
        $scope.searchByAddress = function () {

            geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                'address': $scope.address
            }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var latitude = results[0].geometry.location.lat();
                    var longitude = results[0].geometry.location.lng();

                    $http.post(paths['find_the_closest_events'], {'latitude': latitude, 'longitude': longitude}).
                            success(function (data, status, headers, config) {
                                $scope.eventsByAddress = data.events;
                            }).
                            error(function (data, status, headers, config) {
                                // called asynchronously if an error occurs
                                // or server returns response with an error status.
                            });
                }
            });
        };
        $scope.changeLocalization = function (addr) {
            changeLocalization(addr);
        };

        $scope.searchByUserLocation = function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                $scope.geolocalizationError = "Geolocation is not supported by this browser.";
            }
        };

        function showPosition(position) {
            $http.post(paths['find_the_closest_events'], {'latitude': position.coords.latitude, 'longitude': position.coords.longitude}).
                    success(function (data, status, headers, config) {
                        $scope.eventsByLocation = data.events;
                    }).
                    error(function (data, status, headers, config) {
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                    });

        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    $scope.geolocalizationError = "User denied the request for Geolocation.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    $scope.geolocalizationError = "Location information is unavailable.";
                    break;
                case error.TIMEOUT:
                    $scope.geolocalizationError = "The request to get user location timed out.";
                    break;
                case error.UNKNOWN_ERROR:
                    $scope.geolocalizationError = "An unknown error occurred.";
                    break;
            }
        }
    }]);