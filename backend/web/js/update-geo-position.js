$(document).ready(function () {		 

	modelName = $('#modelName').val();
	
	latitude = $('#' + modelName + '-latitude').val();
	longitude = $('#' + modelName + '-longitude').val();
	
	latLng = new google.maps.LatLng(latitude, longitude);

	map = new google.maps.Map(document.getElementById('map'), {
        zoom: 6,
    });
	
	if (latitude && longitude) {
		map.setCenter(latLng); 
	} else {
		map.setCenter({lat: 55.7, lng: 37.6});
	}
	
	var marker = new google.maps.Marker({
	});	
	if (latitude && longitude) {
        marker.setPosition(latLng);
	}
	marker.setMap(map);
	
    google.maps.event.addListener(map, 'click', function (event) {        
        marker.setPosition(event.latLng);
        $('#' + modelName + '-latitude').val(event.latLng.lat());
        $('#' + modelName + '-longitude').val(event.latLng.lng());
    });
})